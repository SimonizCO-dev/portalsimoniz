<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

//se reciben los parametros para el reporte
$empresa = $model['empresa'];
if (isset($model['motivo_retiro'])) { $motivo_retiro = $model['motivo_retiro']; } else { $motivo_retiro = ""; }
if (isset($model['liquidado'])) { $liquidado = $model['liquidado']; } else { $liquidado = ""; }
if (isset($model['fecha_inicial_fin'])) { $fecha_inicial_fin = $model['fecha_inicial_fin']; } else { $fecha_inicial_fin = ""; }
if (isset($model['fecha_final_fin'])) { $fecha_final_fin = $model['fecha_final_fin']; } else { $fecha_final_fin = ""; }

if($fecha_inicial_fin == "" && $fecha_final_fin != ""){
  $fecha_inicial_fin = $fecha_final_fin;
}

if($fecha_inicial_fin != "" && $fecha_final_fin == ""){
  $fecha_final_fin = $fecha_inicial_fin;
}

//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

$criterio_emp = "";

$empresa = implode(",", $empresa);

$q_empresa = Yii::app()->db->createCommand("SELECT Descripcion FROM T_PR_EMPRESA WHERE Id_Empresa IN (".$empresa.") ORDER BY Descripcion")->queryAll();

$texto_e = '';

foreach ($q_empresa as $e) {
  $texto_e .= $e['Descripcion'].', ';
}

$texto_e = substr ($texto_e, 0, -2);

$criterio_emp .= "Empresa: ".$texto_e;



$criterio_mot = "";

if($motivo_retiro != null){
  $motivo_retiro = implode(",", $motivo_retiro);
  
  $q_motivos = Yii::app()->db->createCommand("SELECT Dominio FROM T_PR_DOMINIO WHERE Id_Dominio IN (".$motivo_retiro.") ORDER BY Dominio")->queryAll();

  $texto_m = '';

  foreach ($q_motivos as $m) {
    $texto_m .= $m['Dominio'].', ';
  }

  $texto_m = substr ($texto_m, 0, -2);

  $criterio_mot .= "Motivos de retiro: ".$texto_m;

}else{
  $criterio_mot .= "Motivos de retiro: TODOS ";
}

$criterio = "";

if($fecha_inicial_fin != null && $fecha_final_fin != null){
  $criterio .= "Criterio de búsqueda: Fecha de retiro: de ".$fecha_inicial_fin." al ".$fecha_final_fin;
}else{
  if($fecha_inicial_fin != null && $fecha_final_fin == null){
    $criterio .= "Criterio de búsqueda: Fecha de retiro: ".$fecha_inicial_fin;
  }
}

if($liquidado != null){
  if($liquidado == 1){
    $criterio .= "Contratos liquidados: SI";
  }else{
    $criterio .= "Contratos liquidados: NO";
  }
}else{
  $criterio .= "Contratos liquidados: SI / NO";
}

//se obtiene la cadena de la fecha actual
$diatxt=date('l');
$dianro=date('d');
$mestxt=date('F');
$anionro=date('Y');
// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'Febrary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$diaesp=str_replace($ding, $desp, $diatxt);
$mesesp=str_replace($ming, $mesp, $mestxt);

$fecha_act= $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;

if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado == "" && $motivo_retiro == "" && $empresa != "") ){
  $o = 1;
}

if(($fecha_inicial_fin == "" && $fecha_final_fin == "" && $liquidado == "" && $motivo_retiro == "" && $empresa != "") ){
  $o = 2;
}

if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado == "" && $motivo_retiro != "" && $empresa != "") ){
  $o = 3;
}

if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado != "" && $motivo_retiro == "" && $empresa != "") ){
  $o = 4;
}

if(($fecha_inicial_fin == "" && $fecha_final_fin == "" && $liquidado != "" && $motivo_retiro != "" && $empresa != "") ){
  $o = 5;
}

if(($fecha_inicial_fin == "" && $fecha_final_fin == "" && $liquidado != "" && $motivo_retiro == "" && $empresa != "") ){
  $o = 6;
}

if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado != "" && $motivo_retiro != "" && $empresa != "") ){
  $o = 7;
}

/*inicio configuración array de datos*/

$FechaM1 = str_replace("-","",$fecha_inicial_fin);
$FechaM2 = str_replace("-","",$fecha_final_fin);

$query ="
  SET NOCOUNT ON
  EXEC P_PR_GH_EMPLEADO_INACT
  @OPT = ".$o.",
  @Fecha_Ini = '".$FechaM1."',
  @Fecha_Fin = '".$FechaM2."',
  @Empresa = '".$empresa."',
  @Motivo = '".$motivo_retiro."',
  @Liquidado = '".$liquidado."'
";

//echo $query;die;

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{
    
    function setCriterioEmp($criterio_emp){
      $this->criterio_emp = $criterio_emp;
    }

    function setCriterioMot($criterio_mot){
      $this->criterio_mot = $criterio_mot;
    }

    function setCriterio($criterio){
      $this->criterio = $criterio;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',10);
      $this->Cell(250,5,'Reporte contratos finalizados',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(90,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_emp),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_mot),0,0,'L');
      $this->Ln();
      
      if($this->criterio != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio),0,0,'L');
        $this->Ln();
      }
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',6);
      $this->Cell(30,2,utf8_decode('Tipo identificación'),0,0,'L');
      $this->Cell(20,2,utf8_decode('No. identificación'),0,0,'L');
      $this->Cell(57,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(23,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Unidad de gerencia'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Área'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Subárea'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Cargo'),0,0,'L');
      $this->Cell(20,2,utf8_decode('Fecha ingreso'),0,0,'L');
      $this->Cell(20,2,utf8_decode('Fecha retiro'),0,0,'L');
      $this->Cell(50,2,utf8_decode('Motivo'),0,0,'L');
      $this->Cell(20,2,utf8_decode('Liquidado'),0,0,'L');
      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

      foreach ($query1 as $reg1) {  

        $tipo_ident       = $reg1 ['Tipo_Identificacion']; 
        $ident            = $reg1 ['Identificacion']; 
        $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre']; 
        $empresa          = $reg1 ['Empresa']; 

        if($reg1 ['Unidad_Gerencia'] != ""){
          $ug = $reg1 ['Unidad_Gerencia']; 
        }else{
          $ug = "-";
        }

        if($reg1 ['Area'] != ""){
          $area = $reg1 ['Area']; 
        }else{
          $area = "-";
        }

        if($reg1 ['Subarea'] != ""){
          $subarea = $reg1 ['Subarea']; 
        }else{
          $subarea = "-";
        }

        if($reg1 ['Cargo'] != ""){
          $cargo = $reg1 ['Cargo']; 
        }else{
          $cargo = "-";
        }

        $fecha_ingreso    = $reg1 ['Fecha_Ingreso']; 
        $fecha_retiro     = $reg1 ['Fecha_Retiro'];
        $motivo           = $reg1 ['M_Retiro'];

        $liquidado        = $reg1 ['Liquidado'];

        $this->SetFont('Arial','',5);
        
        $this->Cell(30,3,utf8_decode($tipo_ident),0,0,'L');
        $this->Cell(20,3,utf8_decode($ident),0,0,'L');
        $this->Cell(57,3,substr(utf8_decode($empleado),0,50),0,0,'L');
        $this->Cell(23,3,utf8_decode($empresa),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($ug),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($area),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($subarea),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($cargo),0,20),0,0,'L');
        $this->Cell(20,3,utf8_decode($fecha_ingreso),0,0,'L');
        $this->Cell(20,3,$fecha_retiro,0,0,'L');
        $this->Cell(50,3,substr(utf8_decode($motivo),0,40),0,0,'L');
        $this->Cell(20,3,utf8_decode($liquidado),0,0,'L');
        $this->Ln();
      }

    }//fin tabla

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','B',6);
        $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
    }
  }

  $pdf = new PDF('L','mm','LEGAL');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setCriterioEmp($criterio_emp);
  $pdf->setCriterioMot($criterio_mot);
  $pdf->setCriterio($criterio);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Contratos_finalizados_'.date('Y_m_d_H_i_s').'.pdf');
}

if($opcion == 2){
  //EXCEL

  $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
  $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
  $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
  $type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

  $objPHPExcel = new Spreadsheet();

  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Reporte');

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Tipo identificación');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'No. identificación');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Empleado');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Empresa');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Unidad de gerencia');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Área');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Subárea');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Cargo');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Fecha ingreso');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Fecha retiro');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Motivo');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Liquidado');

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {
 
    $tipo_ident       = $reg1 ['Tipo_Identificacion']; 
    $ident            = $reg1 ['Identificacion']; 
    $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre']; 
    $empresa          = $reg1 ['Empresa']; 

    if($reg1 ['Unidad_Gerencia'] != ""){
      $ug = $reg1 ['Unidad_Gerencia']; 
    }else{
      $ug = "-";
    }

    if($reg1 ['Area'] != ""){
      $area = $reg1 ['Area']; 
    }else{
      $area = "-";
    }

    if($reg1 ['Subarea'] != ""){
      $subarea = $reg1 ['Subarea']; 
    }else{
      $subarea = "-";
    }

    if($reg1 ['Cargo'] != ""){
      $cargo = $reg1 ['Cargo']; 
    }else{
      $cargo = "-";
    }

    $fecha_ingreso    = $reg1 ['Fecha_Ingreso']; 
    $fecha_retiro     = $reg1 ['Fecha_Retiro'];
    $motivo           = $reg1 ['M_Retiro'];

    $liquidado        = $reg1 ['Liquidado'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $ug);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $area);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $subarea);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $cargo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $fecha_ingreso);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $fecha_retiro);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $motivo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $liquidado);
    
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1;

  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 13; 

  foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Contratos_finalizados_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












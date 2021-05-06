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

if (isset($model['id_empleado'])) { $id_empleado = $model['id_empleado']; } else { $id_empleado = ""; }
if (isset($model['fecha_inicial'])) { $fecha_inicial = $model['fecha_inicial']; } else { $fecha_inicial = ""; }
if (isset($model['fecha_final'])) { $fecha_final = $model['fecha_final']; } else { $fecha_final = ""; }
if (isset($model['motivo'])) { $motivo = $model['motivo']; } else { $motivo = ""; }
$empresa = $model['empresa'];

if($fecha_inicial == "" && $fecha_final != ""){
  $fecha_inicial = $fecha_final;
}

if($fecha_inicial != "" && $fecha_final == ""){
  $fecha_final = $fecha_inicial;
}

if($id_empleado == "" && $fecha_inicial != "" && $fecha_final != "" && $motivo == "" && $empresa != ""){
  $o = 1;
}

if($id_empleado != "" && $fecha_inicial != "" && $fecha_final != "" && $motivo == "" && $empresa != ""){
  $o = 2;
}

if($id_empleado != "" && $fecha_inicial != "" && $fecha_final != "" && $motivo != "" && $empresa != ""){
  $o = 3;
}

if($id_empleado == "" && $fecha_inicial != "" && $fecha_final != "" && $motivo != "" && $empresa != ""){
  $o = 4;
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

if($motivo != null){
  $motivo = implode(",", $motivo);
  
  $q_motivos = Yii::app()->db->createCommand("SELECT Dominio FROM T_PR_DOMINIO WHERE Id_Dominio IN (".$motivo.") ORDER BY Dominio")->queryAll();

  $texto_m = '';

  foreach ($q_motivos as $m) {
    $texto_m .= $m['Dominio'].', ';
  }

  $texto_m = substr ($texto_m, 0, -2);

  $criterio_mot .= "Motivos: ".$texto_m;

}else{
  $criterio_mot .= "Motivos: TODOS ";
}

$criterio_fec = "";

if($fecha_inicial != null && $fecha_final != null){
  $criterio_fec .= "Criterio de búsqueda:  Fecha: de ".$fecha_inicial." al ".$fecha_final;
}else{
  if($fecha_inicial != null && $fecha_final == null){
    $criterio_fec .= "Criterio de búsqueda:  Fecha: ".$fecha_inicial;
  }
}

$criterio_empl = "";

if($id_empleado != null){
  $nombre_emp = UtilidadesEmpleado::nombreempleado($id_empleado);
  $criterio_empl .= "Criterio de búsqueda:  Empleado: ".$nombre_emp;
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

/*inicio configuración array de datos*/

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

$query ="
  SET NOCOUNT ON
  EXEC P_PR_GH_DISCIPLINARIO
  @OPT = ".$o.",
  @Id_Emp = '".$id_empleado."',
  @Fecha_Ini = N'".$FechaM1."',
  @Fecha_Fin = N'".$FechaM2."',
  @Motivo = '".$motivo."',
  @Empresa = '".$empresa."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  //se incluye la libreria pdf
  require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

  class PDF extends FPDF{
    
    function setCriterioEmp($criterio_emp){
      $this->criterio_emp = $criterio_emp;
    }

    function setCriterioMot($criterio_mot){
      $this->criterio_mot = $criterio_mot;
    }

    function setCriterioFec($criterio_fec){
      $this->criterio_fec = $criterio_fec;
    }

    function setCriterioEmpl($criterio_empl){
      $this->criterio_empl = $criterio_empl;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',10);
      $this->Cell(260,5,'Reporte comparendos de empleados',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_emp),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_mot),0,0,'L');
      $this->Ln();
      
      if($this->criterio_fec != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio_fec),0,0,'L');
        $this->Ln();
      }

      if($this->criterio_empl != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio_empl),0,0,'L');
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
      $this->Cell(50,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Evento'),0,0,'L');
      $this->Cell(60,2,utf8_decode('Motivo'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Fecha'),0,0,'L');
      $this->Cell(50,2,utf8_decode('Impuesto Por'),0,0,'L');
      $this->Cell(20,2,utf8_decode('Orden No.'),0,0,'L');
      $this->Cell(45,2,utf8_decode('Notas'),0,0,'L');
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

        $tipo_ident       = $reg1['Tipo_Identificacion']; 
        $ident            = $reg1['Identificacion'];  
        $empleado         = $reg1['Empleado'];
        $empresa          = $reg1['Empresa'];
        $disciplinario    = $reg1['Disciplinario'];
        $motivo           = $reg1['Motivo']; 
        $fecha_evento     = $reg1['Fecha']; 
        $persona_imp      = $reg1['Imp']; 
        $orden            = $reg1['Orden_No'];
        $observaciones    = $reg1['Observacion']; 

        $this->SetFont('Arial','',6);
        $this->Cell(30,3,utf8_decode($tipo_ident),0,0,'L');
        $this->Cell(20,3,utf8_decode($ident),0,0,'L');
        $this->Cell(50,3,substr(utf8_decode($empleado),0,45),0,0,'L');
        $this->Cell(25,3,utf8_decode($empresa),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($disciplinario),0,20),0,0,'L');
        $this->Cell(60,3,substr(utf8_decode($motivo),0,45),0,0,'L');
        $this->Cell(15,3,utf8_decode($fecha_evento),0,0,'L');
        $this->Cell(50,3,substr(utf8_decode($persona_imp),0,45),0,0,'L');
        $this->Cell(20,3,utf8_decode($orden),0,0,'L');
        $this->MultiCell(45,3,utf8_decode($observaciones),0,'J');
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
  $pdf->setCriterioFec($criterio_fec);
  $pdf->setCriterioEmpl($criterio_empl);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Disciplinarios_empleados_'.date('Y_m_d_H_i_s').'.pdf');
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
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Evento');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Motivo');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Fecha');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Impuesto Por');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Orden No.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Observaciones');

  $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {

    $tipo_ident       = $reg1['Tipo_Identificacion']; 
    $ident            = $reg1['Identificacion'];  
    $empleado         = $reg1['Empleado'];
    $empresa          = $reg1['Empresa'];
    $disciplinario    = $reg1['Disciplinario'];
    $motivo           = $reg1['Motivo']; 
    $fecha_evento     = $reg1['Fecha']; 
    $persona_imp      = $reg1['Imp']; 
    $orden            = $reg1['Orden_No'];
    $observaciones    = $reg1['Observacion']; 
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $disciplinario);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $motivo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $fecha_evento);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $persona_imp);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $orden);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $observaciones);
    
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':J'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1;

  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 10; 

  foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Disciplinarios_empleados_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












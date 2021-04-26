<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte
if (isset($model['empresa'])) { $empresa = $model['empresa']; } else { $empresa = ""; }
if (isset($model['motivo_retiro'])) { $motivo_retiro = $model['motivo_retiro']; } else { $motivo_retiro = ""; }
if (isset($model['liquidado'])) { $liquidado = $model['liquidado']; } else { $liquidado = ""; }
if (isset($model['fecha_inicial_fin'])) { $fecha_inicial_fin = $model['fecha_inicial_fin']; } else { $fecha_inicial_fin = ""; }
if (isset($model['fecha_final_fin'])) { $fecha_final_fin = $model['fecha_final_fin']; } else { $fecha_final_fin = ""; }
//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

$condicion = "WHERE HP.Id_M_Retiro IS NOT NULL";

$criterio_emp = "";

if($empresa != null){
  $empresa = implode(",", $empresa);
  $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
  
  $q_empresa = Yii::app()->db->createCommand("SELECT Descripcion FROM TH_EMPRESA WHERE Id_Empresa IN (".$empresa.") ORDER BY Descripcion")->queryAll();

  $texto_e = '';

  foreach ($q_empresa as $e) {
    $texto_e .= $e['Descripcion'].', ';
  }

  $texto_e = substr ($texto_e, 0, -2);

  $criterio_emp .= "Empresa: ".$texto_e;

}else{

  $array_empresas = (Yii::app()->user->getState('array_empresas'));
  $empresa = implode(",",$array_empresas);
  $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    
  $criterio_emp .= "Empresa: TODAS ";
}

$criterio_mot = "";

if($motivo_retiro != null){
  $motivo_retiro = implode(",", $motivo_retiro);
  $condicion .= " AND HP.Id_Retiro IN (".$motivo_retiro.")";
  
  $q_motivos = Yii::app()->db->createCommand("SELECT Dominio FROM TH_DOMINIO WHERE Id_Dominio IN (".$motivo_retiro.") ORDER BY Dominio")->queryAll();

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
  $condicion .= " AND HP.Fecha_Retiro BETWEEN '".$fecha_inicial_fin."' AND '".$fecha_final_fin."'";
  $criterio .= "Criterio de búsqueda: Fecha de retiro: de ".$fecha_inicial_fin." al ".$fecha_final_fin;
}else{
  if($fecha_inicial_fin != null && $fecha_final_fin == null){
    $condicion .= " AND HP.Fecha_Retiro = '".$fecha_inicial_fin."'";
    $criterio .= "Criterio de búsqueda: Fecha de retiro: ".$fecha_inicial_fin;
  }
}

if($liquidado != null){
  if($liquidado == 1){
    $condicion .= " AND HP.Fecha_Liquidacion IS NOT NULL";
    $criterio .= "Contratos liquidados: SI";
  }else{
    $condicion .= " AND HP.Fecha_Liquidacion IS NULL";
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

/*inicio configuración array de datos*/

$query ="
SELECT  
TI.Dominio AS Tipo_Ident, 
P.Identificacion, 
CONCAT (P.Apellido, ' ', P.Nombre) AS Empleado, 
E.Descripcion AS Empresa,
UG.Unidad_Gerencia,
A.Area,
S.Subarea,
C.Cargo, 
CIU.Ciudad,
HP.Fecha_Ingreso, 
HP.Fecha_Retiro,
MO.Dominio AS Motivo,  
CASE WHEN HP.Fecha_Liquidacion IS NULL THEN 'NO'
ELSE CONCAT('SI - ', HP.Fecha_Liquidacion )                                       
END AS Liquidado
FROM TH_CONTRATO_EMPLEADO HP 
LEFT JOIN TH_EMPLEADO P ON HP.Id_Empleado = P.Id_Empleado 
LEFT JOIN TH_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio
LEFT JOIN TH_CIUDAD CIU ON P.Id_Ciudad_Labor = CIU.Id_Ciudad
LEFT JOIN TH_DOMINIO MO ON HP.Id_M_Retiro = MO.Id_Dominio 
LEFT JOIN TH_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
LEFT JOIN TH_UNIDAD_GERENCIA UG ON HP.Id_Unidad_Gerencia = UG.Id_Unidad_Gerencia 
LEFT JOIN TH_AREA A ON HP.Id_Area = A.Id_Area
LEFT JOIN TH_SUBAREA S ON HP.Id_Subarea = S.Id_Subarea
LEFT JOIN TH_CARGO C ON HP.Id_Cargo = C.Id_Cargo 
".$condicion." 
ORDER BY 4,5,6,7,8,3,11 ASC
";

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

        $tipo_ident       = $reg1 ['Tipo_Ident']; 
        $ident            = $reg1 ['Identificacion']; 
        $empleado         = $reg1 ['Empleado']; 
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
        $motivo           = $reg1 ['Motivo'];

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
  $pdf->Output('D','Contratos_finalizados_'.date('Y-m-d H_i_s').'.pdf');
}

if($opcion == 2){
  //EXCEL

  // Se inactiva el autoloader de yii
  spl_autoload_unregister(array('YiiBase','autoload'));   

  require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel.php';
  
  //cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
  spl_autoload_register(array('YiiBase','autoload'));

  $objPHPExcel = new PHPExcel();

  $objPHPExcel->getActiveSheet()->setTitle('Hoja1');
  $objPHPExcel->setActiveSheetIndex();

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'Tipo identificación');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'No. identificación');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Empleado');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Empresa');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Unidad de gerencia');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Área');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Subárea');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Cargo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'Dpto - municipio labor');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('J1', 'Fecha ingreso');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K1', 'Fecha retiro');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('L1', 'Motivo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('M1', 'Liquidado');

  $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {
 
    $tipo_ident       = $reg1 ['Tipo_Ident']; 
    $ident            = $reg1 ['Identificacion']; 
    $empleado         = $reg1 ['Empleado'];

    $empresa = $reg1 ['Empresa']; 
    
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

    if($reg1 ['Ciudad'] != ""){
      $ciudad_labor = $reg1 ['Ciudad']; 
    }else{
      $ciudad_labor = "-";
    }
      
    $fecha_ingreso    = $reg1 ['Fecha_Ingreso']; 
    $fecha_retiro     = $reg1 ['Fecha_Retiro'];
    $motivo           = $reg1 ['Motivo']; 
    $liquidado        = $reg1 ['Liquidado'];

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $ug);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $area);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $subarea);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $cargo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $ciudad_labor);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, $fecha_ingreso);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $fecha_retiro);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$Fila, $motivo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$Fila, $liquidado);
    
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':M'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $Fila = $Fila + 1;

  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {

      $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

      $sheet = $objPHPExcel->getActiveSheet();
      $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(true);
      foreach ($cellIterator as $cell) {
          $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
      }
  }

  $n = 'Contratos_finalizados_'.date('Y-m-d H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












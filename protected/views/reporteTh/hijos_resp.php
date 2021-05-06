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
if (isset($model['genero'])) { $genero = $model['genero']; } else { $genero = ""; }
if (isset($model['edad_inicial'])) { $edad_inicial = $model['edad_inicial']; } else { $edad_inicial = ""; }
if (isset($model['edad_final'])) { $edad_final = $model['edad_final']; } else { $edad_final = ""; }

if($edad_inicial == "" && $edad_final != ""){
  $edad_inicial = $edad_final;
}

if($edad_inicial != "" && $edad_final == ""){
  $edad_final = $edad_inicial;
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

$criterio = "";

if($genero != null){
  $genero = Dominio::model()->findByPk($genero)->Dominio;
  $criterio .= "Género: ".$genero;
}else{
  $criterio .= "Género: TODOS ";  
}

if($edad_inicial != null && $edad_final != null){
  $criterio .= ", Edad: de ".$edad_inicial." a ".$edad_final." años";
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


if(($edad_inicial != "" && $edad_inicial != "" && $genero == "" && $empresa != "") ){
  $o = 1;
}

if(($edad_inicial == "" && $edad_inicial == "" && $genero != "" && $empresa != "") ){
  $o = 2;
}

if(($edad_inicial == "" && $edad_inicial == "" && $genero == "" && $empresa != "") ){
  $o = 3;
}

if(($edad_inicial != "" && $edad_inicial != "" && $genero != "" && $empresa != "") ){
  $o = 4;
}

$query ="
  SET NOCOUNT ON
  EXEC P_PR_GH_HIJOS
  @OPT = ".$o.",
  @Edad_Ini = '".$edad_inicial."',
  @Edad_Fin = '".$edad_final."',
  @Genero = '".$genero."',
  @Empresa = '".$empresa."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{
    
    function setCriterioEmp($criterio_emp){
      $this->criterio_emp = $criterio_emp;
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
      $this->SetFont('Arial','B',9);
      $this->Cell(200,5,'Reporte hijos de empleados',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',5);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_emp),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',5);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: '.$this->criterio),0,0,'L');
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',5);
      $this->Cell(30,2,utf8_decode('Tipo identificación'),0,0,'L');
      $this->Cell(20,2,utf8_decode('No. identificación'),0,0,'L');
      $this->Cell(57,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(36,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(56,2,utf8_decode('Hijo'),0,0,'L');
      $this->Cell(36,2,utf8_decode('Fecha de nacimiento'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Edad'),0,0,'R');
      $this->Cell(36,2,utf8_decode('Género'),0,0,'L');

      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

      foreach ($query1 as $reg1) {  

        $tipo_ident       = $reg1 ['Tipo_Ident']; 
        $ident            = $reg1 ['Identificacion']; 
        $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre'];
        $empresa          = $reg1 ['Empresa']; 
        $hijo             = $reg1 ['Hijo']; 
        $fecha_nacimiento = $reg1 ['Fecha_Nacimiento']; 
        $edad             = $reg1 ['Edad']; 
        $genero           = $reg1 ['Genero']; 

        $this->SetFont('Arial','',5);
        $this->Cell(30,3,utf8_decode($tipo_ident),0,0,'L');
        $this->Cell(20,3,utf8_decode($ident),0,0,'L');
        $this->Cell(57,3,substr(utf8_decode($empleado),0,50),0,0,'L');
        $this->Cell(36,3,utf8_decode($empresa),0,0,'L');
        $this->Cell(56,3,substr(utf8_decode($hijo),0,50),0,0,'L');
        $this->Cell(36,3,utf8_decode($fecha_nacimiento),0,0,'L');
        $this->Cell(15,3,utf8_decode($edad),0,0,'R');
        $this->Cell(36,3,utf8_decode($genero),0,0,'L');
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

  $pdf = new PDF('L','mm','A4');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setCriterioEmp($criterio_emp);
  $pdf->setCriterio($criterio);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Hijos_empleados_'.date('Y_m_d_H_i_s').'.pdf');
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
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Hijo');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Fecha de nacimiento');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Edad');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Género');

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {

    $tipo_ident       = $reg1 ['Tipo_Ident']; 
    $ident            = $reg1 ['Identificacion']; 
    $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre'];
    $empresa          = $reg1 ['Empresa']; 
    $hijo             = $reg1 ['Hijo']; 
    $fecha_nacimiento = $reg1 ['Fecha_Nacimiento']; 
    $edad             = $reg1 ['Edad']; 
    $genero           = $reg1 ['Genero']; 

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $hijo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $fecha_nacimiento);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $edad);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $genero);
        
    $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1;

  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 8; 

  foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Hijos_empleados_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












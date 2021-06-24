<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

//se reciben los parametros para el reporte
$unidad_gerencia = $model['unidad_gerencia'];

$ug = UnidadGerencia::model()->findByPk($unidad_gerencia)->Unidad_Gerencia;

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
  SET NOCOUNT ON
  EXEC P_PR_GH_EMPLEADO_UG
  @Id_UN = ".$unidad_gerencia."
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

//EXCEL

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Novedades');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Tipo identificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'No. identificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Empleado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fec. nacimiento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Empresa');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Regional');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Ciudad de residencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Unidad de gerencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Área');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Subárea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Cargo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Centro de costo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Fec. ingreso');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Salario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Salario flexible ?');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $tipo_ident          = $reg1 ['Tipo_Ident']; 
  $ident               = $reg1 ['Identificacion']; 
  $empleado            = $reg1 ['Empleado'];  
  $fecha_nacimiento    = $reg1 ['Fecha_Nacimiento'];

  $empresa = $reg1 ['Empresa']; 
  
  if($reg1 ['Regional'] != ""){
    $regional = $reg1 ['Regional']; 
  }else{
    $regional = "-";
  }

  if($reg1 ['Ciu_Res'] != ""){
    $ciudad_res = $reg1 ['Ciu_Res']; 
  }else{
    $ciudad_res = "-";
  }

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

  if($reg1 ['CC'] != ""){
    $cc = $reg1 ['CC']; 
  }else{
    $cc = "-";
  }

  $fecha_ingreso = $reg1 ['Fecha_Ingreso']; 
  $salario = number_format($reg1['Salario'],0);

  if($reg1 ['Salario_F'] != ""){
    $sf = $reg1 ['Salario_F']; 
  }else{
    $sf = "-";
  }

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, '');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $tipo_ident);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $ident);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $empleado);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $fecha_nacimiento);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $empresa);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $regional);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $ciudad_res);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $ug);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $area);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $subarea);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $cargo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $cc);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $fecha_ingreso);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $salario);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $sf);
  
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':N'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('P'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  
  $Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 16; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Empleados_x_unidad_gerencia'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












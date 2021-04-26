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

$dias = $model['dias'];

//se obtiene la cadena de la fecha actual
$diatxt=date('l');
$dianro=date('d');
$mestxt=date('F');
$anionro=date('Y');
// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$diaesp=str_replace($ding, $desp, $diatxt);
$mesesp=str_replace($ming, $mesp, $mestxt);

$fecha_act= $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;

$query ="
  SET NOCOUNT ON
  EXEC P_PR_COM_CONS_CLIEN_POT
  @OPT = 2,
  @DIAS = ".$dias."
";

UtilidadesVarias::log($query);

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NIT');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'ID SUCURSAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'DESC. SUCURSAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'CONTACTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'CIUDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'DIRECCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'TELÉFONO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'RUTA CRITERIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'RUTA VISITA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'VENDEDOR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'CUPO TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'CUPO DISP.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'ULT. FACTURA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'FECHA ULT. FACTURA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'DIAS ULT. FACTURA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'PROM. DIAS PAGO');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $Cliente_Nit          = $reg1 ['Cliente_Nit'];
  $Cliente_Nombre       = $reg1 ['Cliente_Nombre'];   
  $Id_Sucursal          = $reg1 ['Id_Sucursal']; 
  $Cliente_Sucursal     = $reg1 ['Cliente_Sucursal'];
  $Contacto_Nombre      = $reg1 ['Contacto_Nombre'];
  $Ciudad               = $reg1 ['Ciudad'];
  $Direccion            = $reg1 ['Direccion'];
  $Telefono             = $reg1 ['Telefono']; 
  $Ruta_Criterio        = $reg1 ['Ruta_Criterio'];
  $Ruta_Visita          = $reg1 ['Ruta_Visita'];
  $Vendedor_Nombre      = $reg1 ['Vendedor_Nombre'];
  $Cupo_Total           = $reg1 ['Cupo_Total'];
  $Cupo_Disp            = $reg1 ['Cupo_Disp'];
  $Ult_Factura          = $reg1 ['Ult_Factura'];
  $Fech_Ult_Factura     = $reg1 ['Fech_Ult_Factura'];
  $Dias_Ult_Factura     = $reg1 ['Dias_Ult_Factura']; 
  $Prom_Dias_Pago       = $reg1 ['Prom_Dias_Pago'];

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $Cliente_Nit);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $Cliente_Nombre);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('C'.$Fila, $Id_Sucursal, $type_string);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $Cliente_Sucursal);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $Contacto_Nombre);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Ciudad);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Direccion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Telefono);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I'.$Fila, $Ruta_Criterio, $type_string);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('J'.$Fila, $Ruta_Visita, $type_string);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Vendedor_Nombre);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Cupo_Total);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Cupo_Disp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Ult_Factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $Fech_Ult_Factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $Dias_Ult_Factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $Prom_Dias_Pago);
      
  $objPHPExcel->getActiveSheet()->getStyle('L'.$Fila.':M'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');

  $Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 17; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Clientes_pot_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
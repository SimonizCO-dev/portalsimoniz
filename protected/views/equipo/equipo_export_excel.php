<?php
/* @var $this PromocionController */
/* @var $model Promocion */

set_time_limit(0);

spl_autoload_unregister(array('YiiBase','autoload'));  

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));


$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Serial');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Empresa que compro');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'N° de factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Fecha de compra');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Proveedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Estado');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);

$Fila = 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->Id_Equipo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$reg->tipoequipo->Dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$reg->Serial);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg->empresacompra->Descripcion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$reg->Numero_Factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,UtilidadesVarias::textofecha($reg->Fecha_Compra));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$reg->proveedor->Proveedor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,UtilidadesVarias::textoestado1($reg->Estado));
  
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
       
}

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 8; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Equipo_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;
  
?>
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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Clasif.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Versión');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Producto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'N° de licencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Usuarios x lic.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Usuarios x lic. disp.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Empresa que compro');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Ubicación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'N° de factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Estado');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->Id_Lic);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$reg->clasificacion->Dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$reg->tipo->Dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg->version->Dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,($reg->Producto == "") ? "-" : $reg->producto->Dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,($reg->Num_Licencia == "") ? "-" : $reg->Num_Licencia);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$reg->Cant_Usuarios);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$reg->CantUsuariosRest($reg->Id_Lic));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,($reg->Empresa_Compra == "") ? "-" : $reg->empresacompra->Descripcion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,($reg->Ubicacion == "") ? "-" : $reg->ubicacion->Dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$reg->Numero_Factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,($reg->Estado == "") ? "-" : $reg->estado->Dominio);

  $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
       
}

/*fin contenido tabla*/


//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 12; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Licencia_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
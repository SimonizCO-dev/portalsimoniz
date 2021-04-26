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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Línea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Bodega');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Cantidad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fecha ult. entrada');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Fecha ult. salida');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Usuario que actualizó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha de actualización');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);

$Fila = 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->iditem->idlinea->Descripcion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$reg->DescItem($reg->Id_Item));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,($reg->Id_Bodega == "") ? "N/A" : $reg->idbodega->Descripcion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg->Cantidad);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,($reg->Fecha_Ult_Ent == "") ? "N/A" : UtilidadesVarias::textofecha($reg->Fecha_Ult_Ent));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,($reg->Fecha_Ult_Sal == "") ? "N/A" : UtilidadesVarias::textofecha($reg->Fecha_Ult_Sal));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$reg->idusuarioact->Usuario);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,UtilidadesVarias::textofechahora($reg->Fecha_Actualizacion));

  $Fila ++;
       
}

/*fin contenido tabla*/


//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 8; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_existencias_x_bogeda_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
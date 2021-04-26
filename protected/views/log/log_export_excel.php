<?php
/* @var $this LogController */
/* @var $model Log */

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Usuario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Opción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Acción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fecha y hora');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->DescTipo($reg->Tipo));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$reg->idusuario->Nombres);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,($reg->Id_Menu == "") ? "-" : $reg->DescOpcPadre($reg->Id_Menu));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,($reg->Accion == "") ? "-" : $reg->Accion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,UtilidadesVarias::textofechahora($reg->Fecha_Hora));

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 5; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Log_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;
  
?>

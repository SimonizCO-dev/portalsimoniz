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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID docto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Clasificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'N° documento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Nombre');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Nivel de revisión');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Acción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Usuario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Fecha y hora');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->Id_Documento);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,UtilidadesVarias::descclasif($reg->iddocumento->Clasificacion));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$reg->iddocumento->tipo->Descripcion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg->iddocumento->Num_Documento);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$reg->iddocumento->Titulo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$reg->iddocumento->Nivel_Revision);  
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,UtilidadesVarias::textoaccion($reg->Accion));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$reg->idusuario->Usuario);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,UtilidadesVarias::textofechahora($reg->Fecha_Hora));

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 9; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Auditoria_documentos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
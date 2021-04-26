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
$fecha_inicial = $model->fecha_inicial;
$fecha_final = $model->fecha_final;

/*inicio configuración array de datos*/

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

$query ="
  EXEC P_PR_COM_POS2_FONT
  @FECHA_INI = N'".$FechaM1."',
  @FECHA_FIN = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'PEDIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'FECHA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'UND. MEDIDA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'EXISTENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'CANTIDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'PRECIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'VLR. NETO');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:J1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:J1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $PEDIDO           = $reg1 ['PEDIDO'];
  $CLIENTE          = $reg1 ['CLIENTE'];
  $FECHA            = $reg1 ['FECHA']; 
  $ITEM             = $reg1 ['ITEM']; 
  $DESCRIPCION      = $reg1 ['DESCRIPCION'];
  $UM               = $reg1 ['UM'];
  $EXISTENCIA       = $reg1 ['EXISTENCIA'];
  $CANTIDAD         = $reg1 ['CANTIDAD'];
  $PRECIO           = $reg1 ['PRECIO'];
  $VLR_NETO         = $reg1 ['VLR_NETO'];

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $PEDIDO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $CLIENTE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $FECHA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $DESCRIPCION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $UM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $EXISTENCIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $CANTIDAD);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $PRECIO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $VLR_NETO);

  $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila.':H'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':J'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);       
  $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':J'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 10; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Ventas_POS_entrega_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
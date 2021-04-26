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
  EXEC P_PR_COM_POS1_FONT
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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'FECHA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'EXISTENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'CANTIDAD');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $FECHA            = $reg1 ['FECHA']; 
  $ITEM             = $reg1 ['ITEM']; 
  $DESCRIPCION      = $reg1 ['DESCRIPCION'];
  $EXISTENCIA       = $reg1 ['EXISTENCIA'];
  $CANTIDAD         = $reg1 ['CANTIDAD'];

  $cal = $EXISTENCIA - $CANTIDAD;

  if($cal < 0){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $FECHA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $DESCRIPCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $EXISTENCIA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $CANTIDAD);

    $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila.':E'.$Fila)->getNumberFormat()->setFormatCode('0'); 
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':C'.$Fila)->getAlignment()->setHorizontal($alignment_left);       
    $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1;

  }

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 5; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Ventas_POS_falt_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
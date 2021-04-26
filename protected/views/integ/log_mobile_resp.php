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
$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

/*inicio configuración array de datos*/

//EXCEL

$query ="
  EXEC P_CF_COM_CONS_LOGMOBILE_FECHA
  @FECHA1 = N'".$FechaM1."',
  @FECHA2 = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Documento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Fecha de elaboración');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Error');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Actualizado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Eliminado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha de registro');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $DOCUMENTO          = $reg1 ['DOCUMENTO']; 
    $FECHA_ELABORACION  = $reg1 ['FECHA_ELABORACION']; 
    $VENDEDOR  = $reg1 ['VENDEDOR'];
    $CLIENTE  = $reg1 ['CLIENTE'];
    $ERROR  = $reg1 ['ERROR'];
    $ACTUALIZADO  = $reg1 ['ACTUALIZADO'];
    $ELIMINADO  = $reg1 ['ELIMINADO'];
    $FECHA_REGISTRO  = $reg1 ['FECHA_REGISTRO'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $DOCUMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $FECHA_ELABORACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $VENDEDOR);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $CLIENTE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $ERROR);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $ACTUALIZADO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $ELIMINADO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $FECHA_REGISTRO);
        
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 8; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_log_mobile_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
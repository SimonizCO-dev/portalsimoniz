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
  EXEC P_PR_COM_CONS_CONT_ITEM_SIVA
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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'DOCUMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'RAZÓN SOCIAL CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'FECHA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'LINEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'VALOR');
$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $Documento             = $reg1 ['Documento']; 
    $Razon_Social_Cliente  = $reg1 ['Razon_Social_Cliente']; 
    $Fecha                 = $reg1 ['Fecha'];
    $Linea                 = $reg1 ['LINEA'];
    $Item                  = $reg1 ['Item'];
    $Referencia            = $reg1 ['Referencia'];
    $Descripcion           = $reg1 ['Descripcion'];
    $Valor                 = $reg1 ['Valor'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $Documento);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $Razon_Social_Cliente);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $Fecha);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $Linea);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $Item);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Referencia);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Descripcion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Valor);
    
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':G'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila)->getNumberFormat()->setFormatCode('0');

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 8; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Items_exentos_iva_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












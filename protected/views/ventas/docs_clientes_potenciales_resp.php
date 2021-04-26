<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

/*inicio configuración array de datos*/

$query ="EXEC P_PR_CRM_CONS_USUARIO2";

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'NOMBRE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'NIT');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'RAZÓN SOCIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'DOCUMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'CONSECUTIVO');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $ID = $reg1 ['ID']; 
    $NOMBRE = $reg1 ['NOMBRE']; 
    $NIT = $reg1 ['NIT'];
    $RAZON_SOCIAL = $reg1 ['RAZON_SOCIAL'];
    $DOCUMENTO = $reg1 ['DOCUMENTO'];
    $CONSECUTIVO = $reg1 ['CONSECUTIVO'];
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ID);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $NOMBRE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $NIT);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $RAZON_SOCIAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $DOCUMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $CONSECUTIVO);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 6; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Docs_clientes_potenciales_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

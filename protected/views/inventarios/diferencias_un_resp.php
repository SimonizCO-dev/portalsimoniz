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

//EXCEL

$query ="EXEC P_PR_COM_CONS_UN";

UtilidadesVarias::log($query);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Descripción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Código de inventario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'UN de inventario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Código de criterio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'UN de criterio');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $item        = $reg1 ['ITEM']; 
    $desc        = $reg1 ['DESCRIPCION']; 
    $cod_inv     = $reg1 ['COD_INVENTARIO'];
    $un_inv      = $reg1 ['UN_INVENTARIO'];
    $cod_cri     = $reg1 ['COD_CRITERIO'];
    $un_cri      = $reg1 ['UN_CRITERIO'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $item);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $desc);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('C'.$Fila, $cod_inv, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $un_inv);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('E'.$Fila, $cod_cri, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $un_cri);
        
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 6; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_diferencias_un_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












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

/*inicio configuración array de datos*/

$query = "
    SELECT DISTINCT
    I_ID_ITEM as ITEM
    ,I_UNIDAD_NEGOCIO as UN
    ,f400_cant_existencia_1 as CANTIDAD
    ,f132_costo_prom_uni as COSTO
    FROM UnoEE1..t400_cm_existencia
    INNER JOIN T_CF_ITEMS ON f400_rowid_item_ext = I_ROWID_ITEM
    INNER JOIN UnoEE1..t132_mc_items_instalacion ON f132_rowid_item_ext = f400_rowid_item_ext AND f132_id_instalacion = '100'
    WHERE f400_rowid_bodega = 105 AND f400_cant_existencia_1 != 0
";

UtilidadesVarias::log($query);

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CANTIDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'COSTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'UN');


$objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $ITEM            = $reg1 ['ITEM']; 
    $UN              = $reg1 ['UN']; 
    $CANTIDAD        = $reg1 ['CANTIDAD']; 
    $COSTO           = $reg1 ['COSTO'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $CANTIDAD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $COSTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $UN);

    $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':B'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':B'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 4; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Costo_X_Item_POS_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

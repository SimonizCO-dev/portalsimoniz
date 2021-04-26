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

$tipo = $model['tipo'];
$cons_inicial = $model['cons_inicial'];
$cons_final = $model['cons_final'];

/*inicio configuración array de datos*/

//EXCEL

$query ="SELECT * FROM T_CF_FACTURA_ELECTRONICA WHERE FE_TIPO_DOCTO = '".$tipo."' AND FE_CONSECUTIVO BETWEEN ".$cons_inicial." AND ".$cons_final." ORDER BY FE_CONSECUTIVO";

UtilidadesVarias::log($query);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Cia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'CO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Tipo de docto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Desc. tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Consecutivo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Cufe');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Fecha de factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha de creación');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:H1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $cia  = $reg1 ['FE_CIA']; 
    $co  = $reg1 ['FE_CO']; 
    $tipo_docto  = $reg1 ['FE_TIPO_DOCTO']; 
    
    
    if($tipo_docto == "FVN") {
      $tipo = 'Factura de Venta Nacional';
    }

    if($tipo_docto == "FVX") {
      $tipo = 'Factura de Exportación';
    }

    if($tipo_docto == "FEC") {
      $tipo = 'Factura de Contingencia Facturador';
    }

    $consecutivo  = $reg1 ['FE_CONSECUTIVO']; 
    $cufe  = $reg1 ['FE_CUFE']; 
    $fecha_factura  = $reg1 ['FE_FECHA_FACTURA'];
    $fecha_creacion  = $reg1 ['CREACION']; 

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $cia);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $co);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $tipo_docto);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $tipo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $consecutivo); 
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $cufe);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $fecha_factura);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $fecha_creacion);
        
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

$objPHPExcel->setActiveSheetIndex(0);

$n = 'Consulta_Fact_elect_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
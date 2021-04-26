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

//EXCEL

$query ="EXEC P_PR_COM_CONS_RUTAS";

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Ruta visita');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Ruta criterio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Sucursal');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $ruta_visita    = $reg1 ['RUTA_VISITA']; 
    $ruta_criterio  = $reg1 ['RUTA_CRITERIO']; 
    $nit            = $reg1 ['NIT'];
    $cliente        = $reg1 ['CLIENTE'];
    $sucursal       = $reg1 ['SUCURSAL'];

    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('A'.$Fila, $ruta_visita, $type_string);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('B'.$Fila, $ruta_criterio, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $nit);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cliente);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('E'.$Fila, $sucursal, $type_string);
        
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 5; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_diferencias_rutas_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

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
  EXEC P_PR_COM_CONS_LIB_PED
  @FECHA1 = N'".$FechaM1."',
  @FECHA2 = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'PEDIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'FECHA PEDIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'NIT');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'VLR. NETO PEDIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'VLR. BRUTO PEDIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'RET. X CUPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'RET. X MORA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'RET. X MARGEN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'FECHA RETENIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'FECHA APROBACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'USUARIO APROB. CART.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'FECHA APROB. CART.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'USUARIO APROB. MARGEN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'FECHA APROB. MARGEN');
$objPHPExcel->getActiveSheet(0)->getStyle('A1:O1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:O1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $Pedido  = $reg1 ['Pedido']; 
    $Fch_Pedido  = $reg1 ['Fch_Pedido']; 
    $NIT  = $reg1 ['NIT'];
    $Cliente  = $reg1 ['Cliente'];
    $Vlr_Neto_Pedido  = $reg1 ['Vlr_Neto_Pedido'];
    $Vlr_Bruto_Pedido  = $reg1 ['Vlr_Bruto_Pedido'];
    $Ret_Cupo  = $reg1 ['Ret_Cupo'];
    $Ret_Mora  = $reg1 ['Ret_Mora'];
    $Ret_Margen  = $reg1 ['Ret_Margen']; 
    $Fch_Retenido  = $reg1 ['Fch_Retenido']; 
    $Fch_Aprobacion  = $reg1 ['Fch_Aprobacion'];
    $Usu_Apro_Cart  = $reg1 ['Usu_Apro_Cart'];
    $Fch_Apro_Cart  = $reg1 ['Fch_Apro_Cart'];
    $Usu_Apro_Marg  = $reg1 ['Usu_Apro_Marg'];
    $Fch_Apro_Marg  = $reg1 ['Fch_Apro_Marg'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $Pedido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $Fch_Pedido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $NIT);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $Cliente);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $Vlr_Neto_Pedido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Vlr_Bruto_Pedido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Ret_Cupo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Ret_Mora);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $Ret_Margen);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Fch_Retenido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Fch_Aprobacion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Usu_Apro_Cart);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Fch_Apro_Cart);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Usu_Apro_Marg);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $Fch_Apro_Marg);
    
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':F'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 15; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->setActiveSheetIndex(0);

$n = 'Historico_liberacion_pedidos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
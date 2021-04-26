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
  EXEC P_PR_COM_INF_FEE_TERPEL_DET
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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CATEGORIA SSCC');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'PRODUCTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'LINEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'TIPO EDS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'RAZÓN SOCIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'DIRECCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'FACTURA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'FECHA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'NIT');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'CIUDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'REGIONAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'NOMBRE EDS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'TOTAL UND.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'COSTO UND.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'TOTAL ANTES IVA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'VLR. FACT. FEE');
$objPHPExcel->getActiveSheet(0)->getStyle('A1:R1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:R1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $CATEGORIA_SSCC  = $reg1 ['CATEGORIA_SSCC']; 
    $PRODUCTO  = $reg1 ['PRODUCTO']; 
    $REFERENCIA  = $reg1 ['REFERENCIA'];
    $LINEA  = $reg1 ['LINEA'];
    $TIPO_EDS  = $reg1 ['TIPO_EDS'];
    $RAZON_SOCIAL  = $reg1 ['RAZON_SOCIAL'];
    $DIRECCION  = $reg1 ['DIRECCION'];
    $FACTURA  = $reg1 ['FACTURA'];
    $FECHA  = $reg1 ['FECHA']; 
    $NIT  = $reg1 ['NIT']; 
    $CIUDAD  = $reg1 ['CIUDAD'];
    $REGIONAL  = $reg1 ['REGIONAL'];
    $NOMBRE_EDS  = $reg1 ['NOMBRE_EDS'];
    $TOTAL_UND  = $reg1 ['TOTAL_UND'];
    $COSTO_UND  = $reg1 ['COSTO_UND'];
    $TOTAL  = $reg1 ['TOTAL'];
    $VLR_FACT_FEE_AIVA  = $reg1 ['VLR_FACT_FEE_AIVA'];
    $VLR_FACT_FEE  = $reg1 ['VLR_FACT_FEE']; 

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $CATEGORIA_SSCC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $PRODUCTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $REFERENCIA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $LINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $TIPO_EDS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $RAZON_SOCIAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $DIRECCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $FACTURA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $FECHA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $NIT);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $CIUDAD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $REGIONAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $NOMBRE_EDS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $TOTAL_UND);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $COSTO_UND);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $TOTAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $VLR_FACT_FEE_AIVA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $VLR_FACT_FEE);
    

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':R'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':R'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 18; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Fee_terpel_detallado_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
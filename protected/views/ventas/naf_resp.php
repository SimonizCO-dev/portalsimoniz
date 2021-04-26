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

$cons_inicial = $model['cons_inicial'];
$cons_final = $model['cons_final'];

/*inicio configuración array de datos*/

$query ="SET NOCOUNT ON EXEC P_PR_COM_NOTAS_APL_FACT
@CONSINICIAL = N'".$cons_inicial."',
@CONSFINAL = N'".$cons_final."'
";

UtilidadesVarias::log($query);

$q1 = Yii::app()->db->createCommand($query)->queryAll();

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Motivo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Nota');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Vlr. nota');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Vlr. factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Vlr. aplicado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Usuario que creo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Cantidad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Precio unitario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Vlr. bruto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Vlr. descuento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Vlr. impuesto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Vlr. neto');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$Fila = 2;

foreach ($q1 as $data) {
    
    $NIT = $data['NIT']; 
    $CLIENTE = $data['CLIENTE']; 
    $MOTIVO = $data['MOTIVO'];
    $NOTA = $data['NOTA']; 
    $FACTURA = $data['FACTURA']; 
    $VALOR_NOTA = $data['VALOR_NOTA'];
    $VALOR_FACTURA = $data['VALOR_FACTURA']; 
    $VALOR_APLICADO = $data['VALOR_APLICADO'];
    $USUARIO_CREACION = $data['USUARIO_CREACION'];
    $FECHA_CREACION = $data['FECHA_CREACION']; 
    $ITEM = $data['ITEM']; 
    $CANTIDAD = $data['CANTIDAD'];
    $PRECIO_UNITARIO = $data['PRECIO_UNITARIO'];
    $VLR_BRUTO = $data['VLR_BRUTO'];
    $VLR_DESCUENTO = $data['VLR_DESCUENTO'];
    $VLR_IMPUESTO = $data['VLR_IMPUESTO'];
    $VLR_NETO = $data['VLR_NETO'];
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $NIT);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $CLIENTE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $MOTIVO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $NOTA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $FACTURA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $VALOR_NOTA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $VALOR_FACTURA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $VALOR_APLICADO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $USUARIO_CREACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $FECHA_CREACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $CANTIDAD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $PRECIO_UNITARIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $VLR_BRUTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $VLR_DESCUENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $VLR_IMPUESTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $VLR_NETO);

    $Fila = $Fila + 1; 

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 17; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Notas_aplic_fact_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

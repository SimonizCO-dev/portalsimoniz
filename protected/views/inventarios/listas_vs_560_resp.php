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
$marca = $model['marca'];
$lista = $model['lista'];
$estado = $model['estado'];

/*inicio configuración array de datos*/

$query= "
    EXEC P_PR_COM_CONS_LISTAS_VS_560
    @ESTADO = N'".$estado."',
    @MARCA = N'".$marca."',
    @LISTA = N'".$lista."'
"; 

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CÓDIGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'CÓDIGO DE BARRAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'PRECIO LISTA 560');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'PRECIO LISTA '.$lista);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'PRECIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'FECHA VCTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'INSTALACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'INVENTARIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'UND. COMPRA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'STOCK MESES');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'UNIDAD DE NEGOCIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'ORIGEN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'cLASIFICACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'CLASE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'MARCA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'SEGMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'LÍNEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'SUB-LÍNEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'UNIDAD DE NEGOCIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'ORACLE');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:Y1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:Y1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
 
$Fila = 2;  

foreach ($query1 as $reg1) {

  $ITEM                = $reg1 ['ITEM'];
  $DESCRIPCION         = $reg1 ['I_DESCRIPCION'];
  $REFERENCIA          = $reg1 ['I_REFERENCIA'];

  if($reg1 ['I_CODIGO_BARRAS'] == NULL){
    $CODIGO_BARRAS = 'NO APLICA';
  }else{
    $CODIGO_BARRAS = $reg1 ['I_CODIGO_BARRAS'];
  }

  $ESTADO              = $reg1 ['I_ESTADO'];

  if($reg1 ['PRECIO1'] == NULL){
    $PRECIO1 = 0;
  }else{
    $PRECIO1 = $reg1 ['PRECIO1'];
  }

  if($reg1 ['PRECIO2'] == NULL){
    $PRECIO2 = 0;
  }else{
    $PRECIO2 = $reg1 ['PRECIO2'];
  }

  if($reg1 ['I_PRECIO'] == NULL){
    $PRECIO = 0;
  }else{
    $PRECIO = $reg1 ['I_PRECIO'];
  }

  if($reg1 ['FCH_VCTO'] == NULL){
    $FCH_VCTO = 'NO APLICA';
  }else{
    $FCH_VCTO = $reg1 ['FCH_VCTO'];
  }

  if($reg1 ['I_INSTALACION'] == NULL){
    $INSTALACION = 'NO APLICA';
  }else{
    $INSTALACION = $reg1 ['I_INSTALACION'];
  }

  if($reg1 ['I_INVENTARIO'] == NULL){
    $INVENTARIO = 'NO APLICA';
  }else{
    $INVENTARIO = $reg1 ['I_INVENTARIO'];
  }

  if($reg1 ['I_TIPO'] == NULL){
    $TIPO = 'NO APLICA';
  }else{
    $TIPO = $reg1 ['I_TIPO'];
  }

  if($reg1 ['I_LOTE'] == NULL){
    $UND_COMPRA = 0;
  }else{
    $UND_COMPRA = $reg1 ['I_LOTE'];
  }

  if($reg1 ['I_STOCK'] == NULL){
    $STOCK_MESES = 0;
  }else{
    $STOCK_MESES = $reg1 ['I_STOCK'];
  }

  if($reg1 ['I_UNIDAD_NEGOCIO'] == NULL){
    $UN = 'NO APLICA';
  }else{
    $UN = $reg1 ['I_UNIDAD_NEGOCIO'];
  }

  if($reg1 ['I_CRI_ORIGEN'] == NULL){
    $ORIGEN = 'NO APLICA';
  }else{
    $ORIGEN = $reg1 ['I_CRI_ORIGEN'];
  }

  if($reg1 ['I_CRI_TIPO'] == NULL){
    $CRI_TIPO = 'NO APLICA';
  }else{
    $CRI_TIPO = $reg1 ['I_CRI_TIPO'];
  }

  if($reg1 ['I_CRI_CLASIFICACION'] == NULL){
    $CLASIFICACION = 'NO APLICA';
  }else{
    $CLASIFICACION = $reg1 ['I_CRI_CLASIFICACION'];
  }

  if($reg1 ['I_CRI_CLASE'] == NULL){
    $CLASE = 'NO APLICA';
  }else{
    $CLASE = $reg1 ['I_CRI_CLASE'];
  }

  if($reg1 ['I_CRI_MARCA'] == NULL){
    $MARCA = 'NO APLICA';
  }else{
    $MARCA = $reg1 ['I_CRI_MARCA'];
  }

  if($reg1 ['I_CRI_SEGMENTO'] == NULL){
    $SEGMENTO = 'NO APLICA';
  }else{
    $SEGMENTO = $reg1 ['I_CRI_SEGMENTO'];
  }

  if($reg1 ['I_CRI_LINEA'] == NULL){
    $LINEA = 'NO APLICA';
  }else{
    $LINEA = $reg1 ['I_CRI_LINEA'];
  }

  if($reg1 ['I_CRI_SUBLINEA'] == NULL){
    $SUBLINEA = 'NO APLICA';
  }else{
    $SUBLINEA = $reg1 ['I_CRI_SUBLINEA'];
  }

  if($reg1 ['I_CRI_UNIDAD_NEGOCIO'] == NULL){
    $CRI_UNIDAD_NEGOCIO = 'NO APLICA';
  }else{
    $CRI_UNIDAD_NEGOCIO = $reg1 ['I_CRI_UNIDAD_NEGOCIO'];
  }

  if($reg1 ['I_CRI_ORACLE'] == NULL){
    $ORACLE = 'NO APLICA';
  }else{
    $ORACLE = $reg1 ['I_CRI_ORACLE'];
  }

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, substr($REFERENCIA,0,20));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, substr($DESCRIPCION,0,40));
  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('D'.$Fila, $CODIGO_BARRAS, $type_string);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, substr($ESTADO, 0, 8));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $PRECIO1);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $PRECIO2);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $PRECIO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $FCH_VCTO);
  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('J'.$Fila, $INSTALACION, $type_string);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $INVENTARIO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $TIPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $UND_COMPRA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $STOCK_MESES);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $UN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $ORIGEN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $CRI_TIPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $CLASIFICACION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $CLASE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $MARCA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $SEGMENTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $LINEA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $SUBLINEA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $CRI_UNIDAD_NEGOCIO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $ORACLE);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':H'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('M'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
  $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 25; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Lista_'.$lista.'_VS_560_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

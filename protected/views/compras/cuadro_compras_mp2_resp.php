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

//se obtiene la cadena de la fecha actual
$diatxt=date('l');
$dianro=date('d');
$mestxt=date('F');
$anionro=date('Y');
// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

$query= "
  SET NOCOUNT ON
  EXEC P_PR_COMP_MPRI
";

UtilidadesVarias::log($query);

//array con titulos de meses

$mes_act=date('F');

$clave = array_search($mes_act, $ming);

$cont = $clave - 1;

$array_titulo_meses = array();

for ($i=1; $i <= 12; $i++) { 

  $m = str_replace($ming, $mesp, $ming[$clave]);
  $mes = strtoupper($m);
  $mes_abrev = substr($mes, 0, 3);

  $array_titulo_meses[] = $mes_abrev;
  if($clave == 11){

    $clave = 0;
  
  }else{
  
    $clave++;
  
  }
}

/*fin configuración array de datos*/

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CÓDIGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'LOTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'FECHA INICIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'FECHA_FINAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', $array_titulo_meses[0]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', $array_titulo_meses[1]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', $array_titulo_meses[2]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', $array_titulo_meses[3]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', $array_titulo_meses[4]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', $array_titulo_meses[5]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', $array_titulo_meses[6]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', $array_titulo_meses[7]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', $array_titulo_meses[8]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', $array_titulo_meses[9]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', $array_titulo_meses[10]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', $array_titulo_meses[11]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'UM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'STOCK');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'PROM. VENTAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'PROM. 6M FORECAST');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'MAX. PROM. VENTAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', 'SUM. 6M FORECAST');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', 'PROM. CONSUMO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'CANT. EXIST.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', 'CANT. IMPORTACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'SUM. CANT. PEND. COMP.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE1', 'FECHA ULT. COMPRA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', 'CANT. ULT. COMPRA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG1', 'CANT. PEND. ULT. COMPRA');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:AG1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AG1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
 
$Fila = 2;  

foreach ($query1 as $reg1) {

  $ITEM                = $reg1 ['ITEM'];
  $REFERENCIA          = $reg1 ['REFERENCIA'];
  $DESCRIPCION         = $reg1 ['DESCRIPCION'];

  if($reg1 ['ESTADO'] == NULL){
    $ESTADO = '-';
  }else{
    $ESTADO = $reg1 ['ESTADO'];
  }

  if($reg1 ['TIPO'] == NULL){
    $TIPO = '-';
  }else{
    $TIPO = $reg1 ['TIPO'];
  }

  if($reg1 ['FECHA_INICIAL'] == NULL){
    $FECHA_INICIAL = '-';
  }else{
    $FECHA_INICIAL = $reg1 ['FECHA_INICIAL'];
  }

  if($reg1 ['FECHA_FINAL'] == NULL){
    $FECHA_FINAL = '-';
  }else{
    $FECHA_FINAL = $reg1 ['FECHA_FINAL'];
  }

  if($reg1 ['MES12'] == NULL){
    $MES12 = 0;
  }else{
    $MES12 = $reg1 ['MES12'];
  }

  if($reg1 ['MES11'] == NULL){
    $MES11 = 0;
  }else{
    $MES11 = $reg1 ['MES11'];
  }

  if($reg1 ['MES10'] == NULL){
    $MES10 = 0;
  }else{
    $MES10 = $reg1 ['MES10'];
  }

  if($reg1 ['MES9'] == NULL){
    $MES9 = 0;
  }else{
    $MES9 = $reg1 ['MES9'];
  }

  if($reg1 ['MES8'] == NULL){
    $MES8 = 0;
  }else{
    $MES8 = $reg1 ['MES8'];
  }

  if($reg1 ['MES7'] == NULL){
    $MES7 = 0;
  }else{
    $MES7 = $reg1 ['MES7'];
  }

  if($reg1 ['MES6'] == NULL){
    $MES6 = 0;
  }else{
    $MES6 = $reg1 ['MES6'];
  }

  if($reg1 ['MES5'] == NULL){
    $MES5 = 0;
  }else{
    $MES5 = $reg1 ['MES5'];
  }

  if($reg1 ['MES4'] == NULL){
    $MES4 = 0;
  }else{
    $MES4 = $reg1 ['MES4'];
  }

  if($reg1 ['MES3'] == NULL){
    $MES3 = 0;
  }else{
    $MES3 = $reg1 ['MES3'];
  }

  if($reg1 ['MES2'] == NULL){
    $MES2 = 0;
  }else{
    $MES2 = $reg1 ['MES2'];
  }

  if($reg1 ['MES1'] == NULL){
    $MES1 = 0;
  }else{
    $MES1 = $reg1 ['MES1'];
  }

  if($reg1 ['UM'] == NULL){
    $UM = '-';
  }else{
    $UM = $reg1 ['UM'];
  }

  if($reg1 ['STOCK'] == NULL){
    $STOCK= 0;
  }else{
    $STOCK = $reg1 ['STOCK'];
  }

  if($reg1 ['Prom_Vt'] == NULL){
    $PROM_VT= 0;
  }else{
    $PROM_VT = $reg1 ['Prom_Vt'];
  }

  if($reg1 ['Prom_FC'] == NULL){
    $PROM_FC= 0;
  }else{
    $PROM_FC = $reg1 ['Prom_FC'];
  }

  if($reg1 ['Max_Prom_Vt'] == NULL){
    $MAX_PROM_VT= 0;
  }else{
    $MAX_PROM_VT = $reg1 ['Max_Prom_Vt'];
  }

  if($reg1 ['Forecast_6M'] == NULL){
    $FORECAST_6M = 0;
  }else{
    $FORECAST_6M = $reg1 ['Forecast_6M'];
  }

  if($reg1 ['Prom_Consumo'] == NULL){
    $PROM_CONSUMO = 0;
  }else{
    $PROM_CONSUMO = $reg1 ['Prom_Consumo'];
  }

  if($reg1 ['Cant_Existencia'] == NULL){
    $CANT_EXISTENCIA = 0;
  }else{
    $CANT_EXISTENCIA = $reg1 ['Cant_Existencia'];
  }

  if($reg1 ['Cant_Importacion'] == NULL){
    $CANT_IMPORTACION = 0;
  }else{
    $CANT_IMPORTACION = $reg1 ['Cant_Importacion'];
  }

  if($reg1 ['Suma_Cant_Pend_Comp'] == NULL){
    $SUMA_CANT_PEND_COMP = 0;
  }else{
    $SUMA_CANT_PEND_COMP = $reg1 ['Suma_Cant_Pend_Comp'];
  }

  if($reg1 ['Fch_Ult_Compra'] == NULL){
    $FECHA_ULT_COMPRA = '-';
  }else{
    $FECHA_ULT_COMPRA = $reg1 ['Fch_Ult_Compra'];
  }

  if($reg1 ['Cant_Ult_Comp'] == NULL){
    $CANT_ULT_COMPRA = 0;
  }else{
    $CANT_ULT_COMPRA = $reg1 ['Cant_Ult_Comp'];
  }

  if($reg1 ['Cant_Pend_Ult_Comp'] == NULL){
    $CANT_PEND_ULT_COMPRA = 0;
  }else{
    $CANT_PEND_ULT_COMPRA = $reg1 ['Cant_Pend_Ult_Comp'];
  }

      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, substr($REFERENCIA,0,20));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, substr($DESCRIPCION,0,35 ));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, substr($ESTADO, 0, 8));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $TIPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $FECHA_INICIAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $FECHA_FINAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $MES12);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $MES11);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $MES10);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $MES9);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $MES8);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $MES7);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $MES6);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $MES5);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $MES4);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $MES3);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $MES2);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $MES1);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $UM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $STOCK);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $PROM_VT);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $PROM_FC);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $MAX_PROM_VT);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $FORECAST_6M);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $PROM_CONSUMO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $CANT_EXISTENCIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila, $CANT_IMPORTACION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $SUMA_CANT_PEND_COMP);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $FECHA_ULT_COMPRA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $CANT_ULT_COMPRA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, $CANT_PEND_ULT_COMPRA);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');   
  $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':T'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':T'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('V'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
  $objPHPExcel->getActiveSheet(0)->getStyle('V'.$Fila.':AD'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('AE'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $objPHPExcel->getActiveSheet(0)->getStyle('AF'.$Fila.':AG'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
  $objPHPExcel->getActiveSheet(0)->getStyle('AF'.$Fila.':AG'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;

}


  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 33; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Cuadro_compras_mp_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

?>

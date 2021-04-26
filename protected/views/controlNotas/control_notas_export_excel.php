<?php
/* @var $this ControlNotasController */
/* @var $model ControlNotas */

set_time_limit(0);

spl_autoload_unregister(array('YiiBase','autoload'));  

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));


$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID Nota');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Nota');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Vlr. factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '% descuento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Vlr. descuento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha Factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Fecha de pago');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Días de pago');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Recibo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Observaciones');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Respuesta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Usuario que creo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Usuario que actualizó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Fecha de actualización');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  if($reg->Id_Control == ""){
    $id_nota = '';
  }else{
    $id_nota = $reg->Id_Control;
  }

  if($reg->Id_Cliente == ""){
    $cliente = '';
  }else{
    $cliente = $reg->Desc_Cliente($reg->Id_Cliente);
  }

  if($reg->Nota == ""){
    $nota = '';
  }else{
    $nota = $reg->Nota;
  }

  if($reg->Factura == ""){
    $factura = '';
  }else{
    $factura = $reg->Factura;
  }

  if($reg->Valor_Factura == ""){
    $vlr_factura = '';
  }else{
    $vlr_factura = $reg->Valor_Factura;
  }

  if($reg->Porc_Desc == ""){
    $porc_desc = '';
  }else{
    $porc_desc = $reg->Porc_Desc;
  }

  if($reg->Valor_Descuento == ""){
    $vlr_desc = '';
  }else{
    $vlr_desc = $reg->Valor_Descuento;
  }

  if($reg->Fecha_Factura == ""){
    $fecha_factura = '';
  }else{
    $fecha_factura = $reg->Fecha_Factura;
  }

  if($reg->Fecha_Pago == ""){
    $fecha_pago = '';
  }else{
    $fecha_pago = $reg->Fecha_Pago;
  }

  if($reg->Dias_Pago == ""){
    $dias_pago = '';
  }else{
    $dias_pago = $reg->Dias_Pago;
  }

  if($reg->Recibo == ""){
    $recibo = '';
  }else{
    $recibo = $reg->Recibo;
  }

  if($reg->Observaciones == ""){
    $observaciones = '';
  }else{
    $observaciones = $reg->Observaciones;
  }

  if($reg->Respuesta == ""){
    $respuesta = '';
  }else{
    switch ($reg->Respuesta) {
      case 0:
          $respuesta = 'EN ELAB.'; 
          break;
      case 1:
          $respuesta = 'APROBADO';  
          break;
      case 2:
          $respuesta = 'NO APROBADO'; 
          break;
    }
  }

  $usuario_creacion = $reg->idusuariocre->Usuario;
  $fecha_creacion = $reg->Fecha_Creacion;
  $usuario_actualizacion = $reg->idusuarioact->Usuario;
  $fecha_actualizacion = $reg->Fecha_Actualizacion;

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$id_nota);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$cliente);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$nota);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$vlr_factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$porc_desc);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$vlr_desc);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$fecha_factura);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,$fecha_pago);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$dias_pago);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$recibo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,$observaciones);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$respuesta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,$usuario_creacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila,$fecha_creacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila,$usuario_actualizacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila,$fecha_actualizacion);

  $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('0');

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 17; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Control_notas_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
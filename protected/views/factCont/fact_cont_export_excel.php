<?php
/* @var $this PromocionController */
/* @var $model Promocion */

//EXCEL

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

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Empresa');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Área');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', '# de factura');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fecha de factura');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Fecha de radicado');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Proveedor');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Observaciones');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Valor');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Moneda');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Usuario que creo');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Fecha de creación');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Usuario que actualizó');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Fecha de actualización');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Usuario que revisó');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Fecha de Revisión');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Estado');

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

  $Fila= 2;

  /*Inicio contenido tabla*/

  foreach ($data as $reg) {

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->Id_Fact);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$reg->DescEmpresa($reg->Empresa));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$reg->area->Area);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg->Num_Factura);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,UtilidadesVarias::textofecha($reg->Fecha_Factura));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,UtilidadesVarias::textofecha($reg->Fecha_Radicado));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$reg->DescProveedor($reg->Proveedor));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,($reg->Observaciones == "") ? "-" : $reg->Observaciones);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,number_format($reg->Valor, 2));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$reg->DescMoneda($reg->Moneda));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$reg->idusuariocre->Usuario);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,UtilidadesVarias::textofechahora($reg->Fecha_Creacion));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$reg->idusuarioact->Usuario);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,UtilidadesVarias::textofechahora($reg->Fecha_Actualizacion));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila,($reg->Id_Usuario_Revision == "") ? "-" : $reg->idusuariorev->Usuario);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila,($reg->Fecha_Revision == "") ? "-" : UtilidadesVarias::textofechahora($reg->Fecha_Revision));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila,$reg->DescEstado($reg->Estado));

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':Q'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila ++;
         
  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 17; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Fact_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

?>
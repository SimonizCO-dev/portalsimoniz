<?php
/* @var $this PromocionController */
/* @var $model Promocion */

//EXCEL

  // Se inactiva el autoloader de yii
  spl_autoload_unregister(array('YiiBase','autoload'));   

  require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel.php';
  
  //cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
  spl_autoload_register(array('YiiBase','autoload'));

  $objPHPExcel = new PHPExcel();

  $objPHPExcel->getActiveSheet()->setTitle('Hoja1');
  $objPHPExcel->setActiveSheetIndex();

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'Cuenta / Usuario');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'Empleado');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Estado');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Usuario que creo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Fecha de creación');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Usuario que actualizó');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Fecha de actualización');

  $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

  $Fila= 3;

  /*Inicio contenido tabla*/

  foreach ($data as $reg) {
  
    $cuenta = $reg->DescCuentaUsuario($reg->Id_Cuenta);
    $empleado = UtilidadesEmpleado::nombreempleado($reg->Id_Empleado);
    $estado = UtilidadesVarias::textoestado1($reg->Estado);
    $usuario_creacion = $reg->idusuariocre->Usuario;
    $fecha_creacion = $reg->Fecha_Creacion;
    $usuario_actualizacion = $reg->idusuarioact->Usuario;
    $fecha_creacion = $reg->Fecha_Creacion;


    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila,$cuenta);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila,$empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila,$estado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila,$usuario_creacion);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila,$fecha_creacion);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila,$usuario_actualizacion);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila,$fecha_creacion);
    $Fila ++;
         
  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {

      $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

      $sheet = $objPHPExcel->getActiveSheet();
      $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(true);
      foreach ($cellIterator as $cell) {
          $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
      }
  }

  $n = 'Cuentas_empleado_'.date('Y-m-d H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

?>


<?php
/* @var $this ReporteCnController */
/* @var $model ReporteC */

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

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Recibo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Estado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Estado de verificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Fecha banco');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Banco correcto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Motivo de rechazo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Usuario que cargo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha y hora de carga');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Usuario que verificó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Fecha y hora de verificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Usuario que aplicó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Fecha y hora de aplicación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Usuario que verifica ent. física');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Fecha y hora de verificación ent. física  ');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:N1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:N1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $recibo = $reg->Recibo;
  $estado = $reg->Desc_Opc($reg->Opc);
  $estado_ver = ($reg->Verificacion == "") ? "-" : $reg->Desc_Verif($reg->Verificacion);
  $fecha_banco = ($reg->Fecha_Banco == "") ? "N/A" : $reg->Fecha_Banco;
  $banco_correcto = ($reg->Banco_Correcto == "") ? "-" : $reg->Desc_Banco($reg->Banco_Correcto);
  $motivo_rechazo = ($reg->Motivo_Rechazo == "") ? "-" : $reg->Desc_Motivo_Rechazo($reg->Motivo_Rechazo);
  $usuario_carga = $reg->idusuariocarga->Usuario;
  $fecha_hora_carga = $reg->Fecha_Hora_Carga;
  $usuario_verificacion = ($reg->Id_Usuario_Verif == "") ? "-" : $reg->idusuarioverif->Usuario;
  $fecha_hora_verificacion = ($reg->Fecha_Hora_Verif == "") ? "-" : $reg->Fecha_Hora_Verif;
  $usuario_aplicacion = ($reg->Id_Usuario_Aplic == "") ? "-" : $reg->idusuarioaplic->Usuario;
  $fecha_hora_aplicacion = ($reg->Fecha_Hora_Aplic == "") ? "-" : $reg->Fecha_Hora_Aplic;
  $usuario_entrega_fis = ($reg->Id_Usuario_Rec_Fis == "") ? "-" : $reg->idusuariorecfis->Usuario;
  $fecha_hora_entrega_fis = ($reg->Fecha_Hora_Rec_Fis == "") ? "-" : $reg->Fecha_Hora_Rec_Fis;

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$recibo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$estado);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$estado_ver);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$fecha_banco);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$banco_correcto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$motivo_rechazo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$usuario_carga);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$fecha_hora_carga);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,$usuario_verificacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$fecha_hora_verificacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$usuario_aplicacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,$fecha_hora_aplicacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$usuario_entrega_fis);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,$fecha_hora_entrega_fis);

  $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':N'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 14; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Estado_recibos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
<?php
/* @var $this PromocionController */
/* @var $model Promocion */

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Dominio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Link');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Usuario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Password');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Empresa administradora');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Contacto empresa administradora');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Contratado por');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Uso');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Fecha de activación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Fecha de vencimiento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Observaciones');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Estado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Usuario que creo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Usuario que actualizó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Fecha de actualización');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  if($reg->Id_Tipo == ''){
    $tipo = 'No asignado';
  }else{
    $tipo = $reg->idtipo->Dominio;
  }

  $dominio = $reg->Dominio;
  $link = $reg->Link;
  $usuario = $reg->Usuario;
  $password = $reg->Password;
  $ea = $reg->Empresa_Administradora;
  $cea = $reg->Contacto_Emp_Adm;
  $cp = $reg->Contratado_Por;
  $uso = $reg->Uso;
  $fecha_activacion = UtilidadesVarias::textofecha($reg->Fecha_Activacion);
  $fecha_vencimiento = UtilidadesVarias::textofecha($reg->Fecha_Vencimiento);

  if($reg->Observaciones == ''){
    $observaciones = 'No asignado';
  }else{
    $observaciones = $reg->Observaciones;
  }

  $estado = UtilidadesVarias::textoestado1($reg->Estado);

  $usuario_creacion = $reg->idusuariocre->Usuario;
  $fecha_creacion = UtilidadesVarias::textofechahora($reg->Fecha_Creacion);

  $usuario_actualizacion = $reg->idusuarioact->Usuario;
  $fecha_actualizacion = UtilidadesVarias::textofechahora($reg->Fecha_Actualizacion);

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$tipo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$link);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$usuario);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$password);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$ea);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$cea);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$cp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,$uso);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$fecha_activacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$fecha_vencimiento);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,$observaciones);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$estado);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,$usuario_creacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila,$fecha_creacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila,$usuario_actualizacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila,$fecha_actualizacion);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':Q'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 17; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Dominios_Web_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit; 

?>
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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Clasif.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Cuenta / Usuario');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Dominio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Password');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Tipo de cuenta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Tipo de acceso');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Cuenta red.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Observaciones');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Estado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Usuario que creo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Ultimo usuario que actualizó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Ultima fecha de actualización');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:N1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:N1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $id_cuenta = $reg->Id_Cuenta;
  $clasificacion = $reg->clasificacion->Dominio;
  $cuenta = $reg->DescCuentaUsuario($reg->Id_Cuenta);
  
  if($reg->Dominio == NULL){
    $dominio = '-';
  }else{
    $dominio = $reg->dominioweb->Dominio;
  }

  $password = $reg->Password;

  if($reg->Tipo_Cuenta == NULL){
    $tipo_cuenta = '-';
  }else{
    $tipo_cuenta = $reg->tipocuenta->Dominio;
  }

  if($reg->Tipo_Acceso == NULL){
    $tipo_acceso = '-';
  }else{
    $tipo_acceso = $reg->DescTipoAcceso($reg->Tipo_Acceso);
  }

  if($reg->Id_Cuenta_Red == NULL){
    $cuenta_red = '-';
  }else{
    $cuenta_red = $reg->DescCuentaUsuario($reg->Id_Cuenta_Red);
  }

  if($reg->Observaciones == NULL){
    $observaciones = '-';
  }else{
    $observaciones = $reg->Observaciones;
  }

  $estado = $reg->estado->Dominio;
  $usuario_creacion = $reg->idusuariocre->Usuario;
  $fecha_creacion = $reg->Fecha_Creacion;
  $usuario_actualizacion = $reg->idusuarioact->Usuario;
  $fecha_creacion = $reg->Fecha_Creacion;


  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$id_cuenta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$clasificacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$cuenta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$dominio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$password);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$tipo_cuenta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$tipo_acceso);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$cuenta_red);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,$observaciones);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$estado);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$usuario_creacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,$fecha_creacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$usuario_actualizacion);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,$fecha_creacion);
  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 14; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Cuentas_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
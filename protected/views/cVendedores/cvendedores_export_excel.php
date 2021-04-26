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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'ROW ID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Nombre');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'E-mail');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'E-mail personal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Celular');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Ciudad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'ID Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Recibo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Nombre ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Portafolio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Nit supervisor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Nombre supervisor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Ultimo usuario que actualizó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'Ultima fecha de actualización');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'Estado');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $id = $reg->ID;
  $rowid = $reg->ROWID;
  $nit_vendedor = $reg->NIT_VENDEDOR;
  $nombre_vendedor = $reg->NOMBRE_VENDEDOR;
  if($reg->EMAIL == "" ) { $email = "NO ASIGNADO"; } else { $email = $reg->EMAIL; }
  if($reg->EMAIL_PERSONAL == "" ) { $email_personal = "NO ASIGNADO"; } else { $email_personal = $reg->EMAIL_PERSONAL; }
  if($reg->TELEFONO == "" ) { $telefono = "NO ASIGNADO"; } else { $telefono = $reg->TELEFONO; }
  if($reg->CIUDAD == "" ) { $ciudad = "NO ASIGNADO"; } else { $ciudad = $reg->CIUDAD; }
  $id_vendedor = $reg->ID_VENDEDOR;
  $recibo = $reg->RECIBO;
  $ruta = $reg->RUTA;
  $nombre_ruta = $reg->NOMBRE_RUTA;
  $portafolio = $reg->PORTAFOLIO;
  $nit_supervisor = $reg->NIT_SUPERVISOR;
  $nombre_supervisor = $reg->NOMBRE_SUPERVISOR;
  if($reg->TIPO == "" ) { $tipo = "NO ASIGNADO"; } else { $tipo = $reg->tipo->Dominio; }
  $usuario_act = $reg->idusuarioact->Usuario;
  $fecha_act = UtilidadesVarias::textofechahora($reg->FECHA_ACTUALIZACION);
  $estado = $reg->ESTADO;

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$id);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$rowid);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$nit_vendedor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$nombre_vendedor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$email);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$email_personal);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$telefono);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$ciudad);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,$id_vendedor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$recibo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$ruta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,$nombre_ruta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$portafolio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,$nit_supervisor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila,$nombre_supervisor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila,$tipo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila,$usuario_act);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila,$fecha_act);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila,$estado);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':S'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 20; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Vendedores_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
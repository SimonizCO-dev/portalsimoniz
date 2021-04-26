<?php
/* @var $this PromocionController */
/* @var $model Promocion */

//EXCEL

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'WIP');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '#');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'CADENA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'INVENTARIO TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'DE 0 A_30 DÍAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'DE 31 A 60_DÍAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'DE 61 A 90 DÍAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'MÁS DE 90 DÍAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'FECHA DE SOLICITUD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'FECHA ENTREGA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'CANT. A ARMAR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'CANT. ORDEN PROD.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'CANT. SIN ENTREGAR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'RESPONSABLE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'DÍAS DE VENCIMIENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'ESTADO COMERCIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'UN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'SUB-MARCA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'FAMILIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'SUB-FAMILIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'GRUPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'ORACLE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', 'CADENA A PRESTAR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', 'CANTIDAD DE PRESTAMO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'CANT. VEND.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', 'FECHA CUMPLIDO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'OBSERVACIONES');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:AD1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AD1')->getFont()->setBold(true);

$Fila= 2;

/*Inicio contenido tabla*/


$wip_ant = "";
$wip_act = "";

$cons = 0;

foreach ($data as $reg) {

  if($wip_ant == ""){
    $wip_ant = $reg->WIP;
    $wip_act = $reg->WIP;
    $cons = 1;
  }else{

    $wip_act = $reg->WIP;

    if($wip_act == $wip_ant){
      $wip_ant = $reg->WIP;
      $cons ++;
    }else{
      $wip_ant = $reg->WIP;
      $cons = 1;
    }

  }

  $cadena = $reg->desccadena($reg->ID);

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg->WIP);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$cons);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$cadena);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg->ID_ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$reg->DESCRIPCION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$reg->ESTADO_COMERCIAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$reg->INVENTARIO_TOTAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila,$reg->DE_0_A_30_DIAS);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila,$reg->DE_31_A_60_DIAS);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila,$reg->DE_61_A_90_DIAS);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila,$reg->MAS_DE_90_DIAS);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila,$reg->FECHA_SOLICITUD_WIP);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila,$reg->FECHA_ENTREGA_WIP);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila,$reg->CANT_A_ARMAR);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila,$reg->CANT_OC_AL_DIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila,$reg->CANT_PENDIENTE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila,$reg->RESPONSABLE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila,$reg->DIAS_VENCIMIENTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila,$reg->ESTADO_COMERCIAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila,$reg->UN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila,$reg->SUB_MARCA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila,$reg->FAMILIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila,$reg->SUB_FAMILIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila,$reg->GRUPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila,$reg->ORACLE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila,$reg->REDISTRIBUCION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila,$reg->PTM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila,$reg->CANT_VEND);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila,$reg->FECHA_CUMPLIDO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila,$reg->OBSERVACIONES);

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 30; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Wip_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>


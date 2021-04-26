<?php
/* @var $this PromocionController */
/* @var $model Promocion */

//EXCEL

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Promoción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Componente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Cantidad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Usuario que creo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Usuario que actualizó');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Fecha de actualización');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:G1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:G1')->getFont()->setBold(true);

$Prom = "";
$Fila= 3;

/*Inicio contenido tabla*/

foreach ($data as $reg) {

  $desc= Yii::app()->db->createCommand("
      SELECT CONCAT (TIP.I_ID_ITEM, ' - ', TIP.I_DESCRIPCION) AS PROMOCION, CONCAT (TIH.I_ID_ITEM, ' - ', TIH.I_DESCRIPCION) AS COMPONENTE, t.Cantidad AS CANTIDAD , t.Fecha_Creacion AS FEC_CRE, TUC.Usuario AS USUA_CRE, t.Fecha_Actualizacion AS FEC_ACT, TUC.Usuario AS USUA_ACT
      FROM T_PR_PROMOCION T 
      INNER JOIN T_CF_ITEMS TIP ON t.Id_Item_Padre = TIP.I_ID_ITEM
      INNER JOIN T_CF_ITEMS TIH ON t.Id_Item_Hijo = TIH.I_ID_ITEM
      INNER JOIN T_PR_USUARIO TUC ON t.Id_Usuario_Creacion = TUC.Id_Usuario
      INNER JOIN T_PR_USUARIO TUA ON t.Id_Usuario_Actualizacion = TUC.Id_Usuario
      WHERE t.Id_Promocion = ".$reg->Id_Promocion."
  ")->queryRow();

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$desc['PROMOCION']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$desc['COMPONENTE']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$desc['CANTIDAD']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$desc['USUA_CRE']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$desc['FEC_CRE']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,$desc['USUA_ACT']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila,$desc['FEC_ACT']);

  $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 7; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Promocion_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;
  
?>


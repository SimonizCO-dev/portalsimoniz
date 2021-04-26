<?php
/* @var $this IExistenciaController */
/* @var $model IExistencia */

set_time_limit(0);

//EXCEL

//se reciben los parametros para el reporte
if (isset($model['linea'])) { 
  
  $linea = implode(",", $model['linea']);

  $cond = "AND t1.Id_Linea IN (".$linea.")";

} else { 
  
  $cond = "";

}

$query= "
  SELECT t2.Descripcion AS Linea, t1.Id_Item, t1.Referencia, t1.Descripcion, t1.UND_Medida 
  FROM T_PR_I_ITEM t1 
  LEFT JOIN T_PR_I_LINEA t2 ON t1.Id_Linea = t2.Id
  WHERE t1.Estado = 1 ".$cond."
  ORDER BY 1, 4
";

$q1 = Yii::app()->db->createCommand($query)->queryAll();

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Línea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Código');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Referencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Descripción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Und. medida');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Cant. verificada');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getFont()->setBold(true);

$Fila = 2;

/*Inicio contenido tabla*/

foreach ($q1 as $reg1) {

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila,$reg1 ['Linea']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila,$reg1 ['Id_Item']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila,$reg1 ['Referencia']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila,$reg1 ['Descripcion']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila,$reg1 ['UND_Medida']);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila,'_______');

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila ++;
       
}

/*fin contenido tabla*/


//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 6; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Listado_inv_fisico_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
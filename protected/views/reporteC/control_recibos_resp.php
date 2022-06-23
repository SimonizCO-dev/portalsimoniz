<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte
$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'N°. recibos cargados');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'N°. recibos verificados');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'N°. recibos aplicados');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:D1')->getFont()->setBold(true);

$Fila = 2;

$f_inicial = new DateTime($fecha_inicial);
$f_final = new DateTime($fecha_final);
$diff = $f_inicial->diff($f_final);

$diff_days = $diff->days + 1;

$total_rc = 0;
$total_rv = 0;
$total_ra = 0;

for ($i=0; $i < $diff_days; $i++) { 
  $fecha = date($fecha_inicial);
  $nueva_fecha = strtotime('+'.$i.' day' , strtotime ($fecha)) ;
  $nueva_fecha = date ( 'Y-m-d' , $nueva_fecha);

  $r_i = $nueva_fecha.' 00:00:00';
  $r_f = $nueva_fecha.' 23:59:59';

  //recibos cargados
  $rc = Yii::app()->db->createCommand("SELECT COUNT(Id_Control) AS Rec_Carg FROM T_PR_CONTROL_RECIBOS WHERE Fecha_Hora_Carga BETWEEN '$r_i' AND '$r_f'")->queryRow();
  $recibos_cargados = $rc['Rec_Carg'];

  $total_rc = $total_rc + $recibos_cargados; 

  //recibos verificados
  $rv = Yii::app()->db->createCommand("SELECT COUNT(Id_Control) AS Rec_Verif FROM T_PR_CONTROL_RECIBOS WHERE Fecha_Hora_Verif BETWEEN '$r_i' AND '$r_f'")->queryRow();
  $recibos_verificados = $rv['Rec_Verif'];

  $total_rv = $total_rv + $recibos_verificados; 

  //recibos aplicados
  $rv = Yii::app()->db->createCommand("SELECT COUNT(Id_Control) AS Rec_Aplic FROM T_PR_CONTROL_RECIBOS WHERE Fecha_Hora_Aplic BETWEEN '$r_i' AND '$r_f'")->queryRow();
  $recibos_aplicados = $rv['Rec_Aplic'];

  $total_ra = $total_ra + $recibos_aplicados; 

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, UtilidadesVarias::textofecha($nueva_fecha));
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $recibos_cargados);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $recibos_verificados);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $recibos_aplicados);
      
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  
  $Fila = $Fila + 1; 

}

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $total_rc);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $total_rv);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $total_ra);

$objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_right);
$objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getFont()->setBold(true);

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 4; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_control_recibos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
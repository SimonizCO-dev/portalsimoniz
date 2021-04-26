<?php
/* @var $this ReporteCController */
/* @var $model ReporteC */

set_time_limit(0);

$query ="
  SET NOCOUNT ON
  EXEC P_PR_CRC_CONS_RECIBOS
";

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Número físico 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Ruta de recibo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Fecha de creación 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Usuario 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Número físico 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Recibo siesa');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Fecha de creación 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha de aprobación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Usuario 2');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    if($reg1 ['NUMERO_FISICO1'] == 0 ){
      $NUMERO_FISICO1 = ""; 
    }else{
      $NUMERO_FISICO1 = $reg1 ['NUMERO_FISICO1']; 
    } 

    $RUTA_RECIBO  = $reg1 ['RUTA_RECIBO'];

    if($reg1 ['FECHA_CREACION1'] == ""){
      $FECHA_CREACION1 = "";
    }else{
      $FECHA_CREACION1 = date("Y-m-d", strtotime($reg1 ['FECHA_CREACION1']));
    }

    $USUARIO1  = $reg1 ['USUARIO1'];
    
    if($reg1 ['NUMERO_FISICO2'] == 0 ){
      $NUMERO_FISICO2 = ""; 
    }else{
      $NUMERO_FISICO2 = $reg1 ['NUMERO_FISICO2']; 
    } 

    $RECIBO_SIESA  = $reg1 ['RECIBO_SIESA'];

    if($reg1 ['FECHA_CREACION2'] == ""){
      $FECHA_CREACION2 = "";
    }else{
      $FECHA_CREACION2 = date("Y-m-d", strtotime($reg1 ['FECHA_CREACION2']));
    }

    if($reg1 ['FECHA_APROBACION'] == ""){
      $FECHA_APROBACION = "";
    }else{
      $FECHA_APROBACION = date("Y-m-d", strtotime($reg1 ['FECHA_APROBACION']));
    }

    $USUARIO2  = $reg1 ['USUARIO2'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $NUMERO_FISICO1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $RUTA_RECIBO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $FECHA_CREACION1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $USUARIO1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $NUMERO_FISICO2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $RECIBO_SIESA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $FECHA_CREACION2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $FECHA_APROBACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $USUARIO2);
        
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    
    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 9; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_verificacion_recibos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
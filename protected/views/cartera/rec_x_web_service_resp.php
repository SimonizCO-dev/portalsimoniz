<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

//se reciben los parametros para el reporte
$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

//se obtiene la cadena de la fecha actual
$diatxt=date('l');
$dianro=date('d');
$mestxt=date('F');
$anionro=date('Y');
// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$diaesp=str_replace($ding, $desp, $diatxt);
$mesesp=str_replace($ming, $mesp, $mestxt);

$fecha_act= $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;

/*inicio configuración array de datos*/

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);
/*inicio configuración array de datos*/

$query ="
  EXEC P_PR_COM_REC_WS
  @FECHA_INI = N'".$FechaM1."',
  @FECHA_FIN = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'RCB');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'FECHA RCB');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'CUS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'N° IDENTIFICACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'BANCO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'VLR. PAGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'FECHA DOCTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'FECHA PAGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'FECHA NOTIFICADO');


$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $ID                 = $reg1 ['Id'];
    $RCB                = $reg1 ['RCB'];
    $FECHA_RCB          = $reg1 ['FECHA_RCB']; 
    $REFERENCIA         = $reg1 ['Referencia']; 
    $CUS                = $reg1 ['CUS']; 
    $N_IDENTIFICACION   = $reg1 ['Numero_Identificacion'];
    $CLIENTE            = $reg1 ['Nom_Cliente'];
    $BANCO              = $reg1 ['Banco'];
    $VALOR_PAGO         = $reg1 ['Valor_Pago'];
    $FECHA_DOCUMENTO    = $reg1 ['Fecha_Documento'];
    $FECHA_PAGO         = $reg1 ['Fecha_Pago'];
    $FECHA_NOTIFICADO   = $reg1 ['Fecha_Notificado'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ID);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $RCB);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $FECHA_RCB);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $REFERENCIA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $CUS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $N_IDENTIFICACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $CLIENTE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $BANCO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $VALOR_PAGO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $FECHA_DOCUMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $FECHA_PAGO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $FECHA_NOTIFICADO);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 12; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Recaudos_X_Web_Service_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

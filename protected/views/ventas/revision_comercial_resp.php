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
$fecha_inicial = $model->fecha_inicial;
$fecha_final = $model->fecha_final;
$marca = trim($model->marca);

//opcion: 1. PDF, 2. EXCEL
$opcion = $model->opcion_exp;

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

$query= "
EXEC P_PR_COM_CONS_VENT_ITEM
    @FECHA1 = N'".$FechaM1."',
    @FECHA2 = N'".$FechaM2."',
    @I_CRI_MARCA = N'".$marca."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

$objPHPExcel->getActiveSheet(0)->mergeCells('A1:AB1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Criterio de búsqueda: Fecha del '.$fecha_inicial.' al '.$fecha_final.', Marca: '.$marca);
$objPHPExcel->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true);

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'CO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'FECHA MOVTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'DOCUMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'UN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'MARCA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', 'LINEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', 'SUB-LINEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'ORACLE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'DESCUENTO PROM.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'PRECIO 560');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', 'MARGEN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', 'CANTIDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', 'VLR BRUTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', 'VLR DESCUENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R3', 'VLR SUBTOTAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S3', 'VLR IMPUESTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T3', 'VLR NETO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U3', 'UTIL. PROM.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V3', 'CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W3', 'ESTRUCTURA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X3', 'SEGMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y3', 'RUTA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z3', 'VENDEDOR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA3', 'CIUDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB3', 'DIRECCIÓN');

$objPHPExcel->getActiveSheet(0)->getStyle('A3:AB3')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A3:AB3')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
 
$Fila = 4;  

foreach ($query1 as $reg1) {
  
  $CO               = $reg1 ['CO'];
  $FECHA_MOVTO        = $reg1 ['FECHA_MOVTO'];
  $DOCUMENTO        = $reg1 ['DOCUMENTO'];   
  $ITEM              = $reg1 ['ITEM'];   
  $DESCRIPCION             = $reg1 ['DESCRIPCION']; 
  $REFERENCIA           = $reg1 ['REFERENCIA']; 
  $UN           = $reg1 ['UN'];  
  $MARCA         = $reg1 ['MARCA']; 
  $LINEA          = $reg1 ['LINEA'];  
  $SUBLINEA         = $reg1 ['SUBLINEA'];
  $ORACLE           = $reg1 ['ORACLE'];    
  $DESCUENTO_PROM         = $reg1 ['DESCUENTO_PROM']; 
  $PRECIO_560          = $reg1 ['PRECIO_560']; 
  $MARGEN           = $reg1 ['MARGEN']; 
  $CANTIDAD                = $reg1 ['CANTIDAD']; 
  $VLR_BRUTO       = $reg1 ['VLR_BRUTO']; 
  $VLR_DESCUENTO       = $reg1 ['VLR_DESCUENTO'];
  $VLR_SUBTOTAL       = $reg1 ['VLR_SUBTOTAL'];
  $VLR_IMPUESTO       = $reg1 ['VLR_IMPUESTO'];
  $VLR_NETO       = $reg1 ['VLR_NETO'];
  $UTIL_PROM       = $reg1 ['UTIL_PROM'];
  $CLIENTE       = $reg1 ['CLIENTE'];
  $ESTRUCTURA       = $reg1 ['ESTRUCTURA'];
  $SEGMENTO       = $reg1 ['SEGMENTO']; 
  $RUTA       = $reg1 ['RUTA']; 
  $VENDEDOR       = $reg1 ['VENDEDOR']; 
  $CIUDAD       = $reg1 ['CIUDAD']; 
  $DIRECCION       = $reg1 ['DIRECCION']; 

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $CO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $FECHA_MOVTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $DOCUMENTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $DESCRIPCION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $REFERENCIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $UN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $MARCA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $LINEA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $SUBLINEA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ORACLE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $DESCUENTO_PROM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $PRECIO_560);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $MARGEN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $CANTIDAD);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $VLR_BRUTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $VLR_DESCUENTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $VLR_SUBTOTAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $VLR_IMPUESTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $VLR_NETO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $UTIL_PROM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $CLIENTE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ESTRUCTURA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $SEGMENTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $RUTA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $VENDEDOR);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $CIUDAD);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $DIRECCION);


  $objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila.':U'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':K'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila.':U'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('V'.$Fila.':AB'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila = $Fila + 1;


}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 28; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Revision_comercial_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

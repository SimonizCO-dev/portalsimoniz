<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

/*inicio configuración array de datos*/

$query ="EXEC P_PR_CRM_CONS_USUARIO3";

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CONSECUTIVO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'N° RECLAMACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'FECHA DE CREACIÓN PQRS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'TIPIFICACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'CASO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'USUARIO EJECUCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'TIPO DE CLIENTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'RAZÓN SOCIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'SUCURSAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'FACTURA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'NIT');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'DIRECCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'PAÍS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'DEPTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'CIUDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'E-MAIL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'TElÉFONO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'TIPO DE SOLUCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'FECHA DE CREACIÓN GESTIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'FECHA DE ACTUALIZACIÓN GESTIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'GESTIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'USUARIO CREACIÓN DE GESTIÓN');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:W1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:W1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $CONSECUTIVO = $reg1 ['CONSECUTIVO']; 
    $ESTADO = $reg1 ['ESTADO']; 
    $NUM_RECLAMACION = $reg1 ['NUM_RECLAMACION'];
    $FECH_CR_PQRS = $reg1 ['FECH_CR_PQRS'];
    $TIPIFICACION = $reg1 ['TIPIFICACION'];
    $CASO = $reg1 ['CASO'];
    $USUARIO_EJECUCION = $reg1 ['USUARIO_EJECUCION'];
    $TIPO_CLIENTE = $reg1 ['TIPO_CLIENTE'];
    $RAZON_SOCIAL = $reg1 ['RAZON_SOCIAL']; 
    $SUCURSAL = $reg1 ['SUCURSAL']; 
    $FACTURA = $reg1 ['FACTURA'];
    $NIT = $reg1 ['NIT'];
    $DIRECCION = $reg1 ['DIRECCION'];
    $PAIS = $reg1 ['PAIS'];
    $DEPTO = $reg1 ['DEPTO'];
    $CIUDAD = $reg1 ['CIUDAD'];
    $EMAIL = $reg1 ['EMAIL']; 
    $TELEFONO = $reg1 ['TELEFONO']; 
    $TIPO_SOLUCION = $reg1 ['TIPO_SOLUCION'];
    $FECH_CR_GESTION = $reg1 ['FECH_CR_GESTION'];
    $FECH_AC_GESTION = $reg1 ['FECH_AC_GESTION'];
    $GESTION = $reg1 ['GESTION'];
    $USUARIO_CREACION_GESTION = $reg1 ['USUARIO_CREACION_GESTION'];
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $CONSECUTIVO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ESTADO);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('C'.$Fila, $NUM_RECLAMACION, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $FECH_CR_PQRS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $TIPIFICACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $CASO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $USUARIO_EJECUCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $TIPO_CLIENTE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $RAZON_SOCIAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $SUCURSAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $FACTURA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $NIT);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $DIRECCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $PAIS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $DEPTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $CIUDAD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $EMAIL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $TELEFONO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $TIPO_SOLUCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $FECH_CR_GESTION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $FECH_AC_GESTION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $GESTION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $USUARIO_CREACION_GESTION);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':W'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 23; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'PQRS_detalle_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

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

$query ="EXEC P_PR_CRM_CONS_USUARIO1";

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'FECHA DE CREACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'NÚMERO WEB');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'MEDIO CAPTACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'NIT SIESA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'VENTAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'CLIENTE ERP');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'RAZÓN SOCIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'CONTACTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'CIUDAD');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'DEPTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'PAÍS');


$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $FECHA_CREACION = $reg1 ['FECHA_CREACION']; 
    $NUMERO_WEB = $reg1 ['NUMERO_WEB']; 
    $MEDIO_CAPTACION = $reg1 ['MEDIO_CAPTACION'];
    $NIT_SIESA = $reg1 ['NIT_SIESA'];
    $VENTAS = $reg1 ['VENTAS'];
    $CLIENTE_ERP = $reg1 ['CLIENTE_ERP'];
    $RAZON_SOCIAL = $reg1 ['RAZON_SOCIAL']; 
    $CONTACTO = $reg1 ['CONTACTO'];
    $CIUDAD = $reg1 ['CIUDAD'];
    $DEPARTAMENTO = $reg1 ['DEPARTAMENTO'];
    $PAIS = $reg1 ['PAIS'];
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $FECHA_CREACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $NUMERO_WEB);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $MEDIO_CAPTACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $NIT_SIESA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $VENTAS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $CLIENTE_ERP);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $RAZON_SOCIAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $CONTACTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $CIUDAD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $DEPARTAMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $PAIS);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':K'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 11; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Clientes_CRM_Siesa_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

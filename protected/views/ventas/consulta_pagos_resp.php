<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

/*inicio configuración array de datos*/

//EXCEL

$query ="
  SELECT DISTINCT 
  t1.Rowid AS Rowid
  ,Descr_Msj AS Banco
  ,Num_Ident AS Nit_Cliente
  ,Nom_Cliente AS Cliente
  ,Referencia AS Factura
  ,Num_Fact AS Numero_Factura
  ,Mod_Pago AS Medio_Pago
  ,Estado AS Estado
  ,Valor_Pago AS Valor
  ,Cus AS Referencia_Pago
  ,ts_fecha AS Fecha_Reporte 
  ,CASE WHEN ISNULL(INTEGRADO,0) = 0 THEN 'SIN REPORTE' WHEN ISNULL(INTEGRADO,0)=1 THEN 'PENDIENTE' WHEN ISNULL(INTEGRADO,0)=2 THEN 'CARGADO' END AS Reportado
  from Pagos_Inteligentes..T_PSE AS t1
  LEFT JOIN [Repositorio_Datos].[dbo].[T_IN_Recibos_Caja] AS t2 ON t1.Id_Cliente = t2.F350_ID_TERCERO AND t1.Cus = t2.F357_REFERENCIA AND t1.Referencia = t2.F350_NOTAS
  ORDER BY 1 DESC
";

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Row Id');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Banco');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '# Factura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Medio de pago');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Estado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Valor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Ref. pago');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Fecha reporte');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Reportado');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $Rowid  = $reg1 ['Rowid']; 
    $Banco  = $reg1 ['Banco']; 
    $Nit_Cliente  = $reg1 ['Nit_Cliente']; 
    $Cliente  = $reg1 ['Cliente']; 
    $Factura  = $reg1 ['Factura']; 
    $Numero_Factura  = $reg1 ['Numero_Factura']; 
    $Medio_Pago  = $reg1 ['Medio_Pago']; 
    $Estado  = $reg1 ['Estado']; 
    $Valor  = $reg1 ['Valor'];
    $Referencia_Pago  = $reg1 ['Referencia_Pago']; 
    $Fecha_Reporte  = $reg1 ['Fecha_Reporte']; 
    $Reportado  = $reg1 ['Reportado']; 

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $Rowid);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $Banco);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $Nit_Cliente);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $Cliente);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $Factura);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Numero_Factura);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Medio_Pago);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Estado);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $Valor);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Referencia_Pago);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Fecha_Reporte);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Reportado);
        
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila)->getNumberFormat()->setFormatCode('0');
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

$n = 'Consulta_pagos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












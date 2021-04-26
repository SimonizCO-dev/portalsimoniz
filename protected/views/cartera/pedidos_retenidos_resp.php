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

$query ="SET NOCOUNT ON EXEC P_PR_FIN_PED_RET";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Estructura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Regional');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Docto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Fecha retenido');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Calificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Retenido cupo ?');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Retenido mora ?');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Vlr. neto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Cupo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Saldo cartera');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Max. mora');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Saldo mora');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Saldo favor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'Cupo adicional');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'C - P');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'Liberar');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'PQRS');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:U1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:U1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$Fila = 2; 

foreach ($query1 as $reg) {

	$estructura = $reg['Estructura'];
	$regional = $reg['Regional'];
	$vendedor = $reg['Vendedor'];
	$nit = $reg['Nit'];
	$cliente = $reg['Cliente'];
	$ruta = $reg['Ruta'];
	$docto = $reg['Docto'];
	$fecha_retenido = $reg['Fecha_Retenido'];
	$calificacion = $reg['Calificacion'];
	$retenido_cupo = $reg['Retenido_Cupo'];
	$retenido_mora = $reg['Retenido_Mora'];
	$vlr_neto = $reg['VNeto'];
	$cupo = $reg['Cupo'];
	$saldo_cartera = $reg['Saldo_Cartera'];
	$max_mora = $reg['Max_Mora'];
	$saldo_mora = $reg['Saldo_Mora'];
	$saldo_favor = $reg['Saldo_Favor'];
	$cupo_adicional = $reg['Cupo_adicional'];
	$c_p = $reg['C_P'];
	$liberar = $reg['Liberar'];
	$pqrs = $reg['PQRS'];

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $estructura);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $regional);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $vendedor);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $nit);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $cliente);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $ruta);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $docto);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $fecha_retenido);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $calificacion);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $retenido_cupo);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $retenido_mora);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $vlr_neto);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $cupo);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $saldo_cartera);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $max_mora);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $saldo_mora);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $saldo_favor);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $cupo_adicional);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $c_p);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $liberar);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $pqrs);


	$objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':K'.$Fila)->getAlignment()->setHorizontal($alignment_left);
	$objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila.':S'.$Fila)->getAlignment()->setHorizontal($alignment_right);
	$objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila.':N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getNumberFormat()->setFormatCode('0');
	$objPHPExcel->getActiveSheet(0)->getStyle('P'.$Fila.':S'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet(0)->getStyle('T'.$Fila.':U'.$Fila)->getAlignment()->setHorizontal($alignment_left);   

	$Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 21; 

foreach (range(0, $nCols) as $col) {
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Pedidos_retenidos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
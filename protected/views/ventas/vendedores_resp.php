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

/*inicio configuración array de datos*/

//EXCEL

$query ="SET NOCOUNT ON EXEC P_PR_COM_CONS_VENDEDORES";

UtilidadesVarias::log($query);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Código');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Estado vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Celular');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Correo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Recibo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Nombre ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Estado ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Portafolio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Coordinador');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $nit              = $reg1 ['Nit_Vendedor']; 
    $nombre_vendedor  = $reg1 ['Nombre_Vendedor']; 
    $codigo           = $reg1 ['Codigo'];
    $estado_vendedor  = $reg1 ['Estado_Vendedor'];
    $celular          = $reg1 ['Celular'];
    $correo           = $reg1 ['Correo'];
    $recibo           = $reg1 ['Recibo'];
    $ruta             = $reg1 ['Ruta'];
    $nombre_ruta      = $reg1 ['Nombre_Ruta'];
    $estado_ruta      = $reg1 ['Estado_Ruta'];
    $portafolio       = $reg1 ['Portafolio'];
    $coordinador      = $reg1 ['Coordinador'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $nit);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $nombre_vendedor);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $codigo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $estado_vendedor);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $celular);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $correo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $recibo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $ruta);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $nombre_ruta);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $estado_ruta);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $portafolio);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $coordinador);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 12; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_vendedores_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

<?php
/* @var $this ReporteThController */
/* @var $model ReporteTh */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

/*inicio configuración array de datos*/

//EXCEL

$query ="
SELECT 
Identificacion
,LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(CAST(Apellido as nvarchar(500)),CHAR(9),''),CHAR(10),''),CHAR(13),''))) as Apellido
,LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(CAST(Nombre   as nvarchar(500)) ,CHAR(9),''),CHAR(10),''),CHAR(13),''))) as Nombre
,Fecha
,Dominio as Tipo
,Puntaje
,LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(CAST(t3.Observacion as nvarchar(500)),CHAR(9),''),CHAR(10),''),CHAR(13),'')))as Observacion
FROM T_PR_EMPLEADO as t1
inner join T_PR_CONTRATO_EMPLEADO as t2 on t2.Id_Empleado=t1.Id_Empleado and t2.Fecha_Retiro is null
inner join T_PR_EVALUACION_EMPLEADO as t3 on t3.Id_Empleado=t2.Id_Empleado
inner join T_PR_DOMINIO as t4 on t4.Id_Dominio=t3.Id_Tipo
order by 2,4
";

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'No. identificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Empleado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Puntaje');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Observaciones');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getAlignment()->setHorizontal($alignment_left);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:F1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $identificacion   = $reg1 ['Identificacion']; 
    $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre']; 
    $fecha            = $reg1 ['Fecha'];
    $tipo             = $reg1 ['Tipo'];
    $puntaje          = $reg1 ['Puntaje'];
    $observaciones    = $reg1 ['Observacion'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $identificacion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $fecha);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $tipo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $puntaje);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $observaciones);
        
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 6; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Evaluaciones_empleados_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












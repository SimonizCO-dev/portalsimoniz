<?php
/* @var $this ReporteThController */
/* @var $model ReporteTh */

set_time_limit(0);

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
FROM Nomina_Real..TH_EMPLEADO as t1
inner join TH_CONTRATO_EMPLEADO as t2 on t2.Id_Empleado=t1.Id_Empleado and t2.Fecha_Retiro is null
inner join TH_EVALUACION_EMPLEADO as t3 on t3.Id_Empleado=t2.Id_Empleado
inner join TH_DOMINIO as t4 on t4.Id_Padre=252 and t4.Id_Dominio=t3.Id_Tipo
order by 2,4
";

// Se inactiva el autoloader de yii
spl_autoload_unregister(array('YiiBase','autoload'));   

require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel.php';

//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
spl_autoload_register(array('YiiBase','autoload'));

$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
$objPHPExcel->setActiveSheetIndex();

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'No. identificación');
$objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'Empleado');
$objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Fecha');
$objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Tipo');
$objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Puntaje');
$objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Observaciones');

$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

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

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $identificacion);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $fecha);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $tipo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $puntaje);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $observaciones);
        
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {

    $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
}

$n = 'Evaluaciones_empleados_'.date('Y-m-d H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












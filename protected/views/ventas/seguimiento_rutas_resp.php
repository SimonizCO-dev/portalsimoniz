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

$anio = $model['anio'];
$rutas = implode(",", $model['ruta']);

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


/*inicio configuración array de datos*/

$query1 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEG_RUTA_VTA
  @OPT = N'1',
  @YEAR = N'".$anio."',
  @RUTAS = N'".$rutas."'
";

UtilidadesVarias::log($query1);

$q1 = Yii::app()->db->createCommand($query1)->queryAll();

$query2 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEG_RUTA_VTA
  @OPT = N'2',
  @YEAR = N'".$anio."',
  @RUTAS = N'".$rutas."'
";

UtilidadesVarias::log($query2);

$q2 = Yii::app()->db->createCommand($query2)->queryAll();

$query3 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEG_RUTA_VTA
  @OPT = N'3',
  @YEAR = N'".$anio."',
  @RUTAS = N'".$rutas."'
";

UtilidadesVarias::log($query3);

$q3 = Yii::app()->db->createCommand($query3)->queryAll();

/*fin configuración array de datos*/

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Pedidos');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Cédula');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Docto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Año');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Mes');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Ruta');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:I1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

foreach ($q1 as $reg) {

  $cedula = $reg['Cedula'];
  $vendedor = $reg['Vendedor'];
  $docto = $reg['Docto'];
  $fecha = $reg['Fecha'];
  $anio = $reg['ANIO'];
  $mes = $reg['MES'];
  $nit = $reg['Nit'];
  $cliente = $reg['Cliente'];
  $ruta = $reg['Ruta'];
  
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $cedula);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $vendedor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $docto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $fecha);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $anio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $mes);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $nit);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $cliente);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $ruta);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 9; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Clientes creados');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A1', 'Cédula');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C1', 'Nit');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E1', 'Sucursal');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('H1', 'Año');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I1', 'Mes');

$objPHPExcel->getActiveSheet(1)->getStyle('A1:I1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(1)->getStyle('A1:I1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

foreach ($q2 as $reg) {

  $cedula = $reg['Cedula'];
  $vendedor = $reg['Vendedor'];
  $nit = $reg['Nit'];
  $cliente = $reg['Cliente'];
  $sucursal = $reg['Sucursal'];
  $ruta = $reg['Ruta'];
  $fecha_creacion = $reg['Creacion_Cliente'];
  $anio = $reg['ANIO'];
  $mes = $reg['MES'];
  
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.$Fila, $cedula);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.$Fila, $vendedor);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.$Fila, $nit);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.$Fila, $cliente);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.$Fila, $sucursal);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('F'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('G'.$Fila, $fecha_creacion);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('H'.$Fila, $anio);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('I'.$Fila, $mes);

  $objPHPExcel->getActiveSheet(1)->getStyle('A'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila = $Fila + 1;
}


/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 9; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->setTitle('Total clientes');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(2)->setCellValue('A1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(2)->setCellValue('B1', 'Nit');
$objPHPExcel->setActiveSheetIndex(2)->setCellValue('C1', 'Cliente');

$objPHPExcel->getActiveSheet(2)->getStyle('A1:C1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(2)->getStyle('A1:C1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

foreach ($q3 as $reg) {

  $ruta = $reg['Ruta'];
  $nit = $reg['Nit'];
  $cliente = $reg['Cliente'];
  
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B'.$Fila, $nit);
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('C'.$Fila, $cliente);

  $objPHPExcel->getActiveSheet(2)->getStyle('A'.$Fila.':C'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 3; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->setActiveSheetIndex(0);

$n = 'Seguimiento_rutas_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
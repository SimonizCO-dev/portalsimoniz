<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//se reciben los parametros para el reporte

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

$periodo = explode("-", $model['periodo']);
$anio = $periodo[0];
$mes = $periodo[1];
$coordinador = trim($model['coordinador']);
$marca = trim($model['marca']);

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
  EXEC P_PR_COM_SEGTO_COMERCIAL
  @OPT = N'1',
  @ANIO = N'".$anio."',
  @MES = N'".$mes."',
  @COORD = N'".$coordinador."',
  @MARCA = N'".$marca."'
";

UtilidadesVarias::log($query1);

$query2 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEGTO_COMERCIAL
  @OPT = N'2',
  @ANIO = N'".$anio."',
  @MES = N'".$mes."',
  @COORD = N'".$coordinador."',
  @MARCA = N'".$marca."'
";

UtilidadesVarias::log($query2);

$query3 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEGTO_COMERCIAL
  @OPT = N'3',
  @ANIO = N'".$anio."',
  @MES = N'".$mes."',
  @COORD = N'".$coordinador."',
  @MARCA = N'".$marca."'
";

UtilidadesVarias::log($query3);

$query4 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEGTO_COMERCIAL
  @OPT = N'4',
  @ANIO = N'".$anio."',
  @MES = N'".$mes."',
  @COORD = N'".$coordinador."',
  @MARCA = N'".$marca."'
";

UtilidadesVarias::log($query4);

$query5 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEGTO_COMERCIAL
  @OPT = N'5',
  @ANIO = N'".$anio."',
  @MES = N'".$mes."',
  @COORD = N'".$coordinador."',
  @MARCA = N'".$marca."'
";

UtilidadesVarias::log($query5);

$query6 = "
  SET NOCOUNT ON
  EXEC P_PR_COM_SEGTO_COMERCIAL
  @OPT = N'6',
  @ANIO = N'".$anio."',
  @MES = N'".$mes."',
  @COORD = N'".$coordinador."',
  @MARCA = N'".$marca."'
";

UtilidadesVarias::log($query6);

/*final configuración array de datos*/

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Pedidos');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Cédula');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Docto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Año');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Mes');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Nit');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Vlr. subtotal');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:J1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:J1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query1)->queryAll(); 

foreach ($q1 as $reg1) {

  $cedula = $reg1['Cedula'];
  $vendedor = $reg1['Vendedor'];
  $docto = $reg1['Docto'];
  $fecha = $reg1['Fecha'];
  $anio = $reg1['ANIO'];
  $mes = $reg1['MES'];
  $nit = $reg1['Nit'];
  $cliente = $reg1['Cliente'];
  $ruta = $reg1['Ruta'];
  $vlr_subtotal = $reg1['Vlr_Subtotal'];
  
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $cedula);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $vendedor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $docto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $fecha);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $anio);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $mes);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $nit);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $cliente);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $vlr_subtotal);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 10; 

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

$q2 = Yii::app()->db->createCommand($query2)->queryAll(); 

foreach ($q2 as $reg2) {

  $cedula = $reg2['Cedula'];
  $vendedor = $reg2['Vendedor'];
  $nit = $reg2['Nit'];
  $cliente = $reg2['Cliente'];
  $sucursal = $reg2['Sucursal'];
  $ruta = $reg2['Ruta'];
  $fecha_creacion = $reg2['Creacion_Cliente'];
  $anio = $reg2['ANIO'];
  $mes = $reg2['MES'];
  
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
$objPHPExcel->setActiveSheetIndex(2)->setCellValue('D1', 'Fecha de creación');
$objPHPExcel->setActiveSheetIndex(2)->setCellValue('E1', 'Fecha de asignación');

$objPHPExcel->getActiveSheet(2)->getStyle('A1:E1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(2)->getStyle('A1:E1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2;

$q3 = Yii::app()->db->createCommand($query3)->queryAll();  

foreach ($q3 as $reg3) {

  $ruta = $reg3['Ruta'];
  $nit = $reg3['Nit'];
  $cliente = $reg3['Cliente'];
  $fecha_creacion = $reg3['Fecha_Creacion'];
  $fecha_asignacion = $reg3['Fecha_Asignacion'];
  
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B'.$Fila, $nit);
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('C'.$Fila, $cliente);
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('D'.$Fila, $fecha_creacion);
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('E'.$Fila, $fecha_asignacion);

  $objPHPExcel->getActiveSheet(2)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 5; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(3);
$objPHPExcel->getActiveSheet()->setTitle('Estado rutas');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(3)->setCellValue('A1', 'Cédula');
$objPHPExcel->setActiveSheetIndex(3)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(3)->setCellValue('C1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(3)->setCellValue('D1', 'Año');
$objPHPExcel->setActiveSheetIndex(3)->setCellValue('E1', 'Mes');
$objPHPExcel->setActiveSheetIndex(3)->setCellValue('F1', 'estado de pedido');
$objPHPExcel->setActiveSheetIndex(3)->setCellValue('G1', 'Vlr. subtotal');

$objPHPExcel->getActiveSheet(3)->getStyle('A1:G1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(3)->getStyle('A1:G1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

$q4 = Yii::app()->db->createCommand($query4)->queryAll(); 

foreach ($q4 as $reg4) {

  $cedula = $reg4['Cedula'];
  $vendedor = $reg4['Vendedor'];
  $ruta = $reg4['Ruta'];
  $anio = $reg4['ANIO'];
  $mes = $reg4['MES'];
  $pedido_estado = $reg4['PEDIDO_ESTADO'];
  $vlr_subtotal = $reg4['Vlr_Subtotal'];
  
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('A'.$Fila, $cedula);
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('B'.$Fila, $vendedor);
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('C'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('D'.$Fila, $anio);
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('E'.$Fila, $mes);
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('F'.$Fila, $pedido_estado);
  $objPHPExcel->setActiveSheetIndex(3)->setCellValue('G'.$Fila, $vlr_subtotal);

  $objPHPExcel->getActiveSheet(3)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(3)->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(3)->getStyle('G'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 7; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(4);
$objPHPExcel->getActiveSheet()->setTitle('Ventas marcas');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(4)->setCellValue('A1', 'Cédula');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('C1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('D1', 'Año');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('E1', 'Mes');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('F1', 'estado de pedido');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('G1', 'Item');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('H1', 'Descripción');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('I1', 'Cantidad');
$objPHPExcel->setActiveSheetIndex(4)->setCellValue('J1', 'Vlr. subtotal');

$objPHPExcel->getActiveSheet(4)->getStyle('A1:J1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(4)->getStyle('A1:J1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

$q5 = Yii::app()->db->createCommand($query5)->queryAll(); 

foreach ($q5 as $reg5) {

  $cedula = $reg5['Cedula'];
  $vendedor = $reg5['Vendedor'];
  $ruta = $reg5['Ruta'];
  $anio = $reg5['ANIO'];
  $mes = $reg5['MES'];
  $pedido_estado = $reg5['PEDIDO_ESTADO'];
  $item = $reg5['ITEM'];
  $descripcion = $reg5['DESCRIPCION'];
  $cantidad = $reg5['CANTIDAD'];
  $vlr_subtotal = $reg5['Vlr_Subtotal'];
  
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('A'.$Fila, $cedula);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('B'.$Fila, $vendedor);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('C'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('D'.$Fila, $anio);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('E'.$Fila, $mes);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('F'.$Fila, $pedido_estado);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('G'.$Fila, $item);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('H'.$Fila, $descripcion);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('I'.$Fila, $cantidad);
  $objPHPExcel->setActiveSheetIndex(4)->setCellValue('J'.$Fila, $vlr_subtotal);

  $objPHPExcel->getActiveSheet(4)->getStyle('A'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(4)->getStyle('J'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(4)->getStyle('I'.$Fila.':J'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 10; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(5);
$objPHPExcel->getActiveSheet()->setTitle('Devoluciones');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(5)->setCellValue('A1', 'Cédula');
$objPHPExcel->setActiveSheetIndex(5)->setCellValue('B1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(5)->setCellValue('C1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(5)->setCellValue('D1', 'Año');
$objPHPExcel->setActiveSheetIndex(5)->setCellValue('E1', 'Mes');
$objPHPExcel->setActiveSheetIndex(5)->setCellValue('F1', 'estado de pedido');
$objPHPExcel->setActiveSheetIndex(5)->setCellValue('G1', 'Vlr. subtotal');

$objPHPExcel->getActiveSheet(5)->getStyle('A1:G1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(5)->getStyle('A1:G1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

$q6 = Yii::app()->db->createCommand($query6)->queryAll(); 

foreach ($q6 as $reg6) {

  $cedula = $reg6['Cedula'];
  $vendedor = $reg6['Vendedor'];
  $ruta = $reg6['Ruta'];
  $anio = $reg6['ANIO'];
  $mes = $reg6['MES'];
  $pedido_estado = $reg6['PEDIDO_ESTADO'];
  $vlr_subtotal = $reg6['Vlr_Subtotal'];
  
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('A'.$Fila, $cedula);
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('B'.$Fila, $vendedor);
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('C'.$Fila, $ruta);
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('D'.$Fila, $anio);
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('E'.$Fila, $mes);
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('F'.$Fila, $pedido_estado);
  $objPHPExcel->setActiveSheetIndex(5)->setCellValue('G'.$Fila, $vlr_subtotal);

  $objPHPExcel->getActiveSheet(5)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(5)->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(5)->getStyle('G'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 7; 

foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$objPHPExcel->setActiveSheetIndex(0);

$n = 'Seguimiento_rutas_coord_marca_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
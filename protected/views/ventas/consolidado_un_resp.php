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

//se reciben los parametros para el reporte
$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];
$un = $model['un'];

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

$array_un = $un;

$cond_un = "";

foreach ($array_un as $key => $value) {
  $cond_un .= "".trim($value).",";
}

$cond_u_n = substr($cond_un, 0, -1);

$query1 = "
  SET NOCOUNT ON
  EXEC P_PR_FIN_CT_CONSOLIDADO_UN_DET
  @Fecha_Ini = N'".$FechaM1."',
  @Fecha_Fin = N'".$FechaM2."',
  @Criterio = N'".$cond_u_n."'
";

$q1 = Yii::app()->db->createCommand($query1)->queryAll();

UtilidadesVarias::log($query1);

$query2 = "
  SET NOCOUNT ON
  EXEC P_PR_FIN_CT_CONSOLIDADO_UN
  @Fecha_Ini = N'".$FechaM1."',
  @Fecha_Fin = N'".$FechaM2."',
  @Criterio = N'".$cond_u_n."'
";

$q2 = Yii::app()->db->createCommand($query2)->queryAll();

UtilidadesVarias::log($query2);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Detalle');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Docto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Consecutivo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Mes');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'OC');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Id Sucursal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Sucursal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Estructura');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Id Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Vendedor');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Descripción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Origen');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Clasificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'Clase');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'Marca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'Submarca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'Segmento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'Familia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'Subfamilia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'Línea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'Sublínea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', 'Grupo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', 'UN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'Cat. Oracle');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', 'Cantidad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'Motivo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE1', 'Moneda');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', 'Tasa');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG1', 'Vlr. bruto alterno');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH1', 'Vlr. bruto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI1', 'Vlr. subtotal alterno');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ1', 'Vlr. subtotal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK1', 'Vlr. imp. alterno');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL1', 'Vlr. imp');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM1', 'Vlr. neto alterno');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN1', 'Vlr. neto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO1', 'Notas');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:AO1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AO1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

foreach ($q1 as $reg) {

  $CO = $reg['CO'];
  $DOCTO = $reg['DOCTO'];
  $CONSECUTIVO = $reg['CONSECUTIVO'];
  $FECHA = $reg['FECHA'];
  $MES = $reg['MES'];
  $CLIENTE = $reg['CLIENTE'];
  $OC = $reg['OC'];
  $ID_SUCURSAL = $reg['ID_SUCURSAL'];
  $SUCURSAL = $reg['SUCURSAL'];
  $ESTRUCTURA = $reg['ESTRUCTURA'];
  $ID_VENDEDOR = $reg['ID_VENDEDOR'];
  $VENDEDOR = $reg['VENDEDOR'];
  $ITEM = $reg['ITEM'];
  $DESCRIPCION = $reg['DESCRIPCION'];
  $ORIGEN = $reg['ORIGEN'];
  $TIPO = $reg['TIPO'];
  $CLASIFICACION = $reg['CLASIFICACION'];
  $CLASE = $reg['CLASE'];
  $MARCA = $reg['MARCA'];
  $SUBMARCA = $reg['SUBMARCA'];
  $SEGMENTO = $reg['SEGMENTO'];
  $FAMILIA = $reg['FAMILIA'];
  $SUBFAMILIA = $reg['SUBFAMILIA'];
  $LINEA = $reg['LINEA'];
  $SUBLINEA = $reg['SUBLINEA'];
  $GRUPO = $reg['GRUPO'];
  $UN = $reg['UN'];
  $ORACLE = $reg['ORACLE'];
  $CANTIDAD = $reg['CANTIDAD'];
  $MOTIVO = $reg['MOTIVO'];
  $MONEDA = $reg['MONEDA'];
  $TASA = $reg['TASA'];
  $Vlr_Bruto_Alt = $reg['Vlr_Bruto_Alt'];
  $Vlr_Bruto = $reg['Vlr_Bruto'];
  $Vlr_Subtotal_Alt = $reg['Vlr_Subtotal_Alt'];
  $Vlr_Subtotal = $reg['Vlr_Subtotal'];
  $Vlr_Imp_Alt = $reg['Vlr_Imp_Alt'];
  $Vlr_Imp = $reg['Vlr_Imp'];
  $Vlr_Neto_Alt = $reg['Vlr_Neto_Alt'];
  $Vlr_Neto = $reg['Vlr_Neto'];
  $Notas = $reg['Notas'];
  
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $CO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $DOCTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $CONSECUTIVO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $FECHA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $MES);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $CLIENTE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $OC);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $ID_SUCURSAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $SUCURSAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $ESTRUCTURA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ID_VENDEDOR);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $VENDEDOR);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $DESCRIPCION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $ORIGEN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $TIPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $CLASIFICACION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $CLASE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $MARCA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $SUBMARCA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $SEGMENTO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $FAMILIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $SUBFAMILIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $LINEA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $SUBLINEA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $GRUPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $UN);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $ORACLE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila, $CANTIDAD);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $MOTIVO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $MONEDA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $TASA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, $Vlr_Bruto_Alt);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $Vlr_Bruto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $Vlr_Subtotal_Alt);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $Vlr_Subtotal);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, $Vlr_Imp_Alt);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $Vlr_Imp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, $Vlr_Neto_Alt);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, $Vlr_Neto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, $Notas);

  $objPHPExcel->getActiveSheet(0)->getStyle('AF'.$Fila.':AN'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('AF'.$Fila.':AN'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 41; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

/*//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {

    $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
}*/

$objPHPExcel->createSheet();

$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Consolidado');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A1', 'CO');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('B1', 'Mes');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('C1', 'Cliente');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D1', 'Autos');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E1', 'Mecánica');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F1', 'Hogar');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G1', 'Administración');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('H1', 'Otras marcas');
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I1', 'Total general');

$objPHPExcel->getActiveSheet(1)->getStyle('A1:I1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(1)->getStyle('A1:I1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$Fila = 2; 

$total_autos = 0;
$total_mecanica = 0;
$total_hogar = 0;
$total_administracion = 0;
$total_otras_marcas = 0;
$t_total_general = 0;

foreach ($q2 as $reg) {

  $co = $reg['CO'];
  $mes = $reg['MES'];
  $cliente = $reg['CLIENTE'];
  $autos = $reg['AUTOS'];
  $mecanica = $reg['MECANICA'];
  $hogar = $reg['HOGAR'];
  $administracion = $reg['ADMINISTRACION'];
  $otras_marcas = $reg['OTRAS_MARCAS'];
  $total_general = $autos + $mecanica + $hogar + $administracion + $otras_marcas;

  $total_autos = $total_autos + $autos;
  $total_mecanica = $total_mecanica + $mecanica;
  $total_hogar = $total_hogar + $hogar;
  $total_administracion = $total_administracion + $administracion;
  $total_otras_marcas = $total_otras_marcas + $otras_marcas;
  $t_total_general = $t_total_general + $total_general;
  
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.$Fila, $co);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B'.$Fila, $mes);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C'.$Fila, $cliente);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.$Fila, $autos);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.$Fila, $mecanica);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('F'.$Fila, $hogar);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('G'.$Fila, $administracion);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('H'.$Fila, $otras_marcas);
  $objPHPExcel->setActiveSheetIndex(1)->setCellValue('I'.$Fila, $total_general);

  $objPHPExcel->getActiveSheet(1)->getStyle('D'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(1)->getStyle('D'.$Fila.':I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00'); 

  $Fila = $Fila + 1;
}

$objPHPExcel->setActiveSheetIndex(1)->setCellValue('A'.$Fila, 'Total');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A'.$Fila.':C'.$Fila);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('D'.$Fila, $total_autos);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('E'.$Fila, $total_mecanica);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('F'.$Fila, $total_hogar);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('G'.$Fila, $total_administracion);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('H'.$Fila, $total_otras_marcas);
$objPHPExcel->setActiveSheetIndex(1)->setCellValue('I'.$Fila, $t_total_general);


$objPHPExcel->getActiveSheet(1)->getStyle('D'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
$objPHPExcel->getActiveSheet(1)->getStyle('D'.$Fila.':I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet(1)->getStyle('A'.$Fila.':I'.$Fila)->getFont()->setBold(true);

/*fin contenido tabla*/

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 9; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}


/*//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {

    $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
}*/

$objPHPExcel->setActiveSheetIndex(0);

$n = 'Consolidado_Detalle_UN_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
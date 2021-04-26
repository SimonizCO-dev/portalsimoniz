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

// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');


//array con titulos de meses

$mes_act=date('F');

$clave = array_search($mes_act, $ming);

$cont = $clave - 1;

$array_titulo_meses = array();

for ($i=1; $i <= 12; $i++) { 

  $m = str_replace($ming, $mesp, $ming[$clave]);
  $mes = strtoupper($m);
  $mes_abrev = substr($mes, 0, 3);

  $array_titulo_meses[] = $mes_abrev;
  if($clave == 11){

    $clave = 0;
  
  }else{
  
    $clave++;
  
  }
}

/*inicio configuración array de datos*/

$query ="EXEC P_PR_COM_CONS_ANALISIS_ITEM";

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

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'INVENTARIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'PRECIO LISTA 001');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'PRECIO LISTA 560');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'LOTE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'STOCK');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'CÓDIGO DE BARRAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'UNIDAD DE NEGOCIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'MARCA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'ORIGEN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'CLASIFICACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'CLASE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'SEGMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'LÍNEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'SUB-LÍNEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'UNIDAD DE NEGOCIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'ORACLE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', $array_titulo_meses[0]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', $array_titulo_meses[1]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', $array_titulo_meses[2]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', $array_titulo_meses[3]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', $array_titulo_meses[4]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', $array_titulo_meses[5]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', $array_titulo_meses[6]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', $array_titulo_meses[7]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE1', $array_titulo_meses[8]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', $array_titulo_meses[9]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG1', $array_titulo_meses[10]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH1', $array_titulo_meses[11]);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI1', 'PROM. VENTAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ1', 'BASE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK1', 'EXIST. A LA FECHA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL1', 'O.C PEND.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM1', 'ROTACIÓN');


$objPHPExcel->getActiveSheet(0)->getStyle('A1:AM1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AM1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $I_ID_ITEM = $reg1 ['I_ID_ITEM']; 
    $I_REFERENCIA = $reg1 ['I_REFERENCIA']; 
    $I_DESCRIPCION = $reg1 ['I_DESCRIPCION'];
    $I_ESTADO = $reg1 ['I_ESTADO'];
    $I_INVENTARIO = $reg1 ['I_INVENTARIO'];
    $Pre_L001 = $reg1 ['Pre_L001'];    
    $Pre_L560 = $reg1 ['Pre_L560']; 
    $I_TIPO = $reg1 ['I_TIPO'];
    $I_LOTE = $reg1 ['I_LOTE'];
    $I_STOCK = $reg1 ['I_STOCK'];
    $I_CODIGO_BARRAS = $reg1 ['I_CODIGO_BARRAS'];
    $I_UNIDAD_NEGOCIO = $reg1 ['I_UNIDAD_NEGOCIO'];    
    $I_CRI_MARCA = $reg1 ['I_CRI_MARCA']; 
    $I_CRI_ORIGEN = $reg1 ['I_CRI_ORIGEN'];
    $I_CRI_TIPO = $reg1 ['I_CRI_TIPO'];
    $I_CRI_CLASIFICACION = $reg1 ['I_CRI_CLASIFICACION'];
    $I_CRI_CLASE = $reg1 ['I_CRI_CLASE'];
    $I_CRI_SEGMENTO = $reg1 ['I_CRI_SEGMENTO'];    
    $I_CRI_LINEA = $reg1 ['I_CRI_LINEA']; 
    $I_CRI_SUBLINEA = $reg1 ['I_CRI_SUBLINEA'];
    $I_CRI_UNIDAD_NEGOCIO = $reg1 ['I_CRI_UNIDAD_NEGOCIO'];
    $I_CRI_ORACLE = $reg1 ['I_CRI_ORACLE'];
    $CI_MES1 = $reg1 ['CI_MES1'];
    $CI_MES2 = $reg1 ['CI_MES2'];    
    $CI_MES3 = $reg1 ['CI_MES3']; 
    $CI_MES4 = $reg1 ['CI_MES4'];
    $CI_MES5 = $reg1 ['CI_MES5'];
    $CI_MES6 = $reg1 ['CI_MES6'];
    $CI_MES7 = $reg1 ['CI_MES7'];
    $CI_MES8 = $reg1 ['CI_MES8'];    
    $CI_MES9 = $reg1 ['CI_MES9']; 
    $CI_MES10 = $reg1 ['CI_MES10'];
    $CI_MES11 = $reg1 ['CI_MES11'];
    $CI_MES12 = $reg1 ['CI_MES12'];
    $PROMEDIO_VTAS = $reg1 ['PROMEDIO_VTAS'];
    $BASE = $reg1 ['BASE'];
    $EXISTENCIA = $reg1 ['EXISTENCIA'];
    $OC_PEND = $reg1 ['OC_PEND'];
    $ROTACION = $reg1 ['ROTACION'];
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $I_ID_ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $I_REFERENCIA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $I_DESCRIPCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $I_ESTADO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $I_INVENTARIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Pre_L001);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Pre_L560);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $I_TIPO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $I_LOTE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $I_STOCK);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('K'.$Fila, $I_CODIGO_BARRAS, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $I_UNIDAD_NEGOCIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $I_CRI_MARCA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $I_CRI_ORIGEN);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $I_CRI_TIPO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $I_CRI_CLASIFICACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $I_CRI_CLASE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $I_CRI_SEGMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $I_CRI_LINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $I_CRI_SUBLINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $I_CRI_UNIDAD_NEGOCIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $I_CRI_ORACLE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $CI_MES12);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $CI_MES11);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $CI_MES10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $CI_MES9);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $CI_MES8);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $CI_MES7);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila, $CI_MES6);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $CI_MES5);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $CI_MES4);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $CI_MES3);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, $CI_MES2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $CI_MES1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $PROMEDIO_VTAS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $BASE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, $EXISTENCIA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $OC_PEND);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, $ROTACION);

    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':G'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':G'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':J'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':J'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('W'.$Fila.':AJ'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('W'.$Fila.':AJ'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('AK'.$Fila.':AL'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet(0)->getStyle('AK'.$Fila.':AL'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('W'.$Fila.':AJ'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('W'.$Fila.':AJ'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 40; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Analisis_x_producto_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

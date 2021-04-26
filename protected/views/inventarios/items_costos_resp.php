<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

//ESTADOS

if(isset($model['estado'])) {
  $v_estado = $model['estado'];

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }

  $texto_estados = substr ($estados, 0, -1);
  $condicion_estados = substr ($estados, 0, -1);

}else{
  $v_estado = "";
  $texto_estados = "TODOS";
  $condicion_estados = "";
}

//CLASES

if(isset($model['clase'])) {
  $v_clases = $model['clase'];

  $array_clases =  $model['clase'];
  $clases = "";
  foreach ($array_clases as $a_clases => $valor) {
    $clases .= "".$valor.",";
  }

  $texto_clases = substr ($clases, 0, -1);
  $condicion_clases = substr ($clases, 0, -1);

}else{
  $v_clases = "";
  $texto_clases = "TODOS";
  $condicion_clases = "";
}

//ORACLE 

if(isset($model['des_ora'])) {
  $v_oracle = $model['des_ora'];

  $array_oracle =  $model['des_ora'];
  $oracle = "";
  foreach ($array_oracle as $a_oracle=> $valor) {
    $oracle .= "'".$valor."',";
  }
  $oracle = substr ($oracle, 0, -1);

  //oracle
  $q_oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 AND Id_Criterio IN (".$oracle.") ORDER BY Criterio_Descripcion")->queryAll();

  $condicion_oracle = '';
  $texto_oracle = '';

  foreach ($q_oracle as $ora) {
    $condicion_oracle .= $ora['Criterio_Descripcion'].',';
    $texto_oracle .= $ora['Criterio_Descripcion'].',';
  }

  $texto_oracle = substr ($texto_oracle, 0, -1);
  $condicion_oracle = substr ($condicion_oracle, 0, -1);

}else{
  $v_oracle = "";
  $texto_oracle = "TODOS";
  $condicion_oracle = "";

}

if($v_estado == "" && $v_clases == "" && $v_oracle == ""){
  $opc = 0;
}else if($v_estado != "" && $v_clases == "" && $v_oracle == ""){
  $opc = 1;
}else if($v_estado == "" && $v_clases != "" && $v_oracle == ""){
  $opc = 2;
}else if($v_estado == "" && $v_clases == "" && $v_oracle != ""){
  $opc = 3;
}else if($v_estado != "" && $v_clases != "" && $v_oracle == ""){
  $opc = 4;
}else if($v_estado != "" && $v_clases == "" && $v_oracle != ""){
  $opc = 5;
}else if($v_estado == "" && $v_clases != "" && $v_oracle != ""){
  $opc = 6;
}else if($v_estado != "" && $v_clases != "" && $v_oracle != ""){
  $opc = 7;
}

set_time_limit(0);

/*inicio configuración array de datos*/

$query ="
  SET NOCOUNT ON
  EXEC P_PR_COM_CONS_PRECIOS
  @OPT = N'".$opc."',
  @ESTADO = N'".$condicion_estados."',
  @CLASE = N'".$condicion_clases."',
  @ORACLE = N'".$condicion_oracle."'
";

UtilidadesVarias::log($query);

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ITEM');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'UNIDAD');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'COSTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'ORIGEN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'CLASIFICACIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'CLASE');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'MARCA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'SEGMENTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'LÍNEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'SUB-LÍNEA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'UNIDAD DE NEGOCIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'ORACLE');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $I_ID_ITEM = $reg1 ['I_ID_ITEM']; 
    $I_REFERENCIA = $reg1 ['I_REFERENCIA']; 
    $I_DESCRIPCION = $reg1 ['I_DESCRIPCION'];
    $I_UNIDAD = $reg1 ['I_UNIDAD'];
    $I_ESTADO = $reg1 ['I_ESTADO'];
    $I_COSTO_INS = $reg1 ['I_COSTO_INS'];
    $I_CRI_ORIGEN = $reg1 ['I_CRI_ORIGEN'];
    $I_CRI_TIPO = $reg1 ['I_CRI_TIPO'];
    $I_CRI_CLASIFICACION = $reg1 ['I_CRI_CLASIFICACION'];
    $I_CRI_CLASE = $reg1 ['I_CRI_CLASE'];
    $I_CRI_MARCA = $reg1 ['I_CRI_MARCA']; 
    $I_CRI_SEGMENTO = $reg1 ['I_CRI_SEGMENTO']; 
    $I_CRI_LINEA = $reg1 ['I_CRI_LINEA'];
    $I_CRI_SUBLINEA = $reg1 ['I_CRI_SUBLINEA'];
    $I_CRI_UNIDAD_NEGOCIO = $reg1 ['I_CRI_UNIDAD_NEGOCIO'];
    $I_CRI_ORACLE = $reg1 ['I_CRI_ORACLE'];
    

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $I_ID_ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $I_REFERENCIA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $I_DESCRIPCION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $I_UNIDAD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $I_ESTADO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $I_COSTO_INS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $I_CRI_ORIGEN);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $I_CRI_TIPO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $I_CRI_CLASIFICACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $I_CRI_CLASE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $I_CRI_MARCA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $I_CRI_SEGMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $I_CRI_LINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $I_CRI_SUBLINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $I_CRI_UNIDAD_NEGOCIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $I_CRI_ORACLE);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 16; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Items_costos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

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

$marca_inicial = $model['marca_inicial'];
$marca_final = $model['marca_final'];

/*inicio configuración array de datos*/

$query ="SET NOCOUNT ON EXEC P_PR_COM_CONS_ANALISIS_EXT_ITEM
@MARCA1 = N'".$marca_inicial."',
@MARCA2 = N'".$marca_final."'
";

UtilidadesVarias::log($query);

$q1 = Yii::app()->db->createCommand($query)->queryAll();

$array_info = array();

foreach ($q1 as $reg) {

  //INFO  DE ITEM
  $Item = $reg ['Item'];
  $I_CRI_UNIDAD_NEGOCIO = $reg ['I_CRI_UNIDAD_NEGOCIO'];
  $Referencia = $reg ['Referencia'];
  $Estado = $reg ['Estado'];
  $FECHA = $reg ['FECHA'];
  $Descripcion = $reg ['Descripcion'];
  $Precio_Actual = $reg ['Precio_Actual'];
  $Cant_Disp = $reg ['Cant_Disp'];
  $Forecast = $reg ['Forecast'];
  $Origen = $reg ['Origen'];
  $Tipo = $reg ['Tipo'];
  $Clase = $reg ['Clase'];
  $Marca = $reg ['Marca'];
  $Segmento = $reg ['Segmento'];
  $Linea = $reg ['Linea'];
  $SubLinea = $reg ['SubLinea'];
  $Categoria = $reg ['Categoria'];
  $Tipo_Item = $reg ['Tipo_Item'];

  //INFO  DE UND
  $Id_UND = $reg ['Id_UND'];
  $Desc_UMD = $reg ['Desc_UMD'];
  $EAN = $reg ['EAN_13'];
  $Cant_UND = $reg ['Cant_UND'];
  
  $array_info[$Item]['info'] = array('I_CRI_UNIDAD_NEGOCIO' => $I_CRI_UNIDAD_NEGOCIO, 'Referencia' => $Referencia, 'Estado' => $Estado, 'FECHA' => $FECHA, 'Descripcion' => $Descripcion, 'Precio_Actual' => $Precio_Actual, 'Cant_Disp' => $Cant_Disp, 'Forecast' => $Forecast, 'Origen' => $Origen, 'Tipo' => $Tipo,'Clase' => $Clase, 'Marca' => $Marca, 'Segmento' => $Segmento,'Linea' => $Linea, 'SubLinea' => $SubLinea, 'Categoria' => $Categoria,'Tipo_Item' => $Tipo_Item);
  
  if(!in_array($EAN, $array_info[$Item])){
    $array_info[$Item]['unds'][] = array('Id_UND' => $Id_UND, 'Desc_UMD' => $Desc_UMD, 'EAN' => $EAN, 'Cant_UND' => $Cant_UND);
  }

}

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'UN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Referencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Estado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fecha');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Descripción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Precio actual');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Cant. disp.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Forecast');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Origen');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Clase');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Marca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Segmento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Línea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Sublínea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Categoria');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'Tipo item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'Und. 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'Desc. Und. 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'EAN 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'Cant. x Und. 1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'Und. 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'Desc. Und. 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'EAN 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', 'Cant. x Und. 2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', 'Und. 3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'Desc. Und. 3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', 'EAN 3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'Cant. x Und. 3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE1', 'Und. 4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', 'Desc. Und. 4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG1', 'EAN 4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH1', 'Cant. x Und. 4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI1', 'Und. 5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ1', 'Desc. Und. 5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK1', 'EAN 5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL1', 'Cant. x Und. 5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM1', 'Und. 6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN1', 'Desc. Und. 6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO1', 'EAN 6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP1', 'Cant. x Und. 6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ1', 'Und. 7');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR1', 'Desc. Und. 7');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS1', 'EAN 7');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT1', 'Cant. x Und. 7');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU1', 'Und. 8');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV1', 'Desc. Und. 8');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW1', 'EAN 8');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX1', 'Cant. x Und. 8');


$objPHPExcel->getActiveSheet(0)->getStyle('A1:AX1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AX1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$Fila = 2;

foreach ($array_info as $item => $data) {
    
    //$info = $data['info'];
    $I_CRI_UNIDAD_NEGOCIO = $data['info']['I_CRI_UNIDAD_NEGOCIO']; 
    $Referencia = $data['info']['Referencia']; 
    $Estado = $data['info']['Estado'];
    $FECHA = $data['info']['FECHA'];
    $Descripcion = $data['info']['Descripcion']; 
    $Precio_Actual = $data['info']['Precio_Actual']; 
    $Cant_Disp = $data['info']['Cant_Disp'];
    $Forecast = $data['info']['Forecast']; 
    $Origen = $data['info']['Origen'];
    $Tipo = $data['info']['Tipo'];
    $Clase = $data['info']['Clase']; 
    $Marca = $data['info']['Marca']; 
    $Segmento = $data['info']['Segmento'];
    $Linea = $data['info']['Linea'];
    $SubLinea = $data['info']['SubLinea'];
    $Categoria = $data['info']['Categoria'];
    $Tipo_Item = $data['info']['Tipo_Item'];

    $a_unds = $data['unds'];

    $unds = array();
    $eans = array();
     
    foreach ($a_unds as $reg) {

        if(!in_array($reg['EAN'], $eans)){
            $unds[] = array('Id_UND' => $reg['Id_UND'], 'Desc_UMD' => $reg['Desc_UMD'], 'EAN' => $reg['EAN'], 'Cant_UND' => $reg['Cant_UND']);
            array_push($eans, $reg['EAN']);      
        }
    }

    $num_unds = count($unds);
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $item);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $I_CRI_UNIDAD_NEGOCIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $Referencia);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $Estado);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $FECHA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Descripcion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Precio_Actual);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Cant_Disp);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $Forecast);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Origen);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Tipo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Clase);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Marca);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Segmento);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $Linea);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $SubLinea);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $Categoria);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $Tipo_Item);
    

    if($num_unds == 1){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 2){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 3){
        
        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vi = $unds[2]['Id_UND']; 
        $vj = $unds[2]['Desc_UMD'];
        $vk = $unds[2]['EAN'];
        $vl = $unds[2]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $vi);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $vj);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AC'.$Fila, $vk, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $vl);

        $objPHPExcel->getActiveSheet(0)->getStyle('AC'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 4){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vi = $unds[2]['Id_UND']; 
        $vj = $unds[2]['Desc_UMD'];
        $vk = $unds[2]['EAN'];
        $vl = $unds[2]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $vi);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $vj);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AC'.$Fila, $vk, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $vl);

        $objPHPExcel->getActiveSheet(0)->getStyle('AC'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vm = $unds[3]['Id_UND']; 
        $vn = $unds[3]['Desc_UMD'];
        $vo = $unds[3]['EAN'];
        $vp = $unds[3]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $vm);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $vn);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AG'.$Fila, $vo, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $vp);

        $objPHPExcel->getActiveSheet(0)->getStyle('AG'.$Fila.':AH'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 5){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vi = $unds[2]['Id_UND']; 
        $vj = $unds[2]['Desc_UMD'];
        $vk = $unds[2]['EAN'];
        $vl = $unds[2]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $vi);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $vj);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AC'.$Fila, $vk, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $vl);

        $objPHPExcel->getActiveSheet(0)->getStyle('AC'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vm = $unds[3]['Id_UND']; 
        $vn = $unds[3]['Desc_UMD'];
        $vo = $unds[3]['EAN'];
        $vp = $unds[3]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $vm);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $vn);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AG'.$Fila, $vo, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $vp);

        $objPHPExcel->getActiveSheet(0)->getStyle('AG'.$Fila.':AH'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vq = $unds[4]['Id_UND']; 
        $vr = $unds[4]['Desc_UMD'];
        $vs = $unds[4]['EAN'];
        $vt = $unds[4]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $vq);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $vr);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AK'.$Fila, $vs, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $vt);

        $objPHPExcel->getActiveSheet(0)->getStyle('AK'.$Fila.':AL'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 6){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet()->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vi = $unds[2]['Id_UND']; 
        $vj = $unds[2]['Desc_UMD'];
        $vk = $unds[2]['EAN'];
        $vl = $unds[2]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $vi);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $vj);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AC'.$Fila, $vk, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $vl);

        $objPHPExcel->getActiveSheet(0)->getStyle('AC'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vm = $unds[3]['Id_UND']; 
        $vn = $unds[3]['Desc_UMD'];
        $vo = $unds[3]['EAN'];
        $vp = $unds[3]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $vm);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $vn);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AG'.$Fila, $vo, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $vp);

        $objPHPExcel->getActiveSheet(0)->getStyle('AG'.$Fila.':AH'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vq = $unds[4]['Id_UND']; 
        $vr = $unds[4]['Desc_UMD'];
        $vs = $unds[4]['EAN'];
        $vt = $unds[4]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $vq);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $vr);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AK'.$Fila, $vs, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $vt);

        $objPHPExcel->getActiveSheet(0)->getStyle('AK'.$Fila.':AL'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vu = $unds[5]['Id_UND']; 
        $vv = $unds[5]['Desc_UMD'];
        $vw = $unds[5]['EAN'];
        $vx = $unds[5]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, $vu);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, $vv);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AO'.$Fila, $vw, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, $vx);

        $objPHPExcel->getActiveSheet(0)->getStyle('AO'.$Fila.':AP'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, '');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 7){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vi = $unds[2]['Id_UND']; 
        $vj = $unds[2]['Desc_UMD'];
        $vk = $unds[2]['EAN'];
        $vl = $unds[2]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $vi);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $vj);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AC'.$Fila, $vk, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $vl);

        $objPHPExcel->getActiveSheet(0)->getStyle('AC'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vm = $unds[3]['Id_UND']; 
        $vn = $unds[3]['Desc_UMD'];
        $vo = $unds[3]['EAN'];
        $vp = $unds[3]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $vm);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $vn);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AG'.$Fila, $vo, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $vp);

        $objPHPExcel->getActiveSheet(0)->getStyle('AG'.$Fila.':AH'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vq = $unds[4]['Id_UND']; 
        $vr = $unds[4]['Desc_UMD'];
        $vs = $unds[4]['EAN'];
        $vt = $unds[4]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $vq);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $vr);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AK'.$Fila, $vs, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $vt);

        $objPHPExcel->getActiveSheet(0)->getStyle('AK'.$Fila.':AL'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vu = $unds[5]['Id_UND']; 
        $vv = $unds[5]['Desc_UMD'];
        $vw = $unds[5]['EAN'];
        $vx = $unds[5]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, $vu);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, $vv);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AO'.$Fila, $vw, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, $vx);

        $objPHPExcel->getActiveSheet(0)->getStyle('AO'.$Fila.':AP'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vy = $unds[6]['Id_UND']; 
        $vz = $unds[6]['Desc_UMD'];
        $vaa = $unds[6]['EAN'];
        $vab = $unds[6]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, $vy);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, $vz);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AS'.$Fila, $vaa, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, $vab);

        $objPHPExcel->getActiveSheet(0)->getStyle('AS'.$Fila.':AT'.$Fila)->getNumberFormat()->setFormatCode('0');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, '');

    }

    if($num_unds == 8){

        $va = $unds[0]['Id_UND']; 
        $vb = $unds[0]['Desc_UMD'];
        $vc = $unds[0]['EAN'];
        $vd = $unds[0]['Cant_UND'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vb);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vc, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $vd);

        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('0');

        $ve = $unds[1]['Id_UND']; 
        $vf = $unds[1]['Desc_UMD'];
        $vg = $unds[1]['EAN'];
        $vh = $unds[1]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $ve);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $vf);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('Y'.$Fila, $vg, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $vh);

        $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':Z'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vi = $unds[2]['Id_UND']; 
        $vj = $unds[2]['Desc_UMD'];
        $vk = $unds[2]['EAN'];
        $vl = $unds[2]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $vi);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $vj);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AC'.$Fila, $vk, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $vl);

        $objPHPExcel->getActiveSheet(0)->getStyle('AC'.$Fila.':AD'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vm = $unds[3]['Id_UND']; 
        $vn = $unds[3]['Desc_UMD'];
        $vo = $unds[3]['EAN'];
        $vp = $unds[3]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $vm);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $vn);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AG'.$Fila, $vo, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $vp);

        $objPHPExcel->getActiveSheet(0)->getStyle('AG'.$Fila.':AH'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vq = $unds[4]['Id_UND']; 
        $vr = $unds[4]['Desc_UMD'];
        $vs = $unds[4]['EAN'];
        $vt = $unds[4]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $vq);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $vr);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AK'.$Fila, $vs, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $vt);

        $objPHPExcel->getActiveSheet(0)->getStyle('AK'.$Fila.':AL'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vu = $unds[5]['Id_UND']; 
        $vv = $unds[5]['Desc_UMD'];
        $vw = $unds[5]['EAN'];
        $vx = $unds[5]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, $vu);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, $vv);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AO'.$Fila, $vw, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, $vx);

        $objPHPExcel->getActiveSheet(0)->getStyle('AO'.$Fila.':AP'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vy = $unds[6]['Id_UND']; 
        $vz = $unds[6]['Desc_UMD'];
        $vaa = $unds[6]['EAN'];
        $vab = $unds[6]['Cant_UND'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, $vy);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, $vz);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AS'.$Fila, $vaa, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, $vab);

        $objPHPExcel->getActiveSheet(0)->getStyle('AS'.$Fila.':AT'.$Fila)->getNumberFormat()->setFormatCode('0');

        $vac = $unds[7]['Id_UND']; 
        $vad = $unds[7]['Desc_UMD'];
        $vae = $unds[7]['EAN'];
        $vaf = $unds[7]['Cant_UND'];


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, $vac);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, $vad);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AW'.$Fila, $vae, $type_string);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, $vaf);

        $objPHPExcel->getActiveSheet(0)->getStyle('AW'.$Fila.':AX'.$Fila)->getNumberFormat()->setFormatCode('0');

    }

    $Fila = $Fila + 1; 

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 50; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Logistica_exterior_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;


?>
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

$un_inicial = $model['un_inicial'];
$un_final = $model['un_final'];

/*inicio configuración array de datos*/

//EXCEL

$query ="
    SET NOCOUNT ON 
    EXEC P_PR_COM_CONS_ITEMS_COMER_UN 
    @UN1 = N'".$un_inicial."',
    @UN2 = N'".$un_final."'
";

UtilidadesVarias::log($query);

$q1 = Yii::app()->db->createCommand($query)->queryAll();

$array_info = array();

foreach ($q1 as $reg1) {

    $UN                = $reg1 ['UN']; 
    $Item              = $reg1 ['Item']; 
    $Referencia        = $reg1 ['Referencia'];
    $Descripcion       = $reg1 ['Descripcion'];
    $Estado_Siesa      = $reg1 ['Estado_Siesa'];
    $Estado_Comercial  = $reg1 ['Estado_Comercial'];

    if($reg1 ['L001'] == NULL){
        $L001 = 0;
    }else{
        $L001 = $reg1 ['L001'];
    }

    if($reg1 ['L860'] == NULL){
        $L860 = 0;
    }else{
        $L860 = $reg1 ['L860'];
    }

    if($reg1 ['L111'] == NULL){
        $L111 = 0;
    }else{
        $L111 = $reg1 ['L111'];
    }

    $UND                = $reg1 ['UND'];
    $Factor             = $reg1 ['Factor'];
    $EAN                = $reg1 ['EAN'];
    $IVA                = $reg1 ['IVA'];
    $Producto_exento    = $reg1 ['Producto_exento'];
    $Cant_Disponible    = $reg1 ['Cant_Disponible'];
    $TOTAL              = $reg1 ['TOTAL'];
    $AUTOMOTRIZ         = $reg1 ['AUTOMOTRIZ'];
    $ELECTRICO          = $reg1 ['ELECTRICO'];
    $FERRETERO          = $reg1 ['FERRETERO'];
    $MIXTO              = $reg1 ['MIXTO'];
    $MIXTO_B_GA         = $reg1 ['MIXTO_B_GA'];

    if($reg1 ['H018_LARGO_PRODUCTO_CMS'] == NULL){
        $H018_LARGO_PRODUCTO_CMS = 0;
    }else{
        $H018_LARGO_PRODUCTO_CMS = $reg1 ['H018_LARGO_PRODUCTO_CMS'];
    }

    if($reg1 ['H018_ANCHO_PRODUCTO_CMS'] == NULL){
        $H018_ANCHO_PRODUCTO_CMS = 0;
    }else{
        $H018_ANCHO_PRODUCTO_CMS = $reg1 ['H018_ANCHO_PRODUCTO_CMS'];
    }

    if($reg1 ['H018_ALTO_PRODUCTO_CMS'] == NULL){
        $H018_ALTO_PRODUCTO_CMS = 0;
    }else{
        $H018_ALTO_PRODUCTO_CMS = $reg1 ['H018_ALTO_PRODUCTO_CMS'];
    }

    if($reg1 ['H018_PESO_PRODUCTO_KG'] == NULL){
        $H018_PESO_PRODUCTO_KG = 0;
    }else{
        $H018_PESO_PRODUCTO_KG = $reg1 ['H018_PESO_PRODUCTO_KG'];
    }

    if($reg1 ['H018_LARGO_UND_EMPAQUE_PPAL_CMS'] == NULL){
        $H018_LARGO_UND_EMPAQUE_PPAL_CMS = 0;
    }else{
        $H018_LARGO_UND_EMPAQUE_PPAL_CMS = $reg1 ['H018_LARGO_UND_EMPAQUE_PPAL_CMS'];
    }

    if($reg1 ['H018_ANCHO_UND_EMPAQUE_PPAL_CMS'] == NULL){
        $H018_ANCHO_UND_EMPAQUE_PPAL_CMS = 0;
    }else{
        $H018_ANCHO_UND_EMPAQUE_PPAL_CMS = $reg1 ['H018_ANCHO_UND_EMPAQUE_PPAL_CMS'];
    }

    if($reg1 ['H018_ALTO_UND_EMPAQUE_PPAL_CMS'] == NULL){
        $H018_ALTO_UND_EMPAQUE_PPAL_CMS = 0;
    }else{
        $H018_ALTO_UND_EMPAQUE_PPAL_CMS = $reg1 ['H018_ALTO_UND_EMPAQUE_PPAL_CMS'];
    }

    if($reg1 ['H018_PESO_UND_EMPAQUE_PPAL_KG'] == NULL){
        $H018_PESO_UND_EMPAQUE_PPAL_KG = 0;
    }else{
        $H018_PESO_UND_EMPAQUE_PPAL_KG = $reg1 ['H018_PESO_UND_EMPAQUE_PPAL_KG'];
    }

    if($reg1 ['H018_LARGO_UND_EMPAQUE_CADENAS_CMS'] == NULL){
        $H018_LARGO_UND_EMPAQUE_CADENAS_CMS = 0;
    }else{
        $H018_LARGO_UND_EMPAQUE_CADENAS_CMS = $reg1 ['H018_LARGO_UND_EMPAQUE_CADENAS_CMS'];
    }

    if($reg1 ['H018_ANCHO_UND_EMPAQUE_CADENAS_CMS'] == NULL){
        $H018_ANCHO_UND_EMPAQUE_CADENAS_CMS = 0;
    }else{
        $H018_ANCHO_UND_EMPAQUE_CADENAS_CMS = $reg1 ['H018_ANCHO_UND_EMPAQUE_CADENAS_CMS'];
    }

    if($reg1 ['H018_ALTO_UND_EMPAQUE_CADENAS_CMS'] == NULL){
        $H018_ALTO_UND_EMPAQUE_CADENAS_CMS = 0;
    }else{
        $H018_ALTO_UND_EMPAQUE_CADENAS_CMS = $reg1 ['H018_ALTO_UND_EMPAQUE_CADENAS_CMS'];
    }

    if($reg1 ['H018_PESO_UND_EMPAQUE_CADENAS_KG'] == NULL){
        $H018_PESO_UND_EMPAQUE_CADENAS_KG = 0;
    }else{
        $H018_PESO_UND_EMPAQUE_CADENAS_KG = $reg1 ['H018_PESO_UND_EMPAQUE_CADENAS_KG'];
    }

    if($reg1 ['H018_UND_VENTA_MINIMA'] == NULL){
        $H018_UND_VENTA_MINIMA = '';
    }else{
        $H018_UND_VENTA_MINIMA = $reg1 ['H018_UND_VENTA_MINIMA'];
    }

    $ORIGEN = $reg1 ['ORIGEN'];
    $TIPO = $reg1 ['TIPO'];
    $CLASIFICACION = $reg1 ['CLASIFICACION'];
    $CLASE = $reg1 ['CLASE'];
    $MARCA = $reg1 ['MARCA'];
    $SEGMENTO = $reg1 ['SEGMENTO'];
    $LINEA = $reg1 ['LINEA'];
    $SUB_LINEA = $reg1 ['SUB_LINEA'];
    $I_UNIDAD_NEGOCIO = $reg1 ['I_UNIDAD_NEGOCIO'];
    $CATEGORIA_ORACLE = $reg1 ['CATEGORIA_ORACLE'];
    $SUB_MARCA = $reg1 ['SUB_MARCA'];

    $array_info[$Item]['info'] = array('UN' => $UN, 'Item' => $Item, 'Referencia' => $Referencia, 'Descripcion' => $Descripcion, 'Estado_Siesa' => $Estado_Siesa, 'Estado_Comercial' => $Estado_Comercial, 'IVA' => $IVA, 'Producto_exento' => $Producto_exento, 'Cant_Disponible' => $Cant_Disponible, 'TOTAL' => $TOTAL,'AUTOMOTRIZ' => $AUTOMOTRIZ, 'ELECTRICO' => $ELECTRICO, 'FERRETERO' => $FERRETERO, 'MIXTO' => $MIXTO, 'MIXTO_B_GA' => $MIXTO_B_GA, 'H018_LARGO_PRODUCTO_CMS' => $H018_LARGO_PRODUCTO_CMS, 'H018_ANCHO_PRODUCTO_CMS' => $H018_ANCHO_PRODUCTO_CMS, 'H018_ALTO_PRODUCTO_CMS' => $H018_ALTO_PRODUCTO_CMS, 'H018_PESO_PRODUCTO_KG' => $H018_PESO_PRODUCTO_KG, 'H018_LARGO_UND_EMPAQUE_PPAL_CMS' => $H018_LARGO_UND_EMPAQUE_PPAL_CMS, 'H018_ANCHO_UND_EMPAQUE_PPAL_CMS' => $H018_ANCHO_UND_EMPAQUE_PPAL_CMS, 'H018_ALTO_UND_EMPAQUE_PPAL_CMS' => $H018_ALTO_UND_EMPAQUE_PPAL_CMS, 'H018_PESO_UND_EMPAQUE_PPAL_KG' => $H018_PESO_UND_EMPAQUE_PPAL_KG, 'H018_LARGO_UND_EMPAQUE_CADENAS_CMS' => $H018_LARGO_UND_EMPAQUE_CADENAS_CMS, 'H018_ANCHO_UND_EMPAQUE_CADENAS_CMS' => $H018_ANCHO_UND_EMPAQUE_CADENAS_CMS, 'H018_ALTO_UND_EMPAQUE_CADENAS_CMS' => $H018_ALTO_UND_EMPAQUE_CADENAS_CMS, 'H018_PESO_UND_EMPAQUE_CADENAS_KG' => $H018_PESO_UND_EMPAQUE_CADENAS_KG, 'H018_UND_VENTA_MINIMA' => $H018_UND_VENTA_MINIMA, 'ORIGEN' => $ORIGEN, 'TIPO' => $TIPO, 'CLASIFICACION' => $CLASIFICACION, 'CLASE' => $CLASE, 'MARCA' => $MARCA, 'SEGMENTO' => $SEGMENTO, 'LINEA' => $LINEA, 'SUB_LINEA' => $SUB_LINEA, 'I_UNIDAD_NEGOCIO' => $I_UNIDAD_NEGOCIO, 'CATEGORIA_ORACLE' => $CATEGORIA_ORACLE, 'SUB_MARCA' => $SUB_MARCA);
    $array_info[$Item]['unds'][] = array('L001' => $L001, 'L860' => $L860, 'L111' => $L111, 'UND' => $UND, 'Factor' => $Factor, 'EAN' => $EAN);

}

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'UN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Item');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Referencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Descripción');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Estado siesa');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Estado comercial');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'L001 (1)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'L860 (1)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'L111 (1)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'UND. (1)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Factor (1)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'EAN (1)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'UND. (2)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Factor (2)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'EAN (2)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'UND. (3)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Factor (3)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'EAN (3)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'UND. (4)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'Factor (4)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'EAN (4)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'IVA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'Producto exento ?');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'Cant. disponible');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'Total');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', 'Automotriz');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', 'Electrico');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'Ferretero');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', 'Mixto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'Mixto B/GA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE1', 'Largo producto CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', 'Ancho producto CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG1', 'Alto producto CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH1', 'Peso producto KG');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI1', 'Largo und. emp. principal CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ1', 'Ancho und. emp. principal CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK1', 'Alto und. emp. principal CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL1', 'Peso und. emp. principal KG');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM1', 'Largo und. emp. cadenas CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN1', 'Ancho und. emp. cadenas CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO1', 'Alto und. emp. cadenas CMS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP1', 'Peso und. emp. cadenas KG');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ1', 'Und. venta mínimo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR1', 'Origen');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS1', 'Tipo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT1', 'Clasificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU1', 'Clase');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV1', 'Marca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW1', 'Segmento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX1', 'Línea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AY1', 'Sub-línea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AZ1', 'Unidad de negocio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BA1', 'Categoria oracle');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('BB1', 'Sub-marca');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:BB1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:BB1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

foreach ($array_info as $item => $data) {

    $UN                = $data['info']['UN']; 
    $Item              = $data['info']['Item']; 
    $Referencia        = $data['info']['Referencia'];
    $Descripcion       = $data['info']['Descripcion'];
    $Estado_Siesa      = $data['info']['Estado_Siesa'];
    $Estado_Comercial  = $data['info']['Estado_Comercial'];

    $IVA                = $data['info']['IVA'];
    $Producto_exento    = $data['info']['Producto_exento'];
    $Cant_Disponible    = $data['info']['Cant_Disponible'];
    $TOTAL              = $data['info']['TOTAL'];
    $AUTOMOTRIZ         = $data['info']['AUTOMOTRIZ'];
    $ELECTRICO          = $data['info']['ELECTRICO'];
    $FERRETERO          = $data['info']['FERRETERO'];
    $MIXTO              = $data['info']['MIXTO'];
    $MIXTO_B_GA         = $data['info']['MIXTO_B_GA'];
    $H018_LARGO_PRODUCTO_CMS = $data['info']['H018_LARGO_PRODUCTO_CMS'];
    $H018_ANCHO_PRODUCTO_CMS = $data['info']['H018_ANCHO_PRODUCTO_CMS'];
    $H018_ALTO_PRODUCTO_CMS = $data['info']['H018_ALTO_PRODUCTO_CMS'];
    $H018_PESO_PRODUCTO_KG = $data['info']['H018_PESO_PRODUCTO_KG'];
    $H018_LARGO_UND_EMPAQUE_PPAL_CMS = $data['info']['H018_LARGO_UND_EMPAQUE_PPAL_CMS'];
    $H018_ANCHO_UND_EMPAQUE_PPAL_CMS = $data['info']['H018_ANCHO_UND_EMPAQUE_PPAL_CMS'];
    $H018_ALTO_UND_EMPAQUE_PPAL_CMS = $data['info']['H018_ALTO_UND_EMPAQUE_PPAL_CMS'];
    $H018_PESO_UND_EMPAQUE_PPAL_KG = $data['info']['H018_PESO_UND_EMPAQUE_PPAL_KG'];
    $H018_LARGO_UND_EMPAQUE_CADENAS_CMS = $data['info']['H018_LARGO_UND_EMPAQUE_CADENAS_CMS'];
    $H018_ANCHO_UND_EMPAQUE_CADENAS_CMS = $data['info']['H018_ANCHO_UND_EMPAQUE_CADENAS_CMS'];
    $H018_ALTO_UND_EMPAQUE_CADENAS_CMS = $data['info']['H018_ALTO_UND_EMPAQUE_CADENAS_CMS'];
    $H018_PESO_UND_EMPAQUE_CADENAS_KG = $data['info']['H018_PESO_UND_EMPAQUE_CADENAS_KG'];
    $H018_UND_VENTA_MINIMA = $data['info']['H018_UND_VENTA_MINIMA'];
    $ORIGEN = $data['info']['ORIGEN'];
    $TIPO = $data['info']['TIPO'];
    $CLASIFICACION = $data['info']['CLASIFICACION'];
    $CLASE = $data['info']['CLASE'];
    $MARCA = $data['info']['MARCA'];
    $SEGMENTO = $data['info']['SEGMENTO'];
    $LINEA = $data['info']['LINEA'];
    $SUB_LINEA = $data['info']['SUB_LINEA'];
    $I_UNIDAD_NEGOCIO = $data['info']['I_UNIDAD_NEGOCIO'];
    $CATEGORIA_ORACLE = $data['info']['CATEGORIA_ORACLE'];
    $SUB_MARCA = $data['info']['SUB_MARCA'];

    $unds = $data['unds'];
    $num_unds = count($unds);
    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $UN);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $Item);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $Referencia);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $Descripcion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $Estado_Siesa);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Estado_Comercial);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    if($num_unds == 1){

        $va = $unds[0]['L001']; 
        $vb = $unds[0]['L860'];
        $vc = $unds[0]['L111'];
        $vd = $unds[0]['UND'];
        $ve = $unds[0]['Factor'];
        $vf = $unds[0]['EAN'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $vb);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $vc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $vd);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ve);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('L'.$Fila, $vf, $type_string);

        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, '');

    }

    if($num_unds == 2){

        $va = $unds[0]['L001']; 
        $vb = $unds[0]['L860'];
        $vc = $unds[0]['L111'];
        $vd = $unds[0]['UND'];
        $ve = $unds[0]['Factor'];
        $vf = $unds[0]['EAN'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $vb);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $vc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $vd);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ve);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('L'.$Fila, $vf, $type_string);

        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

        $vj = $unds[1]['UND'];
        $vk = $unds[1]['Factor'];
        $vl = $unds[1]['EAN'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $vj);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $vk);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('O'.$Fila, $vl, $type_string);
        $objPHPExcel->getActiveSheet(0)->getStyle('M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, '');

    }

    if($num_unds == 3){
        
        $va = $unds[0]['L001']; 
        $vb = $unds[0]['L860'];
        $vc = $unds[0]['L111'];
        $vd = $unds[0]['UND'];
        $ve = $unds[0]['Factor'];
        $vf = $unds[0]['EAN'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $vb);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $vc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $vd);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ve);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('L'.$Fila, $vf, $type_string);

        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

        $vj = $unds[1]['UND'];
        $vk = $unds[1]['Factor'];
        $vl = $unds[1]['EAN'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $vj);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $vk);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('O'.$Fila, $vl, $type_string);
        $objPHPExcel->getActiveSheet(0)->getStyle('M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getAlignment()->setHorizontal($alignment_left);

        $vp = $unds[2]['UND'];
        $vq = $unds[2]['Factor'];
        $vr = $unds[2]['EAN'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $vp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $vq);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('R'.$Fila, $vr, $type_string);
        $objPHPExcel->getActiveSheet(0)->getStyle('P'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('R'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, '');

    }

    if($num_unds == 4){

        $va = $unds[0]['L001']; 
        $vb = $unds[0]['L860'];
        $vc = $unds[0]['L111'];
        $vd = $unds[0]['UND'];
        $ve = $unds[0]['Factor'];
        $vf = $unds[0]['EAN'];
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $va);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $vb);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $vc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $vd);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $ve);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('L'.$Fila, $vf, $type_string);

        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('L'.$Fila)->getAlignment()->setHorizontal($alignment_left);

        $vj = $unds[1]['UND'];
        $vk = $unds[1]['Factor'];
        $vl = $unds[1]['EAN'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $vj);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $vk);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('O'.$Fila, $vl, $type_string);
        $objPHPExcel->getActiveSheet(0)->getStyle('M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila)->getAlignment()->setHorizontal($alignment_left);

        $vp = $unds[2]['UND'];
        $vq = $unds[2]['Factor'];
        $vr = $unds[2]['EAN'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $vp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $vq);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('R'.$Fila, $vr, $type_string);
        $objPHPExcel->getActiveSheet(0)->getStyle('P'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('R'.$Fila)->getAlignment()->setHorizontal($alignment_left);

        $vv = $unds[3]['UND'];
        $vw = $unds[3]['Factor'];
        $vx = $unds[3]['EAN'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $vv);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $vw);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('U'.$Fila, $vx, $type_string);
        $objPHPExcel->getActiveSheet(0)->getStyle('S'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('T'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('T'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('U'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $IVA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $Producto_exento);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $Cant_Disponible);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $TOTAL);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $AUTOMOTRIZ);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$Fila, $ELECTRICO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$Fila, $FERRETERO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$Fila, $MIXTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$Fila, $MIXTO_B_GA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$Fila, $H018_LARGO_PRODUCTO_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $H018_ANCHO_PRODUCTO_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, $H018_ALTO_PRODUCTO_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$Fila, $H018_PESO_PRODUCTO_KG);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$Fila, $H018_LARGO_UND_EMPAQUE_PPAL_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$Fila, $H018_ANCHO_UND_EMPAQUE_PPAL_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$Fila, $H018_ALTO_UND_EMPAQUE_PPAL_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$Fila, $H018_PESO_UND_EMPAQUE_PPAL_KG);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$Fila, $H018_LARGO_UND_EMPAQUE_CADENAS_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$Fila, $H018_ANCHO_UND_EMPAQUE_CADENAS_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$Fila, $H018_ALTO_UND_EMPAQUE_CADENAS_CMS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$Fila, $H018_PESO_UND_EMPAQUE_CADENAS_KG);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$Fila, $H018_UND_VENTA_MINIMA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR'.$Fila, $ORIGEN);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AS'.$Fila, $TIPO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AT'.$Fila, $CLASIFICACION);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU'.$Fila, $CLASE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV'.$Fila, $MARCA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW'.$Fila, $SEGMENTO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AX'.$Fila, $LINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AY'.$Fila, $SUB_LINEA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AZ'.$Fila, $I_UNIDAD_NEGOCIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BA'.$Fila, $CATEGORIA_ORACLE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BB'.$Fila, $SUB_MARCA);

    $objPHPExcel->getActiveSheet(0)->getStyle('V'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('V'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('W'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('X'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('X'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('Y'.$Fila.':AD'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('AE'.$Fila.':AP'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');

    if($H018_UND_VENTA_MINIMA != ''){
        $objPHPExcel->getActiveSheet(0)->getStyle('AQ'.$Fila)->getNumberFormat()->setFormatCode('0');
    }

    $objPHPExcel->getActiveSheet(0)->getStyle('AE'.$Fila.':AQ'.$Fila)->getAlignment()->setHorizontal($alignment_right);
    $objPHPExcel->getActiveSheet(0)->getStyle('AR'.$Fila.':BB'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 54; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Logistica_comercial_x_un_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

//CLASES

if(isset($model['clase'])) {
  $v_clase = $model['clase'];

  $array_clases =  $model['clase'];
  $clases = "";
  foreach ($array_clases as $a_clases => $valor) {
    $clases .= "".$valor.",";
  }
  $clases = substr ($clases, 0, -1);

  //clases
  $q_clases = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 100 AND Id_Criterio IN (".$clases.") ORDER BY Criterio_Descripcion")->queryAll();

  $condicion_clases = '';
  $texto_clases = '';

  foreach ($q_clases as $cla) {
    $condicion_clases .= $cla['Id_Criterio'].',';
    $texto_clases .= $cla['Criterio_Descripcion'].',';
  }

  $texto_clases = substr ($texto_clases, 0, -1);
  $condicion_clases = substr ($condicion_clases, 0, -1);

}else{
  $v_clase = "";
  $texto_clases = "TODOS";
  $condicion_clases = "";
}

//CANALES 

if(isset($model['canal'])) {
  $v_canal = $model['canal'];

  $array_canales =  $model['canal'];
  $canales = "";
  foreach ($array_canales as $a_canales => $valor) {
    $canales .= "'".$valor."',";
  }
  $canales = substr ($canales, 0, -1);

  //canales
  $q_canales = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 200 AND Id_Criterio IN (".$canales.") ORDER BY Criterio_Descripcion")->queryAll();

  $condicion_canales = '';
  $texto_canales = '';

  foreach ($q_canales as $can) {
    $condicion_canales .= $can['Id_Criterio'].',';
    $texto_canales .= $can['Criterio_Descripcion'].',';
  }

  $texto_canales = substr ($texto_canales, 0, -1);
  $condicion_canales = substr ($condicion_canales, 0, -1);

}else{
  $v_canal = "";
  $texto_canales = "TODOS";
  $condicion_canales = "";

}

//REGIONALES

if(isset($model['reg'])) {
  $v_regional = $model['reg'];

  $array_regionales =  $model['reg'];
  $regionales = "";
  foreach ($array_regionales as $a_regionales => $valor) {
    $regionales .= "'".$valor."',";
  }
  $regionales = substr ($regionales, 0, -1);

  //regionales
  $q_regionales = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 600 AND Id_Criterio IN (".$regionales.") ORDER BY Criterio_Descripcion")->queryAll();

  $condicion_regionales = '';
  $texto_regionales = '';

  foreach ($q_regionales as $reg) {
    $condicion_regionales .= $reg['Id_Criterio'].',';
    $texto_regionales .= $reg['Criterio_Descripcion'].',';
  }

  $texto_regionales = substr ($texto_regionales, 0, -1);
  $condicion_regionales = substr ($condicion_regionales, 0, -1);

}else{
  $v_regional = "";
  $texto_regionales = "TODOS";
  $condicion_regionales = "";
}


if($v_clase == "" && $v_canal == "" && $v_regional == ""){
  $opc = 0;
}else if($v_clase != "" && $v_canal == "" && $v_regional == ""){
  $opc = 1;
}else if($v_clase == "" && $v_canal != "" && $v_regional == ""){
  $opc = 2;
}else if($v_clase == "" && $v_canal == "" && $v_regional != ""){
  $opc = 3;
}else if($v_clase != "" && $v_canal != "" && $v_regional == ""){
  $opc = 4;
}else if($v_clase != "" && $v_canal == "" && $v_regional != ""){
  $opc = 5;
}else if($v_clase == "" && $v_canal != "" && $v_regional != ""){
  $opc = 6;
}else if($v_clase != "" && $v_canal != "" && $v_regional != ""){
  $opc = 7;
}

//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

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

$query ="
  SET NOCOUNT ON
  EXEC P_PR_COM_RT_CNL_CLS_REG_FECH
  @FECHA1 = N'".$FechaM1."',
  @FECHA2 = N'".$FechaM2."',
  @OPT = ".$opc.",
  @VAR1 = N'".$condicion_clases."',
  @VAR2 = N'".$condicion_canales."',
  @VAR3 = N'".$condicion_regionales."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{
    
    function setFechaInicial($fecha_inicial){
      $this->fecha_inicial = $fecha_inicial;
    }

    function setFechaFinal($fecha_inicial){
      $this->fecha_final = $fecha_inicial;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setClases($texto_clases){
      $this->clases = $texto_clases;
    }

    function setCanales($texto_canales){
      $this->canales = $texto_canales;
    }
    function setRegionales($texto_regionales){
      $this->regionales = $texto_regionales;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',12);
      $this->Cell(200,5,'RENTABILIDAD POR CRITERIOS',0,0,'L');
      $this->SetFont('Arial','',9);
      $this->Cell(140,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: Fecha del '.$this->fecha_inicial.' al '.$this->fecha_final),0,0,'L');
      $this->Ln();
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: Clases: '.$this->clases.' / Canales: '.$this->canales.' / Regionales: '.$this->regionales),0,0,'L');
      $this->Ln();
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',6);
  
      $this->Cell(32,2,utf8_decode('ORACLE'),0,0,'L');
      $this->Cell(23,2,utf8_decode('VLR'),0,0,'R');
      $this->Cell(23,2,utf8_decode('VLR'),0,0,'R');
      $this->Cell(23,2,utf8_decode('VLR VENTA'),0,0,'R');
      $this->Cell(23,2,utf8_decode('COSTO'),0,0,'R');
      $this->Cell(21,2,utf8_decode('COSTO'),0,0,'R');
      $this->Cell(19,2,utf8_decode('COSTO'),0,0,'R');
      $this->Cell(21,2,utf8_decode('UTILIDAD'),0,0,'R');
      $this->Cell(16,2,utf8_decode('% UTILIDAD'),0,0,'R');
      $this->Cell(23,2,utf8_decode('VLR DESC.'),0,0,'R');
      $this->Cell(21,2,utf8_decode('VLR DESC.'),0,0,'R');
      $this->Cell(21,2,utf8_decode('TOTAL'),0,0,'R');
      $this->Cell(16,2,utf8_decode('% UTILIDAD'),0,0,'R');
      $this->Cell(23,2,utf8_decode('UTILIDAD'),0,0,'R');
      $this->Cell(16,2,utf8_decode('% UTILIDAD'),0,0,'R');
      $this->Cell(19,2,utf8_decode('COSTO'),0,0,'R');   
      $this->Ln(3);   
      
      $this->Cell(32,2,'',0,0,'L');
      $this->Cell(23,2,utf8_decode('VENTA'),0,0,'R');
      $this->Cell(23,2,utf8_decode('DEVOLUCIÓN'),0,0,'R');
      $this->Cell(23,2,utf8_decode('BRUTA REAL'),0,0,'R');
      $this->Cell(23,2,utf8_decode('VENTA'),0,0,'R');
      $this->Cell(21,2,utf8_decode('DEVOLUCIÓN'),0,0,'R');
      $this->Cell(19,2,utf8_decode('REAL'),0,0,'R');
      $this->Cell(21,2,utf8_decode('BRUTA'),0,0,'R');
      $this->Cell(16,2,utf8_decode(''),0,0,'R');
      $this->Cell(23,2,utf8_decode('VENTA'),0,0,'R');
      $this->Cell(21,2,utf8_decode('DEVOLUCIÓN'),0,0,'R');
      $this->Cell(21,2,utf8_decode('DESC.'),0,0,'R');
      $this->Cell(16,2,utf8_decode('DESC.'),0,0,'R');
      $this->Cell(23,2,utf8_decode('NETA'),0,0,'R');
      $this->Cell(16,2,utf8_decode('NETA'),0,0,'R');
      $this->Cell(19,2,utf8_decode('INVENTARIO'),0,0,'R');
      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();
      
      $VLR_VENTA_st = 0;
      $VLR_DEVOLUCION_st = 0;
      $VLR_VENTA_BRUTA_REAL_st = 0;
      $COSTO_VENTA_st = 0;
      $COSTO_DEVOLUCION_st = 0;
      $COSTO_REAL_st = 0;
      $UTILIDAD_BRUTA_st = 0;
      $VLR_DESC_VENTA_st = 0;
      $VLR_DESC_DEVOLUCION_st = 0;
      $TOTAL_DESC_st = 0;
      $UTILIDAD_NETA_st = 0;
      $COSTO_INVENTARIO_st = 0;
      $NOTAS_st = 0;

      foreach ($query1 as $reg1) {

        $MARCA                = $reg1 ['ORACLE'];    
        $VLR_VENTA            = $reg1 ['VLR_VTA']; 
        $VLR_DEVOLUCION       = $reg1 ['VLR_DVO'];
        $VLR_VENTA_BRUTA_REAL = $reg1 ['VEN_BRUTA_REAL'];
        $COSTO_VENTA          = $reg1 ['COSTO_VTA'];
        $COSTO_DEVOLUCION     = $reg1 ['COSTO_DVO']; 
        $COSTO_REAL           = $reg1 ['COSTO_REAL'];
        $UTILIDAD_BRUTA       = $reg1 ['UTIL_BRUTA'];
        $PORC_UTILIDAD        = $reg1 ['POR_UTIL_BRUTA'] * 100;
        $VLR_DESC_VENTA       = $reg1 ['VLR_DESC_VTA'];
        $VLR_DESC_DEVOLUCION  = $reg1 ['VLR_DESC_DVO'];
        $TOTAL_DESC           = $reg1 ['TOT_DESC'];
        $PORC_UTILIDAD_DESC   = $reg1 ['POR_UTI_DESC']  * 100;
        $UTILIDAD_NETA        = $reg1 ['UTIL_NETA']; 
        $PORC_UTILIDAD_NETA   = $reg1 ['POR_UTIL_NETA']  * 100;
        $COSTO_INVENTARIO     = $reg1 ['COSTO_INV'];
        $NOTAS                = $reg1 ['NOTAS'];  

        if($MARCA != "NOTAS CREDITO"){
          $this->SetFont('Arial','',6);
          $this->Cell(32,3,substr(utf8_decode($MARCA),0 , 22),0,0,'L');
          $this->Cell(23,3,number_format(($VLR_VENTA),2,".",","),0,0,'R');
          $this->Cell(23,3,number_format(($VLR_DEVOLUCION),2,".",","),0,0,'R');
          $this->Cell(23,3,number_format(($VLR_VENTA_BRUTA_REAL),2,".",","),0,0,'R');
          $this->Cell(23,3,number_format(($COSTO_VENTA),2,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($COSTO_DEVOLUCION),2,".",","),0,0,'R');
          $this->Cell(19,3,number_format(($COSTO_REAL),2,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($UTILIDAD_BRUTA),2,".",","),0,0,'R');
          $this->Cell(16,3,number_format(($PORC_UTILIDAD),2,".",",").' %',0,0,'R');
          $this->Cell(23,3,number_format(($VLR_DESC_VENTA),2,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($VLR_DESC_DEVOLUCION),2,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($TOTAL_DESC),2,".",","),0,0,'R');
          $this->Cell(16,3,number_format(($PORC_UTILIDAD_DESC),2,".",",").' %',0,0,'R');
          $this->Cell(23,3,number_format(($UTILIDAD_NETA),2,".",","),0,0,'R');
          $this->Cell(16,3,number_format(($PORC_UTILIDAD_NETA),2,".",",").' %',0,0,'R');
          $this->Cell(19,3,number_format(($COSTO_INVENTARIO),2,".",","),0,0,'R');
          $this->Ln();
        }

        $VLR_VENTA_st += $VLR_VENTA;
        $VLR_DEVOLUCION_st += $VLR_DEVOLUCION;
        $VLR_VENTA_BRUTA_REAL_st += $VLR_VENTA_BRUTA_REAL;
        $COSTO_VENTA_st += $COSTO_VENTA;
        $COSTO_DEVOLUCION_st += $COSTO_DEVOLUCION;
        $COSTO_REAL_st += $COSTO_REAL;
        $UTILIDAD_BRUTA_st += $UTILIDAD_BRUTA;
        $VLR_DESC_VENTA_st += $VLR_DESC_VENTA;
        $VLR_DESC_DEVOLUCION_st += $VLR_DESC_DEVOLUCION;
        $TOTAL_DESC_st += $TOTAL_DESC;
        $UTILIDAD_NETA_st += $UTILIDAD_NETA;
        $COSTO_INVENTARIO_st += $COSTO_INVENTARIO;
        $NOTAS_st += $NOTAS;


      }

      if($VLR_VENTA_BRUTA_REAL_st == 0){
        $VLR_VENTA_BRUTA_REAL_st = 0.00000001;
      }

      $this->SetFont('Arial','B',5);
      $this->Cell(32,5,'TOTAL GENERAL',0,0,'R');
      $this->Cell(23,5,number_format(($VLR_VENTA_st),2,".",","),0,0,'R');
      $this->Cell(23,5,number_format(($VLR_DEVOLUCION_st),2,".",","),0,0,'R');
      $this->Cell(23,5,number_format(($VLR_VENTA_BRUTA_REAL_st),2,".",","),0,0,'R');
      $this->Cell(23,5,number_format(($COSTO_VENTA_st),2,".",","),0,0,'R');
      $this->Cell(21,5,number_format(($COSTO_DEVOLUCION_st),2,".",","),0,0,'R');
      $this->Cell(19,5,number_format(($COSTO_REAL_st),2,".",","),0,0,'R');
      $this->Cell(21,5,number_format(($UTILIDAD_BRUTA_st),2,".",","),0,0,'R');
      $PORC_UTILIDAD_st = ($UTILIDAD_BRUTA_st / $VLR_VENTA_BRUTA_REAL_st)  * 100;
      $this->Cell(16,5,number_format(($PORC_UTILIDAD_st),2,".",",").' %',0,0,'R');
      $this->Cell(23,5,number_format(($VLR_DESC_VENTA_st),2,".",","),0,0,'R');
      $this->Cell(21,5,number_format(($VLR_DESC_DEVOLUCION_st),2,".",","),0,0,'R');
      $this->Cell(21,5,number_format(($TOTAL_DESC_st),2,".",","),0,0,'R');
      $PORC_UTILIDAD_DESC_st = ($TOTAL_DESC_st / $VLR_VENTA_BRUTA_REAL_st)  * 100;
      $this->Cell(16,5,number_format(($PORC_UTILIDAD_DESC_st),2,".",",").' %',0,0,'R');
      $this->Cell(23,5,number_format(($UTILIDAD_NETA_st),2,".",","),0,0,'R');
      $PORC_UTILIDAD_NETA_st = ($UTILIDAD_NETA_st / $VLR_VENTA_BRUTA_REAL_st)  * 100;
      $this->Cell(16,5,number_format(($PORC_UTILIDAD_NETA_st),2,".",",").' %',0,0,'R');
      $this->Cell(19,5,number_format(($COSTO_INVENTARIO_st),2,".",","),0,0,'R');

      $this->Ln();

      $this->SetFont('Arial','B',5);
      $this->Cell(32,5,utf8_decode('NOTAS CRÉDITO'),0,0,'R');
      $this->Cell(23,5,number_format(($NOTAS_st),2,".",","),0,0,'R');
      $this->Ln();

      $this->SetDrawColor(0,0,0);
      $this->Cell(340,0,'','T');                            
      $this->Ln();


    }//fin tabla

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','B',6);
        $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
    }
  }

  $pdf = new PDF('L','mm','Legal');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setFechaInicial($fecha_inicial);
  $pdf->setFechaFinal($fecha_final);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->setClases($texto_clases);
  $pdf->setCanales($texto_canales);
  $pdf->setRegionales($texto_regionales);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Rentabilidad_criterios_'.date('Y_m_d_H_i_s').'.pdf');
}

if($opcion == 2){
  //EXCEL

  $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
  $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
  $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
  $type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

  $objPHPExcel = new Spreadsheet();

  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Reporte');

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ORACLE');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'VLR VENTA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'VLR DEVOLUCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'VLR VENTA BRUTA REAL');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'COSTO VENTA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'COSTO DEVOLUCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'COSTO REAL');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'UTILIDAD BRUTA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', '% UTILIDAD');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'VLR DESC. VENTA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'VLR DESC. DEVOLUCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'TOTAL DESC.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', '% UTILIDAD DESC.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'UTILIDAD NETA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', '% UTILIDAD NETA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'COSTO INVENTARIO');

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  $VLR_VENTA_st = 0;
  $VLR_DEVOLUCION_st = 0;
  $VLR_VENTA_BRUTA_REAL_st = 0;
  $COSTO_VENTA_st = 0;
  $COSTO_DEVOLUCION_st = 0;
  $COSTO_REAL_st = 0;
  $UTILIDAD_BRUTA_st = 0;
  $VLR_DESC_VENTA_st = 0;
  $VLR_DESC_DEVOLUCION_st = 0;
  $TOTAL_DESC_st = 0;
  $UTILIDAD_NETA_st = 0;
  $COSTO_INVENTARIO_st = 0;
  $NOTAS_st = 0;

  foreach ($query1 as $reg1) {

    $MARCA                = $reg1 ['ORACLE'];    
    $VLR_VENTA            = $reg1 ['VLR_VTA']; 
    $VLR_DEVOLUCION       = $reg1 ['VLR_DVO'];
    $VLR_VENTA_BRUTA_REAL = $reg1 ['VEN_BRUTA_REAL'];
    $COSTO_VENTA          = $reg1 ['COSTO_VTA'];
    $COSTO_DEVOLUCION     = $reg1 ['COSTO_DVO']; 
    $COSTO_REAL           = $reg1 ['COSTO_REAL'];
    $UTILIDAD_BRUTA       = $reg1 ['UTIL_BRUTA'];
    $PORC_UTILIDAD        = $reg1 ['POR_UTIL_BRUTA'] * 100;
    $VLR_DESC_VENTA       = $reg1 ['VLR_DESC_VTA'];
    $VLR_DESC_DEVOLUCION  = $reg1 ['VLR_DESC_DVO'];
    $TOTAL_DESC           = $reg1 ['TOT_DESC'];
    $PORC_UTILIDAD_DESC   = $reg1 ['POR_UTI_DESC'] * 100;
    $UTILIDAD_NETA        = $reg1 ['UTIL_NETA']; 
    $PORC_UTILIDAD_NETA   = $reg1 ['POR_UTIL_NETA'] * 100;
    $COSTO_INVENTARIO     = $reg1 ['COSTO_INV'];
    $NOTAS                = $reg1 ['NOTAS'];   

    if($MARCA != "NOTAS CREDITO"){
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $MARCA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $VLR_VENTA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $VLR_DEVOLUCION);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $VLR_VENTA_BRUTA_REAL);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $COSTO_VENTA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $COSTO_DEVOLUCION);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $COSTO_REAL);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $UTILIDAD_BRUTA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $PORC_UTILIDAD);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $VLR_DESC_VENTA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $VLR_DESC_DEVOLUCION);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $TOTAL_DESC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $PORC_UTILIDAD_DESC);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $UTILIDAD_NETA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $PORC_UTILIDAD_NETA);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $COSTO_INVENTARIO);
          
      $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
      $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);

      $Fila = $Fila + 1;
    }

    $VLR_VENTA_st += $VLR_VENTA;
    $VLR_DEVOLUCION_st += $VLR_DEVOLUCION;
    $VLR_VENTA_BRUTA_REAL_st += $VLR_VENTA_BRUTA_REAL;
    $COSTO_VENTA_st += $COSTO_VENTA;
    $COSTO_DEVOLUCION_st += $COSTO_DEVOLUCION;
    $COSTO_REAL_st += $COSTO_REAL;
    $UTILIDAD_BRUTA_st += $UTILIDAD_BRUTA;
    $VLR_DESC_VENTA_st += $VLR_DESC_VENTA;
    $VLR_DESC_DEVOLUCION_st += $VLR_DESC_DEVOLUCION;
    $TOTAL_DESC_st += $TOTAL_DESC;
    $UTILIDAD_NETA_st += $UTILIDAD_NETA;
    $COSTO_INVENTARIO_st += $COSTO_INVENTARIO;
    $NOTAS_st += $NOTAS;

  }

  if($VLR_VENTA_BRUTA_REAL_st == 0){
    $VLR_VENTA_BRUTA_REAL_st = 0.00000001;
  }
    
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTAL GENERAL');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $VLR_VENTA_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $VLR_DEVOLUCION_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $VLR_VENTA_BRUTA_REAL_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $COSTO_VENTA_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $COSTO_DEVOLUCION_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $COSTO_REAL_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $UTILIDAD_BRUTA_st);
  $PORC_UTILIDAD_st = ($UTILIDAD_BRUTA_st / $VLR_VENTA_BRUTA_REAL_st)  * 100;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $PORC_UTILIDAD_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $VLR_DESC_VENTA_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $VLR_DESC_DEVOLUCION_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $TOTAL_DESC_st);
  $PORC_UTILIDAD_DESC_st = ($TOTAL_DESC_st / $VLR_VENTA_BRUTA_REAL_st)  * 100;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $PORC_UTILIDAD_DESC_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $UTILIDAD_NETA_st);
  $PORC_UTILIDAD_NETA_st = ($UTILIDAD_NETA_st / $VLR_VENTA_BRUTA_REAL_st)  * 100;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $PORC_UTILIDAD_NETA_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $COSTO_INVENTARIO_st);
 
  $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':P'.$Fila)->getFont()->setBold(true);

  $Fila = $Fila + 1;

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'NOTAS CRÉDITO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $NOTAS_st);

  $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':B'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':B'.$Fila)->getFont()->setBold(true);

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 16; 

  foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Rentabilidad_criterios_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












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

//se reciben los parametros para el reporte
$origen = $model['origen'];

//Estados

if(isset($model['estado'])) {
  $v_estado = $model['estado'];

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

}else{
  $v_estado = "";
  $texto_estados = "TODOS";
  $condicion_estados = "";
}

//Tipos

if(isset($model['tipo'])) {
  $v_tipo = $model['tipo'];

  $array_tipos =  $model['tipo'];
  $tipos = "";
  $texto_tipos = "";
  $condicion_tipos = "";

  foreach ($array_tipos as $a_tipos => $valor) {
    $tipos .= "".$valor.",";
    if($valor == "COM"){
      $texto_tipos .= 'COMPRADOS,';
      $condicion_tipos .= "".$valor.",";
    }
    if($valor == "FAB"){
      $texto_tipos .= 'FABRICADOS,';
      $condicion_tipos .= "".$valor.",";
    }

  }

  $texto_tipos = substr ($texto_tipos, 0, -1);
  $condicion_tipos = substr ($condicion_tipos, 0, -1);

}else{
  $v_tipo = "";
  $texto_tipos = "TODOS";
  $condicion_tipos = "";
}

//se arma la variable opción

if($origen != "" && $condicion_estados != "" && $condicion_tipos == ""){
  $opc = 1;
}
if($origen != "" && $condicion_estados == "" && $condicion_tipos != ""){
  $opc = 2;
}
if($origen != "" && $condicion_estados != "" && $condicion_tipos != ""){
  $opc = 3;
}
if($origen != "" && $condicion_estados == "" && $condicion_tipos == ""){
  $opc = 4;
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

$query= "
    SET NOCOUNT ON
    EXEC P_PR_COM_CONT_PED_ORIGEN
    @OPT = ".$opc.",
    @VAR1 = N'".$origen."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'".$condicion_tipos."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_ma = array();

foreach ($query1 as $reg) {
  
  $marca               = $reg ['CI_MARCA'];
  $item                = $reg ['CI_ITEM'];
  $referencia          = $reg ['CI_REFERENCIA'];
  $descripcion         = $reg ['CI_DESCRIPCION'];
  $estado              = $reg ['CI_ESTADO'];

  if($reg ['CI_LOTE'] == NULL){
    $und_compra = 0;
  }else{
    $und_compra = $reg ['CI_LOTE'];
  }

  if($reg ['CI_PROMEDIO'] == NULL){
    $prom_ventas = 0;
  }else{
    $prom_ventas = $reg ['CI_PROMEDIO'];
  }

  if($reg ['CI_STOCK'] == NULL){
    $stock_meses = 0;
  }else{
    $stock_meses = $reg ['CI_STOCK'];
  }

  if($reg ['CI_BASE'] == NULL){
    $base_pedidos = 0;
  }else{
    $base_pedidos = $reg ['CI_BASE'];
  }

  if($reg ['CI_EXIS'] == NULL){
    $exist_fecha = 0;
  }else{
    $exist_fecha = $reg ['CI_EXIS'];
  }

  if($reg ['CI_ENTRAR'] == NULL){
    $o_c_pend = 0;
  }else{
    $o_c_pend = $reg ['CI_ENTRAR'];
  }

  if($reg ['CI_AD_PEDIR'] == NULL){
    $ad_pedir = 0;
  }else{
    $ad_pedir = $reg ['CI_AD_PEDIR'];
  }

  if($reg ['CI_DIAS'] == NULL){
    
    $dias_cub = 0;

  }else{
    
    if($reg ['CI_DIAS'] > 100000){
    
      $dias_cub = 0;

    }else{
      
      $dias_cub = $reg ['CI_DIAS'];

    }

  }

  if(!array_key_exists($marca, $array_ma)) {
    $array_ma[$marca] = array();
    $array_ma[$marca]['marca'] = $marca;
    $array_ma[$marca]['info'][] = array(
      'item' => $item,
      'referencia' => $referencia,
      'descripcion' => $descripcion,
      'estado' => $estado,
      'und_compra' => $und_compra,
      'prom_ventas' => $prom_ventas,
      'stock_meses' => $stock_meses,
      'base_pedidos' => $base_pedidos,
      'exist_fecha' => $exist_fecha,
      'o_c_pend' => $o_c_pend,
      'ad_pedir' => $ad_pedir,
      'dias_cub' => $dias_cub,
    );     
  }else{
    $array_ma[$marca]['info'][] = array(
    'item' => $item,
    'referencia' => $referencia,
    'descripcion' => $descripcion,
    'estado' => $estado,
    'und_compra' => $und_compra,
    'prom_ventas' => $prom_ventas,
    'stock_meses' => $stock_meses,
    'base_pedidos' => $base_pedidos,
    'exist_fecha' => $exist_fecha,
    'o_c_pend' => $o_c_pend,
    'ad_pedir' => $ad_pedir,
    'dias_cub' => $dias_cub,
    );        
  }
}

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }
    
    function setOrigen($origen){
      $this->origen = $origen;
    }

    function setEstados($estados){
      $this->estados = $estados;
    }

    function setTipos($tipos){
      $this->tipos = $tipos;
    }

    function setData($data){
      $this->data = $data;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(100,5,utf8_decode('CONTROL DE PEDIDOS POR ORIGEN'),0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(95,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(195,5,utf8_decode('Criterio de búsqueda / Origen: '.$this->origen),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(195,5,utf8_decode('Criterio de búsqueda / Estado(s): '.$this->estados),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(195,5,utf8_decode('Criterio de búsqueda / Tipo(s): '.$this->tipos),0,0,'L');
      $this->Ln();
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',5);
  
      $this->Cell(10,2,utf8_decode('MARCA'),0,0,'L');
      $this->Cell(19,2,utf8_decode('REFERENCIA'),0,0,'L');
      $this->Cell(44,2,utf8_decode('DESCRIPCIÓN'),0,0,'L');
      $this->Cell(10,2,utf8_decode('ESTADO'),0,0,'L');
      $this->Cell(14,2,utf8_decode('UND.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PROM.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('STOCK'),0,0,'R');
      $this->Cell(14,2,utf8_decode('BASE'),0,0,'R');
      $this->Cell(14,2,utf8_decode('EXIST.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('O.C'),0,0,'R');
      $this->Cell(14,2,utf8_decode('A.D'),0,0,'R');
      $this->Cell(14,2,utf8_decode('# DÍAS'),0,0,'R');
      
      $this->Ln(3);   
      
      $this->Cell(10,2,utf8_decode('CÓDIGO'),0,0,'L');
      $this->Cell(19,2,utf8_decode(''),0,0,'L');
      $this->Cell(44,2,utf8_decode(''),0,0,'L');
      $this->Cell(10,2,utf8_decode(''),0,0,'L');
      $this->Cell(14,2,utf8_decode('COMPRA'),0,0,'R');
      $this->Cell(14,2,utf8_decode('VENTAS'),0,0,'R');
      $this->Cell(14,2,utf8_decode('MESES'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEDIDOS'),0,0,'R');
      $this->Cell(14,2,utf8_decode('A LA FECHA'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEND.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEDIR'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CUBRIM.'),0,0,'R');


      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $array_ma = $this->data;

      foreach ($array_ma as $ma) {

        $this->SetFont('Arial','B',5);
        $this->Cell(150,3,utf8_decode($ma['marca']),0,0,'L');
        $this->Ln();

        $this->SetDrawColor(0,0,0);
        $this->Cell(195,1,'','T');                            
        $this->Ln();

        foreach ($ma['info'] as $info ) {

          $this->SetFont('Arial','',5);
          $this->Cell(10,3,$info['item'],0,0,'L');
          $this->Cell(19,3,substr(utf8_decode($info['referencia']),0, 20) ,0,0,'L');
          $this->Cell(44,3,substr(utf8_decode($info['descripcion']), 0, 35),0,0,'L');
          $this->Cell(10,3,substr(utf8_decode($info['estado']), 0, 8),0,0,'L');
          $this->Cell(14,3,number_format(($info['und_compra']),0,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['prom_ventas']),0,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['stock_meses']),2,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['base_pedidos']),0,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['exist_fecha']),0,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['o_c_pend']),0,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['ad_pedir']),0,".",","),0,0,'R');
          $this->Cell(14,3,number_format(($info['dias_cub']),0,".",","),0,0,'R');
          $this->Ln();
        }

        
        $this->SetDrawColor(0,0,0);
        $this->Cell(195,1,'','T');                            
        $this->Ln();

      }

    }//fin tabla

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','B',6);
        $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
    }
  }

  $pdf = new PDF('P','mm','A4');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setOrigen($origen);
  $pdf->setEstados($texto_estados);
  $pdf->setTipos($texto_tipos);
  $pdf->setFechaActual($fecha_act);
  $pdf->setData($array_ma);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Control_pedidos_origen_'.date('Y_m_d_H_i_s').'.pdf');
}

if($opcion == 2){
  //EXCEL

  $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
  $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
  $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

  $objPHPExcel = new Spreadsheet();

  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Reporte');

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'MARCA / CÓDIGO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'REFERENCIA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'ESTADO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'UND. COMPRA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'PROM. VENTAS');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'STOCK MESES');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'BASE PEDIDOS');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'EXIST. A LA FECHA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'O.C PEND.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'A.D PEDIR');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', '# DÍAS CUBRIM.');

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:L1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
   
  $Fila = 2;

  foreach ($array_ma as $ma) {

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ma['marca']);
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
    $Fila = $Fila + 1;

    foreach ($ma['info'] as $info ) {

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $info['item']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $info['referencia']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $info['descripcion']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $info['estado']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $info['und_compra']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $info['prom_ventas']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $info['stock_meses']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $info['base_pedidos']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $info['exist_fecha']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $info['o_c_pend']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $info['ad_pedir']);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $info['dias_cub']);

      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
      $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':F'.$Fila)->getNumberFormat()->setFormatCode('0'); 
      $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
      $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila.':L'.$Fila)->getNumberFormat()->setFormatCode('0'); 
      $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':L'.$Fila)->getAlignment()->setHorizontal($alignment_right);      

      $Fila = $Fila + 1;

    }
  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 12; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Control_pedidos_origen_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>
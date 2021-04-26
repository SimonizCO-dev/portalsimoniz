<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se incluye la libreria pdf
require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//se reciben los parametros para el reporte

$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final']; 

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

//no viene tipo 

//FECHAS
$opt = 1;
$i_i = "";
$i_f = "";
$linea = "";
$criterio = 'Fecha : '.UtilidadesVarias::textofecha($fecha_inicial).' al '.UtilidadesVarias::textofecha($fecha_final);

//si viene tipo

if (isset($model['tipo'])) { 
  
  $tipo = $model['tipo'];

  if($tipo == 1){
  
    //FECHAS, ITEMS 
    $opt = 2;
    
    $modelo_item_i =IItem::model()->findByPk($model['item_inicial']);
    $i_i = $modelo_item_i->Id_Item;
    $d_i_i = $modelo_item_i->Id_Item.' - '.$modelo_item_i->Referencia.' - '.$modelo_item_i->Descripcion;
    
    $modelo_item_f =IItem::model()->findByPk($model['item_final']); 
    $i_f = $modelo_item_f->Id_Item;
    $d_i_f = $modelo_item_f->Id_Item.' - '.$modelo_item_f->Referencia.' - '.$modelo_item_f->Descripcion;
    
    $linea = "";

    $criterio = 'Fecha : '.UtilidadesVarias::textofecha($fecha_inicial).' al '.UtilidadesVarias::textofecha($fecha_final).' / Item: '.$d_i_i.' al '.$d_i_f.'.';  
  
  }
  
  if($tipo == 2){
    
    //FECHAS, LINEAS
    $opt = 3;
    $i_i = "";
    $i_f = "";
    $l = implode(",", $model['linea']);
    $linea = $l;

    $cad_lineas = '';

    foreach ($model['linea'] as $id_linea) {
      $modelo_linea = ILinea::model()->findByPk($id_linea);
      $cad_lineas .=  $modelo_linea->Descripcion.', ';      
    }

    $cad_lineas = substr($cad_lineas, 0, -2);

    $criterio = 'Fecha : '.UtilidadesVarias::textofecha($fecha_inicial).' al '.UtilidadesVarias::textofecha($fecha_final).' / Línea(s): '.$cad_lineas.'.';
  
  }

}

$query= "
    EXEC P_CF_INV_CONS_KR_ITEM
    @FECHA1 = N'".$FechaM1."',
    @FECHA2 = N'".$FechaM2."',
    @OPT = N'".$opt."',
    @IT1 = N'".$i_i."',
    @IT2 = N'".$i_f."',
    @LINEA = N'".$linea."'   
";

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_info = array();

$co = 1;

foreach ($query1 as $reg) {

  $aprobacion = $reg ['APROBACION'];
  $referencia = $reg ['REFERENCIA'];
  $tercero = $reg ['TERCERO'];
  $tipo_docto = $reg ['Id_Docto'];
  $documento = $reg ['DOCUMENTO'];
  $linea = $reg ['LINEA'];
  $item = $reg ['ITEM'];
  $descripcion = $reg ['DESCRIPCION'];
  $entrada = $reg ['ENTRADA'];
  $salida = $reg ['SALIDA'];
  $precio = $reg ['PRECIO'];
  $bodega_entrada = $reg ['BODEGA_ENTRADA'];
  $bodega_salida = $reg ['BODEGA_SALIDA'];

  $array_info[$linea][$item.'|'.$descripcion][$referencia.'|'.$documento.'|'.$co] = array('aprobacion' => $aprobacion, 'referencia' => $referencia, 'tercero' => $tercero, 'tipo_docto' => $tipo_docto, 'documento' => $documento, 'entrada' => $entrada, 'salida' => $salida, 'precio' => $precio, 'bodega_entrada' => $bodega_entrada, 'bodega_salida' => $bodega_salida);

  $co++;

}

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

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setFechaIniciaL($fecha_inicial){
    $this->fecha_inicial = $fecha_inicial;
  }

  function setCriterio($criterio){
    $this->criterio = $criterio;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){
    $this->SetFont('Arial','B',12);
    $this->Cell(100,5,'Kardex de inventario',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(95,5,utf8_decode($this->fecha_actual),0,0,'R');

    $this->Ln();
    $this->Ln();
    $this->SetFont('Arial','',7);
    $this->MultiCell(195,5,utf8_decode($this->criterio),0,'J');
    $this->Ln();

    //linea superior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->Ln();  
    
    //cabecera de tabla
    $this->SetFont('Arial','B',6);

    $this->Cell(60,2,utf8_decode('Línea / Item / Fecha - hora aprobación'),0,0,'L');
    $this->Cell(20,2,utf8_decode('Documento'),0,0,'L');
    $this->Cell(30,2,utf8_decode('Bod. entrada'),0,0,'L'); 
    $this->Cell(18,2,utf8_decode('Cant. entrada'),0,0,'L');
    $this->Cell(30,2,utf8_decode('Bod. salida'),0,0,'L');
    $this->Cell(18,2,utf8_decode('Cant. salida'),0,0,'L'); 
    $this->Cell(19,2,utf8_decode('Cant. saldo'),0,0,'L');

    $this->Ln(3);   
    
    //linea inferior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    
    $this->Ln();
    $this->Ln();

  }

  function Tabla(){

    $data = $this->data;

    $this->Ln();
    //$this->Ln();

    foreach ($data as $lin => $ir) {

      $this->SetFont('Arial','B',7);
      $this->Cell(80,3,utf8_decode($lin),0,0,'L');
      $this->Ln();
      $this->Ln();

      foreach ($ir as $item_referencia => $regs) {

        $info_item = explode("|", $item_referencia);
        $it = $info_item[0]; 
        $rf = $info_item[1];

        $fi = $this->fecha_inicial;

        $cantidad = UtilidadesReportes::kardexcantidadxitem($it, $fi);

        $this->SetFont('Arial','B',6);
        $this->Cell(155,3,utf8_decode($it.' - '.$rf),0,0,'L');
        $this->Cell(40,3,number_format($cantidad,0,".",","),0,0,'R');
        $this->Ln();

        $e = 0; 
        $s = 0;

        foreach ($regs as $reg) {

          $tercero = $reg['tercero'];
          $documento = $reg['documento'];
          $tipo_docto = $reg['tipo_docto'];

          if($tipo_docto == Yii::app()->params->ent || $tipo_docto == Yii::app()->params->aje || $tipo_docto == Yii::app()->params->dev){
            //ENTRADAS - AJUSTES POR ENTRADA - DEVOLUCIÓN
            $cantidad = $cantidad + $reg['entrada'];
            $e = $e + $reg['entrada']; 
          }

          if($tipo_docto == Yii::app()->params->sal || $tipo_docto == Yii::app()->params->ajs || $tipo_docto == Yii::app()->params->sad){
            //SALIDA - AJUSTE POR SALIDA - SALIDA DE DOTACIÓN
            $cantidad = $cantidad - $reg['salida'];
            $s = $s + $reg['salida']; 
          }

          $aprobacion = UtilidadesVarias::textofechahora($reg['aprobacion']);
          $referencia = $reg['referencia'];
          $bodega_entrada = $reg['bodega_entrada'];
          $entrada = $reg['entrada'];
          $bodega_salida = $reg['bodega_salida'];
          $salida = $reg['salida'];
          $precio = number_format(($reg['precio']),2,".",",");

          $this->SetFont('Arial','',6);
          $this->Cell(60,3,utf8_decode($aprobacion),0,0,'L');
          $this->Cell(20,3,utf8_decode($documento),0,0,'L');
          $this->Cell(30,3,substr(utf8_decode($bodega_entrada),0, 20),0,0,'L');
          $this->Cell(18,3,number_format($entrada,0,".",","),0,0,'R');
          $this->Cell(30,3,substr(utf8_decode($bodega_salida),0, 20),0,0,'L');
          $this->Cell(18,3,number_format($salida,0,".",","),0,0,'R');
          $this->Cell(19,3,number_format($cantidad,0,".",","),0,0,'R');
          $this->Ln();

        }

        $this->SetDrawColor(0,0,0);
        $this->Cell(195,1,'','T');                            
        $this->Ln(); 

        $this->SetFont('Arial','B',6);
        $this->Cell(60,2,'',0,0,'L');
        $this->Cell(20,2,'',0,0,'L');
        $this->Cell(30,2,'',0,0,'L');
        $this->Cell(18,2,number_format($e,0,".",","),0,0,'R');
        $this->Cell(30,2,'',0,0,'L');
        $this->Cell(18,2,number_format($s,0,".",","),0,0,'R');
        $this->Cell(19,2,number_format($cantidad,0,".",","),0,0,'R');
        $this->Ln();

        $this->SetDrawColor(0,0,0);
        $this->Cell(195,2,'','T');                            
        $this->Ln(); 

        $this->Ln();

      }
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
$pdf->setFechaActual($fecha_act); 
$pdf->setFechaIniciaL($FechaM1); 
$pdf->setCriterio($criterio);
$pdf->setData($array_info);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Kardex_inventario_'.date('Y_m_d_H_i_s').'.pdf');

?>
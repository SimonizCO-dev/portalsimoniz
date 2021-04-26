<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

$logo_simoniz = Yii::app()->getBaseUrl(true).'/images/logo_header_simoniz.jpg';

//se reciben los parametros para el reporte

$consecutivo = $model['consecutivo'];



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

$query ="
  EXEC P_PR_COM_CONS_VT_EMPLEADO1
  @FACTURA = N'".$consecutivo."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_v = array();
$array_v['detalles'] = array();

if(!empty($query1)){

  foreach ($query1 as $reg) {
    $Fecha_Documento = $reg['Fecha_Documento'];
    $Fecha_Emision = $reg['Fecha_Emision'];
    $Facturado = $reg['Facturado'];
    $Pedido = $reg['Pedido'];
    $Remision = $reg['Remision'];
    $Factura = $reg['Factura'];
    $Nit = trim($reg['Nit']);
    $Nombre = $reg['Nombre'];
    $Item = $reg['Item'];
    $Descripcion = $reg['Descripcion'];
    $Referencia = $reg['Referencia'];
    $Cant_Pedida = $reg['Cant_Pedida'];
    $Cant_Facturada = $reg['Cant_Facturada'];
    $Precio_Unitario = $reg['Precio_Unitario'];
    $Iva = $reg['Iva'];
    $Valor_Total = $reg['Valor_Total'];


    $array_v['info']['factura'] = $Factura;
    $array_v['info']['fecha_factura'] = $Fecha_Documento;
    $array_v['info']['facturado'] = $Facturado;


    if(!array_key_exists($Nit, $array_v['detalles'])) { 
      $array_v['detalles'][$Nit] = array();
      $array_v['detalles'][$Nit]['nombre'] = $Nombre;
      $array_v['detalles'][$Nit]['info'][$Item] = array(
        'referencia' => $Referencia,
        'descripcion' => $Descripcion,
        'cantidad_pedida' => $Cant_Pedida,
        'cantidad_facturada' => $Cant_Facturada,
        'precio_unitario' => $Precio_Unitario,
        'iva' => $Iva,
        'valor_total' => $Valor_Total,
      );
    }else{
      
      if(!array_key_exists($Item, $array_v['detalles'][$Nit]['info'])) {
        $array_v['detalles'][$Nit]['info'][$Item] = array(
          'referencia' => $Referencia,
          'descripcion' => $Descripcion,
          'cantidad_pedida' => $Cant_Pedida,
          'cantidad_facturada' => $Cant_Facturada,
          'precio_unitario' => $Precio_Unitario,
          'iva' => $Iva,
          'valor_total' => $Valor_Total,
        );  
      }  
    }
  }
}else{
  $array_v['info']['factura'] = '';
  $array_v['info']['fecha_factura'] = '';
  $array_v['info']['facturado'] = '';
}

$h_factura = $array_v['info']['factura'];
$h_fecha_factura = $array_v['info']['fecha_factura'];
$h_facturado = $array_v['info']['facturado'];


/*fin configuración array de datos*/

  //PDF

  class PDF extends FPDF{

    function setLogoSimoniz($logo_simoniz){
      $this->logo_simoniz = $logo_simoniz;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setFactura($factura){
      $this->factura = $factura;
    }

    function setFechaFactura($fecha_factura){
      $this->fecha_factura = $fecha_factura;
    }

    function setFacturado($facturado){
      $this->facturado = $facturado;
    }

    function setData($data){
      $this->data = $data;
    }

    function Header(){

      $this->Image($this->logo_simoniz, 11, 10, 60, 20);
      $this->Ln(20);
      $this->SetFont('Arial','B',12);
      $this->Cell(100,5,'FACTURA DETALLADA POR LINEA',0,0,'L');
      $this->SetFont('Arial','',9);
      $this->Cell(95,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(195,5,utf8_decode('FACTURA: '.$this->factura),0,0,'L');
      $this->Ln();
      $this->Cell(195,5,utf8_decode('FECHA DE FACTURA: '.$this->fecha_factura),0,0,'L');
      $this->Ln();
      $this->Cell(195,5,utf8_decode('FACTURADO: '.$this->facturado),0,0,'L');
      $this->Ln();
      $this->Ln();

      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  

      $this->SetFont('Arial','B',6);
      $this->Cell(12,5,utf8_decode('ITEM'),0,0,'L');
      $this->Cell(20,5,utf8_decode('REFERENCIA'),0,0,'L');
      $this->Cell(56,5,utf8_decode('DESCRIPCIÓN'),0,0,'L');
      $this->Cell(13,5,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(13,5,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(27,5,utf8_decode('PRECIO'),0,0,'R');
      $this->Cell(27,5,utf8_decode('IVA'),0,0,'R');
      $this->Cell(27,5,utf8_decode('VALOR'),0,0,'R');

      $this->Ln(2);

      $this->SetFont('Arial','B',6);
      $this->Cell(12,5,utf8_decode(''),0,0,'L');
      $this->Cell(20,5,utf8_decode(''),0,0,'L');
      $this->Cell(56,5,utf8_decode(''),0,0,'L');
      $this->Cell(13,5,utf8_decode('PEDIDA'),0,0,'R');
      $this->Cell(13,5,utf8_decode('FACTURA'),0,0,'R');
      $this->Cell(27,5,utf8_decode('UNITARIO'),0,0,'R');
      $this->Cell(27,5,utf8_decode(''),0,0,'R');
      $this->Cell(27,5,utf8_decode('TOTAL'),0,0,'R');

      $this->Ln();
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);

      $this->Ln();
      
    }

    function Tabla(){

      $array_d = $this->data;

      $s_cp = 0;
      $s_cf = 0;
      $s_pu = 0;
      $s_i = 0;
      $s_vt = 0;

      $st_cp = 0;
      $st_cf = 0;
      $st_pu = 0;
      $st_i = 0;
      $st_vt = 0;

      foreach ($array_d as $nit => $datos) {

        $this->SetFont('Arial','B',7);
        $this->Cell(12,5,utf8_decode($nit.' - '.$datos['nombre']),0,0,'L');
        $this->Ln();

        foreach ($datos['info'] as $item => $reg) {

          $this->SetFont('Arial','',6);
          $this->Cell(12,3,utf8_decode($item),0,0,'L');
          $this->Cell(20,3,substr(utf8_decode($reg['referencia']),0, 20),0,0,'L');
          $this->Cell(56,3,substr(utf8_decode($reg['descripcion']), 0, 40),0,0,'L');
          $this->Cell(13,3,$reg['cantidad_pedida'],0,0,'R');
          $this->Cell(13,3,$reg['cantidad_facturada'],0,0,'R');
          $this->Cell(27,3,number_format(($reg['precio_unitario']),0,".",","),0,0,'R');
          $this->Cell(27,3,number_format(($reg['iva']),0,".",","),0,0,'R');
          $this->Cell(27,3,number_format(($reg['valor_total']),0,".",","),0,0,'R');
          $this->Ln();

          $s_cp = $s_cp + $reg['cantidad_pedida'];
          $s_cf = $s_cf + $reg['cantidad_facturada'];
          $s_pu = $s_pu + $reg['precio_unitario'];
          $s_i = $s_i + $reg['iva'];
          $s_vt = $s_vt + $reg['valor_total'];

          $st_cp = $st_cp + $reg['cantidad_pedida'];
          $st_cf = $st_cf + $reg['cantidad_facturada'];
          $st_pu = $st_pu + $reg['precio_unitario'];
          $st_i = $st_i + $reg['iva'];
          $st_vt = $st_vt + $reg['valor_total'];

        }

        //linea superior a la cabecera de la tabla
        $this->SetDrawColor(0,0,0);
        $this->Cell(195,1,'','T');
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->Ln(1);  

        $this->SetFont('Arial','B',6);
        $this->Cell(88,1,utf8_decode('TOTAL'),0,0,'R');
        $this->Cell(13,1,number_format(($s_cp),0,".",","),0,0,'R');
        $this->Cell(13,1,number_format(($s_cf),0,".",","),0,0,'R');
        $this->Cell(27,1,number_format(($s_pu),0,".",","),0,0,'R');
        $this->Cell(27,1,number_format(($s_i),0,".",","),0,0,'R');
        $this->Cell(27,1,number_format(($s_vt),0,".",","),0,0,'R');
        $this->Ln(2);

        $s_cp = 0;
        $s_cf = 0;
        $s_pu = 0;
        $s_i = 0;
        $s_vt = 0;
        
        //linea inferior a la cabecera de la tabla
        $this->SetDrawColor(0,0,0);
        $this->Cell(195,1,'','T');
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->Ln();

      }

      //TOTAL GENERAL

      $this->SetFont('Arial','B',6);
      $this->Cell(88,5,utf8_decode('TOTAL GENERAL'),0,0,'R');
      $this->Cell(13,5,number_format(($st_cp),0,".",","),0,0,'R');
      $this->Cell(13,5,number_format(($st_cf),0,".",","),0,0,'R');
      $this->Cell(27,5,number_format(($st_pu),0,".",","),0,0,'R');
      $this->Cell(27,5,number_format(($st_i),0,".",","),0,0,'R');
      $this->Cell(27,5,number_format(($st_vt),0,".",","),0,0,'R');
      $this->Ln(5);

      $st_cp = 0;
      $st_cf = 0;
      $st_pu = 0;
      $st_i = 0;
      $st_vt = 0;

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
  $pdf->setLogoSimoniz($logo_simoniz);
  $pdf->setFechaActual($fecha_act);
  $pdf->setFactura($h_factura);
  $pdf->setFechaFactura($h_fecha_factura);
  $pdf->setFacturado($h_facturado);
  $pdf->setData($array_v['detalles']);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Venta_empleado_'.date('Y_m_d_H_i_s').'.pdf');

?>
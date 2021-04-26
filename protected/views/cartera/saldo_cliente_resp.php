<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$ruta = $model['ruta'];
$firma = $model['firma'];

$logo_pansell = Yii::app()->getBaseUrl(true).'/images/logo_header.jpg';
$logo_pse_pansell = Yii::app()->getBaseUrl(true).'/images/pse_pansell.jpg';

set_time_limit(0);

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

$query ="
  EXEC P_PR_FIN_CT_CIRCULAR_CLIENTE
  @RUTA = N'".$ruta."'
";

UtilidadesVarias::log($query);

$array_cli = array();

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_cli = array();

foreach ($query1 as $reg) {
  $nit = $reg['NIT'];
  $cliente = $reg['CLIENTE'];
  $direccion = $reg['DIRECCION'];
  $ciudad = $reg['CIUDAD'];
  $telefono = $reg['TELEFONO'];
  $doc = $reg['DOCUMENTO'];
  $fecha_doc = substr($reg['FECHA_DOCTO'], 0, 4).'/'.substr($reg['FECHA_DOCTO'], 4, 2).'/'.substr($reg['FECHA_DOCTO'], 6, 2);
  $valor_inicial = $reg['DEBITO'];
  $saldo = $reg['TOTAL'];
  $pie_pag_1 = $reg['PIE_PAGINA1'];
  $pie_pag_2 = $reg['PIE_PAGINA2'];

  if(!array_key_exists($nit, $array_cli)) {
    $array_cli[$nit] = array();
    $array_cli[$nit]['info'] = array();
    $array_cli[$nit]['info']['cliente'] = $cliente;
    $array_cli[$nit]['info']['direccion'] = $direccion;
    $array_cli[$nit]['info']['ciudad'] = $ciudad;
    $array_cli[$nit]['info']['telefono'] = $telefono;
    $array_cli[$nit]['documentos'][$doc] = array(
      'fecha_doc' => $fecha_doc,
      'valor_inicial' => $valor_inicial,
      'saldo' => $saldo
    
    );
  }else{
    if(!array_key_exists($doc, $array_cli[$nit]['documentos'])) {
      $array_cli[$nit]['documentos'][$doc] = array(
        'fecha_doc' => $fecha_doc,
        'valor_inicial' => $valor_inicial,
        'saldo' => $saldo
      
      );   
    }
  }
}

$PiePag1 = $pie_pag_1;
$PiePag2 = $pie_pag_2;

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  } 

  function setRuta($ruta){
    $this->ruta = $ruta;
  }

  function setFirma($firma){
    $this->firma = $firma;
  }

  function setData($data){
    $this->data = $data;
  }

  function setLogoPansell($logo_pansell){
    $this->logo_pansell = $logo_pansell;
  }

  function setLogoPsePansell($logo_pse_pansell){
    $this->logo_pse_pansell = $logo_pse_pansell;
  }

  function setPiePag1($PiePag1){
    $this->PiePag1 = $PiePag1;
  }

  function setPiePag2($PiePag2){
    $this->PiePag2 = $PiePag2;
  }

  function Header(){
    $this->Image($this->logo_pansell, 11, 10, 150, 30);
    $this->Ln(30);
  }

  function Tabla(){

    $array_cli = $this->data;

    $i = 1;

    $array_acta = array();

    foreach ($array_cli as $cliente => $var_a) {

      $sum = 0;

      if($i > 1){
        $this->AddPage();   
      }

      $cli = $var_a['info']['cliente']; 
      $nit = $cliente;
      $direccion = $var_a['info']['direccion'];
      $ciudad = $var_a['info']['ciudad'];
      $telefono = $var_a['info']['telefono'];
      $documentos = $var_a['documentos'];

      $array_acta[] = array('carta' => 'Carta '.$i, 'cliente' => $cli);
    
      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('Bogotá D.C, '.$this->fecha_actual),0,0,'L');
      $this->Cell(85,5,'Carta '.$i.' / R'.$this->ruta,0,0,'R');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','B',10);
      $this->Cell(100,5,utf8_decode('Señor(a)'),0,0,'L');
      $this->Ln();
      $this->Cell(100,5,utf8_decode($cli),0,0,'L');
      $this->Ln();
      $this->Cell(100,5,$nit,0,0,'L');
      $this->Ln();
      $this->Cell(100,5,utf8_decode($direccion),0,0,'L');
      $this->Ln();
      $this->Cell(100,5,utf8_decode($ciudad),0,0,'L');
      $this->Ln();
      $this->Cell(100,5,utf8_decode($telefono),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('Respetado Señor(a): '),0,0,'L');
      $this->Ln();
      $this->MultiCell(185,3,utf8_decode('La presente tiene como fin verificar el saldo que a la fecha figura en su cuenta con nuestra empresa SIMONIZ S.A :'),0,'J');
      $this->Ln();

      //table header
      $this->SetFont('Arial','B',9);
      $this->SetTextColor(255);
      $this->SetFillColor(160);
      $this->Cell(15,5,'',0,0);
      $this->Cell(40,5,utf8_decode('N°. FACTURA'),1,0,'C',TRUE);
      $this->Cell(40,5,utf8_decode('FECHA FACTURA'),1,0,'C',TRUE);
      $this->Cell(40,5,utf8_decode('VALOR INICIAL'),1,0,'C',TRUE);
      $this->Cell(40,5,utf8_decode('SALDO'),1,0,'C',TRUE);
      $this->Ln();

      $this->SetFont('Arial','',9);
      $this->SetFillColor(255,255,255);
      $this->SetDrawColor(0,0,0);
      $this->SetTextColor(0);

      foreach ($documentos as $docs => $var_b) {
        $this->Cell(15,5,'',0,0);
        $this->Cell(40,5,$docs,1,0,'L');
        $this->Cell(40,5,$var_b['fecha_doc'],1,0,'L');
        $this->Cell(40,5,number_format(($var_b['valor_inicial']),0,".",","),1,0,'R');
        $this->Cell(40,5,number_format(($var_b['saldo']),0,".",","),1,0,'R');
        $this->Ln();

        $sum = $sum + $var_b['saldo'];
      }

      //table footer
      $this->SetFont('Arial','B',9);
      $this->Cell(15,5,'',0,0);
      $this->Cell(120,5,utf8_decode('TOTAL'),1,0,'R');
      $this->Cell(40,5,number_format(($sum),0,".",","),1,0,'R');
      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','B',10);
      $this->Cell(100,5,utf8_decode('El saldo a nuestro cargo es de $ '.number_format(($sum),0,".",",")),0,0,'L');
      $this->SetFont('Arial','',10);
      $this->Cell(86,5,utf8_decode('Correcto _____            Incorrecto _____'),0,0,'R');
      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('Explicación de la diferencia: '),0,0,'L');
      $this->Ln();
      $this->Cell(185,5,utf8_decode('______________________________________________________________________________________________'),0,0,'L');
      $this->Ln();
      $this->Cell(185,5,utf8_decode('______________________________________________________________________________________________'),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->Cell(100,5,utf8_decode('Acepto el saldo aquí relacionado, en constancia firmo: '),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(85,5,utf8_decode('_____________________________________'),0,0,'L');
      $this->SetFont('Arial','B',10);
      $this->Cell(19,5,utf8_decode('C.C o NIT: '),0,0,'L');
      $this->SetFont('Arial','',10);
      $this->Cell(60,5,utf8_decode('______________________________'),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('El Encargado de la verificacion es el señor(a): '.$this->firma),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','B',9);
      $this->MultiCell(185,3,utf8_decode('RECUERDE QUE EL RECIBO DE CAJA ES EL ÚNICO DOCUMENTO EVIDENCIA DEL PAGO DE NUESTRAS FACTURAS, ESTE NO DEBE PRESENTAR ENMENDADURAS, TACHONES O CORRECCIONES, DE SER ASÍ EXIJA EL CAMBIO DEL RECIBO.'),0,'J');
      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('Atentamente,'),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(80,5,utf8_decode('______________________________'),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','B',10);
      $this->Cell(12,3,utf8_decode($this->firma),0,0,'L');
      $this->Ln();
      $this->Cell(12,3,utf8_decode('Cartera tradicional'),0,0,'L');

      $i++;
    
    }

    $this->AddPage();

    $this->SetFont('Arial','',10);
    $this->Cell(100,5,utf8_decode('Bogotá D.C, '.$this->fecha_actual),0,0,'L');
    $this->Ln();
    $this->Ln();
    $this->SetFont('Arial','',10);
    $this->Ln();
    $cant_c = $i - 1;
    $this->MultiCell(185,3,utf8_decode('Con la presente hago entrega de '.$cant_c.' cartas de circularización con fecha de retiro ('.$this->fecha_actual.'), relacionadas a continuación: '),0,'J');
    $this->Ln();
    $this->Ln();

    $i = 1;

    foreach ($array_acta as $r => $reg) {
      
      $carta = $reg['carta'];
      $cliente = $reg['cliente'];
      $this->SetFont('Arial','',9);
      $this->Cell(185,3,utf8_decode($i.'. '.$cliente.' - '.$carta),0,'J');
      $this->Ln();

      $i++;

    }

    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->Cell(92,5,utf8_decode('______________________________'),0,0,'L');
    $this->Cell(92,5,utf8_decode('______________________________'),0,0,'L');

    $this->Ln();
    $this->SetFont('Arial','B',10);
    $this->Cell(92,3,utf8_decode($this->firma),0,0,'L');
    $this->Cell(92,3,utf8_decode('Recibido'),0,0,'L');
    $this->Ln();
    $this->Cell(92,3,utf8_decode('Cartera tradicional'),0,0,'L');
    $this->Cell(92,3,utf8_decode(''),0,0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',10);
    $this->Cell(92,3,utf8_decode('Tel: 4220612 ext. 1014'),0,0,'L');
;


  }//fin tabla

  function Footer()
  {
      $this->SetY(-50);
      $this->Image($this->logo_pse_pansell, 52, null, 100);
      $this->Ln();
      $this->SetFont('Arial','I',7);
      $this->Cell(185,3,utf8_decode($this->PiePag1),0,0,'C');
      $this->Ln();
      $this->Cell(185,3,utf8_decode($this->PiePag2),0,0,'C');
  }
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act);
$pdf->setRuta($ruta);
$pdf->setFirma($firma);
$pdf->setData($array_cli);
$pdf->setLogoPansell($logo_pansell);
$pdf->setLogoPsePansell($logo_pse_pansell);
$pdf->setPiePag1($PiePag1);
$pdf->setPiePag2($PiePag2);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 55);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Circulares_saldo_cliente_'.date('Y_m_d_H_i_s').'.pdf');

?>

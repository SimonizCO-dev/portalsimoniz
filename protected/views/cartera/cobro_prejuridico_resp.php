<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$ruta_inicial = $model['ruta_inicial'];
$ruta_final = $model['ruta_final'];
$estado = $model['estado'];
$valor = $model['valor'];
$dias = $model['dias'];
$firma = $model['firma'];

$logo_pansell = Yii::app()->getBaseUrl(true).'/images/logo_header.jpg';
$logo_pse_pansell = Yii::app()->getBaseUrl(true).'/images/pse_pansell.jpg';

//se obtiene la cadena de la fecha actual
$diatxt=date('l');
$dianro=date('d');
$mestxt=date('F');
$anionro=date('Y');

//fecha plazo = fecha_actual + 8 días

$fecha = date('Y-m-j');
$fec_pla = strtotime ( '+8 day' ,strtotime($fecha));
$dia_txt_fec_pla = date ( 'l' ,$fec_pla);
$dia_nro_fec_pla = date ( 'd' ,$fec_pla);
$mes_txt_fec_pla = date ( 'F' ,$fec_pla);
$anio_nro_fec_pla = date ( 'Y' ,$fec_pla);

// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

$diaesp=str_replace($ding, $desp, $diatxt);
$mesesp=str_replace($ming, $mesp, $mestxt);

$fecha_act= $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;

$dia_pla_esp=str_replace($ding, $desp, $dia_txt_fec_pla);
$mes_pla_esp=str_replace($ming, $mesp, $mes_txt_fec_pla);

$fecha_plazo= $dia_pla_esp.", ".$dia_nro_fec_pla." de ".$mes_pla_esp." de ".$anio_nro_fec_pla;

$query ="
  SET NOCOUNT ON
  EXEC P_PR_FIN_CT_CIRCULAR_PREJURIDICO
  @RUTA1 = N'".$ruta_inicial."',
  @RUTA2 = N'".$ruta_final."',
  @DIAS = ".$dias.",
  @ESTADO = N'".$estado."',
  @VALOR = N'".$valor."'
";

UtilidadesVarias::log($query);

$array_cli = array();

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_cli = array();
$pie_pag_1 = '';
$pie_pag_2 = '';
$ruta = '';

foreach ($query1 as $reg) {
  
  $nit = $reg['Cliente_Nit'];
  $cliente = $reg['Cliente_RZ'];
  $direccion = $reg['Cliente_Direccion'];
  $ciudad = $reg['Cliente_Ciudad'];
  $telefono = $reg['Cliente_Telefono'];
  $doc = $reg['documento'];
  $fecha_doc = substr($reg['Fecha_Docto'], 0, 4).'/'.substr($reg['Fecha_Docto'], 4, 2).'/'.substr($reg['Fecha_Docto'], 6, 2);
  $valor_inicial = $reg['T_Db'];
  $saldo = $reg['Total'];
  $ruta = $reg['Ruta'];
  $asesor = $reg['Vendedor'];
  $pie_pag_1 = $reg['PIE_PAGINA1'];
  $pie_pag_2 = $reg['PIE_PAGINA2'];

  if(!array_key_exists($nit, $array_cli)) {
    $array_cli[$nit] = array();
    $array_cli[$nit]['info'] = array();
    $array_cli[$nit]['info']['cliente'] = $cliente;
    $array_cli[$nit]['info']['direccion'] = $direccion;
    $array_cli[$nit]['info']['ciudad'] = $ciudad;
    $array_cli[$nit]['info']['telefono'] = $telefono;
    $array_cli[$nit]['info']['ruta'] = $ruta;
    $array_cli[$nit]['info']['asesor'] = $asesor;
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

  function setFechaPlazo($fecha_plazo){
    $this->fecha_plazo = $fecha_plazo;
  }

  function setFirma($firma){
    $this->firma = $firma;
  }

  function setRuta($ruta){
    $this->ruta = $ruta;
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
    $this->Ln(28);

  }

  function Tabla(){

    $array_cli = $this->data;

    $i = 1;

    $array_acta = array();

    if(!empty($array_cli)){

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
        $ruta = $var_a['info']['ruta'];
        $asesor = $var_a['info']['asesor'];
        $documentos = $var_a['documentos'];

        $array_acta[] = array('carta' => 'Carta '.$i, 'cliente' => $cli);
      
        $this->SetFont('Arial','',10);
        $this->Cell(100,5,utf8_decode('Bogotá D.C, '.$this->fecha_actual),0,0,'L');
        $this->Cell(85,5,'Carta '.$i.' / R'.$ruta,0,0,'R');
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
        $this->SetFont('Arial','B',10);
        $this->Cell(100,5,utf8_decode('Referencia: COBRO PREJURÍDICO'),0,0,'L');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',10);
        $this->Cell(100,5,utf8_decode('Respetado Señor(a): '),0,0,'L');
        $this->Ln();
        $this->MultiCell(185,3,utf8_decode('En diversas oportunidades nos hemos comunicado con ustedes, por intermedio de su Asesor Comercial, '.$asesor.', con el fin de dar solución inmediata a la cancelación de la(s) factura(s) relacionadas a continuación, sin que a la fecha de hoy haya sido posible que realicen el pago y den cumplimiento a los compromisos por ustedes pactados.'),0,'J');
        $this->Ln();

        //table header
        $this->SetFont('Arial','B',9);
        $this->SetTextColor(255);
        $this->SetFillColor(160);
        $this->Cell(15,5,'',0,0);
        $this->Cell(40,5,utf8_decode('N°. Factura'),1,0,'C',TRUE);
        $this->Cell(40,5,utf8_decode('Fecha factura'),1,0,'C',TRUE);
        $this->Cell(40,5,utf8_decode('Valor inicial'),1,0,'C',TRUE);
        $this->Cell(40,5,utf8_decode('Saldo'),1,0,'C',TRUE);
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

        $this->SetFont('Arial','',10);
        $this->MultiCell(185,3,utf8_decode('Es por eso que si antes del día '.$this->fecha_plazo.' (8 días del plazo) del año en curso no recibimos el pago total de la(s) factura(s) en mención, entenderemos que no hay ninguna intención de pago y procederemos de manera inmediata a enviar los documentos firmados por ustedes a nuestros abogados, con el fin de que inicie el respectivo cobro jurídico, no sin antes notificar que todos los gastos procesales que demanden serán cargados a su cuenta. De igual manera procederemos a reportarlos a las centrales de riesgos como Data Crédito, Cifin y Asobancaria.'),0,'J');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',10);
        $this->MultiCell(185,3,utf8_decode('No es de nuestra intención causarles ningún perjuicio, evítese problemas cancelando en la fecha indicada.'),0,'J');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',10);
        $this->MultiCell(185,3,utf8_decode('Cualquier inquietud y envió de soporte de pago, por favor comunicarse con '.$this->firma.', encargado(a) del departamento de cartera tradicional.'),0,'J');
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();
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
    
    }    

    

    $this->SetFont('Arial','',10);
    $this->Cell(100,5,utf8_decode('Bogotá D.C, '.$this->fecha_actual),0,0,'L');
    $this->Ln();
    $this->Ln();
    $this->SetFont('Arial','',10);
    $this->Ln();
    $cant_c = $i - 1;
    $this->MultiCell(185,3,utf8_decode('Con la presente hago entrega de '.$cant_c.' cartas de circulación con fecha de retiro ('.$this->fecha_actual.'), relacionadas a continuación: '),0,'J');
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


  }//fin tabla

  function Footer()
  {
      $this->SetY(-35);
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
$pdf->setFechaPlazo($fecha_plazo);
$pdf->setFirma($firma);
$pdf->setRuta($ruta);
$pdf->setData($array_cli);
$pdf->setLogoPansell($logo_pansell);
$pdf->setLogoPsePansell($logo_pse_pansell);
$pdf->setPiePag1($PiePag1);
$pdf->setPiePag2($PiePag2);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 40);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Circulares_cobro_prejuridico_'.date('Y_m_d_H_i_s').'.pdf');

?>

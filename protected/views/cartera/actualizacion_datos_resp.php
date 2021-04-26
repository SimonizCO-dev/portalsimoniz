<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$ruta = $model['ruta'];
$estado = $model['estado'];

$logo_pansell = Yii::app()->getBaseUrl(true).'/images/logo_header.jpg';

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
  EXEC P_PR_FIN_CT_CIRCULAR_ACTUALIZACION
  @RUTA = N'".$ruta."',
  @ESTADO = N'".$estado."'
";

UtilidadesVarias::log($query);

$array_cli = array();

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_cli = array();

foreach ($query1 as $reg) {
  $nit = $reg['NIT'];
  $cliente = $reg['CLIENTE'];
  $sucursal = $reg['SUC'];
  $direccion = $reg['DIRECCION'];
  $ciudad = $reg['CIUDAD'];
  $telefono = $reg['TELEFONO'];
  $celular = $reg['CELULAR'];
  $email = $reg['EMAIL'];
  $pie_pag_1 = $reg['PIE_PAGINA1'];
  $pie_pag_2 = $reg['PIE_PAGINA2'];

  $llave = $nit.'|'.$sucursal;

  if(!array_key_exists($llave, $array_cli)) {
    $array_cli[$llave] = array();
    $array_cli[$llave]['info'] = array();
    $array_cli[$llave]['info']['nit'] = $nit;
    $array_cli[$llave]['info']['cliente'] = $cliente;
    $array_cli[$llave]['info']['direccion'] = $direccion;
    $array_cli[$llave]['info']['sucursal'] = $sucursal;
    $array_cli[$llave]['info']['ciudad'] = $ciudad;
    $array_cli[$llave]['info']['telefono'] = $telefono;
    $array_cli[$llave]['info']['celular'] = $celular;
    $array_cli[$llave]['info']['email'] = $email;
  }
}

$PiePag1 = $pie_pag_1;
$PiePag2 = $pie_pag_2;

$num_reg = count($array_cli);

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
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
      $nit = $var_a['info']['nit'];
      $direccion = $var_a['info']['direccion'];
      $sucursal = $var_a['info']['sucursal'];
      $ciudad = $var_a['info']['ciudad'];
      $telefono = $var_a['info']['telefono'];
      $celular = $var_a['info']['celular'];
      $email = $var_a['info']['email'];

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
      $this->Cell(100,5,utf8_decode('SUCURSAL '.$sucursal),0,0,'L');
      $this->Ln();
      $this->Cell(100,5,utf8_decode($direccion),0,0,'L');
      $this->Ln();
      $this->Cell(100,5,utf8_decode($ciudad),0,0,'L');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('Respetado Señor(a): '),0,0,'L');
      $this->Ln();
      $this->MultiCell(185,3,utf8_decode('La presente tiene como fin verificar la información que figura en nuestro sistema, si es correcta favor confirmar, de lo contrario actualizar en la columna # 2 :'),0,'J');
      $this->Ln();

      //table header
      $this->SetFont('Arial','B',9);
      $this->SetTextColor(255);
      $this->SetFillColor(160);
      $this->Cell(10,5,'',0,0);
      $this->Cell(85,5,utf8_decode('(1) INFORMACIÓN REGISTRADA ACTUALMENTE'),1,0,'C',TRUE);
      $this->Cell(85,5,utf8_decode('(2) INFORMACIÓN PARA ACTUALIZAR'),1,0,'C',TRUE);
      $this->Ln();

      $this->SetFillColor(255,255,255);
      $this->SetDrawColor(0,0,0);
      $this->SetTextColor(0);

      $this->Cell(10,5,'',0,0);
      $this->SetFont('Arial','B',8);
      $this->Cell(26,5,utf8_decode('PUNTO DE ENVIO: '),'LTB',0,'L');
      $this->SetFont('Arial','',8);
      $this->Cell(59,5,utf8_decode($sucursal),'TB',0,'L');
      $this->Cell(85,5,'',1,0,'L');
      $this->Ln();

      $this->Cell(10,5,'',0,0);
      $this->SetFont('Arial','B',8);
      $this->Cell(18,5,utf8_decode('DIRECCIÓN: '),'LTB',0,'L');
      $this->SetFont('Arial','',8);
      $this->Cell(67,5,utf8_decode($direccion),'TB',0,'L');
      $this->Cell(85,5,'',1,0,'L');
      $this->Ln();

      $this->Cell(10,5,'',0,0);
      $this->SetFont('Arial','B',8);
      $this->Cell(13,5,utf8_decode('CIUDAD: '),'LTB',0,'L');
      $this->SetFont('Arial','',8);
      $this->Cell(72,5,utf8_decode($ciudad),'TB',0,'L');
      $this->Cell(85,5,'',1,0,'L');
      $this->Ln();

      $this->Cell(10,5,'',0,0);
      $this->SetFont('Arial','B',8);
      $this->Cell(18,5,utf8_decode('TELÉFONO: '),'LTB',0,'L');
      $this->SetFont('Arial','',8);
      $this->Cell(67,5,utf8_decode($telefono),'TB',0,'L');
      $this->Cell(85,5,'',1,0,'L');
      $this->Ln();

      $this->Cell(10,5,'',0,0);
      $this->SetFont('Arial','B',8);
      $this->Cell(16,5,utf8_decode('CELULAR: '),'LTB',0,'L');
      $this->SetFont('Arial','',8);
      $this->Cell(69,5,utf8_decode($celular),'TB',0,'L');
      $this->Cell(85,5,'',1,0,'L');
      $this->Ln();

      $this->Cell(10,5,'',0,0);
      $this->SetFont('Arial','B',8);
      $this->Cell(12,5,utf8_decode('E-MAIL: '),'LTB',0,'L');
      $this->SetFont('Arial','',8);
      $this->Cell(73,5,utf8_decode($email),'TB',0,'L');
      $this->Cell(85,5,'',1,0,'L');
      $this->Ln();
      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','B',10);
      $this->MultiCell(185,3,utf8_decode('NOTA: En caso que la dirección sea modificada, por favor anexar copia de la cámara de comercio para verificar el cambio.'),0,'J');

      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','',10);
      $this->MultiCell(185,3,utf8_decode('* ANEXAR RUT DEBIDAMENTE ACTUALIZADO.'),0,'J');

      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','',10);
      $this->MultiCell(185,3,utf8_decode('Agradecemos enviar la información a la mayor brevedad.'),0,'J');

      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','',10);
      $this->MultiCell(185,3,utf8_decode('* AUTORIZACIÓN DE TRATAMIENTO DE DATOS PERSONALES.'),0,'J');

      $this->Ln();

      $this->SetFont('Arial','',10);
      $this->MultiCell(185,3,utf8_decode('* REGISTRO Y ACTUALIZACIÓN INFORMACIÓN DE TERCEROS.'),0,'J');      

      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',10);
      $this->Cell(100,5,utf8_decode('Cordialmente,'),0,0,'L');

      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','',10);
      $this->Cell(92,5,utf8_decode('______________________________'),0,0,'L');
      $this->SetFont('Arial','',10);
      $this->Cell(79,5,utf8_decode('______________________________'),0,0,'L');

      $this->Ln();

      $this->SetFont('Arial','B',10);
      $this->Cell(92,5,utf8_decode('DEPARTAMENTO DE CRÉDITOS'),0,0,'L');
      $this->SetFont('Arial','B',10);
      $this->Cell(79,5,utf8_decode('FIRMA DE CLIENTE'),0,0,'L');

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
    $this->Ln();
    $this->Ln();
    $this->Cell(92,5,utf8_decode('________________________________'),0,0,'L');
    $this->Cell(92,5,utf8_decode('________________________________'),0,0,'L');
    $this->Ln();
    $this->SetFont('Arial','B',10);
    $this->Cell(92,3,utf8_decode('DEPARTAMENTO DE CRÉDITOS'),0,0,'L');
    $this->Cell(92,3,utf8_decode('Recibido'),0,0,'L');
    $this->Ln();
    $this->Cell(92,3,utf8_decode('Tel: 4220612 ext. 2144'),0,0,'L');


  }//fin tabla

  function Footer()
  {
      $this->SetY(-20);
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
$pdf->setData($array_cli);
$pdf->setLogoPansell($logo_pansell);
$pdf->setPiePag1($PiePag1);
$pdf->setPiePag2($PiePag2);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 40);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Circulares_actualizacion_datos_'.date('Y_m_d_H_i_s').'.pdf');

?>

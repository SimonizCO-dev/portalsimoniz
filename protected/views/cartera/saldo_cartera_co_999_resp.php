<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias


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

$query ="EXEC P_PR_FIN_CT_SC_999";

UtilidadesVarias::log($query);

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setSql($sql){
    $this->sql = $sql;
  }

  function Header(){
    $this->SetFont('Arial','B',12);
    $this->Cell(200,5,'SALDO DE CARTERA POR CO 999 DETALLE',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
    $this->Ln();
    
    $this->SetFont('Arial','B',9);
    $this->Cell(30,5, utf8_decode('Nit'),0,0,'L');
    $this->Cell(70,5, utf8_decode('Razón social'),0,0,'L');
    $this->Cell(30,5, utf8_decode('Entre 1 - 30'),0,0,'R');
    $this->Cell(30,5, utf8_decode('Entre 31 - 60'),0,0,'R');
    $this->Cell(30,5, utf8_decode('Entre 61 - 90'),0,0,'R');
    $this->Cell(30,5, utf8_decode('Entre 90 - 120'),0,0,'R');
    $this->Cell(30,5, utf8_decode('Más de 120'),0,0,'R');
    $this->Cell(30,5, utf8_decode('Total'),0,0,'R');
    $this->Ln();

    $this->SetDrawColor(0,0,0);    
    $this->Cell(280,1,'','T');                            
    $this->Ln();
    
  }

  function Tabla(){

    $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

    $tf_a = 0;
    $tf_b = 0;
    $tf_c = 0;
    $tf_d = 0;
    $tf_e = 0;
    $tf_s = 0;

    foreach ($query1 as $reg) {
      $nit     = $reg['NIT_CLIENTE'];
      $cliente = $reg['RZ_CLIENTE'];
      $a  = $reg['Entre_1_30'];
      $b  = $reg['Entre_31_60'];
      $c  = $reg['Entre_61_90'];
      $d  = $reg['Entre_90_120'];
      $e  = $reg['Entre_120_Mas'];
      $s  = $reg['TOTAL'];

      $this->SetFont('Arial','',9);
      $this->Cell(30,3,utf8_decode($nit),0,0,'L');
      $this->Cell(70,3,substr(utf8_decode($cliente),0 , 33),0,0,'L');
      $this->Cell(30,3,number_format(($a),0,".",","),0,0,'R');
      $this->Cell(30,3,number_format(($b),0,".",","),0,0,'R');
      $this->Cell(30,3,number_format(($c),0,".",","),0,0,'R');
      $this->Cell(30,3,number_format(($d),0,".",","),0,0,'R');
      $this->Cell(30,3,number_format(($e),0,".",","),0,0,'R');
      $this->Cell(30,3,number_format(($s),0,".",","),0,0,'R');
      $this->Ln();

      $tf_a = $tf_a + $a;
      $tf_b = $tf_b + $b;
      $tf_c = $tf_c + $c;
      $tf_d = $tf_d + $d;
      $tf_e = $tf_e + $e;
      $tf_s = $tf_s + $s;

    }

    $this->Ln();
    $this->SetDrawColor(0,0,0);
    $this->Cell(280,0,'','T');                            
    $this->Ln(5);

    $this->Ln();
    $this->SetFont('Arial','B',10);
    $this->Cell(100,3,utf8_decode('TOTALES'),0,0,'L');
    $this->SetFont('Arial','B',8);
    $this->Cell(30,3,number_format(($tf_a),0,".",","),0,0,'R');
    $this->Cell(30,3,number_format(($tf_b),0,".",","),0,0,'R');
    $this->Cell(30,3,number_format(($tf_c),0,".",","),0,0,'R');
    $this->Cell(30,3,number_format(($tf_d),0,".",","),0,0,'R');
    $this->Cell(30,3,number_format(($tf_e),0,".",","),0,0,'R');
    $this->Cell(30,3,number_format(($tf_s),0,".",","),0,0,'R');
    $this->Ln();

    $ppf_a = $tf_a / $tf_s *100;
    $ppf_b = $tf_b / $tf_s *100;
    $ppf_c = $tf_c / $tf_s *100;
    $ppf_d = $tf_d / $tf_s *100;
    $ppf_e = $tf_e / $tf_s *100;

    $this->Cell(100,3,'',0,0,'R');
    $this->Cell(30,3,number_format(($ppf_a),2,".",",").' %',0,0,'R');
    $this->Cell(30,3,number_format(($ppf_b),2,".",",").' %',0,0,'R');
    $this->Cell(30,3,number_format(($ppf_c),2,".",",").' %',0,0,'R');
    $this->Cell(30,3,number_format(($ppf_d),2,".",",").' %',0,0,'R');
    $this->Cell(30,3,number_format(($ppf_e),2,".",",").' %',0,0,'R');

    $tf_s = 0;
    $tf_a = 0;
    $tf_b = 0;
    $tf_c = 0;
    $tf_d = 0;
    $tf_e = 0;

    $this->Ln();
    
  }//fin tabla

  function Footer()
  {
      $this->SetY(-15);
      $this->SetFont('Arial','B',6);
      $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
  }
}

$pdf = new PDF('L','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act);
$pdf->setSql($query);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Saldo_cartera_co_999_detalle_'.date('Y_m_d_H_i_s').'.pdf');

?>
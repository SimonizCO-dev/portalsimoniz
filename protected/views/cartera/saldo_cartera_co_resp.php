<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$co = $model['co'];

//ruta final
$query_co = Yii::app()->db->createCommand("SELECT f285_descripcion FROM UnoEE1..t285_co_centro_op WHERE f285_id_cia = 2 AND f285_id = ".$co."")->queryRow();

$co_desc = $query_co['f285_descripcion'].'';

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
  EXEC P_PR_FIN_CT_SC_CO
  @CO = N'".$co."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_co = array();

foreach ($query1 as $reg) {
  $supervisor    = $reg['SUPERVISOR'];
  $ruta    = $reg['RUTA'];
  $vendedor    = $reg['VENDEDOR'];
  $a  = $reg['Entre_1_30'];
  $b  = $reg['Entre_31_60'];
  $c  = $reg['Entre_61_90'];
  $d  = $reg['Entre_90_120'];
  $e  = $reg['Entre_120_Mas'];
  $s = $a + $b + $c + $d + $e;

  if(!array_key_exists($supervisor, $array_co)) {
    $array_co[$supervisor] = array();
    $array_co[$supervisor]['info'][] = array(
      'supervisor' => $supervisor, 
      'ruta' => $ruta, 
      'vendedor' => $vendedor, 
      'a' => $a,
      'b' => $b,
      'c' => $c,
      'd' => $d,
      'e' => $e,
      's' => $s,
    );     
  }else{
    if(!array_key_exists($ruta, $array_co[$supervisor])) {
      $array_co[$supervisor]['info'][] = array(
        'supervisor' => $supervisor, 
        'ruta' => $ruta, 
        'vendedor' => $vendedor, 
        'a' => $a,
        'b' => $b,
        'c' => $c,
        'd' => $d,
        'e' => $e,
        's' => $s,
      );        
    }
  }

}

/*fin configuración array de datos*/

  //PDF

  class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setCo($co){
      $this->co = $co;
    }

    function setData($data){
      $this->data = $data;
    }

    function Header(){
      $this->SetFont('Arial','B',12);
      $this->Cell(200,5,'SALDO DE CARTERA POR CO',0,0,'L');
      $this->SetFont('Arial','',9);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: CO: '.$this->co),0,0,'L');
      $this->Ln();

      $this->SetFont('Arial','B',7);
      $this->Cell(53,5, utf8_decode('Supervisor'),0,0,'L');
      $this->Cell(8,5, utf8_decode('Ruta'),0,0,'L');
      $this->Cell(57,5, utf8_decode('Vendedor'),0,0,'L');
      $this->Cell(25,5, utf8_decode('Entre 1 - 30'),0,0,'R');
      $this->Cell(25,5, utf8_decode('Entre 31 - 60'),0,0,'R');
      $this->Cell(25,5, utf8_decode('Entre 61 - 90'),0,0,'R');
      $this->Cell(25,5, utf8_decode('Entre 90 - 120'),0,0,'R');
      $this->Cell(25,5, utf8_decode('Más de 120'),0,0,'R');
      $this->Cell(12,5, utf8_decode(''),0,0,'R');
      $this->Cell(25,5, utf8_decode('Total'),0,0,'R');
      $this->Ln();

      $this->SetDrawColor(0,0,0);
      $this->Cell(280,0,'','T');                            
      $this->Ln();
      
    }

    function Tabla(){

      $array_co = $this->data;

      $tf_s = 0;
      $tf_a = 0;
      $tf_b = 0;
      $tf_c = 0;
      $tf_d = 0;
      $tf_e = 0;

      foreach ($array_co as $co) {
      
        $t_s = 0;
        $t_a = 0;
        $t_b = 0;
        $t_c = 0;
        $t_d = 0;
        $t_e = 0;

        foreach ($co['info'] as $info ) {
      
          $this->SetFont('Arial','',7);
          $this->Cell(53,3,substr(utf8_decode($info['supervisor']),0 , 28),0,0,'L');
          $this->Cell(8,3,utf8_decode($info['ruta']),0,0,'L');
          $this->Cell(57,3,substr(utf8_decode($info['vendedor']),0 , 33),0,0,'L');
          $this->Cell(25,3,number_format(($info['a']),0,".",","),0,0,'R');
          $this->Cell(25,3,number_format(($info['b']),0,".",","),0,0,'R');
          $this->Cell(25,3,number_format(($info['c']),0,".",","),0,0,'R');
          $this->Cell(25,3,number_format(($info['d']),0,".",","),0,0,'R');
          $this->Cell(25,3,number_format(($info['e']),0,".",","),0,0,'R');

          $por = $info['e'] / $info['s'] * 100;

          $this->Cell(12,3,number_format(($por),2,".",",").' %',0,0,'R');
          $this->Cell(25,3,number_format(($info['s']),0,".",","),0,0,'R');
          $this->Ln();
          
          $t_a = $t_a + $info['a'];
          $t_b = $t_b + $info['b'];
          $t_c = $t_c + $info['c'];
          $t_d = $t_d + $info['d'];
          $t_e = $t_e + $info['e'];
          $t_s = $t_s + $info['s'];


        }

        //$this->Ln();
        $this->SetDrawColor(0,0,0);
        $this->Cell(280,1,'','T');                            
        $this->Ln();

        $this->SetFont('Arial','B',7);
        $this->Cell(118,3,'TOTALES '.utf8_decode($info['supervisor']),0,0,'R');
        
        $this->Cell(25,3,number_format(($t_a),0,".",","),0,0,'R');
        $this->Cell(25,3,number_format(($t_b),0,".",","),0,0,'R');
        $this->Cell(25,3,number_format(($t_c),0,".",","),0,0,'R');
        $this->Cell(25,3,number_format(($t_d),0,".",","),0,0,'R');
        $this->Cell(25,3,number_format(($t_e),0,".",","),0,0,'R');
        $this->Cell(12,3,'',0,0,'R');
        $this->Cell(25,3,number_format(($t_s),0,".",","),0,0,'R');

        $ppf_a = $t_a / $t_s *100;
        $ppf_b = $t_b / $t_s *100;
        $ppf_c = $t_c / $t_s *100;
        $ppf_d = $t_d / $t_s *100;
        $ppf_e = $t_e / $t_s *100;

        $this->Ln();
        $this->Cell(118,3,'',0,0,'R');
        $this->Cell(25,3,number_format(($ppf_a),2,".",",").' %',0,0,'R');
        $this->Cell(25,3,number_format(($ppf_b),2,".",",").' %',0,0,'R');
        $this->Cell(25,3,number_format(($ppf_c),2,".",",").' %',0,0,'R');
        $this->Cell(25,3,number_format(($ppf_d),2,".",",").' %',0,0,'R');
        $this->Cell(25,3,number_format(($ppf_e),2,".",",").' %',0,0,'R');


        $this->Ln();
        $this->SetDrawColor(0,0,0);
        $this->Cell(280,1,'','T');                            
        $this->Ln();

        $tf_a = $tf_a + $t_a;
        $tf_b = $tf_b + $t_b;
        $tf_c = $tf_c + $t_c;
        $tf_d = $tf_d + $t_d;
        $tf_e = $tf_e + $t_e;
        $tf_s = $tf_s + $t_s;

        $t_a = 0;
        $t_b = 0;
        $t_c = 0;
        $t_d = 0;
        $t_e = 0;
        $t_s = 0;

        $ppf_a = 0;
        $ppf_b = 0;
        $ppf_c = 0;
        $ppf_d = 0;
        $ppf_e = 0;
        
       }

      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','B',10);
      $this->Cell(118,3,utf8_decode('TOTALES'),0,0,'L');
      $this->SetFont('Arial','B',8);
      $this->Cell(25,3,number_format(($tf_a),0,".",","),0,0,'R');
      $this->Cell(25,3,number_format(($tf_b),0,".",","),0,0,'R');
      $this->Cell(25,3,number_format(($tf_c),0,".",","),0,0,'R');
      $this->Cell(25,3,number_format(($tf_d),0,".",","),0,0,'R');
      $this->Cell(25,3,number_format(($tf_e),0,".",","),0,0,'R');
      $this->Cell(12,3,'',0,0,'R');
      $this->Cell(25,3,number_format(($tf_s),0,".",","),0,0,'R');
      $this->Ln();

      $ptf_a = $tf_a / $tf_s *100;
      $ptf_b = $tf_b / $tf_s *100;
      $ptf_c = $tf_c / $tf_s *100;
      $ptf_d = $tf_d / $tf_s *100;
      $ptf_e = $tf_e / $tf_s *100;

      $this->Cell(118,3,'',0,0,'L');
      $this->Cell(25,3,number_format(($ptf_a),2,".",",").' %',0,0,'R');
      $this->Cell(25,3,number_format(($ptf_b),2,".",",").' %',0,0,'R');
      $this->Cell(25,3,number_format(($ptf_c),2,".",",").' %',0,0,'R');
      $this->Cell(25,3,number_format(($ptf_d),2,".",",").' %',0,0,'R');
      $this->Cell(25,3,number_format(($ptf_e),2,".",",").' %',0,0,'R');
      $this->Ln();

      $tf_s = 0;
      $tf_a = 0;
      $tf_b = 0;
      $tf_c = 0;
      $tf_d = 0;
      $tf_e = 0;

      $ptf_a = 0;
      $ptf_b = 0;
      $ptf_c = 0;
      $ptf_d = 0;
      $ptf_e = 0;

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
  $pdf->setData($array_co);
  $pdf->setCo($co.' / '.$co_desc);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Saldo_cartera_co_'.date('Y_m_d_H_i_s').'.pdf');

?>
<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$vendedor_inicial = $model['vendedor_inicial'];
$vendedor_final = $model['vendedor_final'];

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
  EXEC P_PR_FIN_CT_SALD_CART_VENDEDOR
  @ASESOR1 = N'".$vendedor_inicial."',
  @ASESOR2 = N'".$vendedor_final."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_vendedores = array();

foreach ($query1 as $reg) {
  $vendedor       = $reg['RZ_VENDEDOR'];
  $nit_cliente    = $reg['NIT_CLIENTE'];
  $desc_cliente    = $reg['RZ_CLIENTE'];
  $tel_cliente    = $reg['TELEFONO'];
  $dir_cliente    = $reg['DESC_SUC_CLIENTE'];
  $id_suc_cliente = $reg['ID_SUC_CLIENTE'];
  $fecha_doc  = $reg['FECHA_DOCUMENTO'];
  $fecha_venc  = $reg['FECHA_VENCIDO'];
  $n_documento  = $reg['DOCUMENTO'];
  $cond_pago  = $reg['COND_PAGO'];
  $dias  = $reg['DIAS_VCDO'];
  $valor_inicial  = $reg['TOTAL_DOCUMENTO'];
  $valor_saldo  = $reg['SALDO_DOCUMENTO'];
  $a  = $reg['Entre_1_30'];
  $b  = $reg['Entre_31_60'];
  $c  = $reg['Entre_61_90'];
  $d  = $reg['Entre_90_120'];
  $e  = $reg['Entre_120_Mas'];
  $cupo = $reg['CUPO'];
  $chq = $reg['CHQ'];

  if(!array_key_exists($vendedor, $array_vendedores)) {
    $array_vendedores[$vendedor] = array();
    $array_vendedores[$vendedor][$nit_cliente] = array();
    $array_vendedores[$vendedor][$nit_cliente]['nit_cliente'] = $nit_cliente;
    $array_vendedores[$vendedor][$nit_cliente]['desc_cliente'] = $desc_cliente;
    $array_vendedores[$vendedor][$nit_cliente]['tel_cliente'] = $tel_cliente;
    $array_vendedores[$vendedor][$nit_cliente]['dir_cliente'] = $dir_cliente.' / '.$id_suc_cliente; 
    $array_vendedores[$vendedor][$nit_cliente]['cupo'] = $cupo; 
    $array_vendedores[$vendedor][$nit_cliente]['documentos'][] = array(
      'fecha_doc' => $fecha_doc, 
      'fecha_venc' => $fecha_venc, 
      'n_documento' => $n_documento,
      'cond_pago' => $cond_pago, 
      'dias' => $dias,
      'valor_inicial' => $valor_inicial,
      'valor_saldo' => $valor_saldo,
      'chq' => $chq,
      'a' => $a,
      'b' => $b,
      'c' => $c,
      'd' => $d,
      'e' => $e,
    );  
  }else{
    if(!array_key_exists($nit_cliente, $array_vendedores[$vendedor])) {
      $array_vendedores[$vendedor][$nit_cliente] = array();
      $array_vendedores[$vendedor][$nit_cliente]['nit_cliente'] = $nit_cliente;
      $array_vendedores[$vendedor][$nit_cliente]['desc_cliente'] = $desc_cliente;
      $array_vendedores[$vendedor][$nit_cliente]['tel_cliente'] = $tel_cliente;
      $array_vendedores[$vendedor][$nit_cliente]['dir_cliente'] = $dir_cliente.' / '.$id_suc_cliente;
      $array_vendedores[$vendedor][$nit_cliente]['cupo'] = $cupo; 
      $array_vendedores[$vendedor][$nit_cliente]['documentos'][] = array(
        'fecha_doc' => $fecha_doc, 
        'fecha_venc' => $fecha_venc, 
        'n_documento' => $n_documento,
        'cond_pago' => $cond_pago, 
        'dias' => $dias,
        'valor_inicial' => $valor_inicial,
        'valor_saldo' => $valor_saldo,
        'chq' => $chq,
        'a' => $a,
        'b' => $b,
        'c' => $c,
        'd' => $d,
        'e' => $e,
      );     
    }else{
      if(!array_key_exists($n_documento, $array_vendedores[$vendedor][$nit_cliente])) {
        $array_vendedores[$vendedor][$nit_cliente]['nit_cliente'] = $nit_cliente;
        $array_vendedores[$vendedor][$nit_cliente]['desc_cliente'] = $desc_cliente;
        $array_vendedores[$vendedor][$nit_cliente]['tel_cliente'] = $tel_cliente;
        $array_vendedores[$vendedor][$nit_cliente]['dir_cliente'] = $dir_cliente.' / '.$id_suc_cliente; 
        $array_vendedores[$vendedor][$nit_cliente]['cupo'] = $cupo; 
        $array_vendedores[$vendedor][$nit_cliente]['documentos'][] = array(
          'fecha_doc' => $fecha_doc, 
          'fecha_venc' => $fecha_venc, 
          'n_documento' => $n_documento,
          'cond_pago' => $cond_pago, 
          'dias' => $dias,
          'valor_inicial' => $valor_inicial,
          'valor_saldo' => $valor_saldo,
          'chq' => $chq,
          'a' => $a,
          'b' => $b,
          'c' => $c,
          'd' => $d,
          'e' => $e,
        );        
      }
    }
  }
}

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setVendedorInicial($vendedor_inicial){
    $this->vendedor_inicial = $vendedor_inicial;
  }

  function setVendedorFinal($vendedor_final){
    $this->vendedor_final = $vendedor_final;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){
    $this->SetFont('Arial','B',12);
    $this->Cell(200,5,'SALDO DE CARTERA POR VENDEDOR',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
    $this->Ln();
    $this->SetFont('Arial','',7);
    $this->Cell(280,5,utf8_decode('Criterio de búsqueda: Vendedor inicial: '.$this->vendedor_inicial.' / Vendedor final: '.$this->vendedor_final),0,0,'L');
    $this->Ln();

    $this->SetFont('Arial','B',8);
    $this->Cell(18,5, utf8_decode('Fecha doc'),0,0,'L');
    $this->Cell(18,5, utf8_decode('Fecha vcto'),0,0,'L');
    $this->Cell(26,5, utf8_decode('# documento'),0,0,'L');
    $this->Cell(50,5, utf8_decode('Condición de pago'),0,0,'L');
    $this->Cell(9,5, utf8_decode('Días'),0,0,'R');
    $this->Cell(23,5, utf8_decode('Valor Inicial'),0,0,'R');
    $this->Cell(23,5, utf8_decode('Valor Saldo'),0,0,'R');
    $this->Cell(3,5, utf8_decode(''),0,0,'L');
    $this->Cell(22,5, utf8_decode('Entre 1 - 30'),0,0,'R');
    $this->Cell(22,5, utf8_decode('Entre 31 - 60'),0,0,'R');
    $this->Cell(22,5, utf8_decode('Entre 61 - 90'),0,0,'R');
    $this->Cell(22,5, utf8_decode('Entre 90 - 120'),0,0,'R');
    $this->Cell(22,5, utf8_decode('Más de 120'),0,0,'R');
    $this->Ln();
    
    $this->SetDrawColor(0,0,0);
    $this->Cell(280,1,'','T');                            
    $this->Ln();
    
  }

  function Tabla(){

    $array_vendedores = $this->data;

    foreach ($array_vendedores as $vendedor => $array_clientes) {
      
      $this->SetFont('Arial','B',10);
      $this->Ln();
      $this->Cell(195,3, 'Vendedor '.$vendedor ,0,0,'L');
      $this->Ln();

      $tf_s = 0;
      $tf_a = 0;
      $tf_b = 0;
      $tf_c = 0;
      $tf_d = 0;
      $tf_e = 0;

      foreach ($array_clientes as $cliente) {

        $this->Ln();
        $this->SetFont('Arial','B',9);
        $this->Cell(70,3, utf8_decode($cliente['desc_cliente']),0,0,'L');
        $this->Cell(70,3, utf8_decode($cliente['nit_cliente']),0,0,'L');
        $this->Cell(70,3, utf8_decode($cliente['tel_cliente']),0,0,'L');
        $this->Cell(70,3, utf8_decode($cliente['dir_cliente']),0,0,'L');
        $this->Ln();

        $t_s = 0;
        $t_a = 0;
        $t_b = 0;
        $t_c = 0;
        $t_d = 0;
        $t_e = 0;

        $cupo = $cliente['cupo'];

        foreach ($cliente['documentos'] as $documento ) {
      
          $this->SetFont('Arial','',8);
          $this->Cell(18,3,$documento['fecha_doc'],0,0,'L');
          $this->Cell(18,3,$documento['fecha_venc'],0,0,'L');
          $this->Cell(26,3,$documento['n_documento'],0,0,'L');
          $this->Cell(50,3,$documento['cond_pago'],0,0,'L');
          $this->Cell(9,3,$documento['dias'],0,0,'R');
          $this->Cell(23,3,number_format(($documento['valor_inicial']),0,".",","),0,0,'R');
          $this->Cell(23,3,number_format(($documento['valor_saldo']),0,".",","),0,0,'R');
          $this->SetFont('Arial','B',7);
          $this->Cell(3,3,$documento['chq'],0,0,'L');
          $this->SetFont('Arial','',7);
          $this->Cell(22,3,number_format(($documento['a']),0,".",","),0,0,'R');
          $this->Cell(22,3,number_format(($documento['b']),0,".",","),0,0,'R');
          $this->Cell(22,3,number_format(($documento['c']),0,".",","),0,0,'R');
          $this->Cell(22,3,number_format(($documento['d']),0,".",","),0,0,'R');
          $this->Cell(22,3,number_format(($documento['e']),0,".",","),0,0,'R');
          $this->Ln();
          $t_s = $t_s + $documento['valor_saldo'];
          $t_a = $t_a + $documento['a'];
          $t_b = $t_b + $documento['b'];
          $t_c = $t_c + $documento['c'];
          $t_d = $t_d + $documento['d'];
          $t_e = $t_e + $documento['e'];


        }

        $cupo_disp = $cupo - $t_s;

        $this->SetFont('Arial','B',8);
        $this->Cell(33,3,utf8_decode('CUPO TOTAL:'),0,0,'L');
        $this->Cell(34,3,number_format(($cupo),0,".",","),0,0,'L');
        $this->Cell(34,3,utf8_decode('CUPO DISPONIBLE:'),0,0,'L');
        $this->Cell(34,3,number_format(($cupo_disp),0,".",","),0,0,'L');
        $this->Cell(10,3,'TOTALES',0,0,'R');
        $this->Cell(22,3,number_format(($t_s),0,".",","),0,0,'R');
        $this->Cell(3,3,'',0,0,'L');
        $this->Cell(22,3,number_format(($t_a),0,".",","),0,0,'R');
        $this->Cell(22,3,number_format(($t_b),0,".",","),0,0,'R');
        $this->Cell(22,3,number_format(($t_c),0,".",","),0,0,'R');
        $this->Cell(22,3,number_format(($t_d),0,".",","),0,0,'R');
        $this->Cell(22,3,number_format(($t_e),0,".",","),0,0,'R');

        $this->Ln();
        $this->SetDrawColor(0,0,0);
        $this->Cell(280,1,'','T');                            
        $this->Ln();
        $this->Ln();

        $tf_s = $tf_s + $t_s;
        $tf_a = $tf_a + $t_a;
        $tf_b = $tf_b + $t_b;
        $tf_c = $tf_c + $t_c;
        $tf_d = $tf_d + $t_d;
        $tf_e = $tf_e + $t_e;

        $t_s = 0;
        $t_a = 0;
        $t_b = 0;
        $t_c = 0;
        $t_d = 0;
        $t_e = 0;
        
       }

      $this->Ln();
      $this->SetFont('Arial','B',10);
      $this->Cell(145,3,utf8_decode('TOTALES para el vendedor '.$vendedor),0,0,'L');
      $this->SetFont('Arial','B',8);
      $this->Cell(22,3,number_format(($tf_s),0,".",","),0,0,'R');
      $this->Cell(3,3,'',0,0,'L');
      $this->Cell(22,3,number_format(($tf_a),0,".",","),0,0,'R');
      $this->Cell(22,3,number_format(($tf_b),0,".",","),0,0,'R');
      $this->Cell(22,3,number_format(($tf_c),0,".",","),0,0,'R');
      $this->Cell(22,3,number_format(($tf_d),0,".",","),0,0,'R');
      $this->Cell(22,3,number_format(($tf_e),0,".",","),0,0,'R');
      $this->Ln();

      $ptf_a = $tf_a / $tf_s *100;
      $ptf_b = $tf_b / $tf_s *100;
      $ptf_c = $tf_c / $tf_s *100;
      $ptf_d = $tf_d / $tf_s *100;
      $ptf_e = $tf_e / $tf_s *100;

      $this->Cell(170,3,'',0,0,'L');
      $this->Cell(22,3,number_format(($ptf_a),2,".",",").' %',0,0,'R');
      $this->Cell(22,3,number_format(($ptf_b),2,".",",").' %',0,0,'R');
      $this->Cell(22,3,number_format(($ptf_c),2,".",",").' %',0,0,'R');
      $this->Cell(22,3,number_format(($ptf_d),2,".",",").' %',0,0,'R');
      $this->Cell(22,3,number_format(($ptf_e),2,".",",").' %',0,0,'R');
      $this->Ln();

      $tf_s = 0;
      $tf_a = 0;
      $tf_b = 0;
      $tf_c = 0;
      $tf_d = 0;
      $tf_e = 0;

      //$this->Ln();

      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');                            
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

$pdf = new PDF('L','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act);
$pdf->setData($array_vendedores);
$pdf->setVendedorInicial($vendedor_inicial);
$pdf->setVendedorFinal($vendedor_final);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Saldo_cartera_vendedor_'.date('Y_m_d_H_i_s').'.pdf');
?>
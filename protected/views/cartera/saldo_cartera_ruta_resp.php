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

//ruta inicial
$query_ruta_inicial = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id,f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_id = ".$ruta_inicial."")->queryRow();

$condicion_ruta_inicial = $query_ruta_inicial['Id'].'';
$texto_ruta_inicial = $query_ruta_inicial['Ruta'].'';

//ruta final
$query_ruta_final = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id,f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_id = ".$ruta_final."")->queryRow();

$condicion_ruta_final = $query_ruta_final['Id'].'';
$texto_ruta_final = $query_ruta_final['Ruta'].'';

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
  EXEC P_PR_FIN_CT_SALD_CART_RUTA
  @RUTA1 = N'".$condicion_ruta_inicial."',
  @RUTA2 = N'".$condicion_ruta_final."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_rutas = array();

foreach ($query1 as $reg) {
  $ruta       = $reg['RUTA'].'|'.$reg['RZ_VENDEDOR'];
  $nit_cliente    = $reg['NIT_CLIENTE'];
  $desc_cliente    = $reg['RZ_CLIENTE'];
  $tel_cliente    = $reg['TELEFONO'];
  $dir_cliente    = $reg['DESC_SUC_CLIENTE'];
  $id_suc_cliente = $reg['ID_SUC_CLIENTE'];
  $fecha_doc  = $reg['FECHA_DOCUMENTO'];
  $fecha_venc  = $reg['FECHA_VENCIDO'];
  $id  = $reg['VEND'];
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

  if(!array_key_exists($ruta, $array_rutas)) {
    //si no existe la ruta
    $array_rutas[$ruta] = array();
    $array_rutas[$ruta][$nit_cliente] = array();
    $array_rutas[$ruta][$nit_cliente]['desc_cliente'] = $desc_cliente;
    $array_rutas[$ruta][$nit_cliente]['suc'] = array(); 
    $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente] = array();
    $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['telefono'] = $tel_cliente;
    $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['direccion'] = $dir_cliente;
    $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['cupo'] = $cupo;
    $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'] = array();
    $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
      'fecha_doc' => $fecha_doc, 
      'fecha_venc' => $fecha_venc,
      'id_vend' => $id, 
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
    //si no existe el cliente
    if(!array_key_exists($nit_cliente, $array_rutas[$ruta])) {
      $array_rutas[$ruta][$nit_cliente] = array();
      $array_rutas[$ruta][$nit_cliente]['desc_cliente'] = $desc_cliente;
      $array_rutas[$ruta][$nit_cliente]['suc'] = array();
      $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente] = array();
      $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['telefono'] = $tel_cliente;
      $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['direccion'] = $dir_cliente;
      $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['cupo'] = $cupo;
      $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'] = array();
      $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
        'fecha_doc' => $fecha_doc, 
        'fecha_venc' => $fecha_venc,
        'id_vend' => $id, 
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
      //si no existe la sucursal
      if(!array_key_exists($id_suc_cliente, $array_rutas[$ruta][$nit_cliente]['suc'])) {
        $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente] = array();
        $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['telefono'] = $tel_cliente;
        $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['direccion'] = $dir_cliente;
        $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['cupo'] = $cupo;
        $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'] = array();
        $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
          'fecha_doc' => $fecha_doc, 
          'fecha_venc' => $fecha_venc,
          'id_vend' => $id, 
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
        if(!array_key_exists($n_documento, $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'])) {
          //si no existe documento
          $array_rutas[$ruta][$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
            'fecha_doc' => $fecha_doc, 
            'fecha_venc' => $fecha_venc,
            'id_vend' => $id, 
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
}

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setRutaInicial($texto_ruta_inicial){
    $this->ruta_inicial = $texto_ruta_inicial;
  }

  function setRutaFinal($texto_ruta_final){
    $this->ruta_final = $texto_ruta_final;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){

    $this->SetFont('Arial','B',12);
    $this->Cell(200,5,'SALDO DE CARTERA POR RUTA',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
    $this->Ln();
    $this->SetFont('Arial','',7);
    $this->Cell(280,5,utf8_decode('Criterio de búsqueda: Ruta inicial: '.$this->ruta_inicial.' / Ruta final: '.$this->ruta_final),0,0,'L');
    $this->Ln();

    $this->SetFont('Arial','B',8);
    $this->Cell(18,5, utf8_decode('Fecha doc'),0,0,'L');
    $this->Cell(18,5, utf8_decode('Fecha vcto'),0,0,'L');
    $this->Cell(8,5, utf8_decode('ID'),0,0,'L');
    $this->Cell(26,5, utf8_decode('# documento'),0,0,'L');
    $this->Cell(50,5, utf8_decode('Condición de pago'),0,0,'L');
    $this->Cell(8,5, utf8_decode('Días'),0,0,'R');
    $this->Cell(22,5, utf8_decode('Valor Inicial'),0,0,'R');
    $this->Cell(22,5, utf8_decode('Valor Saldo'),0,0,'R');
    $this->Cell(3,5, utf8_decode(''),0,0,'L');
    $this->Cell(21,5, utf8_decode('Entre 1 - 30'),0,0,'R');
    $this->Cell(21,5, utf8_decode('Entre 31 - 60'),0,0,'R');
    $this->Cell(21,5, utf8_decode('Entre 61 - 90'),0,0,'R');
    $this->Cell(21,5, utf8_decode('Entre 90 - 120'),0,0,'R');
    $this->Cell(21,5, utf8_decode('Más de 120'),0,0,'R');
    $this->Ln();
    
    $this->SetDrawColor(0,0,0);
    $this->Cell(280,1,'','T');                            
    $this->Ln();
  
  }

  function Tabla(){

    $array_rutas = $this->data;

    $i = 1;

    foreach ($array_rutas as $ruta => $array_clientes){

      if($i > 1){
        $this->AddPage();
      }

      $explode_ruta = explode("|",$ruta);

      $r = $explode_ruta[0];
      $v = $explode_ruta[1];

      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','B',9);
      $this->Cell(140,3,utf8_decode('Ruta '.$r),0,0,'L');
      $this->Cell(140,3,utf8_decode('Vendedor: '.$v),0,0,'R');
      $this->Ln();
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

      foreach ($array_clientes as $nit_c => $cli_info){

        $nit_cliente = $nit_c;
        $desc_cliente = $cli_info['desc_cliente'];
        $suc_docs = $cli_info['suc'];

        foreach ($suc_docs as $suc => $suc_info) {

          $tel_cliente = $suc_info['telefono'];
          $dir_cliente = $suc_info['direccion'];
          $suc_cliente = $suc;
          $cupo_cliente = $suc_info['cupo'];
        
          $docs_suc_cliente = $suc_info['documentos'];

          $this->SetFont('Arial','B',7);
          $this->Cell(70,3, utf8_decode($desc_cliente),0,0,'L');
          $this->Cell(70,3, utf8_decode($nit_cliente),0,0,'L');
          $this->Cell(70,3, utf8_decode($tel_cliente),0,0,'L');
          $this->Cell(70,3, utf8_decode($dir_cliente.' / '.$suc_cliente),0,0,'L');
          $this->Ln();

          $t_s = 0;
          $t_a = 0;
          $t_b = 0;
          $t_c = 0;
          $t_d = 0;
          $t_e = 0;

          foreach ($docs_suc_cliente as $doc => $doc_info) {

            $fecha_doc = $doc_info['fecha_doc'];
            $fecha_venc = $doc_info['fecha_venc'];
            $id_vend = $doc_info['id_vend'];
            $cond_pago = $doc_info['cond_pago'];
            $dias = $doc_info['dias'];
            $valor_inicial = $doc_info['valor_inicial'];
            $valor_saldo = $doc_info['valor_saldo'];
            $chq = $doc_info['chq'];
            $a = $doc_info['a'];
            $b = $doc_info['b'];
            $c = $doc_info['c'];
            $d = $doc_info['d'];
            $e = $doc_info['e'];

            $this->SetFont('Arial','',7);
            $this->Cell(18,3,$fecha_doc,0,0,'L');
            $this->Cell(18,3,$fecha_venc,0,0,'L');
            $this->Cell(8,3,$id_vend,0,0,'L');
            $this->Cell(26,3,$doc,0,0,'L');
            $this->Cell(50,3,$cond_pago,0,0,'L');
            $this->Cell(8,3,$dias,0,0,'R');
            $this->Cell(22,3,number_format(($valor_inicial),0,".",","),0,0,'R');
            $this->Cell(22,3,number_format(($valor_saldo),0,".",","),0,0,'R');
            $this->SetFont('Arial','B',7);
            $this->Cell(3,3,$chq,0,0,'L');
            $this->SetFont('Arial','',7);
            $this->Cell(21,3,number_format(($a),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($b),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($c),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($d),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($e),0,".",","),0,0,'R');
            $this->Ln();

            $t_s = $t_s + $valor_saldo;
            $t_a = $t_a + $a;
            $t_b = $t_b + $b;
            $t_c = $t_c + $c;
            $t_d = $t_d + $d;
            $t_e = $t_e + $e;

          }

          $cupo_disp = $cupo_cliente - $t_s;

          $this->SetFont('Arial','B',6);
          $this->Cell(33,3,utf8_decode('CUPO TOTAL:'),0,0,'L');
          $this->Cell(37,3,number_format(($cupo_cliente),0,".",","),0,0,'L');
          $this->Cell(34,3,utf8_decode('CUPO DISPONIBLE:'),0,0,'L');
          $this->Cell(37,3,number_format(($cupo_disp),0,".",","),0,0,'L');
          $this->Cell(10,3,'TOTALES',0,0,'R');
          $this->Cell(21,3,number_format(($t_s),0,".",","),0,0,'R');
          $this->Cell(3,3,'',0,0,'L');
          $this->Cell(21,3,number_format(($t_a),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_b),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_c),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_d),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_e),0,".",","),0,0,'R');

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
      }

      $this->SetFont('Arial','B',8);
      $this->Cell(151,3,utf8_decode('TOTALES para la Ruta '.$r),0,0,'L');
      $this->SetFont('Arial','B',7);
      $this->Cell(21,3,number_format(($tf_s),0,".",","),0,0,'R');
      $this->Cell(3,3,'',0,0,'L');
      $this->Cell(21,3,number_format(($tf_a),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_b),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_c),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_d),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_e),0,".",","),0,0,'R');
      $this->Ln();

      $ptf_a = $tf_a / $tf_s *100;
      $ptf_b = $tf_b / $tf_s *100;
      $ptf_c = $tf_c / $tf_s *100;
      $ptf_d = $tf_d / $tf_s *100;
      $ptf_e = $tf_e / $tf_s *100;

      $this->Cell(175,3,'',0,0,'L');
      $this->Cell(21,3,number_format(($ptf_a),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_b),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_c),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_d),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_e),2,".",",").' %',0,0,'R');
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

      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');                            
      $this->Ln();
      $this->Ln();

      $i++;

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
$pdf->setData($array_rutas);
$pdf->setRutaInicial($texto_ruta_inicial);
$pdf->setRutaFinal($texto_ruta_final);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Saldo_cartera_ruta_'.date('Y_m_d_H_i_s').'.pdf');

?>
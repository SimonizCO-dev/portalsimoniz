<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];
$cliente = $model['cliente'];

//cliente
$rep = new Reporte;
$condicion_cliente = $rep->NitCliente($cliente);
$texto_cliente = $rep->DescCliente($cliente);

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

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

$query ="
  SET NOCOUNT ON
  EXEC P_PR_FIN_CONS_HISTORIAL
  @FECHA1 = N'".$FechaM1."',
  @FECHA2 = N'".$FechaM2."',
  @NIT = N'".$condicion_cliente."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_suc = array();

foreach ($query1 as $reg) {
  $nit_cliente = $reg['NIT_CLIENTE'];
  $nombre_cliente = $reg['NOMBRE_CLIENTE'];
  $sucursal = $reg['SUCURSAL'];
  $nombre_sucursal = $reg['NOMBRE_SUCURSAL'];
  $direccion = $reg['DIRECCION'];
  $cuidad = $reg['CIUDAD'];
  $telefono = $reg['TELEFONO'];
  $docto1 = $reg['DOCTO1'];
  $fecha_documento = $reg['FECHA_DOCUMENTO'];
  $fecha_vcto = $reg['FECHA_VCTO'];
  $valor_docto = $reg['VALOR_DOCTO'];
  $valor_aplicado = $reg['VALOR_APLICADO'];
  $saldo = $reg['SALDO'];
  $vend = $reg['VEND'];
  $docto2 = $reg['DOCTO2'];
  $dias = $reg['DIAS'];
  $fecha_recaudo = $reg['FECHA_RECAUDO'];
  $valor_aplicado = $reg['VALOR_APLICADO'];
  $tipo = $reg['TIPO'];

  if(!array_key_exists($sucursal, $array_suc)) {
    $array_suc[$sucursal] = array();
    $array_suc[$sucursal]['info'] = array();
    $array_suc[$sucursal]['info']['nombre_sucursal'] = $nombre_sucursal;
    $array_suc[$sucursal]['info']['direccion'] = $direccion;
    $array_suc[$sucursal]['info']['cuidad'] = $cuidad;
    $array_suc[$sucursal]['documentos'][$docto1] = array(
      'fecha_doc' => $fecha_documento,
      'fecha_vcto' => $fecha_vcto,
      'valor_inicial' => $valor_docto,
      'valor_aplicado' => $valor_aplicado,
      'saldo' => $saldo,
      'tipo' => $tipo,
    );
    if($docto2 != ""){
      $array_suc[$sucursal]['documentos'][$docto1]['hijos'][$docto2] = array(
        'n_doc' => $docto2,
        'fecha_recaudo' => $fecha_recaudo,
        'valor_aplicado' => $valor_aplicado,
        'dias' => $dias,
      );  
    }
  }else{
    if(!array_key_exists($docto1, $array_suc[$sucursal]['documentos'])) {
      $array_suc[$sucursal]['documentos'][$docto1] = array(
        'fecha_doc' => $fecha_documento,
        'fecha_vcto' => $fecha_vcto,
        'valor_inicial' => $valor_docto,
        'valor_aplicado' => $valor_aplicado,
        'saldo' => $saldo,
        'tipo' => $tipo,
      );
      if($docto2 != ""){
        $array_suc[$sucursal]['documentos'][$docto1]['hijos'][$docto2] = array(
          'n_doc' => $docto2,
          'fecha_recaudo' => $fecha_recaudo,
          'valor_aplicado' => $valor_aplicado,
          'dias' => $dias,
        );  
      }   
    }else{
      if($docto2 != ""){
        if(!array_key_exists($docto2, $array_suc[$sucursal]['documentos'][$docto1]['hijos'])) {
          $array_suc[$sucursal]['documentos'][$docto1]['hijos'][$docto2] = array(
            'n_doc' => $docto2,
            'fecha_recaudo' => $fecha_recaudo,
            'valor_aplicado' => $valor_aplicado,
            'dias' => $dias,
          );       
        }
      }
    }
  }
}

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{
  
  function setFechaInicial($fecha_inicial){
    $this->fecha_inicial = $fecha_inicial;
  }

  function setFechaFinal($fecha_inicial){
    $this->fecha_final = $fecha_inicial;
  }

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setCliente($cliente){
    $this->cliente = $cliente;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){
    $this->SetFont('Arial','B',10);
    $this->Cell(100,5,'HISTORIAL DE CARTERA POR CLIENTE',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(95,5,utf8_decode($this->fecha_actual),0,0,'R');
    $this->Ln();
    $this->SetFont('Arial','',7);
    $this->Cell(195,5,utf8_decode('Criterio de búsqueda: Fecha del '.$this->fecha_inicial.' al '.$this->fecha_final),0,0,'L');
    $this->Ln();
    $this->Cell(195,5,utf8_decode('Criterio de búsqueda: Cliente: '.$this->cliente),0,0,'L');
    $this->Ln();
    $this->Ln();
    
    //linea superior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->Ln();  
    
    //cabecera de tabla
    $this->SetFont('Arial','B',8);

    $this->Cell(41,2,utf8_decode('N°. DOCUMENTO'),0,0,'L');
    $this->Cell(20,2,utf8_decode('FECHA DOC'),0,0,'L');
    $this->Cell(20,2,utf8_decode('FECHA VCTO'),0,0,'L');
    $this->Cell(38,2,utf8_decode('VALOR INICIAL'),0,0,'R');
    $this->Cell(38,2,utf8_decode('VALOR APLICADO'),0,0,'R');
    $this->Cell(38,2,utf8_decode('VALOR SALDO'),0,0,'R');   
    $this->Ln(3);   
    
    //linea inferior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    
    $this->Ln(5);

  }

  function Tabla(){

    $array_suc = $this->data;

    foreach ($array_suc as $suc => $var_a) {
      
      $id_suc = $suc;
      $nombre_sucursal = $var_a['info']['nombre_sucursal'];
      $direccion = $var_a['info']['direccion'];
      $cuidad = $var_a['info']['cuidad'];

      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln(); 

      $this->SetFont('Arial','B',8);
      $this->Cell(61,2,utf8_decode($nombre_sucursal),0,0,'L');
      $this->Cell(58,2,utf8_decode($direccion),0,0,'R');
      $this->Cell(38,2,utf8_decode($cuidad),0,0,'R');
      $this->Cell(38,2,utf8_decode($id_suc),0,0,'R');  
      $this->Ln(3);   
      
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();      

      $docs = $var_a['documentos'];

      $flag = 0;

      if(!empty($docs)){
        foreach ($docs as $doc => $var_b) {
          $n_doc = $doc;
          $fecha_doc = $var_b['fecha_doc'];
          $fecha_vcto = $var_b['fecha_vcto'];
          $valor_inicial = $var_b['valor_inicial'];
          $valor_aplicado = $var_b['valor_aplicado'];
          $saldo = $var_b['saldo'];
          $tipo = $var_b['tipo'];

          if($tipo == 1 && $flag == 0){

            $flag = 1;

            $this->SetDrawColor(0,0,0);
            $this->Cell(195,1,'','T');                            
            $this->Ln();
            
            $this->SetFont('Arial','',8);
            $this->Cell(195,2,utf8_decode('Documentos sin aplicar o anticipos'),0,0,'L'); 
            $this->Ln(3);
    
          }

          $this->SetFont('Arial','B',7);
          $this->Cell(41,3,$n_doc,0,0,'L');
          $this->Cell(20,3,$fecha_doc,0,0,'L');
          $this->Cell(20,3,$fecha_vcto,0,0,'L');
          $this->Cell(38,3,number_format(($valor_inicial),2,".",","),0,0,'R');
          $this->Cell(38,3,number_format(($valor_aplicado),2,".",","),0,0,'R');
          $this->Cell(38,3,number_format(($saldo),2,".",","),0,0,'R');
          $this->Ln();


          if (array_key_exists('hijos', $var_b)) {

            $docs2 = $var_b['hijos'];

            foreach ($docs2 as $docb => $var_c) {

              $n_doc = $docb;
              $fecha_recaudo = $var_c['fecha_recaudo'];
              $valor_aplicado = $var_c['valor_aplicado'];
              $dias = $var_c['dias'];

              $this->SetFont('Arial','',7);
              $this->Cell(41,3,$docb,0,0,'R');
              $this->Cell(20,3,'',0,0,'L');
              $this->Cell(20,3,$fecha_recaudo,0,0,'L');
              $this->Cell(38,3,'',0,0,'R');
              $this->Cell(38,3,number_format(($valor_aplicado),2,".",","),0,0,'R');
              $this->Cell(38,3,$dias,0,0,'L');
              $this->Ln();

            }
          }
        }
      }
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
$pdf->setFechaInicial($fecha_inicial);
$pdf->setFechaFinal($fecha_final);
$pdf->setFechaActual($fecha_act);
$pdf->setData($array_suc);
$pdf->setCliente($texto_cliente);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Historico_cartera_cliente_'.date('Y_m_d_H_i_s').'.pdf');


?>












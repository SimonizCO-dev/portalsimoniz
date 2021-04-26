<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$cons_inicial = $model['cons_inicial'];
$cons_final = $model['cons_final'];

/*inicio configuración array de datos*/

$logo_pansell = Yii::app()->getBaseUrl(true).'/images/logo_header.jpg';
$logo_pse_pansell = Yii::app()->getBaseUrl(true).'/images/pse_pansell.jpg';

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

$query ="SET NOCOUNT ON EXEC P_PR_FIN_CONS_DSTO_B2B 
@Num1 = ".$cons_inicial.",
@Num2 = ".$cons_final."
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_fac = array();

foreach ($query1 as $reg) {
  
  $Pedido = $reg['Pedido'];
  $Factura = $reg['Factura'];
  $Nit = $reg['Nit'];
  $Cliente = $reg['Cliente'];
  $Fecha_Factura = $reg['Fecha_Factura'];
  $Item = $reg['Item'];
  $Descripcion = $reg['Descripcion'];
  $Cantidad = $reg['Cantidad'];
  $Precio_Unit = $reg['Precio_Unit'];
  $Tasa_Iva = $reg['Tasa_Iva'];
  $Iva = $reg['Iva'];
  $Tasa_Ica = $reg['Tasa_Ica'];
  $Ica = $reg['Ica'];
  $Valor_Bruto = $reg['Valor_Bruto'];
  $Valor_Descuentos = $reg['Valor_Descuentos'];
  $Valor_Impuestos = $reg['Valor_Impuestos'];
  $Valor_Subtotal = $reg['Valor_Subtotal'];
  $Valor_Neto = $reg['Valor_Neto'];
  $VALOR_DCTO = $reg['VALOR_DCTO'];
  $IVA_DCTO = $reg['IVA_DCTO'];
  $TOTAL_DESCUENTO = $reg['TOTAL_DESCUENTO'];
  $CATEGORIA_ORACLE = $reg['CATEGORIA_ORACLE'];
  $Cedula = $reg['Cedula'];
  $Vendedor = $reg['Vendedor'];

  if(!array_key_exists($Factura, $array_fac)) {
    $array_fac[$Factura] = array();
    $array_fac[$Factura]['info'] = array();
    $array_fac[$Factura]['info']['fecha_factura'] = $Fecha_Factura;
    $array_fac[$Factura]['info']['nit'] = $Nit;
    $array_fac[$Factura]['info']['cliente'] = $Cliente;
    $array_fac[$Factura]['info']['ced_vendedor'] = $Cedula;
    $array_fac[$Factura]['info']['vendedor'] = $Vendedor;
    $array_fac[$Factura]['info']['valor_dcto'] = $VALOR_DCTO;
    $array_fac[$Factura]['info']['iva_dcto'] = $IVA_DCTO;
    $array_fac[$Factura]['info']['descuento_dcto'] = $TOTAL_DESCUENTO;

    $array_fac[$Factura]['items'][$Item] = array(
      'pedido' => $Pedido,
      'item' => $Item,
      'descripcion' => $Descripcion,
      'cantidad' => $Cantidad,
      'tasa_iva' => $Tasa_Iva,
      'tasa_ica' => $Tasa_Ica,
      'valor_subtotal' => $Valor_Subtotal,
      'valor_dcto' => $VALOR_DCTO,
      'iva_dcto' => $IVA_DCTO,
      'descuento_dcto' => $TOTAL_DESCUENTO,



    );
  }else{
    if(!array_key_exists($Item, $array_fac[$Factura]['items'])) {
      $array_fac[$Factura]['items'][$Item] = array(
        'pedido' => $Pedido,
        'item' => $Item,
        'descripcion' => $Descripcion,
        'cantidad' => $Cantidad,
        'tasa_iva' => $Tasa_Iva,
        'tasa_ica' => $Tasa_Ica,
        'valor_subtotal' => $Valor_Subtotal,
        'valor_dcto' => $VALOR_DCTO,
        'iva_dcto' => $IVA_DCTO,
        'descuento_dcto' => $TOTAL_DESCUENTO,
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

  function setData($data){
    $this->data = $data;
  }

  function setLogoPansell($logo_pansell){
    $this->logo_pansell = $logo_pansell;
  }

  function setLogoPsePansell($logo_pse_pansell){
    $this->logo_pse_pansell = $logo_pse_pansell;
  }

  function Header(){
    $this->Image($this->logo_pansell, 11, 10, 150, 30);
    $this->Ln(35);

  }

  function Tabla(){

    $array_fac = $this->data;

    $c_a = count($array_fac);

    $i = 1;

    if(!empty($array_fac)){

      foreach ($array_fac as $fac => $var_a) {

        $sum = 0;

        if($i > 1){
          $this->AddPage();   
        }

        $cli = $var_a['info']['cliente']; 
        $nit = $var_a['info']['nit'];
        $ced_vendedor = $var_a['info']['ced_vendedor'];
        $vendedor = $var_a['info']['vendedor'];
        $fecha_factura = UtilidadesVarias::textofecha($var_a['info']['fecha_factura']);
        $items = $var_a['items'];

        $this->SetFont('Arial','B',10);
        $this->Cell(100,5,utf8_decode('Señor(a)'),0,0,'L');
        $this->Ln();
        $this->Cell(100,5,utf8_decode($cli),0,0,'L');
        $this->Ln();
        $this->Cell(100,5,$nit,0,0,'L');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','B',9);
        $this->Cell(100,5,utf8_decode('N°. Factura / Fecha: '.$fac.' / '.$fecha_factura),0,0,'L');
        $this->Ln();
        $this->Cell(100,5,utf8_decode('Vendedor: '.$ced_vendedor.' - '.$vendedor),0,0,'L');
        $this->Ln(15);

        //table header
        $this->SetFont('Arial','B',7);
        $this->SetTextColor(255);
        $this->SetFillColor(160);
        $this->Cell(18,5,utf8_decode('N°. Ped.'),1,0,'C',TRUE);
        $this->Cell(60,5,utf8_decode('Item'),1,0,'C',TRUE);
        $this->Cell(10,5,utf8_decode('Cant.'),1,0,'C',TRUE);
        $this->Cell(14,5,utf8_decode('Tasa IVA'),1,0,'C',TRUE);
        $this->Cell(14,5,utf8_decode('Tasa ICA'),1,0,'C',TRUE);
        $this->Cell(20,5,utf8_decode('Vlr. Subtotal'),1,0,'C',TRUE);
        $this->Cell(16,5,utf8_decode('Subt. descto'),1,0,'C',TRUE);
        $this->Cell(16,5,utf8_decode('Total Imp.'),1,0,'C',TRUE);
        $this->Cell(16,5,utf8_decode('Total descto'),1,0,'C',TRUE);


        $this->Ln();

        $this->SetFont('Arial','',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);

        $sum_a = 0;
        $sum_b = 0;
        $sum_c = 0;
        $sum_d = 0;

        foreach ($items as $it => $var_b) {  

          $this->Cell(18,5,$var_b['pedido'],1,0,'L');
          $this->Cell(60,5,substr(utf8_decode($var_b['item'].' - '.$var_b['descripcion']),0, 45),1,0,'L');
          $this->Cell(10,5,number_format(($var_b['cantidad']),0,".",","),1,0,'R');
          $this->Cell(14,5,number_format(($var_b['tasa_iva']),2,".",","),1,0,'R');
          $this->Cell(14,5,number_format(($var_b['tasa_ica']),3,".",","),1,0,'R');
          $this->Cell(20,5,number_format(($var_b['valor_subtotal']),2,".",","),1,0,'R'); 
          $this->Cell(16,5,number_format(($var_b['valor_dcto']),2,".",","),1,0,'R');
          $this->Cell(16,5,number_format(($var_b['iva_dcto']),2,".",","),1,0,'R');
          $this->Cell(16,5,number_format(($var_b['descuento_dcto']),2,".",","),1,0,'R');
          $this->Ln();

          $sum_a = $sum_a + $var_b['valor_subtotal'];
          $sum_b = $sum_b + $var_b['valor_dcto'];
          $sum_c = $sum_c + $var_b['iva_dcto'];
          $sum_d = $sum_d + $var_b['descuento_dcto'];

        }

        //table footer
        $this->SetFont('Arial','B',6);
        $this->Cell(116,5,utf8_decode('TOTAL'),1,0,'R');
        $this->Cell(20,5,number_format(($sum_a),2,".",","),1,0,'R');
        $this->Cell(16,5,number_format(($sum_b),2,".",","),1,0,'R');
        $this->Cell(16,5,number_format(($sum_c),2,".",","),1,0,'R');
        $this->Cell(16,5,number_format(($sum_d),2,".",","),1,0,'R');
        $this->Ln();
        $this->Ln();

        $sum_a = 0;
        $sum_b = 0;
        $sum_c = 0;
        $sum_d = 0;

        $i++;
      
      }

      if($i != ($c_a + 1)){

        $this->AddPage();

      }
    
    }    

  }//fin tabla

}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act);
$pdf->setData($array_fac);
$pdf->setLogoPansell($logo_pansell);
$pdf->setLogoPsePansell($logo_pse_pansell);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 25);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Descuentos_B2B_'.date('Y_m_d_H_i_s').'.pdf');

?>


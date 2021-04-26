<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$consecutivo = $model['consecutivo'];
$logo_comstar = Yii::app()->getBaseUrl(true).'/images/logo_header_comstar.jpg';

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
  SET NOCOUNT ON
  EXEC P_PR_COM_COMP4_FVE
  @CONSECUTIVO = N'".$consecutivo."'
";

UtilidadesVarias::log($query);


$data = Yii::app()->db->createCommand($query)->queryAll();

$array_header = array();
$array_data = array();
$array_footer = array();

$nd = 0;

foreach ($data as $reg) {

  if($nd == 0){

    //HEADER

    //info de la cabecera
    $array_header['header_empresa'] = $reg['EMPRESA'];
    $array_header['header_nit_empresa'] = $reg['NIT_EMPRESA'];
    $array_header['header_descripcion_empresa'] = $reg['DESC_EMPRESA'];
    $array_header['header_direccion_empresa'] = $reg['DIR_EMPRESA'];
    $array_header['header_telefono_empresa'] = $reg['TEL_EMPRESA'];
    $array_header['header_ciudad_pais_empresa'] = $reg['CIU_EMPRESA'];
    $array_header['header_no_factura'] = $reg['NUM_FACTURA'];
    $array_header['header_texto1'] = $reg['NOTA1'];

    //info de cliente
    $array_header['cliente_razon_social'] = $reg['CLIENTE'];
    $array_header['cliente_nit'] = $reg['NIT_CLIENTE'];
    $array_header['cliente_direccion'] = $reg['DIR_CLIENTE'];
    $array_header['cliente_telefono'] = $reg['TEL_CLIENTE'];
    $array_header['cliente_codigo'] = $reg['COD_CLIENTE'];
    $array_header['cliente_referencia'] = $reg['REF_CLIENTE'];
    $array_header['cliente_orden'] = $reg['ORDEN'];
    $array_header['cliente_fecha_orden'] = $reg['FCH_ORDEN'];

    //info de envio
    $array_header['cliente_direccion_envio'] = $reg['DIR_ENVIO'];
    $array_header['cliente_telefono_envio'] = $reg['TEL_ENVIO'];

    //info de factura
    $array_header['factura_fecha_emision'] = $reg['FCH_FACTURA'];
    $array_header['factura_fecha_vencimiento'] = $reg['FCH_VENCIMIENTO'];
    $array_header['factura_condicion_pago'] = $reg['COND_PAGO'];
    $array_header['factura_referencia'] = $reg['REFERENCIA'];
    $array_header['header_texto2'] = $reg['NOTA2'];

    //FOOTER

    //totales

    $array_footer['subtotal'] = $reg['VLR_SUB_TOTAL'];
    $array_footer['iva'] = $reg['VLR_IVA'];
    $array_footer['rtfuente'] = $reg['VLR_RET_FUENTE'];
    $array_footer['rtiva'] = $reg['VLR_RET_IVA'];
    $array_footer['rtica'] = $reg['VLR_RET_ICA'];
    $array_footer['total'] = $reg['VLR_TOTAL'];
    $array_footer['trm'] = $reg['TRM'];

    $moneda = $reg['MON'];
    $total = $reg['VLR_TOTAL'];

    $query2 ="
      EXEC P_CF_CONF_NUM_A_LETRAS
        @Numero = ".$total.",
        @Mon = '".$moneda."'
    ";

    $valor_letras = Yii::app()->db->createCommand($query2)->queryRow();
    $vl = $valor_letras['tEXTO'];
    $array_footer['valor_letras'] = $vl;

    //info

    $array_footer['footer_texto1'] = $reg['NOTA3'];
    $array_footer['footer_texto2'] = $reg['NOTA4'];
    $array_footer['footer_texto3'] = $reg['NOTA5'];

  }

  //DETALLE FACTURA

  $array_data[] = array(
    'codigo' =>  $reg['COD_DETALLE'],
    'descripcion' =>  $reg['DESC_DETALLE'],
    'cantidad' =>  $reg['CANT_DETALLE'],
    'valor_unitario' =>  $reg['VLR_UNI_DETALLE'],
    'valor_total' =>  $reg['VLR_TOT_DETALLE']
  );

  $nd ++;
}

$num = count($array_data);

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setLogoComstar($logo_comstar){
    $this->logo_comstar = $logo_comstar;
  }

  function setDataHeader($header){
    $this->header = $header;
  }

  function setData($data){
    $this->data = $data;
  }

  function setDataFooter($footer){
    $this->footer = $footer;
  }

  function Header(){

    $data_header = $this->header;

    if(!empty($data_header)){

        $this->Image($this->logo_comstar, 10, 12, 40, 12);
        $this->Cell(61);
        $this->SetFont('Arial','B',8);
        $this->Cell(61,3,utf8_decode($data_header['header_empresa']),0,0,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(61,3,utf8_decode('FACTURA DE VENTA'),0,0,'R');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode($data_header['header_nit_empresa']),0,0,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(61,3,utf8_decode('N° '.$data_header['header_no_factura']),0,0,'R');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode($data_header['header_descripcion_empresa']),0,0,'C');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode($data_header['header_direccion_empresa']),0,0,'C');
        $this->SetFont('Arial','',6);
        $this->Cell(63,3,utf8_decode('Página '.$this->PageNo().' / {nb}'),0,0,'R');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode($data_header['header_telefono_empresa']),0,0,'C');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode($data_header['header_ciudad_pais_empresa']),0,0,'C');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',4);
        $this->MultiCell(185,3,utf8_decode($data_header['header_texto1']),0,'C');
        $this->Ln();
        $this->SetFont('Arial','B',8);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(93,5,utf8_decode('Datos de cliente'),1,0,'C',TRUE);
        $this->Cell(93,5,utf8_decode('Datos de envío'),1,0,'C',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Razón social:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_razon_social']),'TR',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Dirección:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_direccion_envio']),'TR',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Nit:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_nit']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Teléfono(s):'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_telefono_envio']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Dirección:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_direccion']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode(''),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode(''),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Teléfono(s):'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_telefono']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',8);
        $this->Cell(93,5,'Datos de factura',1,0,'C',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Código cliente:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_codigo']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Fecha de emisión:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['factura_fecha_emision']),'RT',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Referencia cliente:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_referencia']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Fecha de vencimiento:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['factura_fecha_vencimiento']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Orden:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_orden']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Condiciones de pago:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['factura_condicion_pago']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Fecha de orden:'),'LB',0,'L');
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_fecha_orden']),'RB',0,'L');
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode(''),'LB',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode(''),'RB',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Referencia:'),'LB',0,'L');
        $this->SetFont('Arial','',5);
        $this->Cell(156,5,utf8_decode($data_header['factura_referencia']),'RB',0,'L');
        $this->Ln();
        $this->SetFont('Arial','',5);
        $this->MultiCell(185,5,utf8_decode($data_header['header_texto2']),0,'C');

        //tabla
        $this->SetFont('Arial','B',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(26,5,utf8_decode('CÓDIGO'),1,0,'C',TRUE);
        $this->Cell(70,5,utf8_decode('DESCRIPCIÓN DE MERCANCIA'),1,0,'C',TRUE);
        $this->Cell(30,5,utf8_decode('CANT.'),1,0,'C',TRUE);
        $this->Cell(30,5,utf8_decode('VALOR UNIT.'),1,0,'C',TRUE);
        $this->Cell(30,5,utf8_decode('VALOR TOTAL'),1,0,'C',TRUE);
        $this->Ln();
    }
  }

  function Tabla(){

    $data = $this->data;
    $num_reg = count($data);

    //lineas en blanco antes de los totales
    $lineas_blanco = 20 - $num_reg;

    if(!empty($data)){

        foreach ($data as $reg) {
          
          $this->SetFont('Arial','',6); 
          $this->Cell(26,5,utf8_decode($reg['codigo']),'L',0,'C');
          $this->Cell(70,5,utf8_decode($reg['descripcion']),0,0,'L');
          $this->Cell(30,5,number_format(($reg['cantidad']),0,".",","),0,0,'R');
          $this->Cell(30,5,number_format(($reg['valor_unitario']),2,".",","),0,0,'R');
          $this->Cell(30,5,number_format(($reg['valor_total']),2,".",","),'R',0,'R');
          $this->Ln();

        }

        for ($i=1; $i <= $lineas_blanco ; $i++) {

          $this->SetFont('Arial','',6);
          $this->Cell(186,5,'','LR',0,'L');
          $this->Ln();
        
        }

        $data_tot = $this->footer;

        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);

        if($data_tot['trm'] == ""){
            $this->SetFont('Arial','B',6);
            $this->Cell(186,5,utf8_decode('TOTAL A PAGAR:'),'LR',0,'L',TRUE);
            $this->Ln();
            $this->SetFont('Arial','',7);
            $vl = preg_replace('/\s+/', ' ', $data_tot['valor_letras']);
            $this->MultiCell(186,5,utf8_decode($vl),'LRB','L');  
        }else{
            $this->SetFont('Arial','B',6);
            $this->Cell(186,5,utf8_decode('TOTAL A PAGAR:'),'LR',0,'L',TRUE);
            $this->Ln();
            $this->SetFont('Arial','',7);
            $vl = preg_replace('/\s+/', ' ', $data_tot['valor_letras']);
            $this->MultiCell(186,5,utf8_decode($vl),'LR','L');
            $this->MultiCell(186,5,utf8_decode($data_tot['trm']),'LRB','L');   
        }

        $this->SetFont('Arial','B',6);
        $this->Cell(116,5,utf8_decode('FIRMA Y SELLO DEL CLIENTE:'),'LT',0,'L',TRUE);
        $this->Cell(30,5,utf8_decode('SUB-TOTAL'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(40,5,number_format(($data_tot['subtotal']),2,".",","),'RT',0,'R',TRUE);
        $this->Ln();
        $this->Cell(116,5,utf8_decode(''),'L',0,'L',TRUE);
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('IVA'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(40,5,number_format(($data_tot['iva']),2,".",","),'R',0,'R',TRUE);
        $this->Ln();
        $this->SetFont('Arial','',7);
        $this->Cell(116,5,utf8_decode(''),'L',0,'L',TRUE);
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('RETENCIÓN EN LA FUENTE'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(40,5,number_format(($data_tot['rtfuente']),2,".",","),'R',0,'R',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(116,5,utf8_decode(''),'L',0,'L',TRUE);
        $this->Cell(30,5,utf8_decode('RETENCIÓN IVA'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(40,5,number_format(($data_tot['rtiva']),2,".",","),'R',0,'R',TRUE);
        $this->Ln();
        $this->Cell(116,5,utf8_decode(''),'L',0,'L',TRUE);
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('RETENCIÓN ICA'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(40,5,number_format(($data_tot['rtica']),2,".",","),'R',0,'R',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(116,5,utf8_decode('Día:                Mes:                Año:                '),'LRB',0,'R',TRUE);
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('TOTAL A PAGAR'),'LB',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(40,5,number_format(($data_tot['total']),2,".",","),'RB',0,'R',TRUE);
        $this->Ln();
    }

  }//fin tabla

  function Footer()
  {
      $data_footer = $this->footer;

      if(!empty($data_footer)){

          $this->SetY(-30);
          $this->SetFont('Arial','',5);
          //$this->Cell(185,3,utf8_decode($data_footer['footer_texto1']),0,0,'C');
          $this->MultiCell(185,2,utf8_decode($data_footer['footer_texto1']),0,'C'); 
          $this->Ln();
          //$this->Cell(185,3,utf8_decode($data_footer['footer_texto2']),0,0,'C');
          $this->MultiCell(185,2,utf8_decode($data_footer['footer_texto2']),0,'C'); 
          $this->Ln();
          //$this->Cell(185,3,utf8_decode($data_footer['footer_texto3']),0,0,'C');
          $this->MultiCell(185,2,utf8_decode($data_footer['footer_texto3']),0,'C'); 
          $this->Ln();
      }

  }
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setLogoComstar($logo_comstar);
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->setDataFooter($array_footer);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 40);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Factura_comstar_'.$consecutivo.'.pdf');

?>

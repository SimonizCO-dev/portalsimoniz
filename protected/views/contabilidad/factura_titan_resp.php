<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$consecutivo = $model['consecutivo'];
$logo_titan = Yii::app()->getBaseUrl(true).'/images/logo_header_titan.jpg';

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
  EXEC P_PR_COM_COMP3_FVE
  @CONSECUTIVO = ".$consecutivo."
";

UtilidadesVarias::log($query);

$data = Yii::app()->db->createCommand($query)->queryAll();

$array_header = array();
$array_data = array();
$array_footer = array();

$total_und = 0;
$total_caja = 0;
$nd = 0;

foreach ($data as $reg) {

  if($nd == 0){

    //HEADER

    //info de la cabecera
    $array_header['header_empresa'] = $reg['EMPRESA'];
    $array_header['header_nit_empresa'] = $reg['NIT_EMPRESA'];
    $array_header['header_direccion_empresa'] = $reg['DIR_EMPRESA'];
    $array_header['header_telefono_empresa'] = $reg['TEL_EMPRESA'];
    $array_header['header_aa_empresa'] = $reg['AA'];
    $array_header['header_ciudad_pais_empresa'] = $reg['CIU_EMPRESA'];
    $array_header['header_email_empresa'] = $reg['EMAIL'];
    $array_header['header_descripcion'] = $reg['DESC_ENCABEZADO'];
    $array_header['header_no_factura'] = $reg['NUM_FACTURA'];

    //info de cliente
    $array_header['cliente_razon_social'] = $reg['CLIENTE'];
    $array_header['cliente_nit'] = $reg['NIT_CLIENTE'];
    $array_header['cliente_direccion'] = $reg['DIR_CLIENTE'];
    $array_header['cliente_telefono'] = $reg['TEL_CLIENTE'];
    $array_header['ciudad_pais_cliente'] = $reg['CIU_CLIENTE'];
    $array_header['cliente_fecha_factura'] = $reg['FCH_FACTURA'];
    $array_header['cliente_vendedor'] = $reg['VENDEDOR'];

    //info de envio
    $array_header['cliente_envio'] = $reg['ENVIO'];
    $array_header['cliente_direccion_envio'] = $reg['DIR_ENVIO'];
    $array_header['cliente_zona'] = $reg['ZONA'];
    $array_header['cliente_telefono_envio'] = $reg['TEL_ENVIO'];
    $array_header['cliente_ciudad_pais_envio'] = $reg['CIU_ENVIO'];
    $array_header['factura_fecha_vencimiento'] = date("d/m/Y", strtotime($reg['FECH_VCTO']));
    $array_header['factura_pedido'] = $reg['PEDIDO'];

    $array_header['factura_notas'] = $reg['NOTAS'];
    
    $array_footer['vlr_bruto'] = $reg['VLR_BRUTO'];
    $array_footer['vlr_iva_reg'] = $reg['VLR_IVA_REG'];
    $array_footer['vlr_total'] = $reg['VLR_TOTAL'];

    $array_footer['rtc'] = $reg['RTC'];
    $array_footer['desc_tr1'] = $reg['DESC_TR1'];
    $array_footer['vlr_ica_base'] = $reg['VLR_ICA_BASE'];
    $array_footer['vlr_ica_tasa'] = $reg['VLR_ICA_TASA'];
    $array_footer['vlr_ica_ret'] = $reg['VLR_ICA_RET'];

    $array_footer['rti'] = $reg['RTI'];
    $array_footer['desc_tr2'] = $reg['DESC_TR2'];
    $array_footer['vlr_iva_base'] = $reg['VLR_IVA_BASE'];
    $array_footer['vlr_iva_tasa'] = $reg['VLR_IVA_TASA'];
    $array_footer['vlr_iva_ret'] = $reg['VLR_IVA_RET'];

    $moneda = 'COP';
    $total = number_format(($reg['VLR_TOTAL']),0,".","");

    $query2 ="
      EXEC P_CF_CONF_NUM_A_LETRAS
        @Numero = ".$total.",
        @Mon = '".$moneda."'
    ";

    $valor_letras = Yii::app()->db->createCommand($query2)->queryRow();
    $vl = $valor_letras['tEXTO'];
    $array_footer['valor_letras'] = $vl;

    //info
    
    $array_footer['footer_texto1'] = $reg['NOTA1'];
    $array_footer['footer_texto2'] = $reg['NOTA2'];
    $array_footer['footer_texto3'] = $reg['NOTA3'];

  }

  //DETALLE FACTURA

  $total_und = $total_und + $reg['CANT_DETALLE'];
  $total_caja = $total_caja + $reg['CAJAS'];


  $array_data[] = array(
    
    'descripcion' =>  $reg['DESC_DETALLE'],
    'cantidad' =>  $reg['CANT_DETALLE'],
    'cajas' =>  $reg['CAJAS'],
    'codigo' =>  $reg['COD_DETALLE'],
    'porc_iva' =>  $reg['PORC_IVA_DETALLE'],
    'valor_unitario' =>  $reg['VLR_UNI_DETALLE'],
    'valor_iva' =>  $reg['VLR_IVA_DETALLE'],
    'valor_total' =>  $reg['VLR_SUB_TOTAL']
  );
  
  $nd ++;
  
}

$array_footer['total_und'] = $total_und;
$array_footer['total_caja'] = $total_caja;


/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setLogoTitan($logo_titan){
    $this->logo_titan = $logo_titan;
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

        $this->Image($this->logo_titan, 10, 10, 40, 10);
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
        $this->Cell(61,3,utf8_decode($data_header['header_direccion_empresa'].' - CONMUTADOR '.$data_header['header_telefono_empresa'].' - AA '.$data_header['header_aa_empresa']),0,0,'C');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode($data_header['header_ciudad_pais_empresa'].' - E-MAIL '.$data_header['header_email_empresa']),0,0,'C');
        $this->SetFont('Arial','',6);
        $this->Cell(63,3,utf8_decode('Página '.$this->PageNo().' / {nb}'),0,0,'R');
        $this->Ln();
        $this->Ln();
        $this->SetFont('Arial','',5);
        $this->MultiCell(185,2,utf8_decode($data_header['header_descripcion']),0,'J');
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
        $this->Cell(30,5,utf8_decode('Envío:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_envio']),'TR',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Nit:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_nit']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Dirección'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_direccion_envio']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Dirección:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_direccion']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Zona:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_zona']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Teléfono(s):'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_telefono']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Teléfono(s):'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_telefono_envio']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Ciudad - País:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['ciudad_pais_cliente']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Ciudad - País:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_ciudad_pais_envio']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Fecha de factura:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_fecha_factura']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Fecha vencimiento:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['factura_fecha_vencimiento']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Vendedor:'),'LB',0,'L');
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['cliente_vendedor']),'RB',0,'L');
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Pedido N°:'),'LB',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(63,5,utf8_decode($data_header['factura_pedido']),'RB',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',7);
        $this->Cell(30,5,utf8_decode('Referencia:'),'LB',0,'L');
        $this->SetFont('Arial','',5);
        $this->Cell(156,5,utf8_decode($data_header['factura_notas']),'RB',0,'L');
        $this->Ln();
        $this->SetFont('Arial','',5);
        $this->MultiCell(185,5,utf8_decode(''),0,'C');

        //tabla
        $this->SetFont('Arial','B',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(70,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',TRUE);
        $this->Cell(15,5,utf8_decode('CANT.'),1,0,'C',TRUE);
        $this->Cell(13,5,utf8_decode('CAJAS'),1,0,'C',TRUE);
        $this->Cell(13,5,utf8_decode('CÓDIGO'),1,0,'C',TRUE);
        $this->Cell(15,5,utf8_decode('% IVA'),1,0,'C',TRUE);
        $this->Cell(20,5,utf8_decode('VALOR UNIT.'),1,0,'C',TRUE);
        $this->Cell(20,5,utf8_decode('VALOR IVA'),1,0,'C',TRUE);
        $this->Cell(20,5,utf8_decode('VALOR TOTAL'),1,0,'C',TRUE);
        $this->Ln();

    }

  }

  function Tabla(){

    $data = $this->data;
    $num_reg = count($data);

    //lineas en blanco antes de los totales
    $lineas_blanco = 24 - $num_reg;

    if(!empty($data)){

        foreach ($data as $reg) {
          
          $this->SetFont('Arial','',6); 
          $this->Cell(70,5,utf8_decode($reg['descripcion']),'L',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),1,".",","),0,0,'R');
          $this->Cell(13,5,number_format(($reg['cajas']),1,".",","),0,0,'R');
          $this->Cell(13,5,utf8_decode($reg['codigo']),0,0,'L');
          $this->Cell(15,5,number_format(($reg['porc_iva']),2,".",","),0,0,'R');
          $this->Cell(20,5,number_format(($reg['valor_unitario']),0,".",","),0,0,'R');
          $this->Cell(20,5,number_format(($reg['valor_iva']),0,".",","),0,0,'R');
          $this->Cell(20,5,number_format(($reg['valor_total']),0,".",","),'R',0,'R');
          $this->Ln();

        }

        for ($i=1; $i <= $lineas_blanco ; $i++) {

          $this->SetFont('Arial','',6);
          $this->Cell(186,5,'','LR',0,'L');
          $this->Ln();
        
        }

    }

  }//fin tabla

  function Footer()
  {
    $data_footer = $this->footer;

    if(!empty($data_footer)){

        $this->SetY(-80);
        
        $data_tot = $this->footer;

        $this->SetFont('Arial','B',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(37,5,utf8_decode('TOTAL UND'),'LT',0,'C',TRUE);
        $this->Cell(37,5,utf8_decode('TOTAL CAJA'),'LT',0,'C',TRUE);
        $this->Cell(37,5,utf8_decode('VALOR BRUTO'),'LT',0,'C',TRUE);
        $this->Cell(37,5,utf8_decode('IVA REG. COMÚN'),'LT',0,'C',TRUE);
        $this->Cell(38,5,utf8_decode('TOTAL A PAGAR'),'LRT',0,'C',TRUE);
        $this->Ln();
        $this->SetFont('Arial','',6); 
        $this->Cell(37,5,number_format(($data_tot['total_und']),2,".",","),'LT',0,'R');
        $this->Cell(37,5,number_format(($data_tot['total_caja']),2,".",","),'T',0,'R');
        $this->Cell(37,5,number_format(($data_tot['vlr_bruto']),0,".",","),'T',0,'R');
        $this->Cell(37,5,number_format(($data_tot['vlr_iva_reg']),0,".",","),'T',0,'R');
        $this->Cell(38,5,number_format(($data_tot['vlr_total']),0,".",","),'RT',0,'R');
        $this->Ln();

        $this->SetFont('Arial','B',6); 
        $this->Cell(20,8,utf8_decode('RETENCIÓN:'),'LT',0,'L');
        $this->SetFont('Arial','',6); 
        $this->Cell(20,4,utf8_decode($data_tot['rtc']),'T',0,'L');
        $this->Cell(56,4,utf8_decode($data_tot['desc_tr1']),'T',0,'L');
        $this->Cell(30,4,number_format(($data_tot['vlr_ica_base']),0,".",","),'T',0,'R');
        $this->Cell(30,4,number_format(($data_tot['vlr_ica_tasa']),2,".",","),'T',0,'R');
        $this->Cell(30,4,number_format(($data_tot['vlr_ica_ret']),0,".",","),'RT',1,'R');

        $this->Cell(20,4,'',0,0);
        $this->Cell(20,4,utf8_decode($data_tot['rti']),'',0,'L');
        $this->Cell(56,4,utf8_decode($data_tot['desc_tr2']),'',0,'L');
        $this->Cell(30,4,number_format(($data_tot['vlr_iva_base']),0,".",","),'',0,'R');
        $this->Cell(30,4,number_format(($data_tot['vlr_iva_tasa']),2,".",","),'',0,'R');
        $this->Cell(30,4,number_format(($data_tot['vlr_iva_ret']),0,".",","),'R',1,'R');

        $this->SetFont('Arial','B',6); 
        $this->Cell(20,5,utf8_decode('SON:'),'LT',0,'L');
        $this->SetFont('Arial','',6); 
        $this->Cell(166,5,utf8_decode($data_tot['valor_letras']),'RT',0,'L');
        $this->Ln();
        $this->SetFont('Arial','',5);
        $this->MultiCell(186,3,utf8_decode($data_tot['footer_texto1']),'RLT','L');
        $this->SetFont('Arial','B',5);
        $this->MultiCell(186,3,utf8_decode($data_tot['footer_texto2']),'RLT','C');
        $this->SetFont('Arial','B',5);
        $this->MultiCell(186,3,utf8_decode($data_tot['footer_texto3']),'RLT','C');

        $this->SetFont('Arial','',4);
        $this->Cell(93,3,utf8_decode('REVISO:'),'LT',0,'L');
        $this->Cell(93,3,utf8_decode('EL COMPRADOR ACEPTA Y DECLARA HABER RECIBIDO REAL Y MATERIALMENTE LAS MERCANCIAS DESCRITAS EN'),'LRT',0,'L');
        $this->Ln();
        $this->Cell(93,3,'','L',0,'L');
        $this->Cell(93,3,utf8_decode('EL MISMO TITULO VALOR Y SE OBLIGA A PAGAR EL PRECIO EN LA FORMA PACTADA AQUÍ MISMO'),'LR',0,'L');
        $this->Ln();
        $this->Cell(93,3,'','L',0,'L');
        $this->Cell(93,3,'','LR',0,'L');
        $this->Ln();
        $this->Cell(93,3,'','L',0,'L');
        $this->SetFont('Arial','',10);
        $this->Cell(93,3,utf8_decode('ACEPTADA'),'LR',0,'C');
        $this->Ln();
        $this->Cell(93,3,'','L',0,'L');
        $this->Cell(93,3,'','LR',0,'L');
        $this->Ln();
        $this->SetFont('Arial','',4);
        $this->Cell(93,3,utf8_decode('FIRMA Y SELLO:'),'LB',0,'L');
        $this->Cell(93,3,utf8_decode('FIRMA AUTORIZADA POR EL TITULAR DE LA CUENTA Y SELLO, C.C. O NIT.'),'LRB',0,'L');

    }

  }
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setLogoTitan($logo_titan);
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->setDataFooter($array_footer);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 75);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Factura_titan_'.$consecutivo.'.pdf');

?>

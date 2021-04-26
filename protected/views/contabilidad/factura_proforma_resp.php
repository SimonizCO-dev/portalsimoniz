<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$co = $model['c_o'];
$tipo = $model['tipo'];
$consecutivo = $model['consecutivo'];
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
  SET NOCOUNT ON
  EXEC P_PR_COM_COMP2_FEV
  @CO = '".$co."',
  @DOCTO = '".$tipo."',
  @NUM = ".$consecutivo."
";

UtilidadesVarias::log($query);

$data = Yii::app()->db->createCommand($query)->queryAll();

$array_header = array();
$array_data = array();
$array_footer = array();

$nd = 0;

$total_unds = 0;
$total_cajas = 0;

foreach ($data as $reg) {

  if($nd == 0){

    //HEADER

    //info de la cabecera   
    $array_header['header_direccion_empresa'] = $reg['Direccion1'];
    $array_header['header_n_unico'] = $reg['N_Unico'];
    $array_header['header_telefono_empresa'] = $reg['Telefono1'];
    $array_header['header_fax_empresa'] = $reg['Fax1'];
    $array_header['header_email_ventas_empresa'] = $reg['Email1_1'];
    $array_header['header_email_sac_empresa'] = $reg['Email1_2'];
    $array_header['header_no_factura'] = $reg['Secuencial1'];

    //info de cliente
    $array_header['cliente_razon_social'] = $reg['Razon_Social2'];
    $array_header['cliente_nit'] = $reg['Identificacion2'];
    $array_header['cliente_direccion'] = $reg['Direccion2'];
    $array_header['cliente_telefono'] = $reg['Telefono'].' - '.$reg['Celular'];
    $array_header['ciudad_pais_cliente'] = $reg['Ciudad2'].', '.$reg['Departamento2'];

    //info de envio
    $array_header['cliente_direccion_envio'] = $reg['Direccion3'];
    $array_header['cliente_ciudad_pais_envio'] = $reg['Ciudad3'].', '.$reg['Departamento3'];
    $array_header['factura_vendedor'] = $reg['Nombre_Vendedor'];

    //info de factura
    $array_header['factura_fecha_emision'] = $reg['Fecha_Emision'];
    $array_header['factura_fecha_vcto'] = $reg['Fecha_VCTO'];
    $array_header['factura_pedido'] = $reg['Numero_Pedido'];

    $array_footer['subtotal'] = $reg['Subtotal_Factura'];
    $array_footer['iva'] = $reg['Total_Impuesto'];
    $array_footer['total'] = $reg['Total_Factura'];

    $moneda = $reg['Moneda'];
    $total = $reg['Total_Factura'];

    $query2 ="
      EXEC P_CF_CONF_NUM_A_LETRAS
        @Numero = ".$total.",
        @Mon = '".$moneda."'
    ";

    $valor_letras = Yii::app()->db->createCommand($query2)->queryRow();
    $vl = $valor_letras['tEXTO'];
    $array_footer['valor_letras'] = $vl;

  }

  //DETALLE FACTURA

  $array_data[] = array(
    'codigo' =>  $reg['Codigo_Producto'],
    'descripcion' =>  $reg['Descripcion_Producto'],
    'cantidad' =>  $reg['Cantidad'],
    'cajas' =>  $reg['Cajas'],
    'porc_iva' =>  $reg['Porcentaje_IVA'],
    'valor_unitario' =>  $reg['Precio_Unitario'],
    'valor_iva' =>  $reg['Valor_IVA'],
    'valor_total' =>  $reg['Valor_Total_Item']
  );

  $total_unds = $total_unds + $reg['Cantidad'];
  $total_cajas = $total_cajas + $reg['Cajas'];

  $nd ++;

}

$array_footer['total_unds'] = $total_unds;
$array_footer['total_cajas'] = $total_cajas;

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setLogoPansell($logo_pansell){
    $this->logo_pansell = $logo_pansell;
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

        $this->Image($this->logo_pansell, 11, 8, 150, 30);
        $this->Cell(186,10,'',0,0,'C');
        $this->Ln();
        $this->Cell(145);
        $this->SetFont('Arial','B',10);
        $this->Cell(40,5,utf8_decode('FACTURA PROFORMA'),'LRT',0,'C');
        $this->Ln();
        $this->Cell(145);
        $this->SetFont('Arial','B',10);
        $this->Cell(40,3,utf8_decode('N° PNS - '.$data_header['header_no_factura']),'LR',0,'C');
        $this->Ln();
        $this->Cell(145);
        $this->SetFont('Arial','',10);
        $this->Cell(40,3,'','LR',0,'L');
        $this->Ln();
        $this->Cell(145);
        $this->SetFont('Arial','',6);
        $this->Cell(40,3,utf8_decode('Página '.$this->PageNo().' / {nb}'),'LRB',0,'C');
        $this->Ln(8);

        $this->SetFont('Arial','B',6);
        $this->MultiCell(185,3,utf8_decode('DIRECCIÓN: '.$data_header['header_direccion_empresa']),0,'C');
        $this->Ln(0);
        $this->MultiCell(185,3,utf8_decode('N° UNICO: '.$data_header['header_n_unico'].' - TEL.: '.$data_header['header_telefono_empresa'].' - FAX: '.$data_header['header_fax_empresa']),0,'C');
        $this->Ln(0);
        $this->MultiCell(185,3,utf8_decode('E-MAIL(S): VENTAS '.$data_header['header_email_ventas_empresa'].' - SAC '.$data_header['header_email_sac_empresa']),0,'C');
        $this->Ln(5
        );

        $this->SetFont('Arial','B',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(186,5,utf8_decode('DATOS DE CLIENTE'),1,0,'C',TRUE);;
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('NIT - RAZÓN SOCIAL:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(156,5,utf8_decode($data_header['cliente_nit'].' - '.$data_header['cliente_razon_social']),'TR',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('DIRECCIÓN:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(156,5,utf8_decode($data_header['cliente_direccion']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('TELÉFONO(S):'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(156,5,utf8_decode($data_header['cliente_telefono']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('CIUDAD:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(156,5,utf8_decode($data_header['ciudad_pais_cliente']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(118,5,utf8_decode('DATOS DE ENVIO'),'LT',0,'C',TRUE);
        $this->Cell(68,5,'DATOS DE FACTURA','LRT',0,'C',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('DIRECCIÓN:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(88,5,utf8_decode($data_header['cliente_direccion_envio']),'RT',0,'L',TRUE);
        $this->SetFont('Arial','B',6);
        $this->Cell(35,5,utf8_decode('FECHA DE FACTURA:'),'LT',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(33,5,utf8_decode($data_header['factura_fecha_emision']),'RT',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('CIUDAD:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(88,5,utf8_decode($data_header['ciudad_pais_cliente']),'R',0,'L',TRUE);
        $this->SetFont('Arial','B',6);
        $this->Cell(35,5,utf8_decode('FECHA DE VENCIMIENTO:'),'L',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(33,5,utf8_decode($data_header['factura_fecha_vcto']),'R',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',6);
        $this->Cell(30,5,utf8_decode('VENDEDOR:'),'LB',0,'L');
        $this->SetFont('Arial','',6);
        $this->Cell(88,5,utf8_decode($data_header['factura_vendedor']),'RB',0,'L');
        $this->SetFont('Arial','B',6);
        $this->Cell(35,5,utf8_decode('PEDIDO:'),'LB',0,'L',TRUE);
        $this->SetFont('Arial','',6);
        $this->Cell(33,5,utf8_decode($data_header['factura_pedido']),'RB',0,'L',TRUE);
        $this->Ln();
        $this->Ln();

        //tabla
        $this->SetFont('Arial','B',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(14,5,utf8_decode('CÓDIGO'),1,0,'C',TRUE);
        $this->Cell(70,5,utf8_decode('DESCRIPCIÓN DE MERCANCIA'),1,0,'C',TRUE);
        $this->Cell(17,5,utf8_decode('CANT.'),1,0,'C',TRUE);
        $this->Cell(17,5,utf8_decode('CAJAS'),1,0,'C',TRUE);
        $this->Cell(17,5,utf8_decode('% IVA'),1,0,'C',TRUE);
        $this->Cell(17,5,utf8_decode('VLR. UNIT.'),1,0,'C',TRUE);
        $this->Cell(17,5,utf8_decode('VLR. IVA'),1,0,'C',TRUE);
        $this->Cell(17,5,utf8_decode('VLR. TOTAL'),1,0,'C',TRUE);
        $this->Ln();

    }

  }

  function Tabla(){

    $data = $this->data;
    $num_reg = count($data);

    //lineas en blanco antes de los totales
    $lineas_blanco = 28 - $num_reg;

    if(!empty($data)){

        foreach ($data as $reg) {
          
          $this->SetFont('Arial','',6); 
          $this->Cell(14,5,utf8_decode($reg['codigo']),'L',0,'C');
          $this->Cell(70,5,utf8_decode($reg['descripcion']),0,0,'L');
          $this->Cell(17,5,number_format(($reg['cantidad']),0,".",","),0,0,'R');
          $this->Cell(17,5,number_format(($reg['cajas']),2,".",","),0,0,'R');
          $this->Cell(17,5,number_format(($reg['porc_iva']),0,".",","),0,0,'R');
          $this->Cell(17,5,number_format(($reg['valor_unitario']),2,".",","),0,0,'R');
          $this->Cell(17,5,number_format(($reg['valor_iva']),2,".",","),0,0,'R');
          $this->Cell(17,5,number_format(($reg['valor_total']),2,".",","),'R',0,'R');
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

        $this->SetFont('Arial','B',6);
        $this->Cell(35,5,utf8_decode('TOTAL UNIDADES'),'LT',0,'C',TRUE);
        $this->Cell(35,5,utf8_decode('TOTAL CAJAS'),'T',0,'C',TRUE);
        $this->Cell(35,5,utf8_decode('VALOR BRUTO'),'T',0,'C',TRUE);
        $this->Cell(40,5,utf8_decode('IVA REG. COMUN'),'T',0,'C',TRUE);
        $this->Cell(41,5,utf8_decode('TOTAL A PAGAR'),'RT',0,'C',TRUE);
        $this->Ln(3);

        $this->Cell(35,5,number_format(($data_tot['total_unds']),0,".",","),'L',0,'C');
        $this->Cell(35,5,number_format(($data_tot['total_cajas']),0,".",","),0,0,'C');
        $this->Cell(35,5,number_format(($data_tot['subtotal']),2,".",","),0,0,'C');
        $this->Cell(40,5,number_format(($data_tot['iva']),2,".",","),0,0,'C');
        $this->Cell(41,5,number_format(($data_tot['total']),2,".",","),'R',0,'C');
        $this->Ln();

        $this->SetFont('Arial','B',6);
        $vl = preg_replace('/\s+/', ' ', $data_tot['valor_letras']);
        $this->MultiCell(186,5,utf8_decode('SON: '.$vl),'LRTB','L');   
    }

  }//fin tabla
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setLogoPansell($logo_pansell);
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->setDataFooter($array_footer);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 30);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Factura_proforma_'.$consecutivo.'.pdf');

?>

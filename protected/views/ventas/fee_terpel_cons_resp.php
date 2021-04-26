<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

$logo_simoniz = Yii::app()->getBaseUrl(true).'/images/logo_header_simoniz.jpg';
$logo_terpel = Yii::app()->getBaseUrl(true).'/images/logo_header_terpel.jpg';

$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

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
  EXEC P_PR_COM_INF_FEE_TERPEL 
  @FECHA1 = N'".$FechaM1."',
  @FECHA2 = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

$data = Yii::app()->db->createCommand($query)->queryAll();

$array_header = array();
$array_data = array();

$array_header['texto1'] = 'RESUMEN DE FACTURACIÓN FEE SIMONIZ SA';
$array_header['texto2'] = 'Organización Terpel S.A.';
$array_header['texto3'] = 'Corte:  '.UtilidadesVarias::textofecha($fecha_inicial).' - '.UtilidadesVarias::textofecha($fecha_final);

foreach ($data as $reg) {

  //DETALLE DOCTO

  $array_data[] = array(  
    'concepto' =>  $reg['TIPO_EDS'],
    'vlr_venta' =>  $reg['VLR_VENTA'],
    'vlr_fact_ant_iva' =>  $reg['VLR_FACT_ANT_IVA'],
    'vlr_total' =>  $reg['VLR_TOTAL'], 
  );

}

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setLogoSimoniz($logo_simoniz){
    $this->logo_simoniz = $logo_simoniz;
  }

  function setLogoTerpel($logo_terpel){
    $this->logo_terpel = $logo_terpel;
  }

  function setDataHeader($header){
    $this->header = $header;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){

    $data_header = $this->header;

    if(!empty($data_header)){

        $this->Ln(10);
        $this->Image($this->logo_simoniz, 12, 18, 60, 18);
        $this->Image($this->logo_terpel, 165, 18, 20, 20);
        $this->Cell(62);
        $this->SetFont('Arial','B',11);
        $this->Cell(62,5,utf8_decode($data_header['texto1']),0,0,'C');
        $this->Ln();
        $this->Cell(62);
        $this->SetFont('Arial','B',10);
        $this->Cell(62,5,utf8_decode($data_header['texto2']),0,0,'C');
        $this->Ln();
        $this->Cell(62);
        $this->SetFont('Arial','',8);
        $this->Cell(62,5,utf8_decode($data_header['texto3']),0,0,'C');
        $this->Ln();
        $this->Ln(10);

        //tabla
        $this->SetFont('Arial','B',6);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(60,5,utf8_decode('CONCEPTO'),1,0,'C',TRUE);
        $this->Cell(42,5,utf8_decode('VLR. DE LA VENTA'),1,0,'C',TRUE);
        $this->Cell(42,5,utf8_decode('VLR. A FACTURAR ANTES DE IVA'),1,0,'C',TRUE);
        $this->Cell(42,5,utf8_decode('VLR. TOTAL'),1,0,'C',TRUE);
        $this->Ln();

    }

  }

  function Tabla(){

    $data = $this->data;

    $tvv = 0;
    $tvfai = 0;
    $tvt = 0;

    if(!empty($data)){

      foreach ($data as $reg) {
          
        $this->SetFont('Arial','',7); 
        $this->Cell(60,5,utf8_decode($reg['concepto']),'L',0,'L');
        $this->Cell(42,5,number_format(($reg['vlr_venta']),2,".",","),0,0,'R');
        $this->Cell(42,5,number_format(($reg['vlr_fact_ant_iva']),2,".",","),0,0,'R');
        $this->Cell(42,5,number_format(($reg['vlr_total']),2,".",","),'R',0,'R');
        $this->Ln();

        $tvv = $tvv + $reg['vlr_venta'];
        $tvfai = $tvfai + $reg['vlr_fact_ant_iva'];
        $tvt = $tvt + $reg['vlr_total'];

      }

      $this->SetFont('Arial','B',7); 
      $this->Cell(60,5,utf8_decode('TOTAL FACTURACIÓN'),'LTB',0,'L');
      $this->Cell(42,5,number_format(($tvv),2,".",","),'TB',0,'R');
      $this->Cell(42,5,number_format(($tvfai),2,".",","),'TB',0,'R');
      $this->Cell(42,5,number_format(($tvt),2,".",","),'RTB',0,'R');
      $this->Ln();

    }

  }//fin tabla
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setLogoSimoniz($logo_simoniz);
$pdf->setLogoTerpel($logo_terpel);
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 60);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','FEE_'.$FechaM1.'_'.$FechaM2.'.pdf');

?>

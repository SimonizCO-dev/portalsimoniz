<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

/*Modelo cabecera*/

$array_header = array();
$modelo_wip = Wip::model()->findByPk($id);

if(is_null($modelo_wip->OBSERVACIONES)){
  $obs = '-';
}else{
  $obs = $modelo_wip->OBSERVACIONES;
}

//info de la cabecera
$array_header = array();
$array_header['wip'] = $modelo_wip->WIP;
$array_header['fecha_solicitud'] = UtilidadesVarias::textofecha($modelo_wip->FECHA_SOLICITUD_WIP);
$array_header['fecha_entrega'] = UtilidadesVarias::textofecha($modelo_wip->FECHA_ENTREGA_WIP);
$array_header['destino'] = $modelo_wip->desccadena($modelo_wip->ID);
$array_header['obs'] = $obs;
$array_header['empresa_nit'] = 'SIMONIZ S.A / 800.203.984-6';
$array_header['direccion'] = 'CRA. 127 # 15B - 60 BOD. 4, 5 y 6, BOGOTÁ D.C. - COLOMBIA';
$array_header['telefono'] = '(571) 422 06 10 / 418 46 18 / 4220560';
$array_header['logo'] = Yii::app()->getBaseUrl(true).'/images/logo_header_simoniz.jpg';

/*Modelo detalle*/

$array_data = array();
$modelo_det = Wip::model()->findAllByAttributes(array('WIP' => $modelo_wip->WIP));

//info de detalle

foreach ($modelo_det as $det) {

  $array_data[] = array(
    'item' =>  $det->ID_ITEM,
    'descripcion' => $det->DESCRIPCION,
    'cantidad' =>  $det->CANT_A_ARMAR,
    'inv' =>  $det->INVENTARIO_TOTAL,
    'estado' =>  $det->ESTADO_COMERCIAL,
  );
  
}

//PDF

class PDF extends FPDF{

  function setDataHeader($header){
    $this->header = $header;
  }

  function setData($data){
    $this->data = $data;
  }

  function setFirma($firma){
    $this->firma = $firma;
  }

  function setCargo($cargo){
    $this->cargo = $cargo;
  }

  function Header(){
    
    $data_header = $this->header;

    if(!empty($data_header)){

      $this->Image($data_header['logo'], 10, 8, 44, 14);
      $this->Cell(61);
      $this->SetFont('Arial','B',8);
      $this->Cell(61,3,utf8_decode('ORDEN DE PRODUCCIÓN PROMOCIONES OPP'),0,0,'C');
      $this->SetFont('Arial','B',10);
      $this->Cell(61,3,utf8_decode(''),0,0,'R');
      $this->Ln();
      $this->Cell(61);
      $this->SetFont('Arial','B',6);
      $this->Cell(61,3,utf8_decode($data_header['empresa_nit']),0,0,'C');
      $this->SetFont('Arial','B',10);
      $this->Cell(61,3,utf8_decode($data_header['wip']),0,0,'R');
      $this->Ln();
      $this->Cell(61);
      $this->SetFont('Arial','B',6);
      $this->Cell(61,3,utf8_decode($data_header['direccion']),0,0,'C');
      $this->Ln();
      $this->Cell(61);
      $this->SetFont('Arial','B',6);
      $this->Cell(61,3,utf8_decode($data_header['telefono']),0,0,'C');
      $this->SetFont('Arial','',6);
      $this->Cell(63,3,utf8_decode('Página '.$this->PageNo().' / {nb}'),0,0,'R');
      $this->Ln();
      $this->Ln();
      $this->Ln();
      $this->Ln();

      $this->SetFillColor(255,255,255);
      $this->SetDrawColor(0,0,0);
      $this->SetTextColor(0);
      $this->SetFont('Arial','B',8);
      $this->Cell(30,5,utf8_decode('Fecha de solicitud:'),'',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(73,5,utf8_decode($data_header['fecha_solicitud']),'',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(30,5,utf8_decode('Fecha de entrega:'),'',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(73,5,utf8_decode($data_header['fecha_entrega']),'',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(30,5,utf8_decode('Destino:'),'',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(73,5,utf8_decode($data_header['destino']),'',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(30,5,utf8_decode('Observaciones:'),'',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(73,5,utf8_decode($data_header['obs']),'',0,'L',TRUE);
      $this->Ln();
      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','B',7);
      $this->SetFillColor(255,255,255);
      $this->SetDrawColor(0,0,0);
      $this->SetTextColor(0);
      $this->Cell(16,5,utf8_decode('Item'),1,0,'C',TRUE);
      $this->Cell(90,5,utf8_decode('Descripción'),1,0,'C',TRUE);
      $this->Cell(25,5,utf8_decode('Cant. solicitada'),1,0,'C',TRUE);
      $this->Cell(25,5,utf8_decode('Inv. a la fecha'),1,0,'C',TRUE);
      $this->Cell(30,5,utf8_decode('Estado de item'),1,0,'C',TRUE);
      $this->Ln();

    }

  }

  function Tabla(){

    $data = $this->data;

    $data_header = $this->header;

    $num_reg = count($data);

    if(!empty($data)){

      $cont = 1;

      foreach ($data as $reg) {

        if($num_reg  == $cont){
          $this->SetFont('Arial','',7);
          $this->Cell(16,5,substr(utf8_decode($reg['item']),0, 20),'LRB',0,'L');
          $this->Cell(90,5,substr(utf8_decode($reg['descripcion']),0, 70),'RB',0,'L');
          $this->Cell(25,5,number_format(($reg['cantidad']),0,".",","),'RB',0,'R');
          $this->Cell(25,5,number_format(($reg['inv']),0,".",","),'RB',0,'R');
          $this->Cell(30,5,substr(utf8_decode($reg['estado']),0, 20),'RB',0,'L');
          $this->Ln();
        }else{
          $this->SetFont('Arial','',7);
          $this->Cell(16,5,substr(utf8_decode($reg['item']),0, 20),'LR',0,'L');
          $this->Cell(90,5,substr(utf8_decode($reg['descripcion']),0, 70),'R',0,'L');
          $this->Cell(25,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(25,5,number_format(($reg['inv']),0,".",","),'R',0,'R');
          $this->Cell(30,5,substr(utf8_decode($reg['estado']),0, 20),'R',0,'L');
          $this->Ln();
        }

        $cont++;

      }

      $nr = 14;

      for ($i=$cont; $i < $nr; $i++) {
        $this->Ln();
      }

      $this->SetFont('Arial','',10);
      $this->Cell(92,5,utf8_decode('______________________________'),0,0,'L');

      $this->Ln();

      $this->SetFont('Arial','B',7);
      $this->Cell(92,3,utf8_decode($this->firma),0,0,'L');

      $this->Ln();

      $this->SetFont('Arial','B',6);
      $this->Cell(92,3,utf8_decode($this->cargo),0,0,'L');

    }

  }//fin tabla

}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->setFirma($firma);
$pdf->setCargo($cargo);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','WIP_'.$modelo_wip->WIP.'.pdf');

?>

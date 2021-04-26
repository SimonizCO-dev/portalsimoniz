<?php
/* @var $this ParPedEspController */
/* @var $model ParPedEsp */

set_time_limit(0);

/*Modelo cabecera*/

$array_header = array();
$modelo_doc = ParPedEsp::model()->findByPk($id);

//info de la cabecera
$array_header = array();
$array_header['fecha'] = UtilidadesVarias::textofecha($modelo_doc->Fecha);
$array_header['consecutivo'] = $modelo_doc->Consecutivo;
$array_header['estructura'] = $modelo_doc->Estructura;
$array_header['ruta'] = $modelo_doc->Ruta;
$array_header['asesor'] = $modelo_doc->Asesor;
$array_header['coordinador'] = $modelo_doc->Coordinador;
$array_header['nit'] = $modelo_doc->Nit;
$array_header['razon_social'] = $modelo_doc->Razon_Social;
$array_header['direccion'] = $modelo_doc->Direccion;
$array_header['sucursal'] = $modelo_doc->Sucursal;
$array_header['punto_envio'] = $modelo_doc->Punto_Envio;
$array_header['ciudad'] = $modelo_doc->Ciudad;
$array_header['desc_adic'] = $modelo_doc->Porc_Desc;
$array_header['logo'] = Yii::app()->getBaseUrl(true).'/images/logo_header_simoniz.jpg';

if($modelo_doc->Observaciones == ""){
  $observaciones = "N/A";
}else{
  $observaciones = $modelo_doc->Observaciones;
}

$array_header['observaciones'] = $observaciones;

/*Modelo detalle*/

$array_data = array();
$modelo_det = DetParPedEsp::model()->findAllByAttributes(array('Id_Par_Ped_Esp' => $id));

//info de detalle

$total_cant = 0;
$total_sub_total = 0;
$total_iva = 0;

foreach ($modelo_det as $det) {

  $array_data[] = array(
    'codigo' =>  $det->Codigo,
    'descripcion' => $det->Descripcion,
    'marca' =>  $det->Marca,
    'cat_oracle' =>  $det->Cat_Oracle,
    'vlr_unit' =>  $det->Vlr_Unit,
    'cant' =>  $det->Cant,
    'iva' =>  $det->Iva,
    'nota' =>  $det->Nota,
    'sub_total' =>  $det->Vlr_Unit * $det->Cant,
  );

  if($det->Iva == 0){

    $vlr_iva = 0;

  }else{

    $vlr_base = $det->Vlr_Unit * $det->Cant;
    $vlr_iva = (($vlr_base * $det->Iva) / 100);

  }

  $total_cant += $det->Cant;
  $total_sub_total += $det->Vlr_Unit * $det->Cant; 
  $total_iva += $vlr_iva; 

  
}

$array_header['total_cant'] = $total_cant;
$array_header['total_sub_total'] = $total_sub_total;
$array_header['total_iva'] = $total_iva;
$array_header['total'] = $total_sub_total + $total_iva;

//PDF

//se incluye la libreria pdf
require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

class PDF extends FPDF{

  function setDataHeader($header){
    $this->header = $header;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){
    
    $data_header = $this->header;

    if(!empty($data_header)){

      $this->Image($data_header['logo'], 10, 8, 44, 14);
      $this->Cell(93);
      $this->SetFont('Arial','B',8);
      $this->Cell(93,3,utf8_decode(''),0,0,'C');
      $this->SetFont('Arial','B',10);
      $this->Cell(93,3,utf8_decode(''),0,0,'R');
      $this->Ln();
      $this->Cell(93);
      $this->SetFont('Arial','B',8);
      $this->Cell(93,3,utf8_decode('DOCUMENTO DE PARAMETRIZACIÓN'),0,0,'C');
      $this->SetFont('Arial','B',10);
      $this->Cell(91,3,utf8_decode('N° '.$data_header['consecutivo']),0,0,'R');
      $this->Ln();
      $this->Cell(93);
      $this->SetFont('Arial','B',8);
      $this->Cell(93,3,utf8_decode('PEDIDOS ESPECIALES'),0,0,'C');
      $this->SetFont('Arial','',6);
      $this->Cell(93,3,utf8_decode('Página '.$this->PageNo().' / {nb}'),0,0,'R');
      $this->Ln();
      $this->Cell(93);
      $this->SetFont('Arial','B',6);
      $this->Cell(93,3,utf8_decode(''),0,0,'C');
      $this->SetFont('Arial','',6);
      $this->Cell(93,3,utf8_decode(''),0,0,'R');
      $this->Ln();
      $this->Ln();

      $this->SetFont('Arial','B',8);
      $this->SetFillColor(255,255,255);
      $this->SetDrawColor(0,0,0);
      $this->SetTextColor(0);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Razón social:'),'LT',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['razon_social']),'TR',0,'L',TRUE);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Fecha:'),'LT',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['fecha']),'TR',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Nit:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['nit']),'R',0,'L',TRUE);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Estructura:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['estructura']),'R',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Dirección:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['direccion']),'R',0,'L',TRUE);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Ruta:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['ruta']),'R',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Ciudad:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['ciudad']),'R',0,'L',TRUE);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Asesor:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['asesor']),'R',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Sucursal:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['sucursal']),'R',0,'L',TRUE);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Coordinador:'),'L',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['coordinador']),'R',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Punto de envío:'),'LB',0,'L',TRUE);
      $this->SetFont('Arial','',7);
      $this->Cell(100,5,utf8_decode($data_header['punto_envio']),'RB',0,'L',TRUE);
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Descuento adicional:'),'LB',0,'L',TRUE);
      $this->SetFont('Arial','B',9);
      $this->Cell(100,5,utf8_decode($data_header['desc_adic'].' % '),'RB',0,'L',TRUE);
      $this->Ln();
      $this->SetFont('Arial','B',8);
      $this->Cell(40,5,utf8_decode('Observaciones:'),'L',0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(240,5,utf8_decode(''),'R',0,'L');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->MultiCell(280,3,utf8_decode($data_header['observaciones']),'RLB','J');
      $this->Ln();

      //tabla
      $this->SetFont('Arial','B',6);
      $this->SetFillColor(255,255,255);
      $this->SetDrawColor(0,0,0);
      $this->SetTextColor(0);
      $this->Cell(15,5,utf8_decode('CÓDIGO'),1,0,'C',TRUE);
      $this->Cell(60,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',TRUE);
      $this->Cell(25,5,utf8_decode('MARCA'),1,0,'C',TRUE);
      $this->Cell(25,5,utf8_decode('ORACLE'),1,0,'C',TRUE);
      $this->Cell(20,5,utf8_decode('VALOR UNIT.'),1,0,'C',TRUE);
      $this->Cell(100,5,utf8_decode('NOTA'),1,0,'C',TRUE);
      $this->Cell(15,5,utf8_decode('CANT.'),1,0,'C',TRUE);
      $this->Cell(20,5,utf8_decode('SUB TOTAL'),1,0,'C',TRUE);
      $this->Ln(); 
    }

  }

  function Tabla(){

    //$tipo = $this->tipo;
    $data = $this->data;

    $data_header = $this->header;
    //$vlr_total = $data_header['vlr_total'];

    $num_reg = count($data);

    if(!empty($data)){

      $cont = 1;

      foreach ($data as $reg) {

        $this->SetFont('Arial','',5);
        $this->Cell(15,5,substr(utf8_decode($reg['codigo']),0, 50),'LR',0,'L');
        $this->Cell(60,5,substr(utf8_decode($reg['descripcion']),0, 50),'LR',0,'L');
        $this->Cell(25,5,substr(utf8_decode($reg['marca']),0, 20),'R',0,'L');
        $this->Cell(25,5,substr(utf8_decode($reg['cat_oracle']),0, 20),'R',0,'L');
        $this->Cell(20,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
        $this->Cell(100,5,substr(utf8_decode($reg['nota']),0, 70),'LR',0,'L');
        $this->Cell(15,5,number_format(($reg['cant']),0,".",","),'R',0,'R');
        $this->Cell(20,5,number_format(($reg['sub_total']),2,".",","),'R',0,'R');
        $this->Ln();

        $cont++;

      }

      $nr = 19;

      //si tiene menos de 14 items se divide la hoja con una linea
      if($num_reg <= $nr){

        for ($i=$cont; $i < $nr; $i++) {
          $this->Cell(15,5,'','LR',0,'L');
          $this->Cell(60,5,'','LR',0,'L');
          $this->Cell(25,5,'','R',0,'L');
          $this->Cell(25,5,'','R',0,'L');
          $this->Cell(20,5,'','R',0,'R');
          $this->Cell(100,5,'','LR',0,'L');
          $this->Cell(15,5,'','R',0,'R');
          $this->Cell(20,5,'','R',0,'R');
          $this->Ln();
        }

        //linea con total

        $this->SetFont('Arial','B',7);
        $this->Cell(245,5,utf8_decode('SUB TOTAL'),'LTB',0,'R');
        $this->Cell(15,5,number_format(($data_header['total_cant']),0,".",","),'LRTB',0,'R');
        $this->Cell(20,5,number_format(($data_header['total_sub_total']),2,".",","),'RTB',0,'R');
        $this->Ln();

        $this->SetFont('Arial','B',7);
        $this->Cell(245,5,utf8_decode('IVA'),'LTB',0,'R');
        $this->Cell(35,5,number_format(($data_header['total_iva']),2,".",","),'RTB',0,'R');
        $this->Ln();

        $this->SetFont('Arial','B',7);
        $this->Cell(245,5,utf8_decode('TOTAL PEDIDO'),'LTB',0,'R');
        $this->Cell(35,5,number_format(($data_header['total']),2,".",","),'RTB',0,'R');
        $this->Ln();

      }else{

        //linea con total

        $this->SetFont('Arial','B',7);
        $this->Cell(245,5,utf8_decode('SUB TOTAL'),'LTB',0,'R');
        $this->Cell(15,5,number_format(($data_header['total_cant']),0,".",","),'LRTB',0,'R');
        $this->Cell(20,5,number_format(($data_header['total_sub_total']),2,".",","),'RTB',0,'R');
        $this->Ln();

        $this->SetFont('Arial','B',7);
        $this->Cell(245,5,utf8_decode('IVA'),'LTB',0,'R');
        $this->Cell(35,5,number_format(($data_header['total_iva']),2,".",","),'RTB',0,'R');
        $this->Ln();

        $this->SetFont('Arial','B',7);
        $this->Cell(245,5,utf8_decode('TOTAL PEDIDO'),'LTB',0,'R');
        $this->Cell(35,5,number_format(($data_header['total']),2,".",","),'RTB',0,'R');
        $this->Ln();

      }

    }

  }//fin tabla

}

$pdf = new PDF('L','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Par_Ped_Esp_'.$modelo_doc->Consecutivo.'.pdf');

?>

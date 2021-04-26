<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

/*Modelo cabecera*/

$array_header = array();
$modelo_doc = SolProm::model()->findByPk($id);

//info de la cabecera



$array_header = array();

$array_header['consecutivo'] = $modelo_doc->Num_Sol;;
$array_header['responsable'] = $modelo_doc->Responsable;
$array_header['tipo'] = $modelo_doc->DescTipo($modelo_doc->Tipo);
if($modelo_doc->Cliente != null){ $cliente = $modelo_doc->DescCliente($modelo_doc->Cliente); }else{ $cliente = '-'; };
$array_header['cliente'] = $cliente;
$array_header['observaciones'] = $modelo_doc->Observaciones;
$array_header['kit'] = $modelo_doc->DescKit($modelo_doc->Kit);
$array_header['cantidad'] = $modelo_doc->Cant;
$array_header['fecha'] = UtilidadesVarias::textofecha($modelo_doc->Fecha);
$array_header['fecha_t_entrega'] = UtilidadesVarias::textofecha($modelo_doc->Fecha_T_Entrega);
$array_header['logo'] = Yii::app()->getBaseUrl(true).'/images/logo_header_simoniz.jpg';

/*Modelo detalle*/

$array_data = array();
$modelo_det = DetSolProm::model()->findAllByAttributes(array('Id_Sol_Prom' => $id));

//info de detalle

foreach ($modelo_det as $det) {

  $array_data[] = array(
    'item' =>  $det->DescItem($det->Item),
    'cant_base' => number_format($det->Cant_Base, 4),
    'cant_requerida' =>  number_format($det->Cant_Requerida, 4),
    'cant_solicitada' => number_format($det->Cant_Solicitada, 4),
  );
  
}

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
        $this->Cell(61);
        $this->SetFont('Arial','B',8);
        $this->Cell(61,3,utf8_decode(''),0,0,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(61,3,utf8_decode(''),0,0,'R');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode(''),0,0,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(61,3,utf8_decode('N° '.$data_header['consecutivo']),0,0,'R');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode(''),0,0,'C');
        $this->Ln();
        $this->Cell(61);
        $this->SetFont('Arial','B',6);
        $this->Cell(61,3,utf8_decode(''),0,0,'C');
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
        $this->Cell(25,5,utf8_decode('Responsable:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(88,5,utf8_decode($data_header['responsable']),'',0,'L',TRUE);
        $this->SetFont('Arial','B',8);
        $this->Cell(20,5,utf8_decode('Tipo:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(53,5,utf8_decode($data_header['tipo']),'',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,utf8_decode('Cliente:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->MultiCell(161,5,utf8_decode($data_header['cliente']),'','J');
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,utf8_decode('Observaciones:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->MultiCell(161,5,utf8_decode($data_header['observaciones']),'','J');
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,utf8_decode('Kit:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->MultiCell(161,5,utf8_decode($data_header['kit']),'','J');
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,utf8_decode('Cantidad:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(68,5,utf8_decode($data_header['cantidad']),'',0,'L',TRUE);
        $this->Ln();
        $this->SetFont('Arial','B',8);
        $this->Cell(25,5,utf8_decode('Fecha:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(63,5,utf8_decode($data_header['fecha']),'',0,'L',TRUE);
        $this->SetFont('Arial','B',8);
        $this->Cell(45,5,utf8_decode('Fecha tentativa entrega:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(53,5,utf8_decode($data_header['fecha_t_entrega']),'',0,'L',TRUE);
        $this->Ln();
        $this->Ln();

        $this->SetFont('Arial','B',7);
        $this->SetFillColor(255,255,255);
        $this->SetDrawColor(0,0,0);
        $this->SetTextColor(0);
        $this->Cell(96,5,utf8_decode('Item'),1,0,'L',TRUE);
        $this->Cell(30,5,utf8_decode('Cant. base'),1,0,'L',TRUE);
        $this->Cell(30,5,utf8_decode('Cant. requerida'),1,0,'L',TRUE);
        $this->Cell(30,5,utf8_decode('Cant. solicitada'),1,0,'L',TRUE);
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

        $this->SetFont('Arial','',7);
        $this->Cell(96,5,substr(utf8_decode($reg['item']),0, 90),'LR',0,'L');
        $this->Cell(30,5,$reg['cant_base'],'R',0,'L');
        $this->Cell(30,5,$reg['cant_requerida'],'R',0,'R');
        $this->Cell(30,5,$reg['cant_solicitada'],'R',0,'R');
        $this->Ln();

        $cont++;

      }

      $nr = 13;

      //si tiene menos de 14 items se divide la hoja con una linea
      if($num_reg <= $nr){

        for ($i=$cont; $i < $nr; $i++) {

          $this->Cell(96,5,'','LR',0,'L');
          $this->Cell(30,5,'','R',0,'R');
          $this->Cell(30,5,'','R',0,'R');
          $this->Cell(30,5,'','R',0,'R');
          $this->Ln();
        }

        //linea final

        $this->SetFont('Arial','B',7);
        $this->Cell(186,5,'','T',0,'R');
        $this->Ln();

        $this->Line(10, 139.7, 210-14, 139.7);

      }else{

        //linea final

        $this->SetFont('Arial','B',7);
        $this->Cell(186,5,'','T',0,'R');
        $this->Ln();

      } 

    }

  }//fin tabla

}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','SOLICITUD_PROM_'.$modelo_doc->Num_Sol.'.pdf');

?>

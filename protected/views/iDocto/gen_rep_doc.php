<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

/*Modelo cabecera*/

$array_header = array();
$modelo_doc = IDocto::model()->findByPk($id);

//info de la cabecera
$array_header = array();
$array_header['id_tipo'] = $modelo_doc->Id_Tipo_Docto;
$array_header['tipo_abr'] = $modelo_doc->idtipodocto->Tipo;
$array_header['tipo'] = $modelo_doc->idtipodocto->Descripcion;
$array_header['consecutivo'] = $modelo_doc->Consecutivo;
$array_header['fecha'] = UtilidadesVarias::textofecha($modelo_doc->Fecha);
$array_header['referencia'] = $modelo_doc->Referencia;
$array_header['tercero'] = $modelo_doc->DescTercero($modelo_doc->Id_Tercero);
$array_header['vlr_total'] = $modelo_doc->Vlr_Total;
$array_header['estado'] = $modelo_doc->idestado->Descripcion;
$array_header['logo'] = Yii::app()->getBaseUrl(true).'/images/logo_header_simoniz.jpg';

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->sad){
  $array_header['empleado'] = $modelo_doc->DescEmpleado($modelo_doc->Id_Emp);
}else{
  $array_header['empleado'] = '';
}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->aje || $modelo_doc->Id_Tipo_Docto == Yii::app()->params->ajs){
  $array_header['notas'] = $modelo_doc->Notas;
}else{
  $array_header['notas'] = '';
}


/*Modelo detalle*/

$array_data = array();
$modelo_det = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

//info de detalle

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->ent){
  //entrada

  foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>  $det->DescItem($det->Id_Item),
      'bodega_destino' => $det->idbodegadst->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->sal){
  //salida

  foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>   $det->DescItem($det->Id_Item),
      'bodega_origen' =>  $det->idbodegaorg->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->trb){
  //transferencia

 foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>  $det->DescItem($det->Id_Item),
      'bodega_origen' =>  $det->idbodegaorg->Descripcion,
      'bodega_destino' => $det->idbodegadst->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->aje){
  //ajuste por entrada

  foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>  $det->DescItem($det->Id_Item),
      'bodega_destino' => $det->idbodegadst->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->ajs){
  //ajuste por salida

  foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>   $det->DescItem($det->Id_Item),
      'bodega_origen' =>  $det->idbodegaorg->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->sad){
  //salida de dotación

  foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>   $det->DescItem($det->Id_Item),
      'bodega_origen' =>  $det->idbodegaorg->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

if($modelo_doc->Id_Tipo_Docto == Yii::app()->params->dev){
  //devolución

  foreach ($modelo_det as $det) {

    $array_data[] = array(
      'item' =>  $det->DescItem($det->Id_Item),
      'bodega_destino' => $det->idbodegadst->Descripcion,
      'cantidad' =>  $det->Cantidad,
      'vlr_unit' =>  $det->Vlr_Unit_Item,
    );
    
  }

}

//PDF

//se incluye la libreria pdf
require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

class PDF extends FPDF{

  function setDataHeader($header){
    $this->header = $header;
  }

  function setTipo($tipo){
    $this->tipo = $tipo;
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
        $this->Cell(61,3,utf8_decode($data_header['tipo_abr'].' N° '.$data_header['consecutivo']),0,0,'R');
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

        if($data_header['id_tipo'] == Yii::app()->params->sad){
          
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Fecha:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode($data_header['fecha']),'',0,'L',TRUE);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
          $this->Ln();
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Referencia:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->MultiCell(166,5,utf8_decode($data_header['referencia']),'','J');
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Tercero:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode($data_header['tercero']),'',0,'L',TRUE);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
          $this->Ln();
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Empleado:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(166,5,utf8_decode($data_header['empleado']),'',0,'L',TRUE);
          $this->Ln();

        }

        if($data_header['id_tipo'] == Yii::app()->params->aje || $data_header['id_tipo'] == Yii::app()->params->ajs){
          
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Fecha:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode($data_header['fecha']),'',0,'L',TRUE);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
          $this->Ln();
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Referencia:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->MultiCell(166,5,utf8_decode($data_header['referencia']),'','J');
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Tercero:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode($data_header['tercero']),'',0,'L',TRUE);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
          $this->Ln();
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Notas:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->MultiCell(166,5,utf8_decode($data_header['notas']),'','J');

        }

        if($data_header['id_tipo'] == Yii::app()->params->ent || $data_header['id_tipo'] == Yii::app()->params->sal || $data_header['id_tipo'] == Yii::app()->params->trb || $data_header['id_tipo'] == Yii::app()->params->dev){

          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Fecha:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode($data_header['fecha']),'',0,'L',TRUE);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
          $this->Ln();
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Referencia:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->MultiCell(166,5,utf8_decode($data_header['referencia']),'','J');
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode('Tercero:'),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode($data_header['tercero']),'',0,'L',TRUE);
          $this->SetFont('Arial','B',8);
          $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
          $this->SetFont('Arial','',7);
          $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
          $this->Ln();

        }

        $this->SetFont('Arial','B',8);
        $this->Cell(20,5,utf8_decode('Estado:'),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(73,5,utf8_decode($data_header['estado']),'',0,'L',TRUE);
        $this->SetFont('Arial','B',8);
        $this->Cell(20,5,utf8_decode(''),'',0,'L',TRUE);
        $this->SetFont('Arial','',7);
        $this->Cell(73,5,utf8_decode(''),'',0,'L',TRUE);
        $this->Ln();
        $this->Ln();

        if($data_header['id_tipo'] == Yii::app()->params->ent){
          //entrada

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(66,5,utf8_decode('Bodega destino'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();

        }

        if($data_header['id_tipo'] == Yii::app()->params->sal){
          //salida

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(66,5,utf8_decode('Bodega origen'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();

        }

        if($data_header['id_tipo'] == Yii::app()->params->trb){
          //transferencia

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(33,5,utf8_decode('Bodega origen'),1,0,'L',TRUE);
          $this->Cell(33,5,utf8_decode('Bodega destino'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();


        }

        if($data_header['id_tipo'] == Yii::app()->params->aje){
          //ajuste por entrada

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(66,5,utf8_decode('Bodega destino'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();

        }

        if($data_header['id_tipo'] == Yii::app()->params->ajs){
          //ajuste por salida

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(66,5,utf8_decode('Bodega origen'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();

        }

        if($data_header['id_tipo'] == Yii::app()->params->sad){
          //salida de dotación

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(66,5,utf8_decode('Bodega origen'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();

        }

        if($data_header['id_tipo'] == Yii::app()->params->dev){
          //devolución

          $this->SetFont('Arial','B',7);
          $this->SetFillColor(255,255,255);
          $this->SetDrawColor(0,0,0);
          $this->SetTextColor(0);
          $this->Cell(75,5,utf8_decode('Item'),1,0,'L',TRUE);
          $this->Cell(66,5,utf8_decode('Bodega destino'),1,0,'L',TRUE);
          $this->Cell(15,5,utf8_decode('Cantidad'),1,0,'L',TRUE);
          $this->Cell(30,5,utf8_decode('Vlr. unitario'),1,0,'L',TRUE);
          $this->Ln();

        }  
    }

  }

  function Tabla(){

    $tipo = $this->tipo;
    $data = $this->data;

    $data_header = $this->header;
    $vlr_total = $data_header['vlr_total'];

    $num_reg = count($data);

    if(!empty($data)){

      $cont = 1;

      foreach ($data as $reg) {
  
        if($tipo == Yii::app()->params->ent){
          //entrada

          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(66,5,substr(utf8_decode($reg['bodega_destino']),0, 40),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;

        }

        if($tipo == Yii::app()->params->sal){
          //salida

          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(66,5,substr(utf8_decode($reg['bodega_origen']),0, 40),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;
        }

        if($tipo == Yii::app()->params->trb){
          //transferencia

          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(33,5,substr(utf8_decode($reg['bodega_origen']),0, 20),'R',0,'L');
          $this->Cell(33,5,substr(utf8_decode($reg['bodega_destino']),0, 20),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;

        }

        if($tipo == Yii::app()->params->aje){
          //ajuste por entrada

          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(66,5,substr(utf8_decode($reg['bodega_destino']),0, 40),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;

        }

        if($tipo == Yii::app()->params->ajs){
          //ajuste por salida
          
          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(66,5,substr(utf8_decode($reg['bodega_origen']),0, 40),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;

        }

        if($tipo == Yii::app()->params->sad){
          //salida de dotación

          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(66,5,substr(utf8_decode($reg['bodega_origen']),0, 40),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;
        }

        if($tipo == Yii::app()->params->dev){
          //devolución

          $this->SetFont('Arial','',7);
          $this->Cell(75,5,substr(utf8_decode($reg['item']),0, 50),'LR',0,'L');
          $this->Cell(66,5,substr(utf8_decode($reg['bodega_destino']),0, 40),'R',0,'L');
          $this->Cell(15,5,number_format(($reg['cantidad']),0,".",","),'R',0,'R');
          $this->Cell(30,5,number_format(($reg['vlr_unit']),2,".",","),'R',0,'R');
          $this->Ln();

          $cont++;

        }
      }

      if($tipo == Yii::app()->params->sad){
        $nr = 14;
      }

      if($tipo == Yii::app()->params->aje || $tipo == Yii::app()->params->ajs){
        $nr = 13;
      }

      if($tipo == Yii::app()->params->ent || $tipo == Yii::app()->params->sal || $tipo == Yii::app()->params->trb || $tipo == Yii::app()->params->dev){
        $nr = 15;
      }

      //si tiene menos de 14 items se divide la hoja con una linea
      if($num_reg <= $nr){

        for ($i=$cont; $i < $nr; $i++) {

          if($tipo != Yii::app()->params->trb){
            $this->Cell(75,5,'','LR',0,'L');
            $this->Cell(66,5,'','R',0,'L');
            $this->Cell(15,5,'','R',0,'R');
            $this->Cell(30,5,'','R',0,'R');
          }else{
            $this->Cell(75,5,'','LR',0,'L');
            $this->Cell(33,5,'','R',0,'L');
            $this->Cell(33,5,'','R',0,'L');
            $this->Cell(15,5,'','R',0,'R');
            $this->Cell(30,5,'','R',0,'R');
          }

          $this->SetFont('Arial','',7);
          $this->Cell(186,5,'','LR',0,'J');
          $this->Ln();
        }

        //linea con total

        $this->SetFont('Arial','B',7);
        $this->Cell(156,5,utf8_decode('TOTAL'),'LTB',0,'R');
        $this->Cell(30,5,number_format($vlr_total, 2),'RTB',0,'R');
        $this->Ln();

        $this->Line(10, 139.7, 210-14, 139.7);

      }else{

        //linea con total

        $this->SetFont('Arial','B',7);
        $this->Cell(156,5,utf8_decode('TOTAL'),'LTB',0,'R');
        $this->Cell(30,5,number_format($vlr_total, 2),'RTB',0,'R');
        $this->Ln();

      } 

    }

  }//fin tabla

}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setDataHeader($array_header);
$pdf->setTipo($modelo_doc->Id_Tipo_Docto);
$pdf->setData($array_data);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D',$modelo_doc->idtipodocto->Tipo.'_'.$modelo_doc->Consecutivo.'.pdf');

?>

<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias


//se reciben los parametros para el reporte
if (isset($model['Id_Tipo_Docto'])) { $Id_Tipo_Docto = $model['Id_Tipo_Docto']; } else { $Id_Tipo_Docto = ""; }
if (isset($model['Consecutivo'])) { $Consecutivo = $model['Consecutivo']; } else { $Consecutivo = ""; }
if (isset($model['fecha_inicial'])) { $fecha_inicial = $model['fecha_inicial']; } else { $fecha_inicial = ""; }
if (isset($model['fecha_final'])) { $fecha_final = $model['fecha_final']; } else { $fecha_final = ""; }
if (isset($model['Id_Tercero'])) { $Id_Tercero = $model['Id_Tercero']; } else { $Id_Tercero = ""; }
if (isset($model['det_item'])) { $det_item = $model['det_item']; } else { $det_item = ""; }
if (isset($model['det_bodega_origen'])) { $det_bodega_origen = $model['det_bodega_origen']; } else { $det_bodega_origen = ""; }
if (isset($model['det_bodega_destino'])) { $det_bodega_destino = $model['det_bodega_destino']; } else { $det_bodega_destino = ""; }

if($Id_Tipo_Docto == "" && $Consecutivo == "" && $fecha_inicial == "" && $fecha_final == "" && $Id_Tercero == "" && $det_item == "" && $det_bodega_origen == "" && $det_bodega_destino == ""){
  $view_crit = 0;
}else{
  $view_crit = 1;
}


$condicion = "WHERE 1 = 1";

if($view_crit == 0){
  $criterio = "";
}else{
  $criterio = "Criterio de búsqueda:"; 
}

if($Id_Tipo_Docto != null){

  $tipo_docto = ITipoDocto::model()->findByPk($Id_Tipo_Docto)->Descripcion;

  $condicion .= " AND DOC.Id_Tipo_Docto = ".$Id_Tipo_Docto;
  $criterio .= " Tipo: ".$tipo_docto.", ";

}

if($Consecutivo != null){

  $condicion .= " AND DOC.Consecutivo = ".$Consecutivo;
  $criterio .= " Consecutivo: ".$Consecutivo.", ";

}

if($fecha_inicial != null && $fecha_final != null){
  $condicion .= " AND DOC.Fecha BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
  $criterio .= " Fecha: ".UtilidadesVarias::textofecha($fecha_inicial)." al ".UtilidadesVarias::textofecha($fecha_final).", ";
}else{
  if($fecha_inicial != null && $fecha_final == null){
    $condicion .= " AND DOC.Fecha = '".$fecha_inicial."'";
    $criterio .= " Fecha: ".UtilidadesVarias::textofecha($fecha_inicial).", ";
  }
}

if($Id_Tercero != null){

  $tercero = ITercero::model()->findByPk($Id_Tercero);
  $desc_tercero = $tercero->Nit.' - '.$tercero->Nombre;

  $condicion .= " AND DOC.Id_Tercero = ".$Id_Tercero;
  $criterio .= " Tercero: ".$desc_tercero.", ";

}

if($det_item != null){

  $item = IItem::model()->findByPk($det_item);
  $desc_item = $item->Id_Item.' ('.$item->Referencia.' - '.$item->Descripcion.')';

  $condicion .= " AND DET.Id_Item = ".$det_item;
  $criterio .= " Item: ".$desc_item.", ";

}

if($det_bodega_origen != null){

  $bodega_origen = IBodega::model()->findByPk($det_bodega_origen);
  $desc_bodega_origen = $bodega_origen->Descripcion;

  $condicion .= " AND DET.Id_Bodega_Org = ".$det_bodega_origen;
  $criterio .= " Bodega origen: ".$desc_bodega_origen.", ";

}

if($det_bodega_destino != null){

  $bodega_destino = IBodega::model()->findByPk($det_bodega_destino);
  $desc_bodega_destino = $bodega_destino->Descripcion;

  $condicion .= " AND DET.Id_Bodega_Dst = ".$det_bodega_destino;
  $criterio .= " Bodega destino: ".$desc_bodega_destino.", ";

}

$criterio = substr ($criterio, 0, -2);

$query ="
  SELECT
  TD.Descripcion AS Desc_Tipo, 
  TD.Tipo AS Tipo, 
  DOC.Consecutivo AS Consecutivo,
  CONCAT (TER.Nit, ' - ', TER.Nombre) AS Tercero,
  DOC.Fecha AS Fecha,
  ED.Descripcion AS Estado_Docto,
  CONCAT (I.Id_Item, ' (', I.Referencia, ' - ', I.Descripcion, ')') AS Item,
  DET.Cantidad AS Cantidad,
  BO.Descripcion AS Bodega_Origen,
  BD.Descripcion AS Bodega_Destino,
  DET.Vlr_Unit_Item AS Vlr_Unit,
  UC.Usuario AS Usuario_Creacion,
  UA.Usuario AS Usuario_Actualizacion
  FROM T_PR_I_DOCTO_MOVTO DET
  LEFT JOIN T_PR_I_DOCTO DOC ON DET.Id_Docto = DOC.Id
  LEFT JOIN T_PR_I_TIPO_DOCTO TD ON DOC.Id_Tipo_Docto = TD.Id
  LEFT JOIN T_PR_I_ESTADO_DOCTO ED ON DOC.Id_Estado = ED.Id
  LEFT JOIN T_PR_I_ITEM I ON DET.Id_Item = I.Id
  LEFT JOIN T_PR_I_BODEGA BO ON DET.Id_Bodega_Org = BO.Id
  LEFT JOIN T_PR_I_BODEGA BD ON DET.Id_Bodega_Dst = BD.Id
  LEFT JOIN T_PR_USUARIO UC ON DET.Id_Usuario_Creacion = UC.Id_Usuario
  LEFT JOIN T_PR_USUARIO UA ON DET.Id_Usuario_Actualizacion = UA.Id_Usuario 
  LEFT JOIN T_PR_I_TERCERO TER ON DOC.Id_Tercero = TER.Id
  ".$condicion."
  ORDER BY 2, 1, 5
";

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

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setCriterio($criterio){
    $this->criterio = $criterio;
  }

  function setSql($sql){
    $this->sql = $sql;
  }

  function Header(){
    $this->SetFont('Arial','B',12);
    $this->Cell(260,5,'Movimientos de inventario',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');


    if($this->criterio != ""){
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->MultiCell(280,5,utf8_decode($this->criterio),0,'J');
      $this->Ln();
    }else{
      $this->Ln();
      $this->Ln();  
    }

    //linea superior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(340,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->Ln();  
    
    //cabecera de tabla
    $this->SetFont('Arial','B',7);

    $this->Cell(30,2,utf8_decode('Tipo'),0,0,'L');
    $this->Cell(20,2,utf8_decode('Consecutivo'),0,0,'L'); 
    $this->Cell(59,2,utf8_decode('Tercero'),0,0,'L'); 
    $this->Cell(15,2,utf8_decode('Fecha'),0,0,'L'); 
    $this->Cell(20,2,utf8_decode('Estado'),0,0,'L'); 
    $this->Cell(58,2,utf8_decode('Item'),0,0,'L'); 
    $this->Cell(13,2,utf8_decode('Cantidad'),0,0,'L');
    $this->Cell(16,2,utf8_decode('Vlr. unitario'),0,0,'L');  
    $this->Cell(27,2,utf8_decode('Bodega origen'),0,0,'L'); 
    $this->Cell(27,2,utf8_decode('Bodega destino'),0,0,'L');
    $this->Cell(27,2,utf8_decode('Usuario que creo'),0,0,'L');
    $this->Cell(27,2,utf8_decode('Usuario que actualizó'),0,0,'L'); 
    
    $this->Ln(3);   
    
    //linea inferior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(340,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    

    $this->Ln();
  }

  function Tabla(){

    $q = Yii::app()->db->createCommand($this->sql)->queryAll();

    if(!empty($q)){
      foreach ($q as $reg) {

        $Tipo    = $reg ['Desc_Tipo']; 
        $Consecutivo       =  $reg ['Tipo'].'-'.$reg ['Consecutivo']; 
        $Tercero = $reg ['Tercero']; 
        $Tipo    = $reg ['Desc_Tipo']; 
        $Fecha  = $reg ['Fecha'];
        $Estado_Docto  = $reg ['Estado_Docto'];
        $Item  = $reg ['Item'];
        $Cantidad  = $reg ['Cantidad'];
        $Vlr_Unit  = $reg ['Vlr_Unit'];

        if(is_null($reg ['Bodega_Origen'])){
          $Bodega_Origen  = '-';
        }else{
          $Bodega_Origen  = $reg ['Bodega_Origen'];
        }

        if(is_null($reg ['Bodega_Destino'])){
          $Bodega_Destino  = '-';
        }else{
          $Bodega_Destino  = $reg ['Bodega_Destino'];
        }

        $Usuario_Creacion  = $reg ['Usuario_Creacion'];
        $Usuario_Actualizacion  = $reg ['Usuario_Actualizacion'];

        $this->SetFont('Arial','',7);
        $this->Cell(30,3,utf8_decode($Tipo),0,0,'L');
        $this->Cell(20,3,$Consecutivo,0,0,'L');
        $this->Cell(59,3,substr(utf8_decode($Tercero),0, 39),0,0,'L');
        $this->Cell(15,3,$Fecha,0,0,'L');
        $this->Cell(20,3,utf8_decode($Estado_Docto),0,0,'L');
        $this->Cell(58,3,substr(utf8_decode($Item),0, 38),0,0,'L');
        $this->Cell(13,3,utf8_decode($Cantidad),0,0,'R');
        $this->Cell(16,3,number_format(($Vlr_Unit),0,".",","),0,0,'R');
        $this->Cell(27,3,utf8_decode($Bodega_Origen),0,0,'L');
        $this->Cell(27,3,utf8_decode($Bodega_Destino),0,0,'L');
        $this->Cell(27,3,utf8_decode($Usuario_Creacion),0,0,'L');
        $this->Cell(27,3,utf8_decode($Usuario_Actualizacion),0,0,'L');
        $this->Ln(); 
      }
    }

    $this->Ln();
    $this->SetDrawColor(0,0,0);
    $this->Cell(340,0,'','T');                            
    $this->Ln();

  }//fin tabla

  function Footer()
  {
      $this->SetY(-15);
      $this->SetFont('Arial','B',6);
      $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
  }
}

$pdf = new PDF('L','mm','Legal');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act); 
$pdf->setCriterio($criterio);
$pdf->setSql($query);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Movimientos_inventario_'.date('Y_m_d_H_i_s').'.pdf');

?>












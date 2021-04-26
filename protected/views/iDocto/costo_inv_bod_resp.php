<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte
if (isset($model['bodega'])) { $bodega = $model['bodega']; } else { $bodega = ""; }

$criteria=new CDbCriteria;
$criteria->join = 'LEFT JOIN T_PR_I_ITEM i ON i.Id = t.Id_Item LEFT JOIN T_PR_I_LINEA l ON i.Id_Linea = l.Id';
$criteria->condition='t.Id_Bodega=:bodega AND t.Cantidad > 0';
$criteria->params=array(':bodega'=>$bodega);
$criteria->order='l.Descripcion ASC, i.Descripcion ASC';
$items_exist=IExistencia::model()->findAll($criteria);

$desc_bodega = IBodega::model()->findByPk($bodega)->Descripcion;

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

$fecha_act = $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;

/*fin configuración array de datos*/

//PDF

//se incluye la libreria pdf
require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setBodega($bodega){
    $this->bodega = $bodega;
  }

  function setData($data){
    $this->data = $data;
  }

  function Header(){
    $this->SetFont('Arial','B',12);
    $this->Cell(100,5,'Costo de inventario x bodega',0,0,'L');
    $this->SetFont('Arial','',9);
    $this->Cell(95,5,utf8_decode($this->fecha_actual),0,0,'R');

    $this->Ln();
    $this->Ln();
    $this->SetFont('Arial','',7);
    $this->MultiCell(195,5,utf8_decode('Criterio de búsqueda: Bodega / '.$this->bodega),0,'J');
    $this->Ln();
    
    //linea superior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->Ln();  
    
    //cabecera de tabla
    $this->SetFont('Arial','B',7);
    $this->Cell(40,2,utf8_decode('Línea'),0,0,'L');
    $this->Cell(80,2,utf8_decode('Item'),0,0,'L'); 
    $this->Cell(25,2,utf8_decode('Costo unitario'),0,0,'L'); 
    $this->Cell(25,2,utf8_decode('Cantidad'),0,0,'L'); 
    $this->Cell(25,2,utf8_decode('Costo Total'),0,0,'L');  
    
    $this->Ln(3);   
    
    //linea inferior a la cabecera de la tabla
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,1,'','T');
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    

    $this->Ln();
  }

  function Tabla(){

    $data = $this->data;
    $total = 0; 

    if(!empty($data)){
      foreach ($data as $reg) {

        $linea        = $reg->iditem->idlinea->Descripcion; 
        $item         = $reg->DescItem($reg->Id_Item); 
        $costo_unit   = $reg->iditem->Vlr_Costo / $reg->iditem->Total_Inventario; 
        $cantidad     = $reg->Cantidad;
        $costo_tot    = ($reg->iditem->Vlr_Costo / $reg->iditem->Total_Inventario) * $cantidad;

        $total = $total + $costo_tot;

        $this->SetFont('Arial','',7);
        $this->Cell(40,3,substr(utf8_decode($linea),0, 20),0,0,'L');
        $this->Cell(80,3,substr(utf8_decode($item),0, 45),0,0,'L');
        $this->Cell(25,3,number_format(($costo_unit),2,".",","),0,0,'R');
        $this->Cell(25,3,$cantidad,0,0,'R');
        $this->Cell(25,3,number_format(($costo_tot),2,".",","),0,0,'R');
        $this->Ln(); 
      }
    }

    $this->Ln();
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,0,'','T');                            
    $this->Ln(3);

    $this->SetFont('Arial','B',7);
    $this->Cell(170,2,utf8_decode('TOTAL '.$this->bodega),0,0,'R');
    $this->Cell(25,2,number_format(($total),2,".",","),0,0,'R'); 

    $this->Ln();
    $this->Ln();
    $this->SetDrawColor(0,0,0);
    $this->Cell(195,0,'','T');                            
    $this->Ln();
 

  }//fin tabla

  function Footer()
  {
      $this->SetY(-15);
      $this->SetFont('Arial','B',6);
      $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
  }
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act); 
$pdf->setBodega($desc_bodega);
$pdf->setData($items_exist);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Costo_inventario_'.$desc_bodega.'_'.date('Y_m_d_H_i_s').'.pdf');

?>












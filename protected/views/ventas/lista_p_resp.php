<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

$tipo = $model['tipo'];
$lista = $model['lista'];
$marca_inicial = trim($model['marca_inicial']);
$marca_final = trim($model['marca_final']);
$des_ora_ini = trim($model['des_ora_ini']);
$des_ora_fin = trim($model['des_ora_fin']);

$lp = Yii::app()->db->createCommand("SELECT DISTINCT f112_descripcion FROM UnoEE1..t112_mc_listas_precios WHERE f112_id = '".$lista."'")->queryRow();
$lista_pr = $lista.' - '.$lp['f112_descripcion'];

if($tipo == 1){
  //query rango de marcas
  $cad_rango_mar = $marca_inicial.' a '.$marca_final;
  $cad_rango_ora = '';
}else{
  //query rango de oracle
  $cad_rango_mar = '';
  $cad_rango_ora = $des_ora_ini.' a '.$des_ora_fin;
}

//print_r($model);die;

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

/*inicio configuración array de datos*/

if($tipo == 1){
  //query rango de marcas
  $query ="
    SET NOCOUNT ON
    EXEC P_PR_PRECIOS_IMAGEN_CRI
    @OPT = N'".$tipo."',
    @LISTA = N'".$lista."',
    @VAR1 = N'".$marca_inicial."',
    @VAR2 = N'".$marca_final."'
  ";

}else{
  //query rango de oracle
  $query ="
    SET NOCOUNT ON
    EXEC P_PR_PRECIOS_IMAGEN_CRI
    @OPT = N'".$tipo."',
    @LISTA = N'".$lista."',
    @VAR1 = N'".$des_ora_ini."',
    @VAR2 = N'".$des_ora_fin."'
  ";
}

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

  function setFechaActual($fecha_actual){
    $this->fecha_actual = $fecha_actual;
  }

  function setSql($sql){
    $this->sql = $sql;
  }

  function setTipo($tipo){
    $this->tipo = $tipo;
  }

  function setLista($lista){
    $this->lista = $lista;
  }

  function setCriterioMar($criteriomar){
    $this->criteriomar = $criteriomar;
  }

  function setCriterioOra($criterioora){
    $this->criterioora = $criterioora;
  }

  function Header(){
    $this->SetFont('Arial','B',9);
    $this->Cell(100,5,utf8_decode('LISTA DE PRECIOS'),0,0,'L');
    $this->SetFont('Arial','',8);
    $this->Cell(85,5,utf8_decode($this->fecha_actual),0,0,'R');
    $this->Ln();
    $this->Ln();
    $this->SetFont('Arial','',8);
    $this->Cell(185,5,utf8_decode('Criterio de búsqueda / Lista: '.$this->lista),0,0,'L');
    $this->Ln();
    
    if($this->tipo == 1){
      $this->SetFont('Arial','',8);
      $this->Cell(185,5,utf8_decode('Criterio de búsqueda / Rango marcas: '.$this->criteriomar),0,0,'L');
      $this->Ln();
    }else{
      $this->SetFont('Arial','',8);
      $this->Cell(185,5,utf8_decode('Criterio de búsqueda / Rango desc. oracle: '.$this->criterioora),0,0,'L');
      $this->Ln();    
    }

    $this->Ln();

  }

  function Tabla(){

    $MARCA_LINEA_ACT = "";
    $ORACLE_LINEA_ACT = "";

    $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

    foreach ($query1 as $reg1) {
      
      if($this->tipo == 1){
        
        $I_CRI_MARCA       = trim($reg1 ['CRITERIO']);
        $I_CRI_LINEA       = trim($reg1 ['I_CRI_LINEA']);
        $ID                = $reg1 ['ID'];
        $REFERENCIA        = trim($reg1 ['REFERENCIA']);
        $DESCRIPCION       = trim($reg1 ['DESCRIPCION']);    
        $UND               = $reg1 ['UNL'];
        $PRECIO            = $reg1 ['PRECIO'];

        $MARCA_LINEA = $I_CRI_MARCA.' - '.$I_CRI_LINEA;
        
        if (file_exists('tmp/imgs_listas/'.$ID.'.jpg')) {
          $FOTO = 'tmp/imgs_listas/'.$ID.'.jpg';
        }else{
          $FOTO = 'tmp/default.jpg';
        }


        if($MARCA_LINEA_ACT != $MARCA_LINEA){

          $this->SetFont('Arial','B',7);
          $this->Cell(185,10,utf8_decode($MARCA_LINEA),0,0,'L');
          $this->Ln();

          $MARCA_LINEA_ACT = $MARCA_LINEA;

        }

        $this->SetFont('Arial','',7);
        $this->Cell(5,10,'',0,0,'L');
        $this->Cell(25,10,'',0,0,'C',$this->Image($FOTO,$this->GetX(),$this->GetY(),10,10),0,0,'C');
        $this->Cell(15,10,$ID,0,0,'L');
        $this->Cell(25,10,substr(utf8_decode($REFERENCIA),0, 15),0,0,'L');
        $this->Cell(85,10,substr(utf8_decode($DESCRIPCION), 0, 50),0,0,'L');
        $this->Cell(10,10,substr(utf8_decode($UND), 0, 3),0,0,'L');
        $this->Cell(15,10,number_format(($PRECIO),2,".",","),0,0,'R');
        $this->Ln();

      }else{

        $I_CRI_ORACLE      = $reg1 ['CRITERIO'];
        $I_CRI_LINEA       = $reg1 ['I_CRI_LINEA'];
        $ID                = $reg1 ['ID'];
        $REFERENCIA        = $reg1 ['REFERENCIA'];
        $DESCRIPCION       = $reg1 ['DESCRIPCION'];    
        $UND               = $reg1 ['UNL'];
        $PRECIO            = $reg1 ['PRECIO'];

        $ORACLE_LINEA = $I_CRI_ORACLE.' - '.$I_CRI_LINEA;
        
        if (file_exists('tmp/imgs_listas/'.$ID.'.jpg')) {
          $FOTO = 'tmp/imgs_listas/'.$ID.'.jpg';
        }else{
          $FOTO = 'tmp/default.jpg';
        }

        if($ORACLE_LINEA_ACT != $ORACLE_LINEA){

          $this->SetFont('Arial','B',7);
          $this->Cell(185,10,utf8_decode($ORACLE_LINEA),0,0,'L');
          $this->Ln();

          $ORACLE_LINEA_ACT = $ORACLE_LINEA;

        }

        $this->SetFont('Arial','',7);
        $this->Cell(5,10,'',0,0,'L');
        $this->Cell(25,10,'',0,0,'C',$this->Image($FOTO,$this->GetX(),$this->GetY(),10,10),1,0,'C');
        $this->Cell(15,10,$ID,0,0,'L');
        $this->Cell(25,10,substr(utf8_decode($REFERENCIA),0, 15),0,0,'L');
        $this->Cell(85,10,substr(utf8_decode($DESCRIPCION), 0, 50),0,0,'L');
        $this->Cell(10,10,substr(utf8_decode($UND), 0, 3),0,0,'L');
        $this->Cell(15,10,number_format(($PRECIO),2,".",","),0,0,'R');
        $this->Ln();

      }
      
    }


  }//fin tabla*/

  function Footer()
  {
      $this->SetY(-20);
      $this->SetFont('Arial','B',6);
      $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
  }
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setFechaActual($fecha_act);
$pdf->setSql($query);
$pdf->setTipo($tipo);
$pdf->setLista($lista_pr);
$pdf->setCriterioMar($cad_rango_mar);
$pdf->setCriterioOra($cad_rango_ora);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Lista_'.$lista.'_'.date('Y_m_d_H_i_s').'.pdf');

?>
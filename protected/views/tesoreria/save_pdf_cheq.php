<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

$query ="
  SET NOCOUNT ON
  EXEC P_PR_FIN_CH1
  @CIA = '".$cia."',
  @CO = '".$co."',
  @DOCTO = '".$tipo."',
  @NUM_INI = ".$consecutivo.",
  @NUM_FIN = ".$consecutivo."
";

UtilidadesVarias::log($query);

$data = Yii::app()->db->createCommand($query)->queryAll();

$array_info = array();

if(!empty($data)){
      
    $array_info['anio'] = $data[0]['ANO'];

    if($data[0]['MES'] <= 9){
        $array_info['mes'] = "0".$data[0]['MES'];
    }else{
        $array_info['mes'] = $data[0]['MES'];
    }

    if($data[0]['DIA'] <= 9){
        $array_info['dia'] = "0".$data[0]['DIA'];
    }else{
        $array_info['dia'] = $data[0]['DIA'];
    }

    $array_info['titular'] = utf8_decode($data[0]['PROVEEDOR']);

    $array_info['total'] =number_format(($data[0]['TOTAL']),2,".",".");

    $query2 ="
      EXEC P_CF_CONF_NUM_A_LETRAS
        @Numero = ".$data[0]['TOTAL'].",
        @Mon = 'COP'
    ";

    $valor_letras = Yii::app()->db->createCommand($query2)->queryRow();
    $vl = $valor_letras['tEXTO'];
    $array_info['total_letras'] = str_replace("COLOMBIANOS", "M/CTE", $vl);
    $array_info['consecutivo'] = $co."-".$tipo."-".str_pad($consecutivo, 8, "0", STR_PAD_LEFT);
    $array_info['nit'] = "Nit: ".trim($data[0]['NIT']);
    $array_info['razon_social'] = utf8_decode($data[0]['SUCURSAL']);


    
    $array_info['notas'] = "Notas: ".$data[0]['NOTAS'];

    $array_info['total_db'] = "$".number_format(($data[0]['TOTAL_DB']),2,".",".");
    $array_info['total_cr'] = "$".number_format(($data[0]['TOTAL_CR']),2,".",".");
    $array_info['usuario'] = $data[0]['USUARIO_CREACION'];
    $array_info['firma'] = strtoupper($firma);


    foreach ($data as $reg) {

        if($reg['NIT'] != ""){
            $tercero = trim($reg['NIT'])."-".$reg['ID_SUCURSAL'];
        }else{
            $tercero = "";
        }

        if($reg['DB'] < 1){
            $db = "";
        }else{
            $db = "$".number_format(($reg['DB']),2,".",".");
        }

        if($reg['CR'] < 1){
            $cr = "";
        }else{
            $cr = "$".number_format(($reg['CR']),2,".",".");
        }

        $array_info['data'][] = array(
            'aux' =>  $reg['AUXILIAR'],
            'co' =>  $reg['CO'],
            'un' =>  $reg['UN'],
            'tercero' =>  $tercero,
            'c_costos' =>  "",
            'cpto_fe' =>  $reg['CTO_FE'],
            'd_cru_m_pag' =>  "",
            'db' =>  $db,
            'cr' =>  $cr,
          );

        $array_info['cod'] = $reg['DOCTO_BANCO'].": ".str_pad($reg['NUM_DOCTO_BANCO'], 7, "0", STR_PAD_LEFT);
    }
     
}

/*fin configuración array de datos*/

//PDF

//se incluye la libreria pdf
require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

class PDF extends FPDF{

  function setData($data){
    $this->data = $data;
  }

  function Tabla(){

    $data = $this->data;
    
    if(!empty($data)){

        //$this->Ln(20);
        $this->Ln(1);
        $this->SetFont('Arial','',12); 
        $this->Cell(95,3,"",0,0,'L');
        $this->Cell(17,3,$data['anio'],0,0,'L');
        $this->Cell(7,3,$data['mes'],0,0,'L');
        $this->Cell(23,3,$data['dia'],0,0,'L');
        $this->Cell(35,3,$data['total'],0,0,'L');
        $this->Ln(7);
        $this->SetFont('Arial','',11);
        $this->Cell(5,10,"",0,0,'L');
        $this->Cell(170,10,$data['titular'],0,0,'L');
        $this->Ln();
        $this->Cell(5,10,"",0,0,'L');
        $this->MultiCell(170,10,"          ".$data['total_letras'],0,'L');
        $this->Ln(45);
        
        $this->SetFont('Arial','',8); 
        $this->Cell(175,5,$data['anio']." ".$data['mes']." ".$data['dia']."   ".$data['consecutivo'],0,0,'C');
        $this->Ln(10);

        $this->Cell(40,5,$data['nit'],0,0,'L');
        $this->Cell(100,5,$data['razon_social'],0,0,'L');
        $this->Cell(35,5,$data['cod'],0,0,'L');
        $this->Ln();
        $this->Cell(175,5,$data['notas'],0,0,'L');
        $this->Ln(10);
        $this->SetFont('Arial','B',8); 
        $this->Cell(15,5,"Auxiliar",'TB',0,'L');
        $this->Cell(10,5,"C.O",'TB',0,'L');
        $this->Cell(10,5,"U.N",'TB',0,'L');
        $this->Cell(30,5,"Tercero",'TB',0,'L');
        $this->Cell(15,5,"C. Costos",'TB',0,'L');
        $this->Cell(15,5,"Cpto FE",'TB',0,'L');
        $this->Cell(30,5,"D. Cruce / M. Pago",'TB',0,'L');
        $this->Cell(25,5,"Debitos",'TB',0,'L');
        $this->Cell(25,5,"Creditos",'TB',0,'L');
        $this->Ln();

        $c = 50;

        foreach ($data['data'] as $reg) {
            $this->SetFont('Arial','',8); 
            $this->Cell(15,5,$reg['aux'],0,0,'L');
            $this->Cell(10,5,$reg['co'],0,0,'L');
            $this->Cell(10,5,$reg['un'],0,0,'L');
            $this->Cell(30,5,$reg['tercero'],0,0,'L');
            $this->Cell(15,5,$reg['c_costos'],0,0,'L');
            $this->Cell(15,5,$reg['cpto_fe'],0,0,'L');
            $this->Cell(30,5,$reg['d_cru_m_pag'],0,0,'L');
            $this->Cell(25,5,$reg['db'],0,0,'R');
            $this->Cell(25,5,$reg['cr'],0,0,'R');
            $c = $c - 2;
            $this->Ln();
        }

        $this->Ln($c);
        $this->SetFont('Arial','B',8); 
        $this->Cell(125,5,"Sumas Iguales:",0,0,'R');
        $this->Cell(25,5,$data['total_db'],0,0,'R');
        $this->Cell(25,5,$data['total_cr'],0,0,'R');

        $this->Ln(10);
        $this->SetFont('Arial','',12); 
        $this->Cell(50,5,$data['usuario'],0,0,'L');
        $this->Cell(50,5,$data['firma'],0,0,'L');
           
    }

  }//fin tabla
}

$pdf = new PDF('P','mm','A4');
//se definen las variables extendidas de la libreria FPDF
$pdf->setData($array_info);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$name_file = $cia.'_'.$co.'_'.$tipo.'_'.$consecutivo.'.pdf';
$ruta = Yii::app()->basePath.'/../files/portal_reportes/cheques/'.$name_file;
$pdf->Output($ruta,'F');

?>

<?php
/* @var $this InventariosController */
/* @var $model Inventarios */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

//se reciben los parametros para el reporte
$linea = $model['linea'];

//Estados

if(isset($model['estado'])) {
  $v_estado = $model['estado'];

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

}else{
  $v_estado = "";
  $texto_estados = "TODOS";
  $condicion_estados = "";
}

//Tipos

if(isset($model['tipo'])) {
  $v_tipo = $model['tipo'];

  $array_tipos =  $model['tipo'];
  $tipos = "";
  $texto_tipos = "";
  $condicion_tipos = "";

  foreach ($array_tipos as $a_tipos => $valor) {
    $tipos .= "".$valor.",";
    if($valor == "COM"){
      $texto_tipos .= 'COMPRADOS,';
      $condicion_tipos .= "".$valor.",";
    }
    if($valor == "FAB"){
      $texto_tipos .= 'FABRICADOS,';
      $condicion_tipos .= "".$valor.",";
    }

  }

  $texto_tipos = substr ($texto_tipos, 0, -1);
  $condicion_tipos = substr ($condicion_tipos, 0, -1);

}else{
  $v_tipo = "";
  $texto_tipos = "TODOS";
  $condicion_tipos = "";
}

//se arma la variable opción

if($linea != "" && $condicion_estados != "" && $condicion_tipos == ""){
  $opc = 1;
}
if($linea != "" && $condicion_estados == "" && $condicion_tipos != ""){
  $opc = 2;
}
if($linea != "" && $condicion_estados != "" && $condicion_tipos != ""){
  $opc = 3;
}
if($linea != "" && $condicion_estados == "" && $condicion_tipos == ""){
  $opc = 4;
}


//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];


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

//array con titulos de meses

$mes_act=date('F');

$clave = array_search($mes_act, $ming);

$cont = $clave - 1;

$array_titulo_meses = array();

for ($i=1; $i <= 12; $i++) { 

  $m = str_replace($ming, $mesp, $ming[$clave]);
  $mes = strtoupper($m);
  $mes_abrev = substr($mes, 0, 3);

  $array_titulo_meses[] = $mes_abrev;
  if($clave == 11){

    $clave = 0;
  
  }else{
  
    $clave++;
  
  }
}

/*inicio configuración array de datos*/

$query= "
    SET NOCOUNT ON
    EXEC P_PR_COM_ACUM_VT_LN_EST_TIP
    @OPT = ".$opc.",
    @VAR1 = N'".$linea."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'".$condicion_tipos."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }
    
    function setLinea($linea){
      $this->linea = $linea;
    }

    function setEstados($estados){
      $this->estados = $estados;
    }

    function setTipos($tipos){
      $this->tipos = $tipos;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function setTitulos($titulos){
      $this->titulos = $titulos;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(200,5,utf8_decode('CONTROL DE PEDIDOS ACUMULADO / LÍNEA'),0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(140,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda / Línea: '.$this->linea),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda / Estado(s): '.$this->estados),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda / Tipo(s): '.$this->tipos),0,0,'L');
      $this->Ln();
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',5);
  
      $this->Cell(10,2,utf8_decode('CÓDIGO'),0,0,'L');
      $this->Cell(18,2,utf8_decode('REFERENCIA'),0,0,'L');
      $this->Cell(44,2,utf8_decode('DESCRIPCIÓN'),0,0,'L');
      $this->Cell(8,2,utf8_decode('ESTADO'),0,0,'L');
      $this->Cell(13,2,utf8_decode('UND.'),0,0,'R');

      $array_titulos = $this->titulos;

      foreach ($array_titulos as $llave => $valor) {
        $this->Cell(13,2,utf8_decode($valor),0,0,'R');
      }


      $this->Cell(13,2,utf8_decode('PROM.'),0,0,'R');
      $this->Cell(13,2,utf8_decode('STOCK'),0,0,'R');
      $this->Cell(13,2,utf8_decode('BASE'),0,0,'R');
      $this->Cell(13,2,utf8_decode('EXIST.'),0,0,'R');
      $this->Cell(13,2,utf8_decode('O.C'),0,0,'R');
      $this->Cell(13,2,utf8_decode('A.D'),0,0,'R');
      $this->Cell(13,2,utf8_decode('# DÍAS'),0,0,'R');
      
      $this->Ln(3);   
      
      $this->Cell(10,2,utf8_decode(''),0,0,'L');
      $this->Cell(18,2,utf8_decode(''),0,0,'L');
      $this->Cell(44,2,utf8_decode(''),0,0,'L');
      $this->Cell(8,2,utf8_decode(''),0,0,'L');
      $this->Cell(13,2,utf8_decode('COMPRA'),0,0,'R');

      foreach ($array_titulos as $llave => $valor) {
        $this->Cell(13,2,utf8_decode(''),0,0,'R');
      }

      $this->Cell(13,2,utf8_decode('VTA 6-3-2'),0,0,'R');
      $this->Cell(13,2,utf8_decode('MESES'),0,0,'R');
      $this->Cell(13,2,utf8_decode('PEDIDO'),0,0,'R');
      $this->Cell(13,2,utf8_decode('A LA FECHA'),0,0,'R');
      $this->Cell(13,2,utf8_decode('PEND.'),0,0,'R');
      $this->Cell(13,2,utf8_decode('PEDIR'),0,0,'R');
      $this->Cell(13,2,utf8_decode('CUBRIM.'),0,0,'R');


      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

      foreach ($query1 as $reg1) {
        
        $ITEM                = $reg1 ['CI_ITEM'];
        $DESCRIPCION         = $reg1 ['CI_DESCRIPCION'];
        $REFERENCIA          = $reg1 ['CI_REFERENCIA'];    
        $ESTADO              = $reg1 ['CI_ESTADO'];

        if($reg1 ['CI_LOTE'] == NULL){
          $UND_COMPRA = 0;
        }else{
          $UND_COMPRA = $reg1 ['CI_LOTE'];
        }

        if($reg1 ['CI_MES12'] == NULL){
          $MES12 = 0;
        }else{
          $MES12 = $reg1 ['CI_MES12'];
        }

        if($reg1 ['CI_MES11'] == NULL){
          $MES11 = 0;
        }else{
          $MES11 = $reg1 ['CI_MES11'];
        }

        if($reg1 ['CI_MES10'] == NULL){
          $MES10 = 0;
        }else{
          $MES10 = $reg1 ['CI_MES10'];
        }

        if($reg1 ['CI_MES9'] == NULL){
          $MES9 = 0;
        }else{
          $MES9 = $reg1 ['CI_MES9'];
        }

        if($reg1 ['CI_MES8'] == NULL){
          $MES8 = 0;
        }else{
          $MES8 = $reg1 ['CI_MES8'];
        }

        if($reg1 ['CI_MES7'] == NULL){
          $MES7 = 0;
        }else{
          $MES7 = $reg1 ['CI_MES7'];
        }

        if($reg1 ['CI_MES6'] == NULL){
          $MES6 = 0;
        }else{
          $MES6 = $reg1 ['CI_MES6'];
        }

        if($reg1 ['CI_MES5'] == NULL){
          $MES5 = 0;
        }else{
          $MES5 = $reg1 ['CI_MES5'];
        }

        if($reg1 ['CI_MES4'] == NULL){
          $MES4 = 0;
        }else{
          $MES4 = $reg1 ['CI_MES4'];
        }

        if($reg1 ['CI_MES3'] == NULL){
          $MES3 = 0;
        }else{
          $MES3 = $reg1 ['CI_MES3'];
        }

        if($reg1 ['CI_MES2'] == NULL){
          $MES2 = 0;
        }else{
          $MES2 = $reg1 ['CI_MES2'];
        }

        if($reg1 ['CI_MES1'] == NULL){
          $MES1 = 0;
        }else{
          $MES1 = $reg1 ['CI_MES1'];
        }

        if($reg1 ['CI_PROMEDIO'] == NULL){
          $PROM_VENTAS = 0;
        }else{
          $PROM_VENTAS = $reg1 ['CI_PROMEDIO'];
        }

        if($reg1 ['CI_STOCK'] == NULL){
          $STOCK_MESES = 0;
        }else{
          $STOCK_MESES = $reg1 ['CI_STOCK'];
        }

        if($reg1 ['CI_BASE'] == NULL){
          $BASE_PEDIDOS = 0;
        }else{
          $BASE_PEDIDOS = $reg1 ['CI_BASE'];
        }

        if($reg1 ['CI_EXIS'] == NULL){
          $EXIST_FECHA = 0;
        }else{
          $EXIST_FECHA = $reg1 ['CI_EXIS'];
        }

        if($reg1 ['CI_ENTRAR'] == NULL){
          $O_C_PEND = 0;
        }else{
          $O_C_PEND = $reg1 ['CI_ENTRAR'];
        }

        if($reg1 ['CI_AD_PEDIR'] == NULL){
          $AD_PEDIR = 0;
        }else{
          $AD_PEDIR = $reg1 ['CI_AD_PEDIR'];
        }

        if($reg1 ['CI_DIAS'] == NULL){
          
          $DIAS_CUB = number_format((0),1,".",",");
          $o = 0;

        }else{
          $DC = number_format(($reg1 ['CI_DIAS']),0,".",",");

          if(strlen($DC) > 7){

            $pos_pc = strpos($DC, ',');
            $DIAS_CUB = substr($DC, 0, $pos_pc + 4);
            $o = 1;

          }else{

            $o = 0;
            $DIAS_CUB = number_format(($reg1 ['CI_DIAS']),1,".",",");
          
          }
        }

        $this->SetFont('Arial','',5);
        $this->Cell(10,3,$ITEM,0,0,'L');
        $this->Cell(18,3,substr(utf8_decode($REFERENCIA),0, 20) ,0,0,'L');
        $this->Cell(44,3,substr(utf8_decode($DESCRIPCION), 0, 40),0,0,'L');
        $this->Cell(8,3,substr(utf8_decode($ESTADO), 0, 3),0,0,'L');
        $this->Cell(13,3,number_format(($UND_COMPRA),0,".",","),0,0,'R');

        $this->Cell(13,3,number_format(($MES12),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES11),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES10),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES9),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES8),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES7),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES6),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES5),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES4),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES3),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES2),0,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($MES1),0,".",","),0,0,'R');

        $this->Cell(13,3,number_format(($PROM_VENTAS),1,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($STOCK_MESES),1,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($BASE_PEDIDOS),1,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($EXIST_FECHA),1,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($O_C_PEND),1,".",","),0,0,'R');
        $this->Cell(13,3,number_format(($AD_PEDIR),1,".",","),0,0,'R');
        if($o == 1){
          $this->Cell(13,3,$DIAS_CUB.' ...',0,0,'R');
        }else{
          $this->Cell(13,3,$DIAS_CUB,0,0,'R'); 
        }
        
        $this->Ln();

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
  $pdf->setLinea($linea);
  $pdf->setEstados($texto_estados);
  $pdf->setTipos($texto_tipos);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->setTitulos($array_titulo_meses);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Control_pedidos_acumulado_linea_'.date('Y_m_d_H_i_s').'.pdf');
}

if($opcion == 2){
  //EXCEL

  $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
  $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
  $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

  $objPHPExcel = new Spreadsheet();

  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Reporte');

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'CÓDIGO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'REFERENCIA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'DESCRIPCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'ESTADO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'UND. COMPRA');

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', $array_titulo_meses[0]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', $array_titulo_meses[1]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', $array_titulo_meses[2]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', $array_titulo_meses[3]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', $array_titulo_meses[4]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', $array_titulo_meses[5]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', $array_titulo_meses[6]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', $array_titulo_meses[7]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', $array_titulo_meses[8]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', $array_titulo_meses[9]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', $array_titulo_meses[10]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', $array_titulo_meses[11]);

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'PROM. VENTAS');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'STOCK MESES');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'BASE PEDIDOS');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'EXIST. A LA FECHA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'O.C PEND.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'A.D PEDIR');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', '# DÍAS CUBRIM.');

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:X1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:X1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
   
  $Fila = 2;  

  foreach ($query1 as $reg1) {

    $ITEM                = $reg1 ['CI_ITEM'];
    $DESCRIPCION         = $reg1 ['CI_DESCRIPCION'];
    $REFERENCIA          = $reg1 ['CI_REFERENCIA'];    
    $ESTADO              = $reg1 ['CI_ESTADO'];

    if($reg1 ['CI_LOTE'] == NULL){
      $UND_COMPRA = 0;
    }else{
      $UND_COMPRA = $reg1 ['CI_LOTE'];
    }

    if($reg1 ['CI_MES12'] == NULL){
      $MES12 = 0;
    }else{
      $MES12 = $reg1 ['CI_MES12'];
    }

    if($reg1 ['CI_MES11'] == NULL){
      $MES11 = 0;
    }else{
      $MES11 = $reg1 ['CI_MES11'];
    }

    if($reg1 ['CI_MES10'] == NULL){
      $MES10 = 0;
    }else{
      $MES10 = $reg1 ['CI_MES10'];
    }

    if($reg1 ['CI_MES9'] == NULL){
      $MES9 = 0;
    }else{
      $MES9 = $reg1 ['CI_MES9'];
    }

    if($reg1 ['CI_MES8'] == NULL){
      $MES8 = 0;
    }else{
      $MES8 = $reg1 ['CI_MES8'];
    }

    if($reg1 ['CI_MES7'] == NULL){
      $MES7 = 0;
    }else{
      $MES7 = $reg1 ['CI_MES7'];
    }

    if($reg1 ['CI_MES6'] == NULL){
      $MES6 = 0;
    }else{
      $MES6 = $reg1 ['CI_MES6'];
    }

    if($reg1 ['CI_MES5'] == NULL){
      $MES5 = 0;
    }else{
      $MES5 = $reg1 ['CI_MES5'];
    }

    if($reg1 ['CI_MES4'] == NULL){
      $MES4 = 0;
    }else{
      $MES4 = $reg1 ['CI_MES4'];
    }

    if($reg1 ['CI_MES3'] == NULL){
      $MES3 = 0;
    }else{
      $MES3 = $reg1 ['CI_MES3'];
    }

    if($reg1 ['CI_MES2'] == NULL){
      $MES2 = 0;
    }else{
      $MES2 = $reg1 ['CI_MES2'];
    }

    if($reg1 ['CI_MES1'] == NULL){
      $MES1 = 0;
    }else{
      $MES1 = $reg1 ['CI_MES1'];
    }

    if($reg1 ['CI_PROMEDIO'] == NULL){
      $PROM_VENTAS = 0;
    }else{
      $PROM_VENTAS = $reg1 ['CI_PROMEDIO'];
    }

    if($reg1 ['CI_STOCK'] == NULL){
      $STOCK_MESES = 0;
    }else{
      $STOCK_MESES = $reg1 ['CI_STOCK'];
    }

    if($reg1 ['CI_BASE'] == NULL){
      $BASE_PEDIDOS = 0;
    }else{
      $BASE_PEDIDOS = $reg1 ['CI_BASE'];
    }

    if($reg1 ['CI_EXIS'] == NULL){
      $EXIST_FECHA = 0;
    }else{
      $EXIST_FECHA = $reg1 ['CI_EXIS'];
    }

    if($reg1 ['CI_ENTRAR'] == NULL){
      $O_C_PEND = 0;
    }else{
      $O_C_PEND = $reg1 ['CI_ENTRAR'];
    }

    if($reg1 ['CI_AD_PEDIR'] == NULL){
      $AD_PEDIR = 0;
    }else{
      $AD_PEDIR = $reg1 ['CI_AD_PEDIR'];
    }

    if($reg1 ['CI_DIAS'] == NULL){
      $DIAS_CUB = 0;
    }else{
      $DIAS_CUB = $reg1 ['CI_DIAS'];
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, substr($REFERENCIA,0,20));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, substr($DESCRIPCION,0,40));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, substr($ESTADO, 0, 8));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $UND_COMPRA);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $MES12);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $MES11);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $MES10);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $MES9);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $MES8);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $MES7);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $MES6);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $MES5);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $MES4);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $MES3);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $MES2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $MES1);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $PROM_VENTAS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $STOCK_MESES);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $BASE_PEDIDOS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $EXIST_FECHA);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $O_C_PEND);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $AD_PEDIR);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $DIAS_CUB);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':Q'.$Fila)->getNumberFormat()->setFormatCode('0'); 
    $objPHPExcel->getActiveSheet(0)->getStyle('R'.$Fila.':X'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':X'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1;

  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 24; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Control_pedidos_acumulado_linea_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;
  
}

?>












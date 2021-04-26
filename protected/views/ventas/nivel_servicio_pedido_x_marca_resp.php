<?php
/* @var $this ReporteController */
/* @var $model Reporte */

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
$fecha_inicial = $model->fecha_inicial;
$fecha_final = $model->fecha_final;
$marca_inicial = trim($model->marca_inicial);
$marca_final = trim($model->marca_final);
//opcion: 1. PDF, 2. EXCEL
$opcion = $model->opcion_exp;

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

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

$query= "
EXEC P_PR_COM_REDES_PED_FECH_MARCA
    @MARCA1 = N'".$marca_inicial."',
    @MARCA2 = N'".$marca_final."',
    @FECHA1 = N'".$FechaM1."',
    @FECHA2 = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{
    
    function setFechaInicial($fecha_inicial){
      $this->fecha_inicial = $fecha_inicial;
    }

    function setFechaFinal($fecha_inicial){
      $this->fecha_final = $fecha_inicial;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setMarcaInicial($marca_inicial){
      $this->marca_inicial = $marca_inicial;
    }

    function setMarcaFinal($marca_final){
      $this->marca_final = $marca_final;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(200,5,'PEDIDOS PENDIENTES POR DESPACHO / PEDIDO',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: Fecha del '.$this->fecha_inicial.' al '.$this->fecha_final.' / Marca de '.$this->marca_inicial.' a '.$this->marca_final),0,0,'L');
      $this->Ln();
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',5);
  
      $this->Cell(8,2,utf8_decode('MARCA'),0,0,'L');
      $this->Cell(23,2,utf8_decode('REFERENCIA'),0,0,'L');
      $this->Cell(47,2,utf8_decode('DESCRIPCIÓN'),0,0,'L');
      $this->Cell(10,2,utf8_decode('ESTADO'),0,0,'L');
      $this->Cell(12,2,utf8_decode('FECHA'),0,0,'L');
      $this->Cell(16,2,utf8_decode('DOCUMENTO'),0,0,'L');
      $this->Cell(7,2,utf8_decode('SUC.'),0,0,'L');
      $this->Cell(15,2,utf8_decode('NIT'),0,0,'L');
      $this->Cell(35,2,utf8_decode('RAZÓN SOCIAL'),0,0,'L');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(23,2,utf8_decode('VALOR'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEND. POR'),0,0,'R');
      $this->Cell(14,2,utf8_decode('EN'),0,0,'R');
      
      $this->Ln(3);   
      
      $this->Cell(8,2,utf8_decode('ITEM'),0,0,'L');
      $this->Cell(23,2,utf8_decode(''),0,0,'L');
      $this->Cell(47,2,utf8_decode(''),0,0,'L');
      $this->Cell(10,2,utf8_decode(''),0,0,'L');
      $this->Cell(12,2,utf8_decode(''),0,0,'L');
      $this->Cell(16,2,utf8_decode(''),0,0,'L');
      $this->Cell(7,2,utf8_decode(''),0,0,'L');
      $this->Cell(15,2,utf8_decode(''),0,0,'L');
      $this->Cell(35,2,utf8_decode(''),0,0,'L');
      $this->Cell(14,2,utf8_decode('PEDIDO'),0,0,'R');
      $this->Cell(14,2,utf8_decode('ENVIADO'),0,0,'R');
      $this->Cell(14,2,utf8_decode('REDESP.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEND.'),0,0,'R');
      $this->Cell(23,2,utf8_decode('PEDIDO'),0,0,'R');
      $this->Cell(14,2,utf8_decode('ENTRAR'),0,0,'R');
      $this->Cell(14,2,utf8_decode('EXIST.'),0,0,'R');


      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();
      
      $marca = "";

      $Cant_Ped_sp = 0;
      $Cant_Env_sp = 0;
      $Cant_Redes_sp = 0;
      $Cant_Pend_sp = 0;
      $Vlr_Pedido_sp = 0;
      $P_Entrar_sp = 0;
      $Existencia_sp = 0;

      $Cant_Ped_st = 0;
      $Cant_Env_st = 0;
      $Cant_Redes_st = 0;
      $Cant_Pend_st = 0;
      $Vlr_Pedido_st = 0;
      $P_Entrar_st = 0;
      $Existencia_st = 0;

      foreach ($query1 as $reg1) {

        $marca_act = $reg1['MARCA'];
        
        $ITEM               = $reg1 ['ITEM'];
        $REFERENCIA        = $reg1 ['REFERENCIA'];    
        $DESCRIPCION        = $reg1 ['DESCRIPCION'];    
        $LINEA              = $reg1 ['LINEA'];    
        $ESTADO             = $reg1 ['ESTADO'];    
        $Cant_Ped           = $reg1 ['Cant_Ped'];    
        $Cant_Env           = $reg1 ['Cant_Env'];    
        $Cant_Redes         = $reg1 ['Cant_Redes'];    
        $Cant_Pend          = $reg1 ['Cant_Pend'];    
        $Vlr_Pedido         = $reg1 ['Vlr_Pedido'];    
        $P_Entrar           = $reg1 ['P_Entrar'];    
        $Existencia         = $reg1 ['Existencia']; 
        $fecha              = $reg1 ['FECHA']; 
        $f = new DateTime($fecha);
        $fecha = $f->format('Y-m-d');
        $documento          = $reg1 ['DOCUMENTO']; 
        $sucursal           = $reg1 ['SUCURSAL']; 
        $nit                = $reg1 ['NIT']; 
        $razon_social       = $reg1 ['RAZON_SOCIAL']; 

        if($marca != $marca_act){
          
          if($marca != ""){
            $this->SetFont('Arial','B',5);
            $this->Cell(173,5,'TOTAL '.$marca,0,0,'R');
            $this->Cell(14,5,number_format(($Cant_Ped_sp),0,".",","),0,0,'R');
            $this->Cell(14,5,number_format(($Cant_Env_sp),0,".",","),0,0,'R');
            $this->Cell(14,5,number_format(($Cant_Redes_sp),0,".",","),0,0,'R');
            $this->Cell(14,5,number_format(($Cant_Pend_sp),0,".",","),0,0,'R');
            $this->Cell(23,5,number_format(($Vlr_Pedido_sp),2,".",","),0,0,'R');
            $this->Cell(14,5,number_format(($P_Entrar_sp),0,".",","),0,0,'R');
            $this->Cell(14,5,number_format(($Existencia_sp),0,".",","),0,0,'R');
                                 
            $Cant_Ped_sp = 0;
            $Cant_Env_sp = 0;
            $Cant_Redes_sp = 0;
            $Cant_Pend_sp = 0;
            $Vlr_Pedido_sp = 0;
            $P_Entrar_sp = 0;
            $Existencia_sp = 0;
          }


          $marca = $reg1['MARCA'];
          $this->SetFont('Arial','B',6);
          $this->Ln();
          $this->Cell(280,8, $marca ,0,0,'L');
          $this->Ln();
        }

        $this->SetFont('Arial','',5);
        $this->Cell(8,3,$ITEM,0,0,'L');
        $this->Cell(23,3,substr(utf8_decode($REFERENCIA),0, 20) ,0,0,'L');
        $this->Cell(47,3,substr(utf8_decode($DESCRIPCION), 0, 40),0,0,'L');
        $this->Cell(10,3,substr(utf8_decode($ESTADO), 0, 8),0,0,'L');
        $this->Cell(12,3,utf8_decode($fecha),0,0,'L');
        $this->Cell(16,3,utf8_decode($documento),0,0,'L');
        $this->Cell(7,3,utf8_decode($sucursal),0,0,'L');
        $this->Cell(15,3,utf8_decode($nit),0,0,'L');
        $this->Cell(35,3,substr(utf8_decode($razon_social),0 , 35),0,0,'L');
        $this->Cell(14,3,number_format(($Cant_Ped),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Env),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Redes),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Pend),0,".",","),0,0,'R');
        $this->Cell(23,3,number_format(($Vlr_Pedido),2,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($P_Entrar),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Existencia),0,".",","),0,0,'R');

        $Cant_Ped_sp += $Cant_Ped;
        $Cant_Env_sp += $Cant_Env;
        $Cant_Redes_sp += $Cant_Redes;
        $Cant_Pend_sp += $Cant_Pend;
        $Vlr_Pedido_sp += $Vlr_Pedido;
        $P_Entrar_sp += $P_Entrar;
        $Existencia_sp += $Existencia;

        $Cant_Ped_st += $Cant_Ped;
        $Cant_Env_st += $Cant_Env;
        $Cant_Redes_st += $Cant_Redes;
        $Cant_Pend_st += $Cant_Pend;
        $Vlr_Pedido_st += $Vlr_Pedido;
        $P_Entrar_st += $P_Entrar;
        $Existencia_st += $Existencia;

        $this->Ln();

      }

      //se imprime el total de la ultima marca
      $this->SetFont('Arial','B',5);
      $this->Cell(173,5,'TOTAL '.$marca,0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Ped_sp),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Env_sp),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Redes_sp),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Pend_sp),0,".",","),0,0,'R');
      $this->Cell(23,5,number_format(($Vlr_Pedido_sp),2,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($P_Entrar_sp),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Existencia_sp),0,".",","),0,0,'R');


      $this->SetDrawColor(0,0,0);
      $this->Ln();
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,0,'','T');
      $this->SetDrawColor(255,255,255);
      $this->Ln();

      $this->SetFont('Arial','B',5);
      $this->Cell(173,5,'TOTAL GENERAL',0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Ped_st),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Env_st),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Redes_st),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Cant_Pend_st),0,".",","),0,0,'R');
      $this->Cell(23,5,number_format(($Vlr_Pedido_st),2,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($P_Entrar_st),0,".",","),0,0,'R');
      $this->Cell(14,5,number_format(($Existencia_st),0,".",","),0,0,'R');

      $this->Ln();
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,0,'','T');                            
      $this->Ln();

    }//fin tabla

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','B',6);
        $this->Cell(0,8,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
    }
  }

  $pdf = new PDF('L','mm','A4');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setFechaInicial($fecha_inicial);
  $pdf->setFechaFinal($fecha_final);
  $pdf->setMarcaInicial($marca_inicial);
  $pdf->setMarcaFinal($marca_final);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Ped_pend_des_pedido_x_marca_'.date('Y_m_d_H_i_s').'.pdf');
}

if($opcion == 2){
  //EXCEL

  $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
  $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
  $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

  $objPHPExcel = new Spreadsheet();

  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Reporte');

  $objPHPExcel->getActiveSheet(0)->mergeCells('A1:E1');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Criterio de búsqueda: Fecha del '.$fecha_inicial.' al '.$fecha_final.' / Marca de '.$marca_inicial.' a '.$marca_final);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true);

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'MARCA / ITEM');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'REFERENCIA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'DESCRIPCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'ESTADO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'FECHA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'DOCUMENTO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'SUCURSAL');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'NIT');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', 'RAZÓN SOCIAL');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', 'CANT. PEDIDO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'CANT. ENVIADO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'CANT. REDESP.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'CANT. PEND.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', 'VALOR PEDIDO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', 'PEND. POR ENTRAR');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', 'EN EXIST.');

  $objPHPExcel->getActiveSheet(0)->getStyle('A3:P3')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A3:P3')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
   
  $Fila = 4;  
  $marca = "";

  $Cant_Ped_sp = 0;
  $Cant_Env_sp = 0;
  $Cant_Redes_sp = 0;
  $Cant_Pend_sp = 0;
  $Vlr_Pedido_sp = 0;
  $P_Entrar_sp = 0;
  $Existencia_sp = 0;

  $Cant_Ped_st = 0;
  $Cant_Env_st = 0;
  $Cant_Redes_st = 0;
  $Cant_Pend_st = 0;
  $Vlr_Pedido_st = 0;
  $P_Entrar_st = 0;
  $Existencia_st = 0;

  foreach ($query1 as $reg1) {

    $marca_act = $reg1['MARCA'];
    
    $ITEM               = $reg1 ['ITEM'];
    $REFERENCIA        = $reg1 ['REFERENCIA'];    
    $DESCRIPCION        = $reg1 ['DESCRIPCION'];    
    $LINEA              = $reg1 ['LINEA'];    
    $ESTADO             = $reg1 ['ESTADO'];    
    $Cant_Ped           = $reg1 ['Cant_Ped'];    
    $Cant_Env           = $reg1 ['Cant_Env'];    
    $Cant_Redes         = $reg1 ['Cant_Redes'];    
    $Cant_Pend          = $reg1 ['Cant_Pend'];    
    $Vlr_Pedido         = $reg1 ['Vlr_Pedido'];    
    $P_Entrar           = $reg1 ['P_Entrar'];    
    $Existencia         = $reg1 ['Existencia']; 
    $fecha              = $reg1 ['FECHA']; 
    $f = new DateTime($fecha);
    $fecha = $f->format('Y-m-d');
    $documento          = $reg1 ['DOCUMENTO']; 
    $sucursal           = $reg1 ['SUCURSAL']; 
    $nit                = $reg1 ['NIT']; 
    $razon_social       = $reg1 ['RAZON_SOCIAL'];   

    if($marca != $marca_act){
      
      if($marca != ""){

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, 'TOTAL '.$marca);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Cant_Ped_sp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Cant_Env_sp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Cant_Redes_sp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Cant_Pend_sp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Vlr_Pedido_sp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $P_Entrar_sp);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $Existencia_sp);

        $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':M'.$Fila)->getNumberFormat()->setFormatCode('0');        
        $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
        $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('0');
        $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':P'.$Fila)->getFont()->setBold(true);

        $Fila = $Fila + 1;
                             
        $Cant_Ped_sp = 0;
        $Cant_Env_sp = 0;
        $Cant_Redes_sp = 0;
        $Cant_Pend_sp = 0;
        $Vlr_Pedido_sp = 0;
        $P_Entrar_sp = 0;
        $Existencia_sp = 0;
      }

      $marca = $reg1['MARCA'];

      $Fila = $Fila + 1;
      
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $marca);
      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
      
      $Fila = $Fila + 2;

    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, substr($REFERENCIA,0,20));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, substr($DESCRIPCION,0,40));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, substr($ESTADO, 0, 8));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $fecha);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $documento);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $sucursal);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $nit);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, substr($razon_social,0 , 35));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Cant_Ped);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Cant_Env);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Cant_Redes);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Cant_Pend);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Vlr_Pedido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $P_Entrar);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $Existencia);

    $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':M'.$Fila)->getNumberFormat()->setFormatCode('0');        
    $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1;

    $Cant_Ped_sp += $Cant_Ped;
    $Cant_Env_sp += $Cant_Env;
    $Cant_Redes_sp += $Cant_Redes;
    $Cant_Pend_sp += $Cant_Pend;
    $Vlr_Pedido_sp += $Vlr_Pedido;
    $P_Entrar_sp += $P_Entrar;
    $Existencia_sp += $Existencia;

    $Cant_Ped_st += $Cant_Ped;
    $Cant_Env_st += $Cant_Env;
    $Cant_Redes_st += $Cant_Redes;
    $Cant_Pend_st += $Cant_Pend;
    $Vlr_Pedido_st += $Vlr_Pedido;
    $P_Entrar_st += $P_Entrar;
    $Existencia_st += $Existencia;

  }

  //se imprime el total de la ultima marca
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, 'TOTAL '.$marca);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Cant_Ped_sp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Cant_Env_sp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Cant_Redes_sp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Cant_Pend_sp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Vlr_Pedido_sp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $P_Entrar_sp);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $Existencia_sp);

  $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':M'.$Fila)->getNumberFormat()->setFormatCode('0');        
  $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':P'.$Fila)->getFont()->setBold(true);

  $Fila = $Fila + 1;

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, 'TOTAL GENERAL');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Cant_Ped_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Cant_Env_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Cant_Redes_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Cant_Pend_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $Vlr_Pedido_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $P_Entrar_st);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $Existencia_st);

  $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila.':M'.$Fila)->getNumberFormat()->setFormatCode('0');        
  $objPHPExcel->getActiveSheet(0)->getStyle('N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':P'.$Fila)->getFont()->setBold(true);

  $Fila = $Fila + 1;

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 16; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Ped_pend_des_pedido_x_marca_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;
}

?>
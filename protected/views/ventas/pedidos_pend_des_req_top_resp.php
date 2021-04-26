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
  SET NOCOUNT ON
  EXEC P_PR_COM_REDES_RQM_FECH_ITEMS_TOP
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

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(200,5,utf8_decode('PEDIDOS PENDIENTES POR DESPACHO Y REQUISICIONES (TOP)'),0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: Fecha del '.$this->fecha_inicial.' al '.$this->fecha_final),0,0,'L');
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
      $this->Cell(8,2,utf8_decode('#'),0,0,'L');
      $this->Cell(10,2,utf8_decode('ITEM'),0,0,'L');
      $this->Cell(20,2,utf8_decode('REFERENCIA'),0,0,'L');
      $this->Cell(50,2,utf8_decode('DESCRIPCIÓN'),0,0,'L');
      $this->Cell(14,2,utf8_decode('ESTADO'),0,0,'L');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('CANT.'),0,0,'R');
      $this->Cell(24,2,utf8_decode('VALOR'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PROMEDIO'),0,0,'R');
      $this->Cell(14,2,utf8_decode('STOCK'),0,0,'R');
      $this->Cell(14,2,utf8_decode('BASE'),0,0,'R');
      $this->Cell(14,2,utf8_decode('OC.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('EN'),0,0,'R');
      $this->Cell(14,2,utf8_decode('DIAS'),0,0,'R');

      $this->Ln(3);   
      
      $this->Cell(8,2,utf8_decode(''),0,0,'L');
      $this->Cell(10,2,utf8_decode(''),0,0,'L');
      $this->Cell(20,2,utf8_decode(''),0,0,'L');
      $this->Cell(50,2,utf8_decode(''),0,0,'L');
      $this->Cell(14,2,utf8_decode(''),0,0,'L');
      $this->Cell(14,2,utf8_decode('PEDIDA'),0,0,'R');
      $this->Cell(14,2,utf8_decode('ENVIADA'),0,0,'R');
      $this->Cell(14,2,utf8_decode('REDESP.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEND.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEND. RQM'),0,0,'R');
      $this->Cell(24,2,utf8_decode('PEDIDO'),0,0,'R');      
      $this->Cell(14,2,utf8_decode(''),0,0,'R');
      $this->Cell(14,2,utf8_decode(''),0,0,'R');
      $this->Cell(14,2,utf8_decode(''),0,0,'R');
      $this->Cell(14,2,utf8_decode('PEND.'),0,0,'R');
      $this->Cell(14,2,utf8_decode('EXIST.'),0,0,'R');
      $this->Cell(14,2,utf8_decode(''),0,0,'R');

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

      $Vlr_Pedido_st = 0;

      foreach ($query1 as $reg1) {
        
        $POS                = $reg1 ['POSICION_PARETO'];
        $ITEM               = $reg1 ['ITEM'];
        $REFERENCIA         = $reg1 ['REFERENCIA'];    
        $DESCRIPCION        = $reg1 ['DESCRIPCION'];
        $MARCA              = $reg1 ['MARCA'];    
        $LINEA              = $reg1 ['LINEA'];    
        $ESTADO             = $reg1 ['ESTADO'];
        $Cant_Ped           = $reg1 ['Cant_Ped'];    
        $Cant_Env           = $reg1 ['Cant_Env'];    
        $Cant_Redes         = $reg1 ['Cant_Redes'];    
        $Cant_Pend          = $reg1 ['Cant_Pend'];    
        $Vlr_Pedido         = $reg1 ['Vlr_Pedido'];       
        $Cant_Pend_RQM      = $reg1 ['Cant_Pend_RQM'];
        $CI_PROMEDIO        = $reg1 ['CI_PROMEDIO'];
        $CI_STOCK           = $reg1 ['CI_STOCK'];
        $CI_BASE            = $reg1 ['CI_BASE'];
        $OC_PEND            = $reg1 ['OC_PEND'];
        $Existencia         = $reg1 ['EXISTENCIA'];
        $DIAS               = $reg1 ['DIAS'];

        $this->SetFont('Arial','',6);
        $this->Cell(8,3,$POS,0,0,'L');
        $this->Cell(10,3,$ITEM,0,0,'L');
        $this->Cell(20,3,substr(utf8_decode($REFERENCIA),0, 20) ,0,0,'L');
        $this->Cell(50,3,substr(utf8_decode($DESCRIPCION), 0, 30),0,0,'L');
        $this->Cell(14,3,substr(utf8_decode($ESTADO), 0, 8),0,0,'L');
        $this->Cell(14,3,number_format(($Cant_Ped),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Env),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Redes),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Pend),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Cant_Pend_RQM),0,".",","),0,0,'R');
        $this->Cell(24,3,number_format(($Vlr_Pedido),2,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($CI_PROMEDIO),2,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($CI_STOCK),2,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($CI_BASE),2,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($OC_PEND),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($Existencia),0,".",","),0,0,'R');
        $this->Cell(14,3,number_format(($DIAS),2,".",","),0,0,'R');

        $Vlr_Pedido_st += $Vlr_Pedido;
        
        $this->Ln();

      }

      $this->SetDrawColor(0,0,0);
      $this->Ln();
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,0,'','T');
      $this->SetDrawColor(255,255,255);
      $this->Ln();

      $this->SetFont('Arial','B',5);
      $this->Cell(172,3,'TOTAL GENERAL',0,0,'R');
      $this->SetFont('Arial','B',6);
      $this->Cell(24,3,number_format(($Vlr_Pedido_st),2,".",","),0,0,'R');

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
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Ped_pend_des_rqm_top_'.date('Y_m_d_H_i_s').'.pdf');
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
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Criterio de búsqueda: Fecha del '.$fecha_inicial.' al '.$fecha_final);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true);

  /*Cabecera tabla*/

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '#');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'ITEM');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'REFERENCIA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'DESCRIPCIÓN');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'ESTADO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'CANT. PEDIDA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'CANT. ENVIADA');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'CANT. REDESP.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', 'CANT. PEND.');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', 'CANT. PEND. RQM');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'VALOR PEDIDO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'PROMEDIO');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'STOCK');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', 'BASE');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', 'OC. PEND.'); 
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', 'EN EXIST.'); 
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', 'DIAS'); 


  $objPHPExcel->getActiveSheet(0)->getStyle('A3:Q3')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A3:Q3')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
   
  $Fila = 4;  

  $Vlr_Pedido_st = 0;

  foreach ($query1 as $reg1) {
    
    $POS                = $reg1 ['POSICION_PARETO'];
    $ITEM               = $reg1 ['ITEM'];
    $REFERENCIA         = $reg1 ['REFERENCIA'];    
    $DESCRIPCION        = $reg1 ['DESCRIPCION'];
    $MARCA              = $reg1 ['MARCA'];    
    $LINEA              = $reg1 ['LINEA'];    
    $ESTADO             = $reg1 ['ESTADO'];
    $Cant_Ped           = $reg1 ['Cant_Ped'];    
    $Cant_Env           = $reg1 ['Cant_Env'];    
    $Cant_Redes         = $reg1 ['Cant_Redes'];    
    $Cant_Pend          = $reg1 ['Cant_Pend'];    
    $Vlr_Pedido         = $reg1 ['Vlr_Pedido'];       
    $Cant_Pend_RQM      = $reg1 ['Cant_Pend_RQM'];
    $CI_PROMEDIO        = $reg1 ['CI_PROMEDIO'];
    $CI_STOCK           = $reg1 ['CI_STOCK'];
    $CI_BASE            = $reg1 ['CI_BASE'];
    $OC_PEND            = $reg1 ['OC_PEND'];
    $Existencia         = $reg1 ['EXISTENCIA'];
    $DIAS               = $reg1 ['DIAS'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $POS);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ITEM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, substr($REFERENCIA,0,20));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, substr($DESCRIPCION,0,30));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, substr($ESTADO, 0, 8));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Cant_Ped);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Cant_Env);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Cant_Redes);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $Cant_Pend);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Cant_Pend_RQM);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Vlr_Pedido);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $CI_PROMEDIO);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $CI_STOCK);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $CI_BASE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $OC_PEND);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $Existencia);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $DIAS);

    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':J'.$Fila)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila.':N'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('O'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':Q'.$Fila)->getAlignment()->setHorizontal($alignment_right);

    $Fila = $Fila + 1;
    $Vlr_Pedido_st += $Vlr_Pedido;

  }

  $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, 'TOTAL GENERAL');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $Vlr_Pedido_st);

  $objPHPExcel->getActiveSheet()->getStyle('K'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet()->getStyle('J'.$Fila.':K'.$Fila)->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('J'.$Fila.':K'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 17; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Ped_pend_des_rqm_top_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>
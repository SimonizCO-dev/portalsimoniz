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

/*inicio configuración array de datos*/

$modelo_liq = CControlCms::model()->findByAttributes(array('ID_BASE' => $id));

$info_id = 'ID de liquidación: '.$modelo_liq->ID_BASE.', ';
$info_mes = 'Mes: '.$modelo_liq->Desc_Mes($modelo_liq->MES).', ';
$info_anio = 'Año: '.$modelo_liq->ANIO.', ';
$info_tipo = 'Tipo: '.$modelo_liq->tipo->Dominio.', ';
if($modelo_liq->LIQUIDACION == 1){
  $info_liquidacion = 'Liquidación: INDIVIDUAL, ';
  $info_vendedor = 'Vendedor: '.$modelo_liq->Desc_Vend($modelo_liq->VENDEDOR).', ';
}else{
  $info_liquidacion = 'Liquidación: TODOS LOS VENDEDORES, ';
  $info_vendedor = '';  
}
$info_obs = 'Observaciones: '.$modelo_liq->OBSERVACION.'.';

$info = $info_id.$info_mes.$info_anio.$info_tipo.$info_liquidacion.$info_vendedor.$info_obs;

$tipo = $modelo_liq->TIPO;

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

$query ="EXEC P_CF_CMS_CONS_CMS_TOT @ID = ".$id;

//echo $query;die;

if($opc == 1){
  //PDF

  class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }
    
    function setInfo($info){
      $this->info = $info;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function setTipo($tipo){
      $this->tipo = $tipo;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(200,5,utf8_decode('BASE DE COMISIONES'),0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(140,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',8);
      $this->MultiCell(340,5,utf8_decode('INFO: '.$this->info),0,'J');
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',6);
  
      $this->Cell(15,2,utf8_decode('NIT'),0,0,'L');
      $this->Cell(50,2,utf8_decode('VENDEDOR'),0,0,'L');
      $this->Cell(23,2,utf8_decode('TIPO'),0,0,'L');
      //$this->Cell(19,2,utf8_decode('FECHA INICIAL'),0,0,'L');
      $this->Cell(17,2,utf8_decode('FECHA FINAL'),0,0,'L');
      $this->Cell(21,2,utf8_decode('BASE RECAUDO'),0,0,'R');
      $this->Cell(21,2,utf8_decode('COMIS. RECAUDO'),0,0,'R');
      $this->Cell(21,2,utf8_decode('BASE VENTA'),0,0,'R');
      $this->Cell(21,2,utf8_decode('PRESUPUESTO'),0,0,'R');
      $this->Cell(21,2,utf8_decode('% CUMPLIMIENTO'),0,0,'R');
      $this->Cell(21,2,utf8_decode('% COMIS. VENTA'),0,0,'R');
      $this->Cell(21,2,utf8_decode('COMIS. VENTA'),0,0,'R');
      $this->Cell(21,2,utf8_decode('COMIS. CORRERIA'),0,0,'R');
      $this->Cell(21,2,utf8_decode('BASE AJUSTE'),0,0,'R');
      $this->Cell(21,2,utf8_decode('TOTAL AJUSTE'),0,0,'R');
      $this->Cell(26,2,utf8_decode('TOTAL COMISIÓN'),0,0,'R');
      
      $this->Ln(3);   
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $q1 = Yii::app()->db->createCommand($this->sql)->queryAll();

      if(!empty($q1)){
        foreach ($q1 as $reg1) {

          if($this->tipo == $reg1['TIPO']){

            $NIT_VENDEDOR = $reg1['NIT_VENDEDOR'];
            $NOMBRE_VENDEDOR = $reg1['NOMBRE_VENDEDOR']; 
            $TIPO = Dominio::model()->findByPk($reg1['TIPO'])->Dominio;
            //$FECHA_INICIAL = $reg1['FECHA1'];
            $FECHA_FINAL = $reg1['FECHA2'];
            $BASE_RECAUDO = $reg1['BASE_RECAUDO'];
            $RECAUDO = $reg1['RECAUDO'];
            $BASE_VENTA = $reg1['BASE_VENTA'];
            $PRESUPUESTO = $reg1['PRESUPUESTO'];
            $PTJ_CUMPLIMIENTO = $reg1['PTJ_CUMPLIMIENTO'];
            $CUMPLIMIENTO = $reg1['CUMPLIMIENTO'];
            $VENTA = $reg1['VENTA'];
            $CORRERIA = $reg1['CORRERIA'];
            $BASE_AJUSTE = $reg1['BASE_AJUSTE'];
            $AJUSTE = $reg1['AJUSTE']; 
            $TOTAL = $reg1['TOTAL']; 

            $this->SetFont('Arial','',6);
            $this->Cell(15,3,$NIT_VENDEDOR,0,0,'L');
            $this->Cell(50,3,substr(utf8_decode($NOMBRE_VENDEDOR),0, 35),0,0,'L');
            $this->Cell(23,3,substr(utf8_decode($TIPO), 0, 15),0,0,'L');
            //$this->Cell(19,3,$FECHA_INICIAL,0,0,'L');
            $this->Cell(17,3,$FECHA_FINAL,0,0,'L');
            $this->Cell(21,3,number_format(($BASE_RECAUDO),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($RECAUDO),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($BASE_VENTA),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($PRESUPUESTO),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($PTJ_CUMPLIMIENTO),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($CUMPLIMIENTO),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($VENTA),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($CORRERIA),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($BASE_AJUSTE),2,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($AJUSTE),2,".",","),0,0,'R');
            $this->Cell(26,3,number_format(($TOTAL),2,".",","),0,0,'R');
            $this->Ln();

          }
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

  $pdf = new PDF('L','mm','LEGAL');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setInfo($info);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->setTipo($tipo);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Base_comisiones_'.$id.'_'.date('Y_m_d_H_i_s').'.pdf');
}

if($opc == 2){
    //EXCEL

    $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
    $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
    $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

    $objPHPExcel = new Spreadsheet();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');

    /*Cabecera tabla*/

    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:O1');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'INFO: '.$info);
    $objPHPExcel->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true);

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'NIT');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'VENDEDOR');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'TIPO');
    //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'FECHA INICIAL');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'FECHA FINAL');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'BASE RECAUDO');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'COMIS. RECAUDO');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'BASE VENTA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'PRESUPUESTO');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '% CUMPLIMIENTO');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '% COMIS. VENTA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'COMIS. VENTA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'COMIS. CORRERIA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'BASE AJUSTE');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', 'TOTAL AJUSTE');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', 'TOTAL COMISIÓN');

    $objPHPExcel->getActiveSheet(0)->getStyle('A3:O3')->getAlignment()->setHorizontal($alignment_center);
    $objPHPExcel->getActiveSheet(0)->getStyle('A3:O3')->getFont()->setBold(true);

    /*Inicio contenido tabla*/
        
    $Fila = 4;

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        if($tipo == $reg1['TIPO']){

          $NIT_VENDEDOR = $reg1['NIT_VENDEDOR'];
          $NOMBRE_VENDEDOR = $reg1['NOMBRE_VENDEDOR']; 
          $TIPO = Dominio::model()->findByPk($reg1['TIPO'])->Dominio;
          //$FECHA_INICIAL = $reg1['FECHA1'];
          $FECHA_FINAL = $reg1['FECHA2'];
          $BASE_RECAUDO = $reg1['BASE_RECAUDO'];
          $RECAUDO = $reg1['RECAUDO'];
          $BASE_VENTA = $reg1['BASE_VENTA'];
          $VENTA = $reg1['VENTA'];
          $PRESUPUESTO = $reg1['PRESUPUESTO'];
          $PTJ_CUMPLIMIENTO = $reg1['PTJ_CUMPLIMIENTO'];
          $CUMPLIMIENTO = $reg1['CUMPLIMIENTO'];
          $CORRERIA = $reg1['CORRERIA'];
          $BASE_AJUSTE = $reg1['BASE_AJUSTE'];
          $AJUSTE = $reg1['AJUSTE']; 
          $TOTAL = $reg1['TOTAL'];  

          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $NIT_VENDEDOR);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $NOMBRE_VENDEDOR);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $TIPO);
          //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $FECHA_INICIAL);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $FECHA_FINAL);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $BASE_RECAUDO);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $RECAUDO);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $BASE_VENTA);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $PRESUPUESTO);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $PTJ_CUMPLIMIENTO);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $CUMPLIMIENTO);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $VENTA);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $CORRERIA);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $BASE_AJUSTE);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $AJUSTE);
          $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $TOTAL);


          $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
          $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':O'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
          $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_right);

          $Fila = $Fila + 1;
        } 
          
      }
    }

    /*fin contenido tabla*/

    //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
    $nCols = 15; 

    foreach (range(0, $nCols) as $col) {
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
    }

    $n = 'Base_comisiones_'.$id.'_'.date('Y-m-d H_i_s');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = new Xlsx($objPHPExcel);
    ob_end_clean();
    $objWriter->save('php://output');
    exit;

}

?>
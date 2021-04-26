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
$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

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

/*inicio configuración array de datos*/

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

$query= "
    EXEC P_PR_COM_DOCTOS_ASESOR_FECH
    @FECHA1 = N'".$FechaM1."',
    @FECHA2 = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_sup = array();

foreach ($query1 as $reg) {

  $SUPERVISOR = $reg['SUPERVISOR'];
  $VENDEDOR = $reg['VENDEDOR'];
  $TIPO = $reg['TIPO'];
  $CLIENTE = $reg['CLIENTE'];
  $DIAS = $reg['DIAS'];
  $FECHA = $reg['FECHA'];
  $DOCUMENTO = $reg['DOCUMENTO'];
  $VLR_TOTAL = $reg['VLR_TOTAL'];

  if(!array_key_exists($SUPERVISOR, $array_sup)) {
    $array_sup[$SUPERVISOR] = array();
    $array_sup[$SUPERVISOR][$VENDEDOR] = array();
    $array_sup[$SUPERVISOR][$VENDEDOR]['docs_tipo_'.$TIPO][$DOCUMENTO] = array(
      'CLIENTE' => $CLIENTE,
      'FECHA' => $FECHA,
      'VLR_TOTAL' => $VLR_TOTAL,
    
    );
  }else{
    if(!array_key_exists($VENDEDOR,  $array_sup[$SUPERVISOR])) {
      $array_sup[$SUPERVISOR][$VENDEDOR] = array();
      $array_sup[$SUPERVISOR][$VENDEDOR]['docs_tipo_'.$TIPO][$DOCUMENTO] = array(
        'CLIENTE' => $CLIENTE,
        'FECHA' => $FECHA,
        'VLR_TOTAL' => $VLR_TOTAL,
      
      );  
    }else{
      if(!array_key_exists('docs_tipo_'.$TIPO,  $array_sup[$SUPERVISOR][$VENDEDOR])) {
        $array_sup[$SUPERVISOR][$VENDEDOR]['docs_tipo_'.$TIPO][$DOCUMENTO] = array(
          'CLIENTE' => $CLIENTE,
          'FECHA' => $FECHA,
          'VLR_TOTAL' => $VLR_TOTAL,
        
        );  
      }else{
         if(!array_key_exists($DOCUMENTO,  $array_sup[$SUPERVISOR][$VENDEDOR]['docs_tipo_'.$TIPO])) {
          $array_sup[$SUPERVISOR][$VENDEDOR]['docs_tipo_'.$TIPO][$DOCUMENTO] = array(
            'CLIENTE' => $CLIENTE,
            'FECHA' => $FECHA,
            'VLR_TOTAL' => $VLR_TOTAL,
          
          );  
        }
      }
    }
  }
}

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

    function setData($data){
      $this->data = $data;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(130,5,utf8_decode('CONSULTA DE DOCUMENTOS POR ASESOR'),0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(65,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->Cell(195,5,utf8_decode('Criterio de búsqueda: Fecha del '.$this->fecha_inicial.' al '.$this->fecha_final),0,0,'L');
      $this->Ln();
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',5);
  
      $this->Cell(50,2,utf8_decode('SUPERVISOR / VENDEDOR'),0,0,'L');
      $this->Cell(65,2,utf8_decode('CLIENTE'),0,0,'L');
      $this->Cell(30,2,utf8_decode('FECHA'),0,0,'L');
      $this->Cell(30,2,utf8_decode('DOCUMENTO'),0,0,'L');
      $this->Cell(20,2,utf8_decode('VALOR'),0,0,'R');
      $this->Ln(3);   
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(195,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $array_sup = $this->data;

      if(!empty($array_sup)){

        foreach ($array_sup as $sup => $var_a) {

          $this->SetFont('Arial','B',6);
          $this->Cell(150,4,utf8_decode($sup),0,0,'L');
          $this->Ln();

          $cant_doc1_sup = 0;
          $tot_doc1_sup = 0;
          $cant_doc2_sup = 0;
          $tot_doc2_sup = 0;

          foreach ($var_a as $ven => $docs) {
            
            if(array_key_exists('docs_tipo_1',  $docs)){

              $cant_doc1 = 0;
              $tot_doc1 = 0;

              foreach ($docs['docs_tipo_1'] as $doc => $info) {
                $cant_doc1++;
                $tot_doc1 = $tot_doc1 + $info['VLR_TOTAL'];
                $cant_doc1_sup++;
                $tot_doc1_sup = $tot_doc1_sup + $info['VLR_TOTAL'];

                $this->SetFont('Arial','',5);
                $this->Cell(50,2,substr(utf8_decode($ven),0, 40) ,0,0,'L');
                $this->Cell(65,2,substr(utf8_decode($info['CLIENTE']), 0, 55),0,0,'L');
                $this->Cell(30,2,$info['FECHA'],0,0,'L');
                $this->Cell(30,2,$doc,0,0,'L');
                $this->Cell(20,2,number_format(($info['VLR_TOTAL']),0,".",","),0,0,'R');
                $this->Ln();

              }

              $this->SetDrawColor(0,0,0);
              $this->Cell(50,1,'');
              $this->Cell(145,1,'','T');
              $this->SetFillColor(224,235,255);
              $this->SetTextColor(0);
              $this->Ln(1);  

              $this->SetFont('Arial','B',5);
              $this->Cell(145,1,'TOTAL PEDIDOS',0,0,'R');
              $this->Cell(30,1,$cant_doc1. ' Doc(s)',0,0,'L');
              $this->Cell(20,1,number_format(($tot_doc1),0,".",","),0,0,'R');
              $this->Ln(2);

              $this->SetDrawColor(0,0,0);
              $this->Cell(50,1,'');
              $this->Cell(145,1,'','T');
              $this->SetFillColor(224,235,255);
              $this->SetTextColor(0);
              $this->Ln(1); 

              $cant_doc1 = 0;
              $tot_doc1 = 0;

            }

            if(array_key_exists('docs_tipo_2',  $docs)){

              $cant_doc2 = 0;
              $tot_doc2 = 0;

              foreach ($docs['docs_tipo_2'] as $doc => $info) {
                $cant_doc2++;
                $tot_doc2 = $tot_doc2 + $info['VLR_TOTAL'];
                $cant_doc2_sup++;
                $tot_doc2_sup = $tot_doc2_sup + $info['VLR_TOTAL'];

                $this->SetFont('Arial','',5);
                $this->Cell(50,2,substr(utf8_decode($ven),0, 40) ,0,0,'L');
                $this->Cell(65,2,substr(utf8_decode($info['CLIENTE']), 0, 55),0,0,'L');
                $this->Cell(30,2,$info['FECHA'],0,0,'L');
                $this->Cell(30,2,$doc,0,0,'L');
                $this->Cell(20,2,number_format(($info['VLR_TOTAL']),0,".",","),0,0,'R');
                $this->Ln();

              } 

              $this->SetDrawColor(0,0,0);
              $this->Cell(50,1,'');
              $this->Cell(145,1,'','T');
              $this->SetFillColor(224,235,255);
              $this->SetTextColor(0);
              $this->Ln(1); 

              $this->SetFont('Arial','B',5);
              $this->Cell(145,1,'TOTAL RECAUDOS',0,0,'R');
              $this->Cell(30,1,$cant_doc2.' Doc(s)',0,0,'L');
              $this->Cell(20,1,number_format(($tot_doc2),0,".",","),0,0,'R');
              $this->Ln(2);

              $this->SetDrawColor(0,0,0);
              $this->Cell(50,1,'');
              $this->Cell(145,1,'','T');
              $this->SetFillColor(224,235,255);
              $this->SetTextColor(0);
              $this->Ln(1); 

              $cant_doc2 = 0;
              $tot_doc2 = 0;

            }

          }

          $this->SetFont('Arial','B',5);
          $this->Ln();
          $this->Cell(145,2,utf8_decode('TOTAL '.$sup),0,0,'R');
          $this->Ln();

          $this->SetFont('Arial','B',5);
          $this->Cell(145,2,utf8_decode('TOTAL PEDIDOS'),0,0,'R');
          $this->Cell(30,2,$cant_doc1_sup.' Doc(s)',0,0,'L');
          $this->Cell(20,2,number_format(($tot_doc1_sup),0,".",","),0,0,'R');
          $this->Ln();

          $this->SetFont('Arial','B',5);
          $this->Cell(145,2,utf8_decode('TOTAL RECAUDOS'),0,0,'R');
          $this->Cell(30,2,$cant_doc2_sup.' Doc(s)',0,0,'L');
          $this->Cell(20,2,number_format(($tot_doc2_sup),0,".",","),0,0,'R');
          $this->Ln(3);

          $this->SetDrawColor(0,0,0);
          $this->Cell(195,1,'','T');
          $this->SetFillColor(224,235,255);
          $this->SetTextColor(0);

          $this->Ln();

          $cant_doc1_sup = 0;
          $tot_doc1_sup = 0;
          $cant_doc2_sup = 0;
          $tot_doc2_sup = 0;

        }
      }

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
  $pdf->setFechaInicial($fecha_inicial);
  $pdf->setFechaFinal($fecha_final);
  $pdf->setFechaActual($fecha_act);
  $pdf->setData($array_sup);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Consulta_docs_asesor_'.date('Y_m_d_H_i_s').'.pdf');

}
if($opcion == 2){
    //EXCEL

    $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
    $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
    $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
    $type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

    $objPHPExcel = new Spreadsheet();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');

    /*Cabecera tabla*/

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'SUPERVISOR / VENDEDOR');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'CLIENTE');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'FECHA');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'DOCUMENTO');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'VALOR');

    $objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getAlignment()->setHorizontal($alignment_center);
    $objPHPExcel->getActiveSheet(0)->getStyle('A1:E1')->getFont()->setBold(true);

    /*Inicio contenido tabla*/
        
    $Fila = 3;

    if(!empty($array_sup)){

      foreach ($array_sup as $sup => $var_a) {

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $sup);
        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getFont()->setBold(true);
        $Fila = $Fila + 2; 

        $cant_doc1_sup = 0;
        $tot_doc1_sup = 0;
        $cant_doc2_sup = 0;
        $tot_doc2_sup = 0;

        foreach ($var_a as $ven => $docs) {
          
          if(array_key_exists('docs_tipo_1',  $docs)){

            $cant_doc1 = 0;
            $tot_doc1 = 0;

            foreach ($docs['docs_tipo_1'] as $doc => $info) {
              $cant_doc1++;
              $tot_doc1 = $tot_doc1 + $info['VLR_TOTAL'];
              $cant_doc1_sup++;
              $tot_doc1_sup = $tot_doc1_sup + $info['VLR_TOTAL'];

              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ven);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $info['CLIENTE']);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $info['FECHA']);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $doc);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $info['VLR_TOTAL']);
                  
              $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
              $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
              $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right);

              $Fila = $Fila + 1; 

            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'TOTAL PEDIDOS');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cant_doc1.' Doc(s)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tot_doc1);
            
            $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila)->getAlignment()->setHorizontal($alignment_right);    
            $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
            $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
            $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right);
            $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getFont()->setBold(true);

            $Fila = $Fila + 2;  

            $cant_doc1 = 0;
            $tot_doc1 = 0;

          }

          if(array_key_exists('docs_tipo_2',  $docs)){

            $cant_doc2 = 0;
            $tot_doc2 = 0;

            foreach ($docs['docs_tipo_2'] as $doc => $info) {
              $cant_doc2++;
              $tot_doc2 = $tot_doc2 + $info['VLR_TOTAL'];
              $cant_doc2_sup++;
              $tot_doc2_sup = $tot_doc2_sup + $info['VLR_TOTAL'];

              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ven);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $info['CLIENTE']);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $info['FECHA']);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $doc);
              $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $info['VLR_TOTAL']);
                  
              $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
              $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
              $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right);

              $Fila = $Fila + 1; 

            }


            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'TOTAL RECAUDOS');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cant_doc2.' Doc(s)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tot_doc2);
            
            $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila)->getAlignment()->setHorizontal($alignment_right);    
            $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
            $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
            $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right);
            $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getFont()->setBold(true);

            $Fila = $Fila + 2;  

            $cant_doc2 = 0;
            $tot_doc2 = 0;

          }

        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'TOTAL '.$sup);
        $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila)->getAlignment()->setHorizontal($alignment_right); 
        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getFont()->setBold(true);  
        $Fila = $Fila + 1; 

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'TOTAL PEDIDOS');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cant_doc1_sup.' Doc(s)');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tot_doc1_sup);
        $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila)->getAlignment()->setHorizontal($alignment_right);    
        $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
        $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right);
        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getFont()->setBold(true);
        $Fila = $Fila + 1;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'TOTAL RECAUDOS');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cant_doc2_sup.' Doc(s)');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tot_doc2_sup);
        $objPHPExcel->getActiveSheet(0)->getStyle('C'.$Fila)->getAlignment()->setHorizontal($alignment_right);    
        $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
        $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getAlignment()->setHorizontal($alignment_right); 
        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':E'.$Fila)->getFont()->setBold(true);
        $Fila = $Fila + 2; 

        $cant_doc1_sup = 0;
        $tot_doc1_sup = 0;
        $cant_doc2_sup = 0;
        $tot_doc2_sup = 0;

      }
    }

    /*fin contenido tabla*/

    //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
    $nCols = 5; 

    foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
    }

    $n = 'Consulta_docs_asesor_'.date('Y_m_d_H_i_s');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = new Xlsx($objPHPExcel);
    ob_end_clean();
    $objWriter->save('php://output');
    exit;
    
}

?>












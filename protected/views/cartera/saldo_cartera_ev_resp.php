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

$ev = $model['ev'];

//ev
$query_ev = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 AND Id_Criterio = ".$ev."")->queryRow();

$condicion_ev = $query_ev['Id_Criterio'];
$texto_ev = $query_ev['Criterio_Descripcion'];

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

$query ="
  EXEC P_PR_FIN_CT_SALD_CART_ESTRUCTURA
  @ESTRUCTURA = N'".$ev."'
";

UtilidadesVarias::log($query);

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array_clientes = array();

foreach ($query1 as $reg) {
  $ruta       = $reg['RUTA'];
  $nit_cliente    = $reg['NIT_CLIENTE'];
  $desc_cliente    = $reg['RZ_CLIENTE'];
  $tel_cliente    = $reg['TELEFONO'];
  $dir_cliente    = $reg['DESC_SUC_CLIENTE'];
  $id_suc_cliente = $reg['ID_SUC_CLIENTE'];
  $fecha_doc  = $reg['FECHA_DOCUMENTO'];
  $fecha_venc  = $reg['FECHA_VENCIDO'];
  $id  = $reg['VEND'];
  $n_documento  = $reg['DOCUMENTO'];
  $vendedor = $reg['RZ_VENDEDOR'];
  $cond_pago  = $reg['COND_PAGO'];
  $dias  = $reg['DIAS_VCDO'];
  $valor_inicial  = $reg['TOTAL_DOCUMENTO'];
  $valor_saldo  = $reg['SALDO_DOCUMENTO'];
  $a  = $reg['Entre_1_30'];
  $b  = $reg['Entre_31_60'];
  $c  = $reg['Entre_61_90'];
  $d  = $reg['Entre_90_120'];
  $e  = $reg['Entre_120_Mas'];
  $cupo = $reg['CUPO'];
  $chq = $reg['CHQ'];

  //si no existe el cliente
  if(!array_key_exists($nit_cliente, $array_clientes)) {
    $array_clientes[$nit_cliente] = array();
    $array_clientes[$nit_cliente]['desc_cliente'] = $desc_cliente;
    $array_clientes[$nit_cliente]['suc'] = array();
    $array_clientes[$nit_cliente]['suc'][$id_suc_cliente] = array();
    $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['telefono'] = $tel_cliente;
    $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['direccion'] = $dir_cliente;
    $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['cupo'] = $cupo;
    $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['documentos'] = array();
    $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
      'fecha_doc' => $fecha_doc, 
      'fecha_venc' => $fecha_venc,
      'id_vend' => $id, 
      'vendedor' => $vendedor,
      'cond_pago' => $cond_pago, 
      'dias' => $dias,
      'valor_inicial' => $valor_inicial,
      'valor_saldo' => $valor_saldo,
      'chq' => $chq,
      'a' => $a,
      'b' => $b,
      'c' => $c,
      'd' => $d,
      'e' => $e,
    );    
  }else{
    //si no existe la sucursal
    if(!array_key_exists($id_suc_cliente, $array_clientes[$nit_cliente]['suc'])) {
      $array_clientes[$nit_cliente]['suc'][$id_suc_cliente] = array();
      $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['telefono'] = $tel_cliente;
      $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['direccion'] = $dir_cliente;
      $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['cupo'] = $cupo;
      $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['documentos'] = array();
      $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
        'fecha_doc' => $fecha_doc, 
        'fecha_venc' => $fecha_venc,
        'id_vend' => $id, 
        'vendedor' => $vendedor,
        'cond_pago' => $cond_pago, 
        'dias' => $dias,
        'valor_inicial' => $valor_inicial,
        'valor_saldo' => $valor_saldo,
        'chq' => $chq,
        'a' => $a,
        'b' => $b,
        'c' => $c,
        'd' => $d,
        'e' => $e,
      );      
    }else{
      if(!array_key_exists($n_documento, $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['documentos'])) {
        //si no existe documento
        $array_clientes[$nit_cliente]['suc'][$id_suc_cliente]['documentos'][$n_documento] = array(
          'fecha_doc' => $fecha_doc, 
          'fecha_venc' => $fecha_venc,
          'id_vend' => $id, 
          'vendedor' => $vendedor,
          'cond_pago' => $cond_pago, 
          'dias' => $dias,
          'valor_inicial' => $valor_inicial,
          'valor_saldo' => $valor_saldo,
          'chq' => $chq,
          'a' => $a,
          'b' => $b,
          'c' => $c,
          'd' => $d,
          'e' => $e,
        );        
      } 
    }
  }
}

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setEv($ev){
      $this->ev = $ev;
    }

    function setData($data){
      $this->data = $data;
    }

    function Header(){

      $this->SetFont('Arial','B',12);
      $this->Cell(200,5,'SALDO DE CARTERA POR ESTRUCTURA DE VENTAS',0,0,'L');
      $this->SetFont('Arial','',9);
      $this->Cell(140,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',8);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: Estructura de venta: '.$this->ev),0,0,'L');
      $this->Ln();

      $this->SetFont('Arial','B',8);
      $this->Cell(18,5, utf8_decode('Fecha doc'),0,0,'L');
      $this->Cell(18,5, utf8_decode('Fecha vcto'),0,0,'L');
      $this->Cell(8,5, utf8_decode('ID'),0,0,'L');
      $this->Cell(50,5, utf8_decode('Vendedor'),0,0,'L');
      $this->Cell(26,5, utf8_decode('# documento'),0,0,'L');
      $this->Cell(60,5, utf8_decode('Condición de pago'),0,0,'L');
      $this->Cell(8,5, utf8_decode('Días'),0,0,'R');
      $this->Cell(22,5, utf8_decode('Valor Inicial'),0,0,'R');
      $this->Cell(22,5, utf8_decode('Valor Saldo'),0,0,'R');
      $this->Cell(3,5, utf8_decode(''),0,0,'L');
      $this->Cell(21,5, utf8_decode('Entre 1 - 30'),0,0,'R');
      $this->Cell(21,5, utf8_decode('Entre 31 - 60'),0,0,'R');
      $this->Cell(21,5, utf8_decode('Entre 61 - 90'),0,0,'R');
      $this->Cell(21,5, utf8_decode('Entre 90 - 120'),0,0,'R');
      $this->Cell(21,5, utf8_decode('Más de 120'),0,0,'R');
      $this->Ln();
      
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');                            
      $this->Ln();
      $this->Ln();
    
    }

    function Tabla(){

      $array_clientes = $this->data;

      $i = 1;

      $tf_s = 0;
      $tf_a = 0;
      $tf_b = 0;
      $tf_c = 0;
      $tf_d = 0;
      $tf_e = 0;

      $ptf_a = 0;
      $ptf_b = 0;
      $ptf_c = 0;
      $ptf_d = 0;
      $ptf_e = 0;

      foreach ($array_clientes as $nit_c => $cli_info){

        $nit_cliente = $nit_c;
        $desc_cliente = $cli_info['desc_cliente'];
        $suc_docs = $cli_info['suc'];

        foreach ($suc_docs as $suc => $suc_info) {

          $tel_cliente = $suc_info['telefono'];
          $dir_cliente = $suc_info['direccion'];
          $suc_cliente = $suc;
          $cupo_cliente = $suc_info['cupo'];
        
          $docs_suc_cliente = $suc_info['documentos'];

          $this->SetFont('Arial','B',7);
          $this->Cell(80,3, utf8_decode($desc_cliente),0,0,'L');
          $this->Cell(80,3, utf8_decode($nit_cliente),0,0,'L');
          $this->Cell(80,3, utf8_decode($tel_cliente),0,0,'L');
          $this->Cell(80,3, utf8_decode($dir_cliente.' / '.$suc_cliente),0,0,'L');
          $this->Ln();

          $t_s = 0;
          $t_a = 0;
          $t_b = 0;
          $t_c = 0;
          $t_d = 0;
          $t_e = 0;

          foreach ($docs_suc_cliente as $doc => $doc_info) {

            $fecha_doc = $doc_info['fecha_doc'];
            $fecha_venc = $doc_info['fecha_venc'];
            $id_vend = $doc_info['id_vend'];
            $vendedor = $doc_info['vendedor'];
            $cond_pago = $doc_info['cond_pago'];
            $dias = $doc_info['dias'];
            $valor_inicial = $doc_info['valor_inicial'];
            $valor_saldo = $doc_info['valor_saldo'];
            $chq = $doc_info['chq'];
            $a = $doc_info['a'];
            $b = $doc_info['b'];
            $c = $doc_info['c'];
            $d = $doc_info['d'];
            $e = $doc_info['e'];

            $this->SetFont('Arial','',7);
            $this->Cell(18,3,$fecha_doc,0,0,'L');
            $this->Cell(18,3,$fecha_venc,0,0,'L');
            $this->Cell(8,3,$id_vend,0,0,'L');
            $this->Cell(50,3,substr($vendedor, 0, 26),0,0,'L');
            $this->Cell(26,3,$doc,0,0,'L');
            $this->Cell(60,3,$cond_pago,0,0,'L');
            $this->Cell(8,3,$dias,0,0,'R');
            $this->Cell(22,3,number_format(($valor_inicial),0,".",","),0,0,'R');
            $this->Cell(22,3,number_format(($valor_saldo),0,".",","),0,0,'R');
            $this->SetFont('Arial','B',7);
            $this->Cell(3,3,$chq,0,0,'L');
            $this->SetFont('Arial','',7);
            $this->Cell(21,3,number_format(($a),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($b),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($c),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($d),0,".",","),0,0,'R');
            $this->Cell(21,3,number_format(($e),0,".",","),0,0,'R');
            $this->Ln();

            $t_s = $t_s + $valor_saldo;
            $t_a = $t_a + $a;
            $t_b = $t_b + $b;
            $t_c = $t_c + $c;
            $t_d = $t_d + $d;
            $t_e = $t_e + $e;

          }

          $cupo_disp = $cupo_cliente - $t_s;

          $this->SetFont('Arial','B',6);
          $this->Cell(33,3,utf8_decode('CUPO TOTAL:'),0,0,'L');
          $this->Cell(47,3,number_format(($cupo_cliente),0,".",","),0,0,'L');
          $this->Cell(34,3,utf8_decode('CUPO DISPONIBLE:'),0,0,'L');
          $this->Cell(87,3,number_format(($cupo_disp),0,".",","),0,0,'L');
          $this->Cell(10,3,'TOTALES',0,0,'R');
          $this->Cell(21,3,number_format(($t_s),0,".",","),0,0,'R');
          $this->Cell(3,3,'',0,0,'L');
          $this->Cell(21,3,number_format(($t_a),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_b),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_c),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_d),0,".",","),0,0,'R');
          $this->Cell(21,3,number_format(($t_e),0,".",","),0,0,'R');

          $this->Ln();
          $this->SetDrawColor(0,0,0);
          $this->Cell(340,1,'','T');                            
          $this->Ln(4);


          $tf_s = $tf_s + $t_s;
          $tf_a = $tf_a + $t_a;
          $tf_b = $tf_b + $t_b;
          $tf_c = $tf_c + $t_c;
          $tf_d = $tf_d + $t_d;
          $tf_e = $tf_e + $t_e;

          $t_s = 0;
          $t_a = 0;
          $t_b = 0;
          $t_c = 0;
          $t_d = 0;
          $t_e = 0;

        }
      }

      $this->SetFont('Arial','B',8);
      $this->Cell(211,3,utf8_decode('TOTALES '.$this->ev ),0,0,'L');
      $this->SetFont('Arial','B',7);
      $this->Cell(21,3,number_format(($tf_s),0,".",","),0,0,'R');
      $this->Cell(3,3,'',0,0,'L');
      $this->Cell(21,3,number_format(($tf_a),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_b),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_c),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_d),0,".",","),0,0,'R');
      $this->Cell(21,3,number_format(($tf_e),0,".",","),0,0,'R');
      $this->Ln();

      if($tf_s == 0){
        $ptf_a = 0;
        $ptf_b = 0;
        $ptf_c = 0;
        $ptf_d = 0;
        $ptf_e = 0;        
      }else{
        $ptf_a = $tf_a / $tf_s *100;
        $ptf_b = $tf_b / $tf_s *100;
        $ptf_c = $tf_c / $tf_s *100;
        $ptf_d = $tf_d / $tf_s *100;
        $ptf_e = $tf_e / $tf_s *100;
      }

      $this->Cell(235,3,'',0,0,'L');
      $this->Cell(21,3,number_format(($ptf_a),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_b),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_c),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_d),2,".",",").' %',0,0,'R');
      $this->Cell(21,3,number_format(($ptf_e),2,".",",").' %',0,0,'R');
      $this->Ln();

      $tf_s = 0;
      $tf_a = 0;
      $tf_b = 0;
      $tf_c = 0;
      $tf_d = 0;
      $tf_e = 0;

      $ptf_a = 0;
      $ptf_b = 0;
      $ptf_c = 0;
      $ptf_d = 0;
      $ptf_e = 0;

      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');                            
      $this->Ln();
      $this->Ln();

      $i++;
      
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
  $pdf->setData($array_clientes);
  $pdf->setEv($texto_ev);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Saldo_cartera_est_ventas_'.date('Y_m_d_H_i_s').'.pdf');

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

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Estructura de venta: '.$texto_ev);
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:N1');
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:N1')->getFont()->setBold(true);


  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'Fecha doc');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'Fecha vcto');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'ID');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'Vendedor');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '# documento');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'Condición de pago');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'Días');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'Valor Inicial');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', 'Valor Saldo');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'Entre 1 - 30');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'Entre 31 - 60');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'Entre 61 - 90');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', 'Entre 90 - 120');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', '120 Más de 120');

  $objPHPExcel->getActiveSheet(0)->getStyle('A3:O3')->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('A3:O3')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $Fila = 5;

  $tf_s = 0;
  $tf_a = 0;
  $tf_b = 0;
  $tf_c = 0;
  $tf_d = 0;
  $tf_e = 0;

  $ptf_a = 0;
  $ptf_b = 0;
  $ptf_c = 0;
  $ptf_d = 0;
  $ptf_e = 0;

  foreach ($array_clientes as $nit_c => $cli_info){

    $nit_cliente = $nit_c;
    $desc_cliente = $cli_info['desc_cliente'];
    $suc_docs = $cli_info['suc'];

    foreach ($suc_docs as $suc => $suc_info) {

      $tel_cliente = $suc_info['telefono'];
      $dir_cliente = $suc_info['direccion'];
      $suc_cliente = $suc;
      $cupo_cliente = $suc_info['cupo'];
    
      $docs_suc_cliente = $suc_info['documentos'];

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, utf8_decode($desc_cliente));
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$Fila.':D'.$Fila);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, utf8_decode($nit_cliente));
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$Fila.':F'.$Fila);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, utf8_decode($tel_cliente));
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G'.$Fila.':I'.$Fila);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, utf8_decode($dir_cliente.' / '.$suc_cliente));
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('J'.$Fila.':O'.$Fila);
      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':O'.$Fila)->getFont()->setBold(true);

      $Fila = $Fila + 1;

      $t_s = 0;
      $t_a = 0;
      $t_b = 0;
      $t_c = 0;
      $t_d = 0;
      $t_e = 0;

      foreach ($docs_suc_cliente as $doc => $doc_info) {

        $fecha_doc = $doc_info['fecha_doc'];
        $fecha_venc = $doc_info['fecha_venc'];
        $id_vend = $doc_info['id_vend'];
        $vendedor = $doc_info['vendedor'];
        $cond_pago = $doc_info['cond_pago'];
        $dias = $doc_info['dias'];
        $valor_inicial = $doc_info['valor_inicial'];
        $valor_saldo = $doc_info['valor_saldo'];
        $chq = $doc_info['chq'];
        $a = $doc_info['a'];
        $b = $doc_info['b'];
        $c = $doc_info['c'];
        $d = $doc_info['d'];
        $e = $doc_info['e'];

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $fecha_doc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $fecha_venc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $id_vend);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $vendedor);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $doc);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $cond_pago);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $dias);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $valor_inicial);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $valor_saldo);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $chq);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $a);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $b);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $c);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $d);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $e);

        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':O'.$Fila)->getNumberFormat()->setFormatCode('0');        
        $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_right);

        $Fila = $Fila + 1;

        $t_s = $t_s + $valor_saldo;
        $t_a = $t_a + $a;
        $t_b = $t_b + $b;
        $t_c = $t_c + $c;
        $t_d = $t_d + $d;
        $t_e = $t_e + $e;

      }

      $cupo_disp = $cupo_cliente - $t_s;

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'CUPO TOTAL: ');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $cupo_cliente);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$Fila.':C'.$Fila);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, 'CUPO DISPONIBLE:');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $cupo_disp);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$Fila.':G'.$Fila);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, 'TOTALES');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $t_s);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, '');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $t_a);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $t_b);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $t_c);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $t_d);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $t_e);

      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':O'.$Fila)->getFont()->setBold(true);
      $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila)->getNumberFormat()->setFormatCode('0');
      $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
      $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':O'.$Fila)->getNumberFormat()->setFormatCode('0');        
      $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_right);

      $Fila = $Fila + 2;

      $tf_s = $tf_s + $t_s;
      $tf_a = $tf_a + $t_a;
      $tf_b = $tf_b + $t_b;
      $tf_c = $tf_c + $t_c;
      $tf_d = $tf_d + $t_d;
      $tf_e = $tf_e + $t_e;

      $t_s = 0;
      $t_a = 0;
      $t_b = 0;
      $t_c = 0;
      $t_d = 0;
      $t_e = 0;

    }
  }

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTALES '.$texto_ev);
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$Fila.':H'.$Fila);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $tf_s);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, '');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $tf_a);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $tf_b);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $tf_c);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $tf_d);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $tf_e);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':O'.$Fila)->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':O'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('I'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;

  if($tf_s == 0){
    $ptf_a = 0;
    $ptf_b = 0;
    $ptf_c = 0;
    $ptf_d = 0;
    $ptf_e = 0;        
  }else{
    $ptf_a = $tf_a / $tf_s *100;
    $ptf_b = $tf_b / $tf_s *100;
    $ptf_c = $tf_c / $tf_s *100;
    $ptf_d = $tf_d / $tf_s *100;
    $ptf_e = $tf_e / $tf_s *100;
  }

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, number_format(($ptf_a),2).'%');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, number_format(($ptf_b),2).'%');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, number_format(($ptf_c),2).'%');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, number_format(($ptf_d),2).'%');
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, number_format(($ptf_e),2).'%');

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':O'.$Fila)->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila.':O'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
  $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;

  $tf_s = 0;
  $tf_a = 0;
  $tf_b = 0;
  $tf_c = 0;
  $tf_d = 0;
  $tf_e = 0;

  $ptf_a = 0;
  $ptf_b = 0;
  $ptf_c = 0;
  $ptf_d = 0;
  $ptf_e = 0;

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 15; 

  foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Saldo_cartera_est_ventas_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>
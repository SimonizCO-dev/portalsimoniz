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

//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

//se reciben los parametros para el reporte
$id_linea = $model['lin'];

$desc_linea = ILinea::model()->findByPk($id_linea)->Descripcion;

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

//array con titulos de meses

$array_titulo_meses = array();

$array_fec_cons = array();

$a_act=date("Y",strtotime(date('Y')." - 1 YEAR"));

$m_act=date("m",strtotime(date('M')." + 1 MONTH"));

$dm_act=date("F",strtotime(date('M')." + 1 MONTH"));

$clave = array_search($dm_act, $ming);

$m = str_replace($ming, $mesp, $ming[$clave]);
$mes = strtoupper($m);
$mes_abrev = substr($mes, 0, 3);

$array_titulo_meses[] = $mes_abrev.' '.$a_act;

$inicio = $a_act.'-'.$m_act.'-01';

if ($m_act == 12){

  $a = date("Y",strtotime(date('Y')." + 1 YEAR"));
  $d = date("d",(mktime(0,0,0,$m_act+1,1,$a_act)-1));

  $fin = $a.'-01-'.$d;

}else{

  $d = date("d",(mktime(0,0,0,$m_act+1,1,$a_act)-1));
  $fin = $a_act.'-'.$m_act.'-'.$d;
}

$array_fec_cons[] = array('inicio' => $inicio, 'fin' => $fin);

for ($i=2; $i <= 12; $i++) {

  $m_act=date("m",strtotime(date('M')." + ".$i." MONTH"));

  if($m_act == 1){
    $a_act = date("Y");
  }

  $dm_act=date("F",strtotime(date('M')." + ".$i." MONTH"));

  $clave = array_search($dm_act, $ming); 

  $m = str_replace($ming, $mesp, $ming[$clave]);
  $mes = strtoupper($m);
  $mes_abrev = substr($mes, 0, 3);

  $array_titulo_meses[] = $mes_abrev.' '.$a_act;

  $inicio = $a_act.'-'.$m_act.'-01';

  if ($m_act == 01){

    $a = date("Y");
    $d = date("d",(mktime(0,0,0,$m_act+1,1,$a_act)-1));

    $fin = $a.'-01-'.$d;

  }else{

    $d = date("d",(mktime(0,0,0,$m_act+1,1,$a_act)-1));
    $fin = $a_act.'-'.$m_act.'-'.$d;
  }

  $array_fec_cons[] = array('inicio' => $inicio, 'fin' => $fin);

}

//array data 

$modelo_items=IItem::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado AND Id_Linea=:linea', 'params'=>array(':estado'=>1, ':linea'=>$id_linea)));

$array_data = array();

foreach ($modelo_items as $reg) {

  $i = 0;

  $array_cant = array();

  //Se realiza la consulta de cantidades por mes
  foreach ($array_fec_cons as $rangos) {

    //rangos de fechas para consultas
    $fecha_inicio = $array_fec_cons[$i]['inicio'];
    $fecha_fin = $array_fec_cons[$i]['fin'];

    //SQL

    $query ="
      SELECT
      SUM(DET.Cantidad) AS CANT
      FROM T_PR_I_DOCTO_MOVTO DET
      LEFT JOIN T_PR_I_DOCTO DOC ON DET.Id_Docto = DOC.Id
      LEFT JOIN T_PR_I_ITEM I ON DET.Id_Item = I.Id
      WHERE DOC.Id_Tipo_Docto IN (".Yii::app()->params->sal.",".Yii::app()->params->ajs.",".Yii::app()->params->sad.") AND DOC.Id_Estado = ".Yii::app()->params->apro." AND DOC.Fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."' AND DET.Id_Item = ".$reg->Id."
    ";

    $q = Yii::app()->db->createCommand($query)->queryRow();

    $cantidad = $q['CANT'];

    if($cantidad == 0 || $cantidad == NULL){
      $array_cant[$i] = 0;
    }else{
      $array_cant[$i] = $cantidad;  
    }

    $i = $i + 1;

  }

  $array_data[] = array(
    'codigo' => $reg->Id_Item,
    'referencia' => $reg->Referencia,
    'descripcion' => $reg->Descripcion,
    'val_a' => $array_cant[0],
    'val_b' => $array_cant[1],
    'val_c' => $array_cant[2],
    'val_d' => $array_cant[3],
    'val_e' => $array_cant[4],
    'val_f' => $array_cant[5],
    'val_g' => $array_cant[6],
    'val_h' => $array_cant[7],
    'val_i' => $array_cant[8],
    'val_j' => $array_cant[9],
    'val_k' => $array_cant[10],
    'val_l' => $array_cant[11],
  );

}

if($opcion == 1){
  //PDF

  class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setLinea($linea){
      $this->linea = $linea;
    }

    function setTitulos($titulos){
        $this->titulos = $titulos;
      }

    function setData($data){
      $this->data = $data;
    }

    function Header(){
      $this->SetFont('Arial','B',12);
      $this->Cell(200,5,utf8_decode('Salida de items por línea'),0,0,'L');
      $this->SetFont('Arial','',9);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');

      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->MultiCell(280,5,utf8_decode('Línea: '.$this->linea),0,'J');
      $this->Ln();
     
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',6);

      $this->Cell(10,2,utf8_decode('CÓDIGO'),0,0,'L');
      $this->Cell(25,2,utf8_decode('REFERENCIA'),0,0,'L');
      $this->Cell(65,2,utf8_decode('DESCRIPCIÓN'),0,0,'L');

      $array_titulos = $this->titulos;

      foreach ($array_titulos as $llave => $valor) {
        $this->Cell(15,2,utf8_decode($valor),0,0,'R');
      } 
      
      $this->Ln(3);   
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $data = $this->data;

      if(!empty($data)){
        foreach ($data as $reg) {

          $codigo       = $reg['codigo']; 
          $referencia   =  $reg ['referencia']; 
          $descripcion  = $reg ['descripcion']; 
          $val_a        = $reg ['val_a'];
          $val_b        = $reg ['val_b'];
          $val_c        = $reg ['val_c'];
          $val_d        = $reg ['val_d'];
          $val_e        = $reg ['val_e'];
          $val_f        = $reg ['val_f'];
          $val_g        = $reg ['val_g'];
          $val_h        = $reg ['val_h'];
          $val_i        = $reg ['val_i'];
          $val_j        = $reg ['val_j'];
          $val_k        = $reg ['val_k'];
          $val_l        = $reg ['val_l']; 
          
          $this->SetFont('Arial','',7);
          $this->Cell(10,3,utf8_decode($codigo),0,0,'L');
          $this->Cell(25,3,substr(utf8_decode($referencia), 0, 15),0,0,'L');
          $this->Cell(65,3,substr(utf8_decode($descripcion), 0, 35),0,0,'L');
          $this->Cell(15,3,number_format(($val_a),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_b),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_c),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_d),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_e),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_f),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_g),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_h),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_i),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_j),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_k),0,".",","),0,0,'R');
          $this->Cell(15,3,number_format(($val_l),0,".",","),0,0,'R');
          $this->Ln(); 
        }
      }

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
  $pdf->setFechaActual($fecha_act); 
  $pdf->setTitulos($array_titulo_meses);
  $pdf->setLinea($desc_linea);
  $pdf->setData($array_data);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Salida_items_x_linea_'.date('Y_m_d_H_i_s').'.pdf');

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
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1',  $array_titulo_meses[0]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1',  $array_titulo_meses[1]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1',  $array_titulo_meses[2]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1',  $array_titulo_meses[3]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1',  $array_titulo_meses[4]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1',  $array_titulo_meses[5]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1',  $array_titulo_meses[6]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1',  $array_titulo_meses[7]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1',  $array_titulo_meses[8]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1',  $array_titulo_meses[9]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1',  $array_titulo_meses[10]);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1',  $array_titulo_meses[11]);

  $objPHPExcel->getActiveSheet(0)->getStyle('A1:O1')->getAlignment()->setHorizontal($alignment_center);
  $objPHPExcel->getActiveSheet(0)->getStyle('A1:O1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $Fila = 2;  

  if(!empty($array_data)){
    foreach ($array_data as $reg) {

      $codigo       = $reg['codigo']; 
      $referencia   =  $reg ['referencia']; 
      $descripcion  = $reg ['descripcion']; 
      $val_a        = $reg ['val_a'];
      $val_b        = $reg ['val_b'];
      $val_c        = $reg ['val_c'];
      $val_d        = $reg ['val_d'];
      $val_e        = $reg ['val_e'];
      $val_f        = $reg ['val_f'];
      $val_g        = $reg ['val_g'];
      $val_h        = $reg ['val_h'];
      $val_i        = $reg ['val_i'];
      $val_j        = $reg ['val_j'];
      $val_k        = $reg ['val_k'];
      $val_l        = $reg ['val_l']; 

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $codigo);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $referencia);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $descripcion);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $val_a);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $val_b);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $val_c);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $val_d);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $val_e);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $val_f);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $val_g);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $val_h);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $val_i);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $val_j);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $val_k);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $val_l);

      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':C'.$Fila)->getAlignment()->setHorizontal($alignment_left);
      $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila.':O'.$Fila)->getNumberFormat()->setFormatCode('0'); 
      $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal($alignment_right);

      $Fila = $Fila + 1;

    }
  }

  /*fin contenido tabla*/

  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 15; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Salida_items_x_linea_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>
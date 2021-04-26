<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

//Fin inclusion de librerias

//se reciben los parametros para el reporte

$ti = $model['tipo'];
$consecutivo_i = $model['cons_inicial'];
$consecutivo_f = $model['cons_final'];
$logo_simoniz = Yii::app()->getBaseUrl(true).'/images/logo_simonizco.png';

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

$query_e ="
  SET NOCOUNT ON
  EXEC P_PR_POS_FEV
  @NUM1 = N'".$consecutivo_i."',
  @NUM2 = N'".$consecutivo_f."', 
  @TIPO = '".$ti."'
";

UtilidadesVarias::log($query);

$data_e = Yii::app()->db->createCommand($query_e)->queryAll();

//total registros
$n_items = count($data_e);

$array_cons = array();
$array_header = array();
$array_data = array();
$array_f_pago = array();
$array_footer = array();
$code = "";

if($n_items > 0){

    foreach ($data_e as $i) {

        $fact = explode(" ", $i['Factura']);
        $key = $fact[1];

        if(!array_key_exists($key, $array_cons)){
            $array_cons[$key] = $key;
        }
    }

    foreach ($array_cons as $key => $cons) {
    
        $query1 ="
          SET NOCOUNT ON
          EXEC P_PR_POS_FEV
          @NUM1 = N'".$cons."',
          @NUM2 = N'".$cons."',
          @TIPO = '".$ti."'
        ";

        $data1 = Yii::app()->db->createCommand($query1)->queryAll();

        $query2 ="
          SET NOCOUNT ON
          EXEC P_PR_POS_FEV_MP
          @NUM1 = N'".$cons."',
          @NUM2 = N'".$cons."',
          @TIPO = '".$ti."'
        ";

        $data2 = Yii::app()->db->createCommand($query2)->queryAll();

        //items
        foreach ($data1 as $info) {

            //Cabecera
            $nit = utf8_decode($info['TEXTO1']);
            $direccion = utf8_decode($info['TEXTO2']);
            $telefono = utf8_decode($info['TEXTO3']);
            $empresa = utf8_decode($info['TEXTO4']);

            //Datos factura cliente
            $tvp = substr(utf8_decode($info['TPV']), 0, 38);
            $cajero = substr(utf8_decode($info['Cajero']), 0, 24);
            $fecha = substr(utf8_decode($info['Fecha']), 0, 38);
            $n_factura = substr(utf8_decode($info['Factura']), 0, 38);


            $cliente = substr(utf8_decode($info['Cliente']), 0, 37);
            $ident_cliente = substr(utf8_decode($info['Nit']), 0, 38);
            $direccion_cliente = substr(utf8_decode($info['Direccion']), 0, 38);
            $telefono_cliente = substr(utf8_decode($info['Telefono']), 0, 38);
            $celular = substr(utf8_decode($info['Celular']), 0, 38);
            $vlr_total = number_format(floatval($info['Vlr_Fact_Total']),0,".",",");


            $array_header[$key] = array($nit, $direccion, $telefono, $empresa, $tvp, $cajero, $fecha, $n_factura, $cliente, $ident_cliente, $direccion_cliente, $telefono_cliente, $celular, $vlr_total);

            //Datos de resol.
            
            $n_resol = trim(utf8_decode($info['Nro_Resolucion']));
            $fr = date_create($info['Fech_Resolucion']);
            $fecha_resol = date_format($fr,"Y/m/d");
            $fvr = date_create($info['Fecha_Vcto_Resolucion']);
            $fecha_venc_resol = date_format($fvr,"Y/m/d");
            $prefijo = trim(utf8_decode($info['Prefijo']));
            $num_inicial = trim(utf8_decode($info['Num_Inicial']));
            $num_final = trim(utf8_decode($info['Num_Final']));
            $texto5 = utf8_decode($info['TEXTO5']);
            $texto6 = utf8_decode($info['TEXTO6']);
            $texto7 = utf8_decode($info['TEXTO7']);
            $texto8 = utf8_decode($info['TEXTO8']);
            $texto9 = utf8_decode($info['TEXTO9']);
            $texto10 = utf8_decode($info['TEXTO10']);
            $texto11 = utf8_decode($info['TEXTO11']);
            $texto12 = utf8_decode($info['TEXTO12']);
            $texto13 = utf8_decode($info['TEXTO13']);
            $texto14 = utf8_decode($info['TEXTO14']);
            $texto15 = utf8_decode($info['TEXTO15']);
            $texto16 = utf8_decode($info['TEXTO16']);
            $texto17 = utf8_decode($info['TEXTO17']);
            $texto18 = utf8_decode($info['TEXTO18']);
            $texto19 = utf8_decode($info['TEXTO19']);
            $code = str_replace(" ", "", utf8_decode($n_factura." - ".$fecha));
            
            $array_footer[$key] = array($n_resol, $fecha_resol, $fecha_venc_resol, $prefijo, $num_inicial, $num_final, $texto5, $texto6, $texto7, $texto8, $texto9, $texto10, $texto11, $texto12, $texto13, $texto14, $texto15, $texto16, $texto17, $texto18, $texto19, $code);

            $desc = substr(utf8_decode($info['Descripcion']), 0, 38);
            $ref = $info['Referencia'];
            $cant = number_format(floatval($info['Cantidad']),2,".",",");
            $um = $info['UM'];
            $vlr_uni = $info['Vlr_Uni'];
            $vlr_bruto = $info['Vlr_Bruto'];
            $vlr_dsto = $info['Vlr_Dsto'];
            $vlr_iva = $info['Vlr_Iva'];
            $vlr_total = $info['Vlr_Total'];
            $tipo_venta = $info['Tipo_Venta'];

            $array_data[$cons][] = array($desc, $ref, $cant, $um, $vlr_uni, $vlr_bruto, $vlr_dsto, $vlr_iva, $vlr_total, $tipo_venta);
        }

        //formas de pago
        foreach ($data2 as $info_fp) {

            $tipo = utf8_decode($info_fp['TIPO']);
            $aux = $info_fp['Tarjeta'];
            $vlr = $info_fp['Vlr_Tipo'];

            $array_f_pago[$cons][] = array($tipo, $aux, $vlr);
        }

    }

}

/*fin configuración array de datos*/

//PDF

class PDF extends FPDF{

    protected $col = 0; // Current column
    protected $y0;      // Ordinate of column start

    function setLogoSimoniz($logo_simoniz){
        $this->logo_simoniz = $logo_simoniz;
    }

    function setCons($cons){
        $this->cons = $cons;
    }

    function setDataHeader($header){
        $this->header = $header;
    }

    function setData($data){
        $this->data = $data;
    }

    function setDataPago($datapago){
        $this->datapago = $datapago;
    }

    function setDataFooter($footer){
        $this->footer = $footer;
    }

    function Header()
    {
        $this->Ln(10);
        // Save ordinate
        $this->y0 = $this->GetY();
    }

    function SetCol($col)
    {
        // Set position at a given column
        $this->col = $col;
        $x = 10+$col*65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function AcceptPageBreak()
    {
        // Method accepting or not automatic page break
        if($this->col<2){
            // Go to next column
            $this->SetCol($this->col+1);
            // Set ordinate to top
            $this->SetY($this->y0);
            // Keep on page
            return false;
        }else{
            // Go back to first column
            $this->SetCol(0);
            // Page break
            return true;
        }
    }

    function FactMargin()
    {
        $this->Ln(4);
        // Save ordinate
        $this->y0 = $this->GetY();
    }

    function FactBody($cons)
    {
        $data_header = $this->header;
        $data = $this->data;
        $data_fp = $this->datapago;
        $data_footer = $this->footer;

        if(!empty($data)){

            $n_reg = count($data);
            $this->Image($this->logo_simoniz, 19, 3, 43, 18);
            $this->SetFont('Arial','B',7);
            $this->MultiCell(60,3,$data_header[$cons][0],0,'C');
            $this->MultiCell(60,3,$data_header[$cons][1],0,'C');
            $this->MultiCell(60,3,$data_header[$cons][2],0,'C');
            $this->MultiCell(60,3,$data_header[$cons][3],0,'C');
            $this->Ln(6);
            $this->SetFont('Arial','',7);
            $this->Cell(19,3,'TVP',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][4],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Cajero',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][5],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Fecha',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][6],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Factura de venta',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][7],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Cliente',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][8],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Nit / C.C',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][9],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Direccion',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][10],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Telefono',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][11],0,0,'L');
            $this->Ln();
            $this->Cell(19,3,'Celular',0,0,'L');
            $this->Cell(1,3,':',0,0,'L');
            $this->Cell(40,3,$data_header[$cons][12],0,0,'L');
            $this->Ln();
            $this->MultiCell(60,3,'----------------------------------------------------------------------',0,'C');
            $this->Cell(60,3,'Descripcion de item',0,0,'L');
            $this->Ln();
            $this->Cell(16,3,'Referencia',0,0,'L');
            $this->Cell(8,3,'Cant.',0,0,'L');
            $this->Cell(8,3,'U.M',0,0,'L');
            $this->Cell(14,3,'V/r Uni.',0,0,'R');
            $this->Cell(14,3,'Total',0,0,'R');
            $this->Ln();
            $this->MultiCell(60,3,'----------------------------------------------------------------------',0,'C');

            //$venta = 0;
            $gravada = 0;
            $s_iva = 0;
            $exc = 0;
            $descuentos = 0;
            $iva = 0;
            $total = 0;

            foreach ($data[$cons] as $info_item) {
                $this->Cell(60,3,$info_item[0],0,0,'L');
                $this->Ln();
                $this->Cell(16,3,$info_item[1],0,0,'L');
                $this->Cell(8,3,$info_item[2],0,0,'L');
                $this->Cell(8,3,$info_item[3],0,0,'L');
                $this->Cell(14,3,'$'.number_format(floatval($info_item[4]),0,".",","),0,0,'R');
                $this->Cell(14,3,'$'.number_format(floatval($info_item[8]),0,".",","),0,0,'R');
                
                $tipo_venta = $info_item[9];

                if ($tipo_venta == 'G') {
                    $gravada = $gravada + $info_item[5];
                }

                if ($tipo_venta == 'S') {
                    $s_iva = $s_iva + $info_item[5];
                }

                if ($tipo_venta == 'D') {
                    $exc = $exc + $info_item[5];
                }

                //$venta = $venta + $info_item[5];
                $descuentos = $descuentos + $info_item[6];
                $iva = $iva + $info_item[7];
                $total = $total + $info_item[8];
                $this->Ln();
            }

            $this->MultiCell(60,3,'-----------------[DETALLE DE VALORES]------------------',0,'C');
            $this->Cell(46,3,'GRAVADA',0,0,'L');
            $this->Cell(14,3,'$'.number_format(floatval($gravada),0,".",","),0,0,'R');
            $this->Ln();
            $this->Cell(46,3,'SIN IVA',0,0,'L');
            $this->Cell(14,3,'$'.number_format(floatval($s_iva),0,".",","),0,0,'R');
            $this->Ln();
            $this->Cell(46,3,'EXCLUIDAS',0,0,'L');
            $this->Cell(14,3,'$'.number_format(floatval($exc),0,".",","),0,0,'R');
            $this->Ln();
            $this->Cell(46,3,'DESCUENTO',0,0,'L');
            $this->Cell(14,3,'$'.number_format(floatval($descuentos),0,".",","),0,0,'R');
            $this->Ln();
            $this->Cell(46,3,'IVA',0,0,'L');
            $this->Cell(14,3,'$'.number_format(floatval($iva),0,".",","),0,0,'R');
            $this->Ln();
            $this->Cell(46,3,'TOTAL',0,0,'L');
            $this->Cell(14,3,'$'.number_format(floatval($total),0,".",","),0,0,'R');
            $this->Ln();
            $this->MultiCell(60,3,'----------------------------------------------------------------------',0,'C');
            foreach ($data_fp[$cons] as $info_f_pago) {
                $this->Cell(36,3,$info_f_pago[0],0,0,'L');
                $this->Cell(10,3,$info_f_pago[1],0,0,'L');
                $this->Cell(14,3,number_format(floatval($info_f_pago[2]),0,".",","),0,0,'R');
                $this->Ln();
            }
            $this->MultiCell(60,3,'----------------------------------------------------------------------',0,'C');
            $this->Ln();
            $this->SetFont('Arial','',6);
            $this->MultiCell(60,3,'AUTORIZACION NUMERACION DE FACTURACION',0,'C');
            $this->MultiCell(60,3,'RESOLUC. '.$data_footer[$cons][0].' DESDE',0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][1].' HASTA '.$data_footer[$cons][2],0,'C');
            $this->MultiCell(60,3,'PREFIJO. '.$data_footer[$cons][3].' DEL No. '.$data_footer[$cons][4].' AL '.$data_footer[$cons][5],0,'C');
            $this->MultiCell(60,3,'GRANDES CONTRIBUYENTES',0,'C');
            $this->MultiCell(60,3,'RESOL. 012635 DE 2018/12/14',0,'C');
            $this->Ln();
            $this->MultiCell(60,3,$data_footer[$cons][6],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][7],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][8],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][9],0,'C');

            $this->MultiCell(60,3,$data_footer[$cons][10],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][11],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][12],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][13],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][14],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][15],0,'C');
            $this->Ln();
            $this->SetFont('Arial','B',6);
            $this->MultiCell(60,3,$data_footer[$cons][16],0,'C');
            $this->Ln();
            $this->MultiCell(60,3,$data_footer[$cons][17],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][18],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][19],0,'C');
            $this->MultiCell(60,3,$data_footer[$cons][20],0,'C');
            $this->Ln();

            $newY = $this->getY();
            $newX = $this->getX() + 10;

            $this->Image('http://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$data_footer[$cons][21].'.png', $newX, $newY, 40, 40);
            $this->SetCol(0);
        }
    }

    function PrintFact()
    {
        
        $data_cons = $this->cons;
        $data = $this->data;
        $data_fp = $this->datapago;

        foreach ($data_cons as $key => $consec) {

            $n_items = count($data[$consec]);
            $n_fp = count($data_fp[$consec]);

            $n_r = $n_items + $n_fp;

            if($n_r > 9){
                $this->AddPage();
            }else{
                $pagesize = array(297, 80);
                $this->AddPage('P', $pagesize); 
   
            }

            $this->SetCol(0);
            $this->FactMargin();
            $this->FactBody($consec);
        }

        
    }
}

$pdf = new PDF();   
$pdf->SetAutoPageBreak(true, 40);
$pdf->setLogoSimoniz($logo_simoniz);
$pdf->setCons($array_cons);
$pdf->setDataHeader($array_header);
$pdf->setData($array_data);
$pdf->setDataPago($array_f_pago);
$pdf->setDataFooter($array_footer);
$pdf->PrintFact();

$pdf->Output('D','Factura_POS_'.$ti.'_'.$consecutivo_i.'_'.$consecutivo_f.'.pdf');

?>

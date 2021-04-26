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

$sql = "SELECT DISTINCT ROWID AS ID, NIT_VENDEDOR, NOMBRE_VENDEDOR FROM T_PR_C_VENDEDORES ORDER BY NOMBRE_VENDEDOR";
$q = Yii::app()->db->createCommand($sql)->queryAll();

$array_liq = array();

$i = 0;

if(!empty($q)){
    foreach ($q as $reg) {
        
        $id_vend = $reg['ID'];
        $desc_vend = $reg['NIT_VENDEDOR'].' - '.$reg['NOMBRE_VENDEDOR'];

        //ventas
        $venta = "SELECT DISTINCT FACTURA, FECHA, f200_nit AS NIT, f200_razon_social AS CLIENTE, VLR_SUBTOTAL, PORCENTAJE FROM T_PR_C_VENTAS LEFT JOIN UnoEE1..t350_co_docto_contable ON ROWID_FACTURA=f350_rowid LEFT JOIN UnoEE1..t200_mm_terceros ON f200_rowid=f350_rowid_tercero  WHERE ID_BASE = ".$id." AND ROWID_VENDEDOR = ".$id_vend." ORDER BY FECHA";
        $q_venta = Yii::app()->db->createCommand($venta)->queryAll();

        //acelerador
        $acelerador = "SELECT DISTINCT T2.FACTURA, T2.FECHA, T1.ACELERADOR, T1.VLR_SUBTOTAL, T1.PORCENTAJE FROM T_PR_C_VENTAS_DET AS T1 LEFT JOIN T_PR_C_VENTAS AS T2 ON T1.ROWID_REMISION = T2.ROWID_REMISION WHERE T1.ID_BASE = ".$id." AND T1.ROWID_VENDEDOR = ".$id_vend." ORDER BY FECHA";
        $q_acelerador = Yii::app()->db->createCommand($acelerador)->queryAll();

        //recaudos
        $recaudo = "SELECT DISTINCT RECIBO, FECHA_RECIBO, FACTURA, FECHA_FACTURA, DIAS_PAGO, BASE_RECIBO, PORCENTAJE, VLR_BASE_CMS FROM T_PR_C_RECIBOS WHERE ID_BASE = ".$id." AND ROWID_VENDEDOR = ".$id_vend." ORDER BY FECHA_RECIBO";
        $q_recaudo = Yii::app()->db->createCommand($recaudo)->queryAll();

        //ajustes
        $ajuste = "SELECT DISTINCT RECIBO, FECHA_RECIBO, FACTURA, FECHA_FACTURA, DIAS_PAGO, BASE_RECIBO, PORCENTAJE, VLR_BASE_CMS FROM T_PR_C_AJUSTE_RECIBOS WHERE ID_BASE = ".$id." AND ROWID_VENDEDOR = ".$id_vend." ORDER BY FECHA_RECIBO";
        $q_ajuste = Yii::app()->db->createCommand($ajuste)->queryAll();


        if(!empty($q_venta) || !empty($q_acelerador) || !empty($q_recaudo) || !empty($q_ajuste)){


            $array_liq[$i] = array(
                'id_vend' => $id_vend,
                'desc_vend' => $desc_vend,
                'ventas' => array(),
                'acelerador' => array(),
                'recaudos' => array(),
                'ajustes' => array(),
            ); 


            if(!empty($q_venta)){
                $r = 0;
                foreach ($q_venta as $reg_ventas) {
                    
                    $vlr_comision = $reg_ventas['VLR_SUBTOTAL'] / 100 * $reg_ventas['PORCENTAJE'];

                    $f = date_create($reg_ventas['FECHA']);
                    $fecha = date_format($f, 'Y-m-d');

                    $array_liq[$i]['ventas'][$r] = array(
                        
                        'factura' => $reg_ventas['FACTURA'],
                        'fecha_factura' => $fecha,
                        'nit' => $reg_ventas['NIT'],
                        'cliente' => $reg_ventas['CLIENTE'] ,
                        'base_venta_comision' => $reg_ventas['VLR_SUBTOTAL'],
                        'porcentaje' => $reg_ventas['PORCENTAJE'],
                        'vlr_comision' => $vlr_comision,
                    );   
                    
                    $r++;

                }

            }

            if(!empty($q_acelerador)){
                $q = 0;
                foreach ($q_acelerador as $reg_acelerador) {
                        
                    if($reg_acelerador['PORCENTAJE'] > 0){

                        $f = date_create($reg_acelerador['FECHA']);
                        $fecha = date_format($f, 'Y-m-d');

                        //ACELERADOR
                        $EXP_ACEL = explode("|", $reg_acelerador['ACELERADOR']);

                        $tipo_acel = $EXP_ACEL[0];

                        if($tipo_acel == "POR ITEM"){
                            
                            $item = $EXP_ACEL[1];

                            $q_item = Yii::app()->db->createCommand("SELECT CONCAT(I_ID_ITEM,' - ',I_DESCRIPCION,' - ',I_REFERENCIA) AS ITEM FROM T_CF_ITEMS WHERE I_ID_ITEM = '".$item."'")->queryRow();
                            $desc_item = $q_item['ITEM'];

                            $texto_acel = $tipo_acel.': '.$desc_item;

                        }else{

                            $plan = $EXP_ACEL[1];
                            $q_plan = Yii::app()->db->createCommand("SELECT Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = '".$plan."'")->queryRow();
                            $desc_plan = $q_plan['Plan_Descripcion'];
                            $criterio = $EXP_ACEL[2];
                            $q_criterio = Yii::app()->db->createCommand("SELECT Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Criterio = '".$criterio."'")->queryRow();
                            $desc_criterio = $q_criterio['Criterio_Descripcion'];

                            $texto_acel = $tipo_acel.': '.$desc_plan.' / '.$desc_criterio;

                        }

                        $vlr_comision = $reg_acelerador['VLR_SUBTOTAL'] / 100 * $reg_acelerador['PORCENTAJE'];

                        $array_liq[$i]['acelerador'][$q] = array(
                            
                            'factura' => $reg_acelerador['FACTURA'],
                            'fecha_factura' => $fecha,
                            'criterio_acelerador' => $texto_acel,
                            'base_acel_comision' => $reg_acelerador['VLR_SUBTOTAL'],
                            'porcentaje' => $reg_acelerador['PORCENTAJE'],
                            'vlr_comision' => $vlr_comision,

                        );
                        
                        $q++;

                    }

                }

            }

            if(!empty($q_recaudo)){
                $x= 0;

                foreach ($q_recaudo as $reg_recaudos) {
                    
                    $vlr_comision = $reg_recaudos['VLR_BASE_CMS'];

                    $f_r = date_create($reg_recaudos['FECHA_RECIBO']);
                    $fecha_recibo = date_format($f_r, 'Y-m-d');

                    $f_f = date_create($reg_recaudos['FECHA_FACTURA']);
                    $fecha_factura = date_format($f_f, 'Y-m-d');

                    $array_liq[$i]['recaudos'][$x] = array(
                        'recibo' => $reg_recaudos['RECIBO'],
                        'fecha_recibo' => $fecha_recibo,
                        'factura' => $reg_recaudos['FACTURA'],
                        'fecha_factura' => $fecha_factura,
                        'dias_pago' => $reg_recaudos['DIAS_PAGO'],
                        'base_recaudo_comision' => $reg_recaudos['BASE_RECIBO'],
                        'porcentaje' => $reg_recaudos['PORCENTAJE'],
                        'vlr_comision' => $vlr_comision,
                    );   
                    
                    $x++;



                }
            }

            if(!empty($q_ajuste)){
                $y= 0;

                foreach ($q_ajuste as $reg_ajustes) {
                    
                    $vlr_comision = $reg_ajustes['VLR_BASE_CMS'];

                    $f_r = date_create($reg_ajustes['FECHA_RECIBO']);
                    $fecha_recibo = date_format($f_r, 'Y-m-d');

                    $f_f = date_create($reg_ajustes['FECHA_FACTURA']);
                    $fecha_factura = date_format($f_f, 'Y-m-d');

                    $array_liq[$i]['ajustes'][$y] = array(
                        'recibo' => $reg_ajustes['RECIBO'],
                        'fecha_recibo' => $fecha_recibo,
                        'factura' => $reg_ajustes['FACTURA'],
                        'fecha_factura' => $fecha_factura,
                        'dias_pago' => $reg_ajustes['DIAS_PAGO'],
                        'base_recaudo_comision' => $reg_ajustes['BASE_RECIBO'],
                        'porcentaje' => $reg_ajustes['PORCENTAJE'],
                        'vlr_comision' => $vlr_comision,
                    );   
                    
                    $y++; 

                }
            }

            $i++;
        
        }
    }
}

if($opc == 1){
//PDF

class PDF extends FPDF{

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setInfo($info){
      $this->info = $info;
    }

    function setData($data){
      $this->data = $data;
    }

    function Header(){
      $this->SetFont('Arial','B',9);
      $this->Cell(100,5,utf8_decode('DETALLE BASE DE COMISIONES'),0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(95,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->Ln();
      $this->SetFont('Arial','',7);
      $this->MultiCell(195,5,utf8_decode('INFO: '.$this->info),0,'J');
      $this->Ln();
    }

  function Tabla(){

    $array_liq = $this->data;

    $cont = 1;

    if(!empty($array_liq)){
        foreach ($array_liq as $a) {

            if($cont > 1){
                $this->AddPage();   
            }

            $vendedor = $a['desc_vend'];
            $ventas = $a['ventas'];
            $acelerador = $a['acelerador'];
            $recaudos = $a['recaudos'];
            $ajustes = $a['ajustes'];

            $this->SetFont('Arial','B',7);
            $this->Cell(185,3,utf8_decode($vendedor),0,0,'L');
            $this->Ln();

            //RECAUDOS
            if(!empty($recaudos)){

                $this->Ln();
                $this->SetFont('Arial','B',6);
                $this->Cell(195,5,utf8_decode('RECAUDO'),0,0,'L');
                $this->Ln();

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(30,1,utf8_decode('RECIBO'),0,0,'L');
                $this->Cell(22,1,utf8_decode('FECHA RECIBO'),0,0,'L');
                $this->Cell(30,1,utf8_decode('FACTURA'),0,0,'L');
                $this->Cell(22,1,utf8_decode('FECHA FACTURA'),0,0,'L');
                $this->Cell(13,1,utf8_decode('DIAS'),0,0,'R');
                $this->Cell(30,1,utf8_decode('BASE'),0,0,'R');
                $this->Cell(22,1,utf8_decode('PORCENTAJE'),0,0,'R');
                $this->Cell(26,1,utf8_decode('VALOR'),0,0,'R');

                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();

                $total_base_recaudos = 0;
                $total_recaudos = 0;

                foreach ($recaudos as $rec) {

                    $recibo = $rec['recibo'];
                    $fecha_recibo = $rec['fecha_recibo'];
                    $factura = $rec['factura'];
                    $fecha_factura = $rec['fecha_factura'];
                    $dias_pago = $rec['dias_pago'];
                    $base_recaudo_comision = $rec['base_recaudo_comision'];
                    $porcentaje = $rec['porcentaje'];
                    $vlr_comision = $rec['vlr_comision'];


                    $this->SetFont('Arial','',6);
                    $this->Cell(30,3,utf8_decode($recibo),0,0,'L');
                    $this->Cell(22,3,$fecha_recibo,0,0,'L');
                    $this->Cell(30,3,utf8_decode($factura),0,0,'L');
                    $this->Cell(22,3,$fecha_factura,0,0,'L');
                    $this->Cell(13,3,$dias_pago,0,0,'R');
                    $this->Cell(30,3,number_format(($base_recaudo_comision),2,".",","),0,0,'R');
                    $this->Cell(22,3,number_format(($porcentaje),2,".",","),0,0,'R');
                    $this->Cell(26,3,number_format(($vlr_comision),2,".",","),0,0,'R');
                    $this->Ln();

                    $total_base_recaudos = $total_base_recaudos + $base_recaudo_comision;
                    $total_recaudos = $total_recaudos + $vlr_comision;

                }

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(117,1,utf8_decode('TOTAL BASE / RECAUDO'),0,0,'L');
                $this->Cell(30,1,number_format(($total_base_recaudos),2,".",","),0,0,'R');
                $this->Cell(22,1,'',0,0,'R');
                $this->Cell(26,1,number_format(($total_recaudos),2,".",","),0,0,'R');
                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();


                $total_base_recaudos = 0;
                $total_recaudos = 0;

            }

            //AJUSTES
            if(!empty($ajustes)){

                $this->Ln();
                $this->SetFont('Arial','B',6);
                $this->Cell(195,5,utf8_decode('AJUSTES'),0,0,'L');
                $this->Ln();

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(30,1,utf8_decode('RECIBO'),0,0,'L');
                $this->Cell(22,1,utf8_decode('FECHA RECIBO'),0,0,'L');
                $this->Cell(30,1,utf8_decode('FACTURA'),0,0,'L');
                $this->Cell(22,1,utf8_decode('FECHA FACTURA'),0,0,'L');
                $this->Cell(13,1,utf8_decode('DIAS'),0,0,'R');
                $this->Cell(30,1,utf8_decode('BASE'),0,0,'R');
                $this->Cell(22,1,utf8_decode('PORCENTAJE'),0,0,'R');
                $this->Cell(26,1,utf8_decode('VALOR'),0,0,'R');

                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();

                $total_base_ajustes = 0;
                $total_ajustes = 0;

                foreach ($ajustes as $rec) {

                    $recibo = $rec['recibo'];
                    $fecha_recibo = $rec['fecha_recibo'];
                    $factura = $rec['factura'];
                    $fecha_factura = $rec['fecha_factura'];
                    $dias_pago = $rec['dias_pago'];
                    $base_recaudo_comision = $rec['base_recaudo_comision'];
                    $porcentaje = $rec['porcentaje'];
                    $vlr_comision = $rec['vlr_comision'];


                    $this->SetFont('Arial','',6);
                    $this->Cell(30,3,utf8_decode($recibo),0,0,'L');
                    $this->Cell(22,3,$fecha_recibo,0,0,'L');
                    $this->Cell(30,3,utf8_decode($factura),0,0,'L');
                    $this->Cell(22,3,$fecha_factura,0,0,'L');
                    $this->Cell(13,3,$dias_pago,0,0,'R');
                    $this->Cell(30,3,number_format(($base_recaudo_comision),2,".",","),0,0,'R');
                    $this->Cell(22,3,number_format(($porcentaje),2,".",","),0,0,'R');
                    $this->Cell(26,3,number_format(($vlr_comision),2,".",","),0,0,'R');
                    $this->Ln();

                    $total_base_ajustes = $total_base_ajustes + $base_recaudo_comision;
                    $total_ajustes = $total_ajustes + $vlr_comision;

                }

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(117,1,utf8_decode('TOTAL BASE / AJUSTES'),0,0,'L');
                $this->Cell(30,1,number_format(($total_base_ajustes),2,".",","),0,0,'R');
                $this->Cell(22,1,'',0,0,'R');
                $this->Cell(26,1,number_format(($total_ajustes),2,".",","),0,0,'R');
                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();


                $total_base_ajustes = 0;
                $total_ajustes = 0;

            }

            //VENTAS
            if(!empty($ventas)){

                $this->Ln();
                $this->SetFont('Arial','B',6);
                $this->Cell(195,5,utf8_decode('VENTA'),0,0,'L');
                $this->Ln();

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(30,1,utf8_decode('FACTURA'),0,0,'L');
                $this->Cell(22,1,utf8_decode('FECHA FACTURA'),0,0,'L');
                $this->Cell(20,1,utf8_decode('NIT'),0,0,'L');
                $this->Cell(45,1,utf8_decode('RAZÓN SOCIAL'),0,0,'L');
                $this->Cell(30,1,utf8_decode('BASE'),0,0,'R');
                $this->Cell(22,1,utf8_decode('PORCENTAJE'),0,0,'R');
                $this->Cell(26,1,utf8_decode('VALOR'),0,0,'R');

                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();

                $total_base_ventas = 0;
                $total_ventas = 0;

                $fac_ant = "";

                foreach ($ventas as $ven) {
                    
                    $factura = $ven['factura'];
                    $fecha_factura = $ven['fecha_factura'];
                    $nit = $ven['nit'];
                    $cliente = $ven['cliente'];
                    $base_venta_comision = $ven['base_venta_comision'];
                    $porcentaje = $ven['porcentaje'];
                    $vlr_comision = $ven['vlr_comision'];

                    $this->SetFont('Arial','',6);
                    $this->Cell(30,3,utf8_decode($factura),0,0,'L');
                    $this->Cell(22,3,$fecha_factura,0,0,'L');
                    $this->Cell(20,3,$nit,0,0,'L');
                    $this->Cell(45,3,substr(utf8_decode($cliente),0,40),0,0,'L');
                    $this->Cell(30,3,number_format(($base_venta_comision),2,".",","),0,0,'R');
                    $this->Cell(22,3,number_format(($porcentaje),2,".",","),0,0,'R');
                    $this->Cell(26,3,number_format(($vlr_comision),2,".",","),0,0,'R');
                    $this->Ln();

                    if($fac_ant == $factura){
                        $total_base_ventas = $total_base_ventas + 0;
                        $total_ventas = $total_ventas + 0;
                    }else{
                        $total_base_ventas = $total_base_ventas + $base_venta_comision;
                        $total_ventas = $total_ventas + $vlr_comision;
                    }

                    $fac_ant = $factura;

                }

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(117,1,utf8_decode('TOTAL BASE / VENTA'),0,0,'L');
                $this->Cell(30,1,number_format(($total_base_ventas),2,".",","),0,0,'R');
                $this->Cell(22,1,'',0,0,'R');
                $this->Cell(26,1,number_format(($total_ventas),2,".",","),0,0,'R');

                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();

                $total_base_ventas = 0;
                $total_ventas = 0;

            }

            //ACELERADOR
            if(!empty($acelerador)){

                $this->Ln();
                $this->SetFont('Arial','B',6);
                $this->Cell(195,5,utf8_decode('ACELERADOR'),0,0,'L');
                $this->Ln();

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(30,1,utf8_decode('FACTURA'),0,0,'L');
                $this->Cell(22,1,utf8_decode('FECHA FACTURA'),0,0,'L');
                $this->Cell(65,1,utf8_decode('ACELERADOR'),0,0,'L');
                $this->Cell(30,1,utf8_decode('BASE'),0,0,'R');
                $this->Cell(22,1,utf8_decode('PORCENTAJE'),0,0,'R');
                $this->Cell(26,1,utf8_decode('VALOR'),0,0,'R');

                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();

                $total_base_acelerador = 0;
                $total_acelerador = 0;

                foreach ($acelerador as $ace) {

                    $factura = $ace['factura'];
                    $fecha_factura = $ace['fecha_factura'];
                    $criterio_acelerador = $ace['criterio_acelerador'];
                    $base_acel_comision = $ace['base_acel_comision'];
                    $porcentaje = $ace['porcentaje'];
                    $vlr_comision = $ace['vlr_comision'];

                    $this->SetFont('Arial','',6);
                    $this->Cell(30,3,utf8_decode($factura),0,0,'L');
                    $this->Cell(22,3,$fecha_factura,0,0,'L');
                    $this->Cell(65,3,substr(utf8_decode($criterio_acelerador),0,60),0,0,'L');
                    $this->Cell(30,3,number_format(($base_acel_comision),2,".",","),0,0,'R');
                    $this->Cell(22,3,number_format(($porcentaje),2,".",","),0,0,'R');
                    $this->Cell(26,3,number_format(($vlr_comision),2,".",","),0,0,'R');
                    $this->Ln();

                    $total_base_acelerador = $total_base_acelerador + $base_acel_comision;
                    $total_acelerador = $total_acelerador + $vlr_comision;


                }

                //linea superior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);
                $this->Ln();  

                $this->SetFont('Arial','B',6);

                $this->Cell(117,1,utf8_decode('TOTAL BASE / ACELERADOR'),0,0,'L');
                $this->Cell(30,1,number_format(($total_base_acelerador),2,".",","),0,0,'R');
                $this->Cell(22,1,'',0,0,'R');
                $this->Cell(26,1,number_format(($total_acelerador),2,".",","),0,0,'R');

                $this->Ln(2);

                //linea inferior a la cabecera de la tabla
                $this->SetDrawColor(0,0,0);
                $this->Cell(195,1,'','T');
                $this->SetFillColor(224,235,255);
                $this->SetTextColor(0);

                $this->Ln();

                $total_base_acelerador = 0;
                $total_acelerador = 0;

            }

        $cont++;

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
$pdf->setInfo($info);
$pdf->setFechaActual($fecha_act);
$pdf->setData($array_liq);
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 40);
$pdf->AddPage();
$pdf->Tabla();
ob_end_clean();
$pdf->Output('D','Detalle_base_comisiones_'.$id.'_'.date('Y-m-d H_i_s').'.pdf');
}

if($opc == 2){
    //EXCEL

    $alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
    $alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
    $alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

    $objPHPExcel = new Spreadsheet();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');

    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:H1');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'INFO: '.$info);
    $objPHPExcel->getActiveSheet(0)->getStyle('A1')->getFont()->setBold(true);

    $Fila = 3;

    if(!empty($array_liq)){
        foreach ($array_liq as $a) {

            $vendedor = $a['desc_vend'];
            $ventas = $a['ventas'];
            $acelerador = $a['acelerador'];
            $recaudos = $a['recaudos'];
            $ajustes = $a['ajustes'];

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $vendedor);
            $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
            $Fila = $Fila + 2;

            //RECAUDOS
            if(!empty($recaudos)){

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'RECAUDO');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
                
                $Fila++;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'RECIBO');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, 'FECHA RECIBO');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, 'FECHA FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, 'DIAS');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, 'BASE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, 'PORCENTAJE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, 'VALOR');

                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getFont()->setBold(true);
                
                $Fila++;

                $total_base_recaudos = 0;
                $total_recaudos = 0;

                foreach ($recaudos as $rec) {

                    $recibo = $rec['recibo'];
                    $fecha_recibo = $rec['fecha_recibo'];
                    $factura = $rec['factura'];
                    $fecha_factura = $rec['fecha_factura'];
                    $dias_pago = $rec['dias_pago'];
                    $base_recaudo_comision = $rec['base_recaudo_comision'];
                    $porcentaje = $rec['porcentaje'];
                    $vlr_comision = $rec['vlr_comision'];

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $recibo);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $fecha_recibo);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $fecha_factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $dias_pago);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $base_recaudo_comision);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $porcentaje);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $vlr_comision);

                    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
                    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':H'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_right);

                    $Fila++;

                    $total_base_recaudos = $total_base_recaudos + $base_recaudo_comision;
                    $total_recaudos = $total_recaudos + $vlr_comision;

                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTAL BASE / RECAUDO');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $total_base_recaudos);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $total_recaudos);

                $total_base_recaudos = 0;
                $total_recaudos = 0;

                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_right);

                $Fila++;

            }

            //AJUSTES
            if(!empty($ajustes)){

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'AJUSTES');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
                
                $Fila++;


                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'RECIBO');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, 'FECHA RECIBO');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, 'FECHA FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, 'DIAS');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, 'BASE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, 'PORCENTAJE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, 'VALOR');

                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getFont()->setBold(true);
                
                $Fila++;

                $total_base_ajustes = 0;
                $total_ajustes = 0;

                foreach ($ajustes as $rec) {

                    $recibo = $rec['recibo'];
                    $fecha_recibo = $rec['fecha_recibo'];
                    $factura = $rec['factura'];
                    $fecha_factura = $rec['fecha_factura'];
                    $dias_pago = $rec['dias_pago'];
                    $base_recaudo_comision = $rec['base_recaudo_comision'];
                    $porcentaje = $rec['porcentaje'];
                    $vlr_comision = $rec['vlr_comision'];

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $recibo);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $fecha_recibo);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $fecha_factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $dias_pago);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $base_recaudo_comision);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $porcentaje);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $vlr_comision);

                    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('0');
                    $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila.':H'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_right);

                    $Fila++;

                    $total_base_ajustes = $total_base_ajustes + $base_recaudo_comision;
                    $total_ajustes = $total_ajustes + $vlr_comision;

                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTAL BASE / AJUSTES');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $total_base_ajustes);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $total_ajustes);

                $total_base_ajustes = 0;
                $total_ajustes = 0;

                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':H'.$Fila)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('H'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':H'.$Fila)->getAlignment()->setHorizontal($alignment_right);

                $Fila++;

            }

            //VENTAS
            if(!empty($ventas)){

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'VENTA');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
                
                $Fila++;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, 'FECHA FACTURA ');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'NIT');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, 'RAZÓN SOCIAL');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, 'BASE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, 'PORCENTAJE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, 'VALOR');
                
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':G'.$Fila)->getFont()->setBold(true);   
                
                $Fila++;

                $total_base_ventas = 0;
                $total_ventas = 0;

                $fac_ant = "";

                foreach ($ventas as $ven) {

                    $factura = $ven['factura'];
                    $fecha_factura = $ven['fecha_factura'];
                    $nit = $ven['nit'];
                    $cliente = $ven['cliente'];
                    $base_venta_comision = $ven['base_venta_comision'];
                    $porcentaje = $ven['porcentaje'];
                    $vlr_comision = $ven['vlr_comision'];

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $fecha_factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $nit);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cliente);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $base_venta_comision);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $porcentaje);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $vlr_comision);

                    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':D'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':G'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila.':G'.$Fila)->getAlignment()->setHorizontal($alignment_right);


                    $Fila++;

                    if($fac_ant == $factura){
                        $total_base_ventas = $total_base_ventas + 0;
                        $total_ventas = $total_ventas + 0;
                    }else{
                        $total_base_ventas = $total_base_ventas + $base_venta_comision;
                        $total_ventas = $total_ventas + $vlr_comision;
                    }

                    $fac_ant = $factura;

                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTAL BASE / VENTA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $total_base_ventas);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $total_ventas);

                $total_base_ventas = 0;
                $total_ventas = 0;

                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':G'.$Fila)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet(0)->getStyle('E'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':G'.$Fila)->getAlignment()->setHorizontal($alignment_right);

                $Fila++;

            }

            //ACELERADOR
            if(!empty($acelerador)){

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'ACELERADOR');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getFont()->setBold(true);
                
                $Fila++;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, 'FECHA FACTURA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, 'ACELERADOR');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, 'BASE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, 'PORCENTAJE');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, 'VALOR');
                
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getFont()->setBold(true);   
                
                $Fila++;

                $total_base_acelerador = 0;
                $total_acelerador = 0;

                foreach ($acelerador as $ace) {

                    $factura = $ace['factura'];
                    $fecha_factura = $ace['fecha_factura'];
                    $criterio_acelerador = $ace['criterio_acelerador'];
                    $base_acel_comision = $ace['base_acel_comision'];
                    $porcentaje = $ace['porcentaje'];
                    $vlr_comision = $ace['vlr_comision'];

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $fecha_factura);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $criterio_acelerador);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $base_acel_comision);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $porcentaje);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $vlr_comision);

                    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':C'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                    $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila.':F'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                    $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_right);


                    $Fila++;
                    $total_base_acelerador = $total_base_acelerador + $base_acel_comision;
                    $total_acelerador = $total_acelerador + $vlr_comision;


                }

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, 'TOTAL BASE / ACELERADOR');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $total_base_acelerador);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, '');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $total_acelerador);

                $total_base_acelerador = 0;
                $total_acelerador = 0;

                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet(0)->getStyle('D'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('F'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
                $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila)->getAlignment()->setHorizontal($alignment_left);
                $objPHPExcel->getActiveSheet(0)->getStyle('B'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_right);

                $Fila++;

            }

            $Fila++;
        }
    }

    /*fin contenido tabla*/

    //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
    $nCols = 8; 

    foreach (range(0, $nCols) as $col) {
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
    }

    $n = 'Detalle_base_comisiones_'.$id.'_'.date('Y-m-d H_i_s');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = new Xlsx($objPHPExcel);
    ob_end_clean();
    $objWriter->save('php://output');
    exit;

}

?>
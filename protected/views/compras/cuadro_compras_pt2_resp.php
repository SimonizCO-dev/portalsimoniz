<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

spl_autoload_register(array('YiiBase','autoload'));

//Fin inclusion de librerias

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

//se reciben los parametros para el reporte
$opcion = $model['opcion'];
$origen = trim($model['origen']);
$marca = trim($model['marca']);
$linea = trim($model['linea']);
$des_ora = trim($model['des_ora']);
$proveedor = trim($model['proveedor']);

/*inicio configuración array de datos*/

if($opcion == 1){
  /*ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";  
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Estado(s): '.$estados.'.';

}

if($opcion == 2){
  /*ORIGEN - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Estado(s): '.$estados.'.';

}

if($opcion == 3){
  /*ORIGEN*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'',
    @VAR2 = N'',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'.';

}

if($opcion == 4){
  /*CRI/MARCA - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$marca."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Marca: '.$marca.'. / Estado(s): '.$estados.'.';

}

if($opcion == 5){
  /*CRI/LINEA - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$linea."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Línea: '.$linea.'. / Estado(s): '.$estados.'.';

}

if($opcion == 6){
  /*CRI/ORACLE - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$des_ora."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Desc. oracle: '.$des_ora.'. / Estado(s): '.$estados.'.';

}

if($opcion == 7){
  /*CRI/MARCA*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$marca."',
    @VAR2 = N'',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Marca: '.$marca.'.';

}

if($opcion == 8){
  /*CRI/LINEA*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$linea."',
    @VAR2 = N'',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Línea: '.$linea.'.';

}

if($opcion == 9){
  /*CRI/ORACLE*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$des_ora."',
    @VAR2 = N'',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'Desc. oracle: '.$des_ora.'.';

}

if($opcion == 10){
  /*ORIGEN - CRI/MARCA*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$marca."',
    @VAR2 = N'',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Marca: '.$marca.'.';

}

if($opcion == 11){
  /*ORIGEN - CRI/LINEA*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$linea."',
    @VAR2 = N'',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Línea: '.$linea.'.';

}

if($opcion == 12){
  /*ORIGEN - CRI/ORACLE*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$des_ora."',
    @VAR2 = N'',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Desc. oracle: '.$des_ora.'.';

}

if($opcion == 13){
  /*ORIGEN - CRI/MARCA - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$marca."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Marca: '.$marca.'. / Estado(s): '.$estados.'.';

}

if($opcion == 14){
  /*ORIGEN - CRI/LINEA - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$linea."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Línea: '.$linea.'. / Estado(s): '.$estados.'.';

}

if($opcion == 15){
  /*ORIGEN - CRI/ORACLE - ESTADO*/

  $array_estados =  $model['estado'];
  $estados = "";
  foreach ($array_estados as $a_estados => $valor) {
    $estados .= "".$valor.",";
  }
  $estados = substr ($estados, 0, -1);

  $texto_estados = $estados;
  $condicion_estados = $estados;

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'".$des_ora."',
    @VAR2 = N'".$condicion_estados."',
    @VAR3 = N'".$origen."',
    @VAR4 = N''
  ";

  $criterio = 'Origen: '.$origen.'. / Desc. oracle: '.$des_ora.'. / Estado(s): '.$estados.'.';

}


if($opcion == 16){
  /*PROVEEDOR*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'',
    @VAR2 = N'',
    @VAR3 = N'',
    @VAR4 = N'".$proveedor."'
  ";

  $criterio = 'Proveedor: '.$proveedor.'.';

}

if($opcion == 17){
  /*SIN FILTROS*/

  $query= "
    SET NOCOUNT ON
    EXEC P_PR_COMP_EXP_ACUM_VT_MC_EST_TIP
    @OPT = ".$opcion.",
    @VAR1 = N'',
    @VAR2 = N'',
    @VAR3 = N'',
    @VAR4 = N''
  ";

  $criterio = 'SIN FILTROS.';

}

UtilidadesVarias::log($query);

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

/*fin configuración array de datos*/

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');
/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:Z1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Criterio de búsqueda / '.$criterio);

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'CÓDIGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'PROVEEDOR REAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'REFERENCIA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'DESCRIPCIÓN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'UND. EMP. PRINCIPAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'UND. DE COMPRA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', '2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3', '3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3', '6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3', 'PROM. VENTAS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3', 'STOCK MESES');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3', 'PROM. MAX. * MESES STOCK');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3', 'EXIST. A LA FECHA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3', 'IMPORT');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P3', 'O.C PEND.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q3', 'CANT. TOTAL INV. DISP. + INV. PROC. + OC');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R3', 'CUB. MESES INV. DISP.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S3', 'CUB. MESES TOTAL INV. DISP. + OC');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T3', 'A.D PEDIR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U3', 'FORECAST PROX. 6 MESES '.date('Y').' (SUM)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V3', 'FORECAST MES - STOCK '.date('Y'));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W3', 'A PEDIR FINAL');

$objPHPExcel->getActiveSheet(0)->getStyle('A3:W3')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A3:W3')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
 
$Fila = 4;  

foreach ($query1 as $reg1) {

  $ITEM                = $reg1 ['CI_ITEM'];

  if($reg1 ['CI_PROVEEDOR'] == NULL){
    $PROVEEDOR = '-';
  }else{
    $PROVEEDOR = $reg1 ['CI_PROVEEDOR'];
  } 

  $REFERENCIA          = $reg1 ['CI_REFERENCIA'];
  $DESCRIPCION         = $reg1 ['CI_DESCRIPCION'];
  $ESTADO              = $reg1 ['CI_ESTADO'];

  if($reg1 ['UMP'] == NULL){
    $UND_EMP_P = '-';
  }else{
    $UND_EMP_P = $reg1 ['UMP'];
  }

  if($reg1 ['CI_LOTE'] == NULL){
    $UND_COMPRA = 0;
  }else{
    $UND_COMPRA = $reg1 ['CI_LOTE'];
  }

  if($reg1 ['PROM2'] == NULL){
    $PROM2 = 0;
  }else{
    $PROM2 = $reg1 ['PROM2'];
  }

  if($reg1 ['PROM3'] == NULL){
    $PROM3 = 0;
  }else{
    $PROM3 = $reg1 ['PROM3'];
  }

  if($reg1 ['PROM6'] == NULL){
    $PROM6 = 0;
  }else{
    $PROM6 = $reg1 ['PROM6'];
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
    $PROMEDIO_MAXIMO_x_MESES_STOCK = 0;
  }else{
    $PROMEDIO_MAXIMO_x_MESES_STOCK = $reg1 ['CI_BASE'];
  }

  if($reg1 ['CI_EXIS'] == NULL){
    $EXIST_FECHA = 0;
  }else{
    $EXIST_FECHA = $reg1 ['CI_EXIS'];
  }

  if($reg1 ['CI_IMP'] == NULL){
    $IMP = 0;
  }else{
    $IMP = $reg1 ['CI_IMP'];
  }

  if($reg1 ['CI_ENTRAR'] == NULL){
    $O_C_PEND = 0;
  }else{
    $O_C_PEND = $reg1 ['CI_ENTRAR'];
  }

  if($reg1 ['CI_TOTAL'] == NULL){
    $CANTIDAD_TOTAL_INV_DISP_INV_PROC_OC = 0;
  }else{
    $CANTIDAD_TOTAL_INV_DISP_INV_PROC_OC = $reg1 ['CI_TOTAL'];
  }

  if($reg1 ['CI_CUB_MES'] == NULL){
    $CUBRI_MESES_INV_DISPONIBLE = 0;
  }else{
    $CUBRI_MESES_INV_DISPONIBLE = $reg1 ['CI_CUB_MES'];
  }

  

  if($reg1 ['PROM2'] == 0 && $reg1 ['PROM2'] == 0 && $reg1 ['PROM2'] == 0){
    $CUBRI_MESES_TOTAL_INV_DISPON_OC = 0;
  }else{
    if($reg1 ['CI_CUB_MES_TOT'] == NULL){
      $CUBRI_MESES_TOTAL_INV_DISPON_OC = 0;
    }else{
      $CUBRI_MESES_TOTAL_INV_DISPON_OC = $reg1 ['CI_CUB_MES_TOT'];
    } 
  }


  if($reg1 ['CI_AD_PEDIR'] == NULL){
    $AD_PEDIR = 0;
  }else{
    $AD_PEDIR = $reg1 ['CI_AD_PEDIR'];
  }

  if($reg1 ['CI_FORCAST'] == NULL){
    $FORECAST = 0;
  }else{
    $FORECAST = $reg1 ['CI_FORCAST'];
  }

  if($reg1 ['CI_FORCAST_MES'] == NULL){
    $FORECAST_MES = 0;
  }else{
    $FORECAST_MES = $reg1 ['CI_FORCAST_MES'];
  }

  if($reg1 ['CI_PEDIR_TOT'] == NULL){
    $A_PEDIR_TOTAL = 0;
  }else{
    $A_PEDIR_TOTAL = $reg1 ['CI_PEDIR_TOT'];
  }
      
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ITEM);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $PROVEEDOR);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $REFERENCIA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $DESCRIPCION);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $ESTADO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $UND_EMP_P);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $UND_COMPRA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $PROM2);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $PROM3);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $PROM6);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $PROM_VENTAS);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $STOCK_MESES);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $PROMEDIO_MAXIMO_x_MESES_STOCK);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $EXIST_FECHA);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $IMP);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $O_C_PEND);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $CANTIDAD_TOTAL_INV_DISP_INV_PROC_OC);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $CUBRI_MESES_INV_DISPONIBLE);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $CUBRI_MESES_TOTAL_INV_DISPON_OC);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$Fila, $AD_PEDIR);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $FORECAST);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $FORECAST_MES);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $A_PEDIR_TOTAL);

  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':J'.$Fila)->getNumberFormat()->setFormatCode('0'); 
  $objPHPExcel->getActiveSheet(0)->getStyle('K'.$Fila.':P'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
  $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila.':V'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');
  $objPHPExcel->getActiveSheet(0)->getStyle('G'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_right);
  $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila.':V'.$Fila)->getAlignment()->setHorizontal($alignment_right);
 
  $Fila = $Fila + 1;

}


  //se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
  $nCols = 24; 

  foreach (range(0, $nCols) as $col) {
      $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
  }

  $n = 'Cuadro_compras_pt_importados_'.date('Y_m_d_H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = new Xlsx($objPHPExcel);
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

?>

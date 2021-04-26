<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//se reciben los parametros para el reporte

set_time_limit(0);

//Inclusion de librerias

spl_autoload_unregister(array('YiiBase','autoload'));

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

$query ="
SET NOCOUNT ON
EXEC P_PR_CAL_CRM_CASE
  @Fecha_Ini = N'".$FechaM1."',
  @Fecha_Fin = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

/*inicio configuración array de datos*/

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Consecutivo PQRS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Estado PQRS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Referencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Cantidad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Tipificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', '# Reclamación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Área');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Tipo PQRS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Tipo solución');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Descripción caso');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Fecha PQRS');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Fecha auditoría');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Estado auditoría');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:M1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:M1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
 
$resp = Yii::app()->db->createCommand($query)->queryAll();

$Fila = 2; 

foreach ($resp as $reg) {

  $consecutivo        = $reg['Consecutivo_PQRS'];
  $estado_pqrs        = $reg['Estado_PQRS'];
  $referencia         = $reg['Referencia'];
  $cantidad           = $reg['Cantidad'];
  $tipificacion       = $reg['Tipificacion'];
  $numero_reclamacion = $reg['Numero_Reclamacion'];
  $area_empresa       = $reg['Area_Empresa'];
  $tipo_pqrs          = $reg['Tipo_PQRS'];
  $tipo_solucion      = $reg['Tipo_Solucion'];
  $descripcion_caso   = $reg['descripcion_caso'];
  $fecha_pqrs         = $reg['Fecha_PQRS'];
  $fecha_auditoria    = $reg['Fecha_Auditoria'];
  $estado_auditoria   = $reg['Estado_Auditoria'];

  if(is_numeric ($referencia)){

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $consecutivo);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $estado_pqrs);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $referencia);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cantidad);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tipificacion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $numero_reclamacion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $area_empresa);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $tipo_pqrs);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $tipo_solucion);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $descripcion_caso);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $fecha_pqrs);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $fecha_auditoria);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $estado_auditoria);

    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
    
    $Fila = $Fila + 1;


  }else{
    $array_ref = json_decode($referencia);

    if(!empty($array_ref)){
      foreach ($array_ref as $key => $val) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $consecutivo);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $estado_pqrs);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $val);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cantidad);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tipificacion);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $numero_reclamacion);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $area_empresa);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $tipo_pqrs);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $tipo_solucion);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $descripcion_caso);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $fecha_pqrs);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $fecha_auditoria);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $estado_auditoria);

        $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
        
        $Fila = $Fila + 1;
      }
    }else{
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $consecutivo);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $estado_pqrs);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, '');
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $cantidad);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $tipificacion);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $numero_reclamacion);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $area_empresa);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $tipo_pqrs);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $tipo_solucion);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $descripcion_caso);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $fecha_pqrs);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $fecha_auditoria);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $estado_auditoria);

      $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':M'.$Fila)->getAlignment()->setHorizontal($alignment_left);
      
      $Fila = $Fila + 1;
    } 
  }
 
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 13; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Calidad_PQRS_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>
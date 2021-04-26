<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte
$unidad_gerencia = $model['unidad_gerencia'];
$estado = $model['estado'];

$ug = UnidadGerencia::model()->findByPk($unidad_gerencia)->Unidad_Gerencia;

//se obtiene la cadena de la fecha actual
$diatxt=date('l');
$dianro=date('d');
$mestxt=date('F');
$anionro=date('Y');
// *********** traducciones y modificaciones de fechas a letras y a español ***********
$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$ming=array('January', 'Febrary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo');
$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$diaesp=str_replace($ding, $desp, $diatxt);
$mesesp=str_replace($ming, $mesp, $mestxt);

$fecha_act= $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;

/*inicio configuración array de datos*/

if($estado == 1){

  $query ="
  SELECT 
  DISTINCT
  '' AS Novedad,
  TI.Dominio AS Tipo_Ident, 
  P.Identificacion, 
  CONCAT (P.Apellido, ' ', P.Nombre) AS Empleado, 
  P.Fecha_Nacimiento, 
  E.Descripcion AS Empresa,
  R.Regional,
  CI.Ciudad AS Ciu_Res,  
  UG.Unidad_Gerencia,
  A.Area,
  S.Subarea,
  C.Cargo,
  CONCAT(CC.Codigo, ' - ', CC.Centro_Costo) AS CC, 
  HP.Fecha_Ingreso,
  HP.Salario,
  CASE HP.Salario_Flexible
      WHEN  1 THEN 'SI'
      WHEN 0 THEN 'NO'
      ELSE NULL
  END AS Salario_F,
  HP.Fecha_Retiro
  FROM TH_CONTRATO_EMPLEADO HP 
  LEFT JOIN TH_EMPLEADO P ON HP.Id_Empleado = P.Id_Empleado 
  LEFT JOIN TH_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio 
  LEFT JOIN TH_DOMINIO GE ON P.Id_Genero = GE.Id_Dominio 
  LEFT JOIN TH_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa 
  LEFT JOIN TH_REGIONAL R ON P.Id_Regional_Labor = R.Id_Regional
  LEFT JOIN TH_CIUDAD CI ON P.Id_Ciudad_Residencia = CI.Id_Ciudad 
  LEFT JOIN TH_UNIDAD_GERENCIA UG ON HP.Id_Unidad_Gerencia = UG.Id_Unidad_Gerencia 
  LEFT JOIN TH_AREA A ON HP.Id_Area = A.Id_Area
  LEFT JOIN TH_SUBAREA S ON HP.Id_Subarea = S.Id_Subarea
  LEFT JOIN TH_CARGO C ON HP.Id_Cargo = C.Id_Cargo
  LEFT JOIN TH_CENTRO_COSTO CC ON HP.Id_Centro_Costo = CC.Id_C_Costo
  WHERE HP.Id_Unidad_Gerencia = ".$unidad_gerencia." AND HP.Id_M_Retiro IS NULL
  ORDER BY 4,6,9,10,11,12,13 ASC 
  ";

}else{

  $query ="
    SET NOCOUNT ON EXEC [dbo].[CONS_EMP_UG]
    @UG = ".$unidad_gerencia."
  ";

}

/*fin configuración array de datos*/

//EXCEL

// Se inactiva el autoloader de yii
spl_autoload_unregister(array('YiiBase','autoload'));   

require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel.php';

//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
spl_autoload_register(array('YiiBase','autoload'));

$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
$objPHPExcel->setActiveSheetIndex();

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'Novedades');
$objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'Tipo identificación');
$objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'No. identificación');
$objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Empleado');
$objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Fec. nacimiento');
$objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Empresa');
$objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Regional');
$objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Ciudad de residencia');
$objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'Unidad de gerencia');
$objPHPExcel->setActiveSheetIndex()->setCellValue('J1', 'Área');
$objPHPExcel->setActiveSheetIndex()->setCellValue('K1', 'Subárea');
$objPHPExcel->setActiveSheetIndex()->setCellValue('L1', 'Cargo');
$objPHPExcel->setActiveSheetIndex()->setCellValue('M1', 'Centro de costo');
$objPHPExcel->setActiveSheetIndex()->setCellValue('N1', 'Fec. ingreso');
$objPHPExcel->setActiveSheetIndex()->setCellValue('O1', 'Salario');
$objPHPExcel->setActiveSheetIndex()->setCellValue('P1', 'Salario flexible ?');

$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $tipo_ident          = $reg1 ['Tipo_Ident']; 
  $ident               = $reg1 ['Identificacion']; 
  $empleado            = $reg1 ['Empleado'];  
  $fecha_nacimiento    = $reg1 ['Fecha_Nacimiento'];

  $empresa = $reg1 ['Empresa']; 
  
  if($reg1 ['Regional'] != ""){
    $regional = $reg1 ['Regional']; 
  }else{
    $regional = "-";
  }

  if($reg1 ['Ciu_Res'] != ""){
    $ciudad_res = $reg1 ['Ciu_Res']; 
  }else{
    $ciudad_res = "-";
  }

  if($reg1 ['Unidad_Gerencia'] != ""){
    $ug = $reg1 ['Unidad_Gerencia']; 
  }else{
    $ug = "-";
  }

  if($reg1 ['Area'] != ""){
    $area = $reg1 ['Area']; 
  }else{
    $area = "-";
  }

  if($reg1 ['Subarea'] != ""){
    $subarea = $reg1 ['Subarea']; 
  }else{
    $subarea = "-";
  }

  if($reg1 ['Cargo'] != ""){
    $cargo = $reg1 ['Cargo']; 
  }else{
    $cargo = "-";
  }

  if($reg1 ['CC'] != ""){
    $cc = $reg1 ['CC']; 
  }else{
    $cc = "-";
  }

  $fecha_ingreso = $reg1 ['Fecha_Ingreso']; 
  $salario = number_format($reg1['Salario'],0);

  if($reg1 ['Salario_F'] != ""){
    $sf = $reg1 ['Salario_F']; 
  }else{
    $sf = "-";
  }

  $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, '');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $tipo_ident);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $ident);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $empleado);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $fecha_nacimiento);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $empresa);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $regional);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $ciudad_res);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $ug);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, $area);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $subarea);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$Fila, $cargo);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$Fila, $cc);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('N'.$Fila, $fecha_ingreso);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('O'.$Fila, $salario);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('P'.$Fila, $sf);
  
  $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':N'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $objPHPExcel->getActiveSheet()->getStyle('O'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet()->getStyle('O'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $objPHPExcel->getActiveSheet()->getStyle('P'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  
  $Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
foreach($objPHPExcel->getWorksheetIterator() as $worksheet) {

    $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
}

$n = 'Empleados_x_unidad_gerencia'.date('Y-m-d H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
ob_end_clean();
$objWriter->save('php://output');
exit;



?>












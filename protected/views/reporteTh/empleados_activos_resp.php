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

//se reciben los parametros para el reporte
$empresa = $model['empresa'];
if (isset($model['fecha_inicial_cont'])) { $fecha_inicial_cont = $model['fecha_inicial_cont']; } else { $fecha_inicial_cont = ""; }
if (isset($model['fecha_final_cont'])) { $fecha_final_cont = $model['fecha_final_cont']; } else { $fecha_final_cont = ""; }


if($fecha_inicial_cont == "" && $fecha_final_cont != ""){
  $fecha_inicial_cont = $fecha_final_cont;
}

if($fecha_inicial_cont != "" && $fecha_final_cont == ""){
  $fecha_final_cont = $fecha_inicial_cont;
}

//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

$criterio_emp = "";

$empresa = implode(",", $empresa);

$q_empresa = Yii::app()->db->createCommand("SELECT Descripcion FROM T_PR_EMPRESA WHERE Id_Empresa IN (".$empresa.") ORDER BY Descripcion")->queryAll();

$texto_e = '';

foreach ($q_empresa as $e) {
  $texto_e .= $e['Descripcion'].', ';
}

$texto_e = substr ($texto_e, 0, -2);

$criterio_emp .= "Empresa: ".$texto_e;

$criterio = "";

if($fecha_inicial_cont != null && $fecha_final_cont != null){
  $criterio .= "Criterio de búsqueda: Fecha ingreso: de ".$fecha_inicial_cont." al ".$fecha_final_cont;
}

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

if(($fecha_inicial_cont != "" && $fecha_final_cont != "" && $empresa != "")){
  $o = 1;
}

if(($fecha_inicial_cont == "" && $fecha_final_cont == "" && $empresa != "")){
  $o = 2;
}

/*inicio configuración array de datos*/

$FechaM1 = str_replace("-","",$fecha_inicial_cont);
$FechaM2 = str_replace("-","",$fecha_final_cont);

$query ="
  SET NOCOUNT ON
  EXEC P_PR_GH_EMPLEADO_ACT
  @OPT = ".$o.",
  @Fecha_Ini = '".$FechaM1."',
  @Fecha_Fin = '".$FechaM2."',
  @Empresa = '".$empresa."'
";

UtilidadesVarias::log($query);

//EXCEL

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Tipo identificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'No. identificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Empleado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Género');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Fec. nacimiento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'E-mail');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Teléfono(s)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Grado escolaridad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Persona contacto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Teléfono(s) contacto');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Empresa');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Unidad de gerencia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Área');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Subárea');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Cargo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Fec. ingreso');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Salario');

$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:Q1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $tipo_ident          = $reg1 ['Tipo_Identificacion']; 
  $ident               = $reg1 ['Identificacion']; 
  $empleado            = $reg1 ['Apellido'].' '.$reg1 ['Nombre']; 
  $genero              = $reg1 ['Genero']; 
  $fecha_nacimiento    = $reg1 ['Fecha_Nacimiento'];
  
  if($reg1 ['Correo'] != ""){
    $correo = $reg1 ['Correo']; 
  }else{
    $correo = "-";
  }

  if($reg1 ['Telefono'] != ""){
    $telefono = $reg1 ['Telefono']; 
  }else{
    $telefono = "-";
  }

  if($reg1 ['Persona_Contacto'] != ""){
    $persona_contacto = $reg1 ['Persona_Contacto']; 
  }else{
    $persona_contacto = "-";
  }

  if($reg1 ['Tel_Persona_Contacto'] != ""){
    $tel_persona_contacto = $reg1 ['Tel_Persona_Contacto']; 
  }else{
    $tel_persona_contacto = "-";
  }

  $empresa = $reg1 ['Empresa'];
      
  if($reg1 ['Grado_Escolaridad'] != ""){
    $gr_esc = $reg1 ['Grado_Escolaridad']; 
  }else{
    $gr_esc = "-";
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

  $fecha_ingreso = $reg1 ['Fecha_Ingreso']; 
  $salario = number_format($reg1['Salario'],0);

  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $tipo_ident);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $ident);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $empleado);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $genero);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $fecha_nacimiento);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $correo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $telefono);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $gr_esc);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $persona_contacto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $tel_persona_contacto);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $empresa);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $ug);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $area);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $subarea);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $cargo);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $fecha_ingreso);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $salario);
  
  $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getNumberFormat()->setFormatCode('0');
  $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal($alignment_left);
  $objPHPExcel->getActiveSheet(0)->getStyle('Q'.$Fila)->getAlignment()->setHorizontal($alignment_right);

  $Fila = $Fila + 1;

}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 17; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Empleados_activos_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte
if (isset($model['empresa'])) { $empresa = $model['empresa']; } else { $empresa = ""; }
if (isset($model['fecha_inicial_cont'])) { $fecha_inicial_cont = $model['fecha_inicial_cont']; } else { $fecha_inicial_cont = ""; }
if (isset($model['fecha_final_cont'])) { $fecha_final_cont = $model['fecha_final_cont']; } else { $fecha_final_cont = ""; }
//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

$condicion = "WHERE HP.Id_M_Retiro IS NULL AND P.Estado = 1";

$criterio_emp = "";

if($empresa != null){
  $empresa = implode(",", $empresa);
  $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
  
  $q_empresa = Yii::app()->db->createCommand("SELECT Descripcion FROM TH_EMPRESA WHERE Id_Empresa IN (".$empresa.") ORDER BY Descripcion")->queryAll();

  $texto_e = '';

  foreach ($q_empresa as $e) {
    $texto_e .= $e['Descripcion'].', ';
  }

  $texto_e = substr ($texto_e, 0, -2);

  $criterio_emp .= "Empresa: ".$texto_e;

}else{

  $array_empresas = (Yii::app()->user->getState('array_empresas'));
  $empresa = implode(",",$array_empresas);
  $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    
  $criterio_emp .= "Empresa: TODAS ";
}

$criterio = "";

if($fecha_inicial_cont != null && $fecha_final_cont != null){
  $condicion .= " AND HP.Fecha_Ingreso BETWEEN '".$fecha_inicial_cont."' AND '".$fecha_final_cont."'";
  $criterio .= "Criterio de búsqueda: Fecha ingreso: de ".$fecha_inicial_cont." al ".$fecha_final_cont;
}else{
  if($fecha_inicial_cont != null && $fecha_final_cont == null){
    $condicion .= " AND HP.Fecha_Ingreso = '".$fecha_inicial_cont."'";
    $criterio .= "Criterio de búsqueda: Fecha ingreso: ".$fecha_inicial_cont;
  }
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

/*inicio configuración array de datos*/

$query ="
SELECT 
TI.Dominio AS Tipo_Ident, 
P.Identificacion, 
CONCAT (P.Apellido, ' ', P.Nombre) AS Empleado, 
GE.Dominio AS Gen, 
P.Fecha_Nacimiento, 
P.Correo,
P.Telefono,
GRES.Dominio AS Gr_Esc,
P.Persona_Contacto,
P.Tel_Persona_Contacto,
E.Descripcion AS Empresa,  
UG.Unidad_Gerencia,
A.Area,
S.Subarea,
C.Cargo, 
HP.Salario,
HP.Fecha_Ingreso 
FROM TH_CONTRATO_EMPLEADO HP 
LEFT JOIN TH_EMPLEADO P ON HP.Id_Empleado = P.Id_Empleado 
LEFT JOIN TH_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio 
LEFT JOIN TH_DOMINIO GE ON P.Id_Genero = GE.Id_Dominio
LEFT JOIN TH_DOMINIO GRES ON P.Id_Grado_Esc = GRES.Id_Dominio 
LEFT JOIN TH_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa 
LEFT JOIN TH_UNIDAD_GERENCIA UG ON HP.Id_Unidad_Gerencia = UG.Id_Unidad_Gerencia 
LEFT JOIN TH_AREA A ON HP.Id_Area = A.Id_Area
LEFT JOIN TH_SUBAREA S ON HP.Id_Subarea = S.Id_Subarea
LEFT JOIN TH_CARGO C ON HP.Id_Cargo = C.Id_Cargo 
".$condicion."
ORDER BY 7,8,9,10,11,3 ASC
";

//echo $query;die;

/*fin configuración array de datos*/

if($opcion == 1){
  //PDF

  //se incluye la libreria pdf
  require_once Yii::app()->basePath . '/extensions/fpdf/fpdf.php';

  class PDF extends FPDF{
    
    function setCriterioEmp($criterio_emp){
      $this->criterio_emp = $criterio_emp;
    }

    function setCriterio($criterio){
      $this->criterio = $criterio;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',10);
      $this->Cell(260,5,'Reporte empleados activos',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_emp),0,0,'L');
      $this->Ln();
      
      if($this->criterio != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio),0,0,'L');
        $this->Ln();
      }
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',6);
      $this->Cell(30,2,utf8_decode('Tipo identificación'),0,0,'L');
      $this->Cell(20,2,utf8_decode('No. identificación'),0,0,'L');
      $this->Cell(57,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Género'),0,0,'L');
      $this->Cell(20,2,utf8_decode('Fec. nacimiento'),0,0,'L');
      $this->Cell(45,2,utf8_decode('E-mail'),0,0,'L');
      $this->Cell(23,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Unidad de gerencia'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Área'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Subárea'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Cargo'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Fec. ingreso'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Salario'),0,0,'R');
      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

      foreach ($query1 as $reg1) {  

        $tipo_ident          = $reg1 ['Tipo_Ident']; 
        $ident               = $reg1 ['Identificacion']; 
        $empleado            = $reg1 ['Empleado']; 
        $genero              = $reg1 ['Gen']; 
        $fecha_nacimiento    = $reg1 ['Fecha_Nacimiento'];
        
        if($reg1 ['Correo'] != ""){
          $correo = $reg1 ['Correo']; 
        }else{
          $correo = "-";
        }

        $empresa = $reg1 ['Empresa'];
        
        if($reg1 ['Gr_Esc'] != ""){
          $gr_esc = $reg1 ['Gr_Esc']; 
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

        $this->SetFont('Arial','',5);
        $this->Cell(30,3,utf8_decode($tipo_ident),0,0,'L');
        $this->Cell(20,3,utf8_decode($ident),0,0,'L');
        $this->Cell(57,3,substr(utf8_decode($empleado),0,50),0,0,'L');
        $this->Cell(15,3,utf8_decode($genero),0,0,'L');
        $this->Cell(20,3,utf8_decode($fecha_nacimiento),0,0,'L');
        $this->Cell(45,3,substr(utf8_decode($correo),0,50),0,0,'L');
        $this->Cell(23,3,utf8_decode($empresa),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($ug),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($area),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($subarea),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($cargo),0,20),0,0,'L');
        $this->Cell(15,3,utf8_decode($fecha_ingreso),0,0,'L');
        $this->Cell(15,3,utf8_decode($salario),0,0,'R');
        $this->Ln();

      }

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
  $pdf->setCriterioEmp($criterio_emp);
  $pdf->setCriterio($criterio);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Empleados_activos_'.date('Y-m-d H_i_s').'.pdf');
}

if($opcion == 2){
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

  $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'Tipo identificación');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'No. identificación');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Empleado');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Género');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Fec. nacimiento');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'E-mail');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Teléfono(s)');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Grado escolaridad');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'Persona contacto');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('J1', 'Teléfono(s) contacto');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K1', 'Empresa');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('L1', 'Unidad de gerencia');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('M1', 'Área');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('N1', 'Subárea');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('O1', 'Cargo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('P1', 'Fec. ingreso');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('Q1', 'Salario');

  $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {

    $tipo_ident          = $reg1 ['Tipo_Ident']; 
    $ident               = $reg1 ['Identificacion']; 
    $empleado            = $reg1 ['Empleado']; 
    $genero              = $reg1 ['Gen']; 
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
        
    if($reg1 ['Gr_Esc'] != ""){
      $gr_esc = $reg1 ['Gr_Esc']; 
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

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $genero);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $fecha_nacimiento);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $correo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $telefono);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $gr_esc);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $persona_contacto);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, $tel_persona_contacto);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$Fila, $ug);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$Fila, $area);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N'.$Fila, $subarea);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O'.$Fila, $cargo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('P'.$Fila, $fecha_ingreso);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('Q'.$Fila, $salario);
    
    $objPHPExcel->getActiveSheet()->getStyle('Q'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('Q'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

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

  $n = 'Empleados_activos_'.date('Y-m-d H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












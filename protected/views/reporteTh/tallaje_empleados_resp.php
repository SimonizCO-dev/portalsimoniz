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

$condicion = "WHERE CE.Id_M_Retiro IS NULL";

$criterio_emp = "";

if($empresa != null){
  $empresa = implode(",", $empresa);
  $condicion .= " AND CE.Id_Empresa IN (".$empresa.")";
  
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
  $condicion .= " AND CE.Id_Empresa IN (".$empresa.")";

  $criterio_emp .= "Empresa: TODAS ";
}

$criterio = "";

if($fecha_inicial_cont != null && $fecha_final_cont != null){
  $condicion .= " AND CE.Fecha_Ingreso BETWEEN '".$fecha_inicial_cont."' AND '".$fecha_final_cont."'";
  $criterio .= "Criterio de búsqueda: Fecha ingreso: de ".$fecha_inicial_cont." al ".$fecha_final_cont;
}else{
  if($fecha_inicial_cont != null && $fecha_final_cont == null){
    $condicion .= " AND CE.Fecha_Ingreso = '".$fecha_inicial_cont."'";
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
  EMP.Identificacion AS Identificacion, 
  CONCAT (EMP.Apellido,' ',EMP.Nombre) AS Empleado, 
  GEN.Dominio AS Genero, 
  EM.Descripcion AS Empresa, 
  CE.Fecha_Ingreso AS Fecha_Ingreso, 
  UN.Unidad_Gerencia AS UN, 
  A.Area AS Area, S.Subarea AS Subarea, 
  C.Cargo AS Cargo,  
  EMP.Talla_Camisa, 
  EMP.Talla_Pantalon, 
  EMP.Talla_Zapato, 
  EMP.Talla_Overol, 
  EMP.Talla_Bata,
  CE.Id_Contrato 
  FROM TH_EMPLEADO E
  LEFT JOIN TH_CONTRATO_EMPLEADO CE ON CE.Id_Empleado = E.Id_Empleado 
  LEFT JOIN TH_EMPRESA EM ON EM.Id_Empresa = CE.Id_Empresa 
  LEFT JOIN TH_UNIDAD_GERENCIA UN ON UN.Id_Unidad_Gerencia = CE.Id_Unidad_Gerencia 
  LEFT JOIN TH_AREA A ON A.Id_Area = CE.Id_Area 
  LEFT JOIN TH_SUBAREA S ON S.Id_Subarea = CE.Id_Subarea 
  LEFT JOIN TH_CARGO C ON C.Id_Cargo = CE.Id_Cargo 
  LEFT JOIN TH_EMPLEADO EMP ON EMP.Id_Empleado = CE.Id_Empleado 
  LEFT JOIN TH_DOMINIO GEN ON GEN.Id_Dominio = EMP.Id_Genero 
  LEFT JOIN TH_DOMINIO TI ON TI.Id_Dominio = EMP.Id_Tipo_Ident 
  WHERE CE.Id_M_Retiro IS NULL 
  AND CE.Id_Empresa IN (6,7,8,4,5,1,2,3) ORDER BY 5,7,8,9,3
";

$query1 = Yii::app()->db->createCommand($query)->queryAll();

$array = array();

foreach ($query1 as $reg) {
  
  $Tipo_Ident = $reg['Tipo_Ident'];
  $Identificacion = $reg['Identificacion'];
  $Empleado = $reg['Empleado'];
  $Genero = $reg['Genero'];
  $Empresa = $reg['Empresa'];
  $Fecha_Ingreso = $reg['Fecha_Ingreso'];
  $UN = $reg['UN'];
  $Area = $reg['Area'];
  $Subarea = $reg['Subarea'];
  $Cargo = $reg['Cargo'];
  $Talla_Camisa = $reg['Talla_Camisa'];
  $Talla_Pantalon = $reg['Talla_Pantalon'];
  $Talla_Zapato = $reg['Talla_Zapato'];
  $Talla_Overol = $reg['Talla_Overol'];
  $Talla_Bata = $reg['Talla_Bata'];
  $Id_Contrato = $reg['Id_Contrato'];

  $query2 = "
    SELECT 
    E.Elemento AS Elemento
    FROM TH_ELEMENTO_EMPLEADO EE
    LEFT JOIN TH_AREA_ELEMENTO AE ON AE.Id_A_elemento = EE.Id_A_Elemento 
    LEFT JOIN TH_ELEMENTO E ON E.Id_Elemento = AE.Id_Elemento 
    WHERE EE.Id_Contrato = ".$Id_Contrato." AND EE.Id_A_Elemento IN (SELECT Id_A_Elemento FROM TH_AREA_ELEMENTO_DOT) 
    AND EE.Estado = 1 
  ";

  $query3 = Yii::app()->db->createCommand($query2)->queryAll();

  $Elem = "";

  if(!empty($query3)){

    foreach ($query3 as $r) {
      $Elem .= "".$r['Elemento'].", ";
    }

    $Elem = substr ($Elem, 0, -2);    
  }

  $array[$Identificacion] = array();
  $array[$Identificacion]['info'] = array();
  $array[$Identificacion]['info']['Tipo_Ident'] = $Tipo_Ident;
  $array[$Identificacion]['info']['Identificacion'] = $Identificacion;
  $array[$Identificacion]['info']['Empleado'] = $Empleado;
  $array[$Identificacion]['info']['Genero'] = $Genero;
  $array[$Identificacion]['info']['Empresa'] = $Empresa;
  $array[$Identificacion]['info']['Fecha_Ingreso'] = $Fecha_Ingreso;
  $array[$Identificacion]['info']['UN'] = $UN;
  $array[$Identificacion]['info']['Area'] = $Area;
  $array[$Identificacion]['info']['Subarea'] = $Subarea;
  $array[$Identificacion]['info']['Cargo'] = $Cargo;
  $array[$Identificacion]['info']['Talla_Camisa'] = $Talla_Camisa;
  $array[$Identificacion]['info']['Talla_Pantalon'] = $Talla_Pantalon;
  $array[$Identificacion]['info']['Talla_Zapato'] = $Talla_Zapato;
  $array[$Identificacion]['info']['Talla_Overol'] = $Talla_Overol;
  $array[$Identificacion]['info']['Talla_Bata'] = $Talla_Bata;
  $array[$Identificacion]['info']['elementos'] =$Elem;
}

$data = $array;

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

    function setData($data){
      $this->data  = $data;
    }

    function Header(){
      $this->SetFont('Arial','B',10);
      $this->Cell(260,5,utf8_decode('Reporte dotación / tallas empleados activos'),0,0,'L');
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
      $this->SetFont('Arial','B',5);
  
      $this->Cell(27,2,utf8_decode('Tipo identificación'),0,0,'L');
      $this->Cell(18,2,utf8_decode('No. identificación'),0,0,'L');
      $this->Cell(47,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Género'),0,0,'L');
      $this->Cell(23,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Fecha ingreso'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Unidad de gerencia'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Área'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Subárea'),0,0,'L');
      $this->Cell(25,2,utf8_decode('Cargo'),0,0,'L');
      $this->Cell(10,2,utf8_decode('Camisa'),0,0,'L');
      $this->Cell(10,2,utf8_decode('Pantalón'),0,0,'L');
      $this->Cell(10,2,utf8_decode('Zapatos'),0,0,'L');
      $this->Cell(10,2,utf8_decode('Overol'),0,0,'L');
      $this->Cell(10,2,utf8_decode('Bata'),0,0,'L');
      $this->Cell(45,2,utf8_decode('Elementos asignados'),0,0,'L');
      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(340,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $array = $this->data;

      foreach ($array as $registro) {
        
        $Tipo_Ident = ($registro['info']['Tipo_Ident'] == "") ? "NO ASIGNADO" : $registro['info']['Tipo_Ident'];
        $Identificacion = $registro['info']['Identificacion'];
        $Empleado = $registro['info']['Empleado'];
        $Genero = ($registro['info']['Genero'] == "") ? "NO ASIGNADO" : $registro['info']['Genero'];
        $Empresa = $registro['info']['Empresa'];
        $Fecha_Ingreso = ($registro['info']['Fecha_Ingreso'] == "") ? "NO ASIGNADO" : $registro['info']['Fecha_Ingreso'];
        $UN = ($registro['info']['UN'] == "") ? "NO ASIGNADO" : $registro['info']['UN'];
        $Area = ($registro['info']['Area'] == "") ? "NO ASIGNADO" : $registro['info']['Area'];
        $Subarea = ($registro['info']['Subarea'] == "") ? "NO ASIGNADO" : $registro['info']['Subarea'];
        $Cargo = ($registro['info']['Cargo'] == "") ? "NO ASIGNADO" : $registro['info']['Cargo'];
        $Talla_Camisa = ($registro['info']['Talla_Camisa'] == "") ? "-" : $registro['info']['Talla_Camisa'];
        $Talla_Pantalon = ($registro['info']['Talla_Pantalon'] == "") ? "-" : $registro['info']['Talla_Pantalon'];
        $Talla_Zapato = ($registro['info']['Talla_Zapato'] == "") ? "-" : $registro['info']['Talla_Zapato'];
        $Talla_Overol = ($registro['info']['Talla_Overol'] == "") ? "-" : $registro['info']['Talla_Overol'];
        $Talla_Bata = ($registro['info']['Talla_Bata'] == "") ? "-" : $registro['info']['Talla_Bata'];
        $Elem = ($registro['info']['elementos'] == "") ? "-" : $registro['info']['elementos'];

        $this->SetFont('Arial','',5);
        $this->Cell(27,3,substr(utf8_decode($Tipo_Ident),0,22),0,0,'L');
        $this->Cell(18,3,utf8_decode($Identificacion),0,0,'L');
        $this->Cell(47,3,substr(utf8_decode($Empleado),0,45),0,0,'L');
        $this->Cell(15,3,utf8_decode($Genero),0,0,'L');
        $this->Cell(23,3,utf8_decode($Empresa),0,0,'L');
        $this->Cell(15,3,utf8_decode($Fecha_Ingreso),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($UN),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($Area),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($Subarea),0,20),0,0,'L');
        $this->Cell(25,3,substr(utf8_decode($Cargo),0,20),0,0,'L');
        $this->Cell(10,3,utf8_decode($Talla_Camisa),0,0,'L');
        $this->Cell(10,3,utf8_decode($Talla_Pantalon),0,0,'L');
        $this->Cell(10,3,utf8_decode($Talla_Zapato),0,0,'L');
        $this->Cell(10,3,utf8_decode($Talla_Overol),0,0,'L');
        $this->Cell(10,3,utf8_decode($Talla_Bata),0,0,'L');
        $this->MultiCell(45,3,utf8_decode($Elem),0,'J');
        
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
  $pdf->setData($array);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Dotacion_tallas_empleados_'.date('Y-m-d H_i_s').'.pdf');
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
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Empresa');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Fec. ingreso');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Unidad de gerencia');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Área');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'Subárea');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('J1', 'Cargo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K1', 'Camisa');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('L1', 'Pantalón');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('M1', 'Zapatos');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('N1', 'Overol');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('O1', 'Bata');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('P1', 'Elementos asignados');

  $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2;

  foreach ($array as $registro) {
        
    $Tipo_Ident = ($registro['info']['Tipo_Ident'] == "") ? "NO ASIGNADO" : $registro['info']['Tipo_Ident'];
    $Identificacion = $registro['info']['Identificacion'];
    $Empleado = $registro['info']['Empleado'];
    $Genero = ($registro['info']['Genero'] == "") ? "NO ASIGNADO" : $registro['info']['Genero'];
    $Empresa = $registro['info']['Empresa'];
    $Fecha_Ingreso = ($registro['info']['Fecha_Ingreso'] == "") ? "NO ASIGNADO" : $registro['info']['Fecha_Ingreso'];
    $UN = ($registro['info']['UN'] == "") ? "NO ASIGNADO" : $registro['info']['UN'];
    $Area = ($registro['info']['Area'] == "") ? "NO ASIGNADO" : $registro['info']['Area'];
    $Subarea = ($registro['info']['Subarea'] == "") ? "NO ASIGNADO" : $registro['info']['Subarea'];
    $Cargo = ($registro['info']['Cargo'] == "") ? "NO ASIGNADO" : $registro['info']['Cargo'];
    $Talla_Camisa = ($registro['info']['Talla_Camisa'] == "") ? "-" : $registro['info']['Talla_Camisa'];
    $Talla_Pantalon = ($registro['info']['Talla_Pantalon'] == "") ? "-" : $registro['info']['Talla_Pantalon'];
    $Talla_Zapato = ($registro['info']['Talla_Zapato'] == "") ? "-" : $registro['info']['Talla_Zapato'];
    $Talla_Overol = ($registro['info']['Talla_Overol'] == "") ? "-" : $registro['info']['Talla_Overol'];
    $Talla_Bata = ($registro['info']['Talla_Bata'] == "") ? "-" : $registro['info']['Talla_Bata'];
    $Elem = ($registro['info']['elementos'] == "") ? "-" : $registro['info']['elementos'];

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $Tipo_Ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $Identificacion);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $Empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $Genero);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $Empresa);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $Fecha_Ingreso);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $UN);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $Area);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $Subarea);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, $Cargo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $Talla_Camisa);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$Fila, $Talla_Pantalon);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$Fila, $Talla_Zapato);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N'.$Fila, $Talla_Overol);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O'.$Fila, $Talla_Bata);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('P'.$Fila, $Elem);
    
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':P'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

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

  $n = 'Dotacion_tallas_empleados_'.date('Y-m-d H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












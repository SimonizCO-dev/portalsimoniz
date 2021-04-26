<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte
if (isset($model['empresa'])) { $empresa = $model['empresa']; } else { $empresa = ""; }
if (isset($model['genero'])) { $genero = $model['genero']; } else { $genero = ""; }
if (isset($model['edad_inicial'])) { $edad_inicial = $model['edad_inicial']; } else { $edad_inicial = ""; }
if (isset($model['edad_final'])) { $edad_final = $model['edad_final']; } else { $edad_final = ""; }
//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

$condicion = "WHERE Id_Parentesco = ".Yii::app()->params->parentesco_hijo." AND P.Estado = 1";

$criterio_emp = "";

if($empresa != null){
  $empresa = implode(",", $empresa);
  $condicion .= " AND P.Id_Empresa IN (".$empresa.")";
  
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
  $condicion .= " AND P.Id_Empresa IN (".$empresa.")";

  $criterio_emp .= "Empresa: TODAS ";
}

$criterio = "";

if($genero != null){
  $condicion .= " AND H.Id_Genero = ".$genero;
  $genero = Dominio::model()->findByPk($genero)->Dominio;
  $criterio .= "Género: ".$genero;
}else{
  $criterio .= "Género: TODOS ";  
}

if($edad_inicial != null && $edad_final != null){
  $condicion .= " AND DATEDIFF (yy, H.Fecha_Nacimiento, GETDATE()) BETWEEN ".$edad_inicial." AND ".$edad_final;
  $criterio .= ", Edad: de ".$edad_inicial." a ".$edad_final." años";
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
E.Descripcion AS Empresa,
H.Nombre_Apellido AS Hijo, 
H.Fecha_Nacimiento, 
DATEDIFF (yy, H.Fecha_Nacimiento, GETDATE()) AS Edad,
D.Dominio as Genero
FROM TH_NUCLEO_EMPLEADO H
LEFT JOIN TH_EMPLEADO P ON H.Id_Empleado = P.Id_Empleado
LEFT JOIN TH_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio 
LEFT JOIN TH_DOMINIO D ON H.Id_Genero = D.Id_Dominio
LEFT JOIN TH_EMPRESA E ON P.Id_Empresa = E.Id_Empresa
".$condicion."
ORDER BY 4,3,6,7 ASC
";

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
      $this->SetFont('Arial','B',9);
      $this->Cell(200,5,'Reporte hijos de empleados',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',5);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_emp),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',5);
      $this->Cell(280,5,utf8_decode('Criterio de búsqueda: '.$this->criterio),0,0,'L');
      $this->Ln();
      
      //linea superior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      $this->Ln();  
      
      //cabecera de tabla
      $this->SetFont('Arial','B',5);
      $this->Cell(30,2,utf8_decode('Tipo identificación'),0,0,'L');
      $this->Cell(20,2,utf8_decode('No. identificación'),0,0,'L');
      $this->Cell(57,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(36,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(56,2,utf8_decode('Hijo'),0,0,'L');
      $this->Cell(36,2,utf8_decode('Fecha de nacimiento'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Edad'),0,0,'R');
      $this->Cell(36,2,utf8_decode('Género'),0,0,'L');

      $this->Ln(3);
      
      //linea inferior a la cabecera de la tabla
      $this->SetDrawColor(0,0,0);
      $this->Cell(280,1,'','T');
      $this->SetFillColor(224,235,255);
      $this->SetTextColor(0);
      

      $this->Ln();
    }

    function Tabla(){

      $query1 = Yii::app()->db->createCommand($this->sql)->queryAll();

      foreach ($query1 as $reg1) {  

        $tipo_ident       = $reg1 ['Tipo_Ident']; 
        $ident            = $reg1 ['Identificacion']; 
        $empleado         = $reg1 ['Empleado'];
        $empresa          = $reg1 ['Empresa']; 
        $hijo             = $reg1 ['Hijo']; 
        $fecha_nacimiento = $reg1 ['Fecha_Nacimiento']; 
        $edad             = $reg1 ['Edad']; 
        $genero           = $reg1 ['Genero']; 

        $this->SetFont('Arial','',5);
        $this->Cell(30,3,utf8_decode($tipo_ident),0,0,'L');
        $this->Cell(20,3,utf8_decode($ident),0,0,'L');
        $this->Cell(57,3,substr(utf8_decode($empleado),0,50),0,0,'L');
        $this->Cell(36,3,utf8_decode($empresa),0,0,'L');
        $this->Cell(56,3,substr(utf8_decode($hijo),0,50),0,0,'L');
        $this->Cell(36,3,utf8_decode($fecha_nacimiento),0,0,'L');
        $this->Cell(15,3,utf8_decode($edad),0,0,'R');
        $this->Cell(36,3,utf8_decode($genero),0,0,'L');
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

  $pdf = new PDF('L','mm','A4');
  //se definen las variables extendidas de la libreria FPDF
  $pdf->setCriterioEmp($criterio_emp);
  $pdf->setCriterio($criterio);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Hijos_empleados_'.date('Y-m-d H_i_s').'.pdf');
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
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Empresa');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Hijo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Fecha de nacimiento');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Edad');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Género');

  $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {

    $tipo_ident       = $reg1 ['Tipo_Ident']; 
    $ident            = $reg1 ['Identificacion']; 
    $empleado         = $reg1 ['Empleado'];
    $empresa          = $reg1 ['Empresa'];  
    $hijo             = $reg1 ['Hijo']; 
    $fecha_nacimiento = $reg1 ['Fecha_Nacimiento']; 
    $edad             = $reg1 ['Edad']; 
    $genero           = $reg1 ['Genero'];  

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $hijo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $fecha_nacimiento);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $edad);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $genero);
        
    $objPHPExcel->getActiveSheet()->getStyle('G'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':F'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

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

  $n = 'Hijos_empleados_'.date('Y-m-d H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  ob_end_clean();
  $objWriter->save('php://output');
  exit;

}

?>












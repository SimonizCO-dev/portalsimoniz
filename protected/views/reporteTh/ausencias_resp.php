<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

//se reciben los parametros para el reporte
if (isset($model['empresa'])) { $empresa = $model['empresa']; } else { $empresa = ""; }
if (isset($model['motivo_ausencia'])) { $motivo_ausencia = $model['motivo_ausencia']; } else { $motivo_ausencia = ""; }
if (isset($model['fecha_inicial'])) { $fecha_inicial = $model['fecha_inicial']; } else { $fecha_inicial = ""; }
if (isset($model['fecha_final'])) { $fecha_final = $model['fecha_final']; } else { $fecha_final = ""; }
if (isset($model['fecha_inicial_reg'])) { $fecha_inicial_reg = $model['fecha_inicial_reg']; } else { $fecha_inicial_reg = ""; }
if (isset($model['fecha_final_reg'])) { $fecha_final_reg = $model['fecha_final_reg']; } else { $fecha_final_reg = ""; }
if (isset($model['id_empleado'])) { $id_empleado = $model['id_empleado']; } else { $id_empleado = ""; }
//opcion: 1. PDF, 2. EXCEL
$opcion = $model['opcion_exp'];

$condicion = "WHERE 1 = 1";

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

$criterio_mot = "";

if($motivo_ausencia != null){
  $motivo_ausencia = implode(",", $motivo_ausencia);
  $condicion .= " AND A.Id_M_Ausencia IN (".$motivo_ausencia.")";
  
  $q_motivos = Yii::app()->db->createCommand("SELECT Dominio FROM TH_DOMINIO WHERE Id_Dominio IN (".$motivo_ausencia.") ORDER BY Dominio")->queryAll();

  $texto_m = '';

  foreach ($q_motivos as $m) {
    $texto_m .= $m['Dominio'].', ';
  }

  $texto_m = substr ($texto_m, 0, -2);

  $criterio_mot .= "Motivos: ".$texto_m;

}else{
  $criterio_mot .= "Motivos: TODOS ";
}

$criterio_fec = "";

if($fecha_inicial != null && $fecha_final != null){
  $condicion .= " AND A.Fecha_Inicial BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
  $criterio_fec .= "Criterio de búsqueda:  Fecha: de ".$fecha_inicial." al ".$fecha_final;
}else{
  if($fecha_inicial != null && $fecha_final == null){
    $condicion .= " AND A.Fecha_Inicial = '".$fecha_inicial."'";
    $criterio_fec .= "Criterio de búsqueda:  Fecha: ".$fecha_inicial;
  }
}

$criterio_reg = "";

if($fecha_inicial_reg != null && $fecha_final_reg != null){
  $condicion .= " AND A.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_final_reg." 23:59:59'";
  $criterio_reg .= "Criterio de búsqueda:  Fecha de creación: de ".$fecha_inicial_reg." al ".$fecha_final_reg;
}else{
  if($fecha_inicial_reg != null && $fecha_final_reg == null){
    $condicion .= " AND A.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_inicial_reg." 23:59:59'";
    $criterio_reg .= "Criterio de búsqueda:  Fecha de creación: ".$fecha_inicial_reg;
  }
}

$criterio_empl = "";

if($id_empleado != null){
  $condicion .= " AND A.Id_Empleado = ".$id_empleado."";
  $nombre_emp = UtilidadesEmpleado::nombreempleado($id_empleado);
  $criterio_empl .= "Criterio de búsqueda:  Empleado: ".$nombre_emp;
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
A.Id_Ausencia,
A.Fecha_Inicial
FROM TH_AUSENCIA_EMPLEADO A
INNER JOIN TH_CONTRATO_EMPLEADO HP ON A.Id_Empleado = HP.Id_Empleado
INNER JOIN TH_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
".$condicion."
GROUP BY A.Id_Ausencia, A.Fecha_Inicial
ORDER BY 2 DESC
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

    function setCriterioMot($criterio_mot){
      $this->criterio_mot = $criterio_mot;
    }

    function setCriterioFec($criterio_fec){
      $this->criterio_fec = $criterio_fec;
    }

    function setCriterioReg($criterio_reg){
      $this->criterio_reg = $criterio_reg;
    }

    function setCriterioEmpl($criterio_empl){
      $this->criterio_empl = $criterio_empl;
    }

    function setFechaActual($fecha_actual){
      $this->fecha_actual = $fecha_actual;
    }

    function setSql($sql){
      $this->sql = $sql;
    }

    function Header(){
      $this->SetFont('Arial','B',10);
      $this->Cell(260,5,'Reporte ausencias de empleados',0,0,'L');
      $this->SetFont('Arial','',7);
      $this->Cell(80,5,utf8_decode($this->fecha_actual),0,0,'R');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_emp),0,0,'L');
      $this->Ln();
      $this->SetFont('Arial','',6);
      $this->Cell(340,5,utf8_decode('Criterio de búsqueda: '.$this->criterio_mot),0,0,'L');
      $this->Ln();
      
      if($this->criterio_fec != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio_fec),0,0,'L');
        $this->Ln();
      }

      if($this->criterio_reg != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio_reg),0,0,'L');
        $this->Ln();
      }

      if($this->criterio_empl != ""){
        $this->SetFont('Arial','',6);
        $this->Cell(340,5,utf8_decode($this->criterio_empl),0,0,'L');
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
      $this->Cell(52,2,utf8_decode('Empleado'),0,0,'L');
      $this->Cell(23,2,utf8_decode('Empresa'),0,0,'L');
      $this->Cell(61,2,utf8_decode('Motivo'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Fecha inicial'),0,0,'L');
      $this->Cell(15,2,utf8_decode('Fecha final'),0,0,'L');
      $this->Cell(7,2,utf8_decode('Días'),0,0,'L');
      $this->Cell(7,2,utf8_decode('Horas'),0,0,'L');
      $this->Cell(16,2,utf8_decode('Cod. soporte'),0,0,'L');
      $this->Cell(12,2,utf8_decode('Descontar'),0,0,'L');
      $this->Cell(16,2,utf8_decode('Descontar FDS'),0,0,'L');
      $this->Cell(66,2,utf8_decode('Observaciones / Notas'),0,0,'L');
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

        $modeloausencia = AusenciaEmpleado::model()->findByPk($reg1['Id_Ausencia']);

        $tipo_ident       = $modeloausencia->idempleado->idtipoident->Dominio; 
        $ident            = $modeloausencia->idempleado->Identificacion;  
        $empleado         = UtilidadesEmpleado::nombreempleado($modeloausencia->Id_Empleado);
        $empresa          = $modeloausencia->idcontrato->idempresa->Descripcion;
        $motivo           = $modeloausencia->idmausencia->Dominio; 
        $fecha_inicial    = $modeloausencia->Fecha_Inicial; 
        $fecha_final      = $modeloausencia->Fecha_Final; 
        $dias             = $modeloausencia->Dias;
        
        if($modeloausencia->Horas == 0.0){
          $horas = 0;
        }else{
          $horas = $modeloausencia->Horas;
        }

        $cod_soporte      = $modeloausencia->Cod_Soporte; 
        
        if($modeloausencia->Descontar == 1){
          $descontar = 'SI';
        }else{
          $descontar = 'NO';
        }

        if($modeloausencia->Descontar_FDS == 1){
          $descontar_FDS = 'SI';
        }else{
          $descontar_FDS = 'NO';
        }
        
        if($modeloausencia->Observacion != ""){
          $observaciones = $modeloausencia->Observacion; 
        }else{
          $observaciones = "-";
        }

        if($modeloausencia->Nota != ""){
          $notas = $modeloausencia->Nota; 
        }else{
          $notas = "-";
        }

        $this->SetFont('Arial','',6);
        $this->Cell(30,3,utf8_decode($tipo_ident),0,0,'L');
        $this->Cell(20,3,utf8_decode($ident),0,0,'L');
        $this->Cell(52,3,substr(utf8_decode($empleado),0,40),0,0,'L');
        $this->Cell(23,3,utf8_decode($empresa),0,0,'L');
        $this->Cell(61,3,substr(utf8_decode($motivo),0,55),0,0,'L');
        $this->Cell(15,3,utf8_decode($fecha_inicial),0,0,'L');
        $this->Cell(15,3,utf8_decode($fecha_final),0,0,'L');
        $this->Cell(7,3,utf8_decode($dias),0,0,'L');
        $this->Cell(7,3,utf8_decode($horas),0,0,'L');
        $this->Cell(16,3,utf8_decode($cod_soporte),0,0,'L');
        $this->Cell(12,3,utf8_decode($descontar),0,0,'L');
        $this->Cell(16,3,utf8_decode($descontar_FDS),0,0,'L');
        $this->MultiCell(66,3,utf8_decode($observaciones.' / '.$notas),0,'J');
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
  $pdf->setCriterioMot($criterio_mot);
  $pdf->setCriterioFec($criterio_fec);
  $pdf->setCriterioReg($criterio_reg);
  $pdf->setCriterioEmpl($criterio_empl);
  $pdf->setFechaActual($fecha_act);
  $pdf->setSql($query);
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->Tabla();
  ob_end_clean();
  $pdf->Output('D','Ausencias_empleados_'.date('Y-m-d H_i_s').'.pdf');
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
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Motivo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Fecha inicial');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Fecha final');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Días');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'Horas');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('J1', 'Cod. soporte');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K1', 'Descontar');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('L1', 'Descontar FDS');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('M1', 'Observaciones');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('N1', 'Notas');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('O1', 'Fecha de registro');

  $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);

  /*Inicio contenido tabla*/

  $query1 = Yii::app()->db->createCommand($query)->queryAll();
      
  $Fila = 2; 
  
  foreach ($query1 as $reg1) {

    $modeloausencia = AusenciaEmpleado::model()->findByPk($reg1['Id_Ausencia']);

    $empresa          = $modeloausencia->idcontrato->idempresa->Descripcion;
    $tipo_ident       = $modeloausencia->idempleado->idtipoident->Dominio; 
    $ident            = $modeloausencia->idempleado->Identificacion;  
    $empleado         = UtilidadesEmpleado::nombreempleado($modeloausencia->Id_Empleado);
    $motivo           = $modeloausencia->idmausencia->Dominio; 
    $fecha_inicial    = $modeloausencia->Fecha_Inicial; 
    $fecha_final      = $modeloausencia->Fecha_Final; 
    $dias             = $modeloausencia->Dias;
    
    if($modeloausencia->Horas == 0.0){
      $horas = 0;
    }else{
      $horas = $modeloausencia->Horas;
    }

    $cod_soporte      = $modeloausencia->Cod_Soporte; 
    
    if($modeloausencia->Descontar == 1){
      $descontar = 'SI';
    }else{
      $descontar = 'NO';
    }

    if($modeloausencia->Descontar_FDS == 1){
      $descontar_FDS = 'SI';
    }else{
      $descontar_FDS = 'NO';
    } 
    
    if($modeloausencia->Observacion != ""){
      $observaciones = $modeloausencia->Observacion; 
    }else{
      $observaciones = "-";
    }

    if($modeloausencia->Nota != ""){
      $notas = $modeloausencia->Nota; 
    }else{
      $notas = "-";
    }

    $fecha_registro = date("Y-m-d", strtotime($modeloausencia->Fecha_Creacion));

    $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $tipo_ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $ident);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $empleado);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $empresa);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $motivo);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $fecha_inicial);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $fecha_final);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $dias);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $horas);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, $cod_soporte);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $descontar);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('L'.$Fila, $descontar_FDS);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('M'.$Fila, $observaciones);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('N'.$Fila, $notas);
    $objPHPExcel->setActiveSheetIndex()->setCellValue('O'.$Fila, $fecha_registro);
    
    $objPHPExcel->getActiveSheet()->getStyle('H'.$Fila)->getNumberFormat()->setFormatCode('0');
    $objPHPExcel->getActiveSheet()->getStyle('I'.$Fila)->getNumberFormat()->setFormatCode('#,#0.0');  
    $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':G'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $objPHPExcel->getActiveSheet()->getStyle('J'.$Fila.':O'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

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

  $n = 'Ausencias_empleados_'.date('Y-m-d H_i_s');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
  ob_end_clean();
  $objWriter->save('php://output');
  exit;
  
}

?>












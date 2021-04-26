<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

if (isset($model['empresa_compra'])) { $empresa_compra = $model['empresa_compra']; } else { $empresa_compra = ""; }
if (isset($model['clasif'])) { $clasif = $model['clasif']; } else { $clasif = "";  }

//se arma el arreglo con los datos para el reporte

$condicion = "";

if($empresa_compra != ""){
  $a_emp = implode(",", $empresa_compra);
  $condicion .= " AND L.Empresa_Compra IN (".$a_emp.")";
}

if($clasif != ""){
  $condicion .= " AND L.Clasificacion = ".$clasif;
}

$query = "
  SELECT 
  L.Id_Lic,
  E.Descripcion AS Empresa_Compra,
  C.Dominio AS Clasif, 
  T.Dominio AS Tipo, 
  V.Dominio AS Vers, 
  P.Dominio AS Prod, 
  L.Num_Licencia,
  U.Dominio AS Ubic,
  L.Numero_Factura,
  L.Fecha_Inicio,
  L.Fecha_Final
  FROM TH_LICENCIA L
  LEFT JOIN TH_EMPRESA E ON L.Empresa_Compra = E.Id_Empresa 
  LEFT JOIN TH_DOMINIO C ON L.Clasificacion = C.Id_Dominio
  LEFT JOIN TH_DOMINIO T ON L.Tipo = T.Id_Dominio 
  LEFT JOIN TH_DOMINIO V ON L.Version = V.Id_Dominio 
  LEFT JOIN TH_DOMINIO P ON L.Producto = P.Id_Dominio
  LEFT JOIN TH_DOMINIO U ON L.Ubicacion = U.Id_Dominio
  WHERE L.Fecha_Final IS NOT NULL AND DATEDIFF(day,'".date('Y-m-d')."',L.Fecha_Final) < 90 AND L.Estado = ".Yii::app()->params->estado_lic_act." ".$condicion."
  ORDER BY 2,3,4,5,6,11
";

// Se inactiva el autoloader de yii
spl_autoload_unregister(array('YiiBase','autoload'));   

require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel.php';

//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
spl_autoload_register(array('YiiBase','autoload'));

$objPHPExcel = new PHPExcel();

$objPHPExcel->getActiveSheet()->setTitle('Hoja1');
$objPHPExcel->setActiveSheetIndex();

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'ID');
$objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'Empresa que compro');
$objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Clasif.');
$objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Tipo');
$objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Versión');
$objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Producto');
$objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'N° de licencia');
$objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Ubicación');
$objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'N° de factura');
$objPHPExcel->setActiveSheetIndex()->setCellValue('J1', 'Fecha inicio');
$objPHPExcel->setActiveSheetIndex()->setCellValue('K1', 'Fecha fin');

$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

/*Inicio contenido tabla*/

$query1 = Yii::app()->db->createCommand($query)->queryAll();
    
$Fila = 2; 

foreach ($query1 as $reg1) {

  $Id_Lic         = $reg1 ['Id_Lic']; 
  $Empresa_Compra = $reg1 ['Empresa_Compra']; 
  $Clasif         = $reg1 ['Clasif'];
  $Tipo           = $reg1 ['Tipo'];
  $Vers           = $reg1 ['Vers'];
  
  if($reg1 ['Prod'] != ""){
    $Prod   = $reg1 ['Prod'];
  }else{
    $Prod   = '-';
  }
  
  $Num_Licencia   = $reg1 ['Num_Licencia'];
  $Ubic           = $reg1 ['Ubic'];
  $Numero_Factura = $reg1 ['Numero_Factura'];
  $Fecha_Inicio   = $reg1 ['Fecha_Inicio'];
  $Fecha_Final    = $reg1 ['Fecha_Final'];

  $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $Id_Lic);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $Empresa_Compra);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $Clasif);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $Tipo);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $Vers);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $Prod);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $Num_Licencia);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $Ubic);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $Numero_Factura);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('J'.$Fila, $Fecha_Inicio);
  $objPHPExcel->setActiveSheetIndex()->setCellValue('K'.$Fila, $Fecha_Final);
      
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

$n = 'Licencias_x_finalizar_'.date('Y-m-d H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
ob_end_clean();
$objWriter->save('php://output');
exit;

?>

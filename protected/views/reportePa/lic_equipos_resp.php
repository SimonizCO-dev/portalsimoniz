<?php
/* @var $this ReporteController */
/* @var $model Reporte */

set_time_limit(0);

if (isset($model['empresa_compra'])) { $empresa_compra = implode(",", $model['empresa_compra']); } else { $empresa_compra = "";  }
if (isset($model['tipo_equipo'])) { $tipo_equipo = implode(",", $model['tipo_equipo']); } else { $tipo_equipo = "";  }
if (isset($model['tipo_lic'])) { $tipo_lic = $model['tipo_lic']; } else { $tipo_lic = ""; }
if (isset($model['version'])) { $version = $model['version']; } else { $version = ""; }

//se arma el arreglo con los datos para el reporte

if($tipo_lic == Yii::app()->params->version_so){
  //S.O

  $condicion = "";

  if($empresa_compra != ""){
    $condicion .= "AND t2.Empresa_Compra IN (".$empresa_compra.")";
  }

  if($tipo_equipo != ""){
    $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
  }

  if($version != ""){
    $condicion .= "AND t1.Version = ".$version;
  } 

  $query = "
    SELECT
    t2.Serial AS Serial
    ,t2.Modelo AS Modelo
    ,t3.Dominio AS Tipo_Licencia
    ,t4.Dominio AS Version
    ,t1.Num_Licencia AS Licencia
    ,t5.Proveedor
    ,t6.Descripcion AS Empresa
    FROM TH_EQUIPO_SO t1
    INNER JOIN TH_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
    INNER JOIN TH_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
    INNER JOIN TH_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
    INNER JOIN TH_PROVEEDOR AS t5 ON t2.Proveedor = t5.Id_Proveedor
    INNER JOIN TH_EMPRESA AS t6 ON t2.Empresa_Compra = t6.Id_Empresa
    WHERE 1 = 1
    ".$condicion."
    ORDER BY 3,5
  ";

}

if($tipo_lic == Yii::app()->params->version_office){
  //Office
  
   $condicion = "";

  if($empresa_compra != ""){
    $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
  }

  if($tipo_equipo != ""){
    $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
  }

  if($version != ""){
    $condicion .= "AND t1.Version = ".$version;
  } 

  $query = "
    SELECT
    t2.Serial AS Serial
    ,t2.Modelo AS Modelo
    ,t3.Dominio AS Tipo_Licencia
    ,t4.Dominio AS Version
    ,t1.Num_Licencia AS Licencia
    ,t1.Fecha_Inicio as Fecha_Inicio
    ,t1.Fecha_Final as Fecha_Fin
    ,t5.Proveedor
    ,t6.Descripcion AS Empresa
    FROM TH_EQUIPO_OFFICE t1
    INNER JOIN TH_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
    INNER JOIN TH_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
    INNER JOIN TH_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
    INNER JOIN TH_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Proveedor
    INNER JOIN TH_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Empresa
    WHERE 1 = 1
    ".$condicion."
    ORDER BY 3,5
  ";

}

if($tipo_lic == Yii::app()->params->version_adobe){
  //Adobe
  
   $condicion = "";

  if($empresa_compra != ""){
    $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
  }

  if($tipo_equipo != ""){
    $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
  }

  if($version != ""){
    $condicion .= "AND t1.Version = ".$version;
  } 

  $query = "
    SELECT
    t2.Serial AS Serial
    ,t2.Modelo AS Modelo
    ,t3.Dominio AS Tipo_Licencia
    ,t4.Dominio AS Version
    ,t1.Num_Licencia AS Licencia
    ,t1.Fecha_Inicio as Fecha_Inicio
    ,t1.Fecha_Final as Fecha_Fin
    ,t5.Proveedor
    ,t6.Descripcion AS Empresa
    FROM TH_EQUIPO_ADOBE t1
    INNER JOIN TH_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
    INNER JOIN TH_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
    INNER JOIN TH_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
    INNER JOIN TH_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Proveedor
    INNER JOIN TH_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Empresa
    WHERE 1 = 1
    ".$condicion."
    ORDER BY 3,5
  ";

}

if($tipo_lic == Yii::app()->params->version_autodesk){
  //Autodesk
    
   $condicion = "";

  if($empresa_compra != ""){
    $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
  }

  if($tipo_equipo != ""){
    $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
  }

  if($version != ""){
    $condicion .= "AND t1.Version = ".$version;
  } 

  $query = "
    SELECT
    t2.Serial AS Serial
    ,t2.Modelo AS Modelo
    ,t3.Dominio AS Tipo_Licencia
    ,t4.Dominio AS Version
    ,t1.Num_Licencia AS Licencia
    ,t5.Proveedor
    ,t6.Descripcion AS Empresa
    FROM TH_EQUIPO_AUTODESK t1
    INNER JOIN TH_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
    INNER JOIN TH_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
    INNER JOIN TH_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
    INNER JOIN TH_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Proveedor
    INNER JOIN TH_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Empresa
    WHERE 1 = 1
    ".$condicion."
    ORDER BY 3,5
  ";

}

if($tipo_lic == Yii::app()->params->version_antivirus){
  //Antivirus
    
   $condicion = "";

  if($empresa_compra != ""){
    $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
  }

  if($tipo_equipo != ""){
    $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
  }

  if($version != ""){
    $condicion .= "AND t1.Version = ".$version;
  } 

  $query = "
    SELECT
    t2.Serial AS Serial
    ,t2.Modelo AS Modelo
    ,t3.Dominio AS Tipo_Licencia
    ,t4.Dominio AS Version
    ,t1.Num_Licencia AS Licencia
    ,t5.Proveedor
    ,t6.Descripcion AS Empresa
    FROM TH_EQUIPO_ANTIVIRUS t1
    INNER JOIN TH_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
    INNER JOIN TH_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
    INNER JOIN TH_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
    INNER JOIN TH_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Proveedor
    INNER JOIN TH_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Empresa
    WHERE 1 = 1
    ".$condicion."
    ORDER BY 3,5
  ";

}

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

if($tipo_lic == Yii::app()->params->version_so || $tipo_lic == Yii::app()->params->version_autodesk || $tipo_lic == Yii::app()->params->version_antivirus){
      
  
  $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'Serial');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'Modelo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Tipo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Versión');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Licencia');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Proveedor');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Empresa');

  $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

}  

if($tipo_lic == Yii::app()->params->version_office || $tipo_lic == Yii::app()->params->version_adobe){

  $objPHPExcel->setActiveSheetIndex()->setCellValue('A1', 'Serial');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('B1', 'Modelo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('C1', 'Tipo');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('D1', 'Versión');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('E1', 'Licencia');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('F1', 'Fecha inicio');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('G1', 'Fecha fin');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('H1', 'Proveedor');
  $objPHPExcel->setActiveSheetIndex()->setCellValue('I1', 'Empresa');

  $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);

}

/*Inicio contenido tabla*/
    
$Fila = 2;

$q_lic = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q_lic)){
  foreach ($q_lic as $reg1) {

    if($tipo_lic == Yii::app()->params->version_so || $tipo_lic == Yii::app()->params->version_autodesk || $tipo_lic == Yii::app()->params->version_antivirus){
      
      $Serial         = $reg1 ['Serial']; 
      $Modelo         = $reg1 ['Modelo']; 
      $Tipo_Licencia  = $reg1 ['Tipo_Licencia'];
      $Version        = $reg1 ['Version'];
      $Licencia       = $reg1 ['Licencia'];
      $Proveedor      = $reg1 ['Proveedor'];
      $Empresa        = $reg1 ['Empresa'];

      $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $Serial);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $Modelo);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $Tipo_Licencia);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $Version);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $Licencia);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $Proveedor);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $Empresa);

      $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':G'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    }  

    if($tipo_lic == Yii::app()->params->version_office || $tipo_lic == Yii::app()->params->version_adobe){

      $Serial         = $reg1 ['Serial']; 
      $Modelo         = $reg1 ['Modelo']; 
      $Tipo_Licencia  = $reg1 ['Tipo_Licencia'];
      $Version        = $reg1 ['Version'];
      $Licencia       = $reg1 ['Licencia'];
      
      if($reg1 ['Fecha_Fin'] != ""){
        $Fecha_Inicio   = $reg1 ['Fecha_Fin'];
      }else{
        $Fecha_Inicio   = '-';
      }

      if($reg1 ['Fecha_Fin'] != ""){
        $Fecha_Fin   = $reg1 ['Fecha_Fin'];
      }else{
        $Fecha_Fin   = '-';
      }
      
      $Proveedor      = $reg1 ['Proveedor'];
      $Empresa        = $reg1 ['Empresa'];

      $objPHPExcel->setActiveSheetIndex()->setCellValue('A'.$Fila, $Serial);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('B'.$Fila, $Modelo);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('C'.$Fila, $Tipo_Licencia);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('D'.$Fila, $Version);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('E'.$Fila, $Licencia);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('F'.$Fila, $Fecha_Inicio);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('G'.$Fila, $Fecha_Fin);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('H'.$Fila, $Proveedor);
      $objPHPExcel->setActiveSheetIndex()->setCellValue('I'.$Fila, $Empresa);

      $objPHPExcel->getActiveSheet()->getStyle('A'.$Fila.':I'.$Fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    }

    $Fila = $Fila + 1; 
      
  }
  
}

if($tipo_lic == Yii::app()->params->version_so){
  $filename = "Lic_SO_Equipos_".date('Y-m-d H_i_s').".xlsx";
}

if($tipo_lic == Yii::app()->params->version_office){
  $filename = "Lic_Office_Equipos_".date('Y-m-d H_i_s').".xlsx";
}

if($tipo_lic == Yii::app()->params->version_adobe){
  $filename = "Lic_Adobe_Equipos_".date('Y-m-d H_i_s').".xlsx"; 
}

if($tipo_lic == Yii::app()->params->version_autodesk){
  $filename = "Lic_Autodesk_Equipos_".date('Y-m-d H_i_s').".xlsx";
}

if($tipo_lic == Yii::app()->params->version_antivirus){
  $filename = "Lic_Antivirus_Equipos_".date('Y-m-d H_i_s').".xlsx";
}


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

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
ob_end_clean();
$objWriter->save('php://output');
exit;

?>












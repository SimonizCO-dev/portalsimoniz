<?php

spl_autoload_unregister(array('YiiBase','autoload'));  
require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

spl_autoload_register(array('YiiBase','autoload'));

/*
$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
*/
$objPHPExcel=new Spreadsheet();

/*$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');*/

$sql = "
select 
T_PR_CONT.id_contrato,
 case when T_PR_CONT.Tipo=1 then 'CLIENTE' else 'PROVEEDOR' END as tipo
,b.Descripcion as Empresa,
Razon_Social,
Concepto_Contrato,
Dominio as Periodicidad,
Area, 
isnull(dbo.F_Trae_Moneda (id_contrato),0)  as Valor,
Fecha_Inicial  as Fecha_Inicial,
Fecha_Final  as Fecha_Final,
Fecha_Ren_Can  as Fecha_Ren_Can,
case when T_PR_CONT.Estado=1 then 'ACTIVO' else 'INACTIVO' end  as Estado,
REPLACE(REPLACE(CAST(Observaciones AS varchar(MAX)),CHAR(10), ''),char(13),'') AS Observaciones
from T_PR_CONT
inner join T_PR_PA_EMPRESA b on T_PR_CONT.Empresa=id_pa_empresa
inner join T_PR_DOMINIO on T_PR_CONT.Periodicidad=T_PR_DOMINIO.Id_Dominio";
$data = Yii::app()->db->createCommand($sql)->queryAll();


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1','ID');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1','TIPO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1','EMPRESA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1','RAZON SOCIAL');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1','CONCEPTO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1','PERIODICIDAD DE PAGO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1','AREA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1','VALOR');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1','FECHA DE INICIO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1','FECHA DE FIN');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1','FECHA DE REN./CANC');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1','ESTADO');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1','OBSERVACIONES');


#$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getAlignment()->setHorizontal($alignment_center);
#$objPHPExcel->getActiveSheet(0)->getStyle('A1:P1')->getFont()->setBold(true);

$Fila=2;

/*Inicio contenido tabla*/

foreach ($data as $reg1) {

    $ID=$reg1['id_contrato'];
    $TIPO=$reg1['tipo'];
    $EMPRESA=$reg1['Empresa'];
    $RAZON_SOCIAL=$reg1['Razon_Social'];
    $Concepto_Contrato=$reg1['Concepto_Contrato'];
    $Periodicidad=$reg1['Periodicidad'];
    $Area=$reg1['Area'];
    $Valor=$reg1['Valor'];
    $Fecha_Inicial=str_replace('í','i',str_replace('á','a',str_replace('é','e',$reg1['Fecha_Inicial'])));
    $Fecha_Final=str_replace('í','i',str_replace('á','a',str_replace('é','e',$reg1['Fecha_Final'])));
    $Fecha_Ren_Can=str_replace('í','i',str_replace('á','a',str_replace('é','e',$reg1['Fecha_Ren_Can'])));
    $Estado=$reg1['Estado'];
    $Observaciones=$reg1['Observaciones'];
  

      
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $ID);
 $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $TIPO);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $EMPRESA );
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $RAZON_SOCIAL);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $Concepto_Contrato);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $Periodicidad);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $Area);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$Fila, $Valor);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $Fecha_Inicial);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $Fecha_Final);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $Fecha_Ren_Can);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $Estado);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $Observaciones);

  #$objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':S'.$Fila)->getAlignment()->setHorizontal($alignment_left);

  $Fila ++;
       
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 20; 
/*
foreach (range(0, $nCols) as $col) {
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}*/

$n = 'Contratos_'.date('Y_m_d_H_i_s');


header('Content-Encoding: UTF-8');
header('Content-Type: application/vnd.ms-excel;charset=utf-8');
header('Content-Disposition: attachment;filename="'.$n.'.Csv"');
header('Cache-Control: max-age=0');
#$objPHPExcel=str_replace("\xef\xbb\xbf", '', $objPHPExcel);
$objWriter = new Csv($objPHPExcel);

$objWriter->save('php://output');
exit;


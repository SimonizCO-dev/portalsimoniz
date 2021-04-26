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
$fecha_inicial = $model['fecha_inicial'];
$fecha_final = $model['fecha_final'];

$FechaM1 = str_replace("-","",$fecha_inicial);
$FechaM2 = str_replace("-","",$fecha_final);

/*inicio configuración array de datos*/

//EXCEL

$query ="
  EXEC P_PR_COM_CONS_CLIENTE_FCH 
  @FECHA1 = N'".$FechaM1."',
  @FECHA2 = N'".$FechaM2."'
";

UtilidadesVarias::log($query);

$alignment_center = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
$alignment_left = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
$alignment_right = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
$type_string = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;

$objPHPExcel = new Spreadsheet();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Reporte');

/*Cabecera tabla*/

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID Tercero');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Tipo de identificación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Nit cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Tipo de persona');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Razón social');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Apellidos');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Nombres');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'ID Sucursal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Nombre sucursal');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Cupo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Estado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Tipo cliente');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Bloqueado');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Bloqueo de cupo');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Bloqueo de mora');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Ciudad');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Dirección');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'Condición de pago');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'Días de gracias');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'Lista de precio');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'Iva');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'Ica');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'Renta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X1', 'Rete IVA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y1', 'Rete ICA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z1', 'RTARTA');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA1', 'CO facturación');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'CO movimiento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC1', 'Coordinador');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'Ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE1', 'Descripción de ruta');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF1', 'Fecha de nacimiento');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG1', 'E-mail');
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AG1')->getAlignment()->setHorizontal($alignment_center);
$objPHPExcel->getActiveSheet(0)->getStyle('A1:AG1')->getFont()->setBold(true);

/*Inicio contenido tabla*/
    
$Fila = 2;

$q1 = Yii::app()->db->createCommand($query)->queryAll();

if(!empty($q1)){
  foreach ($q1 as $reg1) {

    $A  = $reg1 ['C_ID_TERCERO']; 
    $B  = $reg1 ['C_TIPO_ID']; 
    $C  = $reg1 ['C_NIT_CLIENTE'];
    $D  = $reg1 ['C_TIPO_PERSONA'];
    $E  = $reg1 ['C_NOMBRE_CLIENTE'];
    $F  = $reg1 ['C_APELLIDO'];
    $G  = $reg1 ['C_NOMBRES'];
    $H  = $reg1 ['C_ID_SUCURSAL'];
    $I  = $reg1 ['C_NOMBRE_SUCURSAL']; 
    $J  = $reg1 ['C_CUPO']; 
    $K  = $reg1 ['C_ESTADO'];
    $L  = $reg1 ['C_TIPO_CLIENTE'];
    $M  = $reg1 ['C_BLOQUEADO'];
    $N  = $reg1 ['C_BLOQ_CUPO'];
    $O  = $reg1 ['C_BLOQ_MORA'];
    $P  = $reg1 ['C_CIUDAD'];
    $Q  = $reg1 ['C_DIRECCION']; 
    $R  = $reg1 ['C_COND_PAGO_CLI']; 
    $S  = $reg1 ['C_DIAS_GRACIA'];
    $T  = $reg1 ['C_LISTA_PRECIO'];
    $U  = $reg1 ['C_IVA'];
    $V  = $reg1 ['C_ICA'];
    $W  = $reg1 ['C_RENTA'];
    $X  = $reg1 ['C_RTIVA'];
    $Y  = $reg1 ['C_RTICA']; 
    $Z = $reg1 ['C_RTARTA']; 
    $AA = $reg1 ['C_CO_FACT'];
    $AB = $reg1 ['C_CO_MOVTO'];
    $AC = $reg1 ['COORDINADOR'];
    $AD = $reg1 ['C_RUTA'];
    $AE = $reg1 ['RUTA'];
    $AF = $reg1 ['C_FECHA_NAC'];
    $f = new DateTime($AF);
    $fecha = $f->format('Y-m-d');
    $AG = $reg1 ['CORREO'];

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Fila, $A);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$Fila, $B);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Fila, $C);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Fila, $D);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Fila, $E);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$Fila, $F);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$Fila, $G);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('H'.$Fila, $H, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$Fila, $I);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$Fila, $J);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$Fila, $K);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$Fila, $L);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$Fila, $M);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$Fila, $N);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$Fila, $O);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$Fila, $P);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$Fila, $Q);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$Fila, $R);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$Fila, $S);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('T'.$Fila, $T, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$Fila, $U);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$Fila, $V);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$Fila, $W);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$Fila, $X);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$Fila, $Y);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$Fila, $Z);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AA'.$Fila, $AA, $type_string);
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit('AB'.$Fila, $AB, $type_string);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AC'.$Fila, $AC);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AD'.$Fila, $AD);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('AE'.$Fila, $AE);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$Fila, $fecha);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$Fila, $AG);

    $objPHPExcel->getActiveSheet(0)->getStyle('J'.$Fila)->getNumberFormat()->setFormatCode('#,##0.00');
    $objPHPExcel->getActiveSheet(0)->getStyle('A'.$Fila.':AG'.$Fila)->getAlignment()->setHorizontal($alignment_left);

    $Fila = $Fila + 1; 
      
  }
}

/*fin contenido tabla*/

//se configura el ancho de cada columna en automatico solo funciona en el rango A-Z
$nCols = 33; 

foreach (range(0, $nCols) as $col) {
  $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);                
}

$n = 'Consulta_clientes_x_fecha_'.date('Y_m_d_H_i_s');

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$n.'.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
ob_end_clean();
$objWriter->save('php://output');
exit;


?>












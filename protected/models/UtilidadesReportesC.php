<?php

//clase creada para funciones relacionadas con el modelo de reportes

class UtilidadesReportesC {
  
  public static function verificacionrecibospantalla() {

    $query ="
      SET NOCOUNT ON EXEC CRC_CONS_RECIBOS
    ";

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Número físico 1</th>
                <th>Ruta de recibo</th>
                <th>Fecha de creación 1</th>
                <th>Usuario 1</th>
                <th>Número físico 2</th>
                <th>Recibo siesa</th>
                <th>Fecha de creación 2</th>
                <th>Fecha de aprobación</th>
                <th>Usuario 2</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        if($reg1 ['NUMERO_FISICO1'] == 0 ){
          $NUMERO_FISICO1 = ""; 
        }else{
          $NUMERO_FISICO1 = $reg1 ['NUMERO_FISICO1']; 
        } 

        $RUTA_RECIBO  = $reg1 ['RUTA_RECIBO'];

        if($reg1 ['FECHA_CREACION1'] == ""){
          $FECHA_CREACION1 = "";
        }else{
          $FECHA_CREACION1 = date("Y-m-d", strtotime($reg1 ['FECHA_CREACION1']));
        }

        $USUARIO1  = $reg1 ['USUARIO1'];
        
        if($reg1 ['NUMERO_FISICO2'] == 0 ){
          $NUMERO_FISICO2 = ""; 
        }else{
          $NUMERO_FISICO2 = $reg1 ['NUMERO_FISICO2']; 
        } 

        $RECIBO_SIESA  = $reg1 ['RECIBO_SIESA'];

        if($reg1 ['FECHA_CREACION2'] == ""){
          $FECHA_CREACION2 = "";
        }else{
          $FECHA_CREACION2 = date("Y-m-d", strtotime($reg1 ['FECHA_CREACION2']));
        }

        if($reg1 ['FECHA_APROBACION'] == ""){
          $FECHA_APROBACION = "";
        }else{
          $FECHA_APROBACION = date("Y-m-d", strtotime($reg1 ['FECHA_APROBACION']));
        }

        $USUARIO2  = $reg1 ['USUARIO2'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$NUMERO_FISICO1.'</td>
              <td>'.$RUTA_RECIBO.'</td>
              <td>'.$FECHA_CREACION1.'</td>
              <td>'.$USUARIO1.'</td>
              <td>'.$NUMERO_FISICO2.'</td>
              <td>'.$RECIBO_SIESA.'</td>
              <td>'.$FECHA_CREACION2.'</td>
              <td>'.$FECHA_APROBACION.'</td>
              <td>'.$USUARIO2.'</td>
          </tr>';

        $i++; 

      }

      if($i <= 1){
        $tabla .= ' 
          <tr><td colspan="2" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
        ';
      }

    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function controlrecibospantalla($fecha_inicial, $fecha_final) {

    $f_inicial = new DateTime($fecha_inicial);
    $f_final = new DateTime($fecha_final);
    $diff = $f_inicial->diff($f_final);

    $diff_days = $diff->days + 1;

    $total_rc = 0;
    $total_rv = 0;
    $total_ra = 0;

    $tabla = '
    <table class="table table-sm table-hover">
            <thead>
              <tr>
              <th>Fecha</th>
              <th>N°. recibos cargados</th>
              <th>N°. recibos verificados</th>
              <th>N°. recibos aplicados</th>
              </tr>
            </thead>
        <tbody>';


    for ($i=0; $i < $diff_days; $i++) { 
      $fecha = date($fecha_inicial);
      $nueva_fecha = strtotime('+'.$i.' day' , strtotime ($fecha)) ;
      $nueva_fecha = date ( 'Y-m-d' , $nueva_fecha);

      $r_i = $nueva_fecha.' 00:00:00';
      $r_f = $nueva_fecha.' 23:59:59';

      //recibos cargados
      $rc = Yii::app()->db->createCommand("SELECT COUNT(Id_Control) AS Rec_Carg FROM T_PR_CONTROL_RECIBOS WHERE Fecha_Hora_Carga BETWEEN '$r_i' AND '$r_f'")->queryRow();
      $recibos_cargados = $rc['Rec_Carg'];

      $total_rc = $total_rc + $recibos_cargados; 

      //recibos verificados
      $rv = Yii::app()->db->createCommand("SELECT COUNT(Id_Control) AS Rec_Verif FROM T_PR_CONTROL_RECIBOS WHERE Fecha_Hora_Verif BETWEEN '$r_i' AND '$r_f'")->queryRow();
      $recibos_verificados = $rv['Rec_Verif'];

      $total_rv = $total_rv + $recibos_verificados; 

      //recibos aplicados
      $rv = Yii::app()->db->createCommand("SELECT COUNT(Id_Control) AS Rec_Aplic FROM T_PR_CONTROL_RECIBOS WHERE Fecha_Hora_Aplic BETWEEN '$r_i' AND '$r_f'")->queryRow();
      $recibos_aplicados = $rv['Rec_Aplic'];

      $total_ra = $total_ra + $recibos_aplicados; 

      if ($i % 2 == 0){
        $clase = 'odd'; 
      }else{
        $clase = 'even'; 
      }

      $tabla .= '    
      <tr class="'.$clase.'">
            <td>'.UtilidadesVarias::textofecha($nueva_fecha).'</td>
            <td align="right">'.$recibos_cargados.'</td>
            <td align="right">'.$recibos_verificados.'</td>
            <td align="right">'.$recibos_aplicados.'</td>
        </tr>';


    }

    $tabla .= '    
      <tr class="'.$clase.'">
            <th style="text-align:right !important;">TOTAL</th>
            <th style="text-align:right !important;">'.$total_rc.'</th>
            <th style="text-align:right !important;">'.$total_rv.'</th>
            <th style="text-align:right !important;">'.$total_ra.'</th>
        </tr>';

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

}

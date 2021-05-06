<?php

//clase creada para funciones relacionadas con el modelo de reportes Th

class UtilidadesReportesTh {

  public static function ausenciaspantalla($motivo_ausencia, $fecha_inicial, $fecha_final, $empresa, $id_empleado) {

    if($fecha_inicial == "" && $fecha_final != ""){
      $fecha_inicial = $fecha_final;
    }

    if($fecha_inicial != "" && $fecha_final == ""){
      $fecha_final = $fecha_inicial;
    }

    if($id_empleado == "" && $fecha_inicial != "" && $fecha_final != "" && $motivo_ausencia == "" && $empresa != ""){
      $o = 1;
    }

    if($id_empleado != "" && $fecha_inicial != "" && $fecha_final != "" && $motivo_ausencia == "" && $empresa != ""){
      $o = 2;
    }

    if($id_empleado != "" && $fecha_inicial != "" && $fecha_final != "" && $motivo_ausencia != "" && $empresa != ""){
      $o = 3;
    }

    if($id_empleado == "" && $fecha_inicial != "" && $fecha_final != "" && $motivo_ausencia != "" && $empresa != ""){
      $o = 4;
    }

    $empresa = implode(",", $empresa);
    
    if($motivo_ausencia != null){
      $motivo_ausencia = implode(",", $motivo_ausencia);
    }

    $FechaM1 = str_replace("-","",$fecha_inicial);
    $FechaM2 = str_replace("-","",$fecha_final);

    $query ="
      SET NOCOUNT ON
      EXEC P_PR_GH_AUSENCIAS
      @OPT = ".$o.",
      @Id_Emp = '".$id_empleado."',
      @Fecha_Ini = N'".$FechaM1."',
      @Fecha_Fin = N'".$FechaM2."',
      @Motivo = '".$motivo_ausencia."',
      @Empresa = '".$empresa."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Tipo identificación</th>
                <th>No. identificación</th>
                <th>Empleado</th>
                <th>Empresa</th>
                <th>Motivo</th>
                <th>Fecha inicial</th>
                <th>Fecha final</th>
                <th>Días</th>
                <th>Horas</th>
                <th>Cod. soporte</th>
                <th>Descontar</th>
                <th>Descontar FDS</th>
                <th>Observaciones</th>
                <th>Notas</th>
                </tr>
              </thead>
          <tbody>';

        $query1 = Yii::app()->db->createCommand($query)->queryAll();

        $i = 1;

        if(!empty($query1)){
          foreach ($query1 as $reg1) {

            $tipo_ident       = $reg1['Tipo_Identificacion']; 
            $ident            = $reg1['Identificacion'];  
            $empleado         = $reg1['Apellido'].' '.$reg1['Nombre'];
            $empresa          = $reg1['Empresa'];
            $motivo           = $reg1['Motivo']; 
            $fecha_inicial    = $reg1['Fecha_Inicial']; 
            $fecha_final      = $reg1['Fecha_Final']; 
            $dias             = $reg1['Dias']; 
            
            if($reg1['Horas'] == 0.0){
              $horas = 0;
            }else{
              $horas = $reg1['Horas'];
            }

            $cod_soporte = $reg1['Cod_Soporte']; 
            $descontar = $reg1['Descontar'];
            $descontar_FDS = $reg1['Descontar_FDS'];
            
            
            if($reg1['Observacion'] != ""){
              $observaciones = $reg1['Observacion']; 
            }else{
              $observaciones = "-";
            }

            if($reg1['Nota'] != ""){
              $notas = $reg1['Nota']; 
            }else{
              $notas = "-";
            }

            if ($i % 2 == 0){
              $clase = 'odd'; 
            }else{
              $clase = 'even'; 
            }

            $tabla .= '    
            <tr class="'.$clase.'">
                <td>'.$tipo_ident.'</td>
                <td>'.$ident.'</td>
                <td>'.$empleado.'</td>
                <td>'.$empresa.'</td>
                <td>'.$motivo.'</td>
                <td>'.$fecha_inicial.'</td>
                <td>'.$fecha_final.'</td>
                <td>'.$dias.'</td>
                <td>'.$horas.'</td>
                <td>'.$cod_soporte.'</td>
                <td>'.$descontar.'</td>
                <td>'.$descontar_FDS.'</td>
                <td>'.$observaciones.'</td>
                <td>'.$notas.'</td>
            </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="14" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function hijospantalla($genero, $edad_inicial, $edad_final, $empresa) {
    
    $empresa = implode(",", $empresa);

    if($edad_inicial == "" && $edad_final != ""){
      $edad_inicial = $edad_final;
    }

    if($edad_inicial != "" && $edad_final == ""){
      $edad_final = $edad_inicial;
    }

    if(($edad_inicial != "" && $edad_inicial != "" && $genero == "" && $empresa != "") ){
      $o = 1;
    }

    if(($edad_inicial == "" && $edad_inicial == "" && $genero != "" && $empresa != "") ){
      $o = 2;
    }

    if(($edad_inicial == "" && $edad_inicial == "" && $genero == "" && $empresa != "") ){
      $o = 3;
    }

    if(($edad_inicial != "" && $edad_inicial != "" && $genero != "" && $empresa != "") ){
      $o = 4;
    }

    $query ="
      SET NOCOUNT ON
      EXEC P_PR_GH_HIJOS
      @OPT = ".$o.",
      @Edad_Ini = '".$edad_inicial."',
      @Edad_Fin = '".$edad_final."',
      @Genero = '".$genero."',
      @Empresa = '".$empresa."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Tipo identificación</th
                ><th>No. identificación</th>
                <th>Empleado</th>
                <th>Empresa</th>
                <th>Hijo</th>
                <th>Fecha de nacimiento</th>
                <th>Edad</th>
                <th>Género</th>
                </tr>
              </thead>
          <tbody>';

        $query1 = Yii::app()->db->createCommand($query)->queryAll();

        $i = 1;

        if(!empty($query1)){
          foreach ($query1 as $reg1) {
            
            $tipo_ident       = $reg1 ['Tipo_Ident']; 
            $ident            = $reg1 ['Identificacion'];  
            $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre'];
            $empresa          = $reg1 ['Empresa']; 
            $hijo             = $reg1 ['Hijo']; 
            $fecha_nacimiento = $reg1 ['Fecha_Nacimiento']; 
            $edad             = $reg1 ['Edad']; 
            $genero           = $reg1 ['Genero']; 

            if ($i % 2 == 0){
              $clase = 'odd'; 
            }else{
              $clase = 'even'; 
            }

            $tabla .= '    
            <tr class="'.$clase.'">
                  <td>'.$tipo_ident.'</td>
                  <td>'.$ident.'</td>
                  <td>'.$empleado.'</td>
                  <td>'.$empresa.'</td>
                  <td>'.$hijo.'</td>
                  <td>'.$fecha_nacimiento.'</td>
                  <td>'.$edad.'</td>
                  <td>'.$genero.'</td>
              </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="8" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function empleadosactivospantalla($fecha_inicial_cont, $fecha_final_cont, $empresa) {
    
    $empresa = implode(",", $empresa);

    if($fecha_inicial_cont == "" && $fecha_final_cont != ""){
      $fecha_inicial_cont = $fecha_final_cont;
    }

    if($fecha_inicial_cont != "" && $fecha_final_cont == ""){
      $fecha_final_cont = $fecha_inicial_cont;
    }

    if(($fecha_inicial_cont != "" && $fecha_final_cont != "" && $empresa != "")){
      $o = 1;
    }

    if(($fecha_inicial_cont == "" && $fecha_final_cont == "" && $empresa != "")){
      $o = 2;
    }

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

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Tipo identificación</th>
                <th>No. identificación</th>
                <th>Empleado</th>
                <th>Género</th>
                <th>Fecha de nacimiento</th>
                <th>E-mail</th>
                <th>Teléfono(s)</th>
                <th>Grado escolaridad</th>
                <th>Persona contacto</th>
                <th>Teléfono(s) contacto</th>
                <th>Empresa</th>
                <th>Unidad de gerencia</th>
                <th>Área</th>
                <th>Subárea</th>
                <th>Cargo</th>
                <th>Fecha de ingreso</th>
                <th>Salario</th>
                </tr>
              </thead>
          <tbody>';

        $query1 = Yii::app()->db->createCommand($query)->queryAll();

        $i = 1; 

        if(!empty($query1)){
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
              $gr_es = $reg1 ['Grado_Escolaridad']; 
            }else{
              $gr_es = "-";
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

            if ($i % 2 == 0){
              $clase = 'odd'; 
            }else{
              $clase = 'even'; 
            }

            $tabla .= '    
            <tr class="'.$clase.'">
                  <td>'.$tipo_ident.'</td>
                  <td>'.$ident.'</td>
                  <td>'.$empleado.'</td>
                  <td>'.$genero.'</td>
                  <td>'.$fecha_nacimiento.'</td>
                  <td>'.$correo.'</td>
                  <td>'.$telefono.'</td>
                  <td>'.$gr_es.'</td>
                  <td>'.$persona_contacto.'</td>
                  <td>'.$tel_persona_contacto.'</td>
                  <td>'.$empresa.'</td>
                  <td>'.$ug.'</td>
                  <td>'.$area.'</td>
                  <td>'.$subarea.'</td>
                  <td>'.$cargo.'</td>
                  <td>'.$fecha_ingreso.'</td>
                  <td>'.$salario.'</td>
              </tr>';

            $i++;
          }
        }else{
          $tabla .= ' 
          <tr><td colspan="13" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function contratosfinalizadospantalla($motivo_retiro, $liquidado, $fecha_inicial_fin, $fecha_final_fin, $empresa) {
        
    $empresa = implode(",", $empresa);
    
    if($motivo_retiro != null){
      $motivo_retiro = implode(",", $motivo_retiro);
    }

    if($fecha_inicial_fin == "" && $fecha_final_fin != ""){
      $fecha_inicial_fin = $fecha_final_fin;
    }

    if($fecha_inicial_fin != "" && $fecha_final_fin == ""){
      $fecha_final_fin = $fecha_inicial_fin;
    }

    if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado == "" && $motivo_retiro == "" && $empresa != "") ){
      $o = 1;
    }

    if(($fecha_inicial_fin == "" && $fecha_final_fin == "" && $liquidado == "" && $motivo_retiro == "" && $empresa != "") ){
      $o = 2;
    }

    if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado == "" && $motivo_retiro != "" && $empresa != "") ){
      $o = 3;
    }

    if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado != "" && $motivo_retiro == "" && $empresa != "") ){
      $o = 4;
    }

    if(($fecha_inicial_fin == "" && $fecha_final_fin == "" && $liquidado != "" && $motivo_retiro != "" && $empresa != "") ){
      $o = 5;
    }

    if(($fecha_inicial_fin == "" && $fecha_final_fin == "" && $liquidado != "" && $motivo_retiro == "" && $empresa != "") ){
      $o = 6;
    }

    if(($fecha_inicial_fin != "" && $fecha_final_fin != "" && $liquidado != "" && $motivo_retiro != "" && $empresa != "") ){
      $o = 7;
    }

    /*inicio configuración array de datos*/

    $FechaM1 = str_replace("-","",$fecha_inicial_fin);
    $FechaM2 = str_replace("-","",$fecha_final_fin);

    $query ="
      SET NOCOUNT ON
      EXEC P_PR_GH_EMPLEADO_INACT
      @OPT = ".$o.",
      @Fecha_Ini = '".$FechaM1."',
      @Fecha_Fin = '".$FechaM2."',
      @Empresa = '".$empresa."',
      @Motivo = '".$motivo_retiro."',
      @Liquidado = '".$liquidado."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
        <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Tipo identificación</th>
                <th>No. identificación</th>
                <th>Empleado</th>
                <th>Empresa</th>
                <th>Unidad de gerencia</th>
                <th>Área</th>
                <th>Subárea</th>
                <th>Cargo</th>
                <th>Fecha ingreso</th>
                <th>Fecha retiro</th>
                <th>Motivo</th>
                <th>Liquidado ?</th>
                </tr>
              </thead>
          <tbody>';

        $query1 = Yii::app()->db->createCommand($query)->queryAll();

        $i = 1;

        if(!empty($query1)){
          foreach ($query1 as $reg1) {

            $tipo_ident       = $reg1 ['Tipo_Identificacion']; 
            $ident            = $reg1 ['Identificacion']; 
            $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre']; 
            $empresa          = $reg1 ['Empresa']; 

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

            $fecha_ingreso    = $reg1 ['Fecha_Ingreso']; 
            $fecha_retiro     = $reg1 ['Fecha_Retiro'];
            $motivo           = $reg1 ['M_Retiro'];

            $liquidado        = $reg1 ['Liquidado'];

            if ($i % 2 == 0){
              $clase = 'odd'; 
            }else{
              $clase = 'even'; 
            }

            $tabla .= '    
            <tr class="'.$clase.'">
                  <td>'.$tipo_ident.'</td>
                  <td>'.$ident.'</td>
                  <td>'.$empleado.'</td>
                  <td>'.$empresa.'</td>
                  <td>'.$ug.'</td>
                  <td>'.$area.'</td>
                  <td>'.$subarea.'</td>
                  <td>'.$cargo.'</td>
                  <td>'.$fecha_ingreso.'</td>
                  <td>'.$fecha_retiro.'</td>
                  <td>'.$motivo.'</td>
                  <td>'.$liquidado.'</td>
              </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="12" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

  public static function disciplinariospantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $id_empleado) {

    if($fecha_inicial == "" && $fecha_final != ""){
      $fecha_inicial = $fecha_final;
    }

    if($fecha_inicial != "" && $fecha_final == ""){
      $fecha_final = $fecha_inicial;
    }

    if($id_empleado == "" && $fecha_inicial != "" && $fecha_final != "" && $motivo == "" && $empresa != ""){
      $o = 1;
    }

    if($id_empleado != "" && $fecha_inicial != "" && $fecha_final != "" && $motivo == "" && $empresa != ""){
      $o = 2;
    }

    if($id_empleado != "" && $fecha_inicial != "" && $fecha_final != "" && $motivo != "" && $empresa != ""){
      $o = 3;
    }

    if($id_empleado == "" && $fecha_inicial != "" && $fecha_final != "" && $motivo != "" && $empresa != ""){
      $o = 4;
    }

    $empresa = implode(",", $empresa);
    
    if($motivo != null){
      $motivo = implode(",", $motivo);
    }

    $FechaM1 = str_replace("-","",$fecha_inicial);
    $FechaM2 = str_replace("-","",$fecha_final);

    $query ="
      SET NOCOUNT ON
      EXEC P_PR_GH_DISCIPLINARIO
      @OPT = ".$o.",
      @Id_Emp = '".$id_empleado."',
      @Fecha_Ini = N'".$FechaM1."',
      @Fecha_Fin = N'".$FechaM2."',
      @Motivo = '".$motivo."',
      @Empresa = '".$empresa."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Tipo identificación</th>
            <th>No. identificación</th>
            <th>Empleado</th>
            <th>Empresa</th>
            <th>Evento</th>
            <th>Motivo</th>
            <th>Fecha</th>
            <th>Impuesto Por</th>
            <th>Orden No.</th>
            <th>Observaciones</th>
            </tr>
          </thead>
          <tbody>';

        $query1 = Yii::app()->db->createCommand($query)->queryAll();

        $i = 1;

        if(!empty($query1)){
          foreach ($query1 as $reg1) {

            $tipo_ident       = $reg1['Tipo_Identificacion']; 
            $ident            = $reg1['Identificacion'];  
            $empleado         = $reg1['Empleado'];
            $empresa          = $reg1['Empresa'];
            $disciplinario    = $reg1['Disciplinario'];
            $motivo           = $reg1['Motivo']; 
            $fecha_evento     = $reg1['Fecha']; 
            $persona_imp      = $reg1['Imp']; 
            $orden            = $reg1['Orden_No'];
            $observaciones    = $reg1['Observacion']; 

            if ($i % 2 == 0){
              $clase = 'odd'; 
            }else{
              $clase = 'even'; 
            }

            $tabla .= '    
            <tr class="'.$clase.'">
                <td>'.$tipo_ident.'</td>
                <td>'.$ident.'</td>
                <td>'.$empleado.'</td>
                <td>'.$empresa.'</td>
                <td>'.$disciplinario.'</td>
                <td>'.$motivo.'</td>
                <td>'.$fecha_evento.'</td>
                <td>'.$persona_imp.'</td>
                <td>'.$orden.'</td>
                <td>'.$observaciones.'</td>
            </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="10" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

  

  public static function elemherremppantalla($id_empleado) {

    //logica visibilidad boton nuevo contrato
    $query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_empleado.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

    if(is_null($query_contrato)){
      
      $tabla = '
        <h5>Elemento(s)</h5>
        <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Cantidad</th>
            <th>Elemento</th>
            <th>Subárea</th>
            <th>Área</th>
            <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td>
            </tr>
          </tbody>
        </table>
        <h5>Herramientas(s)</h5>
        <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Herramienta</th>
            <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <td colspan="2" class="empty"><span class="empty">No se encontraron resultados.</span></td>
            </tr>
          </tbody>
        </table>';

    }else{

      $contrato_act = $query_contrato['Id_Contrato'];

      //elementos
      $criteria=new CDbCriteria;
      $criteria->alias = "t";
      $criteria->join = "INNER JOIN T_PR_AREA_ELEMENTO ae ON t.Id_A_Elemento = ae.Id_A_elemento INNER JOIN T_PR_ELEMENTO e ON ae.Id_Elemento = e.Id_Elemento INNER JOIN T_PR_AREA a ON ae.Id_Area = a.Id_Area INNER JOIN T_PR_SUBAREA s ON ae.Id_Subarea = s.Id_Subarea";
      $criteria->condition = "t.Id_Contrato = :Id_Contrato AND t.Estado IN (1,3)";
      $criteria->order = "t.Estado DESC, e.Elemento ASC, s.Subarea ASC, a.Area ASC";
      $criteria->params = array (':Id_Contrato' => $contrato_act);

      $model_elementos_act = ElementoEmpleado::model()->findAll($criteria);

      $tabla = '
        <h5>Elemento(s)</h5>
        <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Cantidad</th>
            <th>Elemento</th>
            <th>Subárea</th>
            <th>Área</th>
            <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            ';

      if(!empty($model_elementos_act)){
        
        $i = 1; 

        foreach ($model_elementos_act as $reg) {
          
          if ($i % 2 == 0){
            $clase = 'odd'; 
          }else{
            $clase = 'even'; 
          }

          if($reg->idaelemento->Id_Subarea == "") { $s = "-"; } else{ $s = $reg->idaelemento->idsubarea->Subarea; }

          $tabla .= '    
          <tr class="'.$clase.'">
                <td align="right">'.$reg->Cantidad.'</td>
                <td>'.$reg->idaelemento->idelemento->Elemento.'</td>
                <td>'.$s.'</td>
                <td>'.$reg->idaelemento->idarea->Area.'</td>
                <td>'.UtilidadesElemento::textoestado($reg->Estado).'</td>
          </tr>';

          $i++;

        }
      }else{
        $tabla .= ' 
          <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
      }

      $tabla .= '</tbody>
        </table>';


      $tabla .= '
        <h5>Herramientas(s)</h5>
        <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Herramienta</th>
            <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            ';

      //herramientas
      $criteria2=new CDbCriteria;
      $criteria2->condition = "Id_Contrato = :Id_Contrato AND Estado IN (1,3)";
      $criteria2->order = "Fecha_Actualizacion DESC";
      $criteria2->params = array (':Id_Contrato' => $contrato_act);

      $model_herramientas_act = HerramientaEmpleado::model()->findAll($criteria2);

      if(!empty($model_herramientas_act)){
        
        $i = 1; 

        foreach ($model_herramientas_act as $reg) {
          
          if ($i % 2 == 0){
            $clase = 'odd'; 
          }else{
            $clase = 'even'; 
          }

          $tabla .= '    
          <tr class="'.$clase.'">
                <td>'.$reg->idherramienta->Nombre.'</td>
                <td>'.UtilidadesHerramienta::textoestado($reg->Estado).'</td>
          </tr>';

          $i++;

        }
      }else{
        $tabla .= ' 
          <tr><td colspan="2" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
      }

    $tabla .= '</tbody>
      </table>';

    }

    return $tabla;
  
  }


  public static function elemherrpendpantalla() {

    //elementos
    $criteria=new CDbCriteria;
    $criteria->alias = "t";
    $criteria->join = "INNER JOIN T_PR_EMPLEADO emp ON t.Id_Empleado = emp.Id_Empleado INNER JOIN T_PR_AREA_ELEMENTO ae ON t.Id_A_Elemento = ae.Id_A_elemento INNER JOIN T_PR_ELEMENTO e ON ae.Id_Elemento = e.Id_Elemento INNER JOIN T_PR_AREA a ON ae.Id_Area = a.Id_Area INNER JOIN T_PR_SUBAREA s ON ae.Id_Subarea = s.Id_Subarea";
    $criteria->condition = "t.Estado = 3";
    $criteria->order = "t.Fecha_Creacion ASC";

    $model_elementos = ElementoEmpleado::model()->findAll($criteria);

    $tabla = '
      <h5>Elemento(s)</h5>
      <table class="table table-sm table-hover">
        <thead>
          <tr>
          <th>Tipo identificación</th>
          <th>No. identificación</th>
          <th>Empleado</th>
          <th>Empresa</th>
          <th>Cantidad</th>
          <th>Elemento</th>
          <th>Subárea</th>
          <th>Área</th>
          <th>Fecha asignación</th>
          <th>Fecha entrega</th>
          </tr>
        </thead>
        <tbody>
          ';
      
      if(!empty($model_elementos)){
        
        $i = 1; 

        foreach ($model_elementos as $reg) {
          
          if ($i % 2 == 0){
            $clase = 'odd'; 
          }else{
            $clase = 'even'; 
          }

          if($reg->idaelemento->Id_Subarea == "") { $s = "-"; } else { $s = $reg->idaelemento->idsubarea->Subarea; }

          if($reg->idaelemento->Opc_Entr == 1) { $d = $reg->idaelemento->Dias_Entrega_Min; } else { $d = $reg->idaelemento->Dias_Entrega_Max; }

          $tabla .= '    
          <tr class="'.$clase.'">
                <td>'.$reg->idempleado->idtipoident->Dominio.'</td>
                <td>'.$reg->idempleado->Identificacion.'</td>
                <td>'.UtilidadesEmpleado::nombreempleado($reg->Id_Empleado).'</td>
                <td>'.$reg->idcontrato->idempresa->Descripcion.'</td>
                <td align="right">'.$reg->Cantidad.'</td>
                <td>'.$reg->idaelemento->idelemento->Elemento.'</td>
                <td>'.$s.'</td>
                <td>'.$reg->idaelemento->idarea->Area.'</td>
                <td>'.UtilidadesVarias::textofechahora($reg->Fecha_Creacion).'</td>
                <td>'.UtilidadesVarias::textofechahora($reg->Fecha_Creacion).'</td>
          </tr>';

          $i++;

        }
      }else{
        $tabla .= ' 
          <tr><td colspan="8" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
      }

      $tabla .= '</tbody>
        </table>';


      $tabla .= '
        <h5>Herramientas(s)</h5>
        <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Tipo identificación</th>
            <th>No. identificación</th>
            <th>Empleado</th>
            <th>Empresa</th>
            <th>Herramienta</th>
            </tr>
          </thead>
          <tbody>
            ';

      //herramientas
      $criteria2=new CDbCriteria;
      $criteria2->alias = "t";
      $criteria2->join = "INNER JOIN T_PR_EMPLEADO emp ON t.Id_Empleado = emp.Id_Empleado INNER JOIN T_PR_HERRAMIENTA h ON h.Id_Herramienta = t.Id_Herramienta";
      $criteria2->condition = "t.Estado = 3";
      $criteria2->order = "t.Fecha_Creacion DESC";

      $model_herramientas_act = HerramientaEmpleado::model()->findAll($criteria2);

      if(!empty($model_herramientas_act)){
        
        $i = 1; 

        foreach ($model_herramientas_act as $reg) {
          
          if ($i % 2 == 0){
            $clase = 'odd'; 
          }else{
            $clase = 'even'; 
          }

          $tabla .= '    
          <tr class="'.$clase.'">
                <td>'.$reg->idempleado->idtipoident->Dominio.'</td>
                <td>'.$reg->idempleado->Identificacion.'</td>
                <td>'.UtilidadesEmpleado::nombreempleado($reg->Id_Empleado).'</td>
                <td>'.$reg->idcontrato->idempresa->Descripcion.'</td>
                <td>'.$reg->idherramienta->Nombre.'</td>
          </tr>';

          $i++;

        }
      }else{
        $tabla .= ' 
          <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
      }

    $tabla .= '</tbody>
      </table>';

    return $tabla;  

  }


  public static function evaluacpantalla() {

    $query ="
    SELECT 
    Identificacion
    ,LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(CAST(Apellido AS nvarchar(500)),CHAR(9),''),CHAR(10),''),CHAR(13),''))) AS Apellido
    ,LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(CAST(Nombre   AS nvarchar(500)) ,CHAR(9),''),CHAR(10),''),CHAR(13),''))) AS Nombre
    ,Fecha
    ,Dominio AS Tipo
    ,Puntaje
    ,LTRIM(RTRIM(REPLACE(REPLACE(REPLACE(CAST(t3.Observacion AS nvarchar(500)),CHAR(9),''),CHAR(10),''),CHAR(13),''))) AS Observacion
    FROM T_PR_EMPLEADO AS t1
    INNER JOIN T_PR_CONTRATO_EMPLEADO AS t2 on t2.Id_Empleado=t1.Id_Empleado AND t2.Fecha_Retiro is null
    INNER JOIN T_PR_EVALUACION_EMPLEADO AS t3 on t3.Id_Empleado=t2.Id_Empleado
    INNER JOIN T_PR_DOMINIO AS t4 on t4.Id_Padre=252 AND t4.Id_Dominio=t3.Id_Tipo
    ORDER BY 2,4 
    ";

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>No. identificación</th>
                <th>Empleado</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Puntaje</th>
                <th>Observaciones</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $identificacion   = $reg1 ['Identificacion']; 
        $empleado         = $reg1 ['Apellido'].' '.$reg1 ['Nombre']; 
        $fecha            = $reg1 ['Fecha'];
        $tipo             = $reg1 ['Tipo'];
        $puntaje          = $reg1 ['Puntaje'];
        $observaciones    = $reg1 ['Observacion'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$identificacion.'</td>
              <td>'.$empleado.'</td>
              <td>'.$fecha.'</td>
              <td>'.$tipo.'</td>
              <td>'.$puntaje.'</td>
              <td>'.$observaciones.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="8" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

}

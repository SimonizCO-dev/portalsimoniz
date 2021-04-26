<?php

//clase creada para funciones relacionadas con el modelo de reportes Th

class UtilidadesReportesTh {
  
  public static function empleadosactivospantalla($fecha_inicial_cont, $fecha_final_cont, $empresa) {
    
    $condicion = "WHERE HP.Id_M_Retiro IS NULL AND P.Estado = 1";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }

    if($fecha_inicial_cont != null && $fecha_final_cont != null){
      $condicion .= " AND HP.Fecha_Ingreso BETWEEN '".$fecha_inicial_cont."' AND '".$fecha_final_cont."'";
    }else{
      if($fecha_inicial_cont != null && $fecha_final_cont == null){
        $condicion .= " AND HP.Fecha_Ingreso = '".$fecha_inicial_cont."'";
      }
    }

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
    FROM T_PR_CONTRATO_EMPLEADO HP 
    LEFT JOIN T_PR_EMPLEADO P ON HP.Id_Empleado = P.Id_Empleado 
    LEFT JOIN T_PR_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio 
    LEFT JOIN T_PR_DOMINIO GE ON P.Id_Genero = GE.Id_Dominio
    LEFT JOIN T_PR_DOMINIO GRES ON P.Id_Grado_Esc = GRES.Id_Dominio  
    LEFT JOIN T_PR_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa 
    LEFT JOIN T_PR_UNIDAD_GERENCIA UG ON HP.Id_Unidad_Gerencia = UG.Id_Unidad_Gerencia 
    LEFT JOIN T_PR_AREA A ON HP.Id_Area = A.Id_Area
    LEFT JOIN T_PR_SUBAREA S ON HP.Id_Subarea = S.Id_Subarea
    LEFT JOIN T_PR_CARGO C ON HP.Id_Cargo = C.Id_Cargo 
    ".$condicion."
    ORDER BY 7,8,9,10,11,3 ASC
    ";

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
              $gr_es = $reg1 ['Gr_Esc']; 
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
    
    $condicion = "WHERE HP.Id_M_Retiro IS NOT NULL";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }

    if($motivo_retiro != null){
      $motivo_retiro = implode(",", $motivo_retiro);
      $condicion .= " AND HP.Id_Retiro IN (".$motivo_retiro.")";
    }

    if($fecha_inicial_fin != null && $fecha_final_fin != null){
      $condicion .= " AND HP.Fecha_Retiro BETWEEN '".$fecha_inicial_fin."' AND '".$fecha_final_fin."'";
    }else{
      if($fecha_inicial_fin != null && $fecha_final_fin == null){
        $condicion .= " AND HP.Fecha_Retiro = '".$fecha_inicial_fin."'";
      } 
    }

    if($liquidado != null){
      if($liquidado == 1){
        $condicion .= " AND HP.Fecha_Liquidacion IS NOT NULL"; 
      }else{
        $condicion .= " AND HP.Fecha_Liquidacion IS NULL";
      }
    }

   $query ="
    SELECT  
    TI.Dominio AS Tipo_Ident, 
    P.Identificacion, 
    CONCAT (P.Apellido, ' ', P.Nombre) AS Empleado, 
    E.Descripcion AS Empresa,
    UG.Unidad_Gerencia,
    A.Area,
    S.Subarea,
    C.Cargo,
    CIU.Ciudad,
    HP.Fecha_Ingreso, 
    HP.Fecha_Retiro,
    MO.Dominio AS Motivo,  
    CASE WHEN HP.Fecha_Liquidacion IS NULL THEN 'NO'
    ELSE CONCAT('SI - ', HP.Fecha_Liquidacion )                                       
    END AS Liquidado
    FROM T_PR_CONTRATO_EMPLEADO HP 
    LEFT JOIN T_PR_EMPLEADO P ON HP.Id_Empleado = P.Id_Empleado 
    LEFT JOIN T_PR_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio
    LEFT JOIN T_PR_CIUDAD CIU ON P.Id_Ciudad_Labor = CIU.Id_Ciudad  
    LEFT JOIN T_PR_DOMINIO MO ON HP.Id_M_Retiro = MO.Id_Dominio 
    LEFT JOIN T_PR_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
    LEFT JOIN T_PR_UNIDAD_GERENCIA UG ON HP.Id_Unidad_Gerencia = UG.Id_Unidad_Gerencia 
    LEFT JOIN T_PR_AREA A ON HP.Id_Area = A.Id_Area
    LEFT JOIN T_PR_SUBAREA S ON HP.Id_Subarea = S.Id_Subarea
    LEFT JOIN T_PR_CARGO C ON HP.Id_Cargo = C.Id_Cargo 
    ".$condicion." 
    ORDER BY 4,5,6,7,8,3,11 ASC
    ";

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
                <th>Dpto - municipio labor</th>
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

            $tipo_ident       = $reg1 ['Tipo_Ident']; 
            $ident            = $reg1 ['Identificacion']; 
            $empleado         = $reg1 ['Empleado']; 
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

            if($reg1 ['Ciudad'] != ""){
              $ciudad_labor = $reg1 ['Ciudad']; 
            }else{
              $ciudad_labor = "-";
            }

            $fecha_ingreso    = $reg1 ['Fecha_Ingreso']; 
            $fecha_retiro     = $reg1 ['Fecha_Retiro'];
            $motivo           = $reg1 ['Motivo'];

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
                  <td>'.$ciudad_labor.'</td>
                  <td>'.$fecha_ingreso.'</td>
                  <td>'.$fecha_retiro.'</td>
                  <td>'.$motivo.'</td>
                  <td>'.$liquidado.'</td>
              </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="13" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

  public static function hijospantalla($genero, $edad_inicial, $edad_final, $empresa) {
    
    $condicion = "WHERE Id_Parentesco = ".Yii::app()->params->parentesco_hijo.' AND P.Estado = 1';

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND P.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND P.Id_Empresa IN (".$empresa.")";
    }

    if($genero != null){
      $condicion .= " AND H.Id_Genero = ".$genero;
    }

    if($edad_inicial != null && $edad_final != null){
      $condicion .= " AND DATEDIFF (yy, H.Fecha_Nacimiento, GETDATE()) BETWEEN ".$edad_inicial." AND ".$edad_final;
    }

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
    FROM T_PR_NUCLEO_EMPLEADO H
    LEFT JOIN T_PR_EMPLEADO P ON H.Id_Empleado = P.Id_Empleado
    LEFT JOIN T_PR_DOMINIO TI ON P.Id_Tipo_Ident = TI.Id_Dominio 
    LEFT JOIN T_PR_DOMINIO D ON H.Id_Genero = D.Id_Dominio
    LEFT JOIN T_PR_EMPRESA E ON P.Id_Empresa = E.Id_Empresa
    ".$condicion."
    ORDER BY 4,3,6,7 ASC
    ";

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
            $empleado         = $reg1 ['Empleado'];
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

  public static function ausenciaspantalla($motivo_ausencia, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado) {
    
    $condicion = "WHERE 1 = 1";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }

    if($motivo_ausencia != null){
      $motivo_ausencia = implode(",", $motivo_ausencia);
      $condicion .= " AND A.Id_M_Ausencia IN (".$motivo_ausencia.")";
    }

    if($fecha_inicial != null && $fecha_final != null){
      $condicion .= " AND A.Fecha_Inicial BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
    }else{
      if($fecha_inicial != null && $fecha_final == null){
        $condicion .= " AND A.Fecha_Inicial = '".$fecha_inicial."'";
      } 
    }

    if($fecha_inicial_reg != null && $fecha_final_reg != null){
      $condicion .= " AND A.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_final_reg." 23:59:59'";
    }else{
      if($fecha_inicial_reg != null && $fecha_final_reg == null){
        $condicion .= " AND A.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_inicial_reg." 23:59:59'";
      }
    }

    if($id_empleado != null){
      $condicion .= " AND A.Id_Empleado = ".$id_empleado."";
    }

    $query ="
    SELECT
    A.Id_Ausencia,
    A.Fecha_Inicial
    FROM T_PR_AUSENCIA_EMPLEADO A
    INNER JOIN T_PR_CONTRATO_EMPLEADO HP ON A.Id_Empleado = HP.Id_Empleado
    INNER JOIN T_PR_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
    ".$condicion."
    GROUP BY A.Id_Ausencia, A.Fecha_Inicial
    ORDER BY 2 DESC
    ";


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

  public static function llamatencpantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado) {
    
    $condicion = "WHERE D.Id_Padre IN (".Yii::app()->params->motivos_d_llamado_atencion.")";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }

    if($motivo != null){
      $motivo = implode(",", $motivo);
      $condicion .= " AND EV.Id_M_Disciplinario IN (".$motivo.")";
    }

    if($fecha_inicial != null && $fecha_final != null){
      $condicion .= " AND EV.Fecha BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
    }else{
      if($fecha_inicial != null && $fecha_final == null){
        $condicion .= " AND EV.Fecha = '".$fecha_inicial."'";
      } 
    }

    if($fecha_inicial_reg != null && $fecha_final_reg != null){
      $condicion .= " AND EV.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_final_reg." 23:59:59'";
    }else{
      if($fecha_inicial_reg != null && $fecha_final_reg == null){
        $condicion .= " AND EV.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_inicial_reg." 23:59:59'";
      }
    }

    if($id_empleado != null){
      $condicion .= " AND EV.Id_Empleado = ".$id_empleado."";
    }

    $query ="
    SELECT
    EV.Id_Disciplinario,
    EV.Fecha
    FROM T_PR_DISCIPLINARIO_EMPLEADO EV
    INNER JOIN T_PR_CONTRATO_EMPLEADO HP ON EV.Id_Empleado = HP.Id_Empleado
    INNER JOIN T_PR_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
    LEFT JOIN T_PR_DOMINIO D ON EV.Id_M_Disciplinario = D.Id_Dominio
    ".$condicion."
    GROUP BY EV.Id_Disciplinario, EV.Fecha 
    ORDER BY 2 DESC
    ";

    $tabla = '
      <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Tipo identificación</th>
            <th>No. identificación</th>
            <th>Empleado</th>
            <th>Empresa</th>
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

            $modelodisciplinario = DisciplinarioEmpleado::model()->findByPk($reg1['Id_Disciplinario']);

            $tipo_ident       = $modelodisciplinario->idempleado->idtipoident->Dominio; 
            $ident            = $modelodisciplinario->idempleado->Identificacion;  
            $empleado         = UtilidadesEmpleado::nombreempleado($modelodisciplinario->Id_Empleado);
            $empresa          = $modelodisciplinario->idcontrato->idempresa->Descripcion;
            $motivo           = $modelodisciplinario->idmdisciplinario->Dominio; 
            $fecha_evento     = $modelodisciplinario->Fecha; 
            $persona_imp      = UtilidadesEmpleado::nombreempleado($modelodisciplinario->Id_Empleado_Imp);
            $orden            = $modelodisciplinario->Orden_No;

            if($modelodisciplinario->Observacion != ""){
              $observaciones = $modelodisciplinario->Observacion; 
            }else{
              $observaciones = "-";
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
                <td>'.$fecha_evento.'</td>
                <td>'.$persona_imp.'</td>
                <td>'.$orden.'</td>
                <td>'.$observaciones.'</td>
            </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="9" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

  public static function sancionespantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado) {
    
    $condicion = "WHERE D.Id_Padre IN (".Yii::app()->params->motivos_d_sancion.")";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }

    if($motivo != null){
      $motivo = implode(",", $motivo);
      $condicion .= " AND EV.Id_M_Disciplinario IN (".$motivo.")";
    }

    if($fecha_inicial != null && $fecha_final != null){
      $condicion .= " AND EV.Fecha BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
    }else{
      if($fecha_inicial != null && $fecha_final == null){
        $condicion .= " AND EV.Fecha = '".$fecha_inicial."'";
      } 
    }

    if($fecha_inicial_reg != null && $fecha_final_reg != null){
      $condicion .= " AND EV.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_final_reg." 23:59:59'";
    }else{
      if($fecha_inicial_reg != null && $fecha_final_reg == null){
        $condicion .= " AND EV.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_inicial_reg." 23:59:59'";
      }
    }

    if($id_empleado != null){
      $condicion .= " AND EV.Id_Empleado = ".$id_empleado."";
    }

    $query ="
    SELECT
    EV.Id_Disciplinario,
    EV.Fecha
    FROM T_PR_DISCIPLINARIO_EMPLEADO EV
    INNER JOIN T_PR_CONTRATO_EMPLEADO HP ON EV.Id_Empleado = HP.Id_Empleado
    INNER JOIN T_PR_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
    LEFT JOIN T_PR_DOMINIO D ON EV.Id_M_Disciplinario = D.Id_Dominio
    ".$condicion."
    GROUP BY EV.Id_Disciplinario, EV.Fecha 
    ORDER BY 2 DESC
    ";

    $tabla = '
      <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Tipo identificación</th>
            <th>No. identificación</th>
            <th>Empleado</th>
            <th>Empresa</th>
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

            $modelodisciplinario = DisciplinarioEmpleado::model()->findByPk($reg1['Id_Disciplinario']);

            $tipo_ident       = $modelodisciplinario->idempleado->idtipoident->Dominio; 
            $ident            = $modelodisciplinario->idempleado->Identificacion;  
            $empleado         = UtilidadesEmpleado::nombreempleado($modelodisciplinario->Id_Empleado);
            $empresa          = $modelodisciplinario->idcontrato->idempresa->Descripcion;
            $motivo           = $modelodisciplinario->idmdisciplinario->Dominio; 
            $fecha_evento     = $modelodisciplinario->Fecha; 
            $persona_imp      = UtilidadesEmpleado::nombreempleado($modelodisciplinario->Id_Empleado_Imp);
            $orden            = $modelodisciplinario->Orden_No;

            if($modelodisciplinario->Observacion != ""){
              $observaciones = $modelodisciplinario->Observacion; 
            }else{
              $observaciones = "-";
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
                <td>'.$fecha_evento.'</td>
                <td>'.$persona_imp.'</td>
                <td>'.$orden.'</td>
                <td>'.$observaciones.'</td>
            </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="9" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

  public static function comparendospantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado) {
    
    $condicion = "WHERE D.Id_Padre IN (".Yii::app()->params->motivos_d_comparendo.")";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND HP.Id_Empresa IN (".$empresa.")";
    }

    if($motivo != null){
      $motivo = implode(",", $motivo);
      $condicion .= " AND EV.Id_M_Disciplinario IN (".$motivo.")";
    }

    if($fecha_inicial != null && $fecha_final != null){
      $condicion .= " AND EV.Fecha BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
    }else{
      if($fecha_inicial != null && $fecha_final == null){
        $condicion .= " AND EV.Fecha = '".$fecha_inicial."'";
      } 
    }

    if($fecha_inicial_reg != null && $fecha_final_reg != null){
      $condicion .= " AND EV.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_final_reg." 23:59:59'";
    }else{
      if($fecha_inicial_reg != null && $fecha_final_reg == null){
        $condicion .= " AND EV.Fecha_Creacion BETWEEN '".$fecha_inicial_reg." 00:00:00' AND '".$fecha_inicial_reg." 23:59:59'";
      }
    }

    if($id_empleado != null){
      $condicion .= " AND EV.Id_Empleado = ".$id_empleado."";
    }

    $query ="
    SELECT
    EV.Id_Disciplinario,
    EV.Fecha
    FROM T_PR_DISCIPLINARIO_EMPLEADO EV
    INNER JOIN T_PR_CONTRATO_EMPLEADO HP ON EV.Id_Empleado = HP.Id_Empleado
    INNER JOIN T_PR_EMPRESA E ON HP.Id_Empresa = E.Id_Empresa
    LEFT JOIN T_PR_DOMINIO D ON EV.Id_M_Disciplinario = D.Id_Dominio
    ".$condicion."
    GROUP BY EV.Id_Disciplinario, EV.Fecha 
    ORDER BY 2 DESC
    ";

    $tabla = '
      <table class="table table-sm table-hover">
          <thead>
            <tr>
            <th>Tipo identificación</th>
            <th>No. identificación</th>
            <th>Empleado</th>
            <th>Empresa</th>
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

            $modelodisciplinario = DisciplinarioEmpleado::model()->findByPk($reg1['Id_Disciplinario']);

            $tipo_ident       = $modelodisciplinario->idempleado->idtipoident->Dominio; 
            $ident            = $modelodisciplinario->idempleado->Identificacion;  
            $empleado         = UtilidadesEmpleado::nombreempleado($modelodisciplinario->Id_Empleado);
            $empresa          = $modelodisciplinario->idcontrato->idempresa->Descripcion;
            $motivo           = $modelodisciplinario->idmdisciplinario->Dominio; 
            $fecha_evento     = $modelodisciplinario->Fecha; 
            $persona_imp      = UtilidadesEmpleado::nombreempleado($modelodisciplinario->Id_Empleado_Imp);
            $orden            = $modelodisciplinario->Orden_No;

            if($modelodisciplinario->Observacion != ""){
              $observaciones = $modelodisciplinario->Observacion; 
            }else{
              $observaciones = "-";
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
                <td>'.$fecha_evento.'</td>
                <td>'.$persona_imp.'</td>
                <td>'.$orden.'</td>
                <td>'.$observaciones.'</td>
            </tr>';

            $i++;

          }
        }else{
          $tabla .= ' 
          <tr><td colspan="9" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

  public static function tallajeempleadospantalla($fecha_inicial_cont, $fecha_final_cont, $empresa) {
    
    $condicion = "WHERE CE.Id_M_Retiro IS NULL";

    if($empresa != null){
      $empresa = implode(",", $empresa);
      $condicion .= " AND CE.Id_Empresa IN (".$empresa.")";
    }else{
      $array_empresas = (Yii::app()->user->getState('array_empresas'));
      $empresa = implode(",",$array_empresas);
      $condicion .= " AND CE.Id_Empresa IN (".$empresa.")";
    }

    if($fecha_inicial_cont != null && $fecha_final_cont != null){
      $condicion .= " AND CE.Fecha_Ingreso BETWEEN '".$fecha_inicial_cont."' AND '".$fecha_final_cont."'";
    }else{
      if($fecha_inicial_cont != null && $fecha_final_cont == null){
        $condicion .= " AND CE.Fecha_Ingreso = '".$fecha_inicial_cont."'";
      }
    }

    $query = "
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
    FROM T_PR_EMPLEADO E
    LEFT JOIN T_PR_CONTRATO_EMPLEADO CE ON CE.Id_Empleado = E.Id_Empleado 
    LEFT JOIN T_PR_EMPRESA EM ON EM.Id_Empresa = CE.Id_Empresa 
    LEFT JOIN T_PR_UNIDAD_GERENCIA UN ON UN.Id_Unidad_Gerencia = CE.Id_Unidad_Gerencia 
    LEFT JOIN T_PR_AREA A ON A.Id_Area = CE.Id_Area 
    LEFT JOIN T_PR_SUBAREA S ON S.Id_Subarea = CE.Id_Subarea 
    LEFT JOIN T_PR_CARGO C ON C.Id_Cargo = CE.Id_Cargo 
    LEFT JOIN T_PR_EMPLEADO EMP ON EMP.Id_Empleado = CE.Id_Empleado 
    LEFT JOIN T_PR_DOMINIO GEN ON GEN.Id_Dominio = EMP.Id_Genero 
    LEFT JOIN T_PR_DOMINIO TI ON TI.Id_Dominio = EMP.Id_Tipo_Ident 
    WHERE CE.Id_M_Retiro IS NULL 
    AND CE.Id_Empresa IN (6,7,8,4,5,1,2,3) ORDER BY 5,7,8,9,3
    ";

    $tabla = '
    <table class="table table-sm table-hover">
            <thead>
              <tr>
              <th>Tipo identificación</th>
              <th>No. identificación</th>
              <th>Empleado</th>
              <th>Género</th>
              <th>Empresa</th>
              <th>Fecha de ingreso</th>
              <th>Unidad de gerencia</th>
              <th>Área</th>
              <th>Subárea</th>
              <th>Cargo</th>
              <th>Camisa</th>
              <th>Pantalón</th>
              <th>Zapatos</th>
              <th>Overol</th>
              <th>Bata</th>
              <th>Elementos asignados</th>
              </tr>
            </thead>
        <tbody>';

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
        FROM T_PR_ELEMENTO_EMPLEADO EE
        LEFT JOIN T_PR_AREA_ELEMENTO AE ON AE.Id_A_elemento = EE.Id_A_Elemento 
        LEFT JOIN T_PR_ELEMENTO E ON E.Id_Elemento = AE.Id_Elemento 
        WHERE EE.Id_Contrato = ".$Id_Contrato." AND EE.Id_A_Elemento IN (SELECT Id_A_Elemento FROM T_PR_AREA_ELEMENTO_DOT) 
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

    if(!empty($array)){

      $i = 1; 

      foreach ($array as $registro) {
        
        $Tipo_Ident = ($registro['info']['Tipo_Ident'] == "") ? "-" : $registro['info']['Tipo_Ident'];
        $Identificacion = $registro['info']['Identificacion'];
        $Empleado = $registro['info']['Empleado'];
        $Genero = ($registro['info']['Genero'] == "") ? "-" : $registro['info']['Genero'];
        $Empresa = $registro['info']['Empresa'];
        $Fecha_Ingreso = ($registro['info']['Fecha_Ingreso'] == "") ? "-" : $registro['info']['Fecha_Ingreso'];
        $UN = ($registro['info']['UN'] == "") ? "-" : $registro['info']['UN'];
        $Area = ($registro['info']['Area'] == "") ? "-" : $registro['info']['Area'];
        $Subarea = ($registro['info']['Subarea'] == "") ? "-" : $registro['info']['Subarea'];
        $Cargo = ($registro['info']['Genero'] == "") ? "-" : $registro['info']['Genero'];
        $Talla_Camisa = ($registro['info']['Talla_Camisa'] == "") ? "-" : $registro['info']['Talla_Camisa'];
        $Talla_Pantalon = ($registro['info']['Talla_Pantalon'] == "") ? "-" : $registro['info']['Talla_Pantalon'];
        $Talla_Zapato = ($registro['info']['Talla_Zapato'] == "") ? "-" : $registro['info']['Talla_Zapato'];
        $Talla_Overol = ($registro['info']['Talla_Overol'] == "") ? "-" : $registro['info']['Talla_Overol'];
        $Talla_Bata = ($registro['info']['Talla_Bata'] == "") ? "-" : $registro['info']['Talla_Bata'];
        $Elem = ($registro['info']['elementos'] == "") ? "-" : $registro['info']['elementos'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Tipo_Ident.'</td>
              <td>'.$Identificacion.'</td>
              <td>'.$Empleado.'</td>
              <td>'.$Genero.'</td>
              <td>'.$Empresa.'</td>
              <td>'.$Fecha_Ingreso.'</td>
              <td>'.$UN.'</td>
              <td>'.$Area.'</td>
              <td>'.$Subarea.'</td>
              <td>'.$Cargo.'</td>
              <td>'.$Talla_Camisa.'</td>
              <td>'.$Talla_Pantalon.'</td>
              <td>'.$Talla_Zapato.'</td>
              <td>'.$Talla_Overol.'</td>
              <td>'.$Talla_Bata.'</td>
              <td>'.$Elem.'</td>
        </tr>';

        $i++;


      }
    }else{
      $tabla .= ' 
          <tr><td colspan="16" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
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

  public static function obscuentapantalla($id_empleado) {

    $criteria=new CDbCriteria;
    $criteria->condition = "Id_Empleado = :Id_Empleado";
    $criteria->order = "Fecha_Actualizacion DESC";
    $criteria->params = array (':Id_Empleado' => $id_empleado);

    $model_cuentas = Cuenta::model()->findAll($criteria);

    $tabla = '
      <table class="table table-sm table-hover">
        <thead>
          <tr>
          <th>Cuenta de correo</th>
          <th>Observaciones</th>
          <th>Estado</th>
          <th>Usuario que actualizó</th>
          <th>Fecha de actualización</th>
          </tr>
        </thead>
        <tbody>
          ';

    if(!empty($model_cuentas)){
      
      $i = 1; 

      foreach ($model_cuentas as $reg) {
        
        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        if($reg->Observaciones == "") { $o = "-"; } else{ $o = $reg->Observaciones; }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$reg->Cuenta_Correo.'</td>
              <td>'.$o.'</td>
              <td>'.UtilidadesEmpleado::estadoactualempleado($reg->Id_Empleado).'</td>
              <td>'.$reg->idusuarioact->Usuario.'</td>
              <td>'.UtilidadesVarias::textofechahora($reg->Fecha_Actualizacion).'</td>
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

  public static function cuentaspantalla($dominio, $estado) {
    
    $condicion = "WHERE c.Clasificacion = 314";

    if($dominio != ""){
      
      $condicion .= " AND c.Dominio = ".$dominio;

    }

    if($estado != null){
  
      $condicion .= " AND c.Estado = ".$estado;

    }

    $query ="
    SELECT
    CONCAT(c.Cuenta_Usuario, '@', dw.Dominio) AS Cuenta,
    CASE
    WHEN c.Observaciones IS NULL THEN '-'
    WHEN c.Observaciones IS NOT NULL THEN c.Observaciones
    Else '-'
    END AS Observaciones,
    est.Dominio AS Estado_Cuenta,
    CASE
    WHEN e.Id_Empleado IS NULL THEN '-'
    WHEN e.Id_Empleado IS NOT NULL THEN CONCAT (e.Nombre, ' ', e.Apellido, ' (',ti.Dominio,' ',e.Identificacion,')')
    Else '-'
    END AS Empleado,
    CASE
    WHEN e.Estado = 1 THEN 'ACTIVO'
    WHEN e.Estado = 0 THEN 'INACTIVO'
    Else '-'
    END AS Estado_Empleado
    FROM T_PR_CUENTA c
    LEFT JOIN T_PR_CUENTA_EMPLEADO ce ON c.Id_Cuenta = ce.Id_Cuenta AND ce.Estado = 1
    LEFT JOIN T_PR_EMPLEADO e ON ce.Id_Empleado = e.Id_Empleado
    LEFT JOIN T_PR_DOMINIO ti ON e.Id_Tipo_Ident = ti.Id_Dominio
    LEFT JOIN T_PR_DOMINIO est ON c.Estado = est.Id_Dominio
    LEFT JOIN T_PR_DOMINIO_WEB dw ON c.Dominio = dw.Id_Dominio_Web
    ".$condicion."
    ORDER BY dw.Dominio, c.Cuenta_Usuario 
    ";

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Correo</th>
                <th>Notas</th>
                <th>Estado de Correo</th>
                <th>Empleado</th>
                <th>Estado de empleado</th>
                </tr>
              </thead>
          <tbody>';

        $query1 = Yii::app()->db->createCommand($query)->queryAll();

        $i = 1; 

        if(!empty($query1)){
          foreach ($query1 as $reg1) {

            $Cuenta          = $reg1 ['Cuenta']; 
            $Notas           = $reg1 ['Observaciones']; 
            $Estado_Cuenta   = $reg1 ['Estado_Cuenta']; 
            $Empleado        = $reg1 ['Empleado']; 
            $Estado_Empleado = $reg1 ['Estado_Empleado']; 

            if ($i % 2 == 0){
              $clase = 'odd'; 
            }else{
              $clase = 'even'; 
            }

            $tabla .= '    
            <tr class="'.$clase.'">
                  <td>'.$Cuenta.'</td>
                  <td>'.$Notas.'</td>
                  <td>'.$Estado_Cuenta.'</td>
                  <td>'.$Empleado.'</td>
                  <td>'.$Estado_Empleado.'</td>
              </tr>';

            $i++;
          }
        }else{
          $tabla .= ' 
          <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
          ';
        }

        $tabla .= '  </tbody>
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

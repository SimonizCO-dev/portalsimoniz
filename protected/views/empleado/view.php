<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */

?>

<script type="text/javascript">
    
$(function() {

    //se calcula la edad al cargar dom
    var fn = '<?php echo $model->Fecha_Nacimiento; ?>';

    if(fn != ""){
        $("#edad_emp").html(calcularEdad(fn));
    }

    var ciudad_res = parseInt(<?php echo $model->Id_Ciudad_Residencia; ?>);
    var ciu_bog = parseInt(<?php echo Yii::app()->params->lugar_res_bogota; ?>);

    if(ciudad_res != ""){
        if(parseInt(ciudad_res) == ciu_bog){
            $("#loc_res").show();
        }else{
            $("#loc_res").hide();
        }
    }

    var genero = parseInt(<?php echo $model->Id_Genero; ?>);
    var gen_fem = parseInt(<?php echo Yii::app()->params->genero_fem; ?>);

    if(genero != ""){
        if(parseInt(genero) == gen_fem){
            $("#ges").show();
        }else{
            $("#ges").hide();
        }
    }

    var alergia =  parseInt(<?php echo $model->Alergia; ?>);

    if(alergia != ""){
        if(parseInt(alergia) == 1){
            $("#obs_alerg").show();
        }else{
            $("#obs_alerg").hide();
        }
    }

    var estado =  parseInt(<?php echo $model->Estado; ?>);

    if(estado != ""){
        if(parseInt(estado) == 1){
            $("#info_act").show();
        }else{
            $("#info_act").hide();
        }
    }

});

function calcularEdad(fecha) {
    var hoy = new Date();
    var cumpleanos = new Date(fecha);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }

    return edad+' Años';
} 

</script>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Resumen de empleado</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <?php if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_nomina) { ?>

            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Acciones
            </button>
            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/update&id='.$model->Id_Empleado; ?>">Actualizar datos de empleado</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=nucleoEmpleado/create&e='.$model->Id_Empleado; ?>">Registro de pariente</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=formacionEmpleado/create&e='.$model->Id_Empleado; ?>">Registro de estudio</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=evaluacionEmpleado/create&e='.$model->Id_Empleado; ?>">Registro de evaluación</a></li>
            <?php if ($asociacion_elementos == 0 && $upd_th == true) { ?>  
                <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=contratoEmpleado/create&e='.$model->Id_Empleado; ?>">Registro de contrato</a></li>
            <?php } else { ?>
                <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=turnoEmpleado/create&e='.$model->Id_Empleado; ?>">Registro de turno</a></li>
                <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ausenciaEmpleado/create&e='.$model->Id_Empleado; ?>">Registro de ausencia</a></li>
                <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=disciplinarioEmpleado/create&e='.$model->Id_Empleado.'&opc=1'; ?>">Registro llamado de atención</a></li>
                <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=disciplinarioEmpleado/create&e='.$model->Id_Empleado.'&opc=2'; ?>">Registro de sanción</a></li>
                <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=disciplinarioEmpleado/create&e='.$model->Id_Empleado.'&opc=3'; ?>">Registro de comparendo</a></li>
                <?php if ($ter_cont == 1 && $upd_th == true) { ?>  
                    <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=contratoEmpleado/terminacion&e='.$model->Id_Empleado; ?>">Terminación de contrato</a></li>
                <?php } ?>
            <?php } ?>

            </ul>

        <?php } if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_sst) { ?>

            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Acciones
            </button>
            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">

            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=disciplinarioEmpleado/create&e='.$model->Id_Empleado.'&opc=3'; ?>">Registro de comparendo</a></li>
            </ul>

        <?php } ?>
    </div>
</div>

<?php if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_nin || Yii::app()->user->getState('niv_det_vis_emp') == ""){ ?>

<!----------SIN VISTA - NINGUNA---------->

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene permisos sobre este modulo.
</div> 

<?php } if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_info_basica){ ?> 

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>ID</label>
            <?php echo '<p>'.$model->Id_Empleado.'</p>';?>
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Tipo de identificación</label>
            <?php echo '<p>'.$model->idtipoident->Dominio.'</p>';?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label># Identificación</label>
            <?php echo '<p>'.$model->Identificacion.'</p>';?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Apellidos</label>
            <?php echo '<p>'.$model->Apellido.'</p>';?>
        </div>
    </div>
</div>
<div class="row">
     <div class="col-sm-4">
        <div class="form-group">
            <label>Nombres</label>
            <?php echo '<p>'.$model->Nombre.'</p>';?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <?php if($model->Fecha_Nacimiento == "") { $Fecha_Nacimiento = "SIN ASIGNAR"; } else { $Fecha_Nacimiento = UtilidadesVarias::textofecha($model->Fecha_Nacimiento); } ?>
            <?php echo '<p>'.$Fecha_Nacimiento.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Edad</label>
            <?php echo '<p id="edad_emp"></p>';?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Dpto - municipio nacimiento</label>
            <?php if($model->Id_Ciudad_Nacimiento == "") { $Ciudad_N = "SIN ASIGNAR"; } else { $Ciudad_N = $model->idciudadn->Ciudad; } ?>
            <?php echo '<p>'.$Ciudad_N.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Dirección</label>
            <?php if($model->Direccion == "") { $Direccion = "SIN ASIGNAR"; } else { $Direccion = $model->Direccion; } ?>
            <?php echo '<p>'.$Direccion.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Teléfono(s)</label>
            <?php if($model->Telefono == "") { $Telefono = "SIN ASIGNAR"; } else { $Telefono = $model->Telefono; } ?>
            <?php echo '<p>'.$Telefono.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>E-mail</label>
            <?php if($model->Correo == "") { $Correo = "SIN ASIGNAR"; } else { $Correo = $model->Correo; } ?>
            <?php echo '<p>'.$Correo.'</p>'; ?>
        </div>
    </div>
</div>
<h5 class="mt-3 mb-3">Datos sociodemográficos</h5>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Grado de escolaridad</label>
            <?php if($model->Id_Grado_Esc == "") { $Id_Grado_Esc = "SIN ASIGNAR"; } else { $Id_Grado_Esc = $model->idgradoesc->Dominio; } ?>
            <?php echo '<p>'.$Id_Grado_Esc.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Estado civil</label>
            <?php if($model->Id_Estado_Civil == "") { $Estado_civil = "SIN ASIGNAR"; } else { $Estado_civil = $model->idestadocivil->Dominio; } ?>
            <?php echo '<p>'.$Estado_civil.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Raza</label>
            <?php if($model->Id_Raza == "") { $Raza = "SIN ASIGNAR"; } else { $Raza = $model->idraza->Dominio; } ?>
            <?php echo '<p>'.$Raza.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Composición familiar</label>
            <?php if($model->Id_Com_Fam == "") { $Com_Fam = "SIN ASIGNAR"; } else { $Com_Fam = UtilidadesEmpleado::compfamempleado($model->Id_Com_Fam); } ?>
            <?php echo '<p>'.$Com_Fam.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Ocupación</label>
            <?php if($model->Id_Ocupacion == "") { $Ocupacion = "SIN ASIGNAR"; } else { $Ocupacion = UtilidadesEmpleado::ocupmempleado($model->Id_Ocupacion); } ?>
            <?php echo '<p>'.$Ocupacion.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Dpto - municipio residencia</label>
            <?php if($model->Id_Ciudad_Residencia == "") { $Ciudad_R = "SIN ASIGNAR"; } else { $Ciudad_R = $model->idciudadr->Ciudad; } ?>
            <?php echo '<p>'.$Ciudad_R.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4" id="loc_res" style="display: none; ">
        <div class="form-group">
            <label>Localidad de residencia</label>
            <?php if($model->Id_Localidad_Residencia == "") { $Loc_Res = "SIN ASIGNAR"; } else { $Loc_Res = $model->idlocres->Dominio; } ?>
            <?php echo '<p>'.$Loc_Res.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>RH</label>
            <?php if($model->Id_Rh == "") { $Rh = "SIN ASIGNAR"; } else { $Rh = $model->idrh->Dominio; } ?>
            <?php echo '<p>'.$Rh.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Género</label>
            <?php if($model->Id_Genero == "") { $Genero = "SIN ASIGNAR"; } else { $Genero = $model->idgenero->Dominio; } ?>
            <?php echo '<p>'.$Genero.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4" id="ges" style="display: none;">
        <div class="form-group">
            <label>Gestante ?</label>
            <?php if($model->Es_Gestante == "") { $Es_Gestante = "SIN ASIGNAR"; } else { $Es_Gestante = UtilidadesVarias::textoestado2($model->Es_Gestante); } ?>
            <?php echo '<p>'.$Es_Gestante.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Estrato socioeconómico</label>
            <?php if($model->Id_Estrato == "") { $Estrato = "SIN ASIGNAR"; } else { $Estrato = $model->idestrato->Dominio; } ?>
            <?php echo '<p>'.$Estrato.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Persona de contacto</label>
            <?php if($model->Persona_Contacto == "") { $Persona_Contacto = "SIN ASIGNAR"; } else { $Persona_Contacto = $model->Persona_Contacto; } ?>
            <?php echo '<p>'.$Persona_Contacto.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Teléfono persona de contacto</label>
            <?php if($model->Tel_Persona_Contacto == "") { $Tel_Persona_Contacto = "SIN ASIGNAR"; } else { $Tel_Persona_Contacto = $model->Tel_Persona_Contacto; } ?>
            <?php echo '<p>'.$Tel_Persona_Contacto.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Parentesco contacto</label>
            <?php if($model->Id_Parentesco_Persona_Contacto == "") { $Parentesco_Persona_Contacto = "SIN ASIGNAR"; } else { $Parentesco_Persona_Contacto = $model->idparentpercont->Dominio; } ?>
            <?php echo '<p>'.$Parentesco_Persona_Contacto.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Fuma ?</label>
            <?php if($model->Fuma == "") { $Fuma = "SIN ASIGNAR"; } else { $Fuma = UtilidadesVarias::textoestado2($model->Fuma); } ?>
            <?php echo '<p>'.$Fuma.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Alergia ?</label>
            <?php if($model->Alergia == "") { $Alergia = "SIN ASIGNAR"; } else { $Alergia = UtilidadesVarias::textoestado2($model->Alergia); } ?>
            <?php echo '<p>'.$Alergia.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row" id="obs_alerg" style="display: none;"> 
    <div class="col-sm-4">
        <div class="form-group">
            <label>Observaciones (alergia)</label>
            <?php if($model->Observaciones == "") { $Observaciones = "SIN ASIGNAR"; } else { $Observaciones = $model->Observaciones; } ?>
            <?php echo '<p>'.$Observaciones.'</p>'; ?>
        </div>
    </div>       
</div>
<h5 class="mt-3 mb-3">Otros datos</h5>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Dpto - municipio labor</label>
            <?php if($model->Id_Ciudad_Labor == "") { $Ciudad_L = "SIN ASIGNAR"; } else { $Ciudad_L = $model->idciudadl->Ciudad; } ?>
            <?php echo '<p>'.$Ciudad_L.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Regional de labor</label>
            <?php if($model->Id_Regional_Labor == "") { $Regional_L = "SIN ASIGNAR"; } else { $Regional_L = $model->idregional->Regional; } ?>
            <?php echo '<p>'.$Regional_L.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Eps</label>
            <?php if($model->Id_Eps == "") { $Eps = "SIN ASIGNAR"; } else { $Eps = $model->ideps->Dominio; } ?>
            <?php echo '<p>'.$Eps.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Caja de compensación</label>
            <?php if($model->Id_Caja_C == "") { $Cc = "SIN ASIGNAR"; } else { $Cc = $model->idcajac->Dominio; } ?>
            <?php echo '<p>'.$Cc.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Fondo de pensiones</label>
            <?php if($model->Id_Fondo_P == "") { $Fondo_p = "SIN ASIGNAR"; } else { $Fondo_p = $model->idfondop->Dominio; } ?>
            <?php echo '<p>'.$Fondo_p.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Fondo de cesantías</label>
            <?php if($model->Id_Fondo_C == "") { $Fondo_c = "SIN ASIGNAR"; } else { $Fondo_c = $model->idfondoc->Dominio; } ?>
            <?php echo '<p>'.$Fondo_c.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Arl</label>
            <?php if($model->Id_Arl == "") { $Arl = "SIN ASIGNAR"; } else { $Arl = $model->idarl->Dominio; } ?>
            <?php echo '<p>'.$Arl.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Banco</label>
            <?php if($model->Id_Banco == "") { $Banco = "SIN ASIGNAR"; } else { $Banco = $model->idbanco->Dominio; } ?>
            <?php echo '<p>'.$Banco.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Tipo de cuenta</label>
            <?php if($model->Id_T_Cuenta == "") { $Tipo_Cuenta = "SIN ASIGNAR"; } else { $Tipo_Cuenta = $model->idtcuenta->Dominio; } ?>
            <?php echo '<p>'.$Tipo_Cuenta.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Número de cuenta</label>
            <?php if($model->Num_Cuenta == "") { $Num_Cuenta = "SIN ASIGNAR"; } else { $Num_Cuenta = $model->Num_Cuenta; } ?>
            <?php echo '<p>'.$Num_Cuenta.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Talla camisa</label>
            <?php if($model->Talla_Camisa == "") { $Talla_Camisa = "SIN ASIGNAR"; } else { $Talla_Camisa = $model->Talla_Camisa; } ?>
            <?php echo '<p>'.$Talla_Camisa.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Talla pantalón</label>
            <?php if($model->Talla_Pantalon == "") { $Talla_Pantalon = "SIN ASIGNAR"; } else { $Talla_Pantalon = $model->Talla_Pantalon; } ?>
            <?php echo '<p>'.$Talla_Pantalon.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Talla zapatos</label>
            <?php if($model->Talla_Zapato == "") { $Talla_Zapato = "SIN ASIGNAR"; } else { $Talla_Zapato = $model->Talla_Zapato; } ?>
            <?php echo '<p>'.$Talla_Zapato.'</p>'; ?>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Talla overol</label>
            <?php if($model->Talla_Overol == "") { $Talla_Overol = "SIN ASIGNAR"; } else { $Talla_Overol = $model->Talla_Overol; } ?>
            <?php echo '<p>'.$Talla_Overol.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Talla bata</label>
            <?php if($model->Talla_Bata == "") { $Talla_Bata = "SIN ASIGNAR"; } else { $Talla_Bata = $model->Talla_Bata; } ?>
            <?php echo '<p>'.$Talla_Bata.'</p>'; ?>
        </div>
    </div>    
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Estado</label>
            <?php echo '<p>'.UtilidadesEmpleado::estadoactualempleado($model->Id_Empleado).'</p>'; ?>
        </div>
    </div>
</div>
<div id="info_act" style="display: none;">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Empresa</label>
                <?php echo '<p>'.UtilidadesEmpleado::empresaactualempleado($model->Id_Empleado).'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Área</label>
                <?php echo '<p>'.UtilidadesEmpleado::unidadgerenciaactualempleado($model->Id_Empleado).'</p>';?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Área</label>
                <?php echo '<p>'.UtilidadesEmpleado::areaactualempleado($model->Id_Empleado).'</p>';?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Subárea</label>
                <?php echo '<p>'.UtilidadesEmpleado::subareaactualempleado($model->Id_Empleado).'</p>';?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Cargo</label>
                <?php echo '<p>'.UtilidadesEmpleado::cargoactualempleado($model->Id_Empleado).'</p>';?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label>Centro de costo</label>
                <?php echo '<p>'.UtilidadesEmpleado::centrocostoactualempleado($model->Id_Empleado).'</p>';?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Usuario que creo</label>
            <?php echo '<p>'.$model->idusuariocre->Usuario.'</p>';?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Fecha de creación</label>
            <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>';?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Ultimo usuario que actualizó</label>
            <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>';?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Ultima fecha de actualización</label>
            <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>';?>
        </div>
    </div>
</div>

<?php } if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_sst){ ?>

<!----------VISTA SST---------->

<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contrato_activo">Contrato Activo</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contratos_anteriores">Contratos anteriores</a></li>
</ul>

<!-- Tab panes -->
 <div class="tab-content">
    <div id="info" class="tab-pane active"><br>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>ID</label>
                    <?php echo '<p>'.$model->Id_Empleado.'</p>';?>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Tipo de identificación</label>
                    <?php echo '<p>'.$model->idtipoident->Dominio.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label># Identificación</label>
                    <?php echo '<p>'.$model->Identificacion.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Apellidos</label>
                    <?php echo '<p>'.$model->Apellido.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Nombres</label>
                    <?php echo '<p>'.$model->Nombre.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha de nacimiento</label>
                    <?php if($model->Fecha_Nacimiento == "") { $Fecha_Nacimiento = "SIN ASIGNAR"; } else { $Fecha_Nacimiento = UtilidadesVarias::textofecha($model->Fecha_Nacimiento); } ?>
                    <?php echo '<p>'.$Fecha_Nacimiento.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Edad</label>
                    <?php echo '<p id="edad_emp"></p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio nacimiento</label>
                    <?php if($model->Id_Ciudad_Nacimiento == "") { $Ciudad_N = "SIN ASIGNAR"; } else { $Ciudad_N = $model->idciudadn->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_N.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dirección</label>
                    <?php if($model->Direccion == "") { $Direccion = "SIN ASIGNAR"; } else { $Direccion = $model->Direccion; } ?>
                    <?php echo '<p>'.$Direccion.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Teléfono(s)</label>
                    <?php if($model->Telefono == "") { $Telefono = "SIN ASIGNAR"; } else { $Telefono = $model->Telefono; } ?>
                    <?php echo '<p>'.$Telefono.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>E-mail</label>
                    <?php if($model->Correo == "") { $Correo = "SIN ASIGNAR"; } else { $Correo = $model->Correo; } ?>
                    <?php echo '<p>'.$Correo.'</p>'; ?>
                </div>
            </div>
        </div>
        <h5 class="mt-3 mb-3">Datos sociodemográficos</h5>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Grado de escolaridad</label>
                    <?php if($model->Id_Grado_Esc == "") { $Id_Grado_Esc = "SIN ASIGNAR"; } else { $Id_Grado_Esc = $model->idgradoesc->Dominio; } ?>
                    <?php echo '<p>'.$Id_Grado_Esc.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estado civil</label>
                    <?php if($model->Id_Estado_Civil == "") { $Estado_civil = "SIN ASIGNAR"; } else { $Estado_civil = $model->idestadocivil->Dominio; } ?>
                    <?php echo '<p>'.$Estado_civil.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Raza</label>
                    <?php if($model->Id_Raza == "") { $Raza = "SIN ASIGNAR"; } else { $Raza = $model->idraza->Dominio; } ?>
                    <?php echo '<p>'.$Raza.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Composición familiar</label>
                    <?php if($model->Id_Com_Fam == "") { $Com_Fam = "SIN ASIGNAR"; } else { $Com_Fam = UtilidadesEmpleado::compfamempleado($model->Id_Com_Fam); } ?>
                    <?php echo '<p>'.$Com_Fam.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ocupación</label>
                    <?php if($model->Id_Ocupacion == "") { $Ocupacion = "SIN ASIGNAR"; } else { $Ocupacion = UtilidadesEmpleado::ocupmempleado($model->Id_Ocupacion); } ?>
                    <?php echo '<p>'.$Ocupacion.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio residencia</label>
                    <?php if($model->Id_Ciudad_Residencia == "") { $Ciudad_R = "SIN ASIGNAR"; } else { $Ciudad_R = $model->idciudadr->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_R.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4" id="loc_res" style="display: none; ">
                <div class="form-group">
                    <label>Localidad de residencia</label>
                    <?php if($model->Id_Localidad_Residencia == "") { $Loc_Res = "SIN ASIGNAR"; } else { $Loc_Res = $model->idlocres->Dominio; } ?>
                    <?php echo '<p>'.$Loc_Res.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>RH</label>
                    <?php if($model->Id_Rh == "") { $Rh = "SIN ASIGNAR"; } else { $Rh = $model->idrh->Dominio; } ?>
                    <?php echo '<p>'.$Rh.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Género</label>
                    <?php if($model->Id_Genero == "") { $Genero = "SIN ASIGNAR"; } else { $Genero = $model->idgenero->Dominio; } ?>
                    <?php echo '<p>'.$Genero.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4" id="ges" style="display: none;">
                <div class="form-group">
                    <label>Gestante ?</label>
                    <?php if($model->Es_Gestante == "") { $Es_Gestante = "SIN ASIGNAR"; } else { $Es_Gestante = UtilidadesVarias::textoestado2($model->Es_Gestante); } ?>
                    <?php echo '<p>'.$Es_Gestante.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estrato socioeconómico</label>
                    <?php if($model->Id_Estrato == "") { $Estrato = "SIN ASIGNAR"; } else { $Estrato = $model->idestrato->Dominio; } ?>
                    <?php echo '<p>'.$Estrato.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Persona de contacto</label>
                    <?php if($model->Persona_Contacto == "") { $Persona_Contacto = "SIN ASIGNAR"; } else { $Persona_Contacto = $model->Persona_Contacto; } ?>
                    <?php echo '<p>'.$Persona_Contacto.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Teléfono persona de contacto</label>
                    <?php if($model->Tel_Persona_Contacto == "") { $Tel_Persona_Contacto = "SIN ASIGNAR"; } else { $Tel_Persona_Contacto = $model->Tel_Persona_Contacto; } ?>
                    <?php echo '<p>'.$Tel_Persona_Contacto.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Parentesco contacto</label>
                    <?php if($model->Id_Parentesco_Persona_Contacto == "") { $Parentesco_Persona_Contacto = "SIN ASIGNAR"; } else { $Parentesco_Persona_Contacto = $model->idparentpercont->Dominio; } ?>
                    <?php echo '<p>'.$Parentesco_Persona_Contacto.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fuma ?</label>
                    <?php if($model->Fuma == "") { $Fuma = "SIN ASIGNAR"; } else { $Fuma = UtilidadesVarias::textoestado2($model->Fuma); } ?>
                    <?php echo '<p>'.$Fuma.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Alergia ?</label>
                    <?php if($model->Alergia == "") { $Alergia = "SIN ASIGNAR"; } else { $Alergia = UtilidadesVarias::textoestado2($model->Alergia); } ?>
                    <?php echo '<p>'.$Alergia.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row" id="obs_alerg" style="display: none;"> 
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Observaciones (alergia)</label>
                    <?php if($model->Observaciones == "") { $Observaciones = "SIN ASIGNAR"; } else { $Observaciones = $model->Observaciones; } ?>
                    <?php echo '<p>'.$Observaciones.'</p>'; ?>
                </div>
            </div>       
        </div>
        <h5 class="mt-3 mb-3">Otros datos</h5>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio labor</label>
                    <?php if($model->Id_Ciudad_Labor == "") { $Ciudad_L = "SIN ASIGNAR"; } else { $Ciudad_L = $model->idciudadl->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_L.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Regional de labor</label>
                    <?php if($model->Id_Regional_Labor == "") { $Regional_L = "SIN ASIGNAR"; } else { $Regional_L = $model->idregional->Regional; } ?>
                    <?php echo '<p>'.$Regional_L.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Eps</label>
                    <?php if($model->Id_Eps == "") { $Eps = "SIN ASIGNAR"; } else { $Eps = $model->ideps->Dominio; } ?>
                    <?php echo '<p>'.$Eps.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Caja de compensación</label>
                    <?php if($model->Id_Caja_C == "") { $Cc = "SIN ASIGNAR"; } else { $Cc = $model->idcajac->Dominio; } ?>
                    <?php echo '<p>'.$Cc.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fondo de pensiones</label>
                    <?php if($model->Id_Fondo_P == "") { $Fondo_p = "SIN ASIGNAR"; } else { $Fondo_p = $model->idfondop->Dominio; } ?>
                    <?php echo '<p>'.$Fondo_p.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fondo de cesantías</label>
                    <?php if($model->Id_Fondo_C == "") { $Fondo_c = "SIN ASIGNAR"; } else { $Fondo_c = $model->idfondoc->Dominio; } ?>
                    <?php echo '<p>'.$Fondo_c.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Arl</label>
                    <?php if($model->Id_Arl == "") { $Arl = "SIN ASIGNAR"; } else { $Arl = $model->idarl->Dominio; } ?>
                    <?php echo '<p>'.$Arl.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Banco</label>
                    <?php if($model->Id_Banco == "") { $Banco = "SIN ASIGNAR"; } else { $Banco = $model->idbanco->Dominio; } ?>
                    <?php echo '<p>'.$Banco.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Tipo de cuenta</label>
                    <?php if($model->Id_T_Cuenta == "") { $Tipo_Cuenta = "SIN ASIGNAR"; } else { $Tipo_Cuenta = $model->idtcuenta->Dominio; } ?>
                    <?php echo '<p>'.$Tipo_Cuenta.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Número de cuenta</label>
                    <?php if($model->Num_Cuenta == "") { $Num_Cuenta = "SIN ASIGNAR"; } else { $Num_Cuenta = $model->Num_Cuenta; } ?>
                    <?php echo '<p>'.$Num_Cuenta.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla camisa</label>
                    <?php if($model->Talla_Camisa == "") { $Talla_Camisa = "SIN ASIGNAR"; } else { $Talla_Camisa = $model->Talla_Camisa; } ?>
                    <?php echo '<p>'.$Talla_Camisa.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla pantalón</label>
                    <?php if($model->Talla_Pantalon == "") { $Talla_Pantalon = "SIN ASIGNAR"; } else { $Talla_Pantalon = $model->Talla_Pantalon; } ?>
                    <?php echo '<p>'.$Talla_Pantalon.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla zapatos</label>
                    <?php if($model->Talla_Zapato == "") { $Talla_Zapato = "SIN ASIGNAR"; } else { $Talla_Zapato = $model->Talla_Zapato; } ?>
                    <?php echo '<p>'.$Talla_Zapato.'</p>'; ?>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla overol</label>
                    <?php if($model->Talla_Overol == "") { $Talla_Overol = "SIN ASIGNAR"; } else { $Talla_Overol = $model->Talla_Overol; } ?>
                    <?php echo '<p>'.$Talla_Overol.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla bata</label>
                    <?php if($model->Talla_Bata == "") { $Talla_Bata = "SIN ASIGNAR"; } else { $Talla_Bata = $model->Talla_Bata; } ?>
                    <?php echo '<p>'.$Talla_Bata.'</p>'; ?>
                </div>
            </div>    
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estado</label>
                    <?php echo '<p>'.UtilidadesEmpleado::estadoactualempleado($model->Id_Empleado).'</p>'; ?>
                </div>
            </div>
        </div>
        <div id="info_act" style="display: none;">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Empresa</label>
                        <?php echo '<p>'.UtilidadesEmpleado::empresaactualempleado($model->Id_Empleado).'</p>'; ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Área</label>
                        <?php echo '<p>'.UtilidadesEmpleado::unidadgerenciaactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Área</label>
                        <?php echo '<p>'.UtilidadesEmpleado::areaactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Subárea</label>
                        <?php echo '<p>'.UtilidadesEmpleado::subareaactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Cargo</label>
                        <?php echo '<p>'.UtilidadesEmpleado::cargoactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Centro de costo</label>
                        <?php echo '<p>'.UtilidadesEmpleado::centrocostoactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Usuario que creo</label>
                    <?php echo '<p>'.$model->idusuariocre->Usuario.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha de creación</label>
                    <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ultimo usuario que actualizó</label>
                    <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ultima fecha de actualización</label>
                    <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>';?>
                </div>
            </div>
        </div>
    </div>
    <div id="contrato_activo" class="tab-pane fade"><br>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#con_act">Contrato</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#com_act">Comparendos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ele_act">Elementos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#her_act">Herramientas</a></li>
        </ul>

        <!-- Tab panes -->
         <div class="tab-content">
            <div id="con_act" class="tab-pane active"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'contrato-empleado-grid-act',
                    'dataProvider'=> $model_contrato_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Empresa',
                            'value'=>'$data->idempresa->Descripcion',
                        ),
                        array(
                            'name' => 'Id_Unidad_Gerencia',
                            'value' => '($data->Id_Unidad_Gerencia == "") ? "SIN ASIGNAR" : $data->idunidadgerencia->Unidad_Gerencia',
                        ),
                        array(
                            'name' => 'Id_Area',
                            'value' => '($data->Id_Area == "") ? "SIN ASIGNAR" : $data->idarea->Area',
                        ),
                        array(
                            'name' => 'Id_Subarea',
                            'value' => '($data->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'Id_Cargo',
                            'value' => '($data->Id_Cargo == "") ? "SIN ASIGNAR" : $data->idcargo->Cargo',
                        ),
                        array(
                            'name' => 'Id_Centro_Costo',
                            'value' => '($data->Id_Centro_Costo == "") ? "SIN ASIGNAR" : $data->idcentrocosto->Codigo',
                        ),
                        array(
                            'name'=>'Fecha_Ingreso',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Ingreso)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("contratoEmpleado/update3", array("id"=>$data->Id_Contrato))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="com_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'comparendo-empleado-grid-act',
                    'dataProvider'=>$model_comparendos_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Disciplinario',
                            'value'=>'$data->idmdisciplinario->Dominio',
                        ),
                        array(
                            'name'=>'Fecha',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                        ),
                        array(
                            'name'=>'Id_Empleado_Imp',
                            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado_Imp)',
                        ),
                        'Orden_No',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("disciplinarioEmpleado/update", array("id"=>$data->Id_Disciplinario, "opc"=>$data->GetOpc($data->Id_Disciplinario)))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="ele_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'elemento-empleado-grid-act',
                    'dataProvider'=>$model_elementos_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name' => 'Cantidad',
                            'type' => 'raw',
                            'value' => '$data->Cantidad',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name' => 'elemento',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idelemento->Elemento',
                        ),
                        array(
                            'name' => 'subarea',
                            'type' => 'raw',
                            'value' => '($data->idaelemento->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idaelemento->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'area',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idarea->Area',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesElemento::textoestado($data->Estado)',
                        ),
                    ),
                )); ?> 
            </div>
            <div id="her_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'herramienta-empleado-grid-act',
                    'dataProvider'=>$model_herramientas_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Herramienta',
                            'value'=>'$data->idherramienta->Nombre',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesHerramienta::textoestado($data->Estado)',
                        ),
                    ),
                )); ?>  
            </div>    
        </div>
    </div>
    <div id="contratos_anteriores" class="tab-pane fade"><br>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#con_ant">Contrato</a></li>
            
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#com_ant">Comparendos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ele_ant">Elementos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#her_ant">Herramientas</a></li>
        </ul>

        <!-- Tab panes -->
         <div class="tab-content">
            <div id="con_ant" class="tab-pane active"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'contrato-empleado-grid-ant',
                    'dataProvider'=> $model_contratos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Empresa',
                            'value'=>'$data->idempresa->Descripcion',
                        ),
                        array(
                            'name' => 'Id_Unidad_Gerencia',
                            'value' => '($data->Id_Unidad_Gerencia == "") ? "SIN ASIGNAR" : $data->idunidadgerencia->Unidad_Gerencia',
                        ),
                        array(
                            'name' => 'Id_Area',
                            'value' => '($data->Id_Area == "") ? "SIN ASIGNAR" : $data->idarea->Area',
                        ),
                        array(
                            'name' => 'Id_Subarea',
                            'value' => '($data->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'Id_Cargo',
                            'value' => '($data->Id_Cargo == "") ? "SIN ASIGNAR" : $data->idcargo->Cargo',
                        ),
                        array(
                            'name' => 'Id_Centro_Costo',
                            'value' => '($data->Id_Centro_Costo == "") ? "SIN ASIGNAR" : $data->idcentrocosto->Codigo',
                        ),
                        array(
                            'name'=>'Fecha_Ingreso',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Ingreso)',
                        ),
                        array(
                            'name'=>'Fecha_Retiro',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Retiro)',
                        ),
                    ),
                )); ?>  
            </div>
            <div id="com_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'comparendo-empleado-grid-ant',
                    'dataProvider'=>$model_comparendos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Disciplinario',
                            'value'=>'$data->idmdisciplinario->Dominio',
                        ),
                        array(
                            'name'=>'Fecha',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                        ),
                        array(
                            'name'=>'Id_Empleado_Imp',
                            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado_Imp)',
                        ),
                        'Orden_No',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("disciplinarioEmpleado/update", array("id"=>$data->Id_Disciplinario, "opc"=>$data->GetOpc($data->Id_Disciplinario)))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="ele_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'elemento-empleado-grid-ant',
                    'dataProvider'=>$model_elementos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name' => 'Cantidad',
                            'type' => 'raw',
                            'value' => '$data->Cantidad',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name' => 'elemento',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idelemento->Elemento',
                        ),
                        array(
                            'name' => 'subarea',
                            'type' => 'raw',
                            'value' => '($data->idaelemento->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idaelemento->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'area',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idarea->Area',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesElemento::textoestado($data->Estado)',
                        ),
                    ),
                )); ?>
            </div>
            <div id="her_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'herramienta-empleado-grid-ant',
                    'dataProvider'=>$model_herramientas_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Herramienta',
                            'value'=>'$data->idherramienta->Nombre',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesHerramienta::textoestado($data->Estado)',
                        ),
                    ),
                )); ?> 
            </div>    
        </div>
    </div>
</div>

<?php } if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_nomina){ ?>

<!----------VISTA NOMINA---------->

<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nucleo">Núcleo familiar</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#formacion">Formación</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#evaluacion">Evaluaciones</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contrato_activo">Contrato Activo</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contratos_anteriores">Contratos anteriores</a></li>
</ul>

<!-- Tab panes -->
 <div class="tab-content">
    <div id="info" class="tab-pane active"><br>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>ID</label>
                    <?php echo '<p>'.$model->Id_Empleado.'</p>';?>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Tipo de identificación</label>
                    <?php echo '<p>'.$model->idtipoident->Dominio.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label># Identificación</label>
                    <?php echo '<p>'.$model->Identificacion.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio de expedición </label>
                    <?php if($model->Id_Ciudad_Exp_Ident == "") { $Ciudad_E_I = "SIN ASIGNAR"; } else { $Ciudad_E_I = $model->idciudadexpident->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_E_I.'</p>'; ?>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Apellidos</label>
                    <?php echo '<p>'.$model->Apellido.'</p>';?>
                </div>
            </div>
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Nombres</label>
                    <?php echo '<p>'.$model->Nombre.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha de nacimiento</label>
                    <?php if($model->Fecha_Nacimiento == "") { $Fecha_Nacimiento = "SIN ASIGNAR"; } else { $Fecha_Nacimiento = UtilidadesVarias::textofecha($model->Fecha_Nacimiento); } ?>
                    <?php echo '<p>'.$Fecha_Nacimiento.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Edad</label>
                    <?php echo '<p id="edad_emp"></p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio nacimiento</label>
                    <?php if($model->Id_Ciudad_Nacimiento == "") { $Ciudad_N = "SIN ASIGNAR"; } else { $Ciudad_N = $model->idciudadn->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_N.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dirección</label>
                    <?php if($model->Direccion == "") { $Direccion = "SIN ASIGNAR"; } else { $Direccion = $model->Direccion; } ?>
                    <?php echo '<p>'.$Direccion.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Teléfono(s)</label>
                    <?php if($model->Telefono == "") { $Telefono = "SIN ASIGNAR"; } else { $Telefono = $model->Telefono; } ?>
                    <?php echo '<p>'.$Telefono.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>E-mail</label>
                    <?php if($model->Correo == "") { $Correo = "SIN ASIGNAR"; } else { $Correo = $model->Correo; } ?>
                    <?php echo '<p>'.$Correo.'</p>'; ?>
                </div>
            </div>
        </div>
        <h5 class="mt-3 mb-3">Datos sociodemográficos</h5>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Grado de escolaridad</label>
                    <?php if($model->Id_Grado_Esc == "") { $Id_Grado_Esc = "SIN ASIGNAR"; } else { $Id_Grado_Esc = $model->idgradoesc->Dominio; } ?>
                    <?php echo '<p>'.$Id_Grado_Esc.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estado civil</label>
                    <?php if($model->Id_Estado_Civil == "") { $Estado_civil = "SIN ASIGNAR"; } else { $Estado_civil = $model->idestadocivil->Dominio; } ?>
                    <?php echo '<p>'.$Estado_civil.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Raza</label>
                    <?php if($model->Id_Raza == "") { $Raza = "SIN ASIGNAR"; } else { $Raza = $model->idraza->Dominio; } ?>
                    <?php echo '<p>'.$Raza.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Composición familiar</label>
                    <?php if($model->Id_Com_Fam == "") { $Com_Fam = "SIN ASIGNAR"; } else { $Com_Fam = UtilidadesEmpleado::compfamempleado($model->Id_Com_Fam); } ?>
                    <?php echo '<p>'.$Com_Fam.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ocupación</label>
                    <?php if($model->Id_Ocupacion == "") { $Ocupacion = "SIN ASIGNAR"; } else { $Ocupacion = UtilidadesEmpleado::ocupmempleado($model->Id_Ocupacion); } ?>
                    <?php echo '<p>'.$Ocupacion.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio residencia</label>
                    <?php if($model->Id_Ciudad_Residencia == "") { $Ciudad_R = "SIN ASIGNAR"; } else { $Ciudad_R = $model->idciudadr->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_R.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4" id="loc_res" style="display: none; ">
                <div class="form-group">
                    <label>Localidad de residencia</label>
                    <?php if($model->Id_Localidad_Residencia == "") { $Loc_Res = "SIN ASIGNAR"; } else { $Loc_Res = $model->idlocres->Dominio; } ?>
                    <?php echo '<p>'.$Loc_Res.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>RH</label>
                    <?php if($model->Id_Rh == "") { $Rh = "SIN ASIGNAR"; } else { $Rh = $model->idrh->Dominio; } ?>
                    <?php echo '<p>'.$Rh.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Género</label>
                    <?php if($model->Id_Genero == "") { $Genero = "SIN ASIGNAR"; } else { $Genero = $model->idgenero->Dominio; } ?>
                    <?php echo '<p>'.$Genero.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4" id="ges" style="display: none;">
                <div class="form-group">
                    <label>Gestante ?</label>
                    <?php if($model->Es_Gestante == "") { $Es_Gestante = "SIN ASIGNAR"; } else { $Es_Gestante = UtilidadesVarias::textoestado2($model->Es_Gestante); } ?>
                    <?php echo '<p>'.$Es_Gestante.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estrato socioeconómico</label>
                    <?php if($model->Id_Estrato == "") { $Estrato = "SIN ASIGNAR"; } else { $Estrato = $model->idestrato->Dominio; } ?>
                    <?php echo '<p>'.$Estrato.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Persona de contacto</label>
                    <?php if($model->Persona_Contacto == "") { $Persona_Contacto = "SIN ASIGNAR"; } else { $Persona_Contacto = $model->Persona_Contacto; } ?>
                    <?php echo '<p>'.$Persona_Contacto.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Teléfono persona de contacto</label>
                    <?php if($model->Tel_Persona_Contacto == "") { $Tel_Persona_Contacto = "SIN ASIGNAR"; } else { $Tel_Persona_Contacto = $model->Tel_Persona_Contacto; } ?>
                    <?php echo '<p>'.$Tel_Persona_Contacto.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Parentesco contacto</label>
                    <?php if($model->Id_Parentesco_Persona_Contacto == "") { $Parentesco_Persona_Contacto = "SIN ASIGNAR"; } else { $Parentesco_Persona_Contacto = $model->idparentpercont->Dominio; } ?>
                    <?php echo '<p>'.$Parentesco_Persona_Contacto.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fuma ?</label>
                    <?php if($model->Fuma == "") { $Fuma = "SIN ASIGNAR"; } else { $Fuma = UtilidadesVarias::textoestado2($model->Fuma); } ?>
                    <?php echo '<p>'.$Fuma.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Alergia ?</label>
                    <?php if($model->Alergia == "") { $Alergia = "SIN ASIGNAR"; } else { $Alergia = UtilidadesVarias::textoestado2($model->Alergia); } ?>
                    <?php echo '<p>'.$Alergia.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row" id="obs_alerg" style="display: none;"> 
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Observaciones (alergia)</label>
                    <?php if($model->Observaciones == "") { $Observaciones = "SIN ASIGNAR"; } else { $Observaciones = $model->Observaciones; } ?>
                    <?php echo '<p>'.$Observaciones.'</p>'; ?>
                </div>
            </div>       
        </div>
        <h5 class="mt-3 mb-3">Otros datos</h5>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Dpto - municipio labor</label>
                    <?php if($model->Id_Ciudad_Labor == "") { $Ciudad_L = "SIN ASIGNAR"; } else { $Ciudad_L = $model->idciudadl->Ciudad; } ?>
                    <?php echo '<p>'.$Ciudad_L.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Regional de labor</label>
                    <?php if($model->Id_Regional_Labor == "") { $Regional_L = "SIN ASIGNAR"; } else { $Regional_L = $model->idregional->Regional; } ?>
                    <?php echo '<p>'.$Regional_L.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Eps</label>
                    <?php if($model->Id_Eps == "") { $Eps = "SIN ASIGNAR"; } else { $Eps = $model->ideps->Dominio; } ?>
                    <?php echo '<p>'.$Eps.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Caja de compensación</label>
                    <?php if($model->Id_Caja_C == "") { $Cc = "SIN ASIGNAR"; } else { $Cc = $model->idcajac->Dominio; } ?>
                    <?php echo '<p>'.$Cc.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fondo de pensiones</label>
                    <?php if($model->Id_Fondo_P == "") { $Fondo_p = "SIN ASIGNAR"; } else { $Fondo_p = $model->idfondop->Dominio; } ?>
                    <?php echo '<p>'.$Fondo_p.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fondo de cesantías</label>
                    <?php if($model->Id_Fondo_C == "") { $Fondo_c = "SIN ASIGNAR"; } else { $Fondo_c = $model->idfondoc->Dominio; } ?>
                    <?php echo '<p>'.$Fondo_c.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Arl</label>
                    <?php if($model->Id_Arl == "") { $Arl = "SIN ASIGNAR"; } else { $Arl = $model->idarl->Dominio; } ?>
                    <?php echo '<p>'.$Arl.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Banco</label>
                    <?php if($model->Id_Banco == "") { $Banco = "SIN ASIGNAR"; } else { $Banco = $model->idbanco->Dominio; } ?>
                    <?php echo '<p>'.$Banco.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Tipo de cuenta</label>
                    <?php if($model->Id_T_Cuenta == "") { $Tipo_Cuenta = "SIN ASIGNAR"; } else { $Tipo_Cuenta = $model->idtcuenta->Dominio; } ?>
                    <?php echo '<p>'.$Tipo_Cuenta.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Número de cuenta</label>
                    <?php if($model->Num_Cuenta == "") { $Num_Cuenta = "SIN ASIGNAR"; } else { $Num_Cuenta = $model->Num_Cuenta; } ?>
                    <?php echo '<p>'.$Num_Cuenta.'</p>'; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla camisa</label>
                    <?php if($model->Talla_Camisa == "") { $Talla_Camisa = "SIN ASIGNAR"; } else { $Talla_Camisa = $model->Talla_Camisa; } ?>
                    <?php echo '<p>'.$Talla_Camisa.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla pantalón</label>
                    <?php if($model->Talla_Pantalon == "") { $Talla_Pantalon = "SIN ASIGNAR"; } else { $Talla_Pantalon = $model->Talla_Pantalon; } ?>
                    <?php echo '<p>'.$Talla_Pantalon.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla zapatos</label>
                    <?php if($model->Talla_Zapato == "") { $Talla_Zapato = "SIN ASIGNAR"; } else { $Talla_Zapato = $model->Talla_Zapato; } ?>
                    <?php echo '<p>'.$Talla_Zapato.'</p>'; ?>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla overol</label>
                    <?php if($model->Talla_Overol == "") { $Talla_Overol = "SIN ASIGNAR"; } else { $Talla_Overol = $model->Talla_Overol; } ?>
                    <?php echo '<p>'.$Talla_Overol.'</p>'; ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Talla bata</label>
                    <?php if($model->Talla_Bata == "") { $Talla_Bata = "SIN ASIGNAR"; } else { $Talla_Bata = $model->Talla_Bata; } ?>
                    <?php echo '<p>'.$Talla_Bata.'</p>'; ?>
                </div>
            </div>    
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Estado</label>
                    <?php echo '<p>'.UtilidadesEmpleado::estadoactualempleado($model->Id_Empleado).'</p>'; ?>
                </div>
            </div>
        </div>
        <div id="info_act" style="display: none;">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Empresa</label>
                        <?php echo '<p>'.UtilidadesEmpleado::empresaactualempleado($model->Id_Empleado).'</p>'; ?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Área</label>
                        <?php echo '<p>'.UtilidadesEmpleado::unidadgerenciaactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Área</label>
                        <?php echo '<p>'.UtilidadesEmpleado::areaactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Subárea</label>
                        <?php echo '<p>'.UtilidadesEmpleado::subareaactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Cargo</label>
                        <?php echo '<p>'.UtilidadesEmpleado::cargoactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Centro de costo</label>
                        <?php echo '<p>'.UtilidadesEmpleado::centrocostoactualempleado($model->Id_Empleado).'</p>';?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Usuario que creo</label>
                    <?php echo '<p>'.$model->idusuariocre->Usuario.'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Fecha de creación</label>
                    <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>';?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ultimo usuario que actualizó</label>
                    <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label>Ultima fecha de actualización</label>
                    <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>';?>
                </div>
            </div>
        </div>  
    </div>
    <div id="nucleo" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'nucleo-empleado-grid',
            'dataProvider'=>$model_parientes->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                array(
                    'name'=>'Id_Parentesco',
                    'value'=>'$data->idparentesco->Dominio',
                ),
                array(
                    'name'=>'Id_Genero',
                    'value'=>'$data->idgenero->Dominio',
                ),
                'Nombre_Apellido',
                array(
                    'name'=>'Fecha_Nacimiento',
                    'value'=>'UtilidadesVarias::textofecha($data->Fecha_Nacimiento)',
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array(
                        'update'=>array(
                            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Actualizar'),
                            'url'=>'Yii::app()->createUrl("nucleoEmpleado/update", array("id"=>$data->Id_Nucleo))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                        ),
                    )
                ),
            ),
        )); ?>  
    </div>
    <div id="formacion" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'formacion-empleado-grid',
            'dataProvider'=>$model_formacion->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                array(
                    'name'=>'Fecha',
                    'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                ),
                'Entidad',
                'Titulo_Obtenido',
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array(
                        'update'=>array(
                            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Actualizar'),
                            'url'=>'Yii::app()->createUrl("formacionEmpleado/update", array("id"=>$data->Id_Formacion))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                        ),
                    )
                ),
            ),
        )); ?>  
    </div>
    <div id="evaluacion" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'evaluacion-empleado-grid',
            'dataProvider'=>$model_evaluaciones->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                array(
                    'name'=>'Fecha',
                    'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                ),
                array(
                'name'=>'Id_Tipo',
                    'value'=>'$data->idtipo->Dominio',
                ),
                'Puntaje',
                'Observacion',
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array(
                        'update'=>array(
                            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Actualizar'),
                            'url'=>'Yii::app()->createUrl("evaluacionEmpleado/update", array("id"=>$data->Id_Evaluacion))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                        ),
                    )
                ),
            ),
        )); ?> 
    </div>
    <div id="contrato_activo" class="tab-pane fade"><br>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#con_act">Contrato</a></li>
            <?php if($upd_th == true){ ?>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nov_act">Novedades</a></li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tur_act">Turnos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#aus_act">Ausencias</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#dis_act">Llamados de atención / Sanciones</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#com_act">Comparendos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ele_act">Elementos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#her_act">Herramientas</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cue_act">Cuentas</a></li>
        </ul>

        <!-- Tab panes -->
         <div class="tab-content">
            <div id="con_act" class="tab-pane active"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'contrato-empleado-grid-act',
                    'dataProvider'=> $model_contrato_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Empresa',
                            'value'=>'$data->idempresa->Descripcion',
                        ),
                        array(
                            'name' => 'Id_Unidad_Gerencia',
                            'value' => '($data->Id_Unidad_Gerencia == "") ? "SIN ASIGNAR" : $data->idunidadgerencia->Unidad_Gerencia',
                        ),
                        array(
                            'name' => 'Id_Area',
                            'value' => '($data->Id_Area == "") ? "SIN ASIGNAR" : $data->idarea->Area',
                        ),
                        array(
                            'name' => 'Id_Subarea',
                            'value' => '($data->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'Id_Cargo',
                            'value' => '($data->Id_Cargo == "") ? "SIN ASIGNAR" : $data->idcargo->Cargo',
                        ),
                        array(
                            'name' => 'Id_Centro_Costo',
                            'value' => '($data->Id_Centro_Costo == "") ? "SIN ASIGNAR" : $data->idcentrocosto->Codigo',
                        ),
                        array(
                            'name'=>'Fecha_Ingreso',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Ingreso)',
                        ),
                        array(
                            'name'=>'Salario',
                            'value'=>function($data){
                                return number_format($data->Salario, 0);
                            },
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                            'visible'=>$upd_th,
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("contratoEmpleado/update", array("id"=>$data->Id_Contrato))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Id_M_Retiro == ""  && Yii::app()->user->getState("upd_th") == true)',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="nov_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'novedad-contrato-grid-act',
                    'dataProvider'=>$model_novedades_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        'Novedad',
                        array(
                            'name'=>'Id_Usuario_Creacion',
                            'value'=>'$data->idusuariocre->Usuario',
                        ),
                        array(
                            'name'=>'Fecha_Creacion',
                            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
                        ),
                    ),
                )); ?>
            </div>
            <div id="tur_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'turno-empleado-grid-act',
                    'dataProvider'=>$model_turnos_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        array(
                            'name'=>'Id_Turno',
                            'value'=>'$data->DescTurno($data->Id_Turno)',
                        ),
                        array(
                            'name'=>'Fecha_Inicial',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Inicial)',
                        ),
                        array(
                            'name'=>'Fecha_Final',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Final)',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesVarias::textoestado1($data->Estado)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("turnoEmpleado/update", array("id"=>$data->Id_T_Empleado))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>
            </div>
            <div id="aus_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'ausencia-grid-act',
                    'dataProvider'=>$model_ausencias_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Ausencia',
                            'value'=>'$data->idmausencia->Dominio',
                        ),
                        'Cod_Soporte',
                        array(
                            'name'=>'Fecha_Inicial',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Inicial)',
                        ),
                        array(
                            'name'=>'Fecha_Final',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Final)',
                        ),
                        array(
                            'name' => 'Dias',
                            'type' => 'raw',
                            'value' => '$data->Dias',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),  
                        array(
                            'name' => 'Horas',
                            'type' => 'raw',
                            'value' => '($data->Horas == 0.0) ? 0 : $data->Horas',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name' => 'Descontar',
                            'value' => 'UtilidadesVarias::textoestado2($data->Descontar)',
                        ),
                        array(
                            'name' => 'Descontar_FDS',
                            'value' => 'UtilidadesVarias::textoestado2($data->Descontar_FDS)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("ausenciaEmpleado/update", array("id"=>$data->Id_Ausencia))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?> 
            </div>
            <div id="dis_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'disciplinario-empleado-grid-act',
                    'dataProvider'=>$model_disciplinarios_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'tipo',
                            'value'=>'$data->DescTipo($data->Id_Disciplinario)',
                        ),
                        array(
                            'name'=>'Id_M_Disciplinario',
                            'value'=>'$data->idmdisciplinario->Dominio',
                        ),
                        array(
                            'name'=>'Fecha',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                        ),
                        array(
                            'name'=>'Id_Empleado_Imp',
                            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado_Imp)',
                        ),
                        'Orden_No',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("disciplinarioEmpleado/update", array("id"=>$data->Id_Disciplinario, "opc"=>$data->GetOpc($data->Id_Disciplinario)))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?> 
            </div>
            <div id="com_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'comparendo-empleado-grid-act',
                    'dataProvider'=>$model_comparendos_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Disciplinario',
                            'value'=>'$data->idmdisciplinario->Dominio',
                        ),
                        array(
                            'name'=>'Fecha',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                        ),
                        array(
                            'name'=>'Id_Empleado_Imp',
                            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado_Imp)',
                        ),
                        'Orden_No',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("disciplinarioEmpleado/update", array("id"=>$data->Id_Disciplinario, "opc"=>$data->GetOpc($data->Id_Disciplinario)))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="ele_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'elemento-empleado-grid-act',
                    'dataProvider'=>$model_elementos_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name' => 'Cantidad',
                            'type' => 'raw',
                            'value' => '$data->Cantidad',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name' => 'elemento',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idelemento->Elemento',
                        ),
                        array(
                            'name' => 'subarea',
                            'type' => 'raw',
                            'value' => '($data->idaelemento->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idaelemento->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'area',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idarea->Area',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesElemento::textoestado($data->Estado)',
                        ),
                    ),
                )); ?> 
            </div>
            <div id="her_act" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'herramienta-empleado-grid-act',
                    'dataProvider'=>$model_herramientas_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Herramienta',
                            'value'=>'$data->idherramienta->Nombre',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesHerramienta::textoestado($data->Estado)',
                        ),
                    ),
                )); ?>  
            </div>
            <div id="cue_act" class="tab-pane fade"><br>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'cuenta-empleado-grid',
                    'dataProvider'=>$model_cuentas_act,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        array(
                            'name'=>'clasificacion',
                            'value' => '$data->idcuenta->clasificacion->Dominio',
                        ),
                        array(
                            'name'=>'Id_Cuenta',
                            'value' => '$data->idcuenta->DescCuentaUsuario($data->Id_Cuenta)',
                        ),
                        array(
                            'name' => 'Estado',
                            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
                        ),
                    ),
                ));

                ?>
            </div>
        </div>
    </div>
    <div id="contratos_anteriores" class="tab-pane fade"><br>
                <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#con_ant">Contrato</a></li>
            <?php if($upd_th == true){ ?>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#nov_ant">Novedades</a></li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tur_ant">Turnos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#aus_ant">Ausencias</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#dis_ant">Llamados de atención / Sanciones</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#com_ant">Comparendos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ele_ant">Elementos</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#her_ant">Herramientas</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cue_ant">Cuentas</a></li>
        </ul>

        <!-- Tab panes -->
         <div class="tab-content">
            <div id="con_ant" class="tab-pane active"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'contrato-empleado-grid-ant',
                    'dataProvider'=> $model_contratos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Empresa',
                            'value'=>'$data->idempresa->Descripcion',
                        ),
                        array(
                            'name' => 'Id_Unidad_Gerencia',
                            'value' => '($data->Id_Unidad_Gerencia == "") ? "SIN ASIGNAR" : $data->idunidadgerencia->Unidad_Gerencia',
                        ),
                        array(
                            'name' => 'Id_Area',
                            'value' => '($data->Id_Area == "") ? "SIN ASIGNAR" : $data->idarea->Area',
                        ),
                        array(
                            'name' => 'Id_Subarea',
                            'value' => '($data->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'Id_Cargo',
                            'value' => '($data->Id_Cargo == "") ? "SIN ASIGNAR" : $data->idcargo->Cargo',
                        ),
                        array(
                            'name' => 'Id_Centro_Costo',
                            'value' => '($data->Id_Centro_Costo == "") ? "SIN ASIGNAR" : $data->idcentrocosto->Codigo',
                        ),
                        array(
                            'name'=>'Fecha_Ingreso',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Ingreso)',
                        ),
                         array(
                            'name'=>'Salario',
                            'value'=>function($data){
                                return number_format($data->Salario, 0);
                            },
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                            'visible'=>$upd_th,
                        ),
                        array(
                            'name'=>'Fecha_Retiro',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Retiro)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{res}{update}',
                            'buttons'=>array(
                                'res'=>array(
                                    'label'=>'<i class="fas fa-id-card-alt actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Resumen de contrato'),
                                    'url'=>'Yii::app()->createUrl("contratoEmpleado/res", array("id"=>$data->Id_Contrato))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && Yii::app()->user->getState("upd_th") == true)',
                                ),
                                'update'=>array(
                                    'label'=>'<i class="fa fa-calendar actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Asignar fecha de liquidación'),
                                    'url'=>'Yii::app()->createUrl("contratoEmpleado/update2", array("id"=>$data->Id_Contrato))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Fecha_Liquidacion == "" && Yii::app()->user->getState("upd_th") == true)',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="nov_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'novedad-contrato-grid-ant',
                    'dataProvider'=>$model_novedades_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        'Novedad',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{view}',
                            'buttons'=>array(
                                'view'=>array(
                                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Visualizar'),
                                    'url'=>'Yii::app()->createUrl("novedadContrato/view", array("id"=>$data->Id_N_Contrato))',
                                ),
                            )
                        ),
                    ),
                )); ?> 
            </div>
            <div id="tur_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'turno-empleado-grid-ant',
                    'dataProvider'=>$model_turnos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Turno',
                            'value'=>'$data->DescTurno($data->Id_Turno)',
                        ),
                        array(
                            'name'=>'Fecha_Inicial',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Inicial)',
                        ),
                        array(
                            'name'=>'Fecha_Final',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Final)',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesVarias::textoestado1($data->Estado)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("turnoEmpleado/update", array("id"=>$data->Id_T_Empleado))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?> 
            </div>
            <div id="aus_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'ausencia-grid-ant',
                    'dataProvider'=>$model_ausencias_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Ausencia',
                            'value'=>'$data->idmausencia->Dominio',
                        ),
                        'Cod_Soporte',
                        array(
                            'name'=>'Fecha_Inicial',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Inicial)',
                        ),
                        array(
                            'name'=>'Fecha_Final',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Final)',
                        ),
                        array(
                            'name' => 'Dias',
                            'type' => 'raw',
                            'value' => '$data->Dias',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),  
                        array(
                            'name' => 'Horas',
                            'type' => 'raw',
                            'value' => '($data->Horas == 0.0) ? 0 : $data->Horas',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name' => 'Descontar',
                            'value' => 'UtilidadesVarias::textoestado2($data->Descontar)',
                        ),
                        array(
                            'name' => 'Descontar_FDS',
                            'value' => 'UtilidadesVarias::textoestado2($data->Descontar_FDS)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("ausenciaEmpleado/update", array("id"=>$data->Id_Ausencia))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?> 
            </div>
            <div id="dis_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'disciplinario-empleado-grid-ant',
                    'dataProvider'=>$model_disciplinarios_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Disciplinario',
                            'value'=>'$data->idmdisciplinario->Dominio',
                        ),
                        array(
                            'name'=>'Fecha',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                        ),
                        array(
                            'name'=>'Id_Empleado_Imp',
                            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado_Imp)',
                        ),
                        'Orden_No',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("disciplinarioEmpleado/update", array("id"=>$data->Id_Disciplinario, "opc"=>$data->GetOpc($data->Id_Disciplinario)))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="com_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'comparendo-empleado-grid-ant',
                    'dataProvider'=>$model_comparendos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_M_Disciplinario',
                            'value'=>'$data->idmdisciplinario->Dominio',
                        ),
                        array(
                            'name'=>'Fecha',
                            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
                        ),
                        array(
                            'name'=>'Id_Empleado_Imp',
                            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado_Imp)',
                        ),
                        'Orden_No',
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{update}',
                            'buttons'=>array(
                                'update'=>array(
                                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Actualizar'),
                                    'url'=>'Yii::app()->createUrl("disciplinarioEmpleado/update", array("id"=>$data->Id_Disciplinario, "opc"=>$data->GetOpc($data->Id_Disciplinario)))',
                                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idcontrato->Id_M_Retiro == "")',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="ele_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'elemento-empleado-grid-ant',
                    'dataProvider'=>$model_elementos_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name' => 'Cantidad',
                            'type' => 'raw',
                            'value' => '$data->Cantidad',
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name' => 'elemento',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idelemento->Elemento',
                        ),
                        array(
                            'name' => 'subarea',
                            'type' => 'raw',
                            'value' => '($data->idaelemento->Id_Subarea == "") ? "SIN ASIGNAR" : $data->idaelemento->idsubarea->Subarea',
                        ),
                        array(
                            'name' => 'area',
                            'type' => 'raw',
                            'value' => '$data->idaelemento->idarea->Area',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesElemento::textoestado($data->Estado)',
                        ),
                    ),
                )); ?>  
            </div>
            <div id="her_ant" class="tab-pane fade"><br>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'herramienta-empleado-grid-ant',
                    'dataProvider'=>$model_herramientas_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        'Id_Contrato',
                        array(
                            'name'=>'Id_Herramienta',
                            'value'=>'$data->idherramienta->Nombre',
                        ),
                        array(
                            'name'=>'Estado',
                            'value'=>'UtilidadesHerramienta::textoestado($data->Estado)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{view}',
                            'buttons'=>array(
                                'view'=>array(
                                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Visualizar'),
                                    'url'=>'Yii::app()->createUrl("herramientaEmpleado/view", array("id"=>$data->Id_H_Empleado))',
                                ),
                            )
                        ),
                    ),
                )); ?>  
            </div>
            <div id="cue_ant" class="tab-pane fade"><br>
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'cuenta-empleado-grid',
                    'dataProvider'=>$model_cuentas_ant,
                    'pager'=>array(
                        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                    ),
                    'enableSorting' => false,
                    'columns'=>array(
                        array(
                            'name'=>'clasificacion',
                            'value' => '$data->idcuenta->clasificacion->Dominio',
                        ),
                        array(
                            'name'=>'Id_Cuenta',
                            'value' => '$data->idcuenta->DescCuentaUsuario($data->Id_Cuenta)',
                        ),
                        array(
                            'name' => 'Estado',
                            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
                        ),
                        array(
                            'class'=>'CButtonColumn',
                            'template'=>'{view}',
                            'buttons'=>array(
                                'view'=>array(
                                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                                    'imageUrl'=>false,
                                    'options'=>array('title'=>'Visualizar'),
                                    'url'=>'Yii::app()->createUrl("cuentaEmpleado/view", array("id"=>$data->Id_Cuenta_Emp))',
                                ),
                            )
                        ),
                    ),
                ));
                ?> 
            </div>    
        </div>
    </div>
</div>


<?php } ?>
<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Empleado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Tipo_Ident', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Tipo_Ident', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Tipo_Ident]',
                    'id'=>'Empleado_Id_Tipo_Ident',
                    'data'=>$lista_tipos_ident,
                    'value' => $model->Id_Tipo_Ident,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Identificacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Identificacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->numberField($model,'Identificacion', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off', 'type' => 'number')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Ciudad_Exp_Ident', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Ciudad_Exp_Ident', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Ciudad_Exp_Ident]',
                    'id'=>'Empleado_Id_Ciudad_Exp_Ident',
                    'data'=>$lista_ciudades,
                    'value' => $model->Id_Ciudad_Exp_Ident,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Apellido', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Apellido', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Apellido', array('class' => 'form-control form-control-sm', 'maxlength' => '30', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div> 
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Nombre', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Nombre', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Nombre', array('class' => 'form-control form-control-sm', 'maxlength' => '30', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Nacimiento', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Nacimiento', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Fecha_Nacimiento', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'edad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'edad', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'edad', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Ciudad_Nacimiento', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Ciudad_Nacimiento', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Ciudad_Nacimiento]',
                    'id'=>'Empleado_Id_Ciudad_Nacimiento',
                    'data'=>$lista_ciudades,
                    'value' => $model->Id_Ciudad_Nacimiento,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Direccion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Direccion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Direccion', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off' , 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Telefono', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Telefono', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Telefono', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>   
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Correo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Correo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Correo', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_min(this)')); ?>
        </div>
    </div>   
</div>

<h5 class="mt-3 mb-3">Datos sociodemográficos</h5>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Grado_Esc', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grado_Esc', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Grado_Esc]',
                    'id'=>'Empleado_Id_Grado_Esc',
                    'data'=>$lista_grados_escolaridad,
                    'value' => $model->Id_Grado_Esc,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Estado_Civil', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Estado_Civil', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Estado_Civil]',
                    'id'=>'Empleado_Id_Estado_Civil',
                    'data'=>$lista_estados_c,
                    'value' => $model->Id_Estado_Civil,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Raza', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Raza', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Raza]',
                    'id'=>'Empleado_Id_Raza',
                    'data'=>$lista_razas,
                    'value' => $model->Id_Raza,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Com_Fam', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Com_Fam', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Usuario[Id_Com_Fam]',
                    'id'=>'Usuario_Id_Com_Fam',
                    'data'=>$lista_composicion_fam,
                    'htmlOptions'=>array(
                        'multiple'=>'multiple',
                    ),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Ocupacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Ocupacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Usuario[Id_Ocupacion]',
                    'id'=>'Usuario_Id_Ocupacion',
                    'data'=>$lista_ocupaciones,
                    'htmlOptions'=>array(
                        'multiple'=>'multiple',
                    ),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Ciudad_Residencia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Ciudad_Residencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Ciudad_Residencia]',
                    'id'=>'Empleado_Id_Ciudad_Residencia',
                    'data'=>$lista_ciudades,
                    'value' => $model->Id_Ciudad_Residencia,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
     <div class="col-sm-4" id="loc_res" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Localidad_Residencia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Localidad_Residencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Localidad_Residencia]',
                    'id'=>'Empleado_Id_Localidad_Residencia',
                    'data'=>$lista_localidades,
                    'value' => $model->Id_Localidad_Residencia,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Rh', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Rh', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Rh]',
                    'id'=>'Empleado_Id_Rh',
                    'data'=>$lista_rh,
                    'value' => $model->Id_Rh,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Genero', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Genero', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Genero]',
                    'id'=>'Empleado_Id_Genero',
                    'data'=>$lista_generos,
                    'value' => $model->Id_Genero,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4" id="ges" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Es_Gestante', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Es_Gestante', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados2 = Yii::app()->params->estados2; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Es_Gestante]',
                    'id'=>'Empleado_Es_Gestante',
                    'data'=>$estados2,
                    'value' => $model->Es_Gestante,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Estrato', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Estrato', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Estrato]',
                    'id'=>'Empleado_Id_Estrato',
                    'data'=>$lista_estratos,
                    'value' => $model->Id_Estrato,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Persona_Contacto', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Persona_Contacto', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Persona_Contacto', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>    
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Tel_Persona_Contacto', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Tel_Persona_Contacto', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Tel_Persona_Contacto', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>   
        </div>
    </div>
</div>
<div class="row">
     <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Parentesco_Persona_Contacto', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Parentesco_Persona_Contacto', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Parentesco_Persona_Contacto]',
                    'id'=>'Empleado_Id_Parentesco_Persona_Contacto',
                    'data'=>$lista_parentesco_contacto,
                    'value' => $model->Id_Parentesco_Persona_Contacto,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Fuma', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fuma', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados2 = Yii::app()->params->estados2; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Fuma]',
                    'id'=>'Empleado_Fuma',
                    'data'=>$estados2,
                    'value' => $model->Fuma,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
     <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Alergia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Alergia', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados2 = Yii::app()->params->estados2; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Alergia]',
                    'id'=>'Empleado_Alergia',
                    'data'=>$estados2,
                    'value' => $model->Alergia,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>     
</div>
<div class="row" id="obs_alerg" style="display: none;">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Observaciones', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>

<h5 class="mt-3 mb-3">Otros datos</h5>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Ciudad_Labor', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Ciudad_Labor', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Ciudad_Labor]',
                    'id'=>'Empleado_Id_Ciudad_Labor',
                    'data'=>$lista_ciudades,
                    'value' => $model->Id_Ciudad_Labor,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Regional_Labor', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Regional_Labor', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Regional_Labor]',
                    'id'=>'Empleado_Id_Regional_Labor',
                    'data'=>$lista_regionales,
                    'value' => $model->Id_Regional_Labor,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Eps', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Eps', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Eps]',
                    'id'=>'Empleado_Id_Eps',
                    'data'=>$lista_eps,
                    'value' => $model->Id_Eps,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Caja_C', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Caja_C', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Caja_C]',
                    'id'=>'Empleado_Id_Caja_C',
                    'data'=>$lista_cajas,
                    'value' => $model->Id_Caja_C,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Fondo_P', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Fondo_P', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Fondo_P]',
                    'id'=>'Empleado_Id_Fondo_P',
                    'data'=>$lista_pensiones,
                    'value' => $model->Id_Fondo_P,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Fondo_C', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Fondo_C', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Fondo_C]',
                    'id'=>'Empleado_Id_Fondo_C',
                    'data'=>$lista_cesantias,
                    'value' => $model->Id_Fondo_C,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Arl', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Arl', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Arl]',
                    'id'=>'Empleado_Id_Arl',
                    'data'=>$lista_arl,
                    'value' => $model->Id_Arl,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">      
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->label($model,'Id_Banco', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Banco', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Banco]',
                    'id'=>'Empleado_Id_Banco',
                    'data'=>$lista_bancos,
                    'value' => $model->Id_Banco,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_T_Cuenta', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_T_Cuenta', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_T_Cuenta]',
                    'id'=>'Empleado_Id_T_Cuenta',
                    'data'=>$lista_tipos_cuenta,
                    'value' => $model->Id_T_Cuenta,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Num_Cuenta', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Num_Cuenta', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Num_Cuenta', array('class' => 'form-control form-control-sm', 'maxlength' => '80', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Talla_Camisa', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Talla_Camisa', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Talla_Camisa', array('class' => 'form-control form-control-sm', 'maxlength' => '3', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>    
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Talla_Pantalon', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Talla_Pantalon', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Talla_Pantalon', array('class' => 'form-control form-control-sm', 'maxlength' => '3', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Talla_Zapato', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Talla_Zapato', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Talla_Zapato', array('class' => 'form-control form-control-sm', 'maxlength' => '3', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?> 
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Talla_Overol', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Talla_Overol', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Talla_Overol', array('class' => 'form-control form-control-sm', 'maxlength' => '3', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Talla_Bata', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Talla_Bata', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Talla_Bata', array('class' => 'form-control form-control-sm', 'maxlength' => '3', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>  
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
    
$(function() {

    $("#valida_form").click(function() {

        var form = $("#Empleado-form");
        var settings = form.data('settings') ;

        var valid_loc_res = 1;
        var valid_ges = 1;
        var valid_obs = 1;
        
        var ciudad_res = $('#Empleado_Id_Ciudad_Residencia').val();
        var gen = $('#Empleado_Id_Genero').val();
        var alerg = $('#Empleado_Alergia').val();
        
        if(ciudad_res != ''){
            if(ciudad_res == <?php echo Yii::app()->params->lugar_res_bogota; ?>){
                
                var loc_res = $('#Empleado_Id_Localidad_Residencia').val();

                if(loc_res == ""){
                    $('#Empleado_Id_Localidad_Residencia_em_').html('Localidad de residencia es requerido.');
                    $('#Empleado_Id_Localidad_Residencia_em_').show();
                    valid_loc_res = 0;  
                }else{
                    $('#Empleado_Id_Localidad_Residencia_em_').html('');
                    $('#Empleado_Id_Localidad_Residencia_em_').hide();    
                }
            }
        }

        if(gen != ''){
            if(gen == <?php echo Yii::app()->params->genero_fem; ?>){
                var ges = $('#Empleado_Es_Gestante').val();

                if(ges == ""){
                    $('#Empleado_Es_Gestante_em_').html('Gestante ? es requerido.');
                    $('#Empleado_Es_Gestante_em_').show();
                    valid_ges = 0;  
                }else{
                    $('#Empleado_Es_Gestante_em_').html('');
                    $('#Empleado_Es_Gestante_em_').hide();    
                }
            }
        }

        if(alerg != ''){
            if(alerg == 1){
                
                var obs = $('#Empleado_Observaciones').val();

                if(obs == ""){
                    $('#Empleado_Observaciones_em_').html('Observaciones (alergia) es requerido.');
                    $('#Empleado_Observaciones_em_').show();
                    valid_obs = 0;  
                }else{
                    $('#Empleado_Observaciones_em_').html('');
                    $('#Empleado_Observaciones_em_').hide();    
                }
            }
        }

        settings.submitting = true ;
            $.fn.yiiactiveform.validate(form, function(messages) {
            if($.isEmptyObject(messages)) {
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                if(valid_loc_res == 1 && valid_ges == 1 && valid_obs == 1){
                    //se envia el form
                    form.submit();
                    loadershow();
                }else{

                    if(ciudad_res != ''){
                        if(ciudad_res == <?php echo Yii::app()->params->lugar_res_bogota; ?>){
                            
                            var loc_res = $('#Empleado_Id_Localidad_Residencia').val();

                            if(loc_res == ""){
                                $('#Empleado_Id_Localidad_Residencia_em_').html('Localidad de residencia es requerido.');
                                $('#Empleado_Id_Localidad_Residencia_em_').show();
                                valid_loc_res = 0;  
                            }else{
                                $('#Empleado_Id_Localidad_Residencia_em_').html('');
                                $('#Empleado_Id_Localidad_Residencia_em_').hide();    
                            }
                        }
                    }

                    if(gen != ''){
                        if(gen == <?php echo Yii::app()->params->genero_fem; ?>){
                            var ges = $('#Empleado_Es_Gestante').val();

                            if(ges == ""){
                                $('#Empleado_Es_Gestante_em_').html('Gestante ? es requerido.');
                                $('#Empleado_Es_Gestante_em_').show();
                                valid_ges = 0;  
                            }else{
                                $('#Empleado_Es_Gestante_em_').html('');
                                $('#Empleado_Es_Gestante_em_').hide();    
                            }
                        }
                    }

                    if(alerg != ''){
                        if(alerg == 1){
                            
                            var obs = $('#Empleado_Observaciones').val();

                            if(obs == ""){
                                $('#Empleado_Observaciones_em_').html('Observaciones (alergia) es requerido.');
                                $('#Empleado_Observaciones_em_').show();
                                valid_obs = 0;  
                            }else{
                                $('#Empleado_Observaciones_em_').html('');
                                $('#Empleado_Observaciones_em_').hide();    
                            }
                        }
                    }

                    settings.submitting = false ;   
                }
              

          } else {
                settings = form.data('settings'),
                    $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                if(ciudad_res != ''){
                    if(ciudad_res == <?php echo Yii::app()->params->lugar_res_bogota; ?>){
                        
                        var loc_res = $('#Empleado_Id_Localidad_Residencia').val();

                        if(loc_res == ""){
                            $('#Empleado_Id_Localidad_Residencia_em_').html('Localidad de residencia es requerido.');
                            $('#Empleado_Id_Localidad_Residencia_em_').show();
                            valid_loc_res = 0;  
                        }else{
                            $('#Empleado_Id_Localidad_Residencia_em_').html('');
                            $('#Empleado_Id_Localidad_Residencia_em_').hide();    
                        }
                    }
                }

                if(gen != ''){
                    if(gen == <?php echo Yii::app()->params->genero_fem; ?>){
                        var ges = $('#Empleado_Es_Gestante').val();

                        if(ges == ""){
                            $('#Empleado_Es_Gestante_em_').html('Gestante ? es requerido.');
                            $('#Empleado_Es_Gestante_em_').show();
                            valid_ges = 0;  
                        }else{
                            $('#Empleado_Es_Gestante_em_').html('');
                            $('#Empleado_Es_Gestante_em_').hide();    
                        }
                    }
                }

                if(alerg != ''){
                    if(alerg == 1){
                        
                        var obs = $('#Empleado_Observaciones').val();

                        if(obs == ""){
                            $('#Empleado_Observaciones_em_').html('Observaciones (alergia) es requerido.');
                            $('#Empleado_Observaciones_em_').show();
                            valid_obs = 0;  
                        }else{
                            $('#Empleado_Observaciones_em_').html('');
                            $('#Empleado_Observaciones_em_').hide();    
                        }
                    }
                }

                settings.submitting = false ;
          }
        });
    });

});

//calculo de edad a partir de la fecha de nacimiento
$("#Empleado_Fecha_Nacimiento").change(function () {
    var fn = this.value; 

    if(fn != ""){
        $("#Empleado_edad").val(calcularEdad(fn));  
    }else{
        $("#Empleado_edad").val('N/A');    
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

//se determina si se debe pedir la localidad de residencias
$("#Empleado_Id_Ciudad_Residencia").change(function () {
    var lugar_res = this.value;

    if(lugar_res == <?php echo Yii::app()->params->lugar_res_bogota; ?>){
        $("#loc_res").show();
    }else{
        $("#loc_res").hide();
        $("#Empleado_Id_Localidad_Residencia").val('');
    }

}); 

//se determina si el genero es femenino para saber si es gestante
$("#Empleado_Id_Genero").change(function () {
    var genero = this.value;

    if(genero == <?php echo Yii::app()->params->genero_fem; ?>){
        $("#ges").show();
    }else{
        $("#ges").hide();
        $("#Empleado_Es_Gestante").val('');
    }

});

//se determina si sufre alergias para mostrar el campo de observaciones
$("#Empleado_Alergia").change(function () {
    var alerg = this.value;

    if(alerg == 1){
        $("#obs_alerg").show();
    }else{
        $("#obs_alerg").hide();
        $("#Empleado_Observaciones").val('');
    }

});

</script>

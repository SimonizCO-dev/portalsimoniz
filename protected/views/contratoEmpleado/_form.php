<?php
/* @var $this ContratoEmpleadoController */
/* @var $model ContratoEmpleado */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contrato-empleado-form',
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
    <div class="col-sm-8">
        <div class="form-group">
            <input type="hidden" id="fecha_ingreso_min" value="<?php echo $fecha_ingreso_min; ?>">
            <?php echo $form->label($model,'Id_Empleado'); ?>
            <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($e).'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->label($model,'Id_Empresa', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Empresa', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Empresa]',
                    'id'=>'ContratoEmpleado_Id_Empresa',
                    'data'=>$lista_empresas,
                    'value' => $model->Id_Empresa,
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
            <?php echo $form->label($model,'Id_Unidad_Gerencia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Unidad_Gerencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Unidad_Gerencia]',
                    'id'=>'ContratoEmpleado_Id_Unidad_Gerencia',
                    'data'=>$lista_ug,
                    'value' => $model->Id_Unidad_Gerencia,
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
    		<?php echo $form->label($model,'Id_Area', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Area', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Area]',
                    'id'=>'ContratoEmpleado_Id_Area',
                    'data'=>$lista_areas,
                    'value' => $model->Id_Area,
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
            <?php echo $form->label($model,'Id_Subarea', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Subarea', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Subarea]',
                    'id'=>'ContratoEmpleado_Id_Subarea',
                    'data'=>$lista_subareas,
                    'value' => $model->Id_Subarea,
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
            <?php echo $form->label($model,'Id_Cargo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Cargo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Cargo]',
                    'id'=>'ContratoEmpleado_Id_Cargo',
                    'data'=>$lista_cargos,
                    'value' => $model->Id_Cargo,
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
            <?php echo $form->label($model,'Id_Centro_Costo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Centro_Costo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Centro_Costo]',
                    'id'=>'ContratoEmpleado_Id_Centro_Costo',
                    'data'=>$lista_cc,
                    'value' => $model->Id_Centro_Costo,
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
            <?php echo $form->label($model,'Id_Turno', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Turno', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Turno]',
                    'id'=>'ContratoEmpleado_Id_Turno',
                    'data'=>$lista_turnos,
                    'value' => $model->Id_Turno,
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
            <?php echo $form->label($model,'Fecha_Ingreso', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Ingreso', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Fecha_Ingreso', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Salario', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Salario', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->numberField($model,'Salario', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Salario_Flexible', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Salario_Flexible', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados2 = Yii::app()->params->estados2; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Salario_Flexible]',
                    'id'=>'ContratoEmpleado_Salario_Flexible',
                    'data'=>$estados2,
                    'value' => $model->Salario_Flexible,
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
            <?php echo $form->label($model,'Id_Con_Ex_Ocup', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Con_Ex_Ocup', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Con_Ex_Ocup]',
                    'id'=>'ContratoEmpleado_Id_Con_Ex_Ocup',
                    'data'=>$lista_concep_exa_ocup,
                    'value' => $model->Id_Con_Ex_Ocup,
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
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Restricciones', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Restricciones', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textArea($model,'Restricciones',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Grupo]',
                    'id'=>'ContratoEmpleado_Id_Grupo',
                    'data'=>$lista_grupos,
                    'value' => $model->Id_Grupo,
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
            <?php echo $form->label($model,'Id_Trab_Esp', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Trab_Esp', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ContratoEmpleado[Id_Trab_Esp]',
                    'id'=>'ContratoEmpleado_Id_Trab_Esp',
                    'data'=>$lista_trabajos_esp,
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

<?php if(!$model->isNewRecord){ ?>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>

<?php } ?>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/view&id='.$e; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>


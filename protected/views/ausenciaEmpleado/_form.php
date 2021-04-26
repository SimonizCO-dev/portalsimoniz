<?php
/* @var $this AusenciaEmpleadoController */
/* @var $model AusenciaEmpleado */
/* @var $form CActiveForm */

if($model->Horas == 0.0){
	$model->Horas = 0;
}

if($model->Dias == ""){
    $model->Dias = 0;
}

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ausencia-empleado-form',
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
    <div class="col-sm-6">
        <div class="form-group">
            <input type="hidden" id="fecha_min" value="<?php echo $fecha_min; ?>">
            <input type="hidden" id="fecha_max" value="<?php echo date('Y-m-d') ?>">
            <?php echo $form->label($model,'Id_Empleado'); ?>
            <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($e).'</p>'; ?> 
        </div>
    </div>
   	<div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'Id_M_Ausencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_M_Ausencia'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'AusenciaEmpleado[Id_M_Ausencia]',
                    'id'=>'AusenciaEmpleado_Id_M_Ausencia',
                    'data'=>$lista_motivos,
                    'value' => $model->Id_M_Ausencia,
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
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'Cod_Soporte', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Cod_Soporte'); ?>
            <?php echo $form->textField($model,'Cod_Soporte', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
    	<div class="form-group">
            <?php echo $form->error($model,'Fecha_Inicial', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Inicial'); ?>
		    <?php echo $form->textField($model,'Fecha_Inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Fecha_Final', array('class' => 'badge badge-warning float-right')); ?>
      	     <?php echo $form->label($model,'Fecha_Final'); ?>
		    <?php echo $form->textField($model,'Fecha_Final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Dias', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Dias'); ?>
            <?php echo $form->numberField($model,'Dias', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off',  'step' => '1', 'min' => '0')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Horas', array('class' => 'badge badge-warning float-right')); ?>
          	<?php echo $form->label($model,'Horas'); ?>
		    <?php echo $form->numberField($model,'Horas', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '0.5', 'min' => '0')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Descontar', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Descontar'); ?>
            <?php $estados2 = Yii::app()->params->estados2; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'AusenciaEmpleado[Descontar]',
                    'id'=>'AusenciaEmpleado_Descontar',
                    'data'=>$estados2,
                    'value' => $model->Descontar,
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
            <?php echo $form->error($model,'Descontar_FDS', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Descontar_FDS'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'AusenciaEmpleado[Descontar_FDS]',
                    'id'=>'AusenciaEmpleado_Descontar_FDS',
                    'data'=>$estados2,
                    'value' => $model->Descontar_FDS,
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
    <div class="col-sm-6">
		<div class="form-group">
			<?php echo $form->error($model,'Observacion', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Observacion'); ?>
			<?php echo $form->textArea($model,'Observacion',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="form-group">
			<?php echo $form->error($model,'Nota', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Nota'); ?>
			<?php echo $form->textArea($model,'Nota',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
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

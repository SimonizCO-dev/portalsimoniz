<?php
/* @var $this ActividadController */
/* @var $model Actividad */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'actividad-form',
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
            <?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha'); ?>
            <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
            <?php echo $form->label($model,'Hora', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Hora', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Hora', array('class' => 'form-control form-control-sm timepicker', 'autocomplete' => 'off', 'readonly' => true, 'value' => $model->HoraAmPm($model->Hora))); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->label($model,'Pais'); ?>
          <?php echo $form->error($model,'Pais', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Actividad[Pais]',
                  'id'=>'Actividad_Pais',
                  'data'=>UtilidadesVarias::listapaises(),
                  'value' => $model->Pais,
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
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Actividad[Id_Grupo]',
                    'id'=>'Actividad_Id_Grupo',
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
    <div class="col-sm-8" id="div_tipo" style="display: none;">
        <div class="form-group">
          <?php echo $form->label($model,'Id_Tipo', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                'name'=>'Actividad[Id_Tipo]',
                'id'=>'Actividad_Id_Tipo',
                'value' => $model->Id_Tipo,
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
    <div class="col-sm-6" id="div_usuario" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Actividad[Id_Usuario]',
                    'id'=>'Actividad_Id_Usuario',
                    'value' => $model->Id_Usuario,
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
    <div class="col-sm-6" id="user_deleg" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Deleg', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Deleg', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Actividad[Id_Usuario_Deleg]',
                    'id'=>'Actividad_Id_Usuario_Deleg',
                    //'data'=>$lista_usuarios,
                    'value' => $model->Id_Usuario_Deleg,
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
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Actividad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Actividad', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textArea($model,'Actividad',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'maxlength'=>5000, 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Prioridad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Prioridad', array('class' => 'badge badge-warning float-right')); ?>
            <?php $prioridades = array(1 => 'ALTA', 2 => 'MEDIA', 3 => 'BAJA'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Actividad[Prioridad]',
                    'id'=>'Actividad_Prioridad',
                    'data'=>$prioridades,
                    'value' => $model->Prioridad,
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
            <?php echo $form->error($model,'Fecha_Finalizacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Finalizacion'); ?>
            <?php echo $form->textField($model,'Fecha_Finalizacion', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=actividad/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>		
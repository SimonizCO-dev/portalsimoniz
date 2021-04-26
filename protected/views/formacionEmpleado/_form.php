<?php
/* @var $this FormacionEmpleadoController */
/* @var $model FormacionEmpleado */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'formacion-empleado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	),
)); ?>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
          <?php echo $form->label($model,'Id_Empleado'); ?>
          <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($e).'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
        	<?php echo $form->label($model,'Fecha', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>
</div> 
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->label($model,'Entidad', array('class' => 'control-label')); ?>
        <?php echo $form->error($model,'Entidad', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->textField($model,'Entidad', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Nivel', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Nivel', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'FormacionEmpleado[Id_Nivel]',
                    'id'=>'FormacionEmpleado_Id_Nivel',
                    'data'=>$lista_niveles,
                    'value' => $model->Id_Nivel,
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
    		<?php echo $form->label($model,'Titulo_Obtenido', array('class' => 'control-label')); ?>
        <?php echo $form->error($model,'Titulo_Obtenido', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Titulo_Obtenido', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div> 
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'Soporte', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
      <input type="hidden" id="valid_sop" value="1">
      <?php echo $form->fileField($model, 'Soporte'); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/view&id='.$e; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
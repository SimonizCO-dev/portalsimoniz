<?php
/* @var $this RegImpController */
/* @var $model RegImp */
/* @var $form CA  ctiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reg-imp-form',
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

<div id="info">
  <div class="row">
  	<div class="col-sm-4">
      	<div class="form-group">
      		<?php echo $form->error($model,'Numero', array('class' => 'badge badge-warning float-right')); ?>
      		<?php echo $form->label($model,'Numero'); ?>
      		<?php echo $form->textField($model,'Numero', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      	</div>
  	</div>
    <div class="col-sm-4">
        <div class="form-group">
        	<?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
        	<?php echo $form->label($model,'Fecha'); ?>
		      <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>
  </div>
  <div class="row">
	<div class="col-sm-8">
		<div class="form-group">
			<?php echo $form->error($model,'Items', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Items'); ?>
			<?php echo $form->textArea($model,'Items',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
		</div>
	</div>
</div>
  <div class="row">
    <div class="col-sm-8">
      <div class="form-group">
        <?php echo $form->label($model,'sop', array('class' => 'control-label')); ?>
        <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
        <input type="hidden" id="valid_sop" value="1">
        <?php echo $form->fileField($model, 'sop'); ?>
      </div>
    </div>
  </div>
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
</div>

<?php $this->endWidget(); ?>

<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none; padding-bottom: 2%;">
    </div>
</div>


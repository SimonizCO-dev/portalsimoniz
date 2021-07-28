<?php
/* @var $this RegImpController */
/* @var $model RegImp */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'em-prod-form',
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
		<div class="col-sm-3">
        <div class="form-group">
          <?php echo $form->label($model,'Codigo', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Codigo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->textField($model,'Codigo', array('class' => 'form-control form-control-sm', 'maxlength' => 20, 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
	 	<div class="col-sm-9">
		  <div class="form-group">
        <?php echo $form->error($model,'Notas', array('class' => 'badge badge-warning float-right')); ?>
			  <?php echo $form->label($model,'Notas'); ?>
			  <?php echo $form->textArea($model,'Notas',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'maxlength'=>500, 'onkeyup' => 'convert_may(this)')); ?>
		  </div>
	 	</div>
</div>
<div class="row">
  <div class="col-sm-9">
    <div class="form-group">
      <?php echo $form->label($model,'sop', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
      <input type="hidden" id="valid_sop" value="0">
      <?php echo $form->fileField($model, 'sop'); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprod/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Crear</button>
    </div>
</div>


<?php $this->endWidget(); ?>
<?php
/* @var $this NetworkController */
/* @var $model Network */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'network-form',
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
			<?php echo $form->error($model,'id_red_1', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'id_red_1'); ?>
			<?php echo $form->numberField($model,'id_red_1', array('class' => 'form-control form-control-sm', 'min' => '0', 'max' => '999', 'step' => '1', 'autocomplete' => 'off')); ?>
		</div>	
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<?php echo $form->error($model,'id_red_2', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'id_red_2'); ?>
			<?php echo $form->numberField($model,'id_red_2', array('class' => 'form-control form-control-sm', 'min' => '0', 'max' => '999', 'step' => '1', 'autocomplete' => 'off')); ?>
		</div>	
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<?php echo $form->error($model,'Segment', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Segment'); ?>
			<?php echo $form->numberField($model,'Segment', array('class' => 'form-control form-control-sm', 'min' => '0', 'max' => '999', 'step' => '1', 'autocomplete' => 'off')); ?>
		</div>	
	</div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=network/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
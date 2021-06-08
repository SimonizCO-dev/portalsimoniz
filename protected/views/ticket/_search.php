<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'Id_Ticket'); ?>
		<?php echo $form->textField($model,'Id_Ticket'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_Grupo'); ?>
		<?php echo $form->textField($model,'Id_Grupo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_Tipo'); ?>
		<?php echo $form->textField($model,'Id_Tipo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Solicitud'); ?>
		<?php echo $form->textArea($model,'Solicitud',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fecha_Asig'); ?>
		<?php echo $form->textField($model,'Fecha_Asig'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_Usuario_Asig'); ?>
		<?php echo $form->textField($model,'Id_Usuario_Asig'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fecha_Cierre'); ?>
		<?php echo $form->textField($model,'Fecha_Cierre'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Calificacion'); ?>
		<?php echo $form->textField($model,'Calificacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fecha_Calificacion'); ?>
		<?php echo $form->textField($model,'Fecha_Calificacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
		<?php echo $form->textField($model,'Id_Usuario_Creacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fecha_Creacion'); ?>
		<?php echo $form->textField($model,'Fecha_Creacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?>
		<?php echo $form->textField($model,'Id_Usuario_Actualizacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fecha_Actualizacion'); ?>
		<?php echo $form->textField($model,'Fecha_Actualizacion'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Estado'); ?>
		<?php echo $form->textField($model,'Estado'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
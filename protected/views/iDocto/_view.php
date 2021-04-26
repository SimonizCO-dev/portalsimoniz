<?php
/* @var $this IDoctoController */
/* @var $data IDocto */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id), array('view', 'id'=>$data->Id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Tipo_Docto')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Tipo_Docto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Tercero')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Tercero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Consecutivo')); ?>:</b>
	<?php echo CHtml::encode($data->Consecutivo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Referencia')); ?>:</b>
	<?php echo CHtml::encode($data->Referencia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Vlr_Total')); ?>:</b>
	<?php echo CHtml::encode($data->Vlr_Total); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Estado')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Estado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Usuario_Creacion')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Usuario_Creacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Usuario_Actualizacion')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Usuario_Actualizacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Creacion')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Creacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Actualizacion')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Actualizacion); ?>
	<br />

	*/ ?>

</div>
<?php
/* @var $this TicketController */
/* @var $data Ticket */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Ticket')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id_Ticket), array('view', 'id'=>$data->Id_Ticket)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Grupo')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Grupo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Tipo')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Tipo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Solicitud')); ?>:</b>
	<?php echo CHtml::encode($data->Solicitud); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Usuario_Sol')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Usuario_Sol); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Asig')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Asig); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Usuario_Asig')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Usuario_Asig); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Cierre')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Cierre); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Calificacion')); ?>:</b>
	<?php echo CHtml::encode($data->Calificacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Calificacion')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Calificacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Usuario_Creacion')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Usuario_Creacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Creacion')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Creacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Usuario_Actualizacion')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Usuario_Actualizacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Actualizacion')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Actualizacion); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Estado')); ?>:</b>
	<?php echo CHtml::encode($data->Estado); ?>
	<br />

	*/ ?>

</div>
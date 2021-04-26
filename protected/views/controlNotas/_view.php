<?php
/* @var $this ControlNotasController */
/* @var $data ControlNotas */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Control')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id_Control), array('view', 'id'=>$data->Id_Control)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Cliente')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Cliente); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Nota')); ?>:</b>
	<?php echo CHtml::encode($data->Nota); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Factura')); ?>:</b>
	<?php echo CHtml::encode($data->Factura); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Valor_Factura')); ?>:</b>
	<?php echo CHtml::encode($data->Valor_Factura); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Porc_Desc')); ?>:</b>
	<?php echo CHtml::encode($data->Porc_Desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Valor_Descuento')); ?>:</b>
	<?php echo CHtml::encode($data->Valor_Descuento); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Factura')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Factura); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Pago')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Pago); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Dias_Pago')); ?>:</b>
	<?php echo CHtml::encode($data->Dias_Pago); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Observaciones')); ?>:</b>
	<?php echo CHtml::encode($data->Observaciones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Respuesta')); ?>:</b>
	<?php echo CHtml::encode($data->Respuesta); ?>
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
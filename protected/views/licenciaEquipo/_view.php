<?php
/* @var $this LicenciaEquipoController */
/* @var $data LicenciaEquipo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Lic_Equ')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id_Lic_Equ), array('view', 'id'=>$data->Id_Lic_Equ)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Equipo')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Equipo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Licencia')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Licencia); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Estado')); ?>:</b>
	<?php echo CHtml::encode($data->Estado); ?>
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

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('Fecha_Actualizacion')); ?>:</b>
	<?php echo CHtml::encode($data->Fecha_Actualizacion); ?>
	<br />

	*/ ?>

</div>
<?php
/* @var $this EmpEquipoController */
/* @var $data EmpEquipo */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Emp_Equ')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Id_Emp_Equ), array('view', 'id'=>$data->Id_Emp_Equ)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Equipo')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Equipo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Id_Emp')); ?>:</b>
	<?php echo CHtml::encode($data->Id_Emp); ?>
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
<?php
/* @var $this EmpEquipoController */
/* @var $model EmpEquipo */

$this->breadcrumbs=array(
	'Emp Equipos'=>array('index'),
	$model->Id_Emp_Equ,
);

$this->menu=array(
	array('label'=>'List EmpEquipo', 'url'=>array('index')),
	array('label'=>'Create EmpEquipo', 'url'=>array('create')),
	array('label'=>'Update EmpEquipo', 'url'=>array('update', 'id'=>$model->Id_Emp_Equ)),
	array('label'=>'Delete EmpEquipo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id_Emp_Equ),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EmpEquipo', 'url'=>array('admin')),
);
?>

<h1>View EmpEquipo #<?php echo $model->Id_Emp_Equ; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id_Emp_Equ',
		'Id_Equipo',
		'Id_Emp',
		'Estado',
		'Id_Usuario_Creacion',
		'Fecha_Creacion',
		'Id_Usuario_Actualizacion',
		'Fecha_Actualizacion',
	),
)); ?>

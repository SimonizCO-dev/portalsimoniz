<?php
/* @var $this LicenciaEquipoController */
/* @var $model LicenciaEquipo */

$this->breadcrumbs=array(
	'Licencia Equipos'=>array('index'),
	$model->Id_Lic_Equ,
);

$this->menu=array(
	array('label'=>'List LicenciaEquipo', 'url'=>array('index')),
	array('label'=>'Create LicenciaEquipo', 'url'=>array('create')),
	array('label'=>'Update LicenciaEquipo', 'url'=>array('update', 'id'=>$model->Id_Lic_Equ)),
	array('label'=>'Delete LicenciaEquipo', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id_Lic_Equ),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LicenciaEquipo', 'url'=>array('admin')),
);
?>

<h1>View LicenciaEquipo #<?php echo $model->Id_Lic_Equ; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id_Lic_Equ',
		'Id_Equipo',
		'Id_Licencia',
		'Estado',
		'Id_Usuario_Creacion',
		'Fecha_Creacion',
		'Id_Usuario_Actualizacion',
		'Fecha_Actualizacion',
	),
)); ?>

<?php
/* @var $this NetworkController */
/* @var $model Network */

$this->breadcrumbs=array(
	'Networks'=>array('index'),
	$model->Id,
);

$this->menu=array(
	array('label'=>'List Network', 'url'=>array('index')),
	array('label'=>'Create Network', 'url'=>array('create')),
	array('label'=>'Update Network', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Delete Network', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Network', 'url'=>array('admin')),
);
?>

<h1>View Network #<?php echo $model->Id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id',
		'Network',
		'Segment',
		'Host',
		'Notas',
		'Estado',
		'Id_Usuario_Creacion',
		'Fecha_Creacion',
		'Id_Usuario_Actualizacion',
		'Fecha_Actualizacion',
	),
)); ?>

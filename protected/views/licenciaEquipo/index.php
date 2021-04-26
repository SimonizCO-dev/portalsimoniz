<?php
/* @var $this LicenciaEquipoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Licencia Equipos',
);

$this->menu=array(
	array('label'=>'Create LicenciaEquipo', 'url'=>array('create')),
	array('label'=>'Manage LicenciaEquipo', 'url'=>array('admin')),
);
?>

<h1>Licencia Equipos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

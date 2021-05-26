<?php
/* @var $this EmpEquipoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Emp Equipos',
);

$this->menu=array(
	array('label'=>'Create EmpEquipo', 'url'=>array('create')),
	array('label'=>'Manage EmpEquipo', 'url'=>array('admin')),
);
?>

<h1>Emp Equipos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

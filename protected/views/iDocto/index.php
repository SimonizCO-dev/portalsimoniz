<?php
/* @var $this IDoctoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Idoctos',
);

$this->menu=array(
	array('label'=>'Create IDocto', 'url'=>array('create')),
	array('label'=>'Manage IDocto', 'url'=>array('admin')),
);
?>

<h1>Idoctos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

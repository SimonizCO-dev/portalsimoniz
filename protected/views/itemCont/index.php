<?php
/* @var $this ItemContController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Item Conts',
);

$this->menu=array(
	array('label'=>'Create ItemCont', 'url'=>array('create')),
	array('label'=>'Manage ItemCont', 'url'=>array('admin')),
);
?>

<h1>Item Conts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

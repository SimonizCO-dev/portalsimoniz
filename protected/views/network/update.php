<?php
/* @var $this NetworkController */
/* @var $model Network */

$this->breadcrumbs=array(
	'Networks'=>array('index'),
	$model->Id=>array('view','id'=>$model->Id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Network', 'url'=>array('index')),
	array('label'=>'Create Network', 'url'=>array('create')),
	array('label'=>'View Network', 'url'=>array('view', 'id'=>$model->Id)),
	array('label'=>'Manage Network', 'url'=>array('admin')),
);
?>

<h1>Update Network <?php echo $model->Id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
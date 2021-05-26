<?php
/* @var $this EmpEquipoController */
/* @var $model EmpEquipo */

$this->breadcrumbs=array(
	'Emp Equipos'=>array('index'),
	$model->Id_Emp_Equ=>array('view','id'=>$model->Id_Emp_Equ),
	'Update',
);

$this->menu=array(
	array('label'=>'List EmpEquipo', 'url'=>array('index')),
	array('label'=>'Create EmpEquipo', 'url'=>array('create')),
	array('label'=>'View EmpEquipo', 'url'=>array('view', 'id'=>$model->Id_Emp_Equ)),
	array('label'=>'Manage EmpEquipo', 'url'=>array('admin')),
);
?>

<h1>Update EmpEquipo <?php echo $model->Id_Emp_Equ; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
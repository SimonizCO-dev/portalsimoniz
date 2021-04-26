<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */
/* @var $form CActiveForm */
?>

<h4>Asignación de elementos / herramientas por empleado</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Empleado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 

$array_areas =Yii::app()->user->getState('array_areas');
$array_subareas =Yii::app()->user->getState('array_subareas');

?>

<?php if(!empty($array_areas) && !empty($array_subareas)) { ?>

<p>
Busque por # identificación, nombres o apellidos (Recuerde que el empleado debe contar con un contrato activo):
</p>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'empleado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'empleado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'empleado'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Empleado_empleado',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('empleado/SearchEmpleadoAsigEnt'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Empleado_empleado"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Empleado_empleado\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Consultar</button>
    </div>
</div>

<?php } else { ?>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene áreas y/o subáreas asignadas, contacte al administrador del sistema.
</div>

<?php } ?>   

<?php $this->endWidget(); ?>

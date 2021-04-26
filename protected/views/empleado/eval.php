<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */
/* @var $form CActiveForm */
?>

<h3>Evaluación por empleado</h3>

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

?>

<p>
Busque por # identificación, nombres o apellidos (Recuerde que el empleado debe contar con un contrato activo):
</p>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'empleado', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'empleado'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[empleado]',
                    'id'=>'Empleado_empleado',
                    'data'=>$lista_empleados,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>

<div class="btn-group" style="padding-bottom: 2%;">
    <button type="button" class="btn btn-success"  onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=evalEmp/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Consultar</button>
</div>

<?php $this->endWidget(); ?>

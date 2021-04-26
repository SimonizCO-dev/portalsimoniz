<?php
/* @var $this ContratoEmpleadoController */
/* @var $model ContratoEmpleado */
/* @var $form CActiveForm */
?>

<h4>Resumen contrato de empleado</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contrato-empleado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Empleado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Empleado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($e).'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Empresa', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Empresa', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$model->idempresa->Descripcion.'</p>'; ?> 
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Unidad_Gerencia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Unidad_Gerencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$unidad_gerencia.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Area', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Area', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$area.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Subarea', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Subarea', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$subarea.'</p>'; ?> 
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Cargo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Cargo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$cargo.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Centro_Costo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Centro_Costo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$centro_costo.'</p>'; ?> 
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Ingreso', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Ingreso', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Ingreso).'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Salario', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Salario', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.number_format($model->Salario, 0).'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Salario_Flexible', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Salario_Flexible', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$salario_flexible.'</p>'; ?>  
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_M_Retiro', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_M_Retiro', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$model->idmretiro->Dominio.'</p>'; ?> 
        </div>
    </div>    
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Retiro', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Retiro', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Retiro).'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Observacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Observacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$model->Observacion.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Liquidacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Liquidacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php if($model->Fecha_Liquidacion !=""){ $fl = UtilidadesVarias::textofecha($model->Fecha_Liquidacion);}else{ $fl = 'SIN ASIGNAR';} ?>
            <?php echo '<p>'.$fl.'</p>'; ?> 
        </div>
    </div> 
</div>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/view&id='.$e; ?>';"><i class="fa fa-reply"></i> Volver</button>
    </div>
</div>

<?php $this->endWidget(); ?>
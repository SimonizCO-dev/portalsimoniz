<?php
/* @var $this PromocionController */
/* @var $model Promocion */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'elemento-sugerido-form',
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


<div class="row">
	<div class="col-sm-8">
    	<div class="form-group">
    		<?php echo $form->label($model,'Id_Sugerido', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Sugerido', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->hiddenField($model,'Id_Sugerido', array('class' => 'form-control form-control-sm')); ?>
		    <?php echo '<p>'.UtilidadesSugerido::sugerido($model->Id_Sugerido).'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->label($model,'Cantidad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Cantidad', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->numberField($model,'Cantidad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off',  'step' => '1', 'min' => '1', 'max' => '10')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Id_A_Elemento', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_A_Elemento', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'Id_A_Elemento', array('class' => 'form-control form-control-sm')); ?>
			<?php echo '<p>'.UtilidadesSugerido::areasubareaelemento($model->Id_A_Elemento).'</p>'; ?>
        </div>
    </div>
	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ElementoSugerido[Estado]',
                    'id'=>'ElementoSugerido_Estado',
                    'data'=>$estados,
                    'value' => $model->Estado,
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

<?php if(!$model->isNewRecord){ ?>

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

<?php } ?>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=sugerido/update&id='.$model->Id_Sugerido; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div> 

<?php $this->endWidget(); ?>
<?php
/* @var $this CPtjRecaudosController */
/* @var $model CPtjRecaudos */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cptj-recaudos-form',
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
   	<div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'TIPO', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'TIPO'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'CPtjRecaudos[TIPO]',
                    'id'=>'CPtjRecaudos_TIPO',
                    'data'=> $lista_tipos,
                    'value' => $model->TIPO,
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
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'PORCENTAJE', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'PORCENTAJE'); ?>
            <?php echo $form->numberField($model,'PORCENTAJE', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '0.01', 'min' => '0.01', 'placeholder' => '0,01')); ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'DIA_INICIAL', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'DIA_INICIAL'); ?>
            <?php echo $form->numberField($model,'DIA_INICIAL', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '1', 'min' => '0', 'max' => '999999', 'placeholder' => '0')); ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'DIA_FINAL', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'DIA_FINAL'); ?>
            <?php echo $form->numberField($model,'DIA_FINAL', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '1', 'min' => '0', 'max' => '999999', 'placeholder' => '0')); ?>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cptjrecaudos/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>
	
<?php $this->endWidget(); ?>

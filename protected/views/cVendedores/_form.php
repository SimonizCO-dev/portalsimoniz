<?php
/* @var $this CVendedoresController */
/* @var $model CVendedores */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cvendedores-form',
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
   	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ROWID'); ?>
            <p><?php echo $model->ROWID; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'NIT_VENDEDOR'); ?>
            <p><?php echo $model->NIT_VENDEDOR; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'NOMBRE_VENDEDOR'); ?>
            <p><?php echo $model->NOMBRE_VENDEDOR; ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'EMAIL', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'EMAIL'); ?>
            <?php if($model->EMAIL == ""){ $EMAIL = "NO ASIGNADO"; }else{ $EMAIL = $model->EMAIL; } ?>
            <p><?php echo $EMAIL; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'EMAIL_PERSONAL', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'EMAIL_PERSONAL'); ?>
            <?php echo $form->textField($model,'EMAIL_PERSONAL', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_min(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'TELEFONO', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'TELEFONO'); ?>
            <?php if($model->TELEFONO == ""){ $TELEFONO = "NO ASIGNADO"; }else{ $TELEFONO = $model->TELEFONO; } ?>
            <p><?php echo $TELEFONO; ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'CIUDAD', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'CIUDAD'); ?>
            <?php if($model->CIUDAD == ""){ $CIUDAD = "NO ASIGNADO"; }else{ $CIUDAD = $model->CIUDAD; } ?>
            <p><?php echo $CIUDAD; ?></p>
        </div>
    </div>
   	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ID_VENDEDOR'); ?>
            <p><?php echo $model->ID_VENDEDOR; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'RECIBO'); ?>
            <p><?php echo $model->RECIBO; ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'RUTA'); ?>
            <p><?php echo $model->RUTA; ?></p>
        </div>
    </div>	
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'NOMBRE_RUTA'); ?>
            <p><?php echo $model->NOMBRE_RUTA; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'PORTAFOLIO'); ?>
            <p><?php echo $model->PORTAFOLIO; ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'NIT_SUPERVISOR'); ?>
            <p><?php echo $model->NIT_SUPERVISOR; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'NOMBRE_SUPERVISOR'); ?>
            <p><?php echo $model->NOMBRE_SUPERVISOR; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'TIPO', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'TIPO'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'CVendedores[TIPO]',
                    'id'=>'CVendedores_TIPO',
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
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ESTADO'); ?>
            <p><?php echo $model->ESTADO; ?></p>
        </div>
    </div>   
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cVendedores/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
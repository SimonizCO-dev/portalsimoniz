<?php
/* @var $this WipController */
/* @var $model Wip */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'wip-form',
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
            <?php echo $form->label($model,'WIP'); ?>
            <?php echo '<p>'.$model->WIP.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'CADENA', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'CADENA'); ?>
            <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Wip[CADENA]',
                  'id'=>'Wip_CADENA',
                  'data'=> $lista_cadenas,
                  'value'=>$model->CADENA,
                  'htmlOptions'=>array(
                  ),
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
            <?php echo $form->label($model,'FECHA_SOLICITUD_WIP'); ?>
            <?php echo '<p>'.UtilidadesVarias::textofecha($model->FECHA_SOLICITUD_WIP).'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'FECHA_ENTREGA_WIP'); ?>
            <?php echo '<p>'.UtilidadesVarias::textofecha($model->FECHA_ENTREGA_WIP).'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'RESPONSABLE', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'RESPONSABLE'); ?>
            <?php echo $form->textField($model,'RESPONSABLE', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>   
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'OBSERVACIONES', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'OBSERVACIONES'); ?>
            <?php echo $form->textField($model,'OBSERVACIONES', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">  
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ID_ITEM'); ?>
            <?php echo '<p>'.$model->ID_ITEM.'</p>'; ?>
        </div>
    </div>
     <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'DESCRIPCION'); ?>
            <?php echo '<p>'.$model->DESCRIPCION.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ESTADO_COMERCIAL'); ?>
            <?php echo '<p>'.$model->ESTADO_COMERCIAL.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">  
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'UN'); ?>
            <?php echo '<p>'.$model->UN.'</p>'; ?>
        </div>
    </div>
     <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'SUB_MARCA'); ?>
            <?php echo '<p>'.$model->SUB_MARCA.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'FAMILIA'); ?>
            <?php echo '<p>'.$model->FAMILIA.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">  
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'SUB_FAMILIA'); ?>
            <?php echo '<p>'.$model->SUB_FAMILIA.'</p>'; ?>
        </div>
    </div>
     <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'GRUPO'); ?>
            <?php echo '<p>'.$model->GRUPO.'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ORACLE'); ?>
            <?php echo '<p>'.$model->ORACLE.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ESTADO_OP'); ?>
            <?php if($model->ESTADO_OP == ""){ echo '<p>-</p>'; } else { echo '<p>'.$model->ESTADO_OP.'</p>'; } ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'INVENTARIO_TOTAL'); ?>
            <?php if($model->INVENTARIO_TOTAL == ""){ echo '<p>-</p>'; } else { echo '<p>'.$model->INVENTARIO_TOTAL.'</p>'; } ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'DE_0_A_30_DIAS'); ?>
            <?php echo '<p>'.$model->DE_0_A_30_DIAS.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">  
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'DE_31_A_60_DIAS'); ?>
            <?php echo '<p>'.$model->DE_31_A_60_DIAS.'</p>'; ?>
        </div>
    </div> 
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'DE_61_A_90_DIAS'); ?>
            <?php echo '<p>'.$model->DE_61_A_90_DIAS.'</p>'; ?>
        </div>
    </div>
     <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'MAS_DE_90_DIAS'); ?>
            <?php echo '<p>'.$model->MAS_DE_90_DIAS.'</p>'; ?>
        </div>
    </div>
</div>
<div class="row">  
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'CANT_A_ARMAR', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'CANT_A_ARMAR'); ?>
            <?php echo $form->numberField($model,'CANT_A_ARMAR', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div>
    </div> 
    <div class="col-sm-4">
    	<div class="form-group">
         	<?php echo $form->label($model,'CANT_OC_AL_DIA'); ?>
            <?php if($model->CANT_OC_AL_DIA == ""){ echo '<p>-</p>'; } else { echo '<p>'.$model->CANT_OC_AL_DIA.'</p>'; } ?>
        </div>
    </div>
     <div class="col-sm-4">
    	<div class="form-group">
         	<?php echo $form->label($model,'CANT_PENDIENTE'); ?>
            <?php if($model->CANT_PENDIENTE == ""){ echo '<p>-</p>'; } else { echo '<p>'.$model->CANT_PENDIENTE.'</p>'; } ?>   
        </div>
    </div>
</div>
<div class="row">  
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'DIAS_VENCIMIENTO'); ?>
            <?php if($model->DIAS_VENCIMIENTO == ""){ echo '<p>-</p>'; } else { echo '<p>'.$model->DIAS_VENCIMIENTO.'</p>'; } ?>   
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'REDISTRIBUCION', array('class' => 'badge badge-warning float-right')); ?>
        	<?php echo $form->label($model,'REDISTRIBUCION'); ?>
        	<?php echo $form->textField($model,'REDISTRIBUCION', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>	   
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'PTM', array('class' => 'badge badge-warning float-right')); ?>
        	<?php echo $form->label($model,'PTM'); ?>
            <?php echo $form->numberField($model,'PTM', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div> 
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=wip/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> <?php echo 'Guardar'; ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
<?php
/* @var $this HerramientaController */
/* @var $model Herramienta */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'control-recibos-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableClientValidation'=>true,
  'clientOptions'=>array(
    'validateOnSubmit'=>true,
  ),
  'htmlOptions' => array(
    'enctype' => 'multipart/form-data'
  ),
)); ?>


<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->label($model,'Recibo'); ?><br>
        <?php echo $form->error($model,'Recibo', array('class' => 'badge badge-warning float-right')); ?>
        
        <?php echo $form->fileField($model, 'Recibo'); ?>
        </div>
    </div>
    <div class="col-sm-8" id="vista_previa">
      <div class="form-group">
        <label>Vista previa</label>
        <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
        <input type="hidden" id="valid_sop" value="0">
        <img id="img" class="img-responsive"/>
      </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=controlRecibos/modrec'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>


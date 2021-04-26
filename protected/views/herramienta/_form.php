
<?php
/* @var $this HerramientaController */
/* @var $model Herramienta */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'herramienta-form',
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
    		<?php echo $form->label($model,'Nombre', array('class' => 'control-label')); ?>
        <?php echo $form->error($model,'Nombre', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Nombre', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
     </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Imagen', array('class' => 'badge badge-warning float-right')); ?>
      <div class="badge badge-warning float-right" id="error_file" style="display: none;"></div>
      <input type="hidden" id="valid_file" value="0">
      <?php echo $form->label($model,'Imagen'); ?>
      <br>
      <?php echo $form->fileField($model, 'Imagen'); ?>
    </div>
  </div>
	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Herramienta[Estado]',
                    'id'=>'Herramienta_Estado',
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
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Descripcion', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Descripcion', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textArea($model,'Descripcion',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
  <div class="col-sm-6" id="vista_previa" style="display: none;">
    <div class="form-group">
      <label>Vista previa</label><br>
      <img id="img" class="img-fluid"/>
      </div>
  </div>
</div>
  
<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=herramienta/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
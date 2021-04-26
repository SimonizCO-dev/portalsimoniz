<?php
/* @var $this AnexoContController */
/* @var $model AnexoCont */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'anexo-cont-form',
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
          <?php echo $form->error($model,'Id_Contrato', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Id_Contrato'); ?>
    			<?php echo $form->hiddenField($model,'Id_Contrato', array('class' => 'form-control form-control-sm', 'value' => $c)); ?>
    			<?php  
    				$mc = new Cont;
    				$desc_cont = $mc->Desccontrato($c);
    				echo '<p>'.$desc_cont.'</p>';
    			?>          
        </div>
    </div>
  	<div class="col-sm-4">
      	<div class="form-group">
  			  <?php echo $form->error($model,'Titulo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Titulo'); ?>
      		<?php echo $form->textField($model,'Titulo', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      	</div>
  	</div>
</div>
<div class="row">
	  <div class="col-sm-8">
    	<div class="form-group">
          <?php echo $form->error($model,'Descripcion', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Descripcion'); ?>
          <?php echo $form->textArea($model,'Descripcion',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
    	</div>
  	</div>
    <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Estado'); ?>
          <?php $estados = Yii::app()->params->estados; ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'AnexoCont[Estado]',
                  'id'=>'AnexoCont_Estado',
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
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'sop', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
      <input type="hidden" id="valid_sop" value="0">
      <?php echo $form->fileField($model, 'sop'); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/view&id='.$c; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
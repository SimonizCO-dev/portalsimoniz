<?php
/* @var $this NetworkController */
/* @var $model Network */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'network-form',
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
  <div class="col-sm-12">
    <div class="form-group">
      <?php echo $form->label($model,'id_equipo'); ?>
      <?php echo '<p>'.UtilidadesVarias::descequipo($e).'</p>'; ?> 
    </div>   
  </div>
</div>
<div class="row">
	<div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'ip', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'ip'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Network[ip]',
              'id'=>'Network_ip',
              'data'=>$lista_ips_disp,
              'options'=>array(
                  'placeholder'=>'Seleccione...',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>	
	</div>
	<div class="col-sm-8">
  		<div class="form-group">
		 	<?php echo $form->error($model,'notas', array('class' => 'badge badge-warning float-right')); ?>
		 	<?php echo $form->label($model,'notas'); ?>
		 	<?php echo $form->textArea($model,'notas',array('class' => 'form-control form-control-sm', 'rows'=>1, 'cols'=>30, 'onkeyup' => 'convert_may(this)')); ?>
	  	</div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=equipo/view&id='.$e; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>


<?php
/* @var $this RegImpController */
/* @var $model RegImp */
/* @var $form CA  ctiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'em-prod-form',
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

<div id="info">
  <div class="row">
    <div class="col-sm-3">
        <div class="form-group">
          <?php echo $form->label($model,'Codigo', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Codigo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->textField($model,'Codigo', array('class' => 'form-control form-control-sm', 'maxlength' => 20, 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
  	<div class="col-sm-9">
  		<div class="form-group">
  			<?php echo $form->error($model,'Notas', array('class' => 'badge badge-warning float-right')); ?>
  			<?php echo $form->label($model,'Notas'); ?>
  			<?php echo $form->textArea($model,'Notas',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'maxlength'=>500, 'onkeyup' => 'convert_may(this)')); ?>
  		</div>
  	</div>
  </div>
  <div class="row">
    <div class="col-sm-9">
      <div class="form-group">
        <?php echo $form->label($model,'sop', array('class' => 'control-label')); ?>
        <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
        <input type="hidden" id="valid_sop" value="1">
        <?php echo $form->fileField($model, 'sop'); ?>
      </div>
    </div>
  </div>
  <div class="row">
      <div class="col-sm-3">
          <div class="form-group">
              <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
              <p><?php echo $model->idusuariocre->Nombres; ?></p>
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
              <p><?php echo $model->idusuarioact->Nombres; ?></p>
          </div>
      </div>
      <div class="col-sm-3">
          <div class="form-group">
              <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
              <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
          </div>
      </div>
  </div>
  <h5>Usuarios</h5>

  <?php

  $this->widget('zii.widgets.grid.CGridView', array(
      'id'=>'em-prod-val-grid',
      'dataProvider'=>$usuarios_val->search(),
      //'filter'=>$model,
      'pager'=>array(
          'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
      ),
      'enableSorting' => false,
      'columns'=>array(
          array(
            'name' => 'Id_Usuario',
            'value' => '$data->idusuario->Nombres',
          ),
          array(
              'name'=>'Estado',
              'type'=>'html',
              'value'=>'$data->DescEstado($data->Estado)',
          ),
          array(
              'name' => 'Fecha_Actualizacion',
              'value' => '($data->Fecha_Actualizacion == "") ? "-" : UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
          ),
          
      ),
  ));

  ?>
</div>

<?php $this->endWidget(); ?>

<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none; padding-bottom: 2%;">
    </div>
</div>

<script type="text/javascript">

$(function() {

    $("#envio_notif").click(function() {

        var opcion = confirm("Se enviara nuevamente la notificación a los usuarios que no han marcado la emisión como vista, esta seguro ? ");
        if (opcion == true) {
            showloader();
            location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprod/envionotif&id='.$model->Id_Em_Prod; ?>';   
        } 
        
    });

 });    

</script>



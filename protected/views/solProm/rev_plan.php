<?php
/* @var $this FichaItemController */
/* @var $model FichaItem */
/* @var $form CActiveForm */

?>

<script>

$(function() {

    $("#valida_form").click(function() {
      var form = $("#sol-prom-form");
      var settings = form.data('settings') ;

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this,messages,form); 
            });

            //se envia el form
            form.submit();
            loadershow();
             
          } else {
              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              settings.submitting = false ;
          }
      });
    });
});

</script>

<h4>Revisión de solicitud</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sol-prom-form',
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
  <div class="col-sm-2">
      <div class="form-group">
          <?php echo $form->error($model,'Num_Sol', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Num_Sol'); ?>
          <p><?php echo $model->Num_Sol; ?></p>
      </div>
  </div>
	<div class="col-sm-8">
    <div class="form-group">
        <?php echo $form->label($model,'Observaciones_Log', array('class' => 'control-label')); ?>
        <?php echo $form->error($model,'Observaciones_Log', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->textArea($model,'Observaciones_Log',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'maxlength'=>400, 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
</div>

<div class="row mb-4">
    <div class="col-sm-6"> 
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=solprom/update&id='.$model->Id_Sol_Prom.'&s='.$model->Estado; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php
/* @var $this VentasController */
/* @var $model Ventas */

?>

<h4>Clientes potenciales</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ventas-form',
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
      <?php echo $form->error($model,'dias', array('class' => 'badge badge-warning float-right')); ?>
  		<?php echo $form->label($model,'dias'); ?>
      <?php echo $form->numberField($model,'dias', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => 1)); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-excel"></i> Generar</button>
    </div>
</div>


<?php $this->endWidget(); ?>

<script type="text/javascript">
  
  $(function() {
    
      $("#valida_form").click(function() {

          var form = $("#ventas-form");
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

  
function resetfields(){
  $('#Ventas_dias').val('');
}

</script>

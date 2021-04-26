<?php
/* @var $this InventarioController */
/* @var $model Inventario */

?>

<h4>Items exentos de iva</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contabilidad-form',
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
      <?php echo $form->error($model,'fecha_inicial', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'fecha_inicial'); ?>
      <?php echo $form->textField($model,'fecha_inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div> 
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'fecha_final', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'fecha_final'); ?>
      <?php echo $form->textField($model,'fecha_final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
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

<script>

$(function() {
  $("#valida_form").click(function() {

      var form = $("#contabilidad-form");
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

  //variables para el lenguaje del datepicker
  $.fn.datepicker.dates['es'] = {
      days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
      daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
      daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
      months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      today: "Hoy",
      clear: "Limpiar",
      format: "yyyy-mm-dd",
      titleFormat: "MM yyyy",
      weekStart: 1
  };

  $("#Contabilidad_fecha_inicial").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#Contabilidad_fecha_final').datepicker('setStartDate', minDate);
  });

  $("#Contabilidad_fecha_final").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#Contabilidad_fecha_inicial').datepicker('setEndDate', maxDate);
  });

});

function resetfields(){
  $('#Contabilidad_fecha_inicial').val('');
  $('#Contabilidad_fecha_final').val('');
}

</script>
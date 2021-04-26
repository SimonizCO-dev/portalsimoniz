<?php
/* @var $this VentasController */
/* @var $model Ventas */
?>

<h4>Seguimiento x rutas</h4>

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
          <?php echo $form->error($model,'periodo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'periodo'); ?>
          <?php echo $form->textField($model,'periodo', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
      </div> 
    <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->label($model,'coordinador'); ?>
        <?php echo $form->error($model,'coordinador', array('class' => 'badge badge-warning float-right')); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Ventas[coordinador]',
                'id'=>'Ventas_coordinador',
                'data'=>$lista_coord,
                'value' => $model->coordinador,
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
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->label($model,'marca'); ?>
        <?php echo $form->error($model,'marca', array('class' => 'badge badge-warning float-right')); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Ventas[marca]',
                'id'=>'Ventas_marca',
                'data'=>$lista_marcas,
                'value' => $model->marca,
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

<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-excel"></i> Generar</button>
    </div>
</div>    


<?php $this->endWidget(); ?>

<script>

$(function() {

  //variables para el lenguaje del datepicker
  $.fn.datepicker.dates['es'] = {
      days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
      daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
      daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
      months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      today: "Hoy",
      clear: "Limpiar",
      format : 'yyyy-mm',
      titleFormat: "MM yyyy",
  };

  $("#Ventas_periodo").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
      format: "yyyy-mm",
      startView: "year", 
      minViewMode: "months",
  });
  
  $("#valida_form").click(function() {
      var form = $("#ventas-form");
      var settings = form.data('settings');

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
  $('#Ventas_periodo').val('');
  $('#Ventas_coordinador').val('').trigger('change');
  $('#Ventas_marca').val('').trigger('change');
}

</script>

<?php
/* @var $this VentasController */
/* @var $model Ventas */

?>

<h4>Rentabilidad de inventario por marca</h4>

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
<div class="row">
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'marca_inicial', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'marca_inicial'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Ventas[marca_inicial]',
                  'id'=>'Ventas_marca_inicial',
                  'data'=> $lista_marcas,
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
          <?php echo $form->error($model,'marca_final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'marca_final'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Ventas[marca_final]',
                  'id'=>'Ventas_marca_final',
                  'data'=> $lista_marcas,
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
  			<?php echo $form->error($model,'opcion_exp', array('class' => 'badge badge-warning float-right')); ?>
      	<?php echo $form->label($model,'opcion_exp'); ?><br>
  			<?php 
  				echo $form->radioButtonList($model,'opcion_exp',
  			    	array('1'=>'<i class="far fa-file-pdf" aria-hidden="true"></i> PDF','2'=>'<i class="far fa-file-excel" aria-hidden="true"></i> EXCEL'),
  			    	array(
  			        	'template'=>'{input}{label}',
  			        	'separator'=>'',
  			        	'labelOptions'=>array(
  			            	'style'=> '
  			                	padding-left:1%;
  			                	padding-right:5%;
  		            	'),
  		          	)                              
  		      	);
  			?>			
    	</div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-cogs"></i> Generar</button>
    </div>
</div>
    
<?php $this->endWidget(); ?>

<script>

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

  $("#Ventas_fecha_inicial").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#Ventas_fecha_final').datepicker('setStartDate', minDate);
  });

  $("#Ventas_fecha_final").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#Ventas_fecha_inicial').datepicker('setEndDate', maxDate);
  });
  
});

function resetfields(){
  $('#Ventas_fecha_inicial').val('');
  $('#Ventas_fecha_final').val('');
  $('#Ventas_marca_inicial').val('').trigger('change');
  $('#Ventas_marca_final').val('').trigger('change');
}

</script>

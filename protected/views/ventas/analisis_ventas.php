<?php
/* @var $this VentasController */
/* @var $model Ventas */
?>

<h4>Análisis de ventas</h4>

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

  <input type="hidden" id="periodo_min" value="<?php echo date('Y'); ?>-01">
  <input type="hidden" id="periodo_max" value="<?php echo date('Y-m'); ?>">

  <div class="row">
      <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'periodo_inicial', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'periodo_inicial'); ?>
          <?php echo $form->textField($model,'periodo_inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
      </div> 
      <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'periodo_final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'periodo_final'); ?>
          <?php echo $form->textField($model,'periodo_final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'opcion', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'opcion'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Ventas[opcion]',
            'id'=>'Ventas_opcion',
            'data'=>array(1=> 'LINEA', 2 => 'MARCA', 3 => 'DESC. ORACLE'),
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
    <div class="col-sm-4" id="linea" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_linea" style="display: none;"></div>
        <?php echo $form->label($model,'linea'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Ventas[linea]',
            'id'=>'Ventas_linea',
            'data'=>$lista_lineas,
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
    <div class="col-sm-4" id="marca" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_marca" style="display: none;"></div>
        <?php echo $form->label($model,'marca'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Ventas[marca]',
            'id'=>'Ventas_marca',
            'data'=>$lista_marcas,
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
    <div class="col-sm-4" id="oracle" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_oracle" style="display: none;"></div>
        <?php echo $form->label($model,'des_ora'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Ventas[des_ora]',
            'id'=>'Ventas_des_ora',
            'data'=>$lista_oracle,
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

<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-excel"></i> Generar</button>
    </div>
</div>    


<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#Ventas_opcion").change(function() {

    var linea  = $('#Ventas_linea').val();
    var marca  = $('#Ventas_marca').val();
    var oracle = $('#Ventas_des_ora').val();
      
    if($(this).val() == ""){


      $('#error_marca').hide();
      $('#error_marca').html('');
      $('#Ventas_marca').val('').trigger('change');
      $('#marca').hide();

      $('#error_linea').hide();
      $('#error_linea').html('');
      $('#Ventas_linea').val('').trigger('change');
      $('#linea').hide();

      $('#error_oracle').hide();
      $('#error_oracle').html('');
      $('#Ventas_des_ora').val('').trigger('change');
      $('#oracle').hide();

    }else{

      if($(this).val() == 1){
        /*LINEA*/

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Ventas_linea').val('').trigger('change');
        $('#linea').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Ventas_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Ventas_des_ora').val('').trigger('change');
        $('#oracle').hide();

      }

      if($(this).val() == 2){
        /*MARCA*/

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Ventas_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Ventas_marca').val('').trigger('change');
        $('#marca').show();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Ventas_des_ora').val('').trigger('change');
        $('#oracle').hide();

      }

      if($(this).val() == 3){
        /*ORACLE*/

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Ventas_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Ventas_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Ventas_des_ora').val('').trigger('change');
        $('#oracle').show();

      }

    }
  });
  

  $("#valida_form").click(function() {
      var form = $("#ventas-form");
      var settings = form.data('settings');
      var opcion = $('#Ventas_opcion').val();
      var linea  = $('#Ventas_linea').val();
      var marca  = $('#Ventas_marca').val();
      var oracle = $('#Ventas_des_ora').val();

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              if(opcion == 1){
                /*LINEA*/

                if(linea == ""){

                  $('#error_linea').show();
                  $('#error_linea').html('Línea es requerido.');
                                    
                }else{
                  
                  $('#error_linea').hide();
                  $('#error_linea').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 2){
                /*MARCA*/

                if(marca == ""){

                  $('#error_marca').show();
                  $('#error_marca').html('Marca es requerido.');
                                    
                }else{
                  
                  $('#error_marca').hide();
                  $('#error_marca').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 3){
                /*ORACLE*/

                if(oracle == ""){

                  $('#error_oracle').show();
                  $('#error_oracle').html('Desc. oracle es requerido.');
                                    
                }else{
                  
                  $('#error_oracle').hide();
                  $('#error_oracle').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

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
      format : 'yyyy-mm',
      titleFormat: "MM yyyy",
  };

  $("#Ventas_periodo_inicial").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
      format: "yyyy-mm",
      startView: "year", 
      minViewMode: "months",
      startDate: $("#periodo_min").val(),
      endDate: $("#periodo_max").val(),
  }).on('changeDate', function (selected) {

    if($("#Ventas_periodo_inicial").val() > $("#Ventas_periodo_final").val()){
        $("#Ventas_periodo_final").val('');
    }

    var minDate = new Date(selected.date.valueOf());
    $('#Ventas_periodo_final').datepicker('setStartDate', minDate);
  });

  $("#Ventas_periodo_final").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
      format: "yyyy-mm",
      startView: "year", 
      minViewMode: "months",
      startDate: $("#periodo_min").val(),
      endDate: $("#periodo_max").val(),
  }).on('changeDate', function (selected) {

    if($("#Ventas_periodo_final").val() < $("#Ventas_periodo_inicial").val()){
        $("#Ventas_periodo_inicial").val('');
    }

    var maxDate = new Date(selected.date.valueOf());
    $('#Ventas_periodo_inicial').datepicker('setEndDate', maxDate);
  });

});

function resetfields(){
  $('#Ventas_periodo_inicial').val('');
  $('#Ventas_periodo_final').val('');
  $('#Ventas_opcion').val('').trigger('change');
}

</script>

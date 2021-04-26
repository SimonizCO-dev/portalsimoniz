<?php
/* @var $this IDoctoController */
/* @var $model IDocto */

//para combos de lineas
$lista_lineas = CHtml::listData($lineas, 'Id', 'Descripcion'); 

?>

<h4>Kardex de inventario</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'idocto-form',
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
      <?php echo $form->label($model,'fecha_inicial'); ?>
      <?php echo $form->error($model,'fecha_inicial', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'fecha_inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div> 
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'fecha_final'); ?>
      <?php echo $form->error($model,'fecha_final', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'fecha_final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div> 
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->label($model,'tipo'); ?>
        <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'IDocto[tipo]',
                'id'=>'IDocto_tipo',
                'data'=>array(1 => 'Rango de items', 2 => 'Línea(s)'),
                'value' => $model->tipo,
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
  <div class="col-sm-6" id="div_item_i" style="display: none">
    <div class="form-group">
      <div class="badge badge-warning float-right" id="error_item_inicial" style="display: none;"></div>
        <?php echo $form->label($model,'item_inicial'); ?>
        <?php echo $form->textField($model,'item_inicial'); ?>
        <?php
          $this->widget('ext.select2.ESelect2', array(
              'selector' => '#IDocto_item_inicial',
              'options'  => array(
                  'minimumInputLength' => 3,
                  'width' => '100%',
                  'language' => 'es',
                  'ajax' => array(
                      'url' => Yii::app()->createUrl('iItem/SearchItem'),
                      'dataType'=>'json',
                      'data'=>'js:function(term){return{q: term};}',
                      'results'=>'js:function(data){ return {results:data};}'                
                  ),
                  'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDocto_det_item"); return "No se encontraron resultados"; }',
                  'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDocto_det_item\')\">Limpiar campo</button>"; }',
              ),
          ));
        ?>
    </div>
  </div>
  <div class="col-sm-6" id="div_item_f" style="display: none">
    <div class="form-group">
        <?php echo $form->label($model,'item_final'); ?>
        <div class="badge badge-warning float-right" id="error_item_final" style="display: none;"></div>
        <?php echo $form->textField($model,'item_final'); ?>
        <?php
          $this->widget('ext.select2.ESelect2', array(
              'selector' => '#IDocto_item_final',
              'options'  => array(
                  'minimumInputLength' => 3,
                  'width' => '100%',
                  'language' => 'es',
                  'ajax' => array(
                      'url' => Yii::app()->createUrl('iItem/SearchItem'),
                      'dataType'=>'json',
                      'data'=>'js:function(term){return{q: term};}',
                      'results'=>'js:function(data){ return {results:data};}'                
                  ),
                  'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDocto_det_item"); return "No se encontraron resultados"; }',
                  'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDocto_det_item\')\">Limpiar campo</button>"; }',
              ),
          ));
        ?>
    </div>
  </div>
  <div class="col-sm-8" id="div_linea" style="display: none">
    <div class="form-group">
          <?php echo $form->label($model,'linea'); ?>
          <div class="badge badge-warning float-right" id="error_linea" style="display: none;"></div>
          <?php
            $this->widget('ext.select2.ESelect2',array(
              'name'=>'IDocto[linea]',
              'id'=>'IDocto_linea',
              'data'=>$lista_lineas,
              'htmlOptions'=>array(
                'multiple'=>'multiple',
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
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-pdf"></i> Generar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {
  
  $("#IDocto_tipo").change(function() {

      var val = $(this).val();

      if(val == ""){

        $('#IDocto_item_inicial').val('').trigger('change');
        $('#s2id_IDocto_item_inicial span').html("");
        $('#IDocto_item_final').val('').trigger('change');
        $('#s2id_IDocto_item_final span').html("");
        $('#IDocto_linea').val('').trigger('change');
        $('#s2id_IDocto_linea span').html("");
        $('#div_item_i').hide();
        $('#div_item_f').hide();
        $('#div_linea').hide();
      }else{
        
        if(val == 1){
          //Rango de items
          $('#div_item_i').show();
          $('#div_item_f').show();
          $('#div_linea').hide();
          $('#IDocto_linea').val('').trigger('change');
          $('#s2id_IDocto_linea span').html("");
        }

        if(val == 2){
          //Línea(s)
          $('#IDocto_item_inicial').val('').trigger('change');
          $('#s2id_IDocto_item_inicial span').html("");
          $('#IDocto_item_final').val('').trigger('change');
          $('#s2id_IDocto_item_final span').html("");
          $('#div_item_i').hide();
          $('#div_item_f').hide();
          $('#div_linea').show();
        }

      }    
  });

  $("#valida_form").click(function() {
      var form = $("#idocto-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              var tipo = $('#IDocto_tipo').val();
              var item_i = $('#IDocto_item_inicial').val();
              var item_f = $('#IDocto_item_final').val();
              var lineas = $('#IDocto_linea').val();

              if(tipo == ''){
                $('#IDocto_item_inicial').val('');
                $('#IDocto_item_final').val('');
                $('#IDocto_linea').val('').trigger('change');
                $('#s2id_IDocto_linea span').html("");

                //se envia el form
                form.submit();
                $(".ajax-loader").fadeIn('fast');
                setTimeout(function(){ $(".ajax-loader").fadeOut('fast'); }, 5000); 

              }else{

                if(tipo == 1){

                  if(item_i == "" || item_f == ""){
                    
                    if(item_i == ""){
                      $('#error_item_inicial').html('Item inicial es requerido.');
                      $('#error_item_inicial').show();
                    }else{
                      $('#error_item_inicial').html('');
                      $('#error_item_inicial').hide();
                    }

                    if(item_f == ""){
                      $('#error_item_final').html('Item final es requerido.');
                      $('#error_item_final').show();
                    }else{
                      $('#error_item_final').html('');
                      $('#error_item_final').hide();
                    }

                  }else{
                
                      $('#error_item_inicial').html('');
                      $('#error_item_inicial').hide();
                      $('#error_item_final').html('');
                      $('#error_item_final').hide();

                      //se envia el form
                      form.submit();
                      $(".ajax-loader").fadeIn('fast');
                      setTimeout(function(){ $(".ajax-loader").fadeOut('fast'); }, 5000); 
                    
                  }
                }

                if(tipo == 2){
                  if(lineas == ""){
                  
                    $('#error_linea').html('Línea(s) es requerido.');
                    $('#error_linea').show();
                   
                  }else{
                  
                    $('#error_linea').html('');
                    $('#error_linea').hide();

                    //se envia el form
                    form.submit();
                    $(".ajax-loader").fadeIn('fast');
                    setTimeout(function(){ $(".ajax-loader").fadeOut('fast'); }, 5000); 
                    
                  }
                }
              }    
          } else {
              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              settings.submitting = false ;

              var tipo = $('#IDocto_tipo').val();
              var item_i = $('#IDocto_item_inicial').val();
              var item_f = $('#IDocto_item_final').val();
              var lineas = $('#IDocto_linea').val();

              if(tipo == ''){
                $('#error_item_inicial').html('');
                $('#error_item_inicial').hide();
                $('#error_item_final').html('');
                $('#error_item_final').hide();

              }else{

                if(tipo == 1){

                  if(item_i == "" || item_f == ""){
                    
                    if(item_i == ""){
                      $('#error_item_inicial').html('Item inicial es requerido.');
                      $('#error_item_inicial').show();
                    }else{
                      $('#error_item_inicial').html('');
                      $('#error_item_inicial').hide();
                    }

                    if(item_f == ""){
                      $('#error_item_final').html('Item final es requerido.');
                      $('#error_item_final').show();
                    }else{
                      $('#error_item_final').html('');
                      $('#error_item_final').hide();
                    }

                  }else{
                
                      $('#error_item_inicial').html('');
                      $('#error_item_inicial').hide();
                      $('#error_item_final').html('');
                      $('#error_item_final').hide();
                    
                  }
                }

                if(tipo == 2){
                  if(lineas == ""){
                  
                    $('#error_linea').html('Línea(s) es requerido.');
                    $('#error_linea').show();
                   
                  }else{
                  
                    $('#error_linea').html('');
                    $('#error_linea').hide();
                    
                  }
                }
              } 
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

  $("#IDocto_fecha_inicial").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#IDocto_fecha_final').datepicker('setStartDate', minDate);
  });

  $("#IDocto_fecha_final").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#IDocto_fecha_inicial').datepicker('setEndDate', maxDate);
  });

});

function resetfields(){
  $('#IDocto_fecha_inicial').val('');
  $('#IDocto_fecha_final').val('');
  $('#IDocto_tipo').val('').trigger('change');
  $('#error_item_inicial').html('');
  $('#error_item_inicial').hide();
  $('#error_item_final').html('');
  $('#error_item_final').hide();
}

</script>
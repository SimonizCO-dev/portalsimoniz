<?php
/* @var $this IDoctoController */
/* @var $model IDocto */

//para combos de tipos
$lista_tipos = CHtml::listData($tipos, 'Id', 'Descripcion'); 

//para combos de bodegas
$lista_bodegas = CHtml::listData($bodegas, 'Id', 'Descripcion'); 

//para combos de estados
$lista_estados = CHtml::listData($estados, 'Id', 'Descripcion'); 

?>

<h4>Movimientos de inventario</h4>

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
  <div class="col-sm-3">
    <div class="form-group">
          <?php echo $form->label($model,'Id_Tipo_Docto'); ?>
          <?php
            $this->widget('ext.select2.ESelect2',array(
              'name'=>'IDocto[Id_Tipo_Docto]',
              'id'=>'IDocto_Id_Tipo_Docto',
              'data'=>$lista_tipos,
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
  <div class="col-sm-3">
    <div class="form-group">
      <?php echo $form->label($model,'Consecutivo'); ?>
      <?php echo $form->numberField($model,'Consecutivo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="form-group">
      <?php echo $form->label($model,'fecha_inicial'); ?>
      <?php echo $form->error($model,'fecha_inicial', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'fecha_inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div> 
  <div class="col-sm-3">
    <div class="form-group">
      <?php echo $form->label($model,'fecha_final'); ?>
      <?php echo $form->error($model,'fecha_final', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'fecha_final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div> 
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="form-group">
        <?php echo $form->label($model,'Id_Tercero'); ?>
        <?php echo $form->textField($model,'Id_Tercero'); ?>
        <?php
            $this->widget('ext.select2.ESelect2', array(
                'selector' => '#IDocto_Id_Tercero',
                'options'  => array(
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'width' => '100%',
                    'language' => 'es',
                    'ajax' => array(
                        'url' => Yii::app()->createUrl('iTercero/SearchTercero'),
                        'dataType'=>'json',
                        'data'=>'js:function(term){return{q: term};}',
                        'results'=>'js:function(data){ return {results:data};}'                   
                    ),
                    'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDocto_Id_Tercero"); return "No se encontraron resultados"; }',
                    'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDocto_Id_Tercero\')\">Limpiar campo</button>"; }',
                ),
            ));
        ?>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="form-group">
        <?php echo $form->label($model,'det_item'); ?>
        <?php echo $form->error($model,'det_item', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->textField($model,'det_item'); ?>
        <?php
          $this->widget('ext.select2.ESelect2', array(
              'selector' => '#IDocto_det_item',
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
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->label($model,'det_bodega_origen'); ?>
        <?php echo $form->error($model,'det_bodega_origen', array('class' => 'badge badge-warning float-right')); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'IDocto[det_bodega_origen]',
                'id'=>'IDocto_det_bodega_origen',
                'data'=>$lista_bodegas,
                'value' => $model->det_bodega_origen,
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
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->label($model,'det_bodega_destino'); ?>
        <?php echo $form->error($model,'det_bodega_destino', array('class' => 'badge badge-warning float-right')); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'IDocto[det_bodega_destino]',
                'id'=>'IDocto_det_bodega_destino',
                'data'=>$lista_bodegas,
                'value' => $model->det_bodega_destino,
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
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'opcion_exp'); ?>
      <?php echo $form->error($model,'opcion_exp', array('class' => 'badge badge-warning float-right')); ?>
      <br>
      <?php 
        echo $form->radioButtonList($model,'opcion_exp',
            array('3'=>'<i class="fa fa-desktop" aria-hidden="true"></i> Pantalla','1'=>'<i class="far fa-file-pdf" aria-hidden="true"></i> PDF'),
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


<div class="row">
    <div class="col-lg-12 table-responsive" id="resultados">
    <!-- contenido via ajax -->
    </div>
</div>  

<?php $this->endWidget(); ?>

<script>

$(function() {
 // $(".ajax-loader").show();
  $("#valida_form").click(function() {

      var form = $("#idocto-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              $("#resultados").html(''); 
              //se envia el form
              if($("input:radio:checked").val() == 3){
                reporte_pantalla();
              }else{
                form.submit();
                loadershow(); 
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
  $('#IDocto_Id_Tipo_Docto').val('').trigger('change');
  $('#IDocto_Consecutivo').val('');
  $('#IDocto_fecha_inicial').val('');
  $('#IDocto_fecha_final').val('');
  $('#IDocto_Id_Tercero').val('').trigger('change');
  $('#s2id_IDocto_Id_Tercero span').html("");
  $('#IDocto_det_item').val('').trigger('change');
  $('#s2id_IDocto_det_item span').html("");
  $('#IDocto_det_bodega_origen').val('').trigger('change');
  $('#IDocto_det_bodega_destino').val('').trigger('change');
  $("#resultados").html('');
}

function reporte_pantalla(){

  var tipo = $("#IDocto_Id_Tipo_Docto").val();
  var consecutivo = $("#IDocto_Consecutivo").val();
  var fecha_inicial = $("#IDocto_fecha_inicial").val();
  var fecha_final = $("#IDocto_fecha_final").val();
  var tercero = $("#IDocto_Id_Tercero").val();
  var item = $("#IDocto_det_item").val();
  var bodega_origen = $("#IDocto_det_bodega_origen").val();
  var bodega_destino = $("#IDocto_det_bodega_destino").val();

  var data = {tipo: tipo, consecutivo: consecutivo, fecha_inicial: fecha_inicial, fecha_final: fecha_final, tercero: tercero, item: item, bodega_origen: bodega_origen, bodega_destino: bodega_destino}
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('iDocto/movimientospant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

</script>
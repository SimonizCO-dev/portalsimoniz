<?php
/* @var $this ReporteController */
/* @var $model Reporte */

?>

<h4>Reporte elementos / herramientas por empleado</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reporte-th-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<p>Busque por # identificación, nombres o apellidos (Recuerde que el empleado debe contar con un contrato activo):</p>

<div class="row">
    <div class="col-sm-8">
      <div class="form-group">
            <?php echo $form->error($model,'id_empleado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'id_empleado'); ?>

            <?php echo $form->textField($model,'id_empleado'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#ReporteTh_id_empleado',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('empleado/SearchEmpleado'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Reporte_id_empleado"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Reporte_id_empleado\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-sm-6"> 
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-search"></i> Consultar</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 table-responsive" id="resultados" style="font-size: 10px !important;">
    <!-- contenido via ajax -->
    </div>
</div>  


<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#valida_form").click(function() {

      var form = $("#reporte-th-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              $("#resultados").html(''); 
              reporte_pantalla();
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

function reporte_pantalla(){

  var id_empleado = $("#ReporteTh_id_empleado").val();
  
  var data = {id_empleado: id_empleado}
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('reporteth/elemherremppant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

function resetfields(){
  $(".ajax-loader").fadeIn('fast');
  $('#ReporteTh_id_empleado').val('').trigger('change');
  $('#s2id_ReporteTh_id_empleado span').html("");
  $("#resultados").html('');
  $(".ajax-loader").fadeOut('fast');
}

</script> 


<?php
/* @var $this ComisionController */
/* @var $model Comision */

?>

<h4>Historico de documento</h4>

  <?php $form=$this->beginWidget('CActiveForm', array(
  	'id'=>'comision-form',
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
            <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'tipo'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Comision[tipo]',
                    'id'=>'Comision_tipo',
                    'data'=>array(1 => 'FACTURA', 2 => 'RECIBO'),
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
    <div class="col-sm-6" id="div_documento" style="display: none;">
      <div class="form-group">
            <?php echo $form->error($model,'documento', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'documento'); ?>
            <?php echo $form->textField($model,'documento'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Comision_documento',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('comision/SearchDoc'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term, tipo: $("#Comision_tipo").val() };}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Comision_documento"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Comision_documento\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
  </div>

  <div class="row mb-2">
      <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-search"></i> Consultar</button>
      </div>
  </div>

<div id="resultados" style="display: none;">

</div>


<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#Comision_tipo").change(function() {

    val = $(this).val();

    if(val == ''){
      $('#div_documento').hide();
    }else{
      $('#div_documento').show();
    }

    $('#Comision_documento').val('').trigger('change');
    $('#s2id_Comision_documento span').html("");
    $("#resultados").html(''); 
    $("#resultados").hide();

  });  

  $("#Comision_documento").change(function() {
    $(".ajax-loader").fadeIn('fast'); 
    $("#resultados").html(''); 
    $("#resultados").hide();
    $(".ajax-loader").fadeOut('fast');
  });  

  $("#valida_form").click(function() {

      var form = $("#comision-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;

      var tipo = $('#Comision_tipo').val();
      var documento = $('#Comision_documento').val();

      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
  
              $("#resultados").html(''); 

              url = "<?php echo Yii::app()->createUrl('comision/gethistdocto'); ?>";

              //AJAX
              var data = {tipo: tipo, documento: documento}
              $.ajax({ 
                type: "POST", 
                url: url,
                data: data,
                beforeSend: function(){
                  $(".ajax-loader").fadeIn('fast'); 
                },
                success: function(data){

                  $("#resultados").html(data);
                  $("#resultados").show();  
                  $(".ajax-loader").fadeOut('fast');
  
                },
              }); 
    
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
  $('#Comision_tipo').val('').trigger('change');
}

</script>

<?php
/* @var $this ComisionController */
/* @var $model Comision */

?>

<div id="div_mensaje" style="display: none;"></div>

<h4>Ajuste de recaudo</h4>

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
    <div class="col-sm-6">
      <div class="form-group">
            <?php echo $form->error($model,'recibo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'recibo'); ?>
            <?php echo $form->textField($model,'recibo'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Comision_recibo',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('comision/SearchRecibo'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Comision_recibo"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Comision_recibo\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
            <?php echo $form->error($model,'id_vendedor', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'id_vendedor'); ?>
            <?php echo $form->textField($model,'id_vendedor'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Comision_id_vendedor',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('comision/SearchVend'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Comision_id_vendedor"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Comision_id_vendedor\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar ajuste</button>
    </div>
  </div>

<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#valida_form").click(function() {

      var form = $("#comision-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;

      var recibo = $('#Comision_recibo').val();
      var vendedor = $('#Comision_id_vendedor').val();

      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              if(confirm("Esta seguro de realizar el ajuste ? ")) { 
                
                url = "<?php echo Yii::app()->createUrl('comision/ajusterec'); ?>";

                //se limpia el div de el mensaje antes de hacer la petición
                limp_div_msg();

                //AJAX
                var data = {recibo: recibo, vendedor: vendedor}
                $.ajax({ 
                  type: "POST", 
                  url: url,
                  dataType: 'json',
                  data: data,
                  beforeSend: function(){
                    $(".ajax-loader").fadeIn('fast'); 
                  },
                  success: function(data){

                    var res = data.res; 
                    var mensaje = data.msg; 

                    if(res == 0){

                      $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>'+mensaje);
                    }

                    if(res == 1){
                      $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-check-circle"></i>Realizado</h5>'+mensaje);  
                    }

                    $("#div_mensaje").fadeIn('fast');
                    $(".ajax-loader").fadeOut('fast');

                    
                  },
                }); 


                //se resetea el formulario para un nuevo ajuste
                $('#Comision_recibo').val('').trigger('change');
                $('#s2id_Comision_recibo span').html("");
                $('#Comision_id_vendedor').val('').trigger('change');
                $('#s2id_Comision_id_vendedor span').html("");

              }else{
                settings = form.data('settings'),
                $.each(settings.attributes, function () {
                   $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                settings.submitting = false ; 
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
  
});

</script>
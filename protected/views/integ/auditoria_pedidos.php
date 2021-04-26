<?php
/* @var $this IntegController */
/* @var $model Integ */

?>

<h4>Auditoría de pedidos</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'integ-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 

?>
    
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'co', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'co'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Integ[co]',
                  'id'=>'Integ_co',
                  'data'=> $lista_co,
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
          <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'tipo'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Integ[tipo]',
                  'id'=>'Integ_tipo',
                  'data'=> $lista_tipos,
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
          <?php echo $form->error($model,'consecutivo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'consecutivo'); ?>
          <?php echo $form->numberField($model,'consecutivo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
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

  $("#valida_form").click(function() {
    var form = $("#integ-form");
    var settings = form.data('settings') ;

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
              $.fn.yiiactiveform.updateInput(this,messages,form); 
            });

            var co = $('#Integ_co').val();
            var tipo = $('#Integ_tipo').val();
            var consecutivo = $('#Integ_consecutivo').val();

            $(".ajax-loader").fadeIn('fast');
          
            var data = {co: co, tipo: tipo, consecutivo: consecutivo}
                 
            $.ajax({ 
              type: "POST",
              data: data, 
              url: "<?php echo Yii::app()->createUrl('integ/auditoriapedidospant'); ?>",
              success: function(data){ 
                $(".ajax-loader").fadeOut('fast');
                $("#resultados").html(data); 
              }
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


  /*$("#valida_form").click(function() {

      var form = $("#integ-form");

      var opcion = $('#Integ_opcion').val();
      var co = $('#Integ_c_o').val();
      var tipo = $('#Integ_tipo').val();
      var consecutivo = $('#Integ_consecutivo').val();

      if(opcion != ""){
        if(opcion == 1){

          //TODO
          form.submit();
          loadershow();

        }else{

          //FILTRADO
          if(co != "" && tipo != "" && consecutivo != ""){

            $(".ajax-loader").fadeIn('fast');
            
            var data = {co: co, tipo: tipo, consecutivo: consecutivo}

            $.ajax({ 
              type: "POST",
              data: data, 
              url: "<?php //echo Yii::app()->createUrl('integ/auditoriapedidospant'); ?>",
              success: function(data){ 
                $(".ajax-loader").fadeOut('fast');
                $("#resultados").html(data); 
              }
            });

          }else{
            if(co == ""){
              $('#Integ_c_o_em_').html('CO es requerido.');
              $('#Integ_c_o_em_').show();
            }

            if(tipo == ""){
              $('#Integ_tipo_em_').html('Tipo es requerido.');
              $('#Integ_tipo_em_').show();
            }
            
            if(consecutivo == ""){
              $('#Integ_consecutivo_em_').html('Consecutivo es requerido.');
              $('#Integ_consecutivo_em_').show();   
            }

          }

        }
      }else{
        $('#Integ_opcion_em_').html('Opción es requerido.');
        $('#Integ_opcion_em_').show();
      }

  });*/

function resetfields(){
  $('#Integ_co').val('').trigger('change');
  $('#Integ_tipo').val('').trigger('change');
  $('#Integ_consecutivo').val('');
  $("#resultados").html(''); 
}

</script>

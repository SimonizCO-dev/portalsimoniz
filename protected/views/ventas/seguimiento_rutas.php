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
      <?php echo $form->error($model,'anio', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'anio'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Ventas[anio]',
              'id'=>'Ventas_anio',
              'data'=> array(date("Y")=> date("Y"), date("Y") - 1 => date("Y") - 1, date("Y") - 2 => date("Y") - 2, date("Y") - 3 => date("Y") - 3, date("Y") - 4 => date("Y") - 4, date("Y") - 5 => date("Y") - 5, date("Y") - 6=> date("Y") - 6),
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
  <div class="col-sm-8">
    <div class="form-group">
        <?php echo $form->label($model,'ruta'); ?>
        <?php echo $form->error($model,'ruta', array('class' => 'badge badge-warning float-right')); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Ventas[ruta]',
                'id'=>'Ventas_ruta',
                'data'=>$lista_rutas,
                'value' => $model->ruta,
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
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-excel"></i> Generar</button>
    </div>
</div>    


<?php $this->endWidget(); ?>

<script>

$(function() {
  
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
  $('#Ventas_anio').val('').trigger('change');
  $('#Ventas_ruta').val('').trigger('change');
}

</script>

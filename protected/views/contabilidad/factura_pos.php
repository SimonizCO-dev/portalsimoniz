<?php
/* @var $this ContabilidadController */
/* @var $model Contabilidad */

?>

<h4>Factura POS</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'contabilidad-form',
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
                'name'=>'Contabilidad[tipo]',
                'id'=>'Contabilidad_tipo',
                'data'=> array('VF1' => 'VF1', 'VF2' => 'VF2', 'VF3' => 'VF3', 'VF4' => 'VF4', 'Vf5' => 'Vf5', 'VF6' => 'VF6', 'VF7' => 'VF7', 'VF8' => 'VF8', 'X50' => 'X50', 'X51' => 'X51', 'X52' => 'X52'),
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
          <?php echo $form->error($model,'cons_inicial', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'cons_inicial'); ?>
          <?php echo $form->numberField($model,'cons_inicial', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'cons_final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'cons_final'); ?>
          <?php echo $form->numberField($model,'cons_final', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
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

  $("#valida_form").click(function() {

      var form = $("#contabilidad-form");
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
  
});

function resetfields(){
  $('#Contabilidad_tipo').val('');
  $('#Contabilidad_cons_inicial').val('');
  $('#Contabilidad_cons_final').val('');
}

</script>

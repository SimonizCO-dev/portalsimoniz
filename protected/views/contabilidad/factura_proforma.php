<?php
/* @var $this ContabilidadController */
/* @var $model Contabilidad */

?>

<h4>Factura PROFORMA</h4>

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
      <?php echo $form->error($model,'c_o', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'c_o'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Contabilidad[c_o]',
              'id'=>'Contabilidad_c_o',
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
              'name'=>'Contabilidad[tipo]',
              'id'=>'Contabilidad_tipo',
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
  $('#Contabilidad_c_o').val('');
  $('#Contabilidad_tipo').val('');
  $('#Contabilidad_consecutivo').val('');
}

</script>

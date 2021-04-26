<?php
/* @var $this CarteraController */
/* @var $model Cartera */

?>

<h4>Cobro prejurídico</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'cartera-form',
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
          <?php echo $form->error($model,'ruta_inicial', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'ruta_inicial'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Cartera[ruta_inicial]',
                  'id'=>'Cartera_ruta_inicial',
                  'data'=> $lista_rutas,
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
    <div class="col-sm-6">
      <div class="form-group">
          <?php echo $form->error($model,'ruta_final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'ruta_final'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Cartera[ruta_final]',
                  'id'=>'Cartera_ruta_final',
                  'data'=> $lista_rutas,
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
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'estado', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'estado'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Cartera[estado]',
                  'id'=>'Cartera_estado',
                  'data'=> $lista_estados,
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
        <?php echo $form->error($model,'valor', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'valor'); ?>  
        <?php echo $form->numberField($model,'valor', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'dias', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'dias'); ?>  
        <?php echo $form->numberField($model,'dias', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'firma', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'firma'); ?>
      <?php echo $form->textField($model,'firma', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off')); ?>
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

      var form = $("#cartera-form");
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
  $('#Cartera_ruta_inicial').val('').trigger('change');
  $('#Cartera_ruta_final').val('').trigger('change');
  $('#Cartera_estado').val('').trigger('change');
  $('#Cartera_valor').val('');
  $('#Cartera_dias').val('');
  $('#Cartera_firma').val('');
}

</script>

<?php
/* @var $this InventariosController */
/* @var $model Inventarios */

?>

<h4>Items / costos</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventarios-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<?php echo $form->hiddenField($model,'opc', array('class' => 'form-control', 'value' => 1)); ?>

<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'clase', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'clase'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Inventarios[clase]',
                  'id'=>'Inventarios_clase',
                  'data'=> $lista_clases,
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
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'des_ora', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'des_ora'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Inventarios[des_ora]',
                  'id'=>'Inventarios_des_ora',
                  'data'=> $lista_oracle,
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
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'estado', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'estado'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Inventarios[estado]',
                  'id'=>'Inventarios_estado',
                  'data'=> $lista_estados,
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

      var form = $("#inventarios-form");
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
  $('#Inventarios_clase').val('').trigger('change');
  $('#Inventarios_des_ora').val('').trigger('change');
  $('#Inventarios_estado').val('').trigger('change');
}

</script>

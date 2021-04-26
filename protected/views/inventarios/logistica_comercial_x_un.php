<?php
/* @var $this InventarioController */
/* @var $model Inventario */

?>

<h4>Logistica comercial x unidad de negocio</h4>

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

<div class="row">
  <div class="col-sm-6">
      <div class="form-group">
          <?php echo $form->error($model,'un_inicial', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'un_inicial'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Inventarios[un_inicial]',
                  'id'=>'Inventarios_un_inicial',
                  'data'=> $lista_un,
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
          <?php echo $form->error($model,'un_final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'un_final'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Inventarios[un_final]',
                  'id'=>'Inventarios_un_final',
                  'data'=> $lista_un,
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
  $('#Inventarios_un_inicial').val('').trigger('change');
  $('#Inventarios_un_final').val('').trigger('change');
}

</script>

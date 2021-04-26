<?php
/* @var $this InventariosController */
/* @var $model Inventarios */

?>

<h4>Control de pedidos por marca (Listas)</h4>

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
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'marca', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'marca'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Inventarios[marca]',
            'id'=>'Inventarios_marca',
            'data'=>$lista_marcas,
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
        <?php echo $form->error($model,'estado', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'estado'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Inventarios[estado]',
            'id'=>'Inventarios_estado',
            'data'=>$lista_estados,
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
        <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'tipo'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Inventarios[tipo]',
            'id'=>'Inventarios_tipo',
            'data'=>$lista_tipos,
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
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'opcion_exp', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'opcion_exp'); ?><br>
        <?php 
          echo $form->radioButtonList($model,'opcion_exp',
            array('1'=>'<i class="far fa-file-pdf" aria-hidden="true"></i> PDF','2'=>'<i class="far fa-file-excel" aria-hidden="true"></i> EXCEL'),
            array(
                'template'=>'{input}{label}',
                'separator'=>'',
                'labelOptions'=>array(
                    'style'=> '
                        padding-left:1%;
                        padding-right:5%;
                  '),
                )                              
            );
        ?>      
      </div>
    </div>
</div>
    
<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-cogs"></i> Generar</button>
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
  $('#Inventarios_marca').val('').trigger('change');
  $('#Inventarios_estado').val('').trigger('change');
  $('#Inventarios_tipo').val('').trigger('change');
}

</script>

<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//para combos de unidades de gerencia
$lista_ug = CHtml::listData($unidades_gerencia, 'Id_Unidad_Gerencia', 'Unidad_Gerencia');

?>

<h4>Reporte empleados x unidad de gerencia</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reporte-th-form',
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
            <?php echo $form->error($model,'unidad_gerencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'unidad_gerencia'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ReporteTh[unidad_gerencia]',
                    'id'=>'ReporteTh_unidad_gerencia',
                    'data'=>$lista_ug,
                    'htmlOptions'=>array(
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
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ReporteTh[estado]',
                    'id'=>'ReporteTh_estado',
                    'data'=>$estados,
                    'value' => $model->estado,
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

<div class="row mb-2">
    <div class="col-sm-6">
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button> 
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="far fa-file-excel"></i> Generar</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 table-responsive" id="resultados" style="font-size: 10px !important;">
    <!-- contenido via ajax -->
    </div>
</div>  


<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#valida_form").click(function() {

      var form = $("#reporte-th-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              $("#resultados").html(''); 
              //se envia el form
              
              form.submit();
              $(".ajax-loader").fadeIn('fast');
              setTimeout(function(){ $(".ajax-loader").fadeOut('fast'); }, 5000); 
 
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

function reporte_pantalla(){

  var empresa = $("#ReporteTh_empresa").val();
  var estado = $("#ReporteTh_estado").val();
  var data = {empresa: empresa, estado: estado}
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('reporte/empleadosactivospant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

function resetfields(){
  $('#ReporteTh_unidad_gerencia').val('').trigger('change');
  $('#ReporteTh_estado').val('').trigger('change');
}
  
</script>



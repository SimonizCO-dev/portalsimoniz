<?php
/* @var $this ReporteController */
/* @var $model Reporte */

?>

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
)); ?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Actualización de recibos</h4>
  </div>
  <div class="col-sm-6 text-right">
  	<?php echo $form->hiddenField($model,'opc', array('class' => 'form-control', 'readonly' => true, 'value' => 1)); ?>
    <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-cogs"></i> Ejecutar proceso</button>
  </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

  	$("#valida_form").click(function() {

		var form = $("#integ-form");

      	if(confirm("Esta seguro de ejecutar el proceso ?")){
			//se envia el form
			form.submit();
			loadershow();
      	}else{
      		return false;
      	}
  	});

});

</script>
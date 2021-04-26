<?php
/* @var $this ReporteController */
/* @var $model Reporte */

?>

<h4>Reporte elementos / herramientas pend. de entrega</h4>

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

<div class="row" style="padding-top: 2%">
    <div class="col-lg-12 table-responsive" id="resultados" style="font-size: 10px !important;">
    <!-- contenido via ajax -->
    </div>
</div>  

<?php $this->endWidget(); ?>

<script>

$(function() {
  reporte_pantalla();
});

function reporte_pantalla(){
  
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('reporteth/elemherrpendpant'); ?>",
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

</script> 


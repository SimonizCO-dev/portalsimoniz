<?php
/* @var $this InventarioController */
/* @var $model Inventario */

?>

<h4>Actualización recibos de caja</h4>

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

<div class="row">
    <div class="col-lg-12 table-responsive" id="resultados">
    <!-- contenido via ajax -->
    </div>
</div>  

<?php $this->endWidget(); ?>

<script>

$(function() {   
  reporte_pantalla();            
});

function reporte_pantalla(){

  var data = {}
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('integ/actrecapant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

function actreca(reca){

  var data = {reca: reca}
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('integ/actualizarreca'); ?>",
    data: data,
    dataType: 'json',
    success: function(response){
      location.reload(); 
    }
  });
}

</script>
<?php
/* @var $this AreaElementoController */
/* @var $model AreaElemento */

//para combos de elementos
$lista_elementos = CHtml::listData($elementos, 'Id_Elemento', 'Elemento'); 

//para combos de áreas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area'); 

//para combos de subáreas
$lista_subareas = CHtml::listData($subareas, 'Id_Subarea', 'Subarea'); 

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#area-elemento-form");
    	var settings = form.data('settings') ;

      	settings.submitting = true ;
      	$.fn.yiiactiveform.validate(form, function(messages) {
          	if($.isEmptyObject(messages)) {
              	$.each(settings.attributes, function () {
                 	$.fn.yiiactiveform.updateInput(this,messages,form); 
               	});
                   
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
   	
</script>

<h4>Actualización de área / subárea a elemento</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_elementos'=>$lista_elementos, 'lista_areas'=>$lista_areas, 'lista_subareas'=>$lista_subareas)); ?>
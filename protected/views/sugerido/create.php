<?php
/* @var $this SugeridoController */
/* @var $model Sugerido */

//para combos de areas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area'); 

//para combos de subareas
$lista_subareas = CHtml::listData($subareas, 'Id_Subarea', 'Subarea'); 

//para combos de cargos
$lista_cargos = CHtml::listData($cargos, 'Id_Cargo', 'Cargo'); 

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#sugerido-form");
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

<h4>Creación de sugerido</h4>  

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_areas'=>$lista_areas, 'lista_subareas'=>$lista_subareas, 'lista_cargos'=>$lista_cargos)); ?>
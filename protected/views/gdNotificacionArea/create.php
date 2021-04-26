<?php
/* @var $this NotificacionAreaController */
/* @var $model NotificacionArea */

//para combos de areas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area');

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#gd-notificacion-area-form");
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


<h4>Creación de conf. para notificación de área</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_areas'=>$lista_areas)); ?>
<?php
/* @var $this IItemController */
/* @var $model IItem */

//para combos de lineas
$lista_lineas = CHtml::listData($lineas, 'Id', 'Descripcion'); 

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#iitem-form");
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

<h4>Creación de item</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_unidades'=>$lista_unidades, 'lista_lineas'=>$lista_lineas, 'id_asignar'=>$id_asignar)); ?>
<?php
/* @var $this ProveedorContController */
/* @var $model ProveedorCont */

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#proveedor-cont-form");
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

<h4>Actualización de proveedor</h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
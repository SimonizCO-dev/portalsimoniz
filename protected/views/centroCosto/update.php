<?php
/* @var $this CentroCostoController */
/* @var $model CentroCosto */

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#centro-costo-form");
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

<h4>Actualización centro de costo</h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
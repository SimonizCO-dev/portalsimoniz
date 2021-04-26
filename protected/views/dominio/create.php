<?php
/* @var $this DominioController */
/* @var $model Dominio */

//para combos de opciones padre
$lista_opciones_p = CHtml::listData($opciones_p, 'Id_Dominio', 'Dominio'); 

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#dominio-form");
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

<h4>Creación de dominio</h4>    
<?php $this->renderPartial('_form', array('model'=>$model, 'lista_opciones_p'=>$lista_opciones_p)); ?>  
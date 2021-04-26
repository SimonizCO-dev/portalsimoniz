<?php
/* @var $this MenuController */
/* @var $model Menu */

//para combos de opciones padre
$lista_opciones_p = array();
foreach ($opciones_p as $o) {
	$opc = Menu::model()->findByPk($o['Id_Menu']);
	$lista_opciones_p[$o['Id_Menu']] = $opc->DescOpcPadre($o['Id_Menu']);
}

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#menu-form");
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

<h4>Actualización opción de menú</h4> 
<?php $this->renderPartial('_form', array('model'=>$model, 'lista_opciones_p'=>$lista_opciones_p)); ?>
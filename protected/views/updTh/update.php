<?php
/* @var $this UpdThController */
/* @var $model UpdTh */


//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres'); 

?>

<script type="text/javascript">
$(function() {
	//se llenan las opciones seleccionadas del modelo
	$('#UpdTh_usuarios').val(<?php echo $json_usuarios_activos ?>).trigger('change');

	$("#valida_form").click(function() {
    	var form = $("#upd-th-form");
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

<h4>Actualización de permisos</h4>    
<?php $this->renderPartial('_form', array('model'=>$model, 'lista_usuarios'=>$lista_usuarios)); ?> 
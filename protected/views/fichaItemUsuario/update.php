<?php
/* @var $this FichaItemUsuarioController */
/* @var $model FichaItemUsuario */

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres');

?>

<script type="text/javascript">
$(function() {
   
	//se llenan las opciones seleccionadas del modelo
	$('#FichaItemUsuario_Id_Users_Reg').val(<?php echo $user_reg ?>).trigger('change');
	$('#FichaItemUsuario_Id_Users_Notif').val(<?php echo $user_not ?>).trigger('change');

	$("#valida_form").click(function() {

      	var form = $("#ficha-item-usuario-form");
		var settings = form.data('settings') ;
		
		settings.submitting = true ;
		$.fn.yiiactiveform.validate(form, function(messages) {
		  if($.isEmptyObject(messages)) {
		    $.each(settings.attributes, function () {
		        $.fn.yiiactiveform.updateInput(this,messages,form); 
		    });

	        //se envia el form
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

<h4>Actualización de usuarios por proceso</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_usuarios'=>$lista_usuarios)); ?>
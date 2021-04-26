<?php
/* @var $this HerramientaController */
/* @var $model Herramienta */

?>

<script>

$(function() {

	
	var extensionesValidas = ".png, .jpeg, .jpg, .PNG, .JPEG, .JPG";
	var textExtensionesValidas = "(.png, jgep, jpg)";
	var pesoPermitido = 512;
	var idInput = "valid_file";
	var idMsg = "error_file";

	$("#valida_form").click(function() {
      var form = $("#herramienta-form");
      var settings = form.data('settings') ;

      var soporte = $('#Herramienta_Imagen').val();

      if(soporte == ''){  
      	$('#error_file').html('Soporte es requerido.');
      	$('#error_file').show();
      }

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              	
              //se valida si el archivo cargado es valido (1)
              valid_file = $('#valid_file').val();

              if(valid_file == 1){
              	//se envia el form
              	form.submit();
                loadershow();
              }else{

              	settings.submitting = false ;	
              }
              

          } else {
              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              settings.submitting = false ;
          }
      });
  	});

  	$("#Herramienta_Imagen").change(function () {

  		$('#error_file').html('');
    	$('#error_file').hide();
    	$('#img').attr('src', "");
    	$('#vista_previa').fadeOut('fast');

  		if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
          	verImagen(this);
            $('#valid_file').val(1);

          }
      }   
    });

    // Vista preliminar de la imagen.
	function verImagen(datos) {

	    if (datos.files && datos.files[0]) {

	        var reader = new FileReader();

	        reader.onload = function (e) {

	            $('#vista_previa').fadeIn('fast');
	            $('#img').attr('src', e.target.result);
	        };

	        reader.readAsDataURL(datos.files[0]);

	    }

	}

});
   	
</script>

<h4>Creación de herramienta</h4> 
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
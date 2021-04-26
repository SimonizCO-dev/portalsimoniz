<?php
/* @var $this HerramientaController */
/* @var $model Herramienta */

?>

<script type="text/javascript">

$(function() {

	var extensionesValidas = "JPEG, .JPG, .jpeg, .jpg";
	var textExtensionesValidas = "(.jpeg, .jpg)";
	var idInput = "valid_sop";
	var idMsg = "error_sop";

	var pesoPermitido = 512;

	$("#valida_form").click(function() {
	    var form = $("#control-recibos-form");
	    var settings = form.data('settings') ;

	    var soporte = $('#ControlRecibos_Recibo').val();

	    if(soporte == ''){
	      $('#error_sop').html('Recibo es requerido.');
	      $('#error_sop').show();
	    }

	    settings.submitting = true ;
	    $.fn.yiiactiveform.validate(form, function(messages) {
	        if($.isEmptyObject(messages)) {
	            $.each(settings.attributes, function () {
	               $.fn.yiiactiveform.updateInput(this,messages,form); 
	            });
	            	
	            //se valida si el archivo cargado es valido
	            valid_sop = $('#valid_sop').val();

	            if(valid_sop == 1){
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


    $("#ControlRecibos_Recibo").change(function () {

  		$('#error_sop').html('');
    	$('#error_sop').hide();

  		if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
        	verImagen(this);
          }else{
          	$('#valid_sop').val(0);
        	$('#ControlRecibos_Recibo').val('');
          }
      }   
    });

    // Vista preliminar de la imagen.
  	function verImagen(datos) {

		if (datos.files && datos.files[0]) {

		  var reader = new FileReader();

		  reader.onload = function (e) {

		      $('#img').attr('src', e.target.result);
		      $('#valid_sop').val(1);
		  };

		  reader.readAsDataURL(datos.files[0]);

		}

  	}

});
	
</script>

<h4>Modificación de recibo <?php echo $model->Recibo ?></h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
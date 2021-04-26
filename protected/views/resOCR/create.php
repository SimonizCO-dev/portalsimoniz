<?php
/* @var $this ResOCRController */
/* @var $model ResOCR */

?>

<script type="text/javascript">


$(function() {

	var extensionesValidas = ".zip, .ZIP";
	var textExtensionesValidas = "(.zip)";
	var pesoPermitido = 30720;
	var idInput = "valid_file";
	var idMsg = "error_file";

	$("#valida_form").click(function() {
      var form = $("#res-ocr-form");
      var settings = form.data('settings') ;

      var soporte = $('#ResOCR_sop').val();

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

  	$("#ResOCR_sop").change(function () {

  		$('#error_file').html('');
    	$('#error_file').hide();

  		if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

			if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
	
				$('#valid_file').val(1);

			}
		}   
    });

});

	
</script>

<h4>Carga resumen ordenes de compra / remisiones</h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
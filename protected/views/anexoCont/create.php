<?php
/* @var $this AnexoContController */
/* @var $model AnexoCont */

?>

<script type="text/javascript">

$(function() {

	var extensionesValidas = ".pdf";
	var textExtensionesValidas = "(.pdf)";
	var idInput = "valid_sop";
	var idMsg = "error_sop";
	var pesoPermitido = 10240;

	$("#valida_form").click(function() {
      var form = $("#anexo-cont-form");
      var settings = form.data('settings') ;

      var soporte = $('#AnexoCont_sop').val();

      if(soporte == ''){
      	$('#error_sop').html('Soporte es requerido.');
      	$('#error_sop').show();
      }

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              	
              //se valida si el archivo cargado es valido (1)
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

	$("#AnexoCont_sop").change(function () {

		$('#error_sop').html('');
	  	$('#error_sop').hide();

			if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

	        if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
	          $('#valid_sop').val(1);
	        }
	    }   
	});

});

	
</script>

<h4>Asociando anexo a contrato</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'c'=>$c)); ?>
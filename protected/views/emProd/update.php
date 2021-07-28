<?php
/* @var $this EmProdController */
/* @var $model EmProd */

?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script type="text/javascript">


$(function() {

	var extensionesValidas = ".pdf";
	var textExtensionesValidas = "(.pdf)";
	var idInput = "valid_sop";
	var idMsg = "error_sop";
	var pesoPermitido = 10240;

	$("#valida_form").click(function() {
      var form = $("#em-prod-form");
      var settings = form.data('settings') ;

      var soporte = $('#EmProd_sop').val();

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              	
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

  	$("#EmProd_sop").change(function () {

		$('#error_sop').html('');
	  	$('#error_sop').hide();

			if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

	        if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
	          $('#valid_sop').val(1);
	        }
	    }   
	});

    renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/emision_prod/'.$model->Documento; ?>');

	$('#toogle_button').click(function(){
   
      $('#viewer').toggle('fast');
      $('#info').toggle('fast');

      return false;

  	});

  	$("#download").click(function() {
        $('#link')[0].click();
    });

});

	
</script>

<div class="row mb-2">
    <div class="col-sm-4">
        <h4>Detalle emisión de producto</h4>
    </div>
    <div class="col-sm-8 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprod/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar emisión</button>
	    <button type="button" class="btn btn-primary btn-sm" id="download"><i class="fas fa-file-pdf"></i> Descargue e imprima aquí la emisión</button>
	    <div style="display: none;">
	        <a href="<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/emision_prod/'.$model->Documento; ?>" download="<?php echo $model->Documento; ?>" style="display: none;" id="link"></a>
	    </div>
        <button type="button" class="btn btn-primary btn-sm" id="envio_notif"><i class="fas fa-envelope"></i> Enviar notificación</button>
	    <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>    
    </div>
</div>

<?php $this->renderPartial('_form2', array('model'=>$model, 'usuarios_val'=>$usuarios_val)); ?>
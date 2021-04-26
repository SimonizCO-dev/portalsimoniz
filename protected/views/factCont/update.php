<?php
/* @var $this FactContController */
/* @var $model FactCont */

//para combos de areas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area');

?>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script type="text/javascript">

$(function() {

	var sop = '<?php echo $model->Doc_Soporte; ?>';
	var extensionesValidas = ".pdf";
	var textExtensionesValidas = "(.pdf)";
	var idInput = "valid_sop";
	var idMsg = "error_sop";
	var pesoPermitido = 2048;

	if(sop != ""){

	    renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/fact_cont/'.$model->Doc_Soporte; ?>');

	    $('#toogle_button').click(function(){
	        $('#info').slideToggle('fast');
	        $('#viewer').slideToggle('fast');
	        return false;
	    });

	}

  $("#valida_form").click(function() {
    var form = $("#fact-cont-form");
    var settings = form.data('settings') ;

    var soporte = $('#FactCont_sop').val();

    settings.submitting = true ;
    $.fn.yiiactiveform.validate(form, function(messages) {
        if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
               $.fn.yiiactiveform.updateInput(this,messages,form); 
            });
              
            //se valida si el archivo cargado es valido (1)
            valid_doc = $('#valid_sop').val();

            if(valid_doc == 1){
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

  $("#FactCont_sop").change(function () {

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

<?php $this->renderPartial('_form2', array('model'=>$model, 'lista_areas'=>$lista_areas)); ?>

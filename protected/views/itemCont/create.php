<?php
/* @var $this ItemContController */
/* @var $model ItemCont */

//para combos de monedas
$lista_monedas = CHtml::listData($monedas, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

$(function() {

	$("#valida_form").click(function() {
	    var form = $("#item-cont-form");
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

<h4>Asociando item a contrato</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'c'=>$c, 'lista_monedas'=>$lista_monedas)); ?>
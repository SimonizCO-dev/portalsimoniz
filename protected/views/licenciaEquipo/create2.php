<?php
/* @var $this LicenciaEquipoController */
/* @var $model LicenciaEquipo */

?>

<script type="text/javascript">


$(function() {

	$("#valida_form").click(function() {
      var form = $("#licencia-equipo-form");
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

<h4>Asociando equipo a licencia</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'l' => $l)); ?>
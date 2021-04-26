<?php
/* @var $this NetworkController */
/* @var $model Network */

?>

<script type="text/javascript">

$(function() {

	$("#valida_form").click(function() {
      var form = $("#network-form");
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

<h4>Asignación de IP(s) por DHCP</h4>

<?php $this->renderPartial('_form3', array('model'=>$model, 'lista_ips_disp'=>$lista_ips_disp)); ?>
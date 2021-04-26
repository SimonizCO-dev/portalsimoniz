<?php
/* @var $this CuentaEmpleadoController */
/* @var $model CuentaEmpleado */

?>

<script type="text/javascript">

$(function() {

  $("#valida_form").click(function() {

      var form = $("#cuenta-empleado-form");
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

<h4>Vinculando empleado a cuenta / usuario</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'modelo_cuenta'=>$modelo_cuenta)); ?>
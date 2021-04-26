<?php
/* @var $this NucleoEmpleadoController */
/* @var $model NucleoEmpleado */

//para combos de géneros
$lista_generos = CHtml::listData($generos, 'Id_Dominio', 'Dominio');

//para combos de parentescos
$lista_parentescos = CHtml::listData($parentescos, 'Id_Dominio', 'Dominio');

?>

<script>

$(function() {

    $("#valida_form").click(function() {
      var form = $("#nucleo-empleado-form");
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

<h4>Actualización pariente de empleado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'e'=>$e, 'lista_generos'=>$lista_generos, 'lista_parentescos'=>$lista_parentescos)); ?>
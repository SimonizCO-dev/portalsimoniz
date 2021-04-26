<?php
/* @var $this EvaluacionEmpleadoController */
/* @var $model EvaluacionEmpleado */

//para combos de tipos de evaluación
$lista_tipos_ev = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

?>

<script>

$(function() {

    $("#valida_form").click(function() {
      var form = $("#evaluacion-empleado-form");
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

<h4>Registro evaluación de empleado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'e' => $e, 'lista_tipos_ev'=>$lista_tipos_ev)); ?>
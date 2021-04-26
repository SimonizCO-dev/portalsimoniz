<?php
/* @var $this FormacionEmpleadoController */
/* @var $model FormacionEmpleado */

//para combos de niveles
$lista_niveles = CHtml::listData($niveles, 'Id_Dominio', 'Dominio');

?>

<script>

$(function() {

  var extensionesValidas = ".pdf";
  var textExtensionesValidas = "(.pdf)";
  var pesoPermitido = 2048;
  var idInput = "valid_sop";
  var idMsg = "error_sop";

	$("#valida_form").click(function() {
      var form = $("#formacion-empleado-form");
      var settings = form.data('settings') ;

      var soporte = $('#FormacionEmpleado_Soporte').val();

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              	
              //se valida si el archivo cargado es valido (1)
              valid_file = $('#valid_sop').val();

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

  	$("#FormacionEmpleado_Soporte").change(function () {

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

<h4>Creación registro de formación empleado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'e' => $e, 'lista_niveles' => $lista_niveles)); ?>
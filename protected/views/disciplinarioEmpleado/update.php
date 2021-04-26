<?php
/* @var $this DisciplinarioEmpleadoController */
/* @var $model DisciplinarioEmpleado */

//para combos de motivos
$lista_motivos = CHtml::listData($motivos, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

$(function() {

  $("#valida_form").click(function() {
    var form = $("#disciplinario-empleado-form");
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

  //variables para el lenguaje del datepicker
  $.fn.datepicker.dates['es'] = {
      days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
      daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
      daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
      months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
      monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
      today: "Hoy",
      clear: "Limpiar",
      format: "yyyy-mm-dd",
      titleFormat: "MM yyyy",
      weekStart: 1
  };

  $("#DisciplinarioEmpleado_Fecha").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
      startDate: $("#fecha_min").val(),
  });

});

</script>

<?php 
if($opc == 1) {
	echo '<h4>Actualización llamado de atención a empleado</h4>';
}

if($opc == 2) {
	echo '<h4>Actualización de sanción a empleado</h4>';
}

if($opc == 3) {
	echo '<h4>Actualización de comparendo a empleado</h4>';
}

?>

<?php $this->renderPartial('_form2', array('model'=>$model, 'lista_motivos'=>$lista_motivos, 'opc'=>$opc, 'e'=>$e, 'fecha_min' => $fecha_min)); ?>
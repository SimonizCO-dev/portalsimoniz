<?php
/* @var $this ActividadController */
/* @var $model Actividad */

$fecha_act = date("Y-m-d");
$fecha = date("Y-m-d",strtotime($fecha_act."- 1 days")); 

//para combos de grupos
$lista_grupos = $grupos;

?>

<script>

$(function() {

    $('#Actividad_Prioridad').val(3).trigger('change');

    $("#valida_form").click(function() {
      var form = $("#actividad-form");
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

    $("#Actividad_Id_Grupo").change(function () {
      vlr = $("#Actividad_Id_Grupo").val();
      if(vlr != ""){
        var data = {grupo: vlr, clasificacion: 1}
        $.ajax({ 
          type: "POST", 
          url: "<?php echo Yii::app()->createUrl('actividad/gettipos'); ?>",
          data: data,
          dataType: 'json',
          success: function(data){ 
            $("#Actividad_Id_Tipo").html('');
            $("#Actividad_Id_Tipo").append('<option value=""></option>');
            $.each(data, function(i,item){
                $("#Actividad_Id_Tipo").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
            });
            $("#div_tipo").show();
          }
        });
      }else{
        $("#Actividad_Id_Tipo").val('');
        $("#div_tipo").hide();
      }
    });

    $("#Actividad_Id_Tipo").change(function () {
      vlr = $("#Actividad_Id_Tipo").val();
      if(vlr != ""){
        var data = {tipo: vlr}
        $.ajax({ 
          type: "POST", 
          url: "<?php echo Yii::app()->createUrl('actividad/getusuariosact'); ?>",
          data: data,
          dataType: 'json',
          success: function(data){ 
            $("#Actividad_Id_Usuario").html('');
            $("#Actividad_Id_Usuario").append('<option value=""></option>');
            $.each(data, function(i,item){
                $("#Actividad_Id_Usuario").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
            });
            $("#div_usuario").show();
          }
        });
      }else{
        $("#Actividad_Id_Usuario").val('');
        $("#div_usuario").hide();
      }
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

    $("#Actividad_Fecha").datepicker({
        language: 'es',
        autoclose: true,
        orientation: "right bottom",
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date.valueOf());
      $('#Actividad_Fecha_Finalizacion').datepicker('setStartDate', minDate);
    });

    $("#Actividad_Fecha_Finalizacion").datepicker({
        language: 'es',
        autoclose: true,
        orientation: "right bottom",
    }).on('changeDate', function (selected) {
      var maxDate = new Date(selected.date.valueOf());
      $('#Actividad_Fecha').datepicker('setEndDate', maxDate);
    });

});

</script>

<h4>Creación de actividad</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_grupos'=>$lista_grupos)); ?>

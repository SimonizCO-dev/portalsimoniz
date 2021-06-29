<?php
/* @var $this ActividadController */
/* @var $model Actividad */

//para combos de grupos
$lista_grupos = $grupos;

?>

<script>

$(function() {

    $("#valida_form").click(function() {

      limp_div_msg();

      var form = $("#actividad-form");
      var settings = form.data('settings');

      var val_form = 1;
          
      var dias_selected = '';
      var horas_selected = ''; 
      var obs_selected = '';

      var checkboxes_checked = document.querySelectorAll('input.custom-control-input:checked').length;

      if(checkboxes_checked == 0){
        var val_form = 0; 
      }else{
        $("input.custom-control-input:checked").each(function() {

          var dia = $(this).attr("data-id");
          var horas = $('#horas_'+dia).val();
          var obs = $('#obs_'+ dia).val();
         
          dias_selected += dia+'|'; 
          horas_selected += horas+'|'; 
          obs_selected += obs+'|';

          if(horas == "" || horas < 0.5 || horas > 24 || obs == ""){
            val_form = 2;
          }
    
        });
      }

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this,messages,form); 
            });
                
            if(val_form == 1){

              var cadena_dias = dias_selected.slice(0,-1);
              var cadena_horas = horas_selected.slice(0,-1);
              var cadena_obs = obs_selected.slice(0,-1);
              
              $('#Actividad_cad_dias').val(cadena_dias);
              $('#Actividad_cad_horas').val(cadena_horas);
              $('#Actividad_cad_obs').val(cadena_obs);

              //se envia el form
              form.submit();
              loadershow();
              //alert();
            }else{
              if(val_form == 0){
                $('html, body').animate({scrollTop:0}, 'fast');
                $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Debe seleccionar por lo menos 1 día de novedad.');
                $("#div_mensaje").fadeIn('fast');
                $(".ajax-loader").fadeOut('fast');
              }

              if(val_form == 2){
                $('html, body').animate({scrollTop:0}, 'fast');
                $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Hay horas y/o observaciones vacias o no validas.');
                $("#div_mensaje").fadeIn('fast');
                $(".ajax-loader").fadeOut('fast');
              } 
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

    $("#Actividad_Id_Grupo").change(function () {
      vlr = $("#Actividad_Id_Grupo").val();
      if(vlr != ""){
        var data = {grupo: vlr, clasificacion: 2}
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
        format : 'yyyy-mm',
        titleFormat: "MM yyyy",
    };

    $("#Actividad_periodo").datepicker({
        language: 'es',
        autoclose: true,
        orientation: "right bottom",
        format: "yyyy-mm",
        startView: "year", 
        minViewMode: "months",
        startDate: $("#periodo_min").val(),
        endDate: $("#periodo_max").val(),
    }).on('changeDate', function (selected) {
      var m = new Date(selected.date.valueOf()).getMonth() + 1;
      calendardisp(m);
      $("#Actividad_mes").val(m);
    });

});

function calendardisp(m){

  limp_div_msg();

  $(".ajax-loader").fadeIn('fast');

  var div_contenido = $('#contenido');

  div_contenido.html('');

  div_contenido.append('<table id="table_items" class="table table-sm table-hover"><thead><tr><th>Fecha</th><th>Horas</th><th>Observaciones</th></tr></thead><tbody></tbody></table>');
  var data = {m: m}
  $.ajax({ 
      type: "POST", 
      url: "<?php echo Yii::app()->createUrl('actividad/getcalendar'); ?>",
      data: data,
      dataType: 'json',
      success: function(data){

          var tabla = $('#table_items');

          for(var key in data){

              var id = key;
              var fecha = data[key]['fecha'];
              var text_fecha = data[key]['text_fecha'];
              tabla.append('<tr class="tr_days" id="tr_'+id+'"><td><div class="custom-control custom-switch custom-switch-off-success custom-switch-on-danger"><input type="checkbox" class="custom-control-input" id="day_'+id+'" data-id="'+id+'" onChange="hab_day('+id+')"><label class="custom-control-label" for="day_'+id+'">'+text_fecha+'</label></div><td><input type="number" id="horas_'+id+'" min="0.5" max="24" step=".5" disabled/></td><td><textarea class="form-control form-control-sm" rows="1" cols="50" maxlength="5000" onkeyup="convert_may(this)" id="obs_'+id+'" disabled></textarea></td></tr>');
          }

        $(".ajax-loader").fadeOut('fast');

      }
  });
            
}

function hab_day(day){

  limp_div_msg();

  if($("#day_"+day).is(':checked')) {  
    $('#horas_'+day).removeAttr('disabled'); 
    $('#obs_'+day).removeAttr('disabled'); 
    $('#horas_'+day).focus();
  } else {  
    $('#horas_'+day).attr('disabled', 'disabled');
    $('#horas_'+day).val('');
    $('#obs_'+day).attr('disabled', 'disabled');
    $('#obs_'+day).val('');
  } 

}

</script>

<div id="div_mensaje" style="display: none;"></div>

<h4>Novedad de disponibilidad</h4>

<?php $this->renderPartial('_form3', array('model'=>$model, 'lista_grupos'=>$lista_grupos)); ?>

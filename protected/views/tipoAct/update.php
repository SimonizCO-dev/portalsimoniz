<?php
/* @var $this TipoActController */
/* @var $model TipoAct */

//para combos de grupos
$lista_grupos = CHtml::listData($grupos, 'Id_Dominio', 'Dominio');

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres');

?>

<script>

$(function() {

  $('#TipoAct_Usuarios').val(<?php echo $json_usuarios_tipact_activos ?>).trigger('change');
  
  $("#div_padre").show();
  loadopc(<?php echo $model->Id_Grupo; ?>);
  
	$("#valida_form").click(function() {
    	var form = $("#tipo-act-form");
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

    $("#TipoAct_Fecha_Inicio").datepicker({
        language: 'es',
        autoclose: true,
        orientation: "right bottom",
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date.valueOf());
      $('#TipoAct_Fecha_Fin').datepicker('setStartDate', minDate);
    });

    $("#TipoAct_Fecha_Fin").datepicker({
        language: 'es',
        autoclose: true,
        orientation: "right bottom",
    }).on('changeDate', function (selected) {
      var maxDate = new Date(selected.date.valueOf());
      $('#TipoAct_Fecha_Inicio').datepicker('setEndDate', maxDate);
    });

    $('#TipoAct_Id_Grupo').change(function() {
        
        $("#TipoAct_Padre").html('');
        $("#TipoAct_Padre").append('<option value=""></option>');  

        if($(this).val() != ""){
            $('#div_padre').show();
            loadopc($(this).val());
        }else{
            $('#div_padre').hide();
        }
    });

});

function loadopc(grupo){
    //debugger;
    var p = <?php echo $p ?>;
  
    var data = {grupo: grupo, id: <?php echo $model->Id_Tipo; ?>}
    $.ajax({ 
      type: "POST", 
      url: "<?php echo Yii::app()->createUrl('tipoAct/loadopc'); ?>",
      data: data,
      dataType: 'json',
      success: function(data){ 
        var opcs = data;
        $("#TipoAct_Padre").html('');
        $("#TipoAct_Padre").append('<option value=""></option>');
        $('#TipoAct_Padre').val('').trigger('change');
        $.each(opcs, function(i,item){
            $("#TipoAct_Padre").append('<option value="'+opcs[i].id+'">'+opcs[i].text+'</option>');
        });

        $("#div_padre").show();

        var p = <?php echo $p ?>;
  
        if(p != 0){
          $('#TipoAct_Padre').val(p).trigger('change');
        }

      }  
    });
}
   	
</script>

<h4>Actualización tipo de actividad</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_grupos' => $lista_grupos, 'lista_usuarios' => $lista_usuarios)); ?>
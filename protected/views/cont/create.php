<?php
/* @var $this ContController */
/* @var $model Cont */

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion');

//para combos de tipos de periodicidad
$lista_period = CHtml::listData($period, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

$(function() {

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

	$("#Cont_Fecha_Inicial").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
	   var minDate = new Date(selected.date.valueOf());
	   $('#Cont_Fecha_Final').datepicker('setStartDate', minDate);
	});

	$("#Cont_Fecha_Final").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
	   var maxDate = new Date(selected.date.valueOf());
	   $('#PcContrato_Fecha_Inicial').datepicker('setEndDate', maxDate);
     calcfecrencan();
	});

  	$("#Cont_Dias_Alerta").change(function() {
    	calcfecrencan();
  	});

  	function calcfecrencan(){
	    var fecha_final = $("#Cont_Fecha_Final").val();
	    var dias_ant = $("#Cont_Dias_Alerta").val();

	    if(fecha_final != "" && dias_ant != ""){
	      
	      fecha_ren_can = moment(fecha_final).subtract(dias_ant,'days').format('YYYY-MM-DD');

	      $("#Cont_Fecha_Ren_Can").val(fecha_ren_can); 

	    }else{
	      $("#Cont_Fecha_Ren_Can").val(''); 
	    }
	}

  	$("#valida_form").click(function() { 
    	var form = $("#cont-form");
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

<h4>Creación de contrato</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_empresas' => $lista_empresas, 'lista_period' => $lista_period)); ?>
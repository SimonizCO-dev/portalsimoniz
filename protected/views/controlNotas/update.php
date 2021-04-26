<?php
/* @var $this ControlNotasController */
/* @var $model ControlNotas */

?>

<script>

$(function() {

	$("#valida_form").click(function() {

		var form = $("#control-notas-form");
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

	$("#ControlNotas_Fecha_Factura").change(function() {
		var f_factura = this.value;
		var f_pago =  $("#ControlNotas_Fecha_Pago").val();

		if(f_factura != "" && f_pago != ""){
			var fecha_factura = moment(f_factura);
			var fecha_pago = moment(f_pago);
			$("#ControlNotas_Dias_Pago").val(fecha_pago.diff(fecha_factura, 'days'));
		}else{
			$("#ControlNotas_Dias_Pago").val('');
		}
		
  	});

  	$("#ControlNotas_Fecha_Pago").change(function() {
	   	f_factura =  $("#ControlNotas_Fecha_Factura").val();
	   	f_pago = this.value;

	   	if(f_factura != "" && f_pago != ""){
			var fecha_factura = moment(f_factura);
			var fecha_pago = moment(f_pago);
			$("#ControlNotas_Dias_Pago").val(fecha_pago.diff(fecha_factura, 'days'));
		}else{
			$("#ControlNotas_Dias_Pago").val('');
		}

  	});

  	$("#ControlNotas_Valor_Factura").change(function() {
 		var vlr_factura = this.value;
 		var porc_desc = $("#ControlNotas_Porc_Desc").val();

 		if(vlr_factura != "" && porc_desc != ""){
 			var porc_desc = ($("#ControlNotas_Porc_Desc").val()) / 100;
			var vlr_descuento = (vlr_factura / 1.19) * porc_desc;
			$("#ControlNotas_Valor_Descuento").val(Math.round(vlr_descuento));
		}else{
			$("#ControlNotas_Valor_Descuento").val('');
		}		

  	});

  	$("#ControlNotas_Porc_Desc").change(function() {
	   	var vlr_factura = $("#ControlNotas_Valor_Factura").val();
	   	var porc_desc = (this.value);

	   	if(vlr_factura != "" && porc_desc != ""){
	   		var porc_desc = (this.value) / 100;
			var vlr_descuento = (vlr_factura / 1.19) * porc_desc;
			$("#ControlNotas_Valor_Descuento").val(Math.round(vlr_descuento));
		}else{
			$("#ControlNotas_Valor_Descuento").val('');
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

	$("#ControlNotas_Fecha_Factura").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
		var minDate = new Date(selected.date.valueOf());
		$('#ControlNotas_Fecha_Pago').datepicker('setStartDate', minDate);
	});

	$("#ControlNotas_Fecha_Pago").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
		var maxDate = new Date(selected.date.valueOf());
		$('#ControlNotas_Fecha_Factura').datepicker('setEndDate', maxDate);
	});
  
});

</script>

<h4>Actualización de nota</h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this TurnoEmpleadoController */
/* @var $model TurnoEmpleado */

?>

<script type="text/javascript">

	$(function() {

	    $("#valida_form").click(function() {

	      valida_turno();
	      var form = $("#turno-empleado-form");
	      var settings = form.data('settings');
	      var valid = $("#valid").val();

	      settings.submitting = true ;
	      $.fn.yiiactiveform.validate(form, function(messages) {
	          if($.isEmptyObject(messages)) {
	            $.each(settings.attributes, function () {
	                $.fn.yiiactiveform.updateInput(this,messages,form); 
	            });

	            if(valid == 1){   
	            	//se envia el form
	            	form.submit();
      				loadershow();
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

		$("#TurnoEmpleado_Fecha_Inicial").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
	      startDate: $("#fecha_min").val(),
		}).on('changeDate', function (selected) {

		   if($("#TurnoEmpleado_Fecha_Inicial").val() > $("#TurnoEmpleado_Fecha_Final").val()){
		   	$("#TurnoEmpleado_Fecha_Final").val('');
		   }

		   var minDate = new Date(selected.date.valueOf());
		   $('#TurnoEmpleado_Fecha_Final').datepicker('setStartDate', minDate);
	       valida_turno();
		});

		$("#TurnoEmpleado_Fecha_Final").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
	      startDate: $("#fecha_min").val(),
		}).on('changeDate', function (selected) {

		   if($("#TurnoEmpleado_Fecha_Final").val() < $("#TurnoEmpleado_Fecha_Inicial").val()){
		   	$("#TurnoEmpleado_Fecha_Inicial").val('');
		   }

		   var maxDate = new Date(selected.date.valueOf());
		   $('#TurnoEmpleado_Fecha_Inicial').datepicker('setEndDate', maxDate);
		   valida_turno();
		}); 

	});

	function valida_turno(){
	    var fecha_inicial = $('#TurnoEmpleado_Fecha_Inicial').val();
	    var fecha_final = $('#TurnoEmpleado_Fecha_Final').val();
	    var empleado = <?php echo $e; ?>;

	    if(fecha_inicial != "" && fecha_final != ""){

		   	var data = {fecha_inicial: fecha_inicial, fecha_final: fecha_final, empleado: empleado}

		    $.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('turnoEmpleado/infoturnocre'); ?>",
				data: data,
				success: function(data){
		 			var res = data.split("|");
		 			var opc =res[0];
		 			var mensaje =res[1];
		 			if(opc == 1){
	 					$('#mensaje').text(mensaje);
	 					$('#div_mensaje').show();
	 					$('#valid').val(0);
		 			}else{
		 				$('#mensaje').text(mensaje);
	 					$('#div_mensaje').hide();
	 					$('#valid').val(1);	
		 			}
				}
			});
		}else{
			$('#valid').val(0);
		}

	}	

</script>

<div class="alert alert-warning alert-dismissible" id="div_mensaje" style="display: none;">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    <p id="mensaje"></p>
</div>

<h4>Registro turno de empleado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'e'=>$e, 'lista_t' => $lista_t, 'fecha_min' => $fecha_min)); ?>
<?php
/* @var $this AusenciaEmpleadoController */
/* @var $model AusenciaEmpleado */

//para combos de motivos
$lista_motivos = CHtml::listData($motivos, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

	$(function() {

	    $("#valida_form").click(function() {
	      var form = $("#ausencia-empleado-form");
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

		$("#AusenciaEmpleado_Fecha_Inicial").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
	      startDate: $("#fecha_min").val(),
	      endDate: $("#fecha_max").val(),
		}).on('changeDate', function (selected) {

	       if($("#AusenciaEmpleado_Fecha_Inicial").val() > $("#AusenciaEmpleado_Fecha_Final").val()){
	        $("#AusenciaEmpleado_Fecha_Final").val('');
	       }

		   var minDate = new Date(selected.date.valueOf());
		   $('#AusenciaEmpleado_Fecha_Final').datepicker('setStartDate', minDate);
	        valida_ausencia();
		});

		$("#AusenciaEmpleado_Fecha_Final").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
	      startDate: $("#fecha_min").val(),
		}).on('changeDate', function (selected) {

	       if($("#AusenciaEmpleado_Fecha_Final").val() < $("#AusenciaEmpleado_Fecha_Inicial").val()){
	        $("#AusenciaEmpleado_Fecha_Inicial").val('');
	       }

		}); 

	});

	function valida_ausencia(){
	    var fecha_inicial = $('#AusenciaEmpleado_Fecha_Inicial').val();
	    var id_ausencia = <?php echo $model->Id_Ausencia ?>;
	    var empleado = <?php echo $e; ?>;
		
		var data = {fecha_inicial: fecha_inicial, id_ausencia: id_ausencia, empleado: empleado}

	    $.ajax({ 
			type: "POST", 
			url: "<?php echo Yii::app()->createUrl('ausenciaEmpleado/infoausenciaact'); ?>",
			data: data,
			success: function(data){
	 			var res = data.split("|");
	 			var opc =res[0];
	 			var mensaje =res[1];
	 			if(opc == 1){
 					$('#mensaje').text(mensaje);
 					$('#div_mensaje').show();
	 			}else{
	 				$('#mensaje').text(mensaje);
 					$('#div_mensaje').hide();	
	 			}
			}
		});

	}

</script>

<div class="alert alert-warning alert-dismissible" id="div_mensaje" style="display: none;">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    <p id="mensaje"></p>
</div>

<h4>Actualización ausencia de empleado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'e'=>$e, 'lista_motivos' => $lista_motivos, 'fecha_min' => $fecha_min)); ?>
<?php
/* @var $this ContratoEmpleadoController */
/* @var $model ContratoEmpleado */

?>

<script type="text/javascript">
	$(function() {

		$("#valida_form").click(function() {
	      var form = $("#contrato-empleado-form");
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

		$("#ContratoEmpleado_Fecha_Liquidacion").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
		  startDate: '<?php echo $model->Fecha_Retiro ?>',
		});

	});

</script>

<h4>Asignación fecha de liquidación contrato de empleado</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'e' => $e, 'unidad_gerencia'=>$unidad_gerencia, 'area'=>$area, 'subarea'=>$subarea, 'cargo'=>$cargo, 'centro_costo'=>$centro_costo, 'salario_flexible'=>$salario_flexible)); ?>
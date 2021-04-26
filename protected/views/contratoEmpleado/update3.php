<?php
/* @var $this ContratoEmpleadoController */
/* @var $model ContratoEmpleado */

//para combos de turnos
$lista_turnos = CHtml::listData($turnos, 'Id_Dominio', 'Dominio');

//para combos de conceptos de examen
$lista_concep_exa_ocup = CHtml::listData($concep_exa_ocup, 'Id_Dominio', 'Dominio');

//para combos de grupos
$lista_grupos = CHtml::listData($grupos, 'Id_Dominio', 'Dominio');

//para combos de trabajos esp.
$lista_trabajos_esp = CHtml::listData($trabajos_esp, 'Id_Dominio', 'Dominio');

?>

<h4>Actualización contrato de empleado</h4>

<?php $this->renderPartial('_form3', array('model'=>$model, 'e' => $e, 'unidad_gerencia'=>$unidad_gerencia, 'area'=>$area, 'subarea'=>$subarea, 'cargo'=>$cargo,'lista_turnos' => $lista_turnos, 'lista_concep_exa_ocup'=>$lista_concep_exa_ocup, 'lista_grupos' => $lista_grupos, 'lista_trabajos_esp'=>$lista_trabajos_esp, 'centro_costo'=>$centro_costo, 'salario_flexible'=>$salario_flexible)); ?>

<script type="text/javascript">
	$(function() {

		//se llenan las opciones seleccionadas del modelo
		
		$('#ContratoEmpleado_Id_Trab_Esp').val(<?php echo $json_te_act ?>).trigger('change');

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

	});

</script>
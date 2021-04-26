<?php
/* @var $this ContratoEmpleadoController */
/* @var $model ContratoEmpleado */

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion');

//para combos de unidades de gerencia
$lista_ug = CHtml::listData($unidades_gerencia, 'Id_Unidad_Gerencia', 'Unidad_Gerencia');

//para combos de áreas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area');

//para combos de subáreas
$lista_subareas = CHtml::listData($subareas, 'Id_Subarea', 'Subarea');

//para combos de cargos
$lista_cargos = CHtml::listData($cargos, 'Id_Cargo', 'Cargo');

//para combos de turnos
$lista_turnos = CHtml::listData($turnos, 'Id_Dominio', 'Dominio');

//para combos de conceptos de examen
$lista_concep_exa_ocup = CHtml::listData($concep_exa_ocup, 'Id_Dominio', 'Dominio');

//para combos de grupos
$lista_grupos = CHtml::listData($grupos, 'Id_Dominio', 'Dominio');

//para combos de trabajos esp.
$lista_trabajos_esp = CHtml::listData($trabajos_esp, 'Id_Dominio', 'Dominio');

?>

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

<h4>Actualización contrato de empleado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'e'=>$e, 'lista_empresas'=>$lista_empresas, 'lista_ug'=>$lista_ug, 'lista_areas'=>$lista_areas, 'lista_subareas'=>$lista_subareas, 'lista_cargos'=>$lista_cargos, 'fecha_ingreso_min' => $fecha_ingreso_min, 'lista_turnos' => $lista_turnos, 'lista_concep_exa_ocup'=>$lista_concep_exa_ocup, 'lista_grupos' => $lista_grupos, 'lista_trabajos_esp'=>$lista_trabajos_esp, 'lista_cc'=>$lista_cc)); ?>


<?php
/* @var $this UsuarioController */
/* @var $model Usuario */

//para combos de perfiles
$lista_perfiles = CHtml::listData($m_perfiles, 'Id_Perfil', 'Descripcion'); 

//para combos de empresas
$lista_empresas = CHtml::listData($m_empresas, 'Id_Empresa', 'Descripcion'); 

//para combos de áreas
$lista_areas = CHtml::listData($m_areas, 'Id_Area', 'Area'); 

//para combos de subáreas
$lista_subareas = CHtml::listData($m_subareas, 'Id_Subarea', 'Subarea'); 

//para combos de niveles de detalle mod. empleado
$lista_niveles = CHtml::listData($m_niveles_detalle, 'Id_Dominio', 'Dominio'); 

//para combos de bodegas
$lista_bodegas = CHtml::listData($m_bodegas, 'Id', 'Descripcion'); 

//para combos tipos de docto
$lista_tipos_docto = CHtml::listData($m_tipos_docto, 'Id', 'Descripcion');

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#usuario-form");
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

<h4>Creación de usuario</h4>    
<?php $this->renderPartial('_form', array('model'=>$model, 'lista_perfiles'=>$lista_perfiles,'lista_empresas'=>$lista_empresas, 'lista_areas' => $lista_areas, 'lista_subareas' => $lista_subareas, 'lista_niveles'=>$lista_niveles, 'lista_bodegas'=>$lista_bodegas, 'lista_tipos_docto'=>$lista_tipos_docto)); ?>  


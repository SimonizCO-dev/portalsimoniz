<?php
/* @var $this FichaItemController */
/* @var $model FichaItem */

?>

<script type="text/javascript">
$(function() {

	var step = <?php echo $s; ?>;
    var e = <?php echo $e; ?>;
    div_step(step, e);

    $('#FichaItem_Pais').val(1).trigger('change');
	$('#FichaItem_Codigo_Item').attr("disabled", true);
	$('#div_info').show();

	$("#valida_form").click(function() {
      var form = $("#ficha-item-form");
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

	$("#rechazar_form").click(function() {
		var opcion = confirm("Desea solicitar una revisión de los datos registrados?");
	    if (opcion == true) {
	    	loadershow();
	       	location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/notas&id='.$model->Id; ?>';
	   	} 
	});

	$("#aprobar_form").click(function() {
    	loadershow();
       	location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/aprobar&id='.$model->Id; ?>';
	});

});

function div_step(step, e){

    if(step == 6){
        //verificación comercial
        if(e == 1){
		  	//campos habilitados
		  	//$('#FichaItem_Tiempo_Reposicion').removeAttr('disabled');
	        //$('#FichaItem_Cant_Moq').removeAttr('disabled');
	        //$('#FichaItem_Stock_Minimo').removeAttr('disabled');
	        $('#FichaItem_Crit_Origen').removeAttr('disabled');
	        $('#FichaItem_Crit_Tipo').removeAttr('disabled');
	        $('#FichaItem_Crit_Clasificacion').removeAttr('disabled');
	        $('#FichaItem_Crit_Clase').removeAttr('disabled');
	        $('#FichaItem_Crit_Marca').removeAttr('disabled');
	        $('#FichaItem_Crit_Submarca').removeAttr('disabled');
	        $('#FichaItem_Crit_Segmento').removeAttr('disabled');
	        $('#FichaItem_Crit_Familia').removeAttr('disabled');
	        $('#FichaItem_Crit_Subfamilia').removeAttr('disabled');
	        $('#FichaItem_Crit_Linea').removeAttr('disabled');
	        $('#FichaItem_Crit_Sublinea').removeAttr('disabled');
	        $('#FichaItem_Crit_Grupo').removeAttr('disabled');
	        $('#FichaItem_Crit_UN').removeAttr('disabled');
	        $('#FichaItem_Crit_Fabrica').removeAttr('disabled');
	        $('#FichaItem_Crit_Cat_Oracle').removeAttr('disabled');
	  	}else{
	  		$("#buttons_1").html('');
	  	}

	  	$('#div_cants').hide();
           
    }

    if(step == 9){
        //verificación comercial
        if(e == 0){
	  		$("#buttons_1").html('');
	  	}else{
	  		$('#FichaItem_Tiempo_Reposicion').removeAttr('disabled');
	        $('#FichaItem_Cant_Moq').removeAttr('disabled');
	        $('#FichaItem_Stock_Minimo').removeAttr('disabled');
	  	}
           
    }

    if(step == 10){
    	$("#buttons_1").html('');
    }
}

</script>

<div class="row mb-2">
  <div class="col-sm-9">
    <h4>Revisión solicitud actualización de ítem</h4>
  </div>
  <div class="col-sm-3 text-right"> 
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
  </div>
</div>

<?php 
$this->renderPartial('_form2', array(
	'model'=>$model,
	'lista_origen'=>$lista_origen,
	'lista_tipo'=>$lista_tipo,
	'lista_clasif'=>$lista_clasif,
	'lista_clase'=>$lista_clase,
	'lista_marca'=>$lista_marca,
	'lista_submarca'=>$lista_submarca,
	'lista_segmento'=>$lista_segmento,
	'lista_familia'=>$lista_familia,
	'lista_linea'=>$lista_linea,
	'lista_subfamilia'=>$lista_subfamilia,
	'lista_sublinea'=>$lista_sublinea,
	'lista_grupo'=>$lista_grupo,
	'lista_un'=>$lista_un,
	'lista_fabrica'=>$lista_fabrica,
	'lista_oracle'=>$lista_oracle,
	's'=>$s,
)); 

?>
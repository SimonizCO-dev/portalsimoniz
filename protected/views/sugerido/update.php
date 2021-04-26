<?php
/* @var $this SugeridoController */
/* @var $model Sugerido */

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#sugerido-form");
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

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Actualización de sugerido</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=sugerido/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <?php if($model->Estado == 1){ ?>
            <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=elementosugerido/create&s='.$model->Id_Sugerido; ?>';"><i class="fa fa-plus"></i> Agregar elemento</button>
        <?php } ?>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->renderPartial('_form2', array('model'=>$model, 'elem_asoc'=>$elem_asoc)); ?>
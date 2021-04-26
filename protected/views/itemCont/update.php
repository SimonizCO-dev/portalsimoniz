<?php
/* @var $this ItemContController */
/* @var $model ItemCont */

//para combos de monedas
$lista_monedas = CHtml::listData($monedas, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

$(function() {

	$("#valida_form").click(function() {
	    var form = $("#item-cont-form");
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

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Actualizando item de contrato</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/view&id='.$c; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>     
    </div>
</div>

<?php $this->renderPartial('_form2', array('model'=>$model, 'c'=>$c, 'lista_monedas'=>$lista_monedas,'historial'=>$historial)); ?>
<?php
/* @var $this IDoctoController */
/* @var $model IDocto */

?>

<script type="text/javascript">
	
$(function() {
	$("#valida_form").click(function() {
		var form = $("#idocto-form");
		var empleado = $('#IDocto_Id_Emp').val();
		var notas = $('#IDocto_Notas').val();

		var tipo = <?php echo $model->Id_Tipo_Docto; ?>;
		var aje = <?php echo Yii::app()->params->aje; ?>;
		var ajs = <?php echo Yii::app()->params->ajs; ?>;
		var sad = <?php echo Yii::app()->params->sad; ?>;

		var settings = form.data('settings') ;
		settings.submitting = true ;
		 $.fn.yiiactiveform.validate(form, function(messages) {
			if($.isEmptyObject(messages)) {
			  	$.each(settings.attributes, function () {
		     		$.fn.yiiactiveform.updateInput(this,messages,form); 
			  	});
				
				if(tipo == aje || tipo == ajs || tipo == sad){
					if(tipo == aje || tipo == ajs){
						//ajuste entrada / salida
						if(notas == ""){
							$('#IDocto_Notas_em_').html('Notas es requerido.');
	            			$('#IDocto_Notas_em_').show();
	            			settings.submitting = false ;
						}else{
							$('#IDocto_Notas_em_').html('');
	            			$('#IDocto_Notas_em_').hide();
	            			//se envia el form
						    showloader();
			                form.submit();	
						}	
					}

					if(tipo == sad){
						//salida de dotación
						if(empleado == ""){
							$('#IDocto_Id_Emp_em_').html('Empleado es requerido.');
	            			$('#IDocto_Id_Emp_em_').show();
	            			settings.submitting = false ;
						}else{
							$('#IDocto_Id_Emp_em_').html('');
	            			$('#IDocto_Id_Emp_em_').hide();
	            			//se envia el form
						    showloader();
			                form.submit();
						}
					}


				}else{
					//se envia el form
				   	showloader();
	                form.submit();	
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
});

</script>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Modificación de documento</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=idocto/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=idoctomovto/create&id='.$model->Id; ?>';"><i class="fa fa-plus"></i> Agregar item</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>
  </div>
</div>

<?php $this->renderPartial('_form2', array('model'=>$model, 'detalle' => $detalle)); ?>
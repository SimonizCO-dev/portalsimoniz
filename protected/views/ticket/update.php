<?php
/* @var $this TicketController */
/* @var $model Ticket */

?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#ticket-form");
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

    $('#Ticket_Estado').change(function() {
      
      var value = $(this).val();
      $('#Ticket_Notas').val('');
        
      if(value != ""){

        if(value == 4){
           //CERRADA
          $('#notas').show();
        }

        if(value == 2 || value == 3){
          //ASIGNADA, EN PROCESO
          $('#notas').hide();

        }

      }else{
        $("#notas").hide();
      }
     
    });

});
   	
</script>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Resumen de ticket ID <?php echo $model->Id_Ticket; ?></h4>
  </div>
  <div class="col-sm-6 text-right">  
  	<?php if($model->Soporte != null){ ?>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-soporte"><i class="fas fa-image"></i> Ver Soporte</button>
   	<?php } ?>
  	<button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ticket/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button> 
  	<button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>  
  </div>
</div>


<?php $this->renderPartial('_form2', array('model'=>$model, 'hist'=>$hist, 'usuarios_asig'=>$usuarios_asig, 'estados'=>$estados)); ?>
<?php
/* @var $this SolPromController */
/* @var $model SolProm */

?>

<script>

$(function() {

    $(".ajax-loader").show();

    var e = <?php echo $e; ?>;
    div_step(e);

    $("#valida_form").click(function() {
        
      var form = $("#sol-prom-form");
      var settings = form.data('settings');

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

    $("#SolProm_Tipo").change(function() {
  		var tipo = $(this).val();

  		$('#SolProm_Cliente').val('').trigger('change');
	    $('#s2id_SolProm_Cliente span').html("");
	    $('#SolProm_Kit').val('').trigger('change');
	    $('#s2id_SolProm_Kit span').html("");

	  	if(tipo != ""){
      		$("#info_cliente_item").show();
	 	}else{

	 		$("#info_cliente_item").hide();
	 	}

	});

});

function div_step(e){

    //inicio

    if(e == 1){
        //campos habilitados
        $('#SolProm_Responsable').removeAttr('disabled');
        $('#SolProm_Tipo').removeAttr('disabled');
        $('#SolProm_Cant').removeAttr('disabled');
        $('#SolProm_Observaciones').removeAttr('disabled');

        $("#buttons_1").html('<button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Crear</button>');
    }

    $("#collapse_2").hide();
    $("#collapse_3").hide();
    $("#collapse_4").hide();
    $("#collapse_5").hide();

    $('#link_collapse_1').addClass('card-warning').removeClass('card-secondary');
    $('#link_collapse_2').addClass('card-danger').removeClass('card-secondary');
    $('#link_collapse_3').addClass('card-danger').removeClass('card-secondary');
    $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');

    $('#img_info_1').addClass('fa-circle').removeClass('fa-clock');
    
    $(".ajax-loader").fadeOut('fast');
}

</script>

<div class="row mb-2">
  <div class="col-sm-9">
    <h4>Solicitud de promoción</h4>
  </div>
  <div class="col-sm-3 text-right"> 
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=solprom/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
  </div>
</div>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos)); ?>
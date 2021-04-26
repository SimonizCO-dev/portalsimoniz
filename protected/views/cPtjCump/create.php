<?php
/* @var $this CPtjCumpController */
/* @var $model CPtjCump */

//para combo de estados de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

?>

<script type="text/javascript">

$(function() {

    $("#valida_form").click(function() {

        var form = $("#cptj-cump-form");
        var settings = form.data('settings') ;

        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
            if($.isEmptyObject(messages)) {
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                var tipo = $("#CPtjCump_TIPO").val();
                var cum_inicial = parseFloat($("#CPtjCump_CUM_INICIAL").val());
                var cum_final = parseFloat($("#CPtjCump_CUM_FINAL").val());

                if(cum_inicial >= cum_final){

                	$("#CPtjCump_CUM_INICIAL_em_").html('Cump. inicial debe ser menor a Cump. final');
                	$("#CPtjCump_CUM_INICIAL_em_").show('');

                } else {

                	$("#CPtjCump_CUM_INICIAL_em_").html('');
                	$("#CPtjCump_CUM_INICIAL_em_").hide('');

	                var data = {tipo: tipo, cum_inicial: cum_inicial, cum_final: cum_final}
	                $.ajax({ 
	                  type: "POST", 
	                  url: "<?php echo Yii::app()->createUrl('CPtjCump/verifconfig'); ?>",
	                  data: data,
	                  dataType: 'json',
	                  success: function(data){
	                    var valid =data['valid'];
	                    var id =data['id'];
	                    if(valid == 0){
	                        $('#mensaje').text('Ya existe un porcentaje de cumplimiento con estos parametros (ID '+id+'), para poder crearla debe inactivar el registro indicado.');
	                        $('#div_mensaje').show();
	                        settings.submitting = false ;
	                    }else{
	                        //se envia el form
	                        form.submit();
							loadershow();
	                    }

	                  }  
	                });
                }
             
            } else {
                settings = form.data('settings'),
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });
                
                settings.submitting = false;
          }
      });
    });

    $('#CPtjCump_TIPO').change(function() {
        hidemsg();
    });

    $('#CPtjCump_PORCENTAJE').change(function() {
        hidemsg();
    });

    $('#CPtjCump_CUM_INICIAL').change(function() {
        hidemsg();
    });

    $('#CPtjCump_CUM_FINAL').change(function() {
        hidemsg();
    });
});

function hidemsg(){
    $('#mensaje').text('');
    $('#div_mensaje').hide();
    $("#CPtjCump_CUM_INICIAL_em_").html('');
	$("#CPtjCump_CUM_INICIAL_em_").hide('');
}

</script>

<div class="alert alert-warning alert-dismissible" id="div_mensaje" style="display: none;">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    <p id="mensaje"></p>
</div>

<h4>Creación de porcentaje cumplimiento</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos)); ?>
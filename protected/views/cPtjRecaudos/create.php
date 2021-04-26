<?php
/* @var $this CPtjRecaudosController */
/* @var $model CPtjRecaudos */

//para combo de estados de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

?>

<script type="text/javascript">

$(function() {

    $("#valida_form").click(function() {
        var form = $("#cptj-recaudos-form");
        var settings = form.data('settings') ;


        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
            if($.isEmptyObject(messages)) {
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                var tipo = $("#CPtjRecaudos_TIPO").val();
                var dia_inicial = parseInt($("#CPtjRecaudos_DIA_INICIAL").val());
                var dia_final = parseInt($("#CPtjRecaudos_DIA_FINAL").val());

                if(dia_inicial >= dia_final){

                	$("#CPtjRecaudos_DIA_INICIAL_em_").html('Dia inicial debe ser menor a Dia final');
                	$("#CPtjRecaudos_DIA_INICIAL_em_").show('');

                } else {

                	$("#CPtjRecaudos_DIA_INICIAL_em_").html('');
                	$("#CPtjRecaudos_DIA_INICIAL_em_").hide('');

                	//var tipo = $("#CPtjVentas_TIPO").val();
	                var data = {tipo: tipo, dia_inicial: dia_inicial, dia_final: dia_final}
	                $.ajax({ 
	                  type: "POST", 
	                  url: "<?php echo Yii::app()->createUrl('CPtjRecaudos/verifconfig'); ?>",
	                  data: data,
	                  dataType: 'json',
	                  success: function(data){
	                    var valid =data['valid'];
	                    var id =data['id'];
	                    if(valid == 0){
	                        $('#mensaje').text('Ya existe un porcentaje de recaudo con estos parametros (ID '+id+'), para poder crearla debe inactivar el registro indicado.');
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

    $('#CPtjRecaudos_TIPO').change(function() {
        hidemsg();
    });

    $('#CPtjRecaudos_PORCENTAJE').change(function() {
        hidemsg();
    });

    $('#CPtjRecaudos_DIA_INICIAL').change(function() {
        hidemsg();
    });

    $('#CPtjRecaudos_DIA_FINAL').change(function() {
        hidemsg();
    });
});

function hidemsg(){
    $('#mensaje').text('');
    $('#div_mensaje').hide();
    $("#CPtjRecaudos_DIA_INICIAL_em_").html('');
	$("#CPtjRecaudos_DIA_INICIAL_em_").hide('');
}

</script>

<div class="alert alert-warning alert-dismissible" id="div_mensaje" style="display: none;">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    <p id="mensaje"></p>
</div>

<h4>Creación de porcentaje recuado</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos)); ?>

<?php
/* @var $this CPtjVentasController */
/* @var $model CPtjVentas */

//para combo de estados de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

?>

<script type="text/javascript">

$(function() {

    $("#valida_form").click(function() {
        var form = $("#cptj-ventas-form");
        var settings = form.data('settings') ;

        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
            if($.isEmptyObject(messages)) {
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                //var porc = $("#CAceleradorCms_PORCENTAJE").val();
                var tipo = $("#CPtjVentas_TIPO").val();

                var data = {tipo: tipo}
                $.ajax({ 
                  type: "POST", 
                  url: "<?php echo Yii::app()->createUrl('cPtjVentas/verifconfig'); ?>",
                  data: data,
                  dataType: 'json',
                  success: function(data){
                    var valid =data['valid'];
                    var id =data['id'];
                    if(valid == 0){
                        $('#mensaje').text('Ya existe un porcentaje de venta con estos parametros (ID '+id+'), para poder crearla debe inactivar el registro indicado.');
                        $('#div_mensaje').show();
                        settings.submitting = false ;
                    }else{
                        //se envia el form
                        form.submit();
						loadershow();
                    }

                  }  
                });

             
            } else {
                settings = form.data('settings'),
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });
                
                settings.submitting = false;
          }
      });
    });

    $('#CPtjVentas_TIPO').change(function() {
        hidemsg();
    });

    $('#CPtjVentas_PORCENTAJE').change(function() {
        hidemsg();
    });
});

function hidemsg(){
    $('#mensaje').text('');
    $('#div_mensaje').hide();
}

</script>

<div class="alert alert-warning alert-dismissible" id="div_mensaje" style="display: none;">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    <p id="mensaje"></p>
</div>

<h4>Creación de porcentaje venta</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos)); ?>
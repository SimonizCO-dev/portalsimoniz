<?php
/* @var $this PedComEnvioController */
/* @var $model PedComEnvio */

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres'); 

?>

<script>

$(function() {

    $("#valida_form").click(function() {

        $('#PedComEnvio_Emails_em_').html('');
        $('#PedComEnvio_Emails_em_').hide('');      

        var form = $("#ped-com-envio-form");
        var settings = form.data('settings') ;
        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this,messages,form); 
            });

            var cad_emails_adic = $('#PedComEnvio_Emails').val();

            var data = {
              cad_emails_adic: cad_emails_adic
            }

            //se validan los email adic.
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('PedComEnvio/validemailsadic'); ?>",
                data: data,
                success: function(resp){
                    var valid = resp;
                    if(valid == 0){
                        $('#PedComEnvio_Emails_em_').html('Hay E-mails no validos.');
                        $('#PedComEnvio_Emails_em_').show('');
                    }else{
                        
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
              settings.submitting = false ;
          }
        });
    });

});

</script>

<h4>Actualización configuración envío por vendedor</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_usuarios'=>$lista_usuarios)); ?>
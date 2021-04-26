    <?php
/* @var $this WipController */
/* @var $model Wip */

?>

<div id="div_mensaje" style="display: none;"></div>

<h3>Envío de WIP</h3>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'wip-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>


<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'WIP'); ?>
            <?php echo '<p>'.$model->WIP.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label>Cadena / Observaciones</label>
            <?php echo '<p>'.$model->desccadena($model->ID).'</p>' ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'firma', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'firma', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'firma', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'cargo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'cargo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'cargo', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'correos_notif', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'correos_notif', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textArea($model,'correos_notif',array('class' => 'form-control', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_min(this)')); ?>
        </div>
    </div> 
</div>

<div class="row mb-4">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=wip/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-paper-plane" ></i> Enviar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

    $("#valida_form").click(function() {

        $('#Wip_correos_notif_em_').html('');
        $('#Wip_correos_notif_em_').hide('');      

        var form = $("#wip-form");
        var settings = form.data('settings') ;
        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this,messages,form); 
            });

            var cad_emails_adic = $('#Wip_correos_notif').val();


            var data = {
              cad_emails_adic: cad_emails_adic
            }

            //se validan los email adic.
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('wip/validemailsadic'); ?>",
                data: data,
                success: function(resp){
                    var valid = resp;
                    if(valid == 0){
                        $('#Wip_correos_notif_em_').html('Hay E-mails no validos.');
                        $('#Wip_correos_notif_em_').show('');
                    }else{
                        var firma = $('#Wip_firma').val();
                        var cargo = $('#Wip_cargo').val();

                        envionotif(<?php echo $model->ID; ?>, firma, cargo, cad_emails_adic);
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

function envionotif(id, firma, cargo, cadena_emails_adic){

    var data = {
      id: id,
      firma: firma,
      cargo: cargo, 
      cadena_emails_adic: cadena_emails_adic
    }

    $.ajax({ 
        type: "POST", 
        url: "<?php echo Yii::app()->createUrl('wip/envionotifwip'); ?>",
        data: data,
        beforeSend: function(){
            $(".ajax-loader").fadeIn('fast'); 
        },
        success: function(resp){

            if(resp == 0){
              $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
              $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h4><i class="icon fa fa-info-circle"></i>Cuidado</h4>No se envío ningún detalle, por favor verifique los Emails.');
            }else{
              //EL PROCESO GENERO NINGUNA LIQUIDACIÓN
              $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
              $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h4><i class="icon fa fa-check"></i>Realizado</h4>Se envio el WIP <?php echo $model->WIP ?> a '+resp+' Email.');  
            }
            
            $("#div_mensaje").fadeIn('fast');
            $(".ajax-loader").fadeOut('fast');
            $('#Wip_firma').val('');
            $('#Wip_cargo').val('');
            $('#Wip_correos_notif').val('');
          
        }
    });
}

</script>




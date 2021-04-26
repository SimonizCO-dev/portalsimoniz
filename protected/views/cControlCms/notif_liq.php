<?php
/* @var $this CControlCmsController */
/* @var $model CControlCms */

?>

<div id="div_mensaje" style="display: none;"></div>

<h4>Resumen de liquidación / envío de detalle</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ccontrol-cms-form',
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
            <?php echo $form->label($model,'ROWID'); ?>
            <?php echo '<p>'.$model->ROWID.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ID_BASE'); ?>
            <?php echo '<p>'.$model->ID_BASE.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'MES'); ?>
            <?php echo '<p>'.$model->Desc_Mes($model->MES).'</p>' ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ANIO'); ?>
            <?php echo '<p>'.$model->ANIO.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'TIPO'); ?>
            <?php echo '<p>'.$model->tipo->Dominio.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'LIQUIDACION'); ?>
            <?php echo '<p>'.$model->Desc_Liq($model->LIQUIDACION).'</p>' ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'VENDEDOR'); ?><br>
            <?php if(is_null($model->VENDEDOR)){ echo '<p>N/A</p>'; }else{ echo '<p>'.$model->Desc_Vend($model->VENDEDOR).'</p>'; } ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ID_USUARIO_CREACION'); ?>
            <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'FECHA_CREACION'); ?>
            <?php echo '<p>'.UtilidadesVarias::textofechahora($model->FECHA_CREACION).'</p>' ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ID_USUARIO_ACTUALIZACION'); ?><br>
            <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'FECHA_ACTUALIZACION'); ?>
            <?php echo '<p>'.UtilidadesVarias::textofechahora($model->FECHA_ACTUALIZACION).'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'ESTADO'); ?>
            <?php echo '<p>'.UtilidadesVarias::textoestado1($model->ESTADO).'</p>' ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'Correos_Notif', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Correos_Notif'); ?>
            <?php echo $form->textArea($model,'Correos_Notif',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_min(this)')); ?>
        </div>
    </div> 
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CControlCms/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

    $("#valida_form").click(function() {

        $('#CControlCms_Correos_Notif_em_').html('');
        $('#CControlCms_Correos_Notif_em_').hide('');      

        var form = $("#ccontrol-cms-form");
        var settings = form.data('settings') ;
        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this,messages,form); 
            });

            var cad_emails_adic = $('#CControlCms_Correos_Notif').val();

            if(cad_emails_adic != ""){

                var data = {
                  cad_emails_adic: cad_emails_adic
                }

                //se validan los email adic.
                $.ajax({ 
                    type: "POST", 
                    url: "<?php echo Yii::app()->createUrl('ccontrolcms/validemailsadic'); ?>",
                    data: data,
                    success: function(resp){
                        var valid = resp;
                        if(valid == 0){
                            $('#CControlCms_Correos_Notif_em_').html('Hay E-mails no validos.');
                            $('#CControlCms_Correos_Notif_em_').show('');
                        }else{
                            envionotif(<?php echo $model->ID_BASE; ?>, cad_emails_adic);
                        }      
                    }
                });   
            }else{
                envionotif(<?php echo $model->ID_BASE; ?>, "");
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

function envionotif(id_base, cadena_emails_adic){

    var data = {
      id_base: id_base, 
      cadena_emails_adic: cadena_emails_adic
    }

    $.ajax({ 
        type: "POST", 
        url: "<?php echo Yii::app()->createUrl('ccontrolcms/envionotifliq'); ?>",
        data: data,
        beforeSend: function(){
            $(".ajax-loader").fadeIn('fast'); 
        },
        success: function(resp){

            if(resp == 0){
              $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
              $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5><p>No se envío ningún detalle, por favor verifique el e-mail de los vendedores y/o adicionales.</p>');
            }else{
              //EL PROCESO GENERO NINGUNA LIQUIDACIÓN
              $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
              $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-check-circle"></i>Realizado</h5><p>Se enviaron '+resp+' detalles.</p>');  
            }
            
            $("#div_mensaje").fadeIn('fast');
            $(".ajax-loader").fadeOut('fast');
            $('#CControlCms_Correos_Notif').val('');
          
        }
    });
}

//función para limpiar el mensaje retornado por el ajax
function limp_div_msg(){
    $("#div_mensaje").hide();  
    classact = $('#div_mensaje').attr('class');
    $("#div_mensaje").removeClass(classact);
    $("#mensaje").html('');
}

</script>




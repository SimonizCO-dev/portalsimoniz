<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */
/* @var $form CActiveForm */
?>

<h4>Asignación de evaluador por colaborador</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Empleado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); 

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres');

?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->label($model,'empleado'); ?>
            <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($model->Id_Empleado).'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Eval', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Eval', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Empleado[Id_Usuario_Eval]',
                    'id'=>'Empleado_Id_Usuario_Eval',
                    'data'=>$lista_usuarios,
                    'value'=>$model->Id_Usuario_Eval,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>


<div class="row mb-2">
    <div class="col-sm-6">  
        <?php if($opc == 1){ ?>
            <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>   
        <?php }else{ ?>
            <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/asige'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <?php } ?>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>
    
<?php $this->endWidget(); ?>

<script type="text/javascript">

$(function() {

    $("#valida_form").click(function() {

        var form = $("#Empleado-form");
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
                
                settings.submitting = false;
          }
      });
    });
});

</script>
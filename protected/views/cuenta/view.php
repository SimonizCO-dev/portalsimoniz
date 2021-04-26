<?php
/* @var $this CuentaController */
/* @var $model Cuenta */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'cuenta-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Visualizando cuenta</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cuenta/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="view_p"><i class="fas fa-key"></i> Ver password actual</button>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Clasificacion'); ?><br>
            <p><?php echo $model->clasificacion->Dominio; ?></p>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="pull-right badge bg-red" id="error_dup" style="display: none;"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4" id="div_cuenta_usuario" style="display: none;">
        <div class="form-group">
            <div class="pull-right badge bg-red" id="error_cuenta_usuario" style="display: none;"></div>
            <?php echo $form->label($model,'Cuenta_Usuario'); ?><br>
            <p><?php echo $model->Cuenta_Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_dominio" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Dominio'); ?><br>
            <p><?php if($model->Dominio != "" ){ echo $model->dominioweb->Dominio; } ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_tipo_cuenta" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Tipo_Cuenta'); ?><br>
            <p><?php if($model->Tipo_Cuenta != "" ){ echo $model->tipocuenta->Dominio; } ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_tipo_acceso" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Tipo_Acceso'); ?><br>
            <p><?php if($model->Tipo_Acceso != "" ){ echo $model->DescTipoAcceso($model->Tipo_Acceso); } ?></p>
        </div>
    </div>
    <div class="col-sm-8" id="div_observaciones" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Observaciones'); ?><br>
            <p><?php if($model->Observaciones != "" ){ echo $model->Observaciones; }else{ echo '-'; } ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_ext" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Ext'); ?>
            <p><?php if($model->Ext != "" ){ echo $model->Ext; }else{ echo '-'; } ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_estado" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Estado'); ?><br>
            <p><?php echo $model->estado->Dominio; ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion'); ?><br>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>                
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion'); ?><br>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>                  
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?><br>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>                     
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion'); ?><br>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>                    
        </div>
    </div>
</div>

<h4>Detalle</h4>

<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#emp">Empleados asoc.</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#log">Log</a></li>
</ul>

<!-- Tab panes -->
 <div class="tab-content">
    <div id="emp" class="tab-pane active"><br>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'cuenta-empleado-grid',
            'dataProvider'=>$emp_asoc->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                array(
                    'name'=>'Id_Empleado',
                    'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::nombreempleado($data->Id_Empleado)',
                ),
                array(
                    'header'=>'Empresa',
                    'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::empresaactualempleado($data->Id_Empleado)',
                ),
                array(
                    'header'=>'Cargo',
                    'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::cargoactualempleado($data->Id_Empleado)',
                ),
                array(
                    'name' => 'Estado',
                    'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
                ),   
            ),
        ));

        ?>   
    </div>
    <div id="log" class="tab-pane"><br>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'cuenta-empleado-grid',
            'dataProvider'=>$nov_cue->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                'Novedades',
                array(
                    'name'=>'Id_Usuario_Creacion',
                    'value'=>'$data->idusuariocre->Usuario',
                ),
                array(
                    'name'=>'Fecha_Creacion',
                    'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
                ),
            ),
        ));

        ?>   
    </div>
 </div>

 <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <p align="center"><?php echo $pass; ?></p>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php $this->endWidget(); ?>

<script type="text/javascript">
  
$(function() {
    var clase = <?php echo $model->Clasificacion; ?>;

    if(clase == <?php echo Yii::app()->params->c_correo ?>){
        $('#div_cuenta_usuario').show();
        $('#div_dominio').show(); 
        $('#div_tipo_cuenta').show(); 
        $('#div_tipo_acceso').hide();
        $('#div_observaciones').show();
        $('#div_estado').show();
        
        var tipo_cuenta = <?php echo $model->Tipo_Cuenta; ?>;

        if(tipo_cuenta == <?php echo Yii::app()->params->t_c_generico ?>){
            $('#div_ext').show();
        }else{
            $('#div_ext').hide();
        }

    }else{
        $('#div_cuenta_usuario').show();
        $('#div_dominio').hide(); 
        $('#div_tipo_cuenta').hide(); 
        $('#div_tipo_acceso').show();
        $('#div_observaciones').show();
        $('#div_estado').show();
        $('#div_ext').hide();
    }

    $("#view_p").click(function() {
        $('#myModal').modal({show:true});
    });

});



</script>

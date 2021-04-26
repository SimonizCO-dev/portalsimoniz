<?php
/* @var $this DominioWebController */
/* @var $model DominioWeb */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dominio-web-form',
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
            <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Tipo'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'DominioWeb[Id_Tipo]',
                    'id'=>'DominioWeb_Id_Tipo',
                    'data'=>$lista_tipos,
                    'value' => $model->Id_Tipo,
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
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Dominio', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Dominio'); ?>
            <?php echo $form->textField($model,'Dominio', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Link', array('class' => 'badge badge-warning float-right')); ?>
          	<?php echo $form->label($model,'Link'); ?>
		    <?php echo $form->textField($model,'Link', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Usuario', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Usuario'); ?>
            <?php echo $form->textField($model,'Usuario', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Password', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Password'); ?>
            <?php echo $form->textField($model,'Password', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Empresa_Administradora', array('class' => 'badge badge-warning float-right')); ?>
          	<?php echo $form->label($model,'Empresa_Administradora'); ?>
		    <?php echo $form->textField($model,'Empresa_Administradora', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Contacto_Emp_Adm', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Contacto_Emp_Adm'); ?>
            <?php echo $form->textField($model,'Contacto_Emp_Adm', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <div class="form-group">
                <?php echo $form->error($model,'Contratado_Por', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Contratado_Por'); ?>
                <?php echo $form->textField($model,'Contratado_Por', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
            </div> 
        </div>
    </div>
	<div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Uso', array('class' => 'badge badge-warning float-right')); ?>
          	<?php echo $form->label($model,'Uso'); ?>
		    <?php echo $form->textField($model,'Uso', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
        </div>
    </div>   
</div> 
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Activacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Activacion'); ?>
            <?php echo $form->textField($model,'Fecha_Activacion', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Vencimiento', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Vencimiento'); ?>
            <?php echo $form->textField($model,'Fecha_Vencimiento', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Estado'); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'DominioWeb[Estado]',
                    'id'=>'DominioWeb_Estado',
                    'data'=>$estados,
                    'value' => $model->Estado,
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
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Observaciones'); ?>
            <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50)); ?>
        </div>
    </div>
</div>
<?php if(!$model->isNewRecord){ ?>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>

<?php } ?>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=dominioweb/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>







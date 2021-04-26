<?php
/* @var $this ResOCRController */
/* @var $model ResOCR */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'res-ocr-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array(
		'enctype' => 'multipart/form-data'
	),
)); ?>


<div class="row">
	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Tipo'); ?>
            <?php $tipos = array(1 => 'ORDENES DE COMPRA', 2 => 'REMISIONES');  ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ResOCR[Tipo]',
                    'id'=>'ResOCR_Tipo',
                    'data'=>$tipos,
                    'value' => $model->Tipo,
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
	<div class="col-sm-8">
	  	<div class="form-group">
			<?php echo $form->error($model,'Descripcion', array('class' => 'badge badge-warning float-right')); ?>
	 		<?php echo $form->label($model,'Descripcion'); ?>
 			<?php echo $form->textArea($model,'Descripcion',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
	  	</div>
  	</div>	
</div>
<div class="row">
	<div class="col-sm-8">
		<div class="form-group">
			<?php echo $form->error($model,'sop', array('class' => 'badge badge-warning float-right')); ?>
  			<div class="badge badge-warning float-right" id="error_file" style="display: none;"></div>
  			<input type="hidden" id="valid_file" value="1">
  			<?php echo $form->label($model,'sop'); ?><br>
	    	<?php echo $form->fileField($model, 'sop'); ?>
    	</div>
  	</div>
  	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Estado'); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ResOCR[Estado]',
                    'id'=>'ResOCR_Estado',
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

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=resocr/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

<?php $this->endWidget(); ?>
<?php
/* @var $this DocumentoController */
/* @var $model Documento */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gd-documento-form',
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
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'areas', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'areas', array('class' => 'badge badge-warning float-right')); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
            'name'=>'GdDocumento[areas]',
            'id'=>'GdDocumento_areas',
            'data'=>$lista_areas,
            'htmlOptions'=>array(
                        'multiple'=>'multiple',
                    ),
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
          <?php echo $form->label($model,'Clasificacion', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Clasificacion', array('class' => 'badge badge-warning float-right')); ?>
          <?php $clasif = Yii::app()->params->clasif_docs; ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                'name'=>'GdDocumento[Clasificacion]',
                'id'=>'GdDocumento_Clasificacion',
                'data'=>$clasif,
                'value' => $model->Clasificacion,
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
      <?php echo $form->label($model,'Tipo', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
            'name'=>'GdDocumento[Tipo]',
            'id'=>'GdDocumento_Tipo',
            'data'=>$lista_tipos,
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
</div>
<div class="row"> 
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Num_Documento', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Num_Documento', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Num_Documento', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div> 
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Titulo', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Titulo', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Titulo', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'onlynumbersletters(this);')); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'Descripcion', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Descripcion', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textArea($model,'Descripcion',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Elaborado_Por', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Elaborado_Por', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Elaborado_Por', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Fecha_Elaboracion', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Fecha_Elaboracion', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Fecha_Elaboracion', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
    </div>
  </div> 
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Nivel_Revision', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Nivel_Revision', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->numberField($model,'Nivel_Revision', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
      </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Fecha_Revision', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Fecha_Revision', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Fecha_Revision', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Emitido_Por', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Emitido_Por', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Emitido_Por', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div> 
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Fecha_Emision', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Fecha_Emision', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->textField($model,'Fecha_Emision', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->label($model,'Aprobado_Por', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Aprobado_Por', array('class' => 'badge badge-warning float-right')); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
            'name'=>'GdDocumento[Aprobado_Por]',
            'id'=>'GdDocumento_Aprobado_Por',
            'data'=>$lista_unidades,
            'value' => $model->Aprobado_Por,
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
        <?php echo $form->label($model,'Permite_Descarga', array('class' => 'control-label')); ?>
        <?php echo $form->error($model,'Permite_Descarga', array('class' => 'badge badge-warning float-right')); ?>
        <?php $estados2 = Yii::app()->params->estados2; ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'GdDocumento[Permite_Descarga]',
                'id'=>'GdDocumento_Permite_Descarga',
                'data'=>$estados2,
                'value' => $model->Permite_Descarga,
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
        <?php echo $form->label($model,'Copia_Controlada', array('class' => 'control-label')); ?>
        <?php echo $form->error($model,'Copia_Controlada', array('class' => 'badge badge-warning float-right')); ?>
        <?php $estados2 = Yii::app()->params->estados2; ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'GdDocumento[Copia_Controlada]',
                'id'=>'GdDocumento_Copia_Controlada',
                'data'=>$estados2,
                'value' => $model->Copia_Controlada,
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
      <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
      <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
      <?php $estados = Yii::app()->params->estados; ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
            'name'=>'GdDocumento[Estado]',
            'id'=>'GdDocumento_Estado',
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
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'doc_consulta', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_doc_consulta" style="display: none;"></div><br>
      <input type="hidden" id="valid_doc_consulta" value="1">
      <?php echo $form->fileField($model, 'doc_consulta'); ?>
      <?php echo $form->hiddenField($model, 'ext_doc_consulta'); ?>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'doc_descarga', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_doc_descarga" style="display: none;"></div><br>
      <input type="hidden" id="valid_doc_descarga" value="1">
      <?php echo $form->fileField($model, 'doc_descarga'); ?>
      <?php echo $form->hiddenField($model, 'ext_doc_descarga'); ?>
    </div>
  </div>
</div>
<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=gddocumento/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>

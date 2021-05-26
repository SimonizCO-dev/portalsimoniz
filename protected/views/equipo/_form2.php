<?php
/* @var $this EquipoController */
/* @var $model Equipo */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'equipo-form',
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
            <?php echo $form->error($model,'Tipo_Equipo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Tipo_Equipo'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Equipo[Tipo_Equipo]',
                    'id'=>'Equipo_Tipo_Equipo',
                    'data'=>$lista_tipos,
                    'value' => $model->Tipo_Equipo,
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
      		<?php echo $form->error($model,'Serial', array('class' => 'badge badge-warning float-right')); ?>
      		<?php echo $form->label($model,'Serial'); ?>
      		<?php echo $form->textField($model,'Serial', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      	</div>
  	</div>
    <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Modelo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Modelo'); ?>
          <?php echo $form->textField($model,'Modelo', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'MAC1', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'MAC1'); ?>
        <?php echo $form->textField($model,'MAC1', array('class' => 'form-control form-control-sm', 'maxlength' => '17', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      </div>
  </div>
  <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'MAC2', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'MAC2'); ?>
        <?php echo $form->textField($model,'MAC2', array('class' => 'form-control form-control-sm', 'maxlength' => '17', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      </div>
  </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Empresa_Compra', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Empresa_Compra'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Equipo[Empresa_Compra]',
                    'id'=>'Equipo_Empresa_Compra',
                    'data'=>$lista_empresas,
                    'value' => $model->Empresa_Compra,
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
          <?php echo $form->error($model,'Fecha_Compra', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Fecha_Compra'); ?>
          <?php echo $form->textField($model,'Fecha_Compra', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
    	</div>
  	</div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Proveedor', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Proveedor'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Equipo[Proveedor]',
                    'id'=>'Equipo_Proveedor',
                    'data'=>$lista_proveedores,
                    'value' => $model->Proveedor,
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
          <?php echo $form->error($model,'Numero_Factura', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Numero_Factura'); ?>
          <?php echo $form->textField($model,'Numero_Factura', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Numero_Inventario', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Numero_Inventario'); ?>
          <?php echo $form->textField($model,'Numero_Inventario', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Num_Usuarios', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Num_Usuarios'); ?>
          <?php echo $form->numberField($model,'Num_Usuarios', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => 1, 'max' => 100)); ?>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Estado'); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Equipo[Estado]',
                    'id'=>'Equipo_Estado',
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
    <div class="col-sm-8">
      <div class="form-group">
        <?php echo $form->label($model,'sop', array('class' => 'control-label')); ?>
        <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
        <input type="hidden" id="valid_sop" value="1">
        <?php echo $form->fileField($model, 'sop'); ?>
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
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=equipo/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>

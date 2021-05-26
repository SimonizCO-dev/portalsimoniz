<?php
/* @var $this ContController */
/* @var $model Cont */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cont-form',
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
          <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Tipo'); ?>
          <?php $lista_tipos = array(1 => 'CLIENTE', 2 => 'PROVEEDOR'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Cont[Tipo]',
                  'id'=>'Cont_Tipo',
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
	  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'Empresa', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Empresa'); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Cont[Empresa]',
                  'id'=>'Cont_Empresa',
                  'data'=>$lista_empresas,
                  'value' => $model->Empresa,
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
    		<?php echo $form->error($model,'Razon_Social', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Razon_Social'); ?>
    		<?php echo $form->textField($model,'Razon_Social', array('class' => 'form-control form-control-sm form-control form-control-sm-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    	</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Concepto_Contrato', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Concepto_Contrato'); ?>
    		<?php echo $form->textField($model,'Concepto_Contrato', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    	</div>
	</div>
	  <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Contacto', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Contacto'); ?>
    		<?php echo $form->textField($model,'Contacto', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    	</div>
	</div>
	<div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Telefono_Contacto', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Telefono_Contacto'); ?>
    		<?php echo $form->textField($model,'Telefono_Contacto', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    	</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Email_Contacto', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Email_Contacto'); ?>
    		<?php echo $form->textField($model,'Email_Contacto', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
    	</div>
	</div>
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->error($model,'Periodicidad', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Periodicidad'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Cont[Periodicidad]',
                'id'=>'Cont_Periodicidad',
                'data'=>$lista_period,
                'value' => $model->Periodicidad,
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
          <?php echo $form->error($model,'Area', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Area'); ?>
          <?php echo $form->textField($model,'Area', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'Fecha_Inicial', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Fecha_Inicial'); ?>
          <?php echo $form->textField($model,'Fecha_Inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
      </div>
  </div>
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'Fecha_Final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Fecha_Final'); ?>
          <?php echo $form->textField($model,'Fecha_Final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
      </div>
  </div>
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'Dias_Alerta', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Dias_Alerta'); ?>
          <?php echo $form->numberField($model,'Dias_Alerta', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
      </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'Fecha_Ren_Can', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Fecha_Ren_Can'); ?>
          <?php echo $form->textField($model,'Fecha_Ren_Can', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
      </div>
  </div>
	<div class="col-sm-4">
    	<div class="form-group">
          <?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Observaciones'); ?>
          <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
    	</div>
	</div>
	<div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Estado'); ?>
        <?php $estados = Yii::app()->params->estados; ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Cont[Estado]',
                'id'=>'Cont_Estado',
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
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
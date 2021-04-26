<?php
/* @var $this CuentaController */
/* @var $model Cuenta */
/* @var $form CActiveForm */
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

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_clasificacion" style="display: none;"></div>
            <?php echo $form->label($model,'Clasificacion'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Cuenta[Clasificacion]',
                    'id'=>'Cuenta_Clasificacion',
                    'data'=>$lista_clases,
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
   		<div class="badge badge-warning float-right" id="error_dup" style="display: none;"></div>
   	</div>
</div>
<div class="row">
	<div class="col-sm-4" id="div_cuenta_usuario" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_cuenta_usuario" style="display: none;"></div>
            <?php echo $form->label($model,'Cuenta_Usuario'); ?>
            <?php echo $form->textField($model,'Cuenta_Usuario', array('class' => 'form-control form-control-sm', 'maxlength' => '30', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4" id="div_dominio" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_dominio" style="display: none;"></div>
            <?php echo $form->label($model,'Dominio'); ?>
            <?php
        		$this->widget('ext.select2.ESelect2',array(
					'name'=>'Cuenta[Dominio]',
					'id'=>'Cuenta_Dominio',
					'data'=>$lista_dominios,
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
    <div class="col-sm-4" id="div_password" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_password" style="display: none;"></div>
            <?php echo $form->label($model,'Password'); ?>
            <?php echo $form->textField($model,'Password', array('class' => 'form-control form-control-sm', 'maxlength' => '30', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4" id="div_tipo_cuenta" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_tipo_cuenta" style="display: none;"></div>
            <?php echo $form->label($model,'Tipo_Cuenta'); ?>
            <?php
        		$this->widget('ext.select2.ESelect2',array(
					'name'=>'Cuenta[Tipo_Cuenta]',
					'id'=>'Cuenta_Tipo_Cuenta',
					'data'=>$lista_tipos,
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
    <div class="col-sm-4" id="div_tipo_acceso" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_tipo_acceso" style="display: none;"></div>
            <?php echo $form->label($model,'Tipo_Acceso'); ?>
    		    <?php
            		$this->widget('ext.select2.ESelect2',array(
    					'name'=>'Cuenta[Tipo_Acceso]',
    					'id'=>'Cuenta_Tipo_Acceso',
    					'data'=> array(1 => 'GENÉRICO', 2 => 'PERSONAL'),
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
    <div class="col-sm-8" id="div_observaciones" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Observaciones'); ?>
            <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50)); ?>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-4" id="div_ext" style="display: none;">
    <div class="form-group">
      <?php echo $form->label($model,'Ext'); ?>
      <?php echo $form->textField($model,'Ext', array('class' => 'form-control form-control-sm', 'maxlength' => '10', 'autocomplete' => 'off')); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cuenta/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
<?php
/* @var $this LicenciaController */
/* @var $model Licencia */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'licencia-form',
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
      <?php echo $form->error($model,'Clasificacion', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Clasificacion'); ?><br>
      <?php
        $this->widget('ext.select2.ESelect2',array(
            'name'=>'Licencia[Clasificacion]',
            'id'=>'Licencia_Clasificacion',
            'data'=>$lista_clases,
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
      <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Tipo'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Licencia[Tipo]',
              'id'=>'Licencia_Tipo',
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
      <?php echo $form->error($model,'Version', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Version'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Licencia[Version]',
              'id'=>'Licencia_Version',
              'data'=>$lista_versiones,
              'value' => $model->Version,
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
      <?php echo $form->error($model,'Producto', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Producto'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Licencia[Producto]',
              'id'=>'Licencia_Producto',
              'data'=>$lista_productos,
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
      <?php echo $form->error($model,'Id_Licencia', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Id_Licencia'); ?>
      <?php echo $form->textField($model,'Id_Licencia', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off')); ?>
    </div>  
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Num_Licencia', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Num_Licencia'); ?>
      <?php echo $form->textField($model,'Num_Licencia', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off')); ?>
    </div>  
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Token', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Token'); ?>
      <?php echo $form->textField($model,'Token', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off')); ?>
    </div>  
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Empresa_Compra', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Empresa_Compra'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Licencia[Empresa_Compra]',
              'id'=>'Licencia_Empresa_Compra',
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
      <?php echo $form->error($model,'Proveedor', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Proveedor'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Licencia[Proveedor]',
              'id'=>'Licencia_Proveedor',
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
      <?php echo $form->textField($model,'Numero_Factura', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->error($model,'Fecha_Factura', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Fecha_Factura'); ?>
        <?php echo $form->textField($model,'Fecha_Factura', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Valor_Comercial', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Valor_Comercial'); ?>
      <?php echo $form->numberField($model,'Valor_Comercial', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
    </div>  
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Fecha_Inicio', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Fecha_Inicio'); ?>
      <?php echo $form->textField($model,'Fecha_Inicio', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Fecha_Final', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Fecha_Final'); ?>
      <?php echo $form->textField($model,'Fecha_Final', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Fecha_Inicio_Sop', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Fecha_Inicio_Sop'); ?>
      <?php echo $form->textField($model,'Fecha_Inicio_Sop', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Fecha_Final_Sop', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Fecha_Final_Sop'); ?>
      <?php echo $form->textField($model,'Fecha_Final_Sop', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Numero_Inventario', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Numero_Inventario'); ?>
      <?php echo $form->textField($model,'Numero_Inventario', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Cuenta_Registro', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Cuenta_Registro'); ?>
      <?php echo $form->textField($model,'Cuenta_Registro', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off')); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Link', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Link'); ?>
      <?php echo $form->textField($model,'Link', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
    </div>
  </div>
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'Password', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Password'); ?>
          <?php echo $form->textField($model,'Password', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off')); ?>
        </div>  
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'Cant_Usuarios', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Cant_Usuarios'); ?>
        <?php echo $form->numberField($model,'Cant_Usuarios', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min'=> 1, 'max'=>100)); ?>
      </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Ubicacion', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Ubicacion'); ?>
      <?php
        $this->widget('ext.select2.ESelect2',array(
            'name'=>'Licencia[Ubicacion]',
            'id'=>'Licencia_Ubicacion',
            'data'=>$lista_ubicaciones,
            'value' => $model->Ubicacion,
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
     <?php echo $form->error($model,'Notas', array('class' => 'badge badge-warning float-right')); ?>
     <?php echo $form->label($model,'Notas'); ?>
     <?php echo $form->textArea($model,'Notas',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Estado'); ?>
      <?php
        $this->widget('ext.select2.ESelect2',array(
            'name'=>'Licencia[Estado]',
            'id'=>'Licencia_Estado',
            'data'=>$lista_estados,
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
      <?php echo $form->label($model,'sop', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
      <input type="hidden" id="valid_sop" value="1">
      <?php echo $form->fileField($model, 'sop'); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'sop2', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_sop2" style="display: none;"></div><br>
      <input type="hidden" id="valid_sop2" value="1">
      <?php echo $form->fileField($model, 'sop2'); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=licencia/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>


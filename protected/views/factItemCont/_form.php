<?php
/* @var $this FactItemContController */
/* @var $model FactItemCont */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fact-item-cont-form',
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
          <?php echo $form->error($model,'Id_Contrato', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Id_Contrato'); ?>
    			<?php echo $form->hiddenField($model,'Id_Contrato', array('class' => 'form-control form-control-sm', 'value' => $c)); ?>
    			<?php  
    				$mc = new Cont;
    				$desc_cont = $mc->Desccontrato($c);
    				echo '<p>'.$desc_cont.'</p>';
    			?> 
          <?php echo $form->hiddenField($model,'cad_item'); ?>
          <?php echo $form->hiddenField($model,'cad_cant'); ?>
          <?php echo $form->hiddenField($model,'cad_vlr_u'); ?>
          <?php echo $form->hiddenField($model,'cad_iva'); ?>         
        </div>
    </div>
    <div class="col-sm-4">
      	<div class="form-group">
          <div id="error_num_fact" class="badge badge-warning float-right" style="display: none;"></div>
          <?php echo $form->label($model,'Numero_Factura'); ?>
          <?php echo $form->textField($model,'Numero_Factura', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
      	</div>
  	</div>
	  <div class="col-sm-4">
  	  <div class="form-group">
        <div id="error_fec_fact" class="badge badge-warning float-right" style="display: none;"></div>
        <?php echo $form->label($model,'Fecha_Factura'); ?>
        <?php echo $form->textField($model,'Fecha_Factura', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
  	  </div>
	  </div>
  </div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <div id="error_tasa_cambio" class="badge badge-warning float-right" style="display: none;"></div>
      <?php echo $form->label($model,'Tasa_Cambio'); ?>
      <?php echo $form->numberField($model,'Tasa_Cambio', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.01')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <?php echo $form->label($model,'vlr_total'); ?>
    <?php echo '<p id="vlr_total">-</p>'; ?>
  </div>
</div>
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
       <?php echo $form->error($model,'item', array('class' => 'badge badge-warning float-right')); ?>
       <?php echo $form->label($model,'item'); ?>
       <?php
        $this->widget('ext.select2.ESelect2',array(
            'name'=>'FactItemCont[item]',
            'id'=>'FactItemCont_item',
            'data'=>$lista_items,
            'htmlOptions'=>array(
              //'multiple'=>'multiple',
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

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/view&id='.$c; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="add" onclick="add_item();"><i class="fas fa-plus"></i> Agregar</button>
        <button type="button" class="btn btn-primary btn-sm" id="btn_save" style="display: none;" onclick="return valida_opciones(event);"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

<div id="contenido"></div>

<?php $this->endWidget(); ?>
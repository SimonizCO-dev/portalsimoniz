<?php
/* @var $this ItemContController */
/* @var $model ItemCont */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'item-cont-form',
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
        </div>
    </div>
  	<div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Id', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Id'); ?>
          <?php echo $form->textField($model,'Id', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Item', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Item'); ?>
          <?php echo $form->textField($model,'Item', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row"> 
  	<div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Cant', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Cant'); ?>
		    <?php echo $form->numberField($model,'Cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Vlr_Unit', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Vlr_Unit'); ?>
		    <?php echo $form->numberField($model,'Vlr_Unit', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'step' => '0.01')); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'Moneda', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Moneda'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'ItemCont[Moneda]',
                'id'=>'ItemCont_Moneda',
                'data'=>$lista_monedas,
                'value' => $model->Moneda,
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
        <?php echo $form->error($model,'Iva', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Iva'); ?>
        <?php echo $form->numberField($model,'Iva', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'value' => 0, 'min' => 0, 'max' => 100)); ?>
        </div>
    </div>    
    <div class="col-sm-8">
      <div class="form-group">
            <?php echo $form->error($model,'Descripcion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Descripcion'); ?>
            <?php echo $form->textArea($model,'Descripcion',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
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
                    'name'=>'ItemCont[Estado]',
                    'id'=>'ItemCont_Estado',
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

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/view&id='.$c; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
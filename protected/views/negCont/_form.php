<?php
/* @var $this NegContController */
/* @var $model NegCont */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'neg-cont-form',
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
    <div class="col-sm-8">
      <div class="form-group">
          <?php echo $form->error($model,'Item', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Item'); ?>
          <?php echo $form->textArea($model,'Item',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
      </div>
    </div>
</div>
<div class="row">
	<div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'Costo', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Costo'); ?>
		    <?php echo $form->numberField($model,'Costo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'step' => '0.01')); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'Moneda', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Moneda'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'NegCont[Moneda]',
                'id'=>'NegCont_Moneda',
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
    <div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'Porc_Desc', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'Porc_Desc'); ?>
          <?php echo $form->numberField($model,'Porc_Desc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '0.01', 'min' => '0.00', 'value' => number_format($model->Porc_Desc, 2))); ?>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-sm-4">
        <div class="form-group">
          <?php echo $form->error($model,'costo_final', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'costo_final'); ?>
          <?php echo '<p id="costo_final">-</p>'; ?>
        </div>
    </div>     
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Estado'); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'NegCont[Estado]',
                    'id'=>'NegCont_Estado',
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
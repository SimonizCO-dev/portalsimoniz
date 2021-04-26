<?php
/* @var $this CPtjAceleradorController */
/* @var $model CPtjAcelerador */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cptj-acelerador-form',
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
            <?php echo $form->error($model,'TIPO', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'TIPO'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'CPtjAcelerador[TIPO]',
                    'id'=>'CPtjAcelerador_TIPO',
                    'data'=> $lista_tipos,
                    'value' => $model->TIPO,
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
            <?php echo $form->error($model,'ID_ACELERADOR', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'ID_ACELERADOR'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'CPtjAcelerador[ID_ACELERADOR]',
                    'id'=>'CPtjAcelerador_ID_ACELERADOR',
                    'data'=> $lista_aceler,
                    'value' => $model->ID_ACELERADOR,
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
     <div class="col-sm-8" id="div_item" style="display: none;">
        <div class="form-group">
            <?php echo $form->error($model,'ITEM', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'ITEM'); ?>
            <?php echo $form->textField($model,'ITEM'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#CPtjAcelerador_ITEM',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('CPtjAcelerador/SearchItem'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("CPtjAcelerador_ITEM"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'CPtjAcelerador_ITEM\')\">Limpiar campo</button>"; }',
                        'initSelection'=>'js:function(element,callback) {
                            var id=$(element).val(); // read #selector value
                            if ( id !== "" ) {
                                $.ajax("'.Yii::app()->createUrl('CPtjAcelerador/SearchItemById').'", {
                                    data: { id: id },
                                    dataType: "json"
                                }).done(function(data,textStatus, jqXHR) { callback(data[0]); });
                           }
                        }',
                    ),
                ));
            ?>


        </div>
    </div>
    <div class="col-sm-4" id="div_plan" style="display: none;">
        <div class="form-group">
            <?php echo $form->error($model,'ID_PLAN', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'ID_PLAN'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'CPtjAcelerador[ID_PLAN]',
                    'id'=>'CPtjAcelerador_ID_PLAN',
                    'data'=>$lista_planes,
                    'value' => $model->ID_PLAN,
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
	<div class="col-sm-4" id="div_criterio" style="display: none;">
    	<div class="form-group">
    		<?php echo $form->error($model,'CRITERIO', array('class' => 'badge badge-warning float-right')); ?>
          	<?php echo $form->label($model,'CRITERIO'); ?>
	        <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'CPtjAcelerador[CRITERIO]',
                    'id'=>'CPtjAcelerador_CRITERIO',
                    'value' => $model->CRITERIO,
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
            <?php echo $form->error($model,'FECHA_INICIAL', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'FECHA_INICIAL'); ?>
		    <?php echo $form->textField($model,'FECHA_INICIAL', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->error($model,'FECHA_FINAL', array('class' => 'badge badge-warning float-right')); ?>
      	     <?php echo $form->label($model,'FECHA_FINAL'); ?>
		    <?php echo $form->textField($model,'FECHA_FINAL', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'PORCENTAJE', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'PORCENTAJE'); ?>
            <?php echo $form->numberField($model,'PORCENTAJE', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '0.01', 'min' => '0.01', 'placeholder' => '0,01')); ?>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cptjacelerador/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
/* @var $this PedComController */
/* @var $model PedCom */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ped-com-form',
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
            <?php echo $form->error($model,'Id_Usuario', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Usuario'); ?>
         	<?php echo $form->hiddenField($model,'Id_Usuario', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true, 'value'=> Yii::app()->user->getState('id_user'))); ?>
         	<p><?php echo Yii::app()->user->getState('name_user'); ?></p>
        </div>
    </div>
    <div class="col-sm-6">
    	<div class="form-group">
	      <?php echo $form->error($model,'Cliente', array('class' => 'badge badge-warning float-right')); ?>
	      <?php echo $form->label($model,'Cliente'); ?>
	      <?php echo $form->textField($model,'Cliente'); ?>
	      <?php
	      $this->widget('ext.select2.ESelect2', array(
	          'selector' => '#PedCom_Cliente',

	          'options'  => array(
	            'allowClear' => true,
	            'minimumInputLength' => 3,
	                'width' => '100%',
	                'language' => 'es',
	                'ajax' => array(
	                      'url' => Yii::app()->createUrl('PedCom/SearchCliente'),
	                  'dataType'=>'json',
	                    'data'=>'js:function(term){return{q: term};}',
	                    'results'=>'js:function(data){ return {results:data};}'
	                             
	              ),
	              'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ParPedEsp_Nit"); return "No se encontraron resultados"; }',
	              'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'PedCom_Cliente\')\">Limpiar campo</button>"; }',
	          ),
	        ));
	      ?>
    	</div>
  	</div>
</div>
<div class="row">
  	<div class="col-sm-4" id="div_suc" style="display: none;">
	    <div class="form-group">
			<?php echo $form->error($model,'Sucursal', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Sucursal', array('class' => 'control-label')); ?>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			    'name'=>'PedCom[Sucursal]',
			    'id'=>'PedCom_Sucursal',
			    'value' => $model->Sucursal,
			    'options'=>array(
			        'placeholder'=>'Seleccione...',
			        'width'=> '100%',
			        'allowClear'=>true,
			    ),
			  ));
			?>
	    </div>
	</div>
	<div class="col-sm-4" id="div_pe" style="display: none;">
	    <div class="form-group">
	    	<?php echo $form->error($model,'Punto_Envio', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Punto_Envio', array('class' => 'control-label')); ?>
			
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			    'name'=>'PedCom[Punto_Envio]',
			    'id'=>'PedCom_Punto_Envio',
			    'value' => $model->Punto_Envio,
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
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=pedcom/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Continuar</button>
    </div>
</div>

<?php $this->endWidget(); ?>


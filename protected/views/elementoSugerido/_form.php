<?php
/* @var $this PromocionController */
/* @var $model Promocion */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'elemento-sugerido-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
));

?>

<div class="row">
	<div class="col-sm-8">
    	<div class="form-group">
    		<?php echo $form->label($model,'Id_Sugerido', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Sugerido', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'Id_Sugerido', array('class' => 'form-control form-control-sm', 'value' => $s)); ?>
		    <?php echo '<p>'.UtilidadesSugerido::sugerido($s).'</p>' ?>
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
    		<?php echo $form->label($model,'Cantidad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Cantidad', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->numberField($model,'Cantidad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off',  'step' => '1', 'min' => '1', 'max' => '10')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Id_A_Elemento', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_A_Elemento', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->textField($model,'Id_A_Elemento'); ?>
			<?php
			    $this->widget('ext.select2.ESelect2', array(
			        'selector' => '#ElementoSugerido_Id_A_Elemento',
			        'options'  => array(
			        	'minimumInputLength' => 5,
		               	'width' => '100%',
		               	'language' => 'es',
		                'ajax' => array(
	                        'url' => Yii::app()->createUrl('areaElemento/SearchElem'),
			               	'dataType'=>'json',
	                    	'data'=>'js:function(term){return{q: term};}',
	                    	'results'=>'js:function(data){ return {results:data};}'                
			            ),
			            'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ElementoSugerido_Id_A_Elemento"); return "No se encontraron resultados"; }',
			            'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'ElementoSugerido_Id_A_Elemento\')\">Limpiar campo</button>"; }',
			            'initSelection'=>'js:function(element,callback) {
		                   	var id=$(element).val(); // read #selector value
		                   	if ( id !== "" ) {
		                     	$.ajax("'.Yii::app()->createUrl('areaElemento/SearchElemById').'", {
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
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=sugerido/update&id='.$s; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div> 

<?php $this->endWidget(); ?>
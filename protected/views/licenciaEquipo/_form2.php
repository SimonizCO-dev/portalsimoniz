<?php
/* @var $this LicenciaEquipoController */
/* @var $model LicenciaEquipo */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'licencia-equipo-form',
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
 	<div class="col-sm-12">
    	<?php echo $form->label($model,'Id_Licencia'); ?>
      <?php $licencia = Licencia::model()->findByPk($l); ?>  
      <?php echo '<p>'.$licencia->DescLicencia($l).'</p>'; ?>     
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
        	<?php echo $form->error($model,'Id_Equipo', array('class' => 'badge badge-warning float-right')); ?>
    		<?php echo $form->label($model,'Id_Equipo'); ?>
            <?php echo $form->textField($model,'Id_Equipo'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#LicenciaEquipo_Id_Equipo',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('licenciaequipo/SearchEquipoAsocLicencia'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term, l: '.$l.'};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("LicenciaEquipo_Id_Equipo"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'LicenciaEquipo_Id_Equipo\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=licencia/view&id='.$l; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>


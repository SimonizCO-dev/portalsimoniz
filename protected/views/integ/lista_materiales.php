<?php
/* @var $this InventarioController */
/* @var $model Inventario */

?>

<h4>Lista de materiales</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'integ-form',
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
  <div class="col-sm-8">
    <div class="form-group">
          <?php echo $form->label($model,'item'); ?>
          <?php echo $form->error($model,'item', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->textField($model,'item'); ?>
          <?php
              $this->widget('ext.select2.ESelect2', array(
                  'selector' => '#Integ_item',
                  'options'  => array(
                      'allowClear' => true,
                      'minimumInputLength' => 3,
                      'width' => '100%',
                      'language' => 'es',
                      'ajax' => array(
                          'url' => Yii::app()->createUrl('reporte/SearchItem'),
                          'dataType'=>'json',
                          'data'=>'js:function(term){return{q: term};}',
                          'results'=>'js:function(data){ return {results:data};}'                   
                      ),
                      'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Integ_item"); return "No se encontraron resultados"; }',
                      'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Integ_item\')\">Limpiar campo</button>"; }',
                  ),
              ));
          ?>
      </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Buscar</button>
    </div>
</div>

<?php $this->endWidget(); ?>
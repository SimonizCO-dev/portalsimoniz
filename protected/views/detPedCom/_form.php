<?php
/* @var $this DetPedComController */
/* @var $model DetPedCom */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'det-ped-com-form',
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
	<div class="col-sm-6">
    	<div class="form-group">
	      <?php echo $form->error($model,'Item', array('class' => 'badge badge-warning float-right')); ?>
	      <?php echo $form->label($model,'Item'); ?>
	      <?php echo $form->hiddenField($model,'Id_Ped_Com', array('class' => 'form-control form-control-sm', 'readonly' => true, 'value'=> $cab->Id_Ped_Com)); ?>
	      <?php echo $form->textField($model,'Item'); ?>
	      <?php
	      $this->widget('ext.select2.ESelect2', array(
	          'selector' => '#DetPedCom_Item',

	          'options'  => array(
	            'allowClear' => true,
	            'minimumInputLength' => 3,
	                'width' => '100%',
	                'language' => 'es',
	                'ajax' => array(
	                      'url' => Yii::app()->createUrl('DetPedCom/SearchItem'),
	                  'dataType'=>'json',
	                    'data'=>'js:function(term){return{q: term, id_cab: $("#DetPedCom_Id_Ped_Com").val()};}',
	                    'results'=>'js:function(data){ return {results:data};}'
	                             
	              ),
	              'formatNoMatches'=> 'js:function(){ clear_select2_ajax("DetPedCom_Item"); return "No se encontraron resultados"; }',
	              'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'DetPedCom_Item\')\">Limpiar campo</button>"; }',
	          ),

	        ));
	      ?>
    	</div>
  	</div>
  	<div class="col-sm-2" id="info_und_emp" style="display: none;">
        <div class="form-group">
        	<?php echo $form->error($model,'Und_Emp', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Und_Emp'); ?>
            <?php echo $form->numberField($model,'Und_Emp', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'min' => '1', 'step' => '1', 'readonly' => true)); ?>
        </div>
    </div>	
	<div class="col-sm-4" id="info_unds_sol" style="display: none;">
	    <div class="form-group">
	        <?php echo $form->error($model,'Un_Sol', array('class' => 'badge badge-warning float-right')); ?>
	        <?php echo $form->label($model,'Un_Sol'); ?>
	        <?php echo $form->numberField($model,'Un_Sol', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'min' => '1', 'step' => '1')); ?>
	    </div>
    </div>	
</div>


<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=pedcom/update&id='.$cab->Id_Ped_Com; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-plus"></i> Agregar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<h5>Detalle</h5>

<?php 

//detalle

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'det-ped-com-grid',
	'dataProvider'=>$detalle->search(),
	'pager'=>array(
	  'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	),
	'enableSorting' => false,
	'columns'=>array(
		array(
	        'name' => 'Item',
	       	'value' => '$data->DescItem($data->Item)',
	    ),
		array(
            'name'=>'Und_Emp',
            'value' => '($data->Und_Emp == "") ? "-" : $data->Und_Emp',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
		array(
            'name'=>'Un_Sol',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{delete}',
            'afterDelete'=>'function(link,success,data){
			         window.location.reload(); 
			}',
            'buttons'=>array(
                
                'delete'=>array(
                    'label'=>'<i class="fas fa-times-circle actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("DetPedCom/delete", array("id"=>$data->Id_Det_Ped_Com))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->idpedcom->NumDet($data->Id_Ped_Com) > 1)',
                ),
            )
		),
	),
));

?>
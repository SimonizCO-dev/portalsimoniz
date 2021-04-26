<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<p>Utilice los filtros para optimizar la busqueda:</p>

	<div class="row">
	    <div class="col-sm-5">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Item_Padre'); ?>
			    <?php echo $form->textField($model,'Id_Item_Padre'); ?>
			    <?php
			    $this->widget('ext.select2.ESelect2', array(
			        'selector' => '#Promocion_Id_Item_Padre',

			        'options'  => array(
			        	'allowClear' => true,
			        	'minimumInputLength' => 3,
		               	'width' => '100%',
		               	'language' => 'es',
		                'ajax' => array(
	                        'url' => Yii::app()->createUrl('promocion/SearchItem'),
			               	'dataType'=>'json',
	                    	'data'=>'js:function(term){return{q: term};}',
	                    	'results'=>'js:function(data){ return {results:data};}'
					                       
			            ),
		            	'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Promocion_Id_Item_Padre"); return "No se encontraron resultados"; }',
		            	'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Promocion_Id_Item_Padre\')\">Limpiar campo</button>"; }',
		        	),

		      	));
			    ?>
	        </div>
	    </div>
	   	<div class="col-sm-5">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Item_Hijo'); ?>
			    <?php echo $form->textField($model,'Id_Item_Hijo', array('autocomplete' => 'off')); ?>
			    <?php
			    $this->widget('ext.select2.ESelect2', array(
			        'selector' => '#Promocion_Id_Item_Hijo',
			        'options'  => array(
			        	'minimumInputLength' => 3,
		               	'width' => '100%',
		               	'language' => 'es',
		                'ajax' => array(
	                        'url' => Yii::app()->createUrl('promocion/SearchItem'),
			               	'dataType'=>'json',
	                    	'data'=>'js:function(term){return{q: term};}',
	                    	'results'=>'js:function(data){ return {results:data};}'
					                       
			            ),
			            'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Promocion_Id_Item_Hijo"); return "No se encontraron resultados"; }',
			            'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Promocion_Id_Item_Hijo\')\">Limpiar campo</button>"; }',
		        	),

		      	));
			    ?>
	        </div>
	    </div>
	    <div class="col-sm-2">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Cantidad'); ?>
			    <?php echo $form->numberField($model,'Cantidad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Promocion[Id_Usuario_Creacion]',
						'id'=>'Promocion_Id_Usuario_Creacion',
						'data'=>$lista_usuarios,
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
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Creacion'); ?>
			    <?php echo $form->textField($model,'Fecha_Creacion', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?>
			    <?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Promocion[Id_Usuario_Actualizacion]',
						'id'=>'Promocion_Id_Usuario_Actualizacion',
						'data'=>$lista_usuarios,
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
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Actualizacion'); ?>
			    <?php echo $form->textField($model,'Fecha_Actualizacion', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">   
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'promocion-grid', //Gridview id
				        'mPageSize' => @$_GET['pageSize'],
				        'mDefPageSize' => Yii::app()->params['defaultPageSize'],
				        'mPageSizeOptions'=>Yii::app()->params['pageSizeOptions'],// Optional, you can use with the widget default
					)); 
				?>	
	        </div>
	    </div>
	</div>
	<div class="row mb-2">
	  	<div class="col-sm-6">  
     		<button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
			<button type="submit" class="btn btn-primary btn-sm" id="yt0"><i class="fa fa-search"></i> Buscar</button>
	  	</div>
	</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
	
function resetfields(){
	$('#Promocion_Id_Item_Padre').val('').trigger('change');
	$('#s2id_Promocion_Id_Item_Padre span').html("");
	$('#Promocion_Id_Item_Hijo').val('').trigger('change');
	$('#s2id_Promocion_Id_Item_Hijo span').html("");
	$('#Promocion_Cantidad').val('');
	$('#Promocion_Id_Usuario_Creacion').val('').trigger('change');
	$('#Promocion_Fecha_Creacion').val('');
	$('#Promocion_Id_Usuario_Actualizacion').val('').trigger('change');
	$('#Promocion_Fecha_Actualizacion').val('');
	$('#Promocion_Estado').val('').trigger('change');
	$('#Promocion_orderby').val('').trigger('change');
	$('#promocion-grid').yiiGridView('update', {
		data: $('.search-form form').serialize()
	});
	return false;
}

</script>

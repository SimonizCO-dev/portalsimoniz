<?php
/* @var $this IExistenciaController */
/* @var $model IExistencia */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<p>Utilice los filtros para optimizar la busqueda:</p>

	<div class="row">
		<div class="col-sm-9">
	        <div class="form-group">
				<?php echo $form->label($model,'linea'); ?>
				<?php
				$this->widget('ext.select2.ESelect2',array(
				  'name'=>'IExistencia[linea]',
				  'id'=>'IExistencia_linea',
				  'data'=>$lista_lineas,
				  'htmlOptions'=>array(
				    'multiple'=>'multiple',
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
	<div class="row">
		<div class="col-sm-9">
	        <div class="form-group">
	            <?php echo $form->label($model,'Id_Item'); ?>
	            <?php echo $form->textField($model,'Id_Item'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2', array(
	                    'selector' => '#IExistencia_Id_Item',
	                    'options'  => array(
	                        'allowClear' => true,
	                        'minimumInputLength' => 3,
	                        'width' => '100%',
	                        'language' => 'es',
	                        'ajax' => array(
	                            'url' => Yii::app()->createUrl('iItem/SearchItem'),
	                            'dataType'=>'json',
	                            'data'=>'js:function(term){return{q: term};}',
	                            'results'=>'js:function(data){ return {results:data};}'                   
	                        ),
	                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IExistencia_Id_Item"); return "No se encontraron resultados"; }',
	                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IExistencia_Id_Item\')\">Limpiar campo</button>"; }',
	                    ),
	                ));
	            ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Bodega'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'IExistencia[Id_Bodega]',
						'id'=>'IExistencia_Id_Bodega',
						'data'=>$lista_bodegas,
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
	          	<?php echo $form->label($model,'Cantidad'); ?>
			    <?php echo $form->numberField($model,'Cantidad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Ult_Ent'); ?>
			    <?php echo $form->textField($model,'Fecha_Ult_Ent', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Ult_Sal'); ?>
			    <?php echo $form->textField($model,'Fecha_Ult_Sal', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'orderby'); ?>
			    <?php
                	$array_orden = array(1 => 'Línea ASC', 2 => 'Línea DESC', 3 => 'Desc. de item ASC', 4 => 'Desc. de item DESC', 5 => 'Bodega ASC', 6 => 'Bodega DESC' , 7 => 'Cantidad ASC', 8 => 'Cantidad DESC', 9 => 'Fecha ult. entrada ASC', 10 => 'Fecha ult. entrada DESC', 11 => 'Fecha ult. salida ASC', 12 => 'Fecha ult. salida DESC'
                	);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'IExistencia[orderby]',
						'id'=>'IExistencia_orderby',
						'data'=>$array_orden,
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
	          	<?php echo $form->label($model,'est_cant'); ?>
			    <?php
                	$array_cond = array(1 => 'Menor a stock mínimo', 2 => 'Mayor o igual a stock mínimo');
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'IExistencia[est_cant]',
						'id'=>'IExistencia_est_cant',
						'data'=>$array_cond,
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
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'iexistencia-grid', //Gridview id
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
		$('#IExistencia_linea').val('').trigger('change');
    	$('#s2id_IExistencia_linea span').html(""); 
		$('#IExistencia_Id_Item').val('').trigger('change');
    	$('#s2id_IExistencia_Id_Item span').html("");
		$('#IExistencia_Id_Bodega').val('').trigger('change');
		$('#IExistencia_Cantidad').val('');
		$('#IExistencia_Fecha_Ult_Ent').val('');
		$('#IExistencia_Fecha_Ult_Sal').val('');
		$('#IExistencia_est_cant').val('').trigger('change');
		$('#IExistencia_orderby').val('').trigger('change');		
		$('#iexistencia-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
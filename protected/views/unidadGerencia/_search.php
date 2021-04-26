<?php
/* @var $this UnidadGerenciaController */
/* @var $model UnidadGerencia */
/* @var $form CActiveForm */
?>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl($this->route),
		'method'=>'get',
	)); ?>
		
		<p>Utilice los filtros para optimizar la busqueda:</p>

		<div class="row">
		    <div class="col-sm-3">
		    	<div class="form-group">
		          	<?php echo $form->label($model,'Id_Unidad_Gerencia'); ?>
				    <?php echo $form->numberField($model,'Id_Unidad_Gerencia', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
		        </div>
		    </div>
		    <div class="col-sm-3">
		    	<div class="form-group">
		          	<?php echo $form->label($model,'Unidad_Gerencia'); ?>
				    <?php echo $form->textField($model,'Unidad_Gerencia', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="col-sm-3">
		    	<div class="form-group">
		          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
	            	<?php
	            		$this->widget('ext.select2.ESelect2',array(
							'name'=>'UnidadGerencia[Id_Usuario_Creacion]',
							'id'=>'UnidadGerencia_Id_Usuario_Creacion',
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
							'name'=>'UnidadGerencia[Id_Usuario_Actualizacion]',
							'id'=>'UnidadGerencia_Id_Usuario_Actualizacion',
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
		          	<?php echo $form->label($model,'Estado'); ?>
				    <?php $estados = Yii::app()->params->estados; ?>
	            	<?php
	            		$this->widget('ext.select2.ESelect2',array(
							'name'=>'UnidadGerencia[Estado]',
							'id'=>'UnidadGerencia_Estado',
							'data'=>$estados,
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
		          	<?php echo $form->label($model,'orderby'); ?>
				    <?php 
	                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Unidad de gerencia ASC', 4 => 'Unidad de gerencia DESC', 5 => 'Usuario que creo ASC', 6 => 'Usuario que creo DESC', 7 => 'Fecha de creación ASC', 8 => 'Fecha de creación DESC', 9 => 'Ultimo usuario que actualizó ASC', 10 => 'Ultimo usuario que actualizó DESC', 11 => 'Ultima fecha de actualización ASC', 12 => 'Ultima fecha de actualización DESC', 13 => 'Estado ASC', 14 => 'Estado DESC',
						);
	            	?>
	            	<?php
	            		$this->widget('ext.select2.ESelect2',array(
							'name'=>'UnidadGerencia[orderby]',
							'id'=>'UnidadGerencia_orderby',
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
		          	<?php 
						$this->widget('application.extensions.PageSize.PageSize', array(
					        'mGridId' => 'unidad-gerencia-grid', //Gridview id
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
			$('#UnidadGerencia_Id_Unidad_Gerencia').val('');
			$('#UnidadGerencia_Unidad_Gerencia').val('');
			$('#UnidadGerencia_Id_Usuario_Creacion').val('').trigger('change');
			$('#UnidadGerencia_Fecha_Creacion').val('');
			$('#UnidadGerencia_Id_Usuario_Actualizacion').val('').trigger('change');
			$('#UnidadGerencia_Fecha_Actualizacion').val('');
			$('#UnidadGerencia_Estado').val('').trigger('change');
			$('#UnidadGerencia_orderby').val('').trigger('change');
			$('#unidad-gerencia-grid').yiiGridView('update', {
				data: $('.search-form form').serialize()
			});
			return false;
		}
		
	</script>
<?php
/* @var $this DocumentoController */
/* @var $model Documento */
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
	          	<?php echo $form->label($model,'Id_Documento'); ?>
			    <?php echo $form->numberField($model,'Id_Documento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Clasificacion'); ?>
			    <?php $clasif = Yii::app()->params->clasif_docs; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdDocumento[Clasificacion]',
						'id'=>'GdDocumento_Clasificacion',
						'data'=>$clasif,
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
	          	<?php echo $form->label($model,'Tipo'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdDocumento[Tipo]',
						'id'=>'GdDocumento_Tipo',
						'data'=>$lista_tipos,
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
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Num_Documento'); ?>
			    <?php echo $form->textField($model,'Num_Documento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Titulo'); ?>
			    <?php echo $form->textField($model,'Titulo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Nivel_Revision'); ?>
			    <?php echo $form->numberField($model,'Nivel_Revision', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>    
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdDocumento[Id_Usuario_Creacion]',
						'id'=>'GdDocumento_Id_Usuario_Creacion',
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
						'name'=>'GdDocumento[Id_Usuario_Actualizacion]',
						'id'=>'GdDocumento_Id_Usuario_Actualizacion',
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
						'name'=>'GdDocumento[Estado]',
						'id'=>'GdDocumento_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Clasificación ASC', 4 => 'Clasificación DESC', 5 => 'Tipo ASC', 6 => 'Tipo DESC', 7 => 'N° documento ASC', 8 => 'N° documento DESC', 9 => 'Nombre ASC', 10 => 'Nombre DESC' , 11 => 'Nivel de Revisión ASC', 12 => 'Nivel de Revisión DESC', 13 => 'Usuario que creo ASC', 14 => 'Usuario que creo DESC', 15 => 'Fecha de creación ASC', 16 => 'Fecha de creación DESC', 17 => 'Ultimo usuario que actualizó
 ASC', 18 => 'Ultimo usuario que actualizó DESC', 19 => 'Ultima fecha de actualización ASC', 20 => 'Ultima fecha de actualización DESC', 21 => 'Estado ASC', 22 => 'Estado DESC',
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdDocumento[orderby]',
						'id'=>'GdDocumento_orderby',
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
				        'mGridId' => 'gd-documento-grid', //Gridview id
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
		$('#GdDocumento_Id_Documento').val('');
		$('#GdDocumento_Id_Area').val('').trigger('change');
		$('#GdDocumento_Clasificacion').val('').trigger('change');
		$('#GdDocumento_Tipo').val('').trigger('change');
		$('#GdDocumento_Num_Documento').val('');
		$('#GdDocumento_Titulo').val('');
		$('#GdDocumento_Nivel_Revision').val('');
		$('#GdDocumento_Id_Usuario_Creacion').val('').trigger('change');
		$('#GdDocumento_Fecha_Creacion').val('');
		$('#GdDocumento_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#GdDocumento_Fecha_Actualizacion').val('');
		$('#GdDocumento_Estado').val('').trigger('change');
		$('#GdDocumento_orderby').val('').trigger('change');		
		$('#gd-documento-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
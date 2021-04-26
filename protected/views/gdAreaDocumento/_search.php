<?php
/* @var $this AreaDocumentoController */
/* @var $model AreaDocumento */
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
	          	<?php echo $form->label($model,'Id_A_Documento'); ?>
			    <?php echo $form->numberField($model,'Id_A_Documento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Documento'); ?>
			    <?php echo $form->numberField($model,'Id_Documento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'clasif_doc'); ?>
			    <?php $clasif = Yii::app()->params->clasif_docs; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAreaDocumento[clasif_doc]',
						'id'=>'GdAreaDocumento_clasif_doc',
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
	          	<?php echo $form->label($model,'tipo_doc'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAreaDocumento[tipo_doc]',
						'id'=>'GdAreaDocumento_tipo_doc',
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
	          	<?php echo $form->label($model,'num_doc'); ?>
			    <?php echo $form->textField($model,'num_doc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>    
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'tit_doc'); ?>
			    <?php echo $form->textField($model,'tit_doc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'n_v_doc'); ?>
			    <?php echo $form->numberField($model,'n_v_doc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Area'); ?>
			    <?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAreaDocumento[Id_Area]',
						'id'=>'GdAreaDocumento_Id_Area',
						'data'=>$lista_areas,
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
	          	<?php echo $form->label($model,'usuario_creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaDocumento[usuario_creacion]',
						'id'=>'AreaDocumento_usuario_creacion',
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
	          	<?php echo $form->label($model,'usuario_actualizacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaDocumento[usuario_actualizacion]',
						'id'=>'AreaDocumento_usuario_actualizacion',
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
						'name'=>'AreaDocumento[Estado]',
						'id'=>'AreaDocumento_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'ID docto ASC', 4 => 'ID docto DESC', 5 => 'N° documento ASC', 6 => 'N° documento DESC', 7 => 'Nombre ASC', 8 => 'Nombre DESC', 9 => 'Área ASC', 10 => 'Área DESC', 11 => 'Usuario que creo ASC', 12 => 'Usuario que creo DESC', 13 => 'Fecha de creación ASC', 14 => 'Fecha de creación DESC', 15 => 'Ultimo usuario que actualizó ASC', 16 => 'Ultimo usuario que actualizó DESC', 17 => 'Ultima fecha de actualización ASC', 18 => 'Ultima fecha de actualización DESC', 19 => 'Estado ASC', 20 => 'Estado DESC');
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaDocumento[orderby]',
						'id'=>'AreaDocumento_orderby',
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
				        'mGridId' => 'area-documento-grid', //Gridview id
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
		$('#AreaDocumento_Id_A_Documento').val('');
		$('#AreaDocumento_Id_Documento').val('');
		$('#AreaDocumento_clasif_doc').val('').trigger('change');
		$('#AreaDocumento_tipo_doc').val('').trigger('change');
		$('#AreaDocumento_num_doc').val('');
		$('#AreaDocumento_tit_doc').val('');
		$('#AreaDocumento_n_v_doc').val('');
		$('#AreaDocumento_Id_Area').val('').trigger('change');
		$('#AreaDocumento_usuario_creacion').val('').trigger('change');
		$('#AreaDocumento_Fecha_Creacion').val('');
		$('#AreaDocumento_usuario_actualizacion').val('').trigger('change');
		$('#AreaDocumento_Fecha_Actualizacion').val('');
		$('#AreaDocumento_Estado').val('').trigger('change');
		$('#AreaDocumento_orderby').val('').trigger('change');		
		$('#yt0').click();
	}
	
</script>
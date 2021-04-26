<?php
/* @var $this GdDocumentoController */
/* @var $model GdDocumento */
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
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'orderby'); ?>
			    <?php 
                	$array_orden = array(1 => 'Tipo ASC', 2 => 'Tipo DESC', 3 => 'N° documento ASC', 4 => 'N° documento DESC', 5 => 'Nombre ASC', 6 => 'Nombre DESC',
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
		$('#GdDocumento_Tipo').val('').trigger('change');
		$('#GdDocumento_Num_Documento').val('');
		$('#GdDocumento_Titulo').val('');
		$('#GdDocumento_orderby').val('').trigger('change');		
		$('#gd-documento-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
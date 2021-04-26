<?php
/* @var $this EquipoController */
/* @var $model Equipo */
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
	          	<?php echo $form->label($model,'Id_Equipo'); ?>
			    <?php echo $form->numberField($model,'Id_Equipo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Tipo_Equipo'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Equipo[Tipo_Equipo]',
	                    'id'=>'Equipo_Tipo_Equipo',
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
	          	<?php echo $form->label($model,'Serial'); ?>
			    <?php echo $form->textField($model,'Serial', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Modelo'); ?>
			    <?php echo $form->textField($model,'Modelo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Empresa_Compra'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Equipo[Empresa_Compra]',
						'id'=>'Equipo_Empresa_Compra',
						'data'=>$lista_empresas,
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
	          	<?php echo $form->label($model,'Numero_Factura'); ?>
			    <?php echo $form->textField($model,'Numero_Factura', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Compra'); ?>
			    <?php echo $form->textField($model,'Fecha_Compra', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Proveedor'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Equipo[Proveedor]',
						'id'=>'Equipo_Proveedor',
						'data'=>$lista_proveedores,
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
	          	<?php echo $form->label($model,'Estado'); ?>
			    <?php $estados = Yii::app()->params->estados; ?>
			    <?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Equipo[Estado]',
						'id'=>'Equipo_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Tipo ASC', 4 => 'Tipo DESC', 5 => 'Serial ASC', 6 => 'Serial DESC', 7 => 'Modelo ASC', 8 => 'Modelo DESC' , 9 => 'Empresa que compro ASC', 10 => 'Empresa que compro DESC', 11 => 'Fecha de compra ASC', 12 => 'Fecha de compra DESC', 13 => 'Proveedor ASC', 14 => 'Proveedor DESC', 15 => 'Estado ASC', 16 => 'Estado DESC',
                	);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Equipo[orderby]',
						'id'=>'Equipo_orderby',
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
					        'mGridId' => 'equipo-grid', //Gridview id
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
		$('#Equipo_Id_Equipo').val('');
		$('#Equipo_Tipo_Equipo').val('').trigger('change');
		$('#Equipo_Serial').val('');
		$('#Equipo_Modelo').val('');
		$('#Equipo_Empresa_Compra').val('').trigger('change');
		$('#Equipo_Numero_Factura').val('');
		$('#Equipo_Fecha_Compra').val('');
		$('#Equipo_Proveedor').val('').trigger('change');
		$('#Equipo_Estado').val('').trigger('change');
		$('#Equipo_orderby').val('').trigger('change');
		$('#equipo-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
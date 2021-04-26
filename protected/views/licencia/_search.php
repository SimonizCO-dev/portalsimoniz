<?php
/* @var $this LicenciaController */
/* @var $model Licencia */
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
	          	<?php echo $form->label($model,'Id_Lic'); ?>
			    <?php echo $form->numberField($model,'Id_Lic', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Clasificacion'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Licencia[Clasificacion]',
	                    'id'=>'Licencia_Clasificacion',
	                    'data'=>$lista_clases,
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
	                    'name'=>'Licencia[Tipo]',
	                    'id'=>'Licencia_Tipo',
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
	            <?php echo $form->label($model,'Version'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Licencia[Version]',
	                    'id'=>'Licencia_Version',
	                    'data'=>$lista_versiones,
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
	            <?php echo $form->label($model,'Producto'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Licencia[Producto]',
	                    'id'=>'Licencia_Producto',
	                    'data'=>$lista_productos,
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
	          	<?php echo $form->label($model,'Num_Licencia'); ?>
			    <?php echo $form->textField($model,'Num_Licencia', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	            <?php echo $form->label($model,'Empresa_Compra'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Licencia[Empresa_Compra]',
	                    'id'=>'Licencia_Empresa_Compra',
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
	            <?php echo $form->label($model,'Ubicacion'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Licencia[Ubicacion]',
	                    'id'=>'Licencia_Ubicacion',
	                    'data'=>$lista_ubicaciones,
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
	          	<?php echo $form->label($model,'Numero_Factura'); ?>
			    <?php echo $form->textField($model,'Numero_Factura', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Estado'); ?>
			    <?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Licencia[Estado]',
						'id'=>'Licencia_Estado',
						'data'=>$lista_estados,
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Clasif. ASC', 4 => 'Clasif. DESC', 5 => 'Tipo ASC', 6 => 'Tipo DESC', 7 => 'Versión ASC', 8 => 'Versión DESC' , 9 => 'Producto ASC', 10 => 'Producto DESC', 11 => 'N° de licencia ASC', 12 => 'N° de licencia DESC', 13 => 'Empresa que compro ASC', 14 => 'Empresa que compro DESC', 15 => 'Ubicación ASC', 16 => 'Ubicación DESC', 17 => 'Estado ASC', 18 => 'Estado DESC',
                	);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Licencia[orderby]',
						'id'=>'Licencia_orderby',
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
					        'mGridId' => 'licencia-grid', //Gridview id
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
		$('#Licencia_Id_Lic').val('');
		$('#Licencia_Clasificacion').val('').trigger('change');
		$('#Licencia_Tipo').val('').trigger('change');
		$('#Licencia_Version').val('').trigger('change');
		$('#Licencia_Producto').val('').trigger('change');
		$('#Licencia_Num_Licencia').val('');
		$('#Licencia_Empresa_Compra').val('').trigger('change');
		$('#Licencia_Ubicacion').val('').trigger('change');
		$('#Licencia_Numero_Factura').val('');
		$('#Licencia_Estado').val('').trigger('change');
		$('#Licencia_orderby').val('').trigger('change');
		$('#licencia-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
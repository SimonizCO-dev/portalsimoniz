<?php
/* @var $this PaEmpresaController */
/* @var $model PaEmpresa */
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
	          	<?php echo $form->label($model,'Id_Pa_Empresa'); ?>
			    <?php echo $form->numberField($model,'Id_Pa_Empresa', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Descripcion'); ?>
			    <?php echo $form->textField($model,'Descripcion', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Tipo'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'PaEmpresa[Tipo]',
	                    'id'=>'PaEmpresa_Tipo',
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
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'PaEmpresa[Id_Usuario_Creacion]',
						'id'=>'PaEmpresa_Id_Usuario_Creacion',
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
						'name'=>'PaEmpresa[Id_Usuario_Actualizacion]',
						'id'=>'PaEmpresa_Id_Usuario_Actualizacion',
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
						'name'=>'PaEmpresa[Estado]',
						'id'=>'PaEmpresa_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Empresa ASC', 4 => 'Empresa DESC', 5 => 'Tipo ASC', 6 => 'Tipo DESC', 7 => 'Usuario que creo ASC', 8 => 'Usuario que creo DESC', 9 => 'Fecha de creación ASC', 10 => 'Fecha de creación DESC', 11 => 'Ultimo usuario que actualizó ASC', 12 => 'Ultimo usuario que actualizó DESC', 13 => 'Ultima fecha de actualización ASC', 14 => 'Ultima fecha de actualización DESC', 15 => 'Estado ASC', 16 => 'Estado DESC',
                	);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'PaEmpresa[orderby]',
						'id'=>'PaEmpresa_orderby',
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
					        'mGridId' => 'pa-empresa-grid', //Gridview id
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
		$('#PaEmpresa_Id_Pa_Empresa').val('');
		$('#PaEmpresa_Descripcion').val('');
		$('#PaEmpresa_Tipo').val('').trigger('change');
		$('#PaEmpresa_Id_Usuario_Creacion').val('').trigger('change');
		$('#PaEmpresa_Fecha_Creacion').val('');
		$('#PaEmpresa_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#PaEmpresa_Fecha_Actualizacion').val('');
		$('#PaEmpresa_Estado').val('').trigger('change');
		$('#PaEmpresa_orderby').val('').trigger('change');
		$('#pa-empresa-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>





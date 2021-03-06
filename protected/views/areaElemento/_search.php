<?php
/* @var $this AreaElementoController */
/* @var $model AreaElemento */
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
	          	<?php echo $form->label($model,'Id_A_elemento'); ?>
			    <?php echo $form->numberField($model,'Id_A_elemento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Elemento'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaElemento[Id_Elemento]',
						'id'=>'AreaElemento_Id_Elemento',
						'data'=>$lista_elementos,
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
	          	<?php echo $form->label($model,'Id_Subarea'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaElemento[Id_Subarea]',
						'id'=>'AreaElemento_Id_Subarea',
						'data'=>$lista_subareas,
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
	          	<?php echo $form->label($model,'Id_Area'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaElemento[Id_Area]',
						'id'=>'AreaElemento_Id_Area',
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
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaElemento[Id_Usuario_Creacion]',
						'id'=>'AreaElemento_Id_Usuario_Creacion',
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
						'name'=>'AreaElemento[Id_Usuario_Actualizacion]',
						'id'=>'AreaElemento_Id_Usuario_Actualizacion',
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
						'name'=>'AreaElemento[Estado]',
						'id'=>'AreaElemento_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Elemento ASC', 4 => 'Elemento DESC', 5 => 'Sub??rea ASC', 6 => 'Sub??rea DESC', 7 => '??rea ASC', 8 => '??rea DESC', 9 => 'Usuario que creo ASC', 10 => 'Usuario que creo DESC', 11 => 'Fecha de creaci??n ASC', 12 => 'Fecha de creaci??n DESC', 13 => 'Ultimo usuario que actualiz?? ASC', 14 => 'Ultimo usuario que actualiz?? DESC', 15 => 'Ultima fecha de actualizaci??n ASC', 16 => 'Ultima fecha de actualizaci??n DESC', 17 => 'Estado ASC', 18 => 'Estado DESC',
                	);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'AreaElemento[orderby]',
						'id'=>'AreaElemento_orderby',
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
					        'mGridId' => 'area-elemento-grid', //Gridview id
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
		$('#AreaElemento_Id_A_elemento').val('');
		$('#AreaElemento_Id_Elemento').val('').trigger('change');
		$('#AreaElemento_Id_Area').val('').trigger('change');
		$('#AreaElemento_Id_Subarea').val('').trigger('change');
		$('#AreaElemento_Id_Usuario_Creacion').val('').trigger('change');
		$('#AreaElemento_Fecha_Creacion').val('');
		$('#AreaElemento_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#AreaElemento_Fecha_Actualizacion').val('');
		$('#AreaElemento_Estado').val('').trigger('change');
		$('#AreaElemento_orderby').val('').trigger('change');
		$('#area-elemento-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

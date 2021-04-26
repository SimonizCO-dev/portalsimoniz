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
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Menu'); ?>
			    <?php echo $form->numberField($model,'Id_Menu', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-6">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'padre'); ?>
			    <?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Menu[padre]',
						'id'=>'Menu_padre',
						'data'=>$lista_opciones_p,
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
	          	<?php echo $form->label($model,'Descripcion'); ?>
			    <?php echo $form->textField($model,'Descripcion', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Orden'); ?>
			    <?php echo $form->numberField($model,'Orden', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => 1, 'max' => 10)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Link'); ?>
			    <?php echo $form->textField($model,'Link', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Descarga_Directa'); ?>
			    <?php $estados2 = Yii::app()->params->estados2; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Menu[Descarga_Directa]',
						'id'=>'Menu_Descarga_Directa',
						'data'=>$estados2,
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
						'name'=>'Menu[Id_Usuario_Creacion]',
						'id'=>'Menu_Id_Usuario_Creacion',
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
						'name'=>'Menu[Id_Usuario_Actualizacion]',
						'id'=>'Menu_Id_Usuario_Actualizacion',
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
						'name'=>'Menu[Estado]',
						'id'=>'Menu_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Opción padre ASC', 4 => 'Opción padre DESC', 5 => 'Descripción ASC', 6 => 'Descripción DESC', 7 => 'Orden ASC', 8 => 'Orden DESC', 9 => 'Link ASC', 10 => 'Link DESC', 11 => 'Usuario que creo ASC', 12 => 'Usuario que creo DESC', 13 => 'Fecha de creación ASC', 14 => 'Fecha de creación DESC', 15 => 'Ultimo usuario que actualizó ASC', 16 => 'Ultimo usuario que actualizó DESC', 17 => 'Ultima fecha de actualización ASC', 18 => 'Ultima fecha de actualización DESC', 19 => 'Estado ASC', 20 => 'Estado DESC',
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Menu[orderby]',
						'id'=>'Menu_orderby',
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
				        'mGridId' => 'menu-grid', //Gridview id
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
		$('#Menu_Id_Menu').val('');
		$('#Menu_padre').val('').trigger('change');
		$('#Menu_Descripcion').val('');
		$('#Menu_Orden').val('');
		$('#Menu_Link').val('');
		$('#Menu_Descarga_Directa').val('').trigger('change');
		$('#Menu_Id_Usuario_Creacion').val('').trigger('change');
		$('#Menu_Fecha_Creacion').val('');
		$('#Menu_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#Menu_Fecha_Actualizacion').val('');
		$('#Menu_Estado').val('').trigger('change');
		$('#Menu_orderby').val('').trigger('change');
		$('#menu-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

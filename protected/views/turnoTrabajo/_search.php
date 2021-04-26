<?php
/* @var $this TurnoTrabajoController */
/* @var $model TurnoTrabajo */
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
	          	<?php echo $form->label($model,'Id_Turno_Trabajo'); ?>
			    <?php echo $form->numberField($model,'Id_Turno_Trabajo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Rango_Dias1'); ?>
			    <?php echo $form->textField($model,'Rango_Dias1', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'maxlength'=>3)); ?>
	        </div>
	    </div>
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Entrada1'); ?>
			    <?php echo $form->textField($model,'Entrada1', array('class' => 'form-control form-control-sm timepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Salida1'); ?>
			    <?php echo $form->textField($model,'Salida1', array('class' => 'form-control form-control-sm timepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Rango_Dias2'); ?>
			    <?php echo $form->textField($model,'Rango_Dias2', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'maxlength'=>3)); ?>
	        </div>
	    </div>
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Entrada2'); ?>
			    <?php echo $form->textField($model,'Entrada2', array('class' => 'form-control form-control-sm timepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Salida2'); ?>
			    <?php echo $form->textField($model,'Salida2', array('class' => 'form-control form-control-sm timepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TurnoTrabajo[Id_Usuario_Creacion]',
						'id'=>'TurnoTrabajo_Id_Usuario_Creacion',
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
						'name'=>'TurnoTrabajo[Id_Usuario_Actualizacion]',
						'id'=>'TurnoTrabajo_Id_Usuario_Actualizacion',
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
						'name'=>'TurnoTrabajo[Estado]',
						'id'=>'TurnoTrabajo_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Código ASC', 4 => 'Código DESC', 5 => 'Centro costo ASC', 6 => 'Centro costo DESC', 7 => 'Usuario que creo ASC', 8 => 'Usuario que creo DESC', 9 => 'Fecha de creación ASC', 10 => 'Fecha de creación DESC', 11 => 'Ultimo usuario que actualizó ASC', 12 => 'Ultimo usuario que actualizó DESC', 13 => 'Ultima fecha de actualización ASC', 14 => 'Ultima fecha de actualización DESC', 15 => 'Estado ASC', 16 => 'Estado DESC',
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TurnoTrabajo[orderby]',
						'id'=>'TurnoTrabajo_orderby',
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
				        'mGridId' => 'turno-trabajo-grid', //Gridview id
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
		$('#TurnoTrabajo_Id_Turno_Trabajo').val('');
		$('#TurnoTrabajo_Rango_Dias1').val('');
		$('#TurnoTrabajo_Entrada1').val('');
		$('#TurnoTrabajo_Salida1').val('');
		$('#TurnoTrabajo_Rango_Dias2').val('');
		$('#TurnoTrabajo_Entrada2').val('');
		$('#TurnoTrabajo_Salida2').val('');
		$('#TurnoTrabajo_Id_Usuario_Creacion').val('').trigger('change');
		$('#TurnoTrabajo_Fecha_Creacion').val('');
		$('#TurnoTrabajo_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#TurnoTrabajo_Fecha_Actualizacion').val('');
		$('#TurnoTrabajo_Estado').val('').trigger('change');
		$('#TurnoTrabajo_orderby').val('').trigger('change');
		$('#turno-trabajo-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

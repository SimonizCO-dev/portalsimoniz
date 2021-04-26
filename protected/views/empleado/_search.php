<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */
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
	          	<?php echo $form->label($model,'Id_Empleado'); ?>
			    <?php echo $form->numberField($model,'Id_Empleado', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
         		<?php echo $form->label($model,'Id_Tipo_Ident'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Empleado[Id_Tipo_Ident]',
						'id'=>'Empleado_Id_Tipo_Ident',
						'data'=>$lista_tipos_ident,
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
	          	<?php echo $form->label($model,'Identificacion'); ?>
			    <?php echo $form->numberField($model,'Identificacion', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Apellido'); ?>
			    <?php echo $form->textField($model,'Apellido', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Nombre'); ?>
			    <?php echo $form->textField($model,'Nombre', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
         		<?php echo $form->label($model,'Id_Empresa'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Empleado[Id_Empresa]',
						'id'=>'Empleado_Id_Empresa',
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
	          	<?php echo $form->label($model,'Telefono'); ?>
			    <?php echo $form->textField($model,'Telefono', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Correo'); ?>
			    <?php echo $form->textField($model,'Correo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Empleado[Id_Usuario_Creacion]',
						'id'=>'Empleado_Id_Usuario_Creacion',
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
						'name'=>'Empleado[Id_Usuario_Actualizacion]',
						'id'=>'Empleado_Id_Usuario_Actualizacion',
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
						'name'=>'Empleado[Estado]',
						'id'=>'Empleado_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Tipo de identificación ASC', 4 => 'Tipo de identificación DESC', 5 => 'Identificación ASC', 6 => 'Identificación DESC', 7 => 'Apellidos ASC', 8 => 'Apellidos DESC', 9 => 'Nombres ASC', 10 => 'Nombres DESC', 11 => 'Empresa ASC', 12 => 'Empresa DESC', 13 => 'Teléfonos ASC', 14 => 'Teléfonos DESC', 15 => 'E-mail ASC', 16 => 'E-mail DESC', 17 => 'Usuario que creo ASC', 18 => 'Usuario que creo DESC', 19 => 'Fecha de creación ASC', 20 => 'Fecha de creación DESC', 21 => 'Ultimo usuario que actualizó ASC', 22 => 'Ultimo usuario que actualizó DESC', 23 => 'Ultima fecha de actualización ASC', 24 => 'Ultima fecha de actualización DESC', 25 => 'Estado ASC', 26 => 'Estado DESC',
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Empleado[orderby]',
						'id'=>'Empleado_orderby',
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
				        'mGridId' => 'empleado-grid', //Gridview id
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
		$('#Empleado_Id_Empleado').val('');
		$('#Empleado_Identificacion').val('');
		$('#Empleado_Id_Tipo_Ident').val('').trigger('change');
		$('#Empleado_Nombre').val('');
		$('#Empleado_Apellido').val('');
		$('#Empleado_Id_Empresa').val('').trigger('change');
		$('#Empleado_Telefono').val('');
		$('#Empleado_Correo').val('');
		$('#Empleado_Id_Usuario_Creacion').val('').trigger('change');
		$('#Empleado_Fecha_Creacion').val('');
		$('#Empleado_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#Empleado_Fecha_Actualizacion').val('');
		$('#Empleado_Estado').val('').trigger('change');
		$('#Empleado_orderby').val('').trigger('change');
		$('#empleado-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

<?php
/* @var $this FichaItemController */
/* @var $model FichaItem */
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
	          	<?php echo $form->label($model,'Id'); ?>
			    <?php echo $form->numberField($model,'Id', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	      <div class="form-group">
	          <?php echo $form->label($model,'Pais'); ?>
	          <?php echo $form->error($model,'Pais', array('class' => 'badge badge-warning float-right')); ?>
	          <?php $paises = array(1 => 'COLOMBIA', 2 => 'ECUADOR', 3 => 'PERÚ', 4 => 'CHILE'); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                  'name'=>'FichaItem[Pais]',
	                  'id'=>'FichaItem_Pais',
	                  'data'=>$paises,
	                  'value' => $model->Pais,
	                  'htmlOptions'=>array(
	                  	'multiple'=>'multiple',
	                  ),
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
	          	<?php $tipos = array(1 => 'CREACIÓN', 2 => 'ACTUALIZACIÓN'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FichaItem[Tipo]',
						'id'=>'FichaItem_Tipo',
						'data'=>$tipos,
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
	          	<?php echo $form->label($model,'Tipo_Producto'); ?>
	          	<?php $tipos_p = array(1 => 'PRODUCTO TERMINADO', 2 => 'PRODUCTO EN PROCESO', 3 => 'POP' , 4 => 'MATERIA PRIMA', 5 => 'PROMOCIÓN'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FichaItem[Tipo_Producto]',
						'id'=>'FichaItem_Tipo_Producto',
						'data'=>$tipos_p,
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
	          	<?php echo $form->label($model,'Codigo_Item'); ?>
			    <?php echo $form->textField($model,'Codigo_Item', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Referencia'); ?>
			    <?php echo $form->textField($model,'Referencia', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Descripcion_Corta'); ?>
			    <?php echo $form->textField($model,'Descripcion_Corta', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Solicitud'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FichaItem[Id_Usuario_Solicitud]',
						'id'=>'FichaItem_Id_Usuario_Solicitud',
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
	          	<?php echo $form->label($model,'Fecha_Hora_Solicitud'); ?>
			    <?php echo $form->textField($model,'Fecha_Hora_Solicitud', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FichaItem[Id_Usuario_Actualizacion]',
						'id'=>'FichaItem_Id_Usuario_Actualizacion',
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
	          	<?php echo $form->label($model,'Fecha_Hora_Actualizacion'); ?>
			    <?php echo $form->textField($model,'Fecha_Hora_Actualizacion', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Step'); ?>
	          	<?php $lista_procesos = array(2 => 'Verificación Desarrollo / Innovación', 3 => 'Finanzas / Contabilidad' , 4 => 'Verificación Finanzas / Contabilidad', 5 => 'Comercial / Mercadeo', 6 => 'Verificación Comercial / Mercadeo', 7 => 'Ingeniería', 8 => 'Verificación Ingeniería', 9 => 'Datos Maestros', 10 => 'Finalizado'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FichaItem[Step]',
						'id'=>'FichaItem_Step',
						'data'=>$lista_procesos,
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
	          	<?php echo $form->label($model,'Estado'); ?>
			    <?php $estados = array(0 => 'RECHAZADO', 1 => 'EN ESPERA', 2 => 'APROBADO') ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FichaItem[Estado]',
						'id'=>'FichaItem_Estado',
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
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'ficha-item-grid', //Gridview id
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
		$('#FichaItem_Id').val('');
		$('#FichaItem_Pais').val('').trigger('change');
		$('#FichaItem_Tipo').val('').trigger('change');
		$('#FichaItem_Tipo_Producto').val('').trigger('change');
		$('#FichaItem_Codigo_Item').val('');
		$('#FichaItem_Referencia').val('');
		$('#FichaItem_Descripcion_Corta').val('');
		$('#FichaItem_Id_Usuario_Solicitud').val('').trigger('change');
		$('#FichaItem_Fecha_Hora_Solicitud').val('');
		$('#FichaItem_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#FichaItem_Fecha_Hora_Actualizacion').val('');
		$('#FichaItem_Step').val('').trigger('change');
		$('#FichaItem_Estado').val('').trigger('change');
		$('#ficha-item-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
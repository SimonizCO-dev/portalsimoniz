<?php
/* @var $this RegImpController */
/* @var $model RegImp */
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
	          	<?php echo $form->label($model,'Numero'); ?>
			    <?php echo $form->textField($model,'Numero', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	     <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha'); ?>
			    <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'item'); ?>
			    <?php echo $form->textField($model,'item', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>   
	</div>
	<div class="row">
			    
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'RegImp[Id_Usuario_Creacion]',
						'id'=>'RegImp_Id_Usuario_Creacion',
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
						'name'=>'RegImp[Id_Usuario_Actualizacion]',
						'id'=>'RegImp_Id_Usuario_Actualizacion',
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
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'reg-imp-grid', //Gridview id
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
		$('#RegImp_Numero').val('');
		$('#RegImp_Fecha').val('');
		$('#RegImp_item').val('');
		$('#RegImp_Id_Usuario_Creacion').val('').trigger('change');
		$('#RegImp_Fecha_Creacion').val('');
		$('#RegImp_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#reg-imp-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

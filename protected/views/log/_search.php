<?php
/* @var $this LogController */
/* @var $model Log */
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
				<?php $tipos = array(1 => 'SESIÓN', 2 => 'CONSULTA DE MENÚ', 3 => 'EJECUCIÓN'); ?>
				<?php
				  $this->widget('ext.select2.ESelect2',array(
				      'name'=>'Log[Tipo]',
				      'id'=>'Log_Tipo',
				      'data'=>$tipos,
				      'value' => $model->Tipo,
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
	          	<?php echo $form->label($model,'Id_Usuario'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Log[Id_Usuario]',
						'id'=>'Log_Id_Usuario',
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
	</div>
	<div class="row">
	    <div class="col-sm-6">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Accion'); ?>
			    <?php echo $form->textField($model,'Accion', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-6">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Menu'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Log[Id_Menu]',
						'id'=>'Log_Id_Menu',
						'data'=>$lista_opciones,
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
	          	<?php echo $form->label($model,'fecha_inicial'); ?>
			    <?php echo $form->textField($model,'fecha_inicial', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'fecha_final'); ?>
			    <?php echo $form->textField($model,'fecha_final', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'log-grid', //Gridview id
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
		$('#Log_Tipo').val('').trigger('change');
		$('#Log_Id_Usuario').val('').trigger('change');
		$('#Log_Id_Menu').val('').trigger('change');
		$('#Log_Accion').val('');
		$('#Log_fecha_inicial').val('');
		$('#Log_fecha_final').val('');	
		$('#log-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

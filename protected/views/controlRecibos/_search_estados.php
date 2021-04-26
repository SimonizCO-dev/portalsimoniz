<?php
/* @var $this ControlRecibosController */
/* @var $model ControlRecibos */
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
	          	<?php echo $form->label($model,'Recibo'); ?>
			    <?php echo $form->textField($model,'Recibo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Opc'); ?>
			    <?php $estado_control_recibos = Yii::app()->params->estado_control_recibos_rev; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'ControlRecibos[Opc]',
						'id'=>'ControlRecibos_Opc',
						'data'=>$estado_control_recibos,
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
	    <div class="col-sm-6">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Verificacion'); ?>
			    <?php $estado_verificacion = Yii::app()->params->estado_verificacion; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'ControlRecibos[Verificacion]',
						'id'=>'ControlRecibos_Verificacion',
						'data'=>$estado_verificacion,
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
	</div>
	<div class="row">
	     <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
					        'mGridId' => 'control-recibos-grid', //Gridview id
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
		$('#ControlRecibos_Recibo').val('');
		$('#ControlRecibos_Opc').val('').trigger('change');
		$('#ControlRecibos_Verificacion').val('').trigger('change');
		$('#control-recibos-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
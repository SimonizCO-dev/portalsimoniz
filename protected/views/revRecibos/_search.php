<?php
/* @var $this RevRecibosController */
/* @var $model RevRecibos */
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
	          	<?php echo $form->label($model,'recibo'); ?>
			    <?php echo $form->textField($model,'recibo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Rev'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'RevRecibos[Id_Usuario_Rev]',
						'id'=>'RevRecibos_Id_Usuario_Rev',
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
	          	<?php echo $form->label($model,'fecha_rev'); ?>
			    <?php echo $form->textField($model,'fecha_rev', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
    	<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Opc'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'RevRecibos[Opc]',
						'id'=>'RevRecibos_Opc',
						'data'=>array(2 => "VERIFICADO", 3 => "APLICADO"),
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
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
					        'mGridId' => 'rev-recibos-grid', //Gridview id
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
		$('#RevRecibos_recibo').val('');
		$('#RevRecibos_Id_Usuario_Rev').val('').trigger('change');
		$('#RevRecibos_fecha_rev').val('');
		$('#RevRecibos_Opc').val('').trigger('change');
		$('#rev-recibos-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
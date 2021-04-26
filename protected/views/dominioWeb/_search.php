<?php
/* @var $this DominioWebController */
/* @var $model DominioWeb */
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
	          	<?php echo $form->label($model,'Id_Tipo'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'DominioWeb[Id_Tipo]',
						'id'=>'DominioWeb_Id_Tipo',
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
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Dominio'); ?>
			    <?php echo $form->textField($model,'Dominio', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
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
	          	<?php echo $form->label($model,'Contratado_Por'); ?>
			    <?php echo $form->textField($model,'Contratado_Por', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div> 
	</div>
	<div class="row">
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Activacion'); ?>
			    <?php echo $form->textField($model,'Fecha_Activacion', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Vencimiento'); ?>
			    <?php echo $form->textField($model,'Fecha_Vencimiento', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
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
						'name'=>'DominioWeb[Estado]',
						'id'=>'DominioWeb_Estado',
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
	            <?php echo $form->label($model,'view'); ?>
	            <?php 
	                $array_view = array(1 => 'Registros fuera de termino', 2 => 'Registros sin alerta', 3 => 'Registros inactivos');
	            ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'DominioWeb[view]',
	                    'id'=>'DominioWeb_view',
	                    'data'=>$array_view,
	                    'htmlOptions'=>array(),
	                    'options'=>array(
	                        'placeholder'=>'Seleccione..',
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
                	$array_orden = array(1 => 'Tipo ASC', 2 => 'Tipo DESC', 3 => 'Dominio ASC', 4 => 'Dominio DESC', 5 => 'Link ASC', 6 => 'Link DESC', 7 => 'Contratado por ASC', 8 => 'Contratado por DESC', 9 => 'Fecha de activación ASC', 10 => 'Fecha de activación DESC', 11 => 'Fecha de vencimiento ASC', 12 => 'Fecha de vencimiento DESC', 13 => 'Estado ASC', 14 => 'Estado DESC',
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'DominioWeb[orderby]',
						'id'=>'DominioWeb_orderby',
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
				        'mGridId' => 'dominio-web-grid', //Gridview id
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
		$('#DominioWeb_Id_Tipo').val('').trigger('change');
		$('#DominioWeb_Dominio').val(''); 
		$('#DominioWeb_Link').val(''); 
		$('#DominioWeb_Contratado_Por').val('');
		$('#DominioWeb_Fecha_Activacion').val('');
		$('#DominioWeb_Fecha_Vencimiento').val('');
    	$('#DominioWeb_Estado').val('').trigger('change');
    	$('#DominioWeb_view').val('').trigger('change');
    	$('#DominioWeb_orderby').val('').trigger('change');
		$('#dominio-web-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>


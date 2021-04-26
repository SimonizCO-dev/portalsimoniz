<?php
/* @var $this CuentaEmpleadoController */
/* @var $model CuentaEmpleado */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>


<p>Utilice los filtros para optimizar la busqueda:</p>

	<div class="row">
	    <div class="col-sm-6">
	        <div class="form-group">
	        	<?php echo $form->error($model,'Id_Cuenta', array('class' => 'pull-right badge bg-red')); ?>
	    		<?php echo $form->label($model,'Id_Cuenta'); ?>
	            <?php echo $form->textField($model,'Id_Cuenta'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2', array(
	                    'selector' => '#CuentaEmpleado_Id_Cuenta',
	                    'options'  => array(
	                        'allowClear' => true,
	                        'minimumInputLength' => 3,
	                        'width' => '100%',
	                        'language' => 'es',
	                        'ajax' => array(
	                            'url' => Yii::app()->createUrl('cuentaempleado/SearchCuenta'),
	                            'dataType'=>'json',
	                            'data'=>'js:function(term){return{q: term};}',
	                            'results'=>'js:function(data){ return {results:data};}'                   
	                        ),
	                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("CuentaEmpleado_Id_Cuenta"); return "No se encontraron resultados"; }',
	                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'CuentaEmpleado_Id_Cuenta\')\">Limpiar campo</button>"; }',
	                    ),
	                ));
	            ?>
	        </div>
	    </div> 
    </div>
    <div class="row">
    	<div class="col-sm-9">
	        <div class="form-group">
	        	<?php echo $form->error($model,'Id_Empleado', array('class' => 'pull-right badge bg-red')); ?>
	    		<?php echo $form->label($model,'Id_Empleado'); ?>
	            <?php echo $form->textField($model,'Id_Empleado'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2', array(
	                    'selector' => '#CuentaEmpleado_Id_Empleado',
	                    'options'  => array(
	                        'allowClear' => true,
	                        'minimumInputLength' => 3,
	                        'width' => '100%',
	                        'language' => 'es',
	                        'ajax' => array(
	                            'url' => Yii::app()->createUrl('cuentaempleado/SearchEmpleado'),
	                            'dataType'=>'json',
	                            'data'=>'js:function(term){return{q: term};}',
	                            'results'=>'js:function(data){ return {results:data};}'                   
	                        ),
	                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("CuentaEmpleado_Id_Empleado"); return "No se encontraron resultados"; }',
	                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'CuentaEmpleado_Id_Empleado\')\">Limpiar campo</button>"; }',
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
						'name'=>'CuentaEmpleado[Id_Usuario_Creacion]',
						'id'=>'CuentaEmpleado_Id_Usuario_Creacion',
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
						'name'=>'CuentaEmpleado[Id_Usuario_Actualizacion]',
						'id'=>'CuentaEmpleado_Id_Usuario_Actualizacion',
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
						'name'=>'CuentaEmpleado[Estado]',
						'id'=>'CuentaEmpleado_Estado',
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
                	$array_orden = array(1 => 'Usuario que creo ASC', 2 => 'Usuario que creo DESC', 3 => 'Fecha de creación ASC', 4 => 'Fecha de creación DESC', 5 => 'Usuario que actualizó ASC', 6 => 'Usuario que actualizó DESC', 7 => 'Fecha de actualización ASC', 8 => 'Fecha de actualización DESC', 9 => 'Estado ASC', 10 => 'Estado DESC',
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CuentaEmpleado[orderby]',
						'id'=>'CuentaEmpleado_orderby',
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
				        'mGridId' => 'cuenta-empleado-grid', //Gridview id
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
		$('#CuentaEmpleado_Id_Cuenta').val('').trigger('change');
	  	$('#s2id_CuentaEmpleado_Id_Cuenta span').html("");
	  	$('#CuentaEmpleado_Id_Empleado').val('').trigger('change');
	  	$('#s2id_CuentaEmpleado_Id_Empleado span').html("");
		$('#CuentaEmpleado_usuario_creacion').val('').trigger('change');
		$('#CuentaEmpleado_Fecha_Creacion').val('');
		$('#CuentaEmpleado_usuario_actualizacion').val('').trigger('change');
		$('#CuentaEmpleado_Fecha_Actualizacion').val('');
		$('#CuentaEmpleado_Estado').val('').trigger('change');
		$('#CuentaEmpleado_orderby').val('').trigger('change');
		$('#cuenta-empleado-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}

	function clear_select2_ajax(id){
	  $('#'+id+'').val('').trigger('change');
	  $('#s2id_'+id+' span').html("");
	}
	
</script>


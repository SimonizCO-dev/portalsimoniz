<?php
/* @var $this GdAudDocumentoController */
/* @var $model GdAudDocumento */
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
	          	<?php echo $form->label($model,'Id_Documento'); ?>
			    <?php echo $form->numberField($model,'Id_Documento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'clasif_doc'); ?>
			    <?php $clasif = Yii::app()->params->clasif_docs; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAudDocumento[clasif_doc]',
						'id'=>'GdAudDocumento_clasif_doc',
						'data'=>$clasif,
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
	          	<?php echo $form->label($model,'tipo_doc'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAudDocumento[tipo_doc]',
						'id'=>'GdAudDocumento_tipo_docc',
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
	          	<?php echo $form->label($model,'num_doc'); ?>
			    <?php echo $form->textField($model,'num_doc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">    
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'tit_doc'); ?>
			    <?php echo $form->textField($model,'tit_doc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'n_v_doc'); ?>
			    <?php echo $form->numberField($model,'n_v_doc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Accion'); ?>
	          	<?php $acciones = Yii::app()->params->acciones; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAudDocumento[Accion]',
						'id'=>'GdAudDocumento_Accion',
						'data'=>$acciones,
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
						'name'=>'GdAudDocumento[Id_Usuario]',
						'id'=>'GdAudDocumento_Id_Usuario',
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
	          	<?php echo $form->label($model,'fecha_inicial'); ?>
			    <?php echo $form->textField($model,'fecha_inicial', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'fecha_final'); ?>
			    <?php echo $form->textField($model,'fecha_final', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'orderby'); ?>
			    <?php 
                	$array_orden = array(1 => 'ID docto ASC', 2 => 'ID docto DESC', 3 => 'N° documento ASC', 4 => 'N° documento DESC', 5 => 'Nombre ASC', 6 => 'Nombre DESC', 7 => 'Acción ASC', 8 => 'Acción DESC', 9 => 'Usuario ASC', 10 => 'Usuario DESC', 11 => 'Fecha y hora ASC', 12 => 'Fecha y hora DESC');
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'GdAudDocumento[orderby]',
						'id'=>'GdAudDocumento_orderby',
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
				        'mGridId' => 'gd-aud-documento-grid', //Gridview id
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

	$(function() {

		//variables para el lenguaje del datepicker
		$.fn.datepicker.dates['es'] = {
		  days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		  daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		  daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
		  months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		  monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		  today: "Hoy",
		  clear: "Limpiar",
		  format: "yyyy-mm-dd",
		  titleFormat: "MM yyyy",
		  weekStart: 1
		};

		$("#GdAudDocumento_fecha_inicial").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
		}).on('changeDate', function (selected) {
		var minDate = new Date(selected.date.valueOf());
		$('#GdAudDocumento_fecha_final').datepicker('setStartDate', minDate);
		});

		$("#GdAudDocumento_fecha_final").datepicker({
		  language: 'es',
		  autoclose: true,
		  orientation: "right bottom",
		}).on('changeDate', function (selected) {
		var maxDate = new Date(selected.date.valueOf());
		$('#GdAudDocumento_fecha_inicial').datepicker('setEndDate', maxDate);
		});

	});


	function resetfields(){
		$('#GdAudDocumento_Id_Documento').val('');
		$('#GdAudDocumento_clasif_doc').val('').trigger('change');
		$('#GdAudDocumento_tipo_doc').val('').trigger('change');
		$('#GdAudDocumento_num_doc').val('');
		$('#GdAudDocumento_tit_doc').val('');
		$('#GdAudDocumento_n_v_doc').val('');
		$('#GdAudDocumento_Accion').val('').trigger('change');
		$('#GdAudDocumento_Id_Usuario').val('').trigger('change');
		$('#GdAudDocumento_fecha_inicial').val('');
		$('#GdAudDocumento_fecha_final').val('');
		$('#GdAudDocumento_orderby').val('').trigger('change');		
		$('#gd-aud-documento-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
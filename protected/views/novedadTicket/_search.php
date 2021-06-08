<?php
/* @var $this NovedadTicketController */
/* @var $model NovedadTicket */
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
	          	<?php echo $form->label($model,'Id_Novedad'); ?>
			    <?php echo $form->numberField($model,'Id_Novedad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Grupo'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'NovedadTicket[Id_Grupo]',
						'id'=>'NovedadTicket_Id_Grupo',
						'data'=>$lista_grupos,
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
	    <div class="col-sm-3" id="div_padre" style="display: none;">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Novedad_Padre'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'NovedadTicket[Id_Novedad_Padre]',
						'id'=>'NovedadTicket_Id_Novedad_Padre',
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
	          	<?php echo $form->label($model,'Novedad'); ?>
			    <?php echo $form->textField($model,'Novedad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'NovedadTicket[Id_Usuario_Creacion]',
						'id'=>'NovedadTicket_Id_Usuario_Creacion',
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
						'name'=>'NovedadTicket[Id_Usuario_Actualizacion]',
						'id'=>'NovedadTicket_Id_Usuario_Actualizacion',
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
						'name'=>'NovedadTicket[Estado]',
						'id'=>'NovedadTicket_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Grupo ASC', 4 => 'Grupo DESC', 5 => 'Novedad ASC', 6 => 'Novedad DESC', 7 => 'Usuario que creo ASC', 8 => 'Usuario que creo DESC', 9 => 'Fecha de creación ASC', 10 => 'Fecha de creación DESC', 11 => 'Usuario que actualizó ASC', 12 => 'Usuario que actualizó DESC', 13 => 'Fecha de actualización ASC', 14 => 'Fecha de actualización DESC'
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'NovedadTicket[orderby]',
						'id'=>'NovedadTicket_orderby',
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
				        'mGridId' => 'novedad-ticket-grid', //Gridview id
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

		  $('#NovedadTicket_Id_Grupo').change(function() {
	        
	        $("#NovedadTicket_Padre").html('');
	        $("#NovedadTicket_Padre").append('<option value=""></option>');  

	        if($(this).val() != ""){
	            $('#div_padre').show();
	            loadopc($(this).val());
	        }else{
	            $('#div_padre').hide();
	        }
	    });

	});

	function resetfields(){
		$('#NovedadTicket_Id_Novedad').val('');
		$('#NovedadTicket_Id_Grupo').val('').trigger('change');
		$('#NovedadTicket_Novedad').val('');
		$('#NovedadTicket_Id_Usuario_Creacion').val('').trigger('change');
		$('#NovedadTicket_Fecha_Creacion').val('');
		$('#NovedadTicket_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#NovedadTicket_Fecha_Actualizacion').val('');
		$('#NovedadTicket_Estado').val('').trigger('change');
		$('#NovedadTicket_orderby').val('').trigger('change');
		$('#novedad-ticket-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}

	function loadopc(grupo){

	    var data = {grupo: grupo, id: ""}
	    $.ajax({ 
	      type: "POST", 
	      url: "<?php echo Yii::app()->createUrl('novedadticket/loadopc'); ?>",
	      data: data,
	      dataType: 'json',
	      success: function(data){ 
	        var opcs = data;
	        $("#NovedadTicket_Id_Novedad_Padre").html('');
	        $("#NovedadTicket_Id_Novedad_Padre").append('<option value=""></option>');
	        $('#NovedadTicket_Id_Novedad_Padre').val('').trigger('change');
	        $.each(opcs, function(i,item){
	            $("#NovedadTicket_Id_Novedad_Padre").append('<option value="'+opcs[i].id+'">'+opcs[i].text+'</option>');
	        });

	        $("#div_padre").show();

	      }  
	    });

	}
	
</script>
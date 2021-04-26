<?php
/* @var $this TipoActController */
/* @var $model TipoAct */
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
			    <?php echo $form->numberField($model,'Id_Tipo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Clasificacion', array('class' => 'control-label')); ?>
	            <?php $clasif = array(1 => 'GENERAL', 2 => 'DISPONIBILIDAD'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'TipoAct[Clasificacion]',
	                    'id'=>'TipoAct_Clasificacion',
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
	          	<?php echo $form->label($model,'Id_Grupo'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TipoAct[Id_Grupo]',
						'id'=>'TipoAct_Id_Grupo',
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
	          	<?php echo $form->label($model,'Padre'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TipoAct[Padre]',
						'id'=>'TipoAct_Padre',
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
	          	<?php echo $form->label($model,'Tipo'); ?>
			    <?php echo $form->textField($model,'Tipo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Inicio'); ?>
			    <?php echo $form->textField($model,'Fecha_Inicio', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Fin'); ?>
			    <?php echo $form->textField($model,'Fecha_Fin', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	            <?php echo $form->label($model,'Ind_Alto'); ?>
	            <?php echo $form->numberField($model,'Ind_Alto', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'max' => '100', 'step' => '0.01')); ?>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	            <?php echo $form->label($model,'Ind_Medio'); ?>
	            <?php echo $form->numberField($model,'Ind_Medio', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'max' => '100', 'step' => '0.01')); ?>
	        </div>
	    </div>
	    <div class="col-sm-2">
	        <div class="form-group">
	            <?php echo $form->label($model,'Ind_Bajo'); ?>
	            <?php echo $form->numberField($model,'Ind_Bajo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'max' => '100', 'step' => '0.01')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TipoAct[Id_Usuario_Creacion]',
						'id'=>'TipoAct_Id_Usuario_Creacion',
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
						'name'=>'TipoAct[Id_Usuario_Actualizacion]',
						'id'=>'TipoAct_Id_Usuario_Actualizacion',
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
			    <?php $estados = array(0 => "INACTIVO", 1 => "EN CURSO", 2 => "FINALIZADO", 3 => "POSPUESTO"); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TipoAct[Estado]',
						'id'=>'TipoAct_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Grupo ASC', 4 => 'Grupo DESC', 5 => 'Tipo ASC', 6 => 'Tipo DESC', 7 => 'Usuario que creo ASC', 8 => 'Usuario que creo DESC', 9 => 'Fecha de creación ASC', 10 => 'Fecha de creación DESC', 11 => 'Usuario que actualizó ASC', 12 => 'Usuario que actualizó DESC', 13 => 'Fecha de actualización ASC', 14 => 'Fecha de actualización DESC'
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'TipoAct[orderby]',
						'id'=>'TipoAct_orderby',
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
				        'mGridId' => 'tipo-act-grid', //Gridview id
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

		  $('#TipoAct_Id_Grupo').change(function() {
	        
	        $("#TipoAct_Padre").html('');
	        $("#TipoAct_Padre").append('<option value=""></option>');  

	        if($(this).val() != ""){
	            $('#div_padre').show();
	            loadopc($(this).val());
	        }else{
	            $('#div_padre').hide();
	        }
	    });

	});

	function resetfields(){
		$('#TipoAct_Id_Tipo').val('');
		$('#TipoAct_Clasificacion').val('').trigger('change');
		$('#TipoAct_Id_Grupo').val('').trigger('change');
		$('#TipoAct_Tipo').val('');
		$('#TipoAct_Ind_Alto').val('');
		$('#TipoAct_Ind_Medio').val('');
		$('#TipoAct_Ind_Bajo').val('');
		$('#TipoAct_Id_Usuario_Creacion').val('').trigger('change');
		$('#TipoAct_Fecha_Creacion').val('');
		$('#TipoAct_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#TipoAct_Fecha_Actualizacion').val('');
		$('#TipoAct_Estado').val('').trigger('change');
		$('#TipoAct_orderby').val('').trigger('change');
		$('#tipo-act-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}

	function loadopc(grupo){

	    var data = {grupo: grupo, id: ""}
	    $.ajax({ 
	      type: "POST", 
	      url: "<?php echo Yii::app()->createUrl('tipoAct/loadopc'); ?>",
	      data: data,
	      dataType: 'json',
	      success: function(data){ 
	        var opcs = data;
	        $("#TipoAct_Padre").html('');
	        $("#TipoAct_Padre").append('<option value=""></option>');
	        $('#TipoAct_Padre').val('').trigger('change');
	        $.each(opcs, function(i,item){
	            $("#TipoAct_Padre").append('<option value="'+opcs[i].id+'">'+opcs[i].text+'</option>');
	        });

	        $("#div_padre").show();

	      }  
	    });

	}
	
</script>
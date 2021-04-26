<?php
/* @var $this SolPromController */
/* @var $model SolProm */
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
	          	<?php echo $form->label($model,'Num_Sol'); ?>
			    <?php echo $form->textField($model,'Num_Sol', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Responsable'); ?>
	            <?php echo $form->textField($model,'Responsable', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Tipo', array('class' => 'control-label')); ?>
	        	<?php
	        		$this->widget('ext.select2.ESelect2',array(
						'name'=>'SolProm[Tipo]',
						'id'=>'SolProm_Tipo',
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
	    <div class="col-sm-6" id="info_cliente" style="display: none;">
	    	<div class="form-group">
		      <?php echo $form->label($model,'Cliente'); ?>
		      <?php echo $form->textField($model,'Cliente'); ?>
		      <?php
		      $this->widget('ext.select2.ESelect2', array(
		          'selector' => '#SolProm_Cliente',

		          'options'  => array(
		            'allowClear' => true,
		            'minimumInputLength' => 3,
		                'width' => '100%',
		                'language' => 'es',
		                'ajax' => array(
		                      'url' => Yii::app()->createUrl('SolProm/SearchCliente'),
		                  'dataType'=>'json',
		                    'data'=>'js:function(term){return{q: term, estructura: $("#SolProm_Tipo").val()};}',
		                    'results'=>'js:function(data){ return {results:data};}'
		                             
		              ),
		              'formatNoMatches'=> 'js:function(){ clear_select2_ajax("SolProm_Cliente"); return "No se encontraron resultados"; }',
		              'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'SolProm_Cliente\')\">Limpiar campo</button>"; }',
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
						'name'=>'SolProm[Id_Usuario_Creacion]',
						'id'=>'SolProm_Id_Usuario_Creacion',
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
						'name'=>'SolProm[Id_Usuario_Actualizacion]',
						'id'=>'SolProm_Id_Usuario_Actualizacion',
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
			    <?php $estados = array(0 => "RECHAZADO GERENCIA", 1 => "REV. GERENCIA", 2 => "REV. PLANEACIÓN", 3 => "REV. LOGÍSTICA") ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'SolProm[Estado]',
						'id'=>'SolProm_Estado',
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
				        'mGridId' => 'sol-prom-grid', //Gridview id
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

	    $("#SolProm_Tipo").change(function() {
	  		var tipo = $(this).val();

	  		$('#SolProm_Cliente').val('').trigger('change');
		    $('#s2id_SolProm_Cliente span').html("");

		  	if(tipo != ""){
	      		$("#info_cliente").show();
		 	}else{
		 		$("#info_cliente").hide();
		 	}

		});

	});


	function resetfields(){
		$('#SolProm_Num_Sol').val('');
		$('#SolProm_Responsable').val('');
		$('#SolProm_Tipo').val('').trigger('change');
		$('#SolProm_Id_Usuario_Creacion').val('').trigger('change');
		$('#SolProm_Fecha_Creacion').val('');
		$('#SolProm_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#SolProm_Fecha_Actualizacion').val('');
		$('#SolProm_Estado').val('').trigger('change');
		$('#sol-prom-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

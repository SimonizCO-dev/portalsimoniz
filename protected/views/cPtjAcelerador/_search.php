<?php
/* @var $this CPtjAceleradorController */
/* @var $model CPtjAcelerador */
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
	          	<?php echo $form->label($model,'ROWID'); ?>
			    <?php echo $form->numberField($model,'ROWID', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'TIPO'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CPtjAcelerador[TIPO]',
						'id'=>'CPtjAcelerador_TIPO',
						'data'=> $lista_tipos,
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
	          	<?php echo $form->label($model,'ID_ACELERADOR'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CPtjAcelerador[ID_ACELERADOR]',
						'id'=>'CPtjAcelerador_ID_ACELERADOR',
						'data'=> $lista_aceler,
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
	            <?php echo $form->label($model,'ITEM'); ?>
	            <?php echo $form->textField($model,'ITEM'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2', array(
	                    'selector' => '#CPtjAcelerador_ITEM',
	                    'options'  => array(
	                        'allowClear' => true,
	                        'minimumInputLength' => 3,
	                        'width' => '100%',
	                        'language' => 'es',
	                        'ajax' => array(
	                            'url' => Yii::app()->createUrl('CPtjAcelerador/SearchItem'),
	                            'dataType'=>'json',
	                            'data'=>'js:function(term){return{q: term};}',
	                            'results'=>'js:function(data){ return {results:data};}'                   
	                        ),
	                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("CPtjAcelerador_ITEM"); return "No se encontraron resultados"; }',
	                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'CPtjAcelerador_ITEM\')\">Limpiar campo</button>"; }'
	                    ),
	                ));
	            ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'ID_PLAN'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CPtjAcelerador[ID_PLAN]',
						'id'=>'CPtjAcelerador_ID_PLAN',
						'data'=>$lista_planes,
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
	    <div class="col-sm-3" id="div_criterio" style="display: none;">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'CRITERIO'); ?>
		        <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'CPtjAcelerador[CRITERIO]',
	                    'id'=>'CPtjAcelerador_CRITERIO',
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
	          	<?php echo $form->label($model,'FECHA_INICIAL'); ?>
			    <?php echo $form->textField($model,'FECHA_INICIAL', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'FECHA_FINAL'); ?>
			    <?php echo $form->textField($model,'FECHA_FINAL', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'PORCENTAJE'); ?>
			    <?php echo $form->numberField($model,'PORCENTAJE', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'step' => '0.01', 'min' => '0.01', 'placeholder' => '0,01')); ?>
	        </div>
	    </div>
    </div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'ID_USUARIO_CREACION'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CPtjAcelerador[ID_USUARIO_CREACION]',
						'id'=>'CPtjAcelerador_ID_USUARIO_CREACION',
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
	          	<?php echo $form->label($model,'FECHA_CREACION'); ?>
			    <?php echo $form->textField($model,'FECHA_CREACION', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'ID_USUARIO_ACTUALIZACION'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CPtjAcelerador[ID_USUARIO_ACTUALIZACION]',
						'id'=>'CPtjAcelerador_ID_USUARIO_ACTUALIZACION',
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
	          	<?php echo $form->label($model,'FECHA_ACTUALIZACION'); ?>
			    <?php echo $form->textField($model,'FECHA_ACTUALIZACION', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'ESTADO'); ?>
			    <?php $estados = Yii::app()->params->estados; ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CPtjAcelerador[ESTADO]',
						'id'=>'CPtjAcelerador_ESTADO',
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
				        'mGridId' => 'cptj-acelerador-grid', //Gridview id
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
	
		$('#CPtjAcelerador_ID_PLAN').change(function() {

			//hidemsg();
			$("#CPtjAcelerador_CRITERIO").html('');
			$("#CPtjAcelerador_CRITERIO").append('<option value=""></option>');  

			if($(this).val() != ""){
			    $('#div_criterio').show();
			    loadcriterios($(this).val());
			}else{
			    $('#div_criterio').hide();
			}
		});

	});

	function loadcriterios(plan){

	    var data = {plan: plan}
	    $.ajax({ 
	      type: "POST", 
	      url: "<?php echo Yii::app()->createUrl('CPtjAcelerador/loadcriterios'); ?>",
	      data: data,
	      dataType: 'json',
	      success: function(data){ 
	        var criterios = data;
	        $("#CPtjAcelerador_CRITERIO").html('');
	        $("#CPtjAcelerador_CRITERIO").append('<option value=""></option>');
	        $('#CPtjAcelerador_CRITERIO').val('').trigger('change');
	        $.each(criterios, function(i,item){
	            $("#CPtjAcelerador_CRITERIO").append('<option value="'+criterios[i].id+'">'+criterios[i].text+'</option>');
	        });

	        $("#div_criterio").show();

	      }  
	    });

	}

	function resetfields(){
		$('#CPtjAcelerador_ROWID').val('');
		$('#CPtjAcelerador_TIPO').val('').trigger('change');
		$('#CPtjAcelerador_ITEM').val('').trigger('change');
	    $('#s2id_CPtjAcelerador_ITEM span').html("");
	    $('#CPtjAcelerador_ID_PLAN').val('').trigger('change');
	    $('#CPtjAcelerador_CRITERIO').val('').trigger('change');
	    $('#CPtjAcelerador_FECHA_INICIAL').val('');
	    $('#CPtjAcelerador_FECHA_FINAL').val('');
	    $('#CPtjAcelerador_PORCENTAJE').val('');
	    $('#CPtjAcelerador_ID_USUARIO_CREACION').val('').trigger('change');
		$('#CPtjAcelerador_FECHA_CREACION').val('');
		$('#CPtjAcelerador_ID_USUARIO_ACTUALIZACION').val('').trigger('change');
		$('#CPtjAcelerador_FECHA_ACTUALIZACION').val('');
		$('#CPtjAcelerador_ESTADO').val('').trigger('change');
		$('#cptj-acelerador-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

	

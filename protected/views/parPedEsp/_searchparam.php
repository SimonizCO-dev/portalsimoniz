<?php
/* @var $this ParPedEspController */
/* @var $model ParPedEsp */
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
	          	<?php echo $form->label($model,'Consecutivo'); ?>
			    <?php echo $form->textField($model,'Consecutivo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha'); ?>
			    <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-6">
	    	<div class="form-group">
		      <?php echo $form->error($model,'Nit', array('class' => 'pull-right badge bg-red')); ?>
		      <?php echo $form->label($model,'Nit'); ?>
		      <?php echo $form->textField($model,'Nit'); ?>
		      <?php
		      $this->widget('ext.select2.ESelect2', array(
		          'selector' => '#ParPedEsp_Nit',

		          'options'  => array(
		            'allowClear' => true,
		            'minimumInputLength' => 3,
		                'width' => '100%',
		                'language' => 'es',
		                'ajax' => array(
		                      'url' => Yii::app()->createUrl('ParPedEsp/SearchCliente'),
		                  'dataType'=>'json',
		                    'data'=>'js:function(term){return{q: term};}',
		                    'results'=>'js:function(data){ return {results:data};}'
		                             
		              ),
		              'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ParPedEsp_Nit"); return "No se encontraron resultados"; }',
		              'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'ParPedEsp_Nit\')\">Limpiar campo</button>"; }',
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
				        'mGridId' => 'par-ped-esp-grid', //Gridview id
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
		$('#ParPedEsp_Consecutivo').val('');
		$('#ParPedEsp_Fecha').val('');
		$('#ParPedEsp_Nit').val('').trigger('change');
    	$('#s2id_ParPedEsp_Nit span').html("");		
		$('#par-ped-esp-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
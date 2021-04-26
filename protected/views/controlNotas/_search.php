<?php
/* @var $this ControlNotasController */
/* @var $model ControlNotas */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<div class="col-sm-6">
  		<div class="form-group">
				<?php echo $form->label($model,'Id_Cliente'); ?>
				<?php echo $form->textField($model,'Id_Cliente'); ?>
				<?php
				$this->widget('ext.select2.ESelect2', array(
				  'selector' => '#ControlNotas_Id_Cliente',
				  'options'  => array(
				    'allowClear' => true,
				    'minimumInputLength' => 5,
				        'width' => '100%',
				        'language' => 'es',
				        'ajax' => array(
				              'url' => Yii::app()->createUrl('controlNotas/SearchCliente'),
				          'dataType'=>'json',
				            'data'=>'js:function(term){return{q: term};}',
				            'results'=>'js:function(data){ return {results:data};}'
				                     
				      ),
				      'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ControlNotas_Id_Cliente"); return "No se encontraron resultados"; }',
				      'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'ControlNotas_Id_Cliente\')\">Limpiar campo</button>"; }',
				  ),
				));
				?>		
			</div>
		</div>
		<div class="col-sm-2">
	  	<div class="form-group">
			<?php echo $form->label($model,'Nota'); ?>
		    <?php echo $form->textField($model,'Nota', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
	  	</div>
	  </div>
	  <div class="col-sm-2">
	  	<div class="form-group">
			<?php echo $form->label($model,'Factura'); ?>
		    <?php echo $form->textField($model,'Factura', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
	  	</div>
	  </div>
	  <div class="col-sm-2">
	  	<div class="form-group">
			<?php echo $form->label($model,'Recibo'); ?>
		    <?php echo $form->textField($model,'Recibo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
	  	</div>
	  </div>
	</div>
	<div class="row">
		<div class="col-sm-3">
  			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Factura'); ?>
		    	<?php echo $form->textField($model,'Fecha_Factura', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>		
			</div>
		</div>
		<div class="col-sm-3">
  			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Pago'); ?>
		    	<?php echo $form->textField($model,'Fecha_Pago', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>			
			</div>
		</div>
		<div class="col-sm-3">
	  	<div class="form-group">
				<?php echo $form->label($model,'Dias_Pago'); ?>
	    	<?php echo $form->numberField($model,'Dias_Pago', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	  	</div>
	  </div>
	  <div class="col-sm-3">
	  	<div class="form-group">
	  		<?php echo $form->label($model,'Respuesta'); ?>
	      	<?php
	          $this->widget('ext.select2.ESelect2',array(
	              'name'=>'ControlNotas[Respuesta]',
	              'id'=>'ControlNotas_Respuesta',
	              'data'=>array(0 => "EN ELAB.", 1 => "APROBADO", 2 => "NO APROBADO"),
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
		    	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
		    	<?php
		    		$this->widget('ext.select2.ESelect2',array(
							'name'=>'ControlNotas[Id_Usuario_Creacion]',
							'id'=>'ControlNotas_Id_Usuario_Creacion',
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
						'name'=>'ControlNotas[Id_Usuario_Actualizacion]',
						'id'=>'ControlNotas_Id_Usuario_Actualizacion',
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
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'control-notas-grid', //Gridview id
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
		$('#ControlNotas_Id_Cliente').val('').trigger('change');
		$('#s2id_ControlNotas_Id_Cliente span').html("");
		$('#ControlNotas_Nota').val('');
		$('#ControlNotas_Factura').val('');
		$('#ControlNotas_Recibo').val('');
		$('#ControlNotas_Fecha_Factura').val('');
		$('#ControlNotas_Fecha_Pago').val('');
		$('#ControlNotas_Dias_Pago').val('');
		$('#ControlNotas_Respuesta').val('').trigger('change');
		$('#ControlNotas_Id_Usuario_Creacion').val('').trigger('change');
		$('#ControlNotas_Fecha_Creacion').val('');
		$('#ControlNotas_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#ControlNotas_Fecha_Actualizacion').val('');
		$('#control-notas-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
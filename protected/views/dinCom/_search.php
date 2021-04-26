<?php
/* @var $this DinComController */
/* @var $model DinCom */
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
	          	<?php echo $form->label($model,'Id_Dic_Com'); ?>
			    <?php echo $form->numberField($model,'Id_Dic_Com', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	      <div class="form-group">
	          <?php echo $form->label($model,'Pais'); ?>
	          <?php echo $form->error($model,'Pais', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                  'name'=>'DinCom[Pais]',
	                  'id'=>'DinCom_Pais',
	                  'data'=>UtilidadesVarias::listapaises(),
	                  'value' => $model->Pais,
	                  'htmlOptions'=>array(
	                  	'multiple'=>'multiple',
	                  ),
	                  'options'=>array(
	                      'placeholder'=>'Seleccione...',
	                      'width'=> '100%',
	                      'allowClear'=>true,
	                  ),
	              ));
	          ?>
	      </div>
	    </div>
	   	<div class="col-sm-4">
			<div class="form-group">
			  <?php echo $form->label($model,'Tipo'); ?>
			  <?php $tipos = array(1 => 'ITEM', 2 => 'CLIENTE', 3 => 'CRITERIO CLIENTE', 4 => 'CRITERIO ITEM', 5 => 'OBSEQUIO', 6 => 'LISTA PRECIOS', 7 => 'CO', 8 => 'ITEM / CLIENTE', 9 => 'ITEM / CRITERIO CLIENTE', 10 => 'ITEM / LISTA DE PRECIOS ', 11 => 'ITEM / CO', 12 => 'CRITERIO ITEM / CRITERIO CLIENTE', 13 => 'CRITERIO ITEM / CLIENTE', 14 => 'CRITERIO ITEM / LISTA DE PRECIOS', 15 => 'CRITERIO ITEM / CO', 16 => 'CRITERIO CLIENTE / LISTA DE PRECIOS', 17 => 'CRITERIO CLIENTE / CO', 18 => 'CLIENTE / LISTA DE PRECIOS', 19 => 'CLIENTE / CO', 20 => 'LISTA DE PRECIOS / CO'); ?>
			  <?php
			      $this->widget('ext.select2.ESelect2',array(
			          'name'=>'DinCom[Tipo]',
			          'id'=>'DinCom_Tipo',
			          'data'=>$tipos,
			          'value' => $model->Tipo,
			          'htmlOptions'=>array(
			          ),
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
						'name'=>'DinCom[Id_Usuario_Creacion]',
						'id'=>'DinCom_Id_Usuario_Creacion',
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
						'name'=>'DinCom[Id_Usuario_Actualizacion]',
						'id'=>'DinCom_Id_Usuario_Actualizacion',
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
						'name'=>'DinCom[Estado]',
						'id'=>'DinCom_Estado',
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
				        'mGridId' => 'din-com-grid', //Gridview id
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
		$('#DinCom_Id_Dic_Com').val('').trigger('change');
		$('#DinCom_Pais').val('').trigger('change');
		$('#DinCom_Tipo').val('').trigger('change');
		$('#DinCom_Id_Usuario_Creacion').val('').trigger('change');
		$('#DinCom_Fecha_Creacion').val('');
		$('#DinCom_Id_Usuario_Actualizacion').val('').trigger('change');
		$('#DinCom_Fecha_Actualizacion').val('');
		$('#DinCom_Estado').val('').trigger('change');
		$('#yt0').click();
	}
	
</script>

<?php
/* @var $this CControlCmsController */
/* @var $model CControlCms */
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
	          	<?php echo $form->label($model,'ID_BASE'); ?>
			    <?php echo $form->numberField($model,'ID_BASE', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'MES'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CControlCms[MES]',
						'id'=>'CControlCms_MES',
						'data'=> array(1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE',10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'),
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
	          	<?php echo $form->label($model,'ANIO'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CControlCms[ANIO]',
						'id'=>'CControlCms_ANIO',
						'data'=> array(date("Y") - 3 => date("Y") - 3, date("Y") -2 => date("Y") -2, date("Y") - 1 => date("Y") - 1, date("Y") => date("Y")),
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
	          	<?php echo $form->label($model,'TIPO'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CControlCms[TIPO]',
						'id'=>'CControlCms_TIPO',
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
	          	<?php echo $form->label($model,'LIQUIDACION'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CControlCms[LIQUIDACION]',
						'id'=>'CControlCms_LIQUIDACION',
						'data'=>array(1 => 'INDIVIDUAL', 2 => 'TODOS LOS VENDEDORES'),
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
	            <?php echo $form->error($model,'VENDEDOR', array('class' => 'pull-right badge bg-red')); ?>
	            <?php echo $form->label($model,'VENDEDOR'); ?>
	            <?php echo $form->textField($model,'VENDEDOR'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2', array(
	                    'selector' => '#CControlCms_VENDEDOR',
	                    'options'  => array(
	                        'allowClear' => true,
	                        'minimumInputLength' => 5,
	                        'width' => '100%',
	                        'language' => 'es',
	                        'ajax' => array(
	                            'url' => Yii::app()->createUrl('comision/SearchVend'),
	                            'dataType'=>'json',
	                            'data'=>'js:function(term){return{q: term, tipo: $("#Comision_tipo").val()};}',
	                            'results'=>'js:function(data){ return {results:data};}'                   
	                        ),
	                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("CControlCms_VENDEDOR"); return "No se encontraron resultados"; }',
	                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'CControlCms_VENDEDOR\')\">Limpiar campo</button>"; }',
	                    ),
	                ));
	            ?>
	        </div>
	    </div>
	    <div class="col-sm-6">
	      <div class="form-group">
	        <?php echo $form->label($model,'OBSERVACION'); ?>
	        <?php echo $form->textField($model,'OBSERVACION', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	      </div>
	    </div>


	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'ID_USUARIO_CREACION'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'CControlCms[ID_USUARIO_CREACION]',
						'id'=>'CControlCms_ID_USUARIO_CREACION',
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
						'name'=>'CControlCms[ID_USUARIO_ACTUALIZACION]',
						'id'=>'CControlCms_ID_USUARIO_ACTUALIZACION',
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
						'name'=>'CControlCms[ESTADO]',
						'id'=>'CControlCms_ESTADO',
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
				        'mGridId' => 'ccontrol-cms-grid', //Gridview id
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
		$('#CControlCms_ID_BASE').val('');
		$('#CControlCms_MES').val('').trigger('change');
		$('#CControlCms_ANIO').val('').trigger('change');
		$('#CControlCms_TIPO').val('').trigger('change');
		$('#CControlCms_LIQUIDACION').val('').trigger('change');
		$('#CControlCms_VENDEDOR').val('').trigger('change');
	    $('#s2id_CControlCms_VENDEDOR span').html("");
	    $('#CControlCms_OBSERVACION').val('');
		$('#CControlCms_ID_USUARIO_CREACION').val('').trigger('change');
		$('#CControlCms_FECHA_CREACION').val('');
		$('#CControlCms_ID_USUARIO_ACTUALIZACION').val('').trigger('change');
		$('#CControlCms_FECHA_ACTUALIZACION').val('');
		$('#CControlCms_ESTADO').val('').trigger('change');
		$('#ccontrol-cms-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>


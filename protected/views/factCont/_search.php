<?php
/* @var $this FactPendController */
/* @var $model FactPend */
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
	          	<?php echo $form->label($model,'Id_Fact'); ?>
			    <?php echo $form->numberField($model,'Id_Fact', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Empresa'); ?>
	          	<?php $lista_empresas = array(1 => "COMSTAR", 2 => "PANSELL", 3 => "SIMONIZ", 4 => "TITAN") ?>
			    <?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FactCont[Empresa]',
						'id'=>'FactCont_Empresa',
						'data'=>$lista_empresas,
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
	            <?php echo $form->label($model,'Area'); ?>
	            <?php
	              $this->widget('ext.select2.ESelect2',array(
	                'name'=>'FactCont[Area]',
	                'id'=>'FactCont_Area',
	                'data'=>$lista_areas,
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
	          	<?php echo $form->label($model,'Num_Factura'); ?>
			    <?php echo $form->textField($model,'Num_Factura', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Factura'); ?>
			    <?php echo $form->textField($model,'Fecha_Factura', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha_Radicado'); ?>
			    <?php echo $form->textField($model,'Fecha_Radicado', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	        <div class="form-group">
	          <?php echo $form->error($model,'periodo_radicado', array('class' => 'badge badge-warning float-right')); ?>
	          <?php echo $form->label($model,'periodo_radicado'); ?>
	          <?php echo $form->textField($model,'periodo_radicado', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-6">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Proveedor'); ?>
			    <?php echo $form->textField($model,'Proveedor'); ?>
			    <?php
			    $this->widget('ext.select2.ESelect2', array(
			        'selector' => '#FactCont_Proveedor',

			        'options'  => array(
			        	'allowClear' => true,
			        	'minimumInputLength' => 3,
		               	'width' => '100%',
		               	'language' => 'es',
		                'ajax' => array(
	                        'url' => Yii::app()->createUrl('proveedorCont/SearchProveedor'),
			               	'dataType'=>'json',
	                    	'data'=>'js:function(term){return{q: term};}',
	                    	'results'=>'js:function(data){ return {results:data};}'
					                       
			            ),
		            	'formatNoMatches'=> 'js:function(){ clear_select2_ajax("FactCont_Proveedor"); return "No se encontraron resultados"; }',
		            	'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'FactCont_Proveedor\')\">Limpiar campo</button>"; }',
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
						'name'=>'FactCont[Id_Usuario_Creacion]',
						'id'=>'FactCont_Id_Usuario_Creacion',
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
						'name'=>'FactCont[Id_Usuario_Actualizacion]',
						'id'=>'FactCont_Id_Usuario_Actualizacion',
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
	          	<?php echo $form->label($model,'Id_Usuario_Revision'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FactCont[Id_Usuario_Revision]',
						'id'=>'FactCont_Id_Usuario_Revision',
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
	          	<?php echo $form->label($model,'Fecha_Revision'); ?>
			    <?php echo $form->textField($model,'Fecha_Revision', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Estado'); ?>
			    <?php $estados = array(0 => 'ANULADA', 1 => 'CARGADA', 2 => 'RECIBIDA') ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FactCont[Estado]',
						'id'=>'FactCont_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Empresa ASC', 4 => 'Empresa DESC', 5 => 'Área ASC', 6 => 'Área DESC', 7 => '# de factura ASC', 8 => '# de factura DESC', 9 => 'Fecha de factura ASC', 10 => 'Fecha de factura DESC', 11 => 'Fecha de radicado ASC', 12 => 'Fecha de radicado DESC', 13 => 'Proveedor ASC', 14 => 'Proveedor DESC', 15 => 'Usuario que creo ASC', 16 => 'Usuario que creo DESC', 17 => 'Fecha de creación ASC', 18 => 'Fecha de creación DESC', 19 => 'Ultimo usuario que actualizó ASC', 20 => 'Ultimo usuario que actualizó DESC', 21 => 'Fecha de actualización ASC', 22 => 'Fecha de actualización DESC', 23 => 'Usuario que revisó ASC', 24 => 'Usuario que revisó DESC', 25 => 'Fecha de revisión ASC', 26 => 'Fecha de revisión DESC', 27 => 'Estado ASC', 28 => 'Estado DESC'
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'FactCont[orderby]',
						'id'=>'FactCont_orderby',
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
	</div>
	<div class="row">
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php 
					$this->widget('application.extensions.PageSize.PageSize', array(
				        'mGridId' => 'fact-cont-grid', //Gridview id
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
      format : 'yyyy-mm',
      titleFormat: "MM yyyy",
  };

  $("#FactCont_periodo_radicado").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
      format: "yyyy-mm",
      startView: "year", 
      minViewMode: "months",
  });

});

function resetfields(){
	$('#FactCont_Id_Fact').val('');
	$('#FactCont_Empresa').val('').trigger('change');
	$('#FactCont_Area').val('').trigger('change');
	$('#FactCont_Num_Factura').val('');
	$('#FactCont_Fecha_Factura').val('');
	$('#FactCont_Fecha_Radicado').val('');
	$('#FactCont_periodo_radicado').val('');
	$('#FactCont_Proveedor').val('').trigger('change');
	$('#s2id_FactCont_Proveedor span').html("");
	$('#FactCont_Id_Usuario_Creacion').val('').trigger('change');
	$('#FactCont_Fecha_Creacion').val('');
	$('#FactCont_Id_Usuario_Actualizacion').val('').trigger('change');
	$('#FactCont_Fecha_Actualizacion').val('');
	$('#FactCont_Id_Usuario_Revision').val('').trigger('change');
	$('#FactCont_Fecha_Revision').val('');
	$('#FactCont_Estado').val('').trigger('change');
	$('#FactCont_orderby').val('').trigger('change');
	$('#fact-cont-grid').yiiGridView('update', {
		data: $('.search-form form').serialize()
	});
	return false;
}

</script>

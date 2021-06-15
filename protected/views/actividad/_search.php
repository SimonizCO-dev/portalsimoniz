<?php
/* @var $this ActividadController */
/* @var $model Actividad */
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
	          	<?php echo $form->label($model,'Id'); ?>
			    <?php echo $form->numberField($model,'Id', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Fecha'); ?>
			    <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'autocomplete' => 'off', 'readonly' => true)); ?>
	        </div>
	    </div>
	    <div class="col-sm-3">
	      <div class="form-group">
	          <?php echo $form->label($model,'Pais'); ?>
	          <?php echo $form->error($model,'Pais', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                  'name'=>'Actividad[Pais]',
	                  'id'=>'Actividad_Pais',
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
	   	<div class="col-sm-3">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'Actividad'); ?>
			    <?php echo $form->textField($model,'Actividad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-3">
	        <div class="form-group">
	            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
	            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Actividad[Id_Grupo]',
	                    'id'=>'Actividad_Id_Grupo',
	                    'data'=>$lista_grupos,
	                    'value' => $model->Id_Grupo,
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
	    <div class="col-sm-9" id="div_tipo" style="display: none;">
	        <div class="form-group">
	          <?php echo $form->label($model,'Id_Tipo', array('class' => 'control-label')); ?>
	          <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                'name'=>'Actividad[Id_Tipo]',
	                'id'=>'Actividad_Id_Tipo',
	                'value' => $model->Id_Tipo,
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
	    <div class="col-sm-9" id="div_usuario" style="display: none;">
	    	<div class="form-group">
	          	<?php echo $form->label($model,'user_enc'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Actividad[user_enc]',
						'id'=>'Actividad_user_enc',
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
	            <?php echo $form->label($model,'Prioridad', array('class' => 'control-label')); ?>
	            <?php $prioridades = array(1 => 'ALTA', 2 => 'MEDIA', 3 => 'BAJA'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Actividad[Prioridad]',
	                    'id'=>'Actividad_Prioridad',
	                    'data'=>$prioridades,
	                    'value' => $model->Prioridad,
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
	          	<?php echo $form->label($model,'Estado'); ?>
			    <?php $estados = array(0 => 'PENDIENTES', 1 => 'RECIBIDO', 2 => 'COMPLETADO', 3 => 'EN ESPERA', 4 => 'EVALUADO', 5 => 'ANULADA', 6 => 'RECHAZADO', 7 => 'EN PROCESO / APROBADO'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Actividad[Estado]',
						'id'=>'Actividad_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Fecha ASC', 4 => 'Fecha DESC', 5 => 'Prioridad ASC', 6 => 'Prioridad DESC'
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Actividad[orderby]',
						'id'=>'Actividad_orderby',
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
				        'mGridId' => 'actividad-grid', //Gridview id
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
		$("#Actividad_Id_Grupo").change(function () {
	      vlr = $("#Actividad_Id_Grupo").val();
	      if(vlr != ""){
	        var data = {grupo: vlr, clasificacion: 0}
	        $.ajax({ 
	          type: "POST", 
	          url: "<?php echo Yii::app()->createUrl('actividad/gettipos'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	          	$("#Actividad_Id_Tipo").val('').trigger('change');
	            $("#Actividad_Id_Tipo").html('');
	            $("#Actividad_Id_Tipo").append('<option value=""></option>');
	            $.each(data, function(i,item){
	                $("#Actividad_Id_Tipo").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#div_tipo").show();
	          }
	        });
	      }else{
	        $("#Actividad_Id_Tipo").val('').trigger('change');
	        $("#div_tipo").hide();
	      }
	    });

	    $("#Actividad_Id_Tipo").change(function () {
	      vlr = $("#Actividad_Id_Tipo").val();
	      if(vlr != ""){
	        var data = {tipo: vlr}
	        $.ajax({ 
	          type: "POST", 
	          url: "<?php echo Yii::app()->createUrl('actividad/getusuarios'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	          	$("#Actividad_user_enc").val('').trigger('change');
	            $("#Actividad_user_enc").html('');
	            $("#Actividad_user_enc").append('<option value=""></option>');
	            $.each(data, function(i,item){
	                $("#Actividad_user_enc").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#div_usuario").show();
	          }
	        });
	      }else{
	        $("#Actividad_user_enc").val('').trigger('change');
	        $("#div_usuario").hide();
	      }
	    });
    });

	function resetfields(){
		$('#Actividad_Id').val('');
		$('#Actividad_Fecha').val('');
		$('#Actividad_Pais').val('').trigger('change');
		$('#Actividad_Actividad').val('');
		$('#Actividad_Id_Grupo').val('').trigger('change');
		$('#Actividad_user_enc').val('').trigger('change');
		$('#Actividad_Id_Usuario_Creacion').val('').trigger('change');
		$('#Actividad_Fecha_Creacion').val('');
		$('#Actividad_Estado').val('').trigger('change');
		$('#Actividad_orderby').val('').trigger('change');
		$('#actividad-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>
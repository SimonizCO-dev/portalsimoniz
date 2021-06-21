<?php
/* @var $this TicketController */
/* @var $model Ticket */
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
	          	<?php echo $form->label($model,'Id_Ticket'); ?>
			    <?php echo $form->numberField($model,'Id_Ticket', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
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
	          	<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Ticket[Id_Usuario_Creacion]',
						'id'=>'Ticket_Id_Usuario_Creacion',
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
	            <?php echo $form->label($model,'Id_Tipo', array('class' => 'control-label')); ?>
	            <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Ticket[Id_Tipo]',
	                    'id'=>'Ticket_Id_Tipo',
	                    'data'=>array(1 => 'INCIDENCIA', 2 => 'REQUERIMIENTO'),
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
	            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
	            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Ticket[Id_Grupo]',
	                    'id'=>'Ticket_Id_Grupo',
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
	</div>
	<div class="row">
	    <div class="col-sm-6" id="div_novedad" style="display: none;">
	        <div class="form-group">
	          <?php echo $form->label($model,'Id_Novedad', array('class' => 'control-label')); ?>
	          <?php echo $form->error($model,'Id_Novedad', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                'name'=>'Ticket[Id_Novedad]',
	                'id'=>'Ticket_Id_Novedad',
	                'value' => $model->Id_Novedad,
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
	    <div class="col-sm-6" id="div_novedad_det" style="display: none;">
	        <div class="form-group">
	          <?php echo $form->label($model,'Id_Novedad_Det', array('class' => 'control-label')); ?>
	          <?php echo $form->error($model,'Id_Novedad_Det', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                'name'=>'Ticket[Id_Novedad_Det]',
	                'id'=>'Ticket_Id_Novedad_Det',
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
	</div>
	<div class="row">
	    <div class="col-sm-6" id="div_usuarios_nov" style="display: none;">
	        <div class="form-group">
	          <?php echo $form->label($model,'Id_Usuario_Asig', array('class' => 'control-label')); ?>
	          <?php echo $form->error($model,'Id_Usuario_Asig', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                'name'=>'Ticket[Id_Usuario_Asig]',
	                'id'=>'Ticket_Id_Usuario_Asig',
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
	</div>
	<div class="row">
		<div class="col-sm-3">
		    <div class="form-group">
	            <?php echo $form->label($model,'Prioridad', array('class' => 'control-label')); ?>
	            <?php $prioridades = array(1 => 'ALTA', 2 => 'MEDIA', 3 => 'BAJA'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'Ticket[Prioridad]',
	                    'id'=>'Ticket_Prioridad',
	                    'data'=>$prioridades,
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
			    <?php $estados = array(1 => 'SIN ASIGNAR', 2 => 'ASIGNADO', 3 => 'EN PROCESO', 4 => 'CERRADO', 5 => 'FINALIZADO / CALIFICADO'); ?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Ticket[Estado]',
						'id'=>'Ticket_Estado',
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
                	$array_orden = array(1 => 'ID ASC', 2 => 'ID DESC', 3 => 'Fecha de creación ASC', 4 => 'Fecha de creación DESC', 5 => 'Prioridad ASC', 6 => 'Prioridad DESC'
					);
            	?>
            	<?php
            		$this->widget('ext.select2.ESelect2',array(
						'name'=>'Ticket[orderby]',
						'id'=>'Ticket_orderby',
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
				        'mGridId' => 'ticket-grid', //Gridview id
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
		$("#Ticket_Id_Grupo").change(function () {
	      vlr = $("#Ticket_Id_Grupo").val();
	      if(vlr != ""){

	        var data = {grupo: vlr}
	        $.ajax({ 
	          type: "POST", 
	          url: "<?php echo Yii::app()->createUrl('ticket/getnovedadesxuser'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	            $("#Ticket_Id_Novedad").html('');
	            $.each(data, function(i,item){
	                $("#Ticket_Id_Novedad").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#Ticket_Id_Novedad").val('').trigger('change');
	            $("#div_novedad").show();
	          }
	        });
	      }else{
	        $("#Ticket_Id_Novedad").val('').trigger('change');
	        $("#div_novedad").hide();
	        $("#Ticket_Id_Novedad_Det").val('').trigger('change');
	        $("#div_novedad_det").hide();
	      }
	    });

	    $("#Ticket_Id_Novedad").change(function () {
	      vlr = $("#Ticket_Id_Novedad").val();
	      if(vlr != ""){
	        var data = {novedad: vlr}
	        $.ajax({ 
	          type: "POST", 
	          url: "<?php echo Yii::app()->createUrl('ticket/getnovedadesdetxuser'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	            $("#Ticket_Id_Novedad_Det").html('');
	            $.each(data, function(i,item){
	                $("#Ticket_Id_Novedad_Det").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#Ticket_Id_Novedad_Det").val('').trigger('change');
	            $("#div_novedad_det").show();
	          }
	        });
	        var data = {novedades: vlr, det_novedades: new Array("")}
	        $.ajax({ 
	          type: "POST", 
	          url: "<?php echo Yii::app()->createUrl('ticket/getusuariosxnovedad'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	            $("#Ticket_Id_Usuario_Asig").html('');
	            $.each(data, function(i,item){
	                $("#Ticket_Id_Usuario_Asig").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#Ticket_Id_Usuario_Asig").val('').trigger('change');
	            $("#div_usuarios_nov").show();
	          }
	        });
	      }else{
	      	$("#Ticket_Id_Novedad_Det").val('').trigger('change');
	        $("#div_novedad_det").hide();
	        $("#Ticket_Id_Usuario_Asig").val('').trigger('change');
	        $("#div_usuarios_nov").hide();
	      }
	    });

	    $("#Ticket_Id_Novedad_Det").change(function () {
	      vlr = $("#Ticket_Id_Novedad").val();
	      vlr_det = $("#Ticket_Id_Novedad").val();
	      if(vlr_det != ""){
	        var data = {novedades: vlr, det_novedades: vlr_det}
	        $.ajax({ 
	          type: "POST", 
	          url: "<?php echo Yii::app()->createUrl('ticket/getusuariosxnovedad'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	            $("#Ticket_Id_Usuario_Asig").html('');
	            $.each(data, function(i,item){
	                $("#Ticket_Id_Usuario_Asig").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#Ticket_Id_Usuario_Asig").val('').trigger('change');
	            $("#div_usuarios_nov").show();
	          }
	        });
	      }else{
	        $("#Ticket_Id_Usuario_Asig").val('').trigger('change');
	        $("#div_usuarios_nov").hide();
	      }
	    });
    });

	function resetfields(){
		$('#Ticket_Id_Ticket').val('');
		$('#Ticket_Fecha_Creacion').val('');
		$('#Actividad_Id_Usuario_Creacion').val('').trigger('change');
		$('#Ticket_Id_Tipo').val('').trigger('change');
		$('#Ticket_Id_Grupo').val('').trigger('change');
		$('#Ticket_Prioridad').val('').trigger('change');
		$('#Ticket_Estado').val('').trigger('change');
		$('#Ticket_orderby').val('').trigger('change');
		$('#ticket-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

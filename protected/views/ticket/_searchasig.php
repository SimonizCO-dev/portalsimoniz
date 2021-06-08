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
	    <div class="col-sm-9" id="div_tipo" style="display: none;">
	        <div class="form-group">
	          <?php echo $form->label($model,'Id_Tipo', array('class' => 'control-label')); ?>
	          <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
	          <?php
	              $this->widget('ext.select2.ESelect2',array(
	                'name'=>'Ticket[Id_Tipo]',
	                'id'=>'Ticket_Id_Tipo',
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
	          url: "<?php echo Yii::app()->createUrl('ticket/gettiposxuser'); ?>",
	          data: data,
	          dataType: 'json',
	          success: function(data){ 
	            $("#Ticket_Id_Tipo").html('');
	            $("#Ticket_Id_Tipo").append('<option value=""></option>');
	            $.each(data, function(i,item){
	                $("#Ticket_Id_Tipo").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	            });
	            $("#div_tipo").show();
	          }
	        });
	      }else{
	        $("#Ticket_Id_Tipo").val('');
	        $("#div_tipo").hide();
	      }
	    });
    });

	function resetfields(){
		$('#Ticket_Id_Ticket').val('');
		$('#Ticket_Id_Grupo').val('').trigger('change');
		$('#Ticket_Fecha_Creacion').val('');


		/*$('#Actividad_Fecha').val('');
		$('#Actividad_Pais').val('').trigger('change');
		$('#Actividad_Actividad').val('');
		$('#Actividad_Id_Grupo').val('').trigger('change');
		$('#Actividad_user_enc').val('').trigger('change');
		$('#Actividad_Id_Usuario_Creacion').val('').trigger('change');
		$('#Actividad_Fecha_Creacion').val('');
		$('#Actividad_Estado').val('').trigger('change');
		$('#Actividad_orderby').val('').trigger('change');*/
		$('#ticket-grid').yiiGridView('update', {
			data: $('.search-form form').serialize()
		});
		return false;
	}
	
</script>

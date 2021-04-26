<?php
/* @var $this ElementoEmpleadoController */
/* @var $model ElementoEmpleado */

?>

<h4>Asignación de elementos / herramientas por empleado</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'elemento-empleado-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


<div class="row">
    <div class="col-sm-8">
    	<div class="form-group">
          	<?php echo $form->label($model,'Id_Empleado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Empleado', array('class' => 'badge badge-warning float-right')); ?>
          	<?php echo $form->hiddenField($model,'Id_Contrato', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_contrato)); ?>
		    <?php echo $form->hiddenField($model,'Id_Empleado', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $e)); ?>
		    <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($e).'</p>'; ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
          	<?php echo $form->label($model,'unidad_gerencia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'unidad_gerencia', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->hiddenField($model,'unidad_gerencia', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_unidad_gerencia)); ?>
		    <?php echo '<p>'.$unidad_gerencia.'</p>'; ?> 
        </div>
    </div>   
</div>
<div class="row">
	<div class="col-sm-4">
        <div class="form-group">
          	<?php echo $form->label($model,'area', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'area', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->hiddenField($model,'area', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_area)); ?>
		    <?php echo '<p>'.$area.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
          	<?php echo $form->label($model,'subarea', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'subarea', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->hiddenField($model,'subarea', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_subarea)); ?>
		    <?php echo '<p>'.$subarea.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
          	<?php echo $form->label($model,'cargo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'cargo', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->hiddenField($model,'cargo', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_cargo)); ?>
		    <?php echo '<p>'.$cargo.'</p>'; ?> 
        </div>
    </div>   
</div>
<div class="row">
	<div class="col-sm-12">
        <div class="form-group">
        	<label>Elementos sugeridos</label>         
            <div id="op2">
			    <div>
			        <ul id="tree2" style="display: none;">
			        </ul>
			    </div>
			</div>            
        </div>
    </div> 
    <div class="col-sm-12">
        <div class="form-group">
            <label>Elementos por área / subárea</label>
            <?php echo $form->hiddenField($model,'elementos', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
            <?php echo $form->hiddenField($model,'cant_ele', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
            <?php echo $form->hiddenField($model,'herramientas', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
            <?php echo $form->hiddenField($model,'opc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
            <div id="op1">
			    <div>
			        <ul id="tree1" style="display: none;">
			        </ul>
			    </div>
			</div>            
        </div>
    </div> 
</div>
<div class="row">
	<div class="col-sm-12">
        <div class="form-group">
        	<label>Herramientas</label>         
            <div id="op3">
			    <div>
			        <ul id="tree3" style="display: none;">
			        </ul>
			    </div>
			</div>            
        </div>
    </div> 
</div>

<div class="row" id="error_elem" style="display: none;">
	<div class="col-sm-12">
		<div class="badge badge-warning float-right" style="">Debe asociar por lo menos 1 elemento / herramienta al empleado.</div>
	</div>
</div>

<div class="row mb-2" id="buttons">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/asignacion' ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="submit" class="btn btn-primary btn-sm" id="btn_submit1" onclick="return valida_desasignacion(event);"  style="display: none;"><i class="fas fa-save"></i> Cancelar asignación de item(s) seleccionado(s)</button>
        <button type="submit" class="btn btn-primary btn-sm" id="btn_submit2" onclick="return valida_asignacion(event);" style="display: none;"><i class="fas fa-save"></i> Asignar - modificar item(s) seleccionado(s)</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

	$('.ajax-loader').fadeIn('fast');

	//funcion para cargar el tree de sugeridos
	var data = {id_contrato: <?php echo $id_contrato; ?>, id_area: <?php echo $id_area; ?>, id_subarea: <?php echo $id_subarea; ?>, id_cargo: <?php echo $id_cargo; ?>}
	$.ajax({ 
		type: "POST", 
		url: "<?php echo Yii::app()->createUrl('elemento/getelementossugerido'); ?>",
		data: data,
		dataType: 'json',
		success: function(data){
			if (data.length > 0) {
			    $.each(data, function(indice0) {
			    	
		    		id0 = data[indice0]['id'];
		    		text0 = data[indice0]['text'];
		    		children0 = data[indice0]['children'];
		    		check0 = data[indice0]['check'];

		    		if (check0 == 1) { checked0 = 'checked'; } else { checked0 = ''; }
		    		$("#tree2").append('<li id="li_s_'+id0+'"><input type="checkbox" '+checked0+' value="'+id0+'"><span> '+text0+'</span></li>');
		    		if (children0.length > 0) {
		    			//nivel 2
		    			$("#li_s_"+id0+"").append('<ul id="ul_s_'+id0+'"></ul>');
		    			$.each(children0, function(indice1) {
		    				id1 = children0[indice1]['id'];
				    		text1 = children0[indice1]['text'];
				    		text1_ent = children0[indice1]['text_ent'];
				    		cantidad1 = children0[indice1]['cantidad'];
				    		check1 = children0[indice1]['check'];

		    				if (check1 == 1) { checked1 = 'checked'; } else { checked1 = ''; }

		    				if(text1_ent != ""){
				    			$("#ul_s_"+id0+"").append('<li id="li_s_'+id1+'"><span> '+text1+'</span>   <small class="badge badge-warning">'+text1_ent+'</small></li>');
				    		}else{
				    			$("#ul_s_"+id0+"").append('<li id="li_s_'+id1+'"><input type="checkbox" '+checked1+' value="'+id1+'" class="ele"> <input id="ie_'+id1+'" class="input_tree" type="number" type="number" min="1" max="10" value="'+cantidad1+'"><span> '+text1+'</span></li>');
				    		}
		    				
				    				
		    			});
					}
			    });
			
				//se inicializa el tree
				$('#op2 div').tree({
			        collapseUiIcon: 'ui-icon-plus',
			        expandUiIcon: 'ui-icon-minus',
			        leafUiIcon: 'ui-icon-bullet',
			    });
				//se colapsa el tree
				$(".ui-icon-minus").trigger("click");
				//se muestra el tree
				$("#tree2").fadeIn();

			}else{
				$('#op2 div').html('<p>No hay sugeridos.</p>');	
			}
		}
	});

	//funcion para cargar el tree de todos los elementos
	var data = {id_contrato: <?php echo $id_contrato; ?>, id_area: <?php echo $id_area; ?>, id_subarea: <?php echo $id_subarea; ?>, id_cargo: <?php echo $id_cargo; ?>}
	$.ajax({ 
		type: "POST", 
		url: "<?php echo Yii::app()->createUrl('elemento/getelementos'); ?>",
		data: data,
		dataType: 'json',
		success: function(data){
			if (data.length > 0) {
			    $.each(data, function(indice0) {
			    	//nivel 1
		    		id0 = data[indice0]['id'];
		    		text0 = data[indice0]['text'];
		    		children0 = data[indice0]['children'];
		    		check0 = data[indice0]['check'];
		    		
		    		if (check0 == 1) { checked0 = 'checked'; } else { checked0 = ''; }
		    		
		    		$("#tree1").append('<li id="li_'+id0+'"><input type="checkbox" '+checked0+' value="'+id0+'"><span> '+text0+'</span></li>');
		    		
		    		if (children0.length > 0) {
		    			//nivel 2
		    			$("#li_"+id0+"").append('<ul id="ul_'+id0+'"></ul>');
		    			$.each(children0, function(indice1) {
		    				id1 = children0[indice1]['id'];
				    		text1 = children0[indice1]['text'];
				    		children1 = children0[indice1]['children'];
				    		check1 = children0[indice1]['check'];
		    				
		    				if (check1 == 1) { checked1 = 'checked'; } else { checked1 = ''; }

				    		$("#ul_"+id0+"").append('<li id="li_'+id1+'"><input type="checkbox" '+checked1+' value="'+id1+'"><span> '+text1+'</span></li>');
				    		
				    		if (children1.length > 0) {
				    			//nivel 3
				    			$("#li_"+id1+"").append('<ul id="ul_'+id1+'"></ul>');
				    			$.each(children1, function(indice2) {
				    				id2 = children1[indice2]['id'];
						    		text2 = children1[indice2]['text'];
						    		text2_ent = children1[indice2]['text_ent'];
						    		cantidad2 = children1[indice2]['cantidad'];
						    		check2 = children1[indice2]['check'];

		    						if (check2 == 1) { checked2 = 'checked'; } else { checked2 = ''; }

		    						if(text2_ent != ""){
				    					$("#ul_"+id1+"").append('<li id="li_'+id2+'"><span> '+text2+'</span>   <small class="badge badge-warning">'+text2_ent+'</small></li>');
						    		}else{
						    			$("#ul_"+id1+"").append('<li id="li_'+id2+'"><input type="checkbox" '+checked2+' value="'+id2+'" class="ele"> <input id="ie_'+id2+'" class="input_tree" type="number" min="1" max="10" value="'+cantidad2+'"><span> '+text2+'</span></li>');	
						    		}
				    			});	
							}	
		    			});
					}
			    });
			
				//se inicializa el tree
				$('#op1 div').tree({
			        collapseUiIcon: 'ui-icon-plus',
			        expandUiIcon: 'ui-icon-minus',
			        leafUiIcon: 'ui-icon-bullet',
			    });
				//se colapsa el tree
				$(".ui-icon-minus").trigger("click");
				//se muestra el tree
				$("#tree1").fadeIn();

			}else{
				$('#op1 div').html('<p>No hay elementos pend. por entrega y/o este usuario no puede asignar / desasignar elementos con área - subárea diferentes a las que tiene configuradas.</p>');		
			}

			$('.ajax-loader').fadeOut('fast');
		}
	});

	

	//funcion para cargar el tree de herramientas
	var data = {id_contrato: <?php echo $id_contrato; ?>}
	$.ajax({ 
		type: "POST", 
		url: "<?php echo Yii::app()->createUrl('herramienta/getherramientas'); ?>",
		data: data,
		dataType: 'json',
		success: function(data){
			if (data.length > 0) {
			    $.each(data, function(indice0) {
			    	
		    		id0 = data[indice0]['id'];
		    		text0 = data[indice0]['text'];
		    		children0 = data[indice0]['children'];
		    		check0 = data[indice0]['check'];

		    		if (check0 == 1) { checked0 = 'checked'; } else { checked0 = ''; }
		    		$("#tree3").append('<li id="li_h_'+id0+'"><input type="checkbox" '+checked0+' value="'+id0+'"><span> '+text0+'</span></li>');
		    		if (children0.length > 0) {
		    			//nivel 2
		    			$("#li_h_"+id0+"").append('<ul id="ul_h_'+id0+'"></ul>');
		    			$.each(children0, function(indice1) {
		    				id1 = children0[indice1]['id'];
				    		text1 = children0[indice1]['text'];
				    		text_ent = children0[indice1]['text_ent'];
				    		check1 = children0[indice1]['check'];

		    				if (check1 == 1) { checked1 = 'checked'; } else { checked1 = ''; }

		    				if(text_ent != ""){
		    					$("#ul_h_"+id0+"").append('<li id="li_h_'+id1+'"><span> '+text1+'</span>   <small class="badge badge-warning">'+text_ent+'</small></li>');
				    		}else{
				    			$("#ul_h_"+id0+"").append('<li id="li_h_'+id1+'"><input type="checkbox" '+checked1+' value="'+id1+'" class="her"><span> '+text1+'</span></li>');	
				    		}


				    				
		    			});
					}
			    });
			
				//se inicializa el tree
				$('#op3 div').tree({
			        collapseUiIcon: 'ui-icon-plus',
			        expandUiIcon: 'ui-icon-minus',
			        leafUiIcon: 'ui-icon-bullet',
			    });
				//se colapsa el tree
				$(".ui-icon-minus").trigger("click");
				//se muestra el tree
				$("#tree3").fadeIn();

			}else{
				$('#op3 div').html('<p>No hay herramientas pend. por entrega.</p>');	
			}
		}
	});

	$('#op1').click(function(e) {  
    	$('#error_elem').hide();
    });

    $('#op2').click(function(e) {  
    	$('#error_elem').hide();
    });

    $('#op3').click(function(e) {  
    	$('#error_elem').hide();
    });

});

function valida_desasignacion(){
	
	//elementos
	var checkbox_selected_e = '';

	$('#ElementoEmpleado_opc').val(0);
	
    $('input.ele[type=checkbox]').each(function(){
        if (this.checked) {
        	id_area_elem = $(this).val();
            checkbox_selected_e += id_area_elem+','; 
        }
    });

    var cadena_c_e = checkbox_selected_e.slice(0,-1);
    
    $('#ElementoEmpleado_elementos').val(cadena_c_e);

    //herramientas
	var checkbox_selected_h = '';
	
    $('input.her[type=checkbox]').each(function(){
        if (this.checked) {

        	id_herramienta = $(this).val();
            checkbox_selected_h += id_herramienta+',';
            
        }
    });

    
    var cadena_c_h = checkbox_selected_h.slice(0,-1);

    $('#ElementoEmpleado_herramientas').val(cadena_c_h);

    ele = $('#ElementoEmpleado_elementos').val();

    her = $('#ElementoEmpleado_herramientas').val();

	if(ele != "" || her != ""){
		$('#buttons').hide();
		loadershow();
		return true;
	}else{
		$('#error_elem').show();
		return false; 	
	}     
}

function valida_asignacion(){
	
	//elementos
	var checkbox_selected_e = '';
	var cant_selected_e = '';

 	$('#ElementoEmpleado_opc').val(1);
	
    $('input.ele[type=checkbox]').each(function(){
        if (this.checked) {

        	id_area_elem = $(this).val();
        	cant = $('#ie_'+id_area_elem).val();

            checkbox_selected_e += id_area_elem+',';
            cant_selected_e += cant+',';
            
        }
    });

    var cadena_c_e = checkbox_selected_e.slice(0,-1);
    var cadena_i_e = cant_selected_e.slice(0,-1);
    
    $('#ElementoEmpleado_elementos').val(cadena_c_e);
    $('#ElementoEmpleado_cant_ele').val(cadena_i_e);

    //herramientas
	var checkbox_selected_h = '';
	
    $('input.her[type=checkbox]').each(function(){
        if (this.checked) {

        	id_herramienta = $(this).val();
            checkbox_selected_h += id_herramienta+',';
            
        }
    });

    
    var cadena_c_h = checkbox_selected_h.slice(0,-1);

    $('#ElementoEmpleado_herramientas').val(cadena_c_h);

    ele = $('#ElementoEmpleado_elementos').val();

    her = $('#ElementoEmpleado_herramientas').val();

	if(ele != "" || her != ""){
		$('#buttons').hide();
		loadershow();
		return true;
	}else{
		$('#error_elem').show();
		return false; 	
	}
}

$(document).ajaxComplete(function() {
	var data_tree1 = $("#tree1 li").length; 
    var data_tree2 = $("#tree2 li").length; 
    var data_tree3 = $("#tree3 li").length;

    if(data_tree1 == 0 && data_tree2 == 0 && data_tree3 == 0){
        $("#btn_submit1").hide();
        $("#btn_submit2").hide();
    }else{
    	$("#btn_submit1").show();
        $("#btn_submit2").show();
    }
});
   	
</script>

               
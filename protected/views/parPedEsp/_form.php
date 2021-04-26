<?php
/* @var $this ParPedEspController */
/* @var $model ParPedEsp */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'par-ped-esp-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div id="div_mensaje" style="display: none;"></div>

<div class="row">
    <div class="col-sm-4">
	    <div class="form-group">
			<?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Fecha'); ?>
			<?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
			<?php echo $form->hiddenField($model,'cad_item'); ?>
            <?php echo $form->hiddenField($model,'cad_vu'); ?>
            <?php echo $form->hiddenField($model,'cad_cant'); ?>
        	<?php echo $form->hiddenField($model,'cad_iva'); ?>
            <?php echo $form->hiddenField($model,'cad_not'); ?>
            <?php echo $form->hiddenField($model,'desc_sucursal'); ?>
            <?php echo $form->hiddenField($model,'desc_punto_envio'); ?>
	    </div>
  	</div>
  	<div class="col-sm-6">
    	<div class="form-group">
	      <?php echo $form->error($model,'Nit', array('class' => 'badge badge-warning float-right')); ?>
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
  	<div class="col-sm-4" id="div_suc" style="display: none;">
	    <div class="form-group">
			<?php echo $form->error($model,'Sucursal', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Sucursal', array('class' => 'control-label')); ?>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			    'name'=>'ParPedEsp[Sucursal]',
			    'id'=>'ParPedEsp_Sucursal',
			    //'data'=>$lista_tipos,
			    'value' => $model->Sucursal,
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
	<div class="col-sm-4" id="div_pe" style="display: none;">
	    <div class="form-group">
	    	<?php echo $form->error($model,'Punto_Envio', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Punto_Envio', array('class' => 'control-label')); ?>
			
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			    'name'=>'ParPedEsp[Punto_Envio]',
			    'id'=>'ParPedEsp_Punto_Envio',
			    //'data'=>$lista_tipos,
			    'value' => $model->Punto_Envio,
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
	<div class="col-sm-4">
        <div class="form-group">
        	<?php echo $form->error($model,'Porc_Desc', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Porc_Desc'); ?>
            <?php echo $form->numberField($model,'Porc_Desc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '0.01')); ?>
        </div>
    </div>
</div>
<div class="row">
	<div class="col-sm-8">
	    <div class="form-group">
	        <?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
	        <?php echo $form->label($model,'Observaciones'); ?>
	        <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'onkeyup' => 'convert_may(this)', 'maxlength' => '200')); ?>
	    </div>
    </div>
</div>
<div class="row" id="info_item" style="display: none;">
	<div class="col-sm-6">
    	<div class="form-group">
	      <?php echo $form->error($model,'item', array('class' => 'badge badge-warning float-right')); ?>
	      <?php echo $form->label($model,'item'); ?>
	      <?php echo $form->textField($model,'item'); ?>
	      <?php
	      $this->widget('ext.select2.ESelect2', array(
	          'selector' => '#ParPedEsp_item',

	          'options'  => array(
	            'allowClear' => true,
	            'minimumInputLength' => 3,
	                'width' => '100%',
	                'language' => 'es',
	                'ajax' => array(
	                      'url' => Yii::app()->createUrl('ParPedEsp/SearchItem'),
	                  'dataType'=>'json',
	                    'data'=>'js:function(term){return{q: term, nit: $("#ParPedEsp_Nit").val(), suc: $("#ParPedEsp_Sucursal").val(), pe: $("#ParPedEsp_Punto_Envio").val()};}',
	                    'results'=>'js:function(data){ return {results:data};}'
	                             
	              ),
	              'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ParPedEsp_item"); return "No se encontraron resultados"; }',
	              'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'ParPedEsp_item\')\">Limpiar campo</button>"; }',
	          ),

	        ));
	      ?>
    	</div>
  	</div>
  	<div class="col-sm-4">
        <div class="form-group">
        	<?php echo $form->error($model,'cant', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'cant'); ?>
            <?php echo $form->numberField($model,'cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'min' => '1', 'step' => '1')); ?>
        </div>
    </div>	
	<div class="col-sm-8">
	    <div class="form-group">
	        <?php echo $form->error($model,'nota', array('class' => 'badge badge-warning float-right')); ?>
	        <?php echo $form->label($model,'nota'); ?>
	        <?php echo $form->textArea($model,'nota',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'onkeyup' => 'convert_may(this)', 'maxlength' => '70')); ?>
	    </div>
    </div>	
</div>
<div class="row mb-4">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=parpedesp/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="add_item();"><i class="fa fa-plus" ></i> Agregar registro</button>
    </div>
</div>

<div id="contenido"></div>

<div class="row mb-2" id="btn_save" style="display: none;">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="return valida_opciones(event);"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

	$('form').keypress(function(e){   
	  if(e == 13){
	      return false;
	  }
	});

	$('input').keypress(function(e){
	  if(e.which == 13){
	      return false;
	  }
	});

  	//variables para el lenguaje del datepicker
  	$.fn.datepicker.dates['es'] = {
		days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
		months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		today: "Hoy",
		clear: "Limpiar",
		format: "yyyy-mm-dd",
		titleFormat: "MM yyyy",
		weekStart: 1
  	};

  	$("#ParPedEsp_Fecha").datepicker({
  		language: 'es',
  		autoclose: true,
  		orientation: "right bottom",
        startDate: new Date(),
  	});

    $("#ParPedEsp_Nit").change(function() {
  		var nit = $(this).val();
	  	if(nit != ""){
  			var data = {nit: nit}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('ParPedEsp/GetSucCliente'); ?>",
				data: data,
				dataType: 'json',
				success: function(data){
					$('#ParPedEsp_Sucursal').val('').trigger('change');
				   	$("#ParPedEsp_Sucursal").html('');
				  	$("#ParPedEsp_Sucursal").append('<option value=""></option>');
				  	$.each(data, function(i,item){
			      		$("#ParPedEsp_Sucursal").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
				  	});
				  	$("#div_suc").show();
				}
			});
	 	}else{
	 		$('#contenido').html('');
        	$('#btn_save').hide(); 
      		$('#ParPedEsp_Sucursal').val('').trigger('change');
      		$('#ParPedEsp_Punto_Envio').val('').trigger('change');
      		$("#div_suc").hide();    

	 	}

	});

	$("#ParPedEsp_Sucursal").change(function() {
  		var nit = $("#ParPedEsp_Nit").val();
  		var suc = $(this).val();
	  	if(suc != ""){
  			var data = {nit: nit, suc: suc}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('ParPedEsp/GetPuntEnvSucCliente'); ?>",
				data: data,
				dataType: 'json',
				success: function(data){ 
					$('#ParPedEsp_Punto_Envio').val('').trigger('change');
				   	$("#ParPedEsp_Punto_Envio").html('');
				  	$("#ParPedEsp_Punto_Envio").append('<option value=""></option>');
				  	$.each(data, function(i,item){
			      		$("#ParPedEsp_Punto_Envio").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
				  	});
				  	$("#div_pe").show();
				}
			});
	 	}else{
 			$('#contenido').html('');
        	$('#btn_save').hide(); 
      		$('#ParPedEsp_Punto_Envio').val('').trigger('change');
      		$("#div_pe").hide();    
	 	}

	});

	$("#ParPedEsp_Punto_Envio").change(function() {

  		var nit = $("#ParPedEsp_Nit").val();
  		var suc = $("#ParPedEsp_Sucursal").val();
  		var pe = $(this).val();

  		$('#ParPedEsp_item').val('').trigger('change');
    	$('#s2id_ParPedEsp_item span').html("");
    	$("#ParPedEsp_cant").val('');
  		$("#ParPedEsp_nota").val('');

	  	if(pe != ""){
			$("#info_item").show();
	 	}else{
	 		$("#info_item").hide();
	 		$('#contenido').html('');
        	$('#btn_save').hide(); 
      		$("#ParPedEsp_Punto_Envio").val('');   
	 	}

	});

});

function add_item(){

    limp_div_msg();

    var fecha = $('#ParPedEsp_Fecha').val();
    var nit = $('#ParPedEsp_Nit').val();
    var suc = $('#ParPedEsp_Sucursal').val();
    var pe = $('#ParPedEsp_Punto_Envio').val();
    var desc_adic = $('#ParPedEsp_Porc_Desc').val();
    var item = $('#ParPedEsp_item').val();
    var cant = $('#ParPedEsp_cant').val();
    var nota = $('#ParPedEsp_nota').val();

    if(fecha != "" && nit != "" && suc != "" && pe != "" && item != "" && cant != "" && cant > 0 && desc_adic != "" && desc_adic > 0){
        
    	$(".ajax-loader").fadeIn('fast');

        var div_contenido = $('#contenido');

        var tr = $("#tr_"+item).length;

        if(!tr){

            var cant_f = $(".tr_items").length;

            if(cant_f == 0){
                div_contenido.append('<table id="table_items" class="table table-sm table-hover"><thead><tr><th>Código</th><th>Descripción</th><th>Marca</th><th>Oracle</th><th>Vlr. unit.</th><th>Cant.</th><th>Nota(s)</th><th>Subtotal</th><th></th></tr></thead><tbody></tbody></table>');
            }

            var data = {item: item, cant: cant, nit: nit, suc: suc , pe: pe, desc_adic: desc_adic}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('ParPedEsp/infoitem'); ?>",
				data: data,
				dataType: 'json',
				success: function(response){

				  var i = response.codigo;
				  var desc_item = response.desc;
				  var marca = response.marca;
				  var oracle = response.oracle;
				  var vlr_unit = response.vlr_unit;
				  var iva = response.iva;
				  var vlr_subtotal = response.vlr_subtotal;

				  if(nota == ""){
				  	nota = "-";
				  }

				  var tabla = $('#table_items');

				  tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><p id="codigo_'+i+'">'+i+'</p></td><td><p id="desc_'+i+'">'+desc_item+'</p></td><td><p id="marca_'+i+'">'+marca+'</p></td><td><p id="oracle_'+i+'">'+oracle+'</p></td><td align="right"><input type="hidden" id="vu_'+i+'" value="'+vlr_unit+'"><p>'+formatNumber(vlr_unit)+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><input type="hidden" id="nota_'+i+'" value="'+nota+'"><p>'+nota+'</p></td><td align="right"><input type="hidden" id="iva_'+i+'" value="'+iva+'"><p>'+formatNumber(vlr_subtotal)+'</p></td><td align="right"><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fas fa-times-circle"></i></button></td></tr>');

				  $(".ajax-loader").fadeOut('fast');

				}
			});

            $('#btn_save').show();  

        	$('#ParPedEsp_item').val('').trigger('change');
	    	$('#s2id_ParPedEsp_item span').html("");
	    	$("#ParPedEsp_cant").val('');
	  		$("#ParPedEsp_nota").val('');
	  		

        }else{
        	$('html, body').animate({scrollTop:0}, 'fast');
            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-exclamation-triangle"></i>Info</h5>Ya existe este item en el registro actual.');
            $("#div_mensaje").fadeIn('fast');
            $(".ajax-loader").fadeOut('fast');
        }
                
    }else{

        if(fecha == ""){
            $('#ParPedEsp_Fecha_em_').html('Fecha es requerido.');
            $('#ParPedEsp_Fecha_em_').show(); 
        }

        if(nit == ""){
            $('#ParPedEsp_Nit_em_').html('Cliente es requerido.');
            $('#ParPedEsp_Nit_em_').show(); 
        }

        if(suc == ""){
            $('#ParPedEsp_Sucursal_em_').html('Sucursal es requerido.');
            $('#ParPedEsp_Sucursal_em_').show();    
        }

        if(pe == ""){
            $('#ParPedEsp_Punto_Envio_em_').html('Punto de envío es requerido.');
            $('#ParPedEsp_Punto_Envio_em_').show();    
        }

        if(desc_adic != ""){
        	if(desc_adic <= 0){
            	$('#ParPedEsp_Porc_Desc_em_').html('% Desc. adic. no puede ser menor o igual a 0.');
            	$('#ParPedEsp_Porc_Desc_em_').show(); 
            }  
        }else{
        	$('#ParPedEsp_Porc_Desc_em_').html('% Desc. adic. es requerido.');
            $('#ParPedEsp_Porc_Desc_em_').show(); 	
        }

        if(item == ""){
            $('#ParPedEsp_item_em_').html('Item es requerido.');
            $('#ParPedEsp_item_em_').show();    
        }

        if(cant != ""){
        	if(cant <= 0){
            	$('#ParPedEsp_cant_em_').html('Cant. no puede ser menor o igual a 0.');
            	$('#ParPedEsp_cant_em_').show(); 
            }  
        }else{
        	$('#ParPedEsp_cant_em_').html('Cant. es requerido.');
            $('#ParPedEsp_cant_em_').show(); 	
        }

    }
}

$("body").on("click", ".delete", function (e) {
    $(this).parent().parent("tr").remove();
    var cant = $(".tr_items").length;
    
    if(cant == 0){
        $('#contenido').html('');
        $('#btn_save').hide();  
    }else{
        $('#btn_save').show();  
    }

});


function valida_opciones(){

	var fecha = $('#ParPedEsp_Fecha').val();
	var desc_adic = $('#ParPedEsp_Porc_Desc').val();


  	if(fecha != "" && desc_adic != "" && desc_adic > 0){

	    $('#btn_save').hide();
	    limp_div_msg();
	    $(".ajax-loader").fadeIn('fast');

	    $('#ParPedEsp_cad_item').val('');
	    $('#ParPedEsp_cad_vu').val('');
	    $('#ParPedEsp_cad_cant').val('');
	    $('#ParPedEsp_cad_iva').val('');
	    $('#ParPedEsp_cad_not').val('');
	    $('#ParPedEsp_desc_sucursal').val('');
	    $('#ParPedEsp_desc_punto_envio').val('');
	        
	    var item_selected = '';
	    var vlr_u_selected = ''; 
	    var cant_selected = '';
	    var iva_selected = '';
	    var nota_selected = '';

	    $("input.items").each(function() {

	      var item = $(this).val();
	      var vlr_u = $('#vu_'+item).val();
	      var cant = $('#cant_'+item).val();
	      var iva = $('#iva_'+item).val();
	      var nota = $('#nota_'+item).val();
	     
	      item_selected += item+'|'; 
	      vlr_u_selected += vlr_u+'|'; 
	      cant_selected += cant+'|';  
	      iva_selected += iva+'|'; 
	      nota_selected += nota+'|'; 
	      
	    });

	    var cadena_item = item_selected.slice(0,-1);
	    var cadena_vlr_u = vlr_u_selected.slice(0,-1);
	    var cadena_cant = cant_selected.slice(0,-1);
	    var cadena_iva = iva_selected.slice(0,-1);
	    var cadena_nota = nota_selected.slice(0,-1);
	    
	    $('#ParPedEsp_cad_item').val(cadena_item);
	    $('#ParPedEsp_cad_vu').val(cadena_vlr_u);
	    $('#ParPedEsp_cad_cant').val(cadena_cant);
	    $('#ParPedEsp_cad_iva').val(cadena_iva);
	    $('#ParPedEsp_cad_not').val(cadena_nota);

	    $('#ParPedEsp_desc_sucursal').val($('#ParPedEsp_Sucursal option:selected').text());
	    $('#ParPedEsp_desc_punto_envio').val($('#ParPedEsp_Punto_Envio option:selected').text());


	    var form = $("#par-ped-esp-form");
	    form.submit();

	}else{
		if(fecha == ""){
            $('#ParPedEsp_Fecha_em_').html('Fecha es requerido.');
            $('#ParPedEsp_Fecha_em_').show(); 
        }

        if(desc_adic != ""){
        	if(desc_adic <= 0){
            	$('#ParPedEsp_Porc_Desc_em_').html('% Desc. adic. no puede ser menor o igual a 0.');
            	$('#ParPedEsp_Porc_Desc_em_').show(); 
            }  
        }else{
        	$('#ParPedEsp_Porc_Desc_em_').html('% Desc. adic. es requerido.');
            $('#ParPedEsp_Porc_Desc_em_').show(); 	
        }	
	}
}
</script>
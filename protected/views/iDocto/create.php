<?php
/* @var $this IDoctoController */
/* @var $model IDocto */

//para combos de tipos
$lista_tipos = CHtml::listData($tipos, 'Id', 'Descripcion'); 

//para combos de lineas
$lista_bodegas = CHtml::listData($bodegas, 'Id', 'Descripcion'); 

?>

<script type="text/javascript">

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


	$('#IDocto_Id_Tipo_Docto').change(function() {

		var value = $(this).val();

		$('#IDocto_Id_Emp').val('').trigger('change');
	    $('#s2id_IDocto_Id_Emp span').html("");

	    $('#IDocto_Notas').val('');

		$('#IDocto_det_item').val('').trigger('change');
	    $('#s2id_IDocto_det_item span').html("");

		$('#IDocto_det_bodega_origen').val('').trigger('change');
		$('#IDocto_det_bodega_destino').val('').trigger('change');

		$('#IDocto_det_bodega_tr_origen').val('').trigger('change');

		$('#IDocto_det_bodega_tr_destino').find('option').remove();
		$('#IDocto_det_bodega_tr_destino').val('').trigger('change');
		$("#IDocto_det_bodega_tr_destino").append('<option value=""></option>');
		$('#IDocto_det_bodega_tr_destino').val('').trigger('change');

		$('#IDocto_det_cant').val('');
		$('#IDocto_det_vlr').val('');

		if(value == ""){
			
			$('#btn_volver').show();
			$('#det_add').hide();
			$('#contenido').html('');
	    	$('#btn_save').hide();
	    	$('#error_det').html('');
			$('#error_det').hide();

		}else{
			
			$('#contenido').html('');
	    	$('#btn_save').hide();
	    	$('#error_det').html('');
			$('#error_det').hide();
			$('#btn_volver').hide();
			$('#det_add').show();

			if(value == <?php echo Yii::app()->params->ent; ?>){
				//entrada
				$('#empleado').hide();
				$('#notas').hide();
				$('#bodega_origen').hide();
				$('#bodega_destino').show();
				$('#bodega_origen_tr').hide();
				$('#bodega_destino_tr').hide();
				$('#valor').show();
			}

			if(value == <?php echo Yii::app()->params->sal; ?>){
				//salida
				$('#empleado').hide();
				$('#notas').hide();
				$('#bodega_origen').show();
				$('#bodega_destino').hide();
				$('#bodega_origen_tr').hide();
				$('#bodega_destino_tr').hide();
				$('#valor').hide();
			}

			if(value == <?php echo Yii::app()->params->trb; ?>){
				//transferencia
				$('#empleado').hide();
				$('#notas').hide();
				$('#bodega_origen').hide();
				$('#bodega_destino').hide();
				$('#bodega_origen_tr').show();
				$('#bodega_destino_tr').show();
				$('#valor').hide();
			}

			if(value == <?php echo Yii::app()->params->aje; ?>){
				//ajuste entrada
				$('#empleado').hide();
				$('#notas').show();
				$('#bodega_origen').hide();
				$('#bodega_destino').show();
				$('#bodega_origen_tr').hide();
				$('#bodega_destino_tr').hide();
				$('#valor').hide();
			}

			if(value == <?php echo Yii::app()->params->ajs; ?>){
				//ajuste salida
				$('#empleado').hide();
				$('#notas').show();
				$('#bodega_origen').show();
				$('#bodega_destino').hide();
				$('#bodega_origen_tr').hide();
				$('#bodega_destino_tr').hide();
				$('#valor').hide();
			}

			if(value == <?php echo Yii::app()->params->sad; ?>){
				//salida de dotación
				$('#empleado').show();
				$('#notas').hide();
				$('#bodega_origen').show();
				$('#bodega_destino').hide();
				$('#bodega_origen_tr').hide();
				$('#bodega_destino_tr').hide();
				$('#valor').hide();
			}

			if(value == <?php echo Yii::app()->params->dev; ?>){
				//devolución
				$('#empleado').hide();
				$('#notas').hide();
				$('#bodega_origen').hide();
				$('#bodega_destino').show();
				$('#bodega_origen_tr').hide();
				$('#bodega_destino_tr').hide();
				$('#valor').hide();
			}
		}
	   
	});

});

function add_item(){

	limp_div_msg();

	//cabecera
	var tipo = $('#IDocto_Id_Tipo_Docto').val();
	var fecha = $('#IDocto_Fecha').val();
	var ref = $('#IDocto_Referencia').val();
	var tercero = $('#IDocto_Id_Tercero').val();
	var empleado = $('#IDocto_Id_Emp').val();
	var notas = $('#IDocto_Notas').val();

	//detalle
	var item = $('#IDocto_det_item').val();
	var item_desc =$('#s2id_IDocto_det_item span').html();
	
	var bodega_origen = $('#IDocto_det_bodega_origen').val();
	var bodega_origen_desc =$('#s2id_IDocto_det_bodega_origen span').html();

	var bodega_destino = $('#IDocto_det_bodega_destino').val();
	var bodega_destino_desc =$('#s2id_IDocto_det_bodega_destino span').html();
	
	var bodega_origen_tr = $('#IDocto_det_bodega_tr_origen').val();
	var bodega_origen_tr_desc =$('#s2id_IDocto_det_bodega_tr_origen span').html();

	var bodega_destino_tr = $('#IDocto_det_bodega_tr_destino').val();
	var bodega_destino_tr_desc =$('#s2id_IDocto_det_bodega_tr_destino span').html();
	
	var cant = $('#IDocto_det_cant').val();
	var vlr = $('#IDocto_det_vlr').val();


	if(tipo == <?php echo Yii::app()->params->ent; ?>){
		//entrada
		if( tipo != "" && fecha != "" && ref != "" && tercero != "" && item != "" && bodega_destino != "" && cant != "" && vlr != ""){

			$(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_destino;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega destino</th><th>Cantidad</th><th>Vlr. unitario</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_destino_'+i+'" value="'+bodega_destino+'"><p>'+bodega_destino_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td align="right"><input type="hidden" id="vlr_'+i+'" value="'+vlr+'"><p>'+formatNumber(vlr)+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_destino').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $('#IDocto_det_vlr').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	$('html, body').animate({scrollTop:0}, 'fast');
	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega destino).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_destino == ""){
	            $('#IDocto_det_bodega_destino_em_').html('Bodega destino es requerido.');
	            $('#IDocto_det_bodega_destino_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        }
	        if(vlr == ""){
	            $('#IDocto_det_vlr_em_').html('Vlr. unitario es requerido.');
	            $('#IDocto_det_vlr_em_').show();    
	        }  
		}

	}

	if(tipo == <?php echo Yii::app()->params->sal; ?>){
		//salida
		if( tipo != "" && fecha != "" && ref != "" && tercero != "" && item != "" && bodega_origen != "" && cant != ""){

			$(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_origen;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega origen</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_origen_'+i+'" value="'+bodega_origen+'"><p>'+bodega_origen_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_origen').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	

	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_origen == ""){
	            $('#IDocto_det_bodega_origen_em_').html('Bodega origen es requerido.');
	            $('#IDocto_det_bodega_origen_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        }
		}

	}

	if(tipo == <?php echo Yii::app()->params->trb; ?>){
		//transferencia
		if( tipo != "" && fecha != "" && ref != "" && tercero != "" && item != "" && bodega_origen_tr != "" && bodega_destino_tr != "" && cant != ""){
			
			$(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_origen_tr+'_'+bodega_destino_tr;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega origen</th><th>Bodega destino</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_origen_tr_'+i+'" value="'+bodega_origen_tr+'"><p>'+bodega_origen_tr_desc+'</p></td><td><input type="hidden" id="bodega_destino_tr_'+i+'" value="'+bodega_destino_tr+'"><p>'+bodega_destino_tr_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_tr_origen').val('').trigger('change');
	            $('#IDocto_det_bodega_tr_destino').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	$('html, body').animate({scrollTop:0}, 'fast');
	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen - Bodega destino).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_origen_tr == ""){
	            $('#IDocto_det_bodega_tr_origen_em_').html('Bodega origen es requerido.');
	            $('#IDocto_det_bodega_tr_origen_em_').show();    
	        }
	        if(bodega_destino_tr == ""){
	            $('#IDocto_det_bodega_tr_destino_em_').html('Bodega destino es requerido.');
	            $('#IDocto_det_bodega_tr_destino_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        }
		}

	}

	if(tipo == <?php echo Yii::app()->params->aje; ?>){
		//ajuste entrada
		if( tipo != "" && fecha != "" && ref != "" && tercero != "" && item != "" && bodega_destino != "" && cant != "" && notas != ""){

			$(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_destino;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega destino</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_destino_'+i+'" value="'+bodega_destino+'"><p>'+bodega_destino_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_destino').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	$('html, body').animate({scrollTop:0}, 'fast');
	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega destino).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_destino == ""){
	            $('#IDocto_det_bodega_destino_em_').html('Bodega destino es requerido.');
	            $('#IDocto_det_bodega_destino_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        } 
	        if(notas == ""){
	            $('#IDocto_Notas_em_').html('Notas es requerido.');
	            $('#IDocto_Notas_em_').show();    
	        } 

		}

	}

	if(tipo == <?php echo Yii::app()->params->ajs; ?>){
		//ajuste salida
		if( tipo != "" && fecha != "" && ref != "" && tercero != "" && item != "" && bodega_origen != "" && cant != ""  && notas != ""){
			
			$(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_origen;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega origen</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_origen_'+i+'" value="'+bodega_origen+'"><p>'+bodega_origen_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_origen').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	$('html, body').animate({scrollTop:0}, 'fast');
	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_origen == ""){
	            $('#IDocto_det_bodega_origen_em_').html('Bodega origen es requerido.');
	            $('#IDocto_det_bodega_origen_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        }
	        if(notas == ""){
	            $('#IDocto_Notas_em_').html('Notas es requerido.');
	            $('#IDocto_Notas_em_').show();    
	        }
		}
	}

	if(tipo == <?php echo Yii::app()->params->sad; ?>){
		//salida de dotación 
		if( tipo != "" && fecha != "" && ref != "" && tercero != ""  && empleado != "" && item != "" && bodega_origen != "" && cant != ""){

	        $(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_origen;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega origen</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_origen_'+i+'" value="'+bodega_origen+'"><p>'+bodega_origen_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_origen').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	$('html, body').animate({scrollTop:0}, 'fast');
	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(empleado == ""){
	            $('#IDocto_Id_Emp_em_').html('Empleado es requerido.');
	            $('#IDocto_Id_Emp_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_origen == ""){
	            $('#IDocto_det_bodega_origen_em_').html('Bodega origen es requerido.');
	            $('#IDocto_det_bodega_origen_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        }
		}

	}

	if(tipo == <?php echo Yii::app()->params->dev; ?>){
		//devolución
		if( tipo != "" && fecha != "" && ref != "" && tercero != "" && item != "" && bodega_destino != "" && cant != ""){

	        $(".ajax-loader").fadeIn('fast');

	        var div_contenido = $('#contenido');

	        var i = item+'_'+bodega_destino;
	        var tr = $("#tr_"+i).length;

	        if(!tr){
			
				var cant_f = $(".tr_items").length;

	            if(cant_f == 0){
	                div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Item</th><th>Bodega destino</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
	            }

	            var tabla = $('#table_items');

				tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="item_'+i+'" value="'+item+'"><p>'+item_desc+'</p></td><td><input type="hidden" id="bodega_destino_'+i+'" value="'+bodega_destino+'"><p>'+bodega_destino_desc+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fa fa-trash"></i></button></td></tr>');

	            //se resetean los campos para seguir recibiendo detalles
	            $('#btn_save').show(); 
	            $('#IDocto_det_item').val('').trigger('change');
	            $('#s2id_IDocto_det_item span').html("");
	            $('#IDocto_det_bodega_destino').val('').trigger('change');
	            $('#IDocto_det_cant').val('');
	            $(".ajax-loader").fadeOut('fast');

	        }else{
	        	$('html, body').animate({scrollTop:0}, 'fast');
	            $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
	            $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega destino).');	
	            $("#div_mensaje").fadeIn('fast');
	            $(".ajax-loader").fadeOut('fast');
	        }

		}else{
			if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(item == ""){
	            $('#IDocto_det_item_em_').html('Item es requerido.');
	            $('#IDocto_det_item_em_').show(); 
	        }
	        if(bodega_destino == ""){
	            $('#IDocto_det_bodega_destino_em_').html('Bodega destino es requerido.');
	            $('#IDocto_det_bodega_destino_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDocto_det_cant_em_').html('Cantidad es requerido.');
	            $('#IDocto_det_cant_em_').show();    
	        } 
		}

	}

}

$("#IDocto_det_bodega_tr_origen").change( function (){

	bod = $(this).val();
	
	$('#IDocto_det_bodega_tr_destino').find('option').remove();
	$('#IDocto_det_bodega_tr_destino').val('').trigger('change');
	$("#IDocto_det_bodega_tr_destino").append('<option value=""></option>');
	$('#IDocto_det_bodega_tr_destino').val('').trigger('change');

	if(bod != ""){

	 	$("#IDocto_det_bodega_tr_origen option").each(function(name, val) { 
	 	
	 		opt_val = val.value;
			opt_text = val.text;

			if(bod != opt_val && opt_val !=  ""){
				$("#IDocto_det_bodega_tr_destino").append('<option value='+opt_val+'>'+opt_text+'</option>');
			}

 	 	});

	}
   
});


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

	//cabecera
	var tipo = $('#IDocto_Id_Tipo_Docto').val();
	var fecha = $('#IDocto_Fecha').val();
	var ref = $('#IDocto_Referencia').val();
	var tercero = $('#IDocto_Id_Tercero').val();
	var empleado = $('#IDocto_Id_Emp').val();
	var notas = $('#IDocto_Notas').val();

    $('#IDocto_cad_item').val('');
    $('#IDocto_cad_bodega_origen').val('');
    $('#IDocto_cad_bodega_destino').val('');
    $('#IDocto_cad_cant').val('');
    $('#IDocto_cad_vlr').val('');

    var item_selected = '';
    var bodega_origen_selected = ''; 
    var bodega_destino_selected = ''; 
    var cant_selected = ''; 
    var vlr_selected = '';

    if(tipo == <?php echo Yii::app()->params->ent; ?>){
    	//entrada
    	
    	if(tipo == "" && fecha == "" || ref == "" || tercero == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	      
	    	return false;

		}else{

			$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		            f = $(this).val();
		            
		            item = $('#item_'+f).val();
		            bodega = $('#bodega_destino_'+f).val();
		            cant = $('#cant_'+f).val();
		            vlr = $('#vlr_'+f).val();
		            
		            item_selected += item+",";
		            bodega_destino_selected += bodega+",";
		            cant_selected += cant+","; 
		            vlr_selected += vlr+",";         

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_destino = bodega_destino_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);
		    var cadena_vlr = vlr_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_destino').val(cadena_bodega_destino);
		    $('#IDocto_cad_cant').val(cadena_cant);
		    $('#IDocto_cad_vlr').val(cadena_vlr);

		    return true;
		}

    }

    if(tipo == <?php echo Yii::app()->params->sal; ?>){
    	//salida

    	if(tipo == "" && fecha == "" || ref == "" || tercero == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	      
	    	return false;

		}else{

			$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		            f = $(this).val();
		            
		            item = $('#item_'+f).val();
		            bodega = $('#bodega_origen_'+f).val();
		            cant = $('#cant_'+f).val();
		            
		            item_selected += item+",";
		            bodega_origen_selected += bodega+",";
		            cant_selected += cant+","; 

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_origen = bodega_origen_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_origen').val(cadena_bodega_origen);
		    $('#IDocto_cad_cant').val(cadena_cant);

		    return true;
		}

    }

    if(tipo == <?php echo Yii::app()->params->trb; ?>){
    	//transferencia

    	if(tipo == "" && fecha == "" || ref == "" || tercero == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	      
	    	return false;

		}else{

			$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		            f = $(this).val();
		            item = $('#item_'+f).val();
		            bodega_origen_tr = $('#bodega_origen_tr_'+f).val();
		            bodega_destino_tr = $('#bodega_destino_tr_'+f).val();
		            cant = $('#cant_'+f).val();
		            
		            item_selected += item+",";
		            bodega_origen_selected += bodega_origen_tr+",";
		            bodega_destino_selected += bodega_destino_tr+",";
		            cant_selected += cant+","; 

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_origen = bodega_origen_selected.slice(0,-1);
		    var cadena_bodega_destino = bodega_destino_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_origen').val(cadena_bodega_origen);
		    $('#IDocto_cad_bodega_destino').val(cadena_bodega_destino);
		    $('#IDocto_cad_cant').val(cadena_cant);

		    return true;
		}

    }

    if(tipo == <?php echo Yii::app()->params->aje; ?>){
    	//ajuste entrada

    	if(tipo == "" && fecha == "" || ref == "" || tercero == "" || notas == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(notas == ""){
	            $('#IDocto_Notas_em_').html('Notas es requerido.');
	            $('#IDocto_Notas_em_').show();    
	        }
	      
	    	return false;

		}else{

			$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		            f = $(this).val();
		            
		            item = $('#item_'+f).val();
		            bodega = $('#bodega_destino_'+f).val();
		            cant = $('#cant_'+f).val();
		            
		            item_selected += item+",";
		            bodega_destino_selected += bodega+",";
		            cant_selected += cant+","; 

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_destino = bodega_destino_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_destino').val(cadena_bodega_destino);
		    $('#IDocto_cad_cant').val(cadena_cant);

		    return true;
		}

    }

    if(tipo == <?php echo Yii::app()->params->ajs; ?>){
    	//ajuste salida

    	if(tipo == "" && fecha == "" || ref == "" || tercero == "" || notas == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(notas == ""){
	            $('#IDocto_Notas_em_').html('Notas es requerido.');
	            $('#IDocto_Notas_em_').show();    
	        }
	      
	    	return false;

		}else{

			$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		            f = $(this).val();
		            
		            item = $('#item_'+f).val();
		            bodega = $('#bodega_origen_'+f).val();
		            cant = $('#cant_'+f).val();
		            
		            item_selected += item+",";
		            bodega_origen_selected += bodega+",";
		            cant_selected += cant+","; 

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_origen = bodega_origen_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_origen').val(cadena_bodega_origen);
		    $('#IDocto_cad_cant').val(cadena_cant);

		    return true;

		}

    }


    if(tipo == <?php echo Yii::app()->params->sad; ?>){
    	//salida de dotación

    	if(tipo == "" || fecha == "" || ref == "" || tercero == "" || empleado == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	        if(empleado == ""){
	            $('#IDocto_Id_Emp_em_').html('Empleado es requerido.');
	            $('#IDocto_Id_Emp_em_').show(); 
	        }

	        return false;

    	}else{

    		$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		        f = $(this).val();
	            
	            item = $('#item_'+f).val();
	            bodega = $('#bodega_origen_'+f).val();
	            cant = $('#cant_'+f).val();
	            
	            item_selected += item+",";
	            bodega_origen_selected += bodega+",";
	            cant_selected += cant+","; 

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_origen = bodega_origen_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_origen').val(cadena_bodega_origen);
		    $('#IDocto_cad_cant').val(cadena_cant);

		    return true;

    	}

    }

    if(tipo == <?php echo Yii::app()->params->dev; ?>){
    	//devolución

    	if(tipo == "" && fecha == "" || ref == "" || tercero == ""){

    		if(tipo == ""){
	            $('#IDocto_Id_Tipo_Docto_em_').html('Tipo es requerido.');
	            $('#IDocto_Id_Tipo_Docto_em_').show(); 
	        }
	        if(fecha == ""){
	            $('#IDocto_Fecha_em_').html('Fecha es requerido.');
	            $('#IDocto_Fecha_em_').show(); 
	        }
	        if(ref == ""){
	            $('#IDocto_Referencia_em_').html('Referencia es requerido.');
	            $('#IDocto_Referencia_em_').show(); 
	        }
	        if(tercero == ""){
	            $('#IDocto_Id_Tercero_em_').html('Tercero es requerido.');
	            $('#IDocto_Id_Tercero_em_').show(); 
	        }
	      
	    	return false;

		}else{

			$('#btn_save').hide();
	    	limp_div_msg();
	    	$(".ajax-loader").fadeIn('fast');

	    	$("input.items").each(function() {

		            f = $(this).val();
		            
		            item = $('#item_'+f).val();
		            bodega = $('#bodega_destino_'+f).val();
		            cant = $('#cant_'+f).val();
		            
		            item_selected += item+",";
		            bodega_destino_selected += bodega+",";
		            cant_selected += cant+","; 

		    });

		    var cadena_item = item_selected.slice(0,-1);
		    var cadena_bodega_destino = bodega_destino_selected.slice(0,-1);
		    var cadena_cant = cant_selected.slice(0,-1);

		    $('#IDocto_cad_item').val(cadena_item);
		    $('#IDocto_cad_bodega_destino').val(cadena_bodega_destino);
		    $('#IDocto_cad_cant').val(cadena_cant);

		    return true;
		}

    }
       
}

</script>

<h4>Creación de documento</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos, 'lista_bodegas'=>$lista_bodegas)); ?>
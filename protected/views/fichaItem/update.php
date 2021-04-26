<?php
/* @var $this FichaItemController */
/* @var $model FichaItem */

?>

<script type="text/javascript">
$(function() {

	$(".ajax-loader").fadeIn('fast');

	var tipo_producto = $('#FichaItem_Tipo_Producto').val();

	var step = <?php echo $s; ?>;
	var e = <?php echo $e; ?>;
    div_step(step, e);

    if(tipo_producto == 1){
        $('#pres').show();
        $('#env').show();
        $('#und_ep').show();
        $('#und_cad').show();
    }

    if(tipo_producto == 5){
        $('#pres').hide();
        $('#env').hide();
        $('#und_ep').show();
        $('#und_cad').show();
    }

    if(tipo_producto == 2 || tipo_producto == 3 || tipo_producto == 4){
        $('#pres').hide();
        $('#env').hide();
        $('#und_ep').hide();
        $('#und_cad').hide();
    }
   
	//se llenan las opciones seleccionadas del modelo
	$('#FichaItem_Pais').val(<?php echo $paises_activos ?>).trigger('change');
	$('#FichaItem_Instalaciones').val(<?php echo $instalaciones_activas ?>).trigger('change');
	$('#FichaItem_Bodegas').val(<?php echo $bodegas_activas ?>).trigger('change');

	$("#valida_form").click(function() {

      	var form = $("#ficha-item-form");
		var settings = form.data('settings') ;
		var step = <?php echo $s; ?>;
		var tipo_producto = $("#FichaItem_Tipo_Producto").val();

		var pres = $("#FichaItem_Presentacion").val();
		var env = $("#FichaItem_Envase").val();
      	var und_ep = $("#FichaItem_Ep_Medida").val();
      	var und_cad = $("#FichaItem_Cad_Medida").val();

		if(step == 2){

			if(tipo_producto == 1){   
		        //producto terminado
		        if(pres != "" && env != "" && und_ep != "" && und_cad != ""){
		            $valid = 1;
		        }else{
		            if(pres == ""){
		                $('#FichaItem_Presentacion_em_').html('Presentación es requerido.');
		                $('#FichaItem_Presentacion_em_').show(); 
		            }

		            if(env == ""){
		                $('#FichaItem_Envase_em_').html('Envase es requerido.');
		                $('#FichaItem_Envase_em_').show(); 
		            }

		            if(und_ep == ""){
	                    $('#FichaItem_Ep_Medida_em_').html('Und. medida emp. principal es requerido.');
	                    $('#FichaItem_Ep_Medida_em_').show(); 
	                }

	                if(und_cad == ""){
	                    $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
	                    $('#FichaItem_Cad_Medida_em_').show(); 
	                }

		            $valid = 0;

		        }

		      }else{

		      	if(tipo_producto == 5){   
			        //prom
			        if(und_ep != "" || und_cad != ""){
			            $valid = 1;
			        }else{
			            if(und_ep == ""){
			                $('#FichaItem_Ep_Medida_em_').html('Und. medida emp. principal es requerido.');
			                $('#FichaItem_Ep_Medida_em_').show(); 
			            }

			            if(und_cad == ""){
			                $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
			                $('#FichaItem_Cad_Medida_em_').show(); 
			            }

			            $valid = 0;

			        }

		      	}else{
	      			$valid = 1;
		      	}

		        
		     }

		}else{
			$valid = 1;
		}
		
		settings.submitting = true ;
		$.fn.yiiactiveform.validate(form, function(messages) {
		  if($.isEmptyObject(messages)) {
		    $.each(settings.attributes, function () {
		        $.fn.yiiactiveform.updateInput(this,messages,form); 
		    });

			if($valid == 1){
                //se envia el form
                form.submit();
                loadershow();
            }else{
            	if(step == 2){

					if(tipo_producto == 1){   
				        //producto terminado
				        
				            if(pres == ""){
				                $('#FichaItem_Presentacion_em_').html('Presentación es requerido.');
				                $('#FichaItem_Presentacion_em_').show(); 
				            }

				            if(env == ""){
				                $('#FichaItem_Envase_em_').html('Envase es requerido.');
				                $('#FichaItem_Envase_em_').show(); 
				            }

				            if(und_ep == ""){
				                $('#FichaItem_Ep_Medida_em_').html('Und. medida emp. principal es requerido.');
				                $('#FichaItem_Ep_Medida_em_').show(); 
				            }

				            if(und_cad == ""){
				                $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
				                $('#FichaItem_Cad_Medida_em_').show(); 
				            }

				    }else{

				        if(tipo_producto == 5){
				            //promoción
				            
			                if(und_ep == ""){
			                    $('#FichaItem_Ep_Medida_em_').html('Und. medida emp. principal es requerido.');
			                    $('#FichaItem_Ep_Medida_em_').show(); 
			                }

			                if(und_cad == ""){
			                    $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
			                    $('#FichaItem_Cad_Medida_em_').show(); 
			                }
				        }
				    }

				}	
            }
		    
		  } else {
		      settings = form.data('settings'),
		      $.each(settings.attributes, function () {
		         $.fn.yiiactiveform.updateInput(this,messages,form); 
		      });
		      settings.submitting = false ;

		      if(step == 2){

					if(tipo_producto == 1){   
				        //producto terminado
				        
				            if(pres == ""){
				                $('#FichaItem_Presentacion_em_').html('Presentación es requerido.');
				                $('#FichaItem_Presentacion_em_').show(); 
				            }

				            if(env == ""){
				                $('#FichaItem_Envase_em_').html('Envase es requerido.');
				                $('#FichaItem_Envase_em_').show(); 
				            }

				            if(und_ep == ""){
				                $('#FichaItem_Ep_Medida_em_').html('Und. medida emp. principal es requerido.');
				                $('#FichaItem_Ep_Medida_em_').show(); 
				            }

				            if(und_cad == ""){
				                $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
				                $('#FichaItem_Cad_Medida_em_').show(); 
				            }

				    }else{

				        if(tipo_producto == 5){
				            //promoción
				            
			                if(und_ep == ""){
			                    $('#FichaItem_Ep_Medida_em_').html('Und. medida emp. principal es requerido.');
			                    $('#FichaItem_Ep_Medida_em_').show(); 
			                }

			                if(und_cad == ""){
			                    $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
			                    $('#FichaItem_Cad_Medida_em_').show(); 
			                }
				        }
				    }

				}
		  }
		});
	});

	$('#FichaItem_Presentacion').change(function() {
        var tipo_producto = $("#FichaItem_Tipo_Producto").val();
        var val = $('#FichaItem_Presentacion').val();
        if(tipo_producto == 1){   
            if(val == ""){
                $('#FichaItem_Presentacion_em_').html('Presentación es requerido.');
                $('#FichaItem_Presentacion_em_').show(); 
            }else{
                $('#FichaItem_Presentacion_em_').html('');
                $('#FichaItem_Presentacion_em_').hide(); 
            }

        }else{
            $('#FichaItem_Presentacion_em_').html('');
            $('#FichaItem_Presentacion_em_').hide(); 
        
        }
        
    });

	$('#FichaItem_Ep_Medida').change(function() {
        var tipo_producto = $("#FichaItem_Tipo_Producto").val();
        var val = $('#FichaItem_Ep_Medida').val();
        if(tipo_producto == 1 || tipo_producto == 5){   
            if(val == ""){
                $('#FichaItem_Ep_Medida_em_').html('Und. medida subemp. cadena es requerido.');
                $('#FichaItem_Ep_Medida_em_').show(); 
            }else{
                $('#FichaItem_Ep_Medida_em_').html('');
                $('#FichaItem_Ep_Medida_em_').hide(); 
            }

        }else{
            $('#FichaItem_Ep_Medida_em_').html('');
            $('#FichaItem_Ep_Medida_em_').hide(); 
        
        }
      
    });

    $('#FichaItem_Cad_Medida').change(function() {
        var tipo_producto = $("#FichaItem_Tipo_Producto").val();
        var val = $('#FichaItem_Cad_Medida').val();
        if(tipo_producto == 1 || tipo_producto == 5){   
            if(val == ""){
                $('#FichaItem_Cad_Medida_em_').html('Und. medida subemp. cadena es requerido.');
                $('#FichaItem_Cad_Medida_em_').show(); 
            }else{
                $('#FichaItem_Cad_Medida_em_').html('');
                $('#FichaItem_Cad_Medida_em_').hide(); 
            }

        }else{
            $('#FichaItem_Cad_Medida_em_').html('');
            $('#FichaItem_Cad_Medida_em_').hide(); 
        
        }
        
    });

	$('#FichaItem_Tipo_Producto').change(function() {

        var value = $(this).val();

        if(value == 1){
            $('#pres').show();
            $('#env').show();
            $('#comp').hide();
            $('#und_ep').show();
            $('#und_cad').show();
        }

        if(value == 5){
            $('#pres').hide();
            $('#env').hide();
            $('#comp').show();
            $('#und_ep').show();
            $('#und_cad').show();
        }

        if(value == 2 || value == 3 || value == 4){
            $('#pres').hide();
            $('#env').hide();
            $('#comp').hide();
            $('#und_ep').hide();
            $('#und_cad').hide();
        }

        desc_corta();
       
    });

    $("#addcomp").click(function() {
        var comp = $("#FichaItem_comp").val();
        var cant = $("#FichaItem_cant").val();

        if(comp != "" && cant != ""){
            var comp_c = cant+' '+comp;
            var cont = $('#FichaItem_Descripcion_Larga').val();
            if(cont != ""){
                $('#FichaItem_Descripcion_Larga').val(cont+', '+comp_c); 
            }else{
                $('#FichaItem_Descripcion_Larga').val(comp_c);    
            }

            $('#FichaItem_comp').val('').trigger('change');
            $('#s2id_FichaItem_comp span').html(""); 
            $("#FichaItem_cant").val('');
        }  

    });

	$("#rechazar_form").click(function() {
		var opcion = confirm("Desea solicitar una revisión de los datos registrados?");
	    if (opcion == true) {
	    	loadershow();
	       	location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/notas&id='.$model->Id; ?>';
	   	} 
	});

});

function desc_corta(){

    var tp = $('#FichaItem_Tipo_Producto').val();

    var nf = $('#FichaItem_Nombre_Funcional').val();
    var marca = $('#FichaItem_Marca_Producto').val();
    var caract = $('#FichaItem_Caracteristicas').val();
    var present = $('#FichaItem_Presentacion').val();

    if(tp == 1){
        if(nf != "" && marca != "" && caract != "" && present != ""){
            var desc_corta = nf+' '+marca+' '+caract+' '+present;
            $('#FichaItem_Descripcion_Corta').val(desc_corta);  
        }else{
            $('#FichaItem_Descripcion_Corta').val('');  
        }
    }else{
        if(tp == 2 || tp == 3 || tp == 4 || tp == 5){
            if(nf != "" && marca != "" && caract != ""){
                var desc_corta = nf+' '+marca+' '+caract;
                $('#FichaItem_Descripcion_Corta').val(desc_corta);  
            }else{
                $('#FichaItem_Descripcion_Corta').val('');  
            }   
        }else{
            $('#FichaItem_Descripcion_Corta').val('');     
        }   
    }
        
}

function calculo_volumen(opc){
	if(opc ==  1){
		var largo = $('#FichaItem_Un_Largo').val();
		var ancho = $('#FichaItem_Un_Ancho').val();
		var alto = $('#FichaItem_Un_Alto').val();

		if(largo != "" && ancho != "" && alto != ""){
			var volumen = largo * ancho * alto / 1000000;
			$('#FichaItem_Un_Volumen').val(volumen.toFixed(6));	
		}else{
			$('#FichaItem_Un_Volumen').val('');	
		}		
	}

	if(opc ==  2){
		var largo = $('#FichaItem_Ep_Largo').val();
		var ancho = $('#FichaItem_Ep_Ancho').val();
		var alto = $('#FichaItem_Ep_Alto').val();

		if(largo != "" && ancho != "" && alto != ""){
			var volumen = largo * ancho * alto / 1000000;
			$('#FichaItem_Ep_Volumen').val(volumen.toFixed(6));	
		}else{
			$('#FichaItem_Ep_Volumen').val('');	
		}	
	}

	if(opc ==  3){
		var largo = $('#FichaItem_Cad_Largo').val();
		var ancho = $('#FichaItem_Cad_Ancho').val();
		var alto = $('#FichaItem_Cad_Alto').val();

		if(largo != "" && ancho != "" && alto != ""){
			var volumen = largo * ancho * alto / 1000000;
			$('#FichaItem_Cad_Volumen').val(volumen.toFixed(6));	
		}else{
			$('#FichaItem_Cad_Volumen').val('');	
		}	
	}
}

function div_step(step, e){

	var tipo_producto = $('#FichaItem_Tipo_Producto').val();

    if(step == 2){

    	if(e == 1){

    		if(tipo_producto == 5){
		        $('#comp').show();
		    }

	    	//campos habilitados
	    	$('#FichaItem_Pais').removeAttr('disabled');
            $('#FichaItem_Tipo_Producto').removeAttr('disabled');
            $('#FichaItem_Origen').removeAttr('disabled');
            $('#FichaItem_Nombre_Funcional').removeAttr('disabled');
            $('#FichaItem_Marca_Producto').removeAttr('disabled');
            $('#FichaItem_Caracteristicas').removeAttr('disabled');
            $('#FichaItem_Descripcion_Larga').removeAttr('disabled');
            $('#FichaItem_Unidad_Medida_Inv').removeAttr('disabled');
            $('#FichaItem_Unidad_Medida_Compra').removeAttr('disabled');
            $('#FichaItem_Ind_Compra').removeAttr('disabled');
            $('#FichaItem_Ind_Manufactura').removeAttr('disabled');
            $('#FichaItem_Ind_Venta').removeAttr('disabled');
            $("#FichaItem_Un_Medida").removeAttr('disabled');
            $("#FichaItem_Ep_Medida").removeAttr('disabled');
            $("#FichaItem_Cad_Medida").removeAttr('disabled');
            $("#FichaItem_cant").removeAttr('disabled');
            $('#FichaItem_Presentacion').removeAttr('disabled');
            $('#FichaItem_Envase').removeAttr('disabled');
	  		$("#buttons_1").html('<button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
	  	}

        $("#collapse_2").hide();
        $("#collapse_3").hide();
        $("#collapse_4").hide();
        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 3){
        //finanzas

        if(e == 1){
	    	//campos habilitados
	  		$('#FichaItem_Tipo_Inventario').removeAttr('disabled');
  			$('#FichaItem_Grupo_Impositivo').removeAttr('disabled');
  			$('#FichaItem_Exento_Impuesto').removeAttr('disabled');
  			$("#buttons_2").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
	  	}

        $("#collapse_3").hide();
        $("#collapse_4").hide();
        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 4){
        //verificación finanzas

        if(e == 1){
	    	//campos habilitados
	  		$('#FichaItem_Tipo_Inventario').removeAttr('disabled');
  			$('#FichaItem_Grupo_Impositivo').removeAttr('disabled');
  			$('#FichaItem_Exento_Impuesto').removeAttr('disabled');
  			$("#buttons_2").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
	  	}

        $("#collapse_3").hide();
        $("#collapse_4").hide();
        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 5){
        //comercial

        if(e == 1){
	  		//campos habilitados
	  		$('#FichaItem_Crit_Origen').removeAttr('disabled');
	  		$('#FichaItem_Crit_Tipo').removeAttr('disabled');
	  		$('#FichaItem_Crit_Clasificacion').removeAttr('disabled');
	  		$('#FichaItem_Crit_Clase').removeAttr('disabled');
	  		$('#FichaItem_Crit_Marca').removeAttr('disabled');
	  		$('#FichaItem_Crit_Submarca').removeAttr('disabled');
	  		$('#FichaItem_Crit_Segmento').removeAttr('disabled');
	  		$('#FichaItem_Crit_Familia').removeAttr('disabled');
	  		$('#FichaItem_Crit_Subfamilia').removeAttr('disabled');
	  		$('#FichaItem_Crit_Linea').removeAttr('disabled');
	  		$('#FichaItem_Crit_Sublinea').removeAttr('disabled');
	  		$('#FichaItem_Crit_Grupo').removeAttr('disabled');
	  		$('#FichaItem_Crit_UN').removeAttr('disabled');
	  		$('#FichaItem_Crit_Fabrica').removeAttr('disabled');
	  		$('#FichaItem_Crit_Cat_Oracle').removeAttr('disabled');
	  		$("#buttons_3").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
	  	}

        $("#collapse_4").hide();
        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 6){
        //verificación comercial

        if(e == 1){
	  		//campos habilitados
	  		$('#FichaItem_Crit_Origen').removeAttr('disabled');
	  		$('#FichaItem_Crit_Tipo').removeAttr('disabled');
	  		$('#FichaItem_Crit_Clasificacion').removeAttr('disabled');
	  		$('#FichaItem_Crit_Clase').removeAttr('disabled');
	  		$('#FichaItem_Crit_Marca').removeAttr('disabled');
	  		$('#FichaItem_Crit_Submarca').removeAttr('disabled');
	  		$('#FichaItem_Crit_Segmento').removeAttr('disabled');
	  		$('#FichaItem_Crit_Familia').removeAttr('disabled');
	  		$('#FichaItem_Crit_Subfamilia').removeAttr('disabled');
	  		$('#FichaItem_Crit_Linea').removeAttr('disabled');
	  		$('#FichaItem_Crit_Sublinea').removeAttr('disabled');
	  		$('#FichaItem_Crit_Grupo').removeAttr('disabled');
	  		$('#FichaItem_Crit_UN').removeAttr('disabled');
	  		$('#FichaItem_Crit_Fabrica').removeAttr('disabled');
	  		$('#FichaItem_Crit_Cat_Oracle').removeAttr('disabled');
	  		$("#buttons_3").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
	  	}

        $("#collapse_4").hide();
        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 7){
        //ingenieria

        if(tipo_producto == 1 || tipo_producto == 5){
			$('#log_ep').show();
			$('#log_se_cad').show();
			if(e == 1){
		  		//campos habilitados
		  		$('#FichaItem_Un_Medida').removeAttr('disabled');
		  		$('#FichaItem_Un_Cant').removeAttr('disabled');
		  		$('#FichaItem_Un_Peso').removeAttr('disabled');
		  		$('#FichaItem_Un_Largo').removeAttr('disabled');
		  		$('#FichaItem_Un_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Un_Alto').removeAttr('disabled');
		  		$('#FichaItem_Ep_Medida').removeAttr('disabled');
		  		$('#FichaItem_Ep_Cant').removeAttr('disabled');
		  		$('#FichaItem_Ep_Peso').removeAttr('disabled');
		  		$('#FichaItem_Ep_Largo').removeAttr('disabled');
		  		$('#FichaItem_Ep_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Ep_Alto').removeAttr('disabled');
		  		$('#FichaItem_Cad_Medida').removeAttr('disabled');
		  		$('#FichaItem_Cad_Cant').removeAttr('disabled');
		  		$('#FichaItem_Cad_Peso').removeAttr('disabled');
		  		$('#FichaItem_Cad_Largo').removeAttr('disabled');
		  		$('#FichaItem_Cad_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Cad_Alto').removeAttr('disabled');
		  		$("#buttons_4").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
		  	}
		}else{
			$('#log_ep').hide();
			$('#log_se_cad').hide();
			if(e == 1){
		  		//campos habilitados
		  		$('#FichaItem_Un_Medida').removeAttr('disabled');
		  		$('#FichaItem_Un_Cant').removeAttr('disabled');
		  		$('#FichaItem_Un_Peso').removeAttr('disabled');
		  		$('#FichaItem_Un_Largo').removeAttr('disabled');
		  		$('#FichaItem_Un_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Un_Alto').removeAttr('disabled');
		  		$("#buttons_4").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
		  	}
		}

        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_4').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 8){
        //verificación ingenieria

        if(tipo_producto == 1 || tipo_producto == 5){
			$('#log_ep').show();
			$('#log_se_cad').show();
			if(e == 1){
		  		//campos habilitados
		  		$('#FichaItem_Un_Medida').removeAttr('disabled');
		  		$('#FichaItem_Un_Cant').removeAttr('disabled');
		  		$('#FichaItem_Un_Peso').removeAttr('disabled');
		  		$('#FichaItem_Un_Largo').removeAttr('disabled');
		  		$('#FichaItem_Un_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Un_Alto').removeAttr('disabled');
		  		$('#FichaItem_Ep_Medida').removeAttr('disabled');
		  		$('#FichaItem_Ep_Cant').removeAttr('disabled');
		  		$('#FichaItem_Ep_Peso').removeAttr('disabled');
		  		$('#FichaItem_Ep_Largo').removeAttr('disabled');
		  		$('#FichaItem_Ep_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Ep_Alto').removeAttr('disabled');
		  		$('#FichaItem_Cad_Medida').removeAttr('disabled');
		  		$('#FichaItem_Cad_Cant').removeAttr('disabled');
		  		$('#FichaItem_Cad_Peso').removeAttr('disabled');
		  		$('#FichaItem_Cad_Largo').removeAttr('disabled');
		  		$('#FichaItem_Cad_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Cad_Alto').removeAttr('disabled');
		  		$("#buttons_4").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
		  	}
		}else{
			$('#log_ep').hide();
			$('#log_se_cad').hide();
			if(e == 1){
		  		//campos habilitados
		  		$('#FichaItem_Un_Medida').removeAttr('disabled');
		  		$('#FichaItem_Un_Cant').removeAttr('disabled');
		  		$('#FichaItem_Un_Peso').removeAttr('disabled');
		  		$('#FichaItem_Un_Largo').removeAttr('disabled');
		  		$('#FichaItem_Un_Ancho').removeAttr('disabled');
		  		$('#FichaItem_Un_Alto').removeAttr('disabled');
		  		$("#buttons_4").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>');
		  	}
		}

        $("#collapse_5").hide();

       	$('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_4').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 9){
        //datos maestros

        if(tipo_producto == 1 || tipo_producto == 5){
			$('#log_ep').show();
			$('#log_se_cad').show();
			$('#un_gtin').show();
			$('#ep_gtin').show();
			$('#cad_gtin').show();
			if(e == 1){
		  		//campos habilitados
		  		$('#FichaItem_Codigo_Item').removeAttr('disabled');
				$('#FichaItem_Referencia').removeAttr('disabled');
				$('#FichaItem_Maneja_Lote').removeAttr('disabled');
				$('#FichaItem_Tiempo_Reposicion').removeAttr('disabled');
				$('#FichaItem_Cant_Moq').removeAttr('disabled');
				$('#FichaItem_Stock_Minimo').removeAttr('disabled');
				$('#FichaItem_Posicion_Arancelar').removeAttr('disabled');
				$('#FichaItem_Instalaciones').removeAttr('disabled');
				$('#FichaItem_Bodegas').removeAttr('disabled');
				$('#FichaItem_Un_Gtin').removeAttr('disabled');
				$('#FichaItem_Ep_Gtin').removeAttr('disabled');
				$('#FichaItem_Cad_Gtin').removeAttr('disabled');
				$("#buttons_5").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-check-circle"></i> Aprobar</button>');
		  	}
		}else{
		
			$('#log_ep').hide();
			$('#log_se_cad').hide();
			$('#un_gtin').hide();
			$('#ep_gtin').hide();
			$('#cad_gtin').hide();
			if(e == 1){
		  		//campos habilitados
		  		$('#FichaItem_Codigo_Item').removeAttr('disabled');
				$('#FichaItem_Referencia').removeAttr('disabled');
				$('#FichaItem_Maneja_Lote').removeAttr('disabled');
				$('#FichaItem_Tiempo_Reposicion').removeAttr('disabled');
				$('#FichaItem_Cant_Moq').removeAttr('disabled');
				$('#FichaItem_Stock_Minimo').removeAttr('disabled');
				$('#FichaItem_Posicion_Arancelar').removeAttr('disabled');
				$('#FichaItem_Instalaciones').removeAttr('disabled');
				$('#FichaItem_Bodegas').removeAttr('disabled');
				$("#buttons_5").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-check-circle"></i> Aprobar</button>');
		  	}	
	
		}

		var un_med = '<?php echo $desc_un_med; ?>';
		var ep_med = '<?php echo $desc_ep_med; ?>';
		var cad_med = '<?php echo $desc_cad_med; ?>';

		
		$('label[for="FichaItem_Un_Gtin"]').text('EAN 13 / '+un_med);

		$('label[for="FichaItem_Ep_Gtin"]').text('EAN 14 / '+ep_med);

		$('label[for="FichaItem_Cad_Gtin"]').text('EAN 14 - 1 / '+cad_med);

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-warning').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_4').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_5').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 10){

    	if(tipo_producto == 1 || tipo_producto == 5){
			$('#log_ep').show();
			$('#log_se_cad').show();
			$('#un_gtin').show();
			$('#ep_gtin').show();
			$('#cad_gtin').show();
			
		}else{
			$('#log_ep').hide();
			$('#log_se_cad').hide();
			$('#un_gtin').hide();
			$('#ep_gtin').hide();
			$('#cad_gtin').hide();
		}

		var un_med = '<?php echo $desc_un_med; ?>';
		var ep_med = '<?php echo $desc_ep_med; ?>';
		var cad_med = '<?php echo $desc_cad_med; ?>';

		
		$('label[for="FichaItem_Un_Gtin"]').text('EAN 13 / '+un_med);

		$('label[for="FichaItem_Ep_Gtin"]').text('EAN 14 / '+ep_med);

		$('label[for="FichaItem_Cad_Gtin"]').text('EAN 14 - 1 / '+cad_med);

        //proceso completo
        $("#buttons_1").html('');
        $("#buttons_2").html('');
        $("#buttons_3").html('');
        $("#buttons_4").html('');
        $("#buttons_5").html('');

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_5').addClass('card-success').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_4').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_5').addClass('fa-check-circle').removeClass('fa-clock');
    }

    $(".ajax-loader").fadeOut('fast');
}

</script>

<div class="row mb-2">
  <div class="col-sm-9">
    <h4>Revisión solicitud creación de ítem en siesa</h4>
  </div>
  <div class="col-sm-3 text-right"> 
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
  </div>
</div>

<?php 
$this->renderPartial('_form', array(
	'model'=>$model,
	'lista_unidad'=>$lista_unidad,
	'lista_tipo_inv'=>$lista_tipo_inv,
	'lista_grupo_imp'=>$lista_grupo_imp,
	'lista_origen'=>$lista_origen,
	'lista_tipo'=>$lista_tipo,
	'lista_clasif'=>$lista_clasif,
	'lista_clase'=>$lista_clase,
	'lista_marca'=>$lista_marca,
	'lista_submarca'=>$lista_submarca,
	'lista_segmento'=>$lista_segmento,
	'lista_familia'=>$lista_familia,
	'lista_linea'=>$lista_linea,
	'lista_subfamilia'=>$lista_subfamilia,
	'lista_sublinea'=>$lista_sublinea,
	'lista_grupo'=>$lista_grupo,
	'lista_un'=>$lista_un,
	'lista_fabrica'=>$lista_fabrica,
	'lista_oracle'=>$lista_oracle,
	'paises_activos'=>$paises_activos,
	'lista_ins'=>$lista_ins,
	'lista_bodegas'=>$lista_bodegas,
	's'=>$s,
)); 

	?>
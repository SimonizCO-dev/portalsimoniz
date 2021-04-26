<?php
/* @var $this IDoctoMovtoController */
/* @var $model IDoctoMovto */

//para combos de lineas
$lista_bodegas = CHtml::listData($bodegas, 'Id', 'Descripcion');

$modelo_docto = IDocto::model()->findByPk($id);

?>

<script type="text/javascript">

function add_item(){
    
    limp_div_msg();

	var form = $("#idocto-movto-form");

	//cabecera
	var tipo = <?php echo $modelo_docto->Id_Tipo_Docto; ?>;
	var id_docto = <?php echo $modelo_docto->Id; ?>;

	//detalle
    var id_reg = <?php echo $model->Id ?>;
	var item = $('#IDoctoMovto_Id_Item').val();
	var bodega_origen = $('#IDoctoMovto_Id_Bodega_Org').val();
	var bodega_destino = $('#IDoctoMovto_Id_Bodega_Dst').val();
	var cant = $('#IDoctoMovto_Cantidad').val();
	var vlr = $('#IDoctoMovto_Vlr_Unit_Item').val();

	if(tipo == <?php echo Yii::app()->params->ent; ?>){
		//entrada
		if( item != "" && bodega_destino != "" && cant != "" && vlr != ""){

			var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: null, bodega_destino: bodega_destino}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){

                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega destino).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        //se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }

                }
            });

		}else{
	        if(item == ""){
	            $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
	            $('#IDoctoMovto_Id_Item_em_').show(); 
	        }
	        if(bodega_destino == ""){
	            $('#IDoctoMovto_Id_Bodega_Dst_em_').html('Bodega destino es requerido.');
	            $('#IDoctoMovto_Id_Bodega_Dst_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
	            $('#IDoctoMovto_Cantidad_em_').show();    
	        }
	        if(vlr == ""){
	            $('#IDoctoMovto_Vlr_Unit_Item_em_').html('Vlr. unitario es requerido.');
	            $('#IDoctoMovto_Vlr_Unit_Item_em_').show();    
	        }  
		}

	}

	if(tipo == <?php echo Yii::app()->params->sal; ?>){
		//salida
		if(item != "" && bodega_origen != "" && cant != ""){
			
			var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: bodega_origen, bodega_destino: null}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){
                   
                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        $//se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }

                }
            });

		}else{
	        if(item == ""){
	            $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
	            $('#IDoctoMovto_Id_Item_em_').show(); 
	        }
	        if(bodega_origen == ""){
	            $('#IDoctoMovto_Id_Bodega_Org_em_').html('Bodega origen es requerido.');
	            $('#IDoctoMovto_Id_Bodega_Org_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
	            $('#IDoctoMovto_Cantidad_em_').show();    
	        }
		}

	}

	if(tipo == <?php echo Yii::app()->params->trb; ?>){
		//tranferencia
		if(item != "" && bodega_origen != "" && bodega_destino != "" && cant != ""){
			
			var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: bodega_origen, bodega_destino: bodega_destino}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){
                   
                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen - Bodega destino).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        //se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }
                }
            });	

		}else{
	        if(item == ""){
	            $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
	            $('#IDoctoMovto_Id_Item_em_').show(); 
	        }
	        if(bodega_origen == ""){
	            $('#IDoctoMovto_Id_Bodega_Org_em_').html('Bodega origen es requerido.');
	            $('#IDoctoMovto_Id_Bodega_Org_em_').show();    
	        }
	        if(bodega_destino == ""){
	            $('#IDoctoMovto_Id_Bodega_Dst_em_').html('Bodega destino es requerido.');
	            $('#IDoctoMovto_Id_Bodega_Dst_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
	            $('#IDoctoMovto_Cantidad_em_').show();    
	        }
		}
	}

	if(tipo == <?php echo Yii::app()->params->aje; ?>){
		//ajuste por entrada
		if(item != "" && bodega_destino != "" && cant != ""){
			
			var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: null, bodega_destino: bodega_destino}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){
                   
                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega destino).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        //se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }

                }
            });

		}else{
	        if(item == ""){
	            $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
	            $('#IDoctoMovto_Id_Item_em_').show(); 
	        }
	        if(bodega_destino == ""){
	            $('#IDoctoMovto_Id_Bodega_Dst_em_').html('Bodega destino es requerido.');
	            $('#IDoctoMovto_Id_Bodega_Dst_em_').show();    
	        }
	        if(cant == ""){
	            $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
	            $('#IDoctoMovto_Cantidad_em_').show();    
	        }
		}

	}

    if(tipo == <?php echo Yii::app()->params->ajs; ?>){
        //ajuste por salida
        if(item != "" && bodega_origen != "" && cant != ""){
            
            var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: bodega_origen, bodega_destino: null}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){
                   
                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        //se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }

                }
            });

        }else{
            if(item == ""){
                $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
                $('#IDoctoMovto_Id_Item_em_').show(); 
            }
            if(bodega_origen == ""){
                $('#IDoctoMovto_Id_Bodega_Org_em_').html('Bodega origen es requerido.');
                $('#IDoctoMovto_Id_Bodega_Org_em_').show();    
            }
            if(cant == ""){
                $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
                $('#IDoctoMovto_Cantidad_em_').show();    
            }
        }

    }

    if(tipo == <?php echo Yii::app()->params->sad; ?>){
        //salida de dotación
        if(item != "" && bodega_origen != "" && cant != ""){
            
            var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: bodega_origen, bodega_destino: null}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){
                   
                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega origen).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        //se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }

                }
            });

        }else{
            if(item == ""){
                $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
                $('#IDoctoMovto_Id_Item_em_').show(); 
            }
            if(bodega_origen == ""){
                $('#IDoctoMovto_Id_Bodega_Org_em_').html('Bodega origen es requerido.');
                $('#IDoctoMovto_Id_Bodega_Org_em_').show();    
            }
            if(cant == ""){
                $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
                $('#IDoctoMovto_Cantidad_em_').show();    
            }
        }

    }

    if(tipo == <?php echo Yii::app()->params->dev; ?>){
        //devolución
        if(item != "" && bodega_destino != "" && cant != ""){
            
            var data = {docto: id_docto, id_reg: id_reg, item: item, bodega_origen: null, bodega_destino: bodega_destino}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('iDoctoMovto/verificarduplicidad'); ?>",
                data: data,
                success: function(response){
                   
                    if(response == 0){
                        //se encontro un registro identico en item / bodega
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Ya existe un detalle con la misma combinación (Item - Bodega destino).');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                    if(response == 1){
                        //si esta disponible la cantidad solicitada
                        //se envia el form
                        $(".ajax-loader").fadeIn('fast');
                        form.submit();
                    }

                }
            });

        }else{
            if(item == ""){
                $('#IDoctoMovto_Id_Item_em_').html('Item es requerido.');
                $('#IDoctoMovto_Id_Item_em_').show(); 
            }
            if(bodega_destino == ""){
                $('#IDoctoMovto_Id_Bodega_Dst_em_').html('Bodega destino es requerido.');
                $('#IDoctoMovto_Id_Bodega_Dst_em_').show();    
            }
            if(cant == ""){
                $('#IDoctoMovto_Cantidad_em_').html('Cantidad es requerido.');
                $('#IDoctoMovto_Cantidad_em_').show();    
            }
        }

    }
}

$("#IDoctoMovto_Id_Item").change( function (){
	limp_div_msg();  
});

$("#IDoctoMovto_Id_Bodega_Dst").change( function (){
    limp_div_msg();
});

$("#IDoctoMovto_Id_Bodega_Org").change( function (){

	var tipo = <?php echo $modelo_docto->Id_Tipo_Docto; ?>;

	limp_div_msg();

	bod = $(this).val();
	
	$('#IDoctoMovto_Id_Bodega_Dst').find('option').remove();
	$('#IDoctoMovto_Id_Bodega_Dst').val('').trigger('change');
	$("#IDoctoMovto_Id_Bodega_Dst").append('<option value=""></option>');
	$('#IDoctoMovto_Id_Bodega_Dst').val('').trigger('change');

	if(bod != ""){

		if(tipo == <?php echo Yii::app()->params->trb; ?>){

			$("#IDoctoMovto_Id_Bodega_Org option").each(function(name, val) { 
	 	
		 		opt_val = val.value;
				opt_text = val.text;

				if(bod != opt_val && opt_val !=  ""){
					$("#IDoctoMovto_Id_Bodega_Dst").append('<option value='+opt_val+'>'+opt_text+'</option>');
				}

	 	 	});

		}
	 	
	}
   
});

//funcion para cargar bodegas de origen y destino
$(function () {

    var tipo = <?php echo $modelo_docto->Id_Tipo_Docto; ?>;

    if(tipo == <?php echo Yii::app()->params->trb; ?>){

    var bod = $('#IDoctoMovto_Id_Bodega_Org').val();

    $('#IDoctoMovto_Id_Bodega_Dst').find('option').remove();
    $('#IDoctoMovto_Id_Bodega_Dst').val('').trigger('change');
    $("#IDoctoMovto_Id_Bodega_Dst").append('<option value=""></option>');
    $('#IDoctoMovto_Id_Bodega_Dst').val('').trigger('change');

    $("#IDoctoMovto_Id_Bodega_Org option").each(function(name, val) { 

        opt_val = val.value;
        opt_text = val.text;

        if(bod != opt_val && opt_val !=  ""){
            $("#IDoctoMovto_Id_Bodega_Dst").append('<option value='+opt_val+'>'+opt_text+'</option>');
        }

    });

    $('#IDoctoMovto_Id_Bodega_Dst').val(<?php echo $model->Id_Bodega_Dst; ?>).trigger('change');

    }

});

</script>

<div id="div_mensaje" style="display: none;">
</div>

<h4>Actualización de detalle</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'id'=>$id, 'lista_bodegas'=>$lista_bodegas, 'modelo_docto'=>$modelo_docto)); ?>
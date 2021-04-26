<?php
/* @var $this FactItemContController */
/* @var $model FactItemCont */

//para combos de items 
$lista_items = CHtml::listData($items, 'Id_Item', 'IdItem_Item');

?>

<script type="text/javascript">

$(function() {

	$("body").on("click", ".delete", function (e) {
	    
	  $(this).parent().parent("tr").remove();
	  var cant = $(".tr_items").length;
	  
	  if(cant == 0){
	      $('#contenido').html('');
	      $('#btn_save').hide();  
	  }else{
	      $('#btn_save').show();  
	  }

	  cal_total_fact();

	});

	$("#FactItemCont_Numero_Factura").change(function() {
	  
	  limp_div_msg();

	  var val = $("#FactItemCont_Numero_Factura").val();
	  if(val == ""){
	    
	    $('#error_num_fact').html('N° de fact. es requerido.');
	    $('#error_num_fact').show();
	    $('#btn_save').hide();

	  }else{

	    $('#error_num_fact').html('');
	    $('#error_num_fact').hide();

	    var cant = $(".tr_items").length;
	  
	    if(cant > 0){
	      $('#btn_save').show();  
	    } 
	  }

	});

	$("#FactItemCont_Fecha_Factura").change(function() {
	  
	  limp_div_msg();

	  var val = $("#FactItemCont_Fecha_Factura").val();
	  if(val == ""){
	    
	    $('#error_fec_fact').html('Fecha de fact. es requerido.');
	    $('#error_fec_fact').show();
	    $('#btn_save').hide(); 

	  }else{

	    $('#error_fec_fact').html('');
	    $('#error_fec_fact').hide();

	    var cant = $(".tr_items").length;
	  
	    if(cant > 0){
	      $('#btn_save').show();  
	    } 
	  }

	});

	$("#FactItemCont_Tasa_Cambio").change(function() {
  
      limp_div_msg();

	  var val = $("#FactItemCont_Tasa_Cambio").val();
	  if(val == ""){
	    $('#error_tasa_cambio').html('Tasa de cambio es requerido.');
	    $('#error_tasa_cambio').show();

	    $("input.items").each(function() {
	      var item = $(this).val();
	      $("#vt_"+item).val('');
	    });

	    $("#vlr_total").html('-');
	    $('#btn_save').hide(); 
	  }else{

	    if(val >= 0){
	      $('#error_tasa_cambio').html('');
	      $('#error_tasa_cambio').hide();

	      var cant = $(".tr_items").length;
	    
	      if(cant > 0){
	        
	        $("input.items").each(function() {
	          var item = $(this).val();
	          cal_total_x_item(item);
	        });

	        $('#btn_save').show();  
	      } 
	    }else{
	      $('#error_tasa_cambio').html('Tasa de cambio debe ser igual o mayor a 0.');
	      $('#error_tasa_cambio').show();

	      $("input.items").each(function() {
	        var item = $(this).val();
	        $("#vt_"+item).val('');
	      });

	      $("#vlr_total").html('-');
	      $('#btn_save').hide(); 
	    }

	  }

	});

});

function add_item(){

  var id_contrato = $('#FactItemCont_Id_Contrato').val();
  var n_factura = $('#FactItemCont_Numero_Factura').val();
  var f_factura = $('#FactItemCont_Fecha_Factura').val();
  var tasa_cambio = $('#FactItemCont_Tasa_Cambio').val();
  var id_fact = 0;
  var item = $('#FactItemCont_item').val();

  if(n_factura != "" && f_factura != "" && item != "" && tasa_cambio != "" && tasa_cambio >= 0){

    limp_div_msg();

    var div_contenido = $('#contenido');
    var tr = $("#tr_"+item).length;

    if(!tr){

      var cant = $(".tr_items").length;

      if(cant == 0){
        div_contenido.append('<table class="table table-sm table-hover" id="table_item" style="font-size:11px !important;"><thead><tr><th>Item</th><th>Cant.</th><th>Vlr. unit.</th><th>Moneda</th><th>Iva</th><th>Vlr. total</th></tr></thead><tbody></tbody></table>');
      }

      $('#btn_save').show();

      var data = {item: item}
      $.ajax({ 
        type: "POST", 
        url: "<?php echo Yii::app()->createUrl('factItemCont/infoitem'); ?>",
        data: data,
        dataType: 'json',
        success: function(response){

          var i = response.item;
          var desc_item = response.desc_item;
          var cant = response.cant;
          var vlr_unit = response.vlr_unit;
          var iva = response.iva;
          var id_moneda = response.id_moneda;
          var moneda = response.moneda;

          if(id_moneda == <?php echo Yii::app()->params->moneda_USD ?>){

            if(iva == 0){
              
              var vlr_total = (vlr_unit * tasa_cambio) * cant;

            }else{

              var vlr_base = (vlr_unit * tasa_cambio) * cant;
              var vlr_iva = ((vlr_base * iva) / 100);
              var vlr_total = vlr_base + vlr_iva;

            }

          }else{

            if(iva == 0){

              var vlr_total = vlr_unit * cant;

            }else{

              var vlr_base = vlr_unit * cant;
              var vlr_iva = ((vlr_base * iva) / 100);
              var vlr_total = vlr_base + vlr_iva;

            }

          }

          var tabla = $('#table_item');

          tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'">'+desc_item+'</td><td><input type="number" id="cant_'+i+'" value="'+cant+'" onchange="cal_total_x_item('+i+');"></td><td><input type="number" id="vu_'+i+'" value="'+vlr_unit+'" onchange="cal_total_x_item('+i+');" step = "0.01"></td><td><input type="hidden" id="moneda_'+i+'" value="'+id_moneda+'">'+moneda+'</td><td><input type="number" id="iva_'+i+'" value="'+iva+'" onchange="cal_total_x_item('+i+');"></td><td><input type="text" id="vt_'+i+'" value="'+vlr_total.toFixed(2)+'" disabled></td><td><button type="button" class="btn btn-danger btn-xs delete"><i class="fa fa-trash" aria-hidden="true"></i> </button></td></tr>');

          cal_total_fact();
        }
      });

      $('#FactItemCont_item').val('').trigger('change');

    }else{
      $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
      $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Este item ya esta asociado a la factura.');
      $("#div_mensaje").show();
    }

  }else{

    if(n_factura == ""){
      $('#error_num_fact').html('N° de fact. es requerido.');
      $('#error_num_fact').show(); 
    }

    if(f_factura == ""){
      $('#error_fec_fact').html('Fecha de fact. es requerido.');
      $('#error_fec_fact').show();    
    }

    if(tasa_cambio == ""){
      $('#error_tasa_cambio').html('Tasa de cambio es requerido.');
      $('#error_tasa_cambio').show();    
    }

    if(item == ""){
      $('#FactItemCont_item_em_').html('Item es requerido.');
      $('#FactItemCont_item_em_').show();    
    }
  }
}


function cal_total_fact(){

  $(".ajax-loader").fadeIn('fast');

  var vlr_total = 0;
  
  $("input.items").each(function() {

    var item = $(this).val();
    var vlr_t = parseFloat($('#vt_'+item).val());
    vlr_total += vlr_t;

  });

  $("#vlr_total").html(formatNumber(vlr_total)+' COP');
  $(".ajax-loader").fadeOut('fast'); 
}

function cal_total_x_item(item){	

  $(".ajax-loader").fadeIn('fast');
  
  var cant = $("#cant_"+item).val();
  var vlr_unit = $("#vu_"+item).val();
  var iva = $("#iva_"+item).val();
  var id_moneda = $("#moneda_"+item).val();
  var tasa_cambio = $("#FactItemCont_Tasa_Cambio").val();
  
  if(cant != "" && vlr_unit != "" && iva != "" && tasa_cambio != ""){
    
    if(cant > 0 && vlr_unit > 0 && vlr_unit > 0 && iva >= 0 && tasa_cambio >= 0){
      if(id_moneda == <?php echo Yii::app()->params->moneda_USD ?>){

        if(iva == 0){

          var vlr_total = (vlr_unit * tasa_cambio) * cant;

        }else{

          var vlr_base = (vlr_unit * tasa_cambio) * cant;
          var vlr_iva = ((vlr_base * iva) / 100);
          var vlr_total = vlr_base + vlr_iva;

        }

      }else{

        if(iva == 0){

          var vlr_total = vlr_unit * cant;

        }else{

          var vlr_base = vlr_unit * cant;
          var vlr_iva = ((vlr_base * iva) / 100);
          var vlr_total = vlr_base + vlr_iva;

        }

      }

      $("#vt_"+item).val(vlr_total.toFixed(2));
      $('#btn_save').show();

      cal_total_fact();
      limp_div_msg();

    }else{

      $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
      $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Hay item(s) configurados con valores invalidos y/o vacios.');
      $("#div_mensaje").show();
 
      $("#vt_"+item).val('');
      $("#vlr_total").html('-');
      $('#btn_save').hide();  
    }

  }else{

    $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
    $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Hay item(s) configurados con valores invalidos y/o vacios.');
    $("#div_mensaje").show();

    $("#vt_"+item).val('');
    $("#vlr_total").html('-');
    $('#btn_save').hide(); 
  }

  $(".ajax-loader").fadeOut('fast');

}

function valida_opciones(){

  var id_contrato = $('#FactItemCont_Id_Contrato').val();
  var n_factura = $('#FactItemCont_Numero_Factura').val();
  var f_factura = $('#FactItemCont_Fecha_Factura').val();
  var id_fact = 0;

  if(n_factura != "" && f_factura != ""){

    var data = {id_contrato: id_contrato, n_factura: n_factura, id_fact: 0}
    $.ajax({ 
      type: "POST", 
      url: "<?php echo Yii::app()->createUrl('factItemCont/existfact'); ?>",
      data: data,
      success: function(response){

        if(response == 1){

            $('#btn_save').hide();
            limp_div_msg();
            $(".ajax-loader").fadeIn('fast');
  
            $('#FactItemCont_cad_item').val('');
            $('#FactItemCont_cad_cant').val('');
            $('#FactItemCont_cad_vlr_u').val('');
            $('#FactItemCont_cad_iva').val('');
                
            var item_selected = ''; 
            var cant_selected = '';
            var vlr_u_selected = '';
            var iva_selected = '';

            $("input.items").each(function() {

              var item = $(this).val();
              var cant = $('#cant_'+item).val();
              var vlr_u = parseInt($('#vu_'+item).val());
              var iva = parseInt($('#iva_'+item).val());
             
              item_selected += item+','; 
              cant_selected += cant+','; 
              vlr_u_selected += vlr_u+',';  
              iva_selected += iva+','; 
              
            });

            var cadena_item = item_selected.slice(0,-1);
            var cadena_cant = cant_selected.slice(0,-1);
            var cadena_vlr_u = vlr_u_selected.slice(0,-1);
            var cadena_iva = iva_selected.slice(0,-1);
            
            $('#FactItemCont_cad_item').val(cadena_item);
            $('#FactItemCont_cad_cant').val(cadena_cant);
            $('#FactItemCont_cad_vlr_u').val(cadena_vlr_u);
            $('#FactItemCont_cad_iva').val(cadena_iva);

            var form = $("#fact-item-cont-form");
            form.submit();
               
        }else{

          $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
          $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Esta factura ya se encuentra asociada al contrato.');
          $("#div_mensaje").show();
        }
      }
    });
  }
}

</script>

<div id="div_mensaje" style="display: none;"></div>

<h4>Asociando factura a contrato</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'c'=>$c, 'lista_items'=>$lista_items)); ?>
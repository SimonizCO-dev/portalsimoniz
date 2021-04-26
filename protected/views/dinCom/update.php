<?php
/* @var $this DinComController */
/* @var $model DinCom */

?>

<script type="text/javascript">

$(function() {


	viewfieldsxtipo(<?php echo $model->Tipo ?>);

	$("#valida_form").click(function() {
    	
		var form = $("#din-com-form");
		var settings = form.data('settings');

		var tipo = $('#DinCom_Tipo').val();

      	settings.submitting = true ;
      	$.fn.yiiactiveform.validate(form, function(messages) {
          	if($.isEmptyObject(messages)) {
              	$.each(settings.attributes, function () {
                 	$.fn.yiiactiveform.updateInput(this,messages,form); 
             	});

	     		form.submit();
				loadershow();
             	
          	} else {
              	settings = form.data('settings'),
              	$.each(settings.attributes, function () {
                 	$.fn.yiiactiveform.updateInput(this,messages,form); 
              	});
              	settings.submitting = false ;

              	show_errors(tipo);
          	}
      	});

  	});

});

function viewfieldsxtipo(value){

	if(value == 1){
		//ITEM

		//manejo de divs
		$('#div_item').show();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 2){
		//CLIENTE

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').show();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 3){
		//CRITERIO CLIENTE

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').show();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 4){
		//CRITERIO ITEM

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').show();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
	}

	if(value == 5){
		//OBSEQUIO

		//manejo de divs
		$('#div_item').show();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').hide();
		$('#div_cant_max').hide();
		$('#div_cant_ped').show();
		$('#div_cant_obs').show();
		$('#div_vlr_min').hide();
		$('#div_vlr_max').hide();
		$('#div_desc').hide();

		//limpieza de campos
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
	}

	if(value == 6){
		//LISTA PRECIOS

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').show();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 7){
		//CO

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').show();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 8){
		//ITEM / CLIENTE

		//manejo de divs
		$('#div_item').show();
		$('#div_cliente').show();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 9){
		//ITEM / CRITERIO CLIENTE

		//manejo de divs
		$('#div_item').show();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').show();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 10){
		//ITEM / LISTA DE PRECIOS

		//manejo de divs
		$('#div_item').show();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').show();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 11){
		//ITEM / CO

		//manejo de divs
		$('#div_item').show();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').show();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 12){
		//CRITERIO ITEM / CRITERIO CLIENTE

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').show();
		$('#div_p_cri_item').show();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
	}

	if(value == 13){
		//CRITERIO ITEM / CLIENTE

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').show();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').show();
		$('#div_l_precios').hide();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
	}

	if(value == 14){
		//CRITERIO ITEM / LISTA DE PRECIOS

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').show();
		$('#div_l_precios').show();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
	}

	if(value == 15){
		//CRITERIO ITEM / CO

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').show();
		$('#div_l_precios').hide();
		$('#div_co').show();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
	}

	if(value == 16){
		//CRITERIO CLIENTE / LISTA DE PRECIOS

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').show();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').show();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 17){
		//CRITERIO CLIENTE / CO

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').show();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').show();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 18){
		//CLIENTE / LISTA DE PRECIOS

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').show();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').show();
		$('#div_co').hide();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_req').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_CO').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 19){
		//CLIENTE / CO

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').show();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').hide();
		$('#div_co').show();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Lista_Precios').val('').trigger('change');
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}

	if(value == 20){
		//LISTA DE PRECIOS / CO

		//manejo de divs
		$('#div_item').hide();
		$('#div_cliente').hide();
		$('#div_p_cri_cliente').hide();
		$('#div_p_cri_item').hide();
		$('#div_l_precios').show();
		$('#div_co').show();
		$('#div_cant_min').show();
		$('#div_cant_max').show();
		$('#div_cant_ped').hide();
		$('#div_cant_obs').hide();
		$('#div_vlr_min').show();
		$('#div_vlr_max').show();
		$('#div_desc').show();

		//limpieza de campos
		$('#DinCom_Item').val('').trigger('change');
    	$('#s2id_DinCom_Item span').html("");
    	$('#DinCom_Cliente').val('').trigger('change');
    	$('#s2id_DinCom_Cliente span').html("");
    	$('#DinCom_Cant_Ped').val('');
    	$('#DinCom_Cant_Obs').val('');
		$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
		$('#DinCom_Cad_Plan_Cliente').val('');
		$('#DinCom_Cad_Criterio_Cliente').val('');
		$('#contenido_criterios_cliente').html('');
		$('#DinCom_Id_Plan_Item').val('').trigger('change');
		$('#DinCom_Cad_Plan_Item').val('');
		$('#DinCom_Cad_Criterio_Item').val('');
		$('#contenido_criterios_item').html('');
	}
} 

</script>

<h4>Actualización de dinámica comercial</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'criterio_cliente'=>$criterio_cliente, 'criterio_item'=>$criterio_item)); ?>
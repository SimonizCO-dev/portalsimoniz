<?php
/* @var $this DinComController */
/* @var $model DinCom */

?>

<script type="text/javascript">

$(function() {

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

	$("#DinCom_Fecha_Inicio").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
	var minDate = new Date(selected.date.valueOf());
	$('#DinCom_Fecha_Fin').datepicker('setStartDate', minDate);
	});

	$("#DinCom_Fecha_Fin").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
	var maxDate = new Date(selected.date.valueOf());
	$('#DinCom_Fecha_Inicio').datepicker('setEndDate', maxDate);
	});

    //criterios cliente
    $("#DinCom_Id_Plan_Cliente").change(function() {
  		var plan = $(this).val();
	  	if(plan != ""){
  			var data = {plan: plan}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('DinCom/GetCriteriosPlanCliente'); ?>",
				data: data,
				dataType: 'json',
				success: function(data){
					$('#DinCom_Id_Criterio_Cliente').val('').trigger('change');
				   	$("#DinCom_Id_Criterio_Cliente").html('');
				  	$.each(data, function(i,item){
			      		$("#DinCom_Id_Criterio_Cliente").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
				  	});
				  	$("#div_cri_cli").show();
				  	$('#DinCom_Id_Criterio_Cliente').val('').trigger('change');
				  	$("#div_btn_cri_cli").show();
				}
			});
	 	}else{
      		$('#DinCom_Id_Criterio_Cliente').val('').trigger('change');
      		$("#div_cri_cli").hide();  
      		$("#div_btn_cri_cli").hide(); 
	 	}

	});

    //criterios item
	$("#DinCom_Id_Plan_Item").change(function() {
  		var plan = $(this).val();
	  	if(plan != ""){
  			var data = {plan: plan}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('DinCom/GetCriteriosPlanItem'); ?>",
				data: data,
				dataType: 'json',
				success: function(data){
					$('#DinCom_Id_Criterio_Item').val('').trigger('change');
				   	$("#DinCom_Id_Criterio_Item").html('');
				  	$.each(data, function(i,item){
			      		$("#DinCom_Id_Criterio_Item").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
				  	});
				  	$("#div_cri_item").show();
				  	$("#div_btn_cri_item").show(); 
				}
			});
	 	}else{
      		$('#DinCom_Id_Criterio_Item').val('').trigger('change');
      		$("#div_cri_item").hide();
      		$("#div_btn_cri_item").hide();       
	 	}

	});


	$('#DinCom_Tipo').change(function() {

		//limpiar errores
		clean_errors();

		var value = $(this).val();

		if(value == ''){
			//VACIO

			//manejo de divs
			$('#div_item').hide();
			$('#div_cliente').hide();
			$('#div_p_cri_cliente').hide();
			$('#div_p_cri_item').hide();
			$('#div_l_precios').hide();
			$('#div_co').hide();
			$('#div_cant_min').hide();
			$('#div_cant_max').hide();
			$('#div_cant_ped').hide();
			$('#div_cant_obs').hide();
			$('#div_vlr_min').hide();
			$('#div_vlr_max').hide();
			$('#div_desc').hide();
			$('#contenido_criterios_cliente').html('');
			$('#contenido_criterios_item').html('');

			//limpieza de campos
			$('#DinCom_Item').val('').trigger('change');
	    	$('#s2id_DinCom_Item span').html("");
	    	$('#DinCom_Cliente').val('').trigger('change');
	    	$('#s2id_DinCom_Cliente span').html("");
	    	$('#DinCom_Lista_Precios').val('').trigger('change');
	    	$('#DinCom_CO').val('').trigger('change');
	    	$('#DinCom_Cant_Min').val('');
	    	$('#DinCom_Cant_Max').val('');
	    	$('#DinCom_Cant_Ped').val('');
	    	$('#DinCom_Cant_Obs').val('');
			$('#DinCom_Vlr_Min').val('');
			$('#DinCom_Vlr_Max').val('');
			$('#DinCom_Descuento').val('');
			$('#DinCom_Id_Plan_Cliente').val('').trigger('change');
			$('#DinCom_Cad_Plan_Cliente').val('');
			$('#DinCom_Cad_Criterio_Cliente').val('');
			$('#contenido_criterios_cliente').html('');
			$('#DinCom_Id_Plan_Item').val('').trigger('change');
			$('#DinCom_Cad_Plan_Item').val('');
			$('#DinCom_Cad_Criterio_Item').val('');
			$('#contenido_criterios_item').html('');

		}


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
	   
	});

	//campos onchange

    $("#DinCom_Item").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Item').html('');
            $('#Error_Item').hide(); 	
	 	}else{
      		$('#Error_Item').html('Item es requerido.');
            $('#Error_Item').show(); 	 
	 	}
	});

	$("#DinCom_Cliente").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Cliente').html('');
            $('#Error_Cliente').hide(); 	
	 	}else{
      		$('#Error_Cliente').html('Cliente es requerido.');
            $('#Error_Cliente').show(); 	 
	 	}
	});

	$("#DinCom_Lista_Precios").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Lista_Precios').html('');
            $('#Error_Lista_Precios').hide(); 	
	 	}else{
      		$('#Error_Lista_Precios').html('Lista de precios es requerido.');
            $('#Error_Lista_Precios').show(); 	 
	 	}
	});

	$("#DinCom_CO").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_CO').html('');
            $('#Error_CO').hide(); 	
	 	}else{
      		$('#Error_CO').html('CO es requerido.');
            $('#Error_CO').show(); 	 
	 	}
	});

	$("#DinCom_Cant_Ped").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Cant_Ped').html('');
            $('#Error_Cant_Ped').hide(); 	
	 	}else{
      		$('#Error_Cant_Ped').html('Cant. Ped. es requerido.');
            $('#Error_Cant_Ped').show(); 	 
	 	}
	});

	$("#DinCom_Cant_Obs").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Cant_Obs').html('');
            $('#Error_Cant_Obs').hide(); 	
	 	}else{
      		$('#Error_Cant_Obs').html('Cant. Obsequio. es requerido.');
            $('#Error_Cant_Obs').show(); 	 
	 	}
	});

	$("#DinCom_Descuento").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Descuento').html('');
            $('#Error_Descuento').hide(); 	
	 	}else{
      		$('#Error_Descuento').html('Descuento es requerido.');
            $('#Error_Descuento').show(); 	 
	 	}
	});

	$("#DinCom_Id_Plan_Cliente").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Id_Plan_Cliente').html('');
            $('#Error_Id_Plan_Cliente').hide(); 	
	 	}else{
      		$('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
		    $('#Error_Id_Plan_Cliente').show(); 	 
	 	}
	});

	$("#DinCom_Id_Criterio_Item").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
	  		$('#Error_Id_Plan_Item').html('');
            $('#Error_Id_Plan_Item').hide(); 
  			$('#Error_Id_Criterio_Item').html('');
            $('#Error_Id_Criterio_Item').hide(); 	
	 	}else{
      		$('#Error_Id_Criterio_Item').html('Criterio item es requerido.');
		    $('#Error_Id_Criterio_Item').show(); 	 
	 	}
	});

	$("#DinCom_Id_Plan_Item").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
  			$('#Error_Id_Plan_Item').html('');
            $('#Error_Id_Plan_Item').hide(); 	
	 	}else{
      		$('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
		    $('#Error_Id_Plan_Item').show(); 	 
	 	}
	});

	$("#DinCom_Id_Criterio_Cliente").change(function() {
  		var val = $(this).val();
	  	if(val != ""){
	  		$('#Error_Id_Plan_Cliente').html('');
            $('#Error_Id_Plan_Cliente').hide(); 
  			$('#Error_Id_Criterio_Cliente').html('');
            $('#Error_Id_Criterio_Cliente').hide(); 	
	 	}else{
      		$('#Error_Id_Criterio_Cliente').html('Criterio cliente es requerido.');
		    $('#Error_Id_Criterio_Cliente').show(); 	 
	 	}
	});

	//fin campos onchange

	$("#valida_form").click(function() {
    	
		var form = $("#din-com-form");
		var settings = form.data('settings');

		var tipo = $('#DinCom_Tipo').val();

		//limpiar errores
		clean_errors();

		//campos de form y valores
		var item = $('#DinCom_Item').val();
		var cliente = $('#DinCom_Cliente').val();
		var lista_precios = $('#DinCom_Lista_Precios').val();
		var co = $('#DinCom_CO').val();
		var cant_min = $('#DinCom_Cant_Min').val();
		var cant_max = $('#DinCom_Cant_Max').val();
		var cant_ped = $('#DinCom_Cant_Ped').val();
		var cant_obs = $('#DinCom_Cant_Obs').val();
		var vlr_min = $('#DinCom_Vlr_Min').val();
		var vlr_max = $('#DinCom_Vlr_Max').val();
		var descuento = $('#DinCom_Descuento').val();
		var planes_cliente = $('#DinCom_Cad_Plan_Cliente').val();
		var criterio_cliente = $('#DinCom_Cad_Criterio_Cliente').val();
		var planes_item = $('#DinCom_Cad_Plan_Item').val();
		var criterio_item = $('#DinCom_Cad_Criterio_Item').val();

		if(tipo == 1){
			//ITEM
		    if(item != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(item == ""){
		            $('#Error_Item').html('Item es requerido.');
		            $('#Error_Item').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 2){
			//CLIENTE
		    if(cliente != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(cliente == ""){
		            $('#Error_Cliente').html('Cliente es requerido.');
		            $('#Error_Cliente').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 3){
			//CRITERIO CLIENTE
			if(criterio_cliente != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_cliente == ""){
		            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Cliente').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 4){
			//CRITERIO ITEM
			if(criterio_item != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_item == ""){
		            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Item').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 5){
			//OBSEQUIO
			if(item != "" && cant_ped != "" && cant_obs != ""){
		        $valid = 1;
		    }else{
		    	if(item == ""){
		            $('#Error_Item').html('Item es requerido.');
		            $('#Error_Item').show(); 
		        }

		        if(cant_ped == ""){
		            $('#Error_Cant_Ped').html('Cant. Ped. es requerido.');
		            $('#Error_Cant_Ped').show(); 
		        }

		        if(cant_obs == ""){
		            $('#Error_Cant_Obs').html('Cant. Obsequio es requerido.');
		            $('#Error_Cant_Obs').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 6){
			//LISTA PRECIOS
			if(lista_precios != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(lista_precios == ""){
		            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
		            $('#Error_Lista_Precios').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 7){
			//CO
			if(co != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(co == ""){
		            $('#Error_CO').html('CO es requerido.');
		            $('#Error_CO').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 8){
			//ITEM / CLIENTE
			if(item != "" && cliente != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(item == ""){
		            $('#Error_Item').html('Item es requerido.');
		            $('#Error_Item').show(); 
		        }

		        if(cliente == ""){
		            $('#Error_Cliente').html('Cliente es requerido.');
		            $('#Error_Cliente').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 9){
			//ITEM / CRITERIO CLIENTE
			if(item != "" && criterio_cliente != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(item == ""){
		            $('#Error_Item').html('Item es requerido.');
		            $('#Error_Item').show(); 
		        }

		        if(criterio_cliente == ""){
		            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Cliente').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 10){
			//ITEM / LISTA DE PRECIOS
			if(item != "" && lista_precios != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(item == ""){
		            $('#Error_Item').html('Item es requerido.');
		            $('#Error_Item').show(); 
		        }

		        if(lista_precios == ""){
		            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
		            $('#Error_Lista_Precios').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 11){
			//ITEM / CO
			if(item != "" && co != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(item == ""){
		            $('#Error_Item').html('Item es requerido.');
		            $('#Error_Item').show(); 
		        }

		        if(co == ""){
		            $('#Error_CO').html('CO es requerido.');
		            $('#Error_CO').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 12){
			//CRITERIO ITEM / CRITERIO CLIENTE
			if(criterio_cliente != "" && criterio_item != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_cliente == ""){
		            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Cliente').show(); 
		        }

		        if(criterio_item == ""){
		            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Item').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 13){
			//CRITERIO ITEM / CLIENTE
			if(criterio_item != "" && cliente != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_item == ""){
		            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Item').show(); 
		        }

		        if(cliente == ""){
		            $('#Error_Cliente').html('Cliente es requerido.');
		            $('#Error_Cliente').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 14){
			//CRITERIO ITEM / LISTA DE PRECIOS
			if(criterio_item != "" && lista_precios != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_item == ""){
		            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Item').show(); 
		        }

		        if(lista_precios == ""){
		            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
		            $('#Error_Lista_Precios').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 15){
			//CRITERIO ITEM / CO
			if(criterio_item != "" && co != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_item == ""){
		            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Item').show(); 
		        }

		        if(co == ""){
		            $('#Error_CO').html('CO es requerido.');
		            $('#Error_CO').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 16){
			//CRITERIO CLIENTE / LISTA DE PRECIOS
			if(criterio_cliente != "" && lista_precios != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_cliente == ""){
		            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Cliente').show(); 
		        }

		        if(lista_precios == ""){
		            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
		            $('#Error_Lista_Precios').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 17){
			//CRITERIO CLIENTE / CO
			if(criterio_cliente != "" && co != "" && descuento != ""){
		        $valid = 1;
		    }else{
		        if(criterio_cliente == ""){
		            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
		            $('#Error_Id_Plan_Cliente').show(); 
		        }

		        if(co == ""){
		            $('#Error_CO').html('CO es requerido.');
		            $('#Error_CO').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 18){
			//CLIENTE / LISTA DE PRECIOS
			if(cliente != "" && lista_precios != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(cliente == ""){
		            $('#Error_Cliente').html('Cliente es requerido.');
		            $('#Error_Cliente').show(); 
		        }

		        if(lista_precios == ""){
		            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
		            $('#Error_Lista_Precios').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 19){
			//CLIENTE / CO
			if(cliente != "" && co != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(cliente == ""){
		            $('#Error_Cliente').html('Cliente es requerido.');
		            $('#Error_Cliente').show(); 
		        }

		        if(co == ""){
		            $('#Error_CO').html('CO es requerido.');
		            $('#Error_CO').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

		if(tipo == 20){
			//LISTA DE PRECIOS / CO
			if(lista_precios != "" && co != "" && descuento != ""){
		        $valid = 1;
		    }else{
		    	if(lista_precios == ""){
		            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
		            $('#Error_Lista_Precios').show(); 
		        }

		        if(co == ""){
		            $('#Error_CO').html('CO es requerido.');
		            $('#Error_CO').show(); 
		        }

		        if(descuento == ""){
		            $('#Error_Descuento').html('% Descuento es requerido.');
		            $('#Error_Descuento').show(); 
		        }

		        $valid = 0;

		    }
		}

      	settings.submitting = true ;
      	$.fn.yiiactiveform.validate(form, function(messages) {
          	if($.isEmptyObject(messages)) {
              	$.each(settings.attributes, function () {
                 	$.fn.yiiactiveform.updateInput(this,messages,form); 
             	});

             	if($valid == 1){
             		form.submit();
      				loadershow();
             	}else{

					show_errors(tipo);
             	}
            	
      
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

function show_errors(tipo){

	var item = $('#DinCom_Item').val();
	var cliente = $('#DinCom_Cliente').val();
	var lista_precios = $('#DinCom_Lista_Precios').val();
	var co = $('#DinCom_CO').val();
	var cant_min = $('#DinCom_Cant_Min').val();
	var cant_max = $('#DinCom_Cant_Max').val();
	var cant_ped = $('#DinCom_Cant_Ped').val();
	var cant_obs = $('#DinCom_Cant_Obs').val();
	var vlr_min = $('#DinCom_Vlr_Min').val();
	var vlr_max = $('#DinCom_Vlr_Max').val();
	var descuento = $('#DinCom_Descuento').val();
	var planes_cliente = $('#DinCom_Cad_Plan_Cliente').val();
	var criterio_cliente = $('#DinCom_Cad_Criterio_Cliente').val();
	var planes_item = $('#DinCom_Cad_Plan_Item').val();
	var criterio_item = $('#DinCom_Cad_Criterio_Item').val();

	if(tipo == 1){
		//ITEM
	    if(item == "" || descuento == ""){
	    
	        if(item == ""){
	            $('#Error_Item').html('Item es requerido.');
	            $('#Error_Item').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	    
	}

	if(tipo == 2){
		//CLIENTE
	    if(cliente == "" || descuento == ""){
	    
	        if(cliente == ""){
	            $('#Error_Cliente').html('Cliente es requerido.');
	            $('#Error_Cliente').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 3){
		//CRITERIO CLIENTE
		if(criterio_cliente == "" || descuento == ""){
	        
	        if(criterio_cliente == ""){
	            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Cliente').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 4){
		//CRITERIO ITEM
		if(criterio_item == "" && descuento == ""){
	    
	        if(criterio_item == ""){
	            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Item').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 5){
		//OBSEQUIO
		if(item == "" && cant_ped == "" && cant_obs == ""){
	 
	    	if(item == ""){
	            $('#Error_Item').html('Item es requerido.');
	            $('#Error_Item').show(); 
	        }

	        if(cant_ped == ""){
	            $('#Error_Cant_Ped').html('Cant. Ped. es requerido.');
	            $('#Error_Cant_Ped').show(); 
	        }

	        if(cant_obs == ""){
	            $('#Error_Cant_Obs').html('Cant. Obsequio es requerido.');
	            $('#Error_Cant_Obs').show(); 
	        }

	    }
	}

	if(tipo == 6){
		//LISTA PRECIOS
		if(lista_precios == "" && descuento == ""){

	        if(lista_precios == ""){
	            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
	            $('#Error_Lista_Precios').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 7){
		//CO
		if(co == "" || descuento == ""){
	        
	        if(co == ""){
	            $('#Error_CO').html('CO es requerido.');
	            $('#Error_CO').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 8){
		//ITEM / CLIENTE
		if(item == "" || cliente == "" || descuento == ""){

	    	if(item == ""){
	            $('#Error_Item').html('Item es requerido.');
	            $('#Error_Item').show(); 
	        }

	        if(cliente == ""){
	            $('#Error_Cliente').html('Cliente es requerido.');
	            $('#Error_Cliente').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 9){
		//ITEM / CRITERIO CLIENTE
		if(item == "" || criterio_cliente == "" || descuento == ""){

	    	if(item == ""){
	            $('#Error_Item').html('Item es requerido.');
	            $('#Error_Item').show(); 
	        }

	        if(criterio_cliente == ""){
	            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Cliente').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 10){
		//ITEM / LISTA DE PRECIOS
		if(item == "" || lista_precios == "" || descuento != ""){
	        
	    	if(item == ""){
	            $('#Error_Item').html('Item es requerido.');
	            $('#Error_Item').show(); 
	        }

	        if(lista_precios == ""){
	            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
	            $('#Error_Lista_Precios').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 11){
		//ITEM / CO
		if(item == "" || co == "" || descuento == ""){
	        
	    	if(item == ""){
	            $('#Error_Item').html('Item es requerido.');
	            $('#Error_Item').show(); 
	        }

	        if(co == ""){
	            $('#Error_CO').html('CO es requerido.');
	            $('#Error_CO').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 12){
		//CRITERIO ITEM / CRITERIO CLIENTE
		if(criterio_cliente == "" || criterio_item == "" || descuento == ""){

	        if(criterio_cliente == ""){
	            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Cliente').show(); 
	        }

	        if(criterio_item == ""){
	            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Item').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 13){
		//CRITERIO ITEM / CLIENTE
		if(criterio_item == "" || cliente == "" || descuento == ""){

	        if(criterio_item == ""){
	            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Item').show(); 
	        }

	        if(cliente == ""){
	            $('#Error_Cliente').html('Cliente es requerido.');
	            $('#Error_Cliente').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 14){
		//CRITERIO ITEM / LISTA DE PRECIOS
		if(criterio_item == "" || lista_precios == "" || descuento == ""){

	        if(criterio_item == ""){
	            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Item').show(); 
	        }

	        if(lista_precios == ""){
	            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
	            $('#Error_Lista_Precios').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 15){
		//CRITERIO ITEM / CO
		if(criterio_item == "" || co == "" || descuento == ""){

	        if(criterio_item == ""){
	            $('#Error_Id_Plan_Item').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Item').show(); 
	        }

	        if(co == ""){
	            $('#Error_CO').html('CO es requerido.');
	            $('#Error_CO').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 16){
		//CRITERIO CLIENTE / LISTA DE PRECIOS
		if(criterio_cliente == "" || lista_precios == "" || descuento == ""){

	        if(criterio_cliente == ""){
	            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Cliente').show(); 
	        }

	        if(lista_precios == ""){
	            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
	            $('#Error_Lista_Precios').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 17){
		//CRITERIO CLIENTE / CO
		if(criterio_cliente == "" || co == "" || descuento == ""){
	        
	        if(criterio_cliente == ""){
	            $('#Error_Id_Plan_Cliente').html('Debe elegir por los menos 1 criterio.');
	            $('#Error_Id_Plan_Cliente').show(); 
	        }

	        if(co == ""){
	            $('#Error_CO').html('CO es requerido.');
	            $('#Error_CO').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 18){
		//CLIENTE / LISTA DE PRECIOS
		if(cliente == "" || lista_precios == "" || descuento == ""){
	        
	    	if(cliente == ""){
	            $('#Error_Cliente').html('Cliente es requerido.');
	            $('#Error_Cliente').show(); 
	        }

	        if(lista_precios == ""){
	            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
	            $('#Error_Lista_Precios').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 19){
		//CLIENTE / CO
		if(cliente == "" ||	co == "" || descuento == ""){

	    	if(cliente == ""){
	            $('#Error_Cliente').html('Cliente es requerido.');
	            $('#Error_Cliente').show(); 
	        }

	        if(co == ""){
	            $('#Error_CO').html('CO es requerido.');
	            $('#Error_CO').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}

	if(tipo == 20){
		//LISTA DE PRECIOS / CO
		if(lista_precios == "" || co == "" || descuento == ""){

	    	if(lista_precios == ""){
	            $('#Error_Lista_Precios').html('Lista de precios es requerido.');
	            $('#Error_Lista_Precios').show(); 
	        }

	        if(co == ""){
	            $('#Error_CO').html('CO es requerido.');
	            $('#Error_CO').show(); 
	        }

	        if(descuento == ""){
	            $('#Error_Descuento').html('% Descuento es requerido.');
	            $('#Error_Descuento').show(); 
	        }

	    }
	}
}

function clean_errors(){
	$('#Error_Item').html('');
	$('#Error_Item').hide();

	$('#DinCom_Cliente').html('');
	$('#DinCom_Cliente').hide(); 

	$('#Error_Lista_Precios').html('');
	$('#Error_Lista_Precios').hide(); 

	$('#Error_CO').html('');
	$('#Error_CO').hide(); 

	$('#Error_Cant_Min').html('');
	$('#Error_Cant_Min').hide(); 

	$('#Error_Cant_Max').html('');
	$('#Error_Cant_Max').hide(); 

	$('#Error_Cant_Ped').html('');
	$('#Error_Cant_Ped').hide(); 

	$('#Error_Cant_Obs').html('');
	$('#Error_Cant_Obs').hide(); 

	$('#Error_Vlr_Min').html('');
	$('#Error_Vlr_Min').hide(); 

	$('#Error_Vlr_Max').html('');
	$('#Error_Vlr_Max').hide(); 

	$('#Error_Descuento').html('');
	$('#Error_Descuento').hide(); 

	$('#Error_Id_Plan_Cliente').html('');
	$('#Error_Id_Plan_Cliente').hide(); 

	$('#Error_Id_Criterio_Cliente').html('');
	$('#Error_Id_Criterio_Cliente').hide(); 

	$('#Error_Cad_Criterio_Cliente').html('');
	$('#Error_Cad_Criterio_Cliente').hide(); 

	$('#Error_Id_Plan_Item').html('');
	$('#Error_Id_Plan_Item').hide(); 

	$('#Error_Id_Criterio_Item').html('');
	$('#Error_Id_Criterio_Item').hide(); 

	$('#Error_Cad_Criterio_Item').html('');
	$('#Error_Cad_Criterio_Item').hide(); 


}

function add_criterio_cliente(){

   //se obtienen los valores necesarios 
    var id_plan = $('#DinCom_Id_Plan_Cliente').val();
    var id_criterio = $('#DinCom_Id_Criterio_Cliente').val();
    var plan = $('#s2id_DinCom_Id_Plan_Cliente span').html();
    var criterio = $('#s2id_DinCom_Id_Criterio_Cliente span').html();

    $('#Error_Id_Plan_Cliente').html('');
    $('#Error_Id_Plan_Cliente').hide();
    $('#Error_Id_Criterio_Cliente').html('');
    $('#Error_Id_Criterio_Cliente').hide();
    
    if(id_plan != "" && id_plan != null && id_criterio != "" && id_criterio != null){

 		var div_contenido = $('#contenido_criterios_cliente');

        var cant_f = $(".tr_criterio_cliente").length;

        if(cant_f < 2){

            if(cant_f == 0){

            	//si no existe ningun criterio no hay necesidad de evaluar el primer registro

                div_contenido.append('<table class="table table-sm table-hover" id="table_criterios_cliente"><thead><tr><th colspan="3">Criterios cliente</th></tr><tr><th>Plan</th><th>Criterio</th><th></th></tr></thead><tbody></tbody></table>');

                var tabla = $('#table_criterios_cliente');

            	tabla.append('<tr class="tr_criterio_cliente" data-plan="'+id_plan+'" data-criterio="'+id_criterio+'"><td><p>'+plan+'</p></td><td><p>'+criterio+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete-criterio-cliente"><i class="fas fa-trash-alt"></i></button></td></tr>');

            	valida_opciones_criterio_cliente();

            }else{

            	//hay necesidad de evaluar
            	//2 planes max., 2 criterios x cada plan
            	//1. si hay más de dos planes agregados
            	//2. si hay mas de dos criterios

            	var array_plan_cliente = [];
            	$(".tr_criterio_cliente").each(function(index) {
            		var plan_cliente = $(this).attr("data-plan");
            		if(jQuery.inArray(plan_cliente, array_plan_cliente) === -1){
            			//no existe este plan en el arreglo
					  	array_plan_cliente.push(plan_cliente);
					}	
				});

            	var n_planes_cliente = array_plan_cliente.length;

            	//se revisa si el criterio actual ingresado esta entre el array de los registrados
            	if(jQuery.inArray(id_plan, array_plan_cliente) === -1) {
				    //no existe el plan, se revisa que el arreglo no cuente con el max de planes
			    	if(n_planes_cliente < 2){
			    		//se puede insertar el registro por que: hay menos de 2 planes 
			    		var tabla = $('#table_criterios_cliente');

		            	tabla.append('<tr class="tr_criterio_cliente" data-plan="'+id_plan+'" data-criterio="'+id_criterio+'"><td><p>'+plan+'</p></td><td><p>'+criterio+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete-criterio-cliente"><i class="fas fa-trash-alt"></i></button></td></tr>');

		            	valida_opciones_criterio_cliente();

			    	}else{
			    		//el plan agregado seria el 3ro en el arreglo y el max. permitido son 2
			    		$('#Error_Id_Plan_Cliente').html('Ya se han agregado el número de planes max. permitido (2).');
            			$('#Error_Id_Plan_Cliente').show();	
			    	}

				}else{
					//existe el plan, se revisa que este no tenga mas de dos criterios agregados

					var array_criterios_plan_cliente = [];
	            	$('.tr_criterio_cliente[data-plan="'+id_plan+'"]').each(function(index) {
	            		var criterio_plan_cliente = $(this).attr("data-criterio");
	            		if(jQuery.inArray(criterio_plan_cliente, array_criterios_plan_cliente) === -1){	
					   		array_criterios_plan_cliente.push(criterio_plan_cliente);
					   	}
					});

					var n_criterios_plan_cliente = array_criterios_plan_cliente.length;

					if(n_criterios_plan_cliente < 1){

						//se revisa si el criterio actual ingresado esta entre el array de los registrados
            			if(jQuery.inArray(id_criterio, array_criterios_plan_cliente) === -1) {
            				//se puede insertar el registro por que: hay menos de 2 criterios por el plan actual y el criterio actual no ha sido registrado
				    		var tabla = $('#table_criterios_cliente');

			            	tabla.append('<tr class="tr_criterio_cliente" data-plan="'+id_plan+'" data-criterio="'+id_criterio+'"><td><p>'+plan+'</p></td><td><p>'+criterio+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete-criterio-cliente"><i class="fas fa-trash-alt"></i></button></td></tr>');

			            	valida_opciones_criterio_cliente();

            			}else{
            				//el plan / criterio ya existe en el registro actual
				    		$('#Error_Id_Criterio_Cliente').html('Esta combinación plan / criterio ya esta registrada.');
	            			$('#Error_Id_Criterio_Cliente').show();
            			}

			    	}else{
			    		//el plan agregado seria el 2do en el arreglo y el max. permitido es 1
			    		$('#Error_Id_Criterio_Cliente').html('Ya se ha agregado un criterio para este plan.');
            			$('#Error_Id_Criterio_Cliente').show();	
			    	}

				}

            }

        }else{
            $('#Error_Id_Criterio_Cliente').html('Ya se han agregado el número de planes (2) / criterios (1 x plan) max. permitido.');
            $('#Error_Id_Criterio_Cliente').show(); 
        }   
        
    }else{
        if(id_plan == "" || id_plan == null){
            $('#Error_Id_Plan_Cliente').html('Plan cliente es requerido.');
            $('#Error_Id_Plan_Cliente').show(); 
        }
        if(id_criterio == "" || id_criterio == null){
            $('#Error_Id_Criterio_Cliente').html('Criterio cliente es requerido.');
            $('#Error_Id_Criterio_Cliente').show(); 
        }

    }
}

function valida_opciones_criterio_cliente(){
    
    var plan_cliente_selected = '';
    var criterio_cliente_selected = ''; 
   
    $(".tr_criterio_cliente").each(function(index) {
           	
       	var plan_cliente = $(this).attr("data-plan");
       	var crit_cliente = $(this).attr("data-criterio");

        plan_cliente_selected += plan_cliente+',';
        criterio_cliente_selected += crit_cliente+',';         

    });

    if(plan_cliente_selected != "" && criterio_cliente_selected != ""){
    	var cadena_plan_cliente = plan_cliente_selected.slice(0,-1);
    	var cadena_criterio_cliente = criterio_cliente_selected.slice(0,-1);
    
    	$('#DinCom_Cad_Plan_Cliente').val(cadena_plan_cliente);
    	$('#DinCom_Cad_Criterio_Cliente').val(cadena_criterio_cliente);
    }else{
    	$('#DinCom_Cad_Plan_Cliente').val('');
    	$('#DinCom_Cad_Criterio_Cliente').val('');
    }  
}

//eliminacion de lineas dinamicas plan / criterio cliente
$("body").on("click", ".delete-criterio-cliente", function (e) {
    $(this).parent().parent("tr").remove();
    var cant = $(".tr_criterio_cliente").length;
    
    if(cant == 0){
        $('#contenido_criterios_cliente').html('');
        valida_opciones_criterio_cliente();  
    }else{
      	valida_opciones_criterio_cliente();  
    }

});

function add_criterio_item(){

   //se obtienen los valores necesarios 
    var id_plan = $('#DinCom_Id_Plan_Item').val();
    var id_criterio = $('#DinCom_Id_Criterio_Item').val();
    var plan = $('#s2id_DinCom_Id_Plan_Item span').html();
    var criterio = $('#s2id_DinCom_Id_Criterio_Item span').html();

    $('#Error_Id_Plan_Item').html('');
    $('#Error_Id_Plan_Item').hide();
    $('#Error_Id_Criterio_Item').html('');
    $('#Error_Id_Criterio_Item').hide();
    
    if(id_plan != "" && id_plan != null && id_criterio != "" && id_criterio != null){

 		var div_contenido = $('#contenido_criterios_item');

        var cant_f = $(".tr_criterio_item").length;

        if(cant_f < 2){

            if(cant_f == 0){

            	//si no existe ningun criterio no hay necesidad de evaluar el primer registro

                div_contenido.append('<table class="table table-sm table-hover" id="table_criterios_item"><thead><tr><th colspan="3">Criterios item</th></tr><tr><th>Plan</th><th>Criterio</th><th></th></tr></thead><tbody></tbody></table>');

                var tabla = $('#table_criterios_item');

            	tabla.append('<tr class="tr_criterio_item" data-plan="'+id_plan+'" data-criterio="'+id_criterio+'"><td><p>'+plan+'</p></td><td><p>'+criterio+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete-criterio-item"><i class="fas fa-trash-alt"></i></button></td></tr>');

            	valida_opciones_criterio_item();

            }else{

            	//hay necesidad de evaluar
            	//2 planes max., 2 criterios x cada plan
            	//1. si hay más de dos planes agregados
            	//2. si hay mas de dos criterios

            	var array_plan_item = [];
            	$(".tr_criterio_item").each(function(index) {
            		var plan_item = $(this).attr("data-plan");
            		if(jQuery.inArray(plan_item, array_plan_item) === -1){
            			//no existe este plan en el arreglo
					  	array_plan_item.push(plan_item);
					}	
				});

            	var n_planes_item = array_plan_item.length;

            	//se revisa si el criterio actual ingresado esta entre el array de los registrados
            	if(jQuery.inArray(id_plan, array_plan_item) === -1) {
				    //no existe el plan, se revisa que el arreglo no cuente con el max de planes
			    	if(n_planes_item < 2){
			    		//se puede insertar el registro por que: hay menos de 2 planes 
			    		var tabla = $('#table_criterios_item');

		            	tabla.append('<tr class="tr_criterio_item" data-plan="'+id_plan+'" data-criterio="'+id_criterio+'"><td><p>'+plan+'</p></td><td><p>'+criterio+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete-criterio-item"><i class="fas fa-trash-alt"></i></button></td></tr>');

		            	valida_opciones_criterio_item();

			    	}else{
			    		//el plan agregado seria el 3ro en el arreglo y el max. permitido son 2
			    		$('#Error_Id_Plan_Item').html('Ya se han agregado el número de planes max. permitido (2).');
            			$('#Error_Id_Plan_Item').show();	
			    	}

				}else{
					//existe el plan, se revisa que este no tenga mas de dos criterios agregados

					var array_criterios_plan_item = [];
	            	$('.tr_criterio_item[data-plan="'+id_plan+'"]').each(function(index) {
	            		var criterio_plan_item = $(this).attr("data-criterio");
	            		if(jQuery.inArray(criterio_plan_item, array_criterios_plan_item) === -1){	
					   		array_criterios_plan_item.push(criterio_plan_item);
					   	}
					});

					var n_criterios_plan_item = array_criterios_plan_item.length;

					if(n_criterios_plan_item < 1){

						//se revisa si el criterio actual ingresado esta entre el array de los registrados
            			if(jQuery.inArray(id_criterio, array_criterios_plan_item) === -1) {
            				//se puede insertar el registro por que: hay menos de 2 criterios por el plan actual y el criterio actual no ha sido registrado
				    		var tabla = $('#table_criterios_item');

			            	tabla.append('<tr class="tr_criterio_item" data-plan="'+id_plan+'" data-criterio="'+id_criterio+'"><td><p>'+plan+'</p></td><td><p>'+criterio+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete-criterio-item"><i class="fas fa-trash-alt"></i></button></td></tr>');

			            	valida_opciones_criterio_item();

            			}else{
            				//el plan / criterio ya existe en el registro actual
				    		$('#Error_Id_Criterio_Item').html('Esta combinación plan / criterio ya esta registrada.');
	            			$('#Error_Id_Criterio_Item').show();
            			}

			    	}else{
			    		//el plan agregado seria el 2do en el arreglo y el max. permitido es 1
			    		$('#Error_Id_Criterio_Item').html('Ya se ha agregado un criterio para este plan.');
            			$('#Error_Id_Criterio_Item').show();	
			    	}

				}

            }

        }else{
            $('#Error_Id_Criterio_Item').html('Ya se han agregado el número de planes (2) / criterios (1 x plan) max. permitido.');
            $('#Error_Id_Criterio_Item').show(); 
        }   
        
    }else{
        if(id_plan == "" || id_plan == null){
            $('#Error_Id_Plan_Item').html('Plan item es requerido.');
            $('#Error_Id_Plan_Item').show(); 
        }
        if(id_criterio == "" || id_criterio == null){
            $('#Error_Id_Criterio_Item').html('Criterio item es requerido.');
            $('#Error_Id_Criterio_Item').show(); 
        }

    }
}

function valida_opciones_criterio_item(){
    
    var plan_item_selected = '';
    var criterio_item_selected = ''; 
   
    $(".tr_criterio_item").each(function(index) {
           	
       	var plan_item = $(this).attr("data-plan");
       	var crit_item = $(this).attr("data-criterio");

        plan_item_selected += plan_item+',';
        criterio_item_selected += crit_item+',';         

    });

    if(plan_item_selected != "" && criterio_item_selected != ""){
    	var cadena_plan_item = plan_item_selected.slice(0,-1);
    	var cadena_criterio_item = criterio_item_selected.slice(0,-1);
    
    	$('#DinCom_Cad_Plan_Item').val(cadena_plan_item);
    	$('#DinCom_Cad_Criterio_Item').val(cadena_criterio_item);
    }else{
    	$('#DinCom_Cad_Plan_Item').val('');
    	$('#DinCom_Cad_Criterio_Item').val('');
    } 
}

//eliminacion de lineas dinamicas plan / criterio item
$("body").on("click", ".delete-criterio-item", function (e) {
    $(this).parent().parent("tr").remove();
    var cant = $(".tr_criterio_item").length;
    
    if(cant == 0){
        $('#contenido_criterios_item').html(''); 
        valida_opciones_criterio_item(); 
    }else{
      	//no se realiza ninguna acción
      	valida_opciones_criterio_item();   
    }
});

</script>

<h4>Creación de dinámica comercial</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lp'=>$lp, 'co'=>$co)); ?>
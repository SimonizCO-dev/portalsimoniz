<?php
/* @var $this PedComController */
/* @var $model PedCom */

?>

<script>

$(function() {

  	$("#valida_form").click(function() {
    	var form = $("#ped-com-form");
    	var settings = form.data('settings') ;

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
          	}
      	});
    });

    $("#PedCom_Cliente").change(function() {

  		var nit = $(this).val();

	  	if(nit != ""){
  			var data = {nit: nit}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('PedCom/GetSucCliente'); ?>",
				data: data,
				dataType: 'json',
				success: function(data){
					$('#PedCom_Sucursal').val('').trigger('change');
				   	$("#PedCom_Sucursal").html('');
				  	$("#PedCom_Sucursal").append('<option value=""></option>');
				  	$.each(data, function(i,item){
			      		$("#PedCom_Sucursal").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
				  	});
				  	$("#div_suc").show();
				}
			});
	 	}else{
	 		$('#contenido').html('');
        	$('#btn_save').hide();
        	$('#PedCom_Sucursal').val('').trigger('change');
        	$('#PedCom_Punto_Envio').val('').trigger('change'); 
      		$("#div_suc").hide();    

	 	}

	});

	$("#PedCom_Sucursal").change(function() {

  		var nit = $("#PedCom_Cliente").val();
  		var suc = $(this).val();

	  	if(suc != ""){
  			var data = {nit: nit, suc: suc}
			$.ajax({ 
				type: "POST", 
				url: "<?php echo Yii::app()->createUrl('PedCom/GetPuntEnvSucCliente'); ?>",
				data: data,
				dataType: 'json',
				success: function(data){ 
					$('#PedCom_Punto_Envio').val('').trigger('change');
					$("#PedCom_Punto_Envio").html('');
				  	$("#PedCom_Punto_Envio").append('<option value=""></option>');
				  	$.each(data, function(i,item){
			      		$("#PedCom_Punto_Envio").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
				  	});
				  	$("#div_pe").show();
				}
			});
	 	}else{
 			$('#contenido').html('');
        	$('#btn_save').hide(); 
      		$('#PedCom_Punto_Envio').val('').trigger('change'); 
      		$("#div_pe").hide();    
	 	}

	});

});

</script>

<h4>Creación de pedido</h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
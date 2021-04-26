<?php
/* @var $this SolPromController */
/* @var $model SolProm */

?>

<script type="text/javascript">
$(function() {

	$(".ajax-loader").show();

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

	var step = <?php echo $s; ?>;
	var e = <?php echo $e; ?>;

    div_step(step, e);

    $("#rechazar_form").click(function() {

        var opcion = confirm("¿ Esta seguro de rechazar la solicitud ? ");
        if (opcion == true) {
            var form = $("#sol-prom-form");
            var settings = form.data('settings') ;
            $("#SolProm_Estado").val(0);
            form.submit();
            loadershow();
        } 
        
    });

    $("#aprobar_g_form").click(function() {

        var opcion = confirm("¿ Esta seguro de aprobar la solicitud ? ");
        if (opcion == true) {
            var form = $("#sol-prom-form");
            var settings = form.data('settings') ;
            $("#SolProm_Estado").val(2);
            form.submit();
            loadershow();
        } 
        
    });

    $("#aprobar_p_form").click(function() {

        var opcion = confirm("¿ Esta seguro de aprobar la solicitud ? ");
        if (opcion == true) {
            var form = $("#sol-prom-form");
            var settings = form.data('settings') ;
            $("#SolProm_Estado").val(3);
            form.submit();
            loadershow();
        } 
        
    });

    $("#revision_form").click(function() {

        var opcion = confirm("¿ Esta seguro de solicitar una revisión de la solicitud ? ");
        if (opcion == true) {
            loadershow();
            location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=solProm/rev&id='.$model->Id_Sol_Prom; ?>';
        } 
        
    });

	$("#valida_form").click(function() {

      	var form = $("#sol-prom-form");
		var settings = form.data('settings') ;
		
		settings.submitting = true ;
		$.fn.yiiactiveform.validate(form, function(messages) {
		  if($.isEmptyObject(messages)) {
		    $.each(settings.attributes, function () {
		        $.fn.yiiactiveform.updateInput(this,messages,form); 
		    });

            //se envia el form
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

	$("#valida_l_form").click(function() {

      	var form = $("#sol-prom-form");
		var settings = form.data('settings') ;
		
		settings.submitting = true ;
		$.fn.yiiactiveform.validate(form, function(messages) {
		  if($.isEmptyObject(messages)) {
		    $.each(settings.attributes, function () {
		        $.fn.yiiactiveform.updateInput(this,messages,form); 
		    });

		    $("#SolProm_Estado").val(4);

            //se envia el form
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

    $("#SolProm_Val_Compra").click(function() {  
        validaprob(); 
    }); 

    $("#SolProm_Val_Prod").click(function() {  
        validaprob();  
    }); 

    $("#SolProm_Val_MT").click(function() {  
        validaprob(); 
    });

    $("#SolProm_Comp_Disp").click(function() {  
        if($("#SolProm_Comp_Disp").is(':checked')) {  
            $("#fecha_entrega").show(); 
            $("#valida_l_form").show(); 
        } else {  
            $("#fecha_entrega").hide(); 
            $("#valida_l_form").hide();   
        } 
    });

});

function div_step(step, e){

	var tipo_producto = $('#FichaItem_Tipo_Producto').val();
	var r = <?php echo $r; ?>;
	if(step == 0){
        if(r == 1){

        	//rev. gerencia
        	//$("#collapse_2").hide();
	        $("#collapse_3").hide();
	        $("#collapse_4").hide();

            $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
            $('#link_collapse_2').addClass('card-warning').removeClass('card-secondary');
            $('#link_collapse_3').addClass('card-danger').removeClass('card-secondary');
            $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');

	        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
	        $('#img_info_2').addClass('fas fa-times-circle').removeClass('fa-clock');
	        $('#img_info_3').addClass('fas fa-times-circle').removeClass('fa-clock');
	        $('#img_info_4').addClass('fas fa-times-circle').removeClass('fa-clock');
        }

        if(r == 2){
        	//rev. planeación
	        //$("#collapse_3").hide();
	        $("#collapse_4").hide();

            $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
            $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
            $('#link_collapse_3').addClass('card-warning').removeClass('card-secondary');
            $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');

	        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
	        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
	        $('#img_info_3').addClass('fas fa-times-circle').removeClass('fa-clock');
	        $('#img_info_4').addClass('fas fa-times-circle').removeClass('fa-clock');
        } 
    }

    if(step == 1){
        //rev. gerencia

        if(e == 1){
	    	//campos habilitados
	  		var fecha = '<?php echo date('Y-m-d'); ?>';
	        var fecha_t_entrega = '<?php echo date("Y-m-d",strtotime(date('Y-m-d')." + 20 days")); ?>';
	        $('#SolProm_Fecha').val(fecha);
	  		$('#SolProm_Fecha_T_Entrega').val(fecha_t_entrega);
            $('#SolProm_Observaciones_Ger').removeAttr('disabled');
  			$("#buttons_2").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-times-circle"></i> Rechazar</button> <button type="button" class="btn btn-primary btn-sm" id="aprobar_g_form"><i class="fas fa-check-circle"></i> Aprobar</button>');
	  	}

        $("#collapse_3").hide();
        $("#collapse_4").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-danger').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 2){
        //rev. planeación

        if(e == 1){
	    	//campos habilitados
	    	$('#SolProm_Val_Compra').removeAttr('disabled');
  			$('#SolProm_Val_Prod').removeAttr('disabled');
  			$('#SolProm_Val_MT').removeAttr('disabled');
            $('#SolProm_Observaciones_Pla').removeAttr('disabled');
  			$("#buttons_3").html('<button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-times-circle"></i> Rechazar</button> <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button> <button type="button" class="btn btn-primary btn-sm" id="aprobar_p_form" style="display: none;"><i class="fas fa-check-circle"></i> Aprobar</button>');

  			validaprob(); 

	  	}

        $("#collapse_4").hide();
        $("#collapse_5").hide();

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-warning').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-danger').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 3){

    	$("#SolProm_Fecha_Entrega").datepicker({
	        language: 'es',
	        autoclose: true,
	        orientation: "right bottom",
	        startDate: '<?php echo $model->Fecha; ?>',
	        endDate: '<?php echo $model->Fecha_T_Entrega; ?>',
	    });

        //rev. planeación
        if(e == 1){
	    	//campos habilitados

	    	var url = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=solprom/genrepdoc&id='.$model->Id_Sol_Prom; ?>';

	    	$('#SolProm_Comp_Disp').removeAttr('disabled');
  			$('#SolProm_Observaciones').removeAttr('disabled');
  			$("#buttons_4").html('<button type="button" class="btn btn-primary btn-sm" id="revision_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button> <button type="button" class="btn btn-primary btn-sm" onclick="location.href = \''+url+'\'"><i class="fas fa-file-pdf"></i> Descargar PDF</button> <button type="button" class="btn btn-primary btn-sm" id="valida_l_form" style="display: none;"><i class="fas fa-save"></i> Guardar</button>');

	  	}

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-warning').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_4').addClass('fa-circle').removeClass('fa-clock');
    }

    if(step == 4){

    	$("#fecha_entrega").show(); 

        $('#link_collapse_1').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_2').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_3').addClass('card-success').removeClass('card-secondary');
        $('#link_collapse_4').addClass('card-success').removeClass('card-secondary');

        $('#img_info_1').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_2').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_3').addClass('fa-check-circle').removeClass('fa-clock');
        $('#img_info_4').addClass('fa-check-circle').removeClass('fa-clock');
    }

    $(".ajax-loader").fadeOut('fast');
}

function validaprob(){

    if($("#SolProm_Val_Compra").is(':checked') && $("#SolProm_Val_Prod").is(':checked') && $("#SolProm_Val_MT").is(':checked')) { 

        $("#aprobar_p_form").show(); 
    } else {  
        $("#aprobar_p_form").hide();  
    } 
}

</script>

<div class="row mb-2">
  <div class="col-sm-9">
    <h4>Revisión solicitud de promoción</h4>
  </div>
  <div class="col-sm-3 text-right"> 
    <button type="button" class="btn btn-success btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=solprom/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
  </div>
</div>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos, 'detalle'=>$detalle)); ?>
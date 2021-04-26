<?php
/* @var $this CuentaController */
/* @var $model Cuenta */

//para combos de clases de cuenta / usuario
$lista_clases = CHtml::listData($clases, 'Id_Dominio', 'Dominio'); 

//para combo de dominios (correo electronico)
$lista_dominios = CHtml::listData($dominios, 'Id_Dominio_Web', 'Dominio'); 

//para combo de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combo de estados
$lista_estados = CHtml::listData($estados, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">
  
$(function() {
	
	var clase = <?php echo $model->Clasificacion; ?>;

	if(clase == <?php echo Yii::app()->params->c_correo ?>){
		$('#div_cuenta_usuario').show();
        $('#div_dominio').show(); 
        $('#div_password').show();
        $('#div_tipo_cuenta').show(); 
        $('#div_tipo_acceso').hide();
        $('#div_observaciones').show();
        $('#div_estado').show();
        
        var tipo_cuenta = $('#Cuenta_Tipo_Cuenta').val();

        if(tipo_cuenta == <?php echo Yii::app()->params->t_c_generico ?>){
        	$('#div_ext').show();
        }else{
        	$('#div_ext').hide();
        }

	}else{
		$('#div_cuenta_usuario').show();
        $('#div_dominio').hide(); 
        $('#div_password').show();
        $('#div_tipo_cuenta').hide(); 
        $('#div_tipo_acceso').show();
        $('#div_observaciones').show();
        $('#div_estado').show();
        $('#div_ext').hide();

        var tipo_acceso = $('#Cuenta_Tipo_Acceso').val();
        var num_emp = <?php echo $model->NumUsuariosAsoc($model->Id_Cuenta) ?>;

        if(tipo_acceso == 2 && num_emp == 1){
			$('#btn_asoc').hide();
        }else{
        	$('#btn_asoc').show();
        }

	}


	$("#valida_form").click(function() {
    
	    var form = $("#cuenta-form");
	    var clase = <?php echo $model->Clasificacion ?>;
	    var id_reg = <?php echo $model->Id_Cuenta ?>;

	    if(clase == <?php echo Yii::app()->params->c_correo ?>){
	      	//CORREO ELECTRONICO

	        var tipo_cuenta = $('#Cuenta_Tipo_Cuenta').val();
	        var estado = $('#Cuenta_Estado').val();

			if(tipo_cuenta != "" && estado != ""){

	            limpiar_errores();
				$('#div_buttons').hide();
				$(".ajax-loader").fadeIn('fast');
				form.submit();
		                
			}else{

				if(tipo_cuenta == ""){
				  $('#error_tipo_cuenta').html('Tipo de cuenta es requerido.');
				  $('#error_tipo_cuenta').show(); 
				}

				if(estado == ""){
				  $('#error_estado').html('Estado es requerido.');
				  $('#error_estado').show(); 
				}
			}

		}else{
		  	//DEMAS CUENTAS / USUARIOS

	        var tipo_acceso = $('#Cuenta_Tipo_Acceso').val();
			var estado = $('#Cuenta_Estado').val();

			if(tipo_acceso != "" && estado != ""){

                limpiar_errores();
				$('#div_buttons').hide();
				$(".ajax-loader").fadeIn('fast');
				form.submit();

			}else{

				if(tipo_acceso == ""){
				  $('#error_tipo_acceso').html('Tipo de acceso es requerido.');
				  $('#error_tipo_acceso').show(); 
				}

				if(estado == ""){
				  $('#error_estado').html('Estado es requerido.');
				  $('#error_estado').show(); 
				}
			}

		}
   
  	});
  
	$("#Cuenta_Cuenta_Usuario").change(function() {
		var valor = $('#Cuenta_Cuenta_Usuario').val(); 

		if(valor != ""){
			$('#error_cuenta_usuario').html('');
			$('#error_cuenta_usuario').hide();
		}
	});

	$("#Cuenta_Dominio").change(function() {
		var valor = $('#Cuenta_Dominio').val(); 

		if(valor != ""){
		 	$('#error_dominio').html('');
		 	$('#error_dominio').hide();
		}
	});

	$("#Cuenta_Tipo_Cuenta").change(function() {
	    var valor = $('#Cuenta_Tipo_Cuenta').val(); 

	    if(valor == ""){
	      $('#div_ext').hide();
	      $('#Cuenta_Ext').val('');
	    }else{
	      if(valor == <?php echo Yii::app()->params->t_c_generico ?>){
	        $('#div_ext').show();
	      }else{
	        $('#div_ext').hide();
	        $('#Cuenta_Ext').val('');
	      }
	    }

	});

	$("#Cuenta_Tipo_Acceso").change(function() {
		var valor = $('#Cuenta_Tipo_Acceso').val(); 

		if(valor != ""){
	  		$('#error_tipo_acceso').html('');
	  		$('#error_tipo_acceso').hide();
		}
	});

	$("#Cuenta_Estado").change(function() {
		var valor = $('#Cuenta_Estado').val(); 

		if(valor != ""){
	  		$('#error_estado').html('');
	  		$('#error_estado').hide();
		}
	});

	$("#view_p").click(function() {
        $('#myModal').modal({show:true});
    });

});

function limpiar_errores(){

  $('#error_cuenta_usuario').html('');
  $('#error_cuenta_usuario').hide(); 
  $('#error_dominio').html('');
  $('#error_dominio').hide(); 
  $('#error_tipo_cuenta').html('');
  $('#error_tipo_cuenta').hide(); 
  $('#error_tipo_acceso').html('');
  $('#error_tipo_acceso').hide();
  $('#error_estado').html('');
  $('#error_estado').hide();
  $('#error_dup').html('');
  $('#error_dup').hide(); 

}

</script>

<?php $this->renderPartial('_form2', array('model'=>$model, 'lista_clases'=>$lista_clases, 'lista_dominios'=>$lista_dominios, 'lista_tipos'=>$lista_tipos, 'lista_estados'=>$lista_estados, 'emp_asoc' => $emp_asoc, 'nov_cue' => $nov_cue, 'pass'=>$pass)); ?>
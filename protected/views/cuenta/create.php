<?php
/* @var $this CuentaController */
/* @var $model Cuenta */

//para combos de clases de cuenta / usuario
$lista_clases = CHtml::listData($clases, 'Id_Dominio', 'Dominio'); 

//para combo de dominios (correo electronico)
$lista_dominios = CHtml::listData($dominios, 'Id_Dominio_Web', 'Dominio'); 

//para combo de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">
  
$(function() {

  $("#Cuenta_Clasificacion").change(function() {

    var clase = $('#Cuenta_Clasificacion').val();

    if(clase != ''){
       
      $('#error_clasificacion').html('');
      $('#error_clasificacion').hide();

      limpiar_errores();

      if(clase == <?php echo Yii::app()->params->c_correo ?>){
		//CORREO ELECTRONICO

        $('#div_cuenta_usuario').show();
        $('#div_dominio').show(); 
        $('#div_password').show();
        $('#div_tipo_cuenta').show(); 
        $('#div_tipo_acceso').hide();
        $('#div_observaciones').show();

        $('#Cuenta_Tipo_Acceso').val('').trigger('change');

      }else{
      	//DEMAS CUENTAS / USUARIOS

      	$('#div_cuenta_usuario').show();
        $('#div_dominio').hide(); 
        $('#div_password').show();
        $('#div_tipo_cuenta').hide(); 
        $('#div_tipo_acceso').show();
        $('#div_observaciones').show();

      	$('#Cuenta_Dominio').val('').trigger('change');
      	$('#Cuenta_Tipo_Cuenta').val('').trigger('change');


      }

    }else{
      
      $('#div_cuenta_usuario').hide();
      $('#div_dominio').hide(); 
      $('#div_tipo_cuenta').hide(); 
      $('#div_tipo_acceso').hide(); 
      $('#div_password').hide();
      $('#div_observaciones').hide();

	  $('#error_clasificacion').html('Clasif. es requerido');
      $('#error_clasificacion').show();
      
      $('#Cuenta_Cuenta_Usuario').val('');
      $('#Cuenta_Dominio').val('').trigger('change');
      $('#Cuenta_Password').val('');
      $('#Cuenta_Tipo_Cuenta').val('').trigger('change');
      $('#Cuenta_Tipo_Acceso').val('').trigger('change');
      $('#Cuenta_Observaciones').val('');

    }
  });


  $("#valida_form").click(function() {
    
    var form = $("#cuenta-form");
    var clase = $('#Cuenta_Clasificacion').val();

    if(clase == ''){
      $('#error_clasificacion').html('Clasif. es requerido.');
      $('#error_clasificacion').show();
    }else{
      $('#error_clasificacion').html('');
      $('#error_clasificacion').hide();
    }

    limpiar_errores();

    if(clase == <?php echo Yii::app()->params->c_correo ?>){
    	//CORREO ELECTRONICO

      var cuenta_usuario = $('#Cuenta_Cuenta_Usuario').val();
      var dominio = $('#Cuenta_Dominio').val();
      var password = $('#Cuenta_Password').val();
      var tipo_cuenta = $('#Cuenta_Tipo_Cuenta').val();

  		if(cuenta_usuario != "" && dominio != "" && password != "" && tipo_cuenta != ""){

  			var data = {clase: clase, cuenta_usuario: cuenta_usuario, dominio: dominio}
  	        $.ajax({ 
  	            type: "POST", 
  	            url: "<?php echo Yii::app()->createUrl('cuenta/verificarduplicidad'); ?>",
  	            data: data,
  	            success: function(response){

  	                if(response == 0){
  	                    //se encontro una cuenta igual
  	                    $('#error_dup').html('Esta cuenta / usuario ya esta registrada.');
  	                    $('#error_dup').show();
  	                }

  	                if(response == 1){
  	                    //si la cuenta no existe

  	                    limpiar_errores();
            			form.submit();
						loadershow();
  	                }

  	            }
  	        });


  		}else{
  			if(cuenta_usuario == ""){
  			  $('#error_cuenta_usuario').html('Cuenta / Usuario es requerido.');
  			  $('#error_cuenta_usuario').show(); 
  			}

  			if(dominio == ""){
  			  $('#error_dominio').html('Dominio es requerido.');
  			  $('#error_dominio').show(); 
  			}

  			if(password == ""){
  			  $('#error_password').html('Password es requerido.');
  			  $('#error_password').show(); 
  			}

  			if(tipo_cuenta == ""){
  			  $('#error_tipo_cuenta').html('Tipo de cuenta es requerido.');
  			  $('#error_tipo_cuenta').show(); 
  			}

		  }

	  }else{
	  	//DEMAS CUENTAS / USUARIOS

	  	var cuenta_usuario = $('#Cuenta_Cuenta_Usuario').val();
      	var password = $('#Cuenta_Password').val();
      	var tipo_acceso = $('#Cuenta_Tipo_Acceso').val();

  		if(cuenta_usuario != "" && password != "" && tipo_acceso != ""){

  			var data = {clase: clase, cuenta_usuario: cuenta_usuario, dominio: null}
        $.ajax({ 
            type: "POST", 
            url: "<?php echo Yii::app()->createUrl('cuenta/verificarduplicidad'); ?>",
            data: data,
            success: function(response){

                if(response == 0){
                    //se encontro una cuenta igual
                    $('#error_dup').html('Este cuenta / usuario ya esta registrada.');
                    $('#error_dup').show();
                }

                if(response == 1){
                    //si la cuenta no existe

                    limpiar_errores();
          			form.submit();
					loadershow();
                }

            }
        });

  		}else{
  			if(cuenta_usuario == ""){
  			  $('#error_cuenta_usuario').html('Cuenta / Usuario es requerido.');
  			  $('#error_cuenta_usuario').show(); 
  			}

  			if(password == ""){
  			  $('#error_password').html('Password es requerido.');
  			  $('#error_password').show(); 
  			}

  			if(tipo_acceso == ""){
  			  $('#error_tipo_acceso').html('Tipo de acceso es requerido.');
  			  $('#error_tipo_acceso').show(); 
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

  $("#Cuenta_Password").change(function() {
    var valor = $('#Cuenta_Password').val(); 

    if(valor != ""){
      $('#error_password').html('');
      $('#error_password').hide();
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

});


function limpiar_errores(){

  $('#error_cuenta_usuario').html('');
  $('#error_cuenta_usuario').hide(); 
  $('#error_dominio').html('');
  $('#error_dominio').hide(); 
  $('#error_password').html('');
  $('#error_password').hide(); 
  $('#error_tipo_cuenta').html('');
  $('#error_tipo_cuenta').hide(); 
  $('#error_tipo_acceso').html('');
  $('#error_tipo_acceso').hide();
  $('#error_dup').html('');
  $('#error_dup').hide(); 

}
    
</script>

<h4>Creación de cuenta / usuario</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_clases'=>$lista_clases, 'lista_dominios'=>$lista_dominios, 'lista_tipos'=>$lista_tipos)); ?>
<?php
/* @var $this EquipoController */
/* @var $model Equipo */

//para combos de tipos de equipo
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Pa_Empresa', 'Descripcion'); 

//para combos de proveedores
$lista_proveedores = CHtml::listData($proveedores, 'Id_Proveedor', 'Proveedor'); 

?>

<script type="text/javascript">


$(function() {

	var extensionesValidas = ".pdf, .JPEG, .JPG, .PNG, .jpeg, .jpg, .png";
	var textExtensionesValidas = "(.pdf, .png, .jpeg, .jpg)";
	var idInput = "valid_sop";
	var idMsg = "error_sop";
	var pesoPermitido = 1024;

	$("#valida_form").click(function() {
	    var form = $("#equipo-form");
	    var settings = form.data('settings') ;

	    var soporte = $('#Equipo_sop').val();

	    settings.submitting = true ;
	    $.fn.yiiactiveform.validate(form, function(messages) {
	        if($.isEmptyObject(messages)) {
	            $.each(settings.attributes, function () {
	               $.fn.yiiactiveform.updateInput(this,messages,form); 
	            });
	            	
	            //se valida si el archivo cargado es valido (1)
	            valid_sop = $('#valid_sop').val();

	            if(valid_sop == 1){
	            	//se envia el form
	            	form.submit();
                	loadershow();
	            }else{

	            	settings.submitting = false ;	
	            }
	            

	        } else {
	            settings = form.data('settings'),
	            $.each(settings.attributes, function () {
	               $.fn.yiiactiveform.updateInput(this,messages,form); 
	            });
	            settings.submitting = false ;
	        }
	    });
	});

	$("#Equipo_sop").change(function () {

  		$('#error_sop').html('');
    	$('#error_sop').hide();

  		if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
            $('#valid_sop').val(1);
          }
      }   
    });


	$("#Equipo_MAC1").on("keydown", function(event) {
		const BACKSPACE_KEY = 8
		const COLON_KEY = 186
		const _colonPositions = [2, 5, 8, 11, 14]
		const _newValue = $(this).val().trim()
		const _currentPosition = _newValue.length
		if (event.keyCode === COLON_KEY) {
		  event.preventDefault()
		}
		if (event.keyCode !== BACKSPACE_KEY) {
		  if (_colonPositions.some(position => position === _currentPosition)) {
		    $("#Equipo_MAC1").val(_newValue.concat(':'))
		  }
		}
	});

	$("#Equipo_MAC2").on("keydown", function(event) {
		const BACKSPACE_KEY = 8
		const COLON_KEY = 186
		const _colonPositions = [2, 5, 8, 11, 14]
		const _newValue = $(this).val().trim()
		const _currentPosition = _newValue.length
		if (event.keyCode === COLON_KEY) {
		  event.preventDefault()
		}
		if (event.keyCode !== BACKSPACE_KEY) {
		  if (_colonPositions.some(position => position === _currentPosition)) {
		    $("#Equipo_MAC2").val(_newValue.concat(':'))
		  }
		}
	});

});

	
</script>

<h4>Actualización de equipo</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'lista_tipos'=>$lista_tipos, 'lista_empresas'=>$lista_empresas, 'lista_proveedores'=>$lista_proveedores)); ?>
<?php
/* @var $this LicenciaController */
/* @var $model Licencia */

//para combos de clases de licencia
$lista_clases = CHtml::listData($clases, 'Id_Dominio', 'Dominio');

//para combos de tipos de licencia
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combos de versiones
$lista_versiones = CHtml::listData($versiones, 'Id_Dominio', 'Dominio');

//para combos de productos
$lista_productos = CHtml::listData($productos, 'Id_Dominio', 'Dominio');

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion'); 

//para combos de proveedores
$lista_proveedores = CHtml::listData($proveedores, 'Id_Pa_Proveedor', 'Proveedor'); 

//para combos de ubicaciones
$lista_ubicaciones = CHtml::listData($ubicaciones, 'Id_Dominio', 'Dominio');

//para combos de estados
$lista_estados = CHtml::listData($estados, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

$(function() {

	var extensionesValidas = ".pdf, .JPEG, .JPG, .PNG, .jpeg, .jpg, .png";
	var textExtensionesValidas = "(.pdf, .png, .jpeg, .jpg)";
	var idInput = "valid_sop";
	var idMsg = "error_sop";
	var pesoPermitido = 1024;

	var extensionesValidas2 = ".JPEG, .JPG, .PNG, .jpeg, .jpg, .png";
	var textExtensionesValidas2 = "(.png, .jpeg, .jpg)";
	var idInput2 = "valid_sop2";
	var idMsg2 = "error_sop2";
	var pesoPermitido2 = 512;

	$("#valida_form").click(function() {
      var form = $("#licencia-form");
      var settings = form.data('settings') ;

      var soporte = $('#Licencia_sop').val();

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              	
              //se valida si el archivo cargado es valido (1)
              valid_sop = $('#valid_sop').val();
              valid_sop2 = $('#valid_sop2').val();

              if(valid_sop == 1 && valid_sop2 == 1){
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

	$("#Licencia_Fecha_Inicio").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
	   var minDate = new Date(selected.date.valueOf());
	   $('#Licencia_Fecha_Final').datepicker('setStartDate', minDate);
	});

	$("#Licencia_Fecha_Final").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
	   var maxDate = new Date(selected.date.valueOf());
	   $('#Licencia_Fecha_Inicio').datepicker('setEndDate', maxDate);
	});

  	$("#Licencia_sop").change(function () {

  		$('#error_sop').html('');
    	$('#error_sop').hide();

  		if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {
            $('#valid_sop').val(1);
          }else{
          	$('#valid_sop').val(0);	
          }
      }   
    });

    $("#Licencia_sop2").change(function () {

  		$('#error_sop2').html('');
    	$('#error_sop2').hide();

  		if(validarExtension(this, extensionesValidas2, textExtensionesValidas2, idInput2, idMsg2)) {

          if(validarPeso(this, pesoPermitido2, idInput2, idMsg2)) {
            $('#valid_sop2').val(1);
          }else{
          	$('#valid_sop2').val(0);	
          }
      }   
    });

});

	
</script>

<h4>Actualización de licencia</h4>

<?php 
	$this->renderPartial('_form2', array(
		'model'=>$model, 
		'lista_clases'=>$lista_clases,
		'lista_tipos'=>$lista_tipos,
		'lista_versiones'=>$lista_versiones,
		'lista_productos'=>$lista_productos,
		'lista_empresas'=>$lista_empresas,
		'lista_proveedores'=>$lista_proveedores,
		'lista_ubicaciones'=>$lista_ubicaciones,
		'lista_estados'=>$lista_estados,
	)); 
?>
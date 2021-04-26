<?php
/* @var $this DocumentoController */
/* @var $model Documento */

//para combo de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Tipo', 'Descripcion'); 

//para combo de áreas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area'); 

//para combo de unidades de gerencia
$lista_unidades = CHtml::listData($unidades_gerencia, 'Id_Unidad_Gerencia', 'Unidad_Gerencia'); 

?>

<script type="text/javascript">
$(function() {
	//se llenan las opciones seleccionadas del modelo
	$('#GdDocumento_areas').val(<?php echo $json_areas_activas ?>).trigger('change');
	$('#GdDocumento_Clasificacion').val(<?php echo $model->Clasificacion ?>).trigger('change');

	$('#GdDocumento_Titulo').on("cut copy paste",function(e) {
	e.preventDefault();
	});

  	var extensionesValidasCon = ".pdf, .PDF";
	var textExtensionesValidasCon = "(.pdf)";
	var idInputCon = "valid_doc_consulta";
	var idMsgCon = "error_doc_consulta";

	var extensionesValidasDes = ".doc, .docx, .xls, .xlsx, .pdf";
	var textExtensionesValidasDes = "(.doc, .docx, .xls, .xlsx, .pdf)";
	var idInputDes = "valid_doc_descarga";
	var idMsgDes = "error_doc_descarga";

	var pesoPermitido = 5120;

	$("#valida_form").click(function() {
	    var form = $("#gd-documento-form");
	    var settings = form.data('settings') ;

	    settings.submitting = true ;
	    $.fn.yiiactiveform.validate(form, function(messages) {
	        if($.isEmptyObject(messages)) {
	            $.each(settings.attributes, function () {
	               $.fn.yiiactiveform.updateInput(this,messages,form); 
	            });
	            	
	            //se valida si el archivo cargado para consulta y descarga sea valido
	            valid_doc_consulta = $('#valid_doc_consulta').val();
	            valid_doc_descarga = $('#valid_doc_descarga').val();

	            if(valid_doc_consulta == 1 && valid_doc_descarga == 1){
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

	  $("#GdDocumento_Clasificacion").change(function () {
	    vlr = $("#GdDocumento_Clasificacion").val();
	    if(vlr != ""){
	      var data = {clasificacion: vlr}
	      $.ajax({ 
	        type: "POST", 
	        url: "<?php echo Yii::app()->createUrl('gddocumento/gettipos'); ?>",
	        data: data,
	        dataType: 'json',
	        success: function(data){ 
	          $("#GdDocumento_Tipo").html('');
	          $("#GdDocumento_Tipo").append('<option value=""></option>');
	          $.each(data, function(i,item){
	              $("#GdDocumento_Tipo").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
	          });
	          $('#GdDocumento_Tipo').val('').trigger('change');
	          $("#div_tipo").show();
	        }
	      });
	    }else{
	      $("#GdDocumento_Tipo").val('').trigger('change');
	      $("#div_tipo").hide();
	    }
	  });

  	$("#GdDocumento_doc_consulta").change(function () {

  		$('#error_doc_consulta').html('');
    	$('#error_doc_consulta').hide();

  		if(validarExtension(this, extensionesValidasCon, textExtensionesValidasCon, idInputCon, idMsgCon)) {

          if(validarPeso(this, pesoPermitido, idInputCon, idMsgCon)) {
            $('#valid_doc_consulta').val(1);
            var ruta = this.value;
			var extension = ruta.substring(ruta.lastIndexOf('.') + 1).toLowerCase();
			$('#GdDocumento_ext_doc_consulta').val(extension);
          }else{
          	$('#valid_doc_descarga').val(0);
        	$('#GdDocumento_ext_doc_descarga').val('');
          }
      }   
    });

    $("#GdDocumento_doc_descarga").change(function () {

  		$('#error_doc_descarga').html('');
    	$('#error_doc_descarga').hide();

  		if(validarExtension(this, extensionesValidasDes, textExtensionesValidasDes, idInputDes, idMsgDes)) {

          if(validarPeso(this, pesoPermitido, idInputDes, idMsgDes)) {
            $('#valid_doc_descarga').val(1);
            var ruta = this.value;
			var extension = ruta.substring(ruta.lastIndexOf('.') + 1).toLowerCase();
			$('#GdDocumento_ext_doc_descarga').val(extension);
          }else{
          	$('#valid_doc_descarga').val(0);
        	$('#GdDocumento_ext_doc_descarga').val('');
          }
      }   
    });

});
</script>

<h4>Actualización de documento</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'lista_tipos' => $lista_tipos, 'lista_areas' => $lista_areas, 'lista_unidades' => $lista_unidades)); ?>
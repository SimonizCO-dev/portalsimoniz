<?php
/* @var $this CVendedoresController */
/* @var $model CVendedores */

if($tipo == 1){
	//creación de vend.
	echo '<h3>Creación de vendedores</h3>';
}else{
	//inactivación de vend.
	echo '<h3>Inactivación de vendedores</h3>';

}

?>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'cvendedores-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableClientValidation'=>true,
  'clientOptions'=>array(
    'validateOnSubmit'=>true,
  ),
  'htmlOptions' => array(
    'enctype' => 'multipart/form-data'
  ),
)); ?>

<div id="mensaje" role="alert"></div>

<div class="row">
    <div class="col-sm-6">
      <div class="form-group">
        <div class="pull-right badge bg-red" id="error_file" style="display: none;"></div>
        <input type="hidden" id="valid_file" value="0">
        <?php echo $form->label($model,'archivo'); ?>
        <?php echo $form->fileField($model, 'archivo'); ?>
        </div>
    </div>
</div>

<div class="btn-group" style="padding-bottom: 2%" id="buttons">
	<button type="button" class="btn btn-success" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cVendedores/admin'; ?>';"><i class="fa fa-reply"></i> Volver </button>
    <button type="button" class="btn btn-success" id="valida_form"><i class="fa fa-upload"></i> Subir archivo</button>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

  var extensionesValidas = ".xlsx";
  var pesoPermitido = 1024;

  $("#valida_form").click(function() {

      var clase_act = $('#mensaje').attr('class');
      $('#mensaje').removeClass(clase_act);
      $("#mensaje").html('');

      var form = $("#cvendedores-form");
      var settings = form.data('settings');

      var archivo = $('#CVendedores_archivo').val();

      if(archivo == ''){
        $('#error_file').html('Debe subir un archivo.');
        $('#error_file').show();
      }
          
      //se valida si el archivo cargado es valido (1)
      valid_file = $('#valid_file').val();

      if(valid_file == 1){

        //información del formulario
        var formData = new FormData($("#cvendedores-form")[0]);
        var message = ""; 
        //hacemos la petición ajax  
        $.ajax({
            url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cVendedores/uploadfile&tipo='.$tipo; ?>",  
            type: 'POST',
            // Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){
                $(".ajax-loader").fadeIn('fast');      
            },
            //una vez finalizado correctamente
            success: function(data){
                $(".ajax-loader").fadeOut('fast');
                var data = jQuery.parseJSON(data);
                var opc = data.opc; 
                var mensaje = data.msj; 

                if(opc == 0){
                  //el archivo esta vacio
                  $("#mensaje").addClass("alert alert-error");
                  $("#mensaje").html(mensaje);
                }

                if(opc == 1){
                  //el archivo tiene errores
                  $("#mensaje").addClass("alert alert-warning");
                  $("#mensaje").html(mensaje);
                }

                //se resetea el campo FILE
                $("#cvendedores-form")[0].reset();
                $('#valid_file').val(0);
            },
        });

      }
             
  });

  $("#CVendedores_archivo").change(function () {

      $('#error_file').html('');
      $('#error_file').hide();

      if(validarExtension(this)) {

          if(validarPeso(this)) {

            $('#valid_file').val(1);

          }
      }  
  });

  $("#CVendedores_archivo").click(function () {

    var clase_act = $('#mensaje').attr('class');
    $('#mensaje').removeClass(clase_act);
    $("#mensaje").html('');

  });


  // Validacion de extensiones permitidas
  function validarExtension(datos) {

    var ruta = datos.value;
    var extension = ruta.substring(ruta.lastIndexOf('.') + 1).toLowerCase();
    var extensionValida = extensionesValidas.indexOf(extension);

    if(extensionValida < 0) {

      $('#error_file').html('La extensión no es válida (.'+ extension+'), Solo se admite (.xlsx)');
      $('#error_file').show();
      $('#valid_file').val(0);
      return false;

    } else {

      return true;

    }
  }

  // Validacion de peso del fichero en kbs

  function validarPeso(datos) {

    if (datos.files && datos.files[0]) {

          var pesoFichero = datos.files[0].size/1024;

          if(pesoFichero > pesoPermitido) {

              $('#error_file').html('El peso maximo permitido del fichero es: ' + pesoPermitido / 1024 + ' MB, Su fichero tiene: '+ (pesoFichero /1024).toFixed(2) +' MB.');
              $('#error_file').show();
              $('#valid_file').val(0);
              return false;

          } else {

              return true;

          }

      }

  }

});

</script>

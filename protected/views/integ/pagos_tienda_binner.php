<?php
/* @var $this InventarioController */
/* @var $model Inventario */

?>

<h4>Pagos tienda binner</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'integ-form',
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

<div id="mensaje"></div>

<div class="row">
    <div class="col-sm-8">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_file" style="display: none;"></div>
        <input type="hidden" id="valid_file" value="0">
        <?php echo $form->label($model,'archivo'); ?><br>
        <?php echo $form->fileField($model, 'archivo'); ?>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-upload"></i> Subir archivo</button>
    </div>
</div>


<?php $this->endWidget(); ?>

<script>

$(function() {

  var extensionesValidas = ".csv";
  var textExtensionesValidas = "(.csv)";
  var pesoPermitido = 2048;
  var idInput = "valid_file";
  var idMsg = "error_file";

  $("#valida_form").click(function() {

      var clase_act = $('#mensaje').attr('class');
      $('#mensaje').removeClass(clase_act);
      $("#mensaje").html('');

      var form = $("#integ-form");
      var settings = form.data('settings') ;

      var archivo = $('#Integ_archivo').val();

      if(archivo == ''){
        $('#error_file').html('Debe cargar un archivo.');
        $('#error_file').show();
      }
          
      //se valida si el archivo cargado es valido (1)
      valid_file = $('#valid_file').val();

      if(valid_file == 1){

        //información del formulario
        var formData = new FormData($("#integ-form")[0]);
        var message = ""; 
        //hacemos la petición ajax  
        $.ajax({
            url: "<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=integ/uploadpagostiendabinner'; ?>",  
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
                var mensaje = data.msj; 

                $("#mensaje").addClass("alert alert-warning alert-dismissible");
                $("#mensaje").html(mensaje);
                
                //se resetea el campo FILE
                $("#integ-form")[0].reset();
                $('#valid_file').val(0);
            },
        });

      }
             
  });

  $("#Integ_archivo").change(function () {

      $('#error_file').html('');
      $('#error_file').hide();

      if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {

            $('#valid_file').val(1);

          }
      }  
  });

  $("#Integ_archivo").click(function () {

    var clase_act = $('#mensaje').attr('class');
    $('#mensaje').removeClass(clase_act);
    $("#mensaje").html('');

  });

});

</script>


<?php
/* @var $this FormacionEmpleadoController */
/* @var $model FormacionEmpleado */

//para combos de niveles
$lista_niveles = CHtml::listData($niveles, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script>

$(function() {

	var sop = '<?php echo $model->Soporte; ?>';
  var extensionesValidas = ".pdf";
  var textExtensionesValidas = "(.pdf)";
  var pesoPermitido = 2048;
  var idInput = "valid_sop";
  var idMsg = "error_sop";

	if(sop != ""){

      renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/talento_humano/cert_estudios_emp/'.$model->Soporte; ?>');

      $('#toogle_button').click(function(){
          $('#info').slideToggle('fast');
          $('#viewer').slideToggle('fast');
          return false;
      });

  }

	$("#valida_form").click(function() {
      var form = $("#formacion-empleado-form");
      var settings = form.data('settings') ;

      var soporte = $('#FormacionEmpleado_Soporte').val();

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              	
              //se valida si el archivo cargado es valido (1)
              valid_file = $('#valid_sop').val();

              if(valid_file == 1){
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

    $("#FormacionEmpleado_Soporte").change(function () {

        $('#error_sop').html('');
        $('#error_sop').hide();

      if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {

            $('#valid_sop').val(1);

          }
      }   
    });
});

</script>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Actualización registro de formación empleado</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/view&id='.$e; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <?php if ($model->Soporte != "") {  ?>
          <button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar doc.</button>
        <?php } ?> 
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>     
    </div>
</div>

<?php $this->renderPartial('_form2', array('model'=>$model, 'e' => $e, 'lista_niveles' => $lista_niveles)); ?>
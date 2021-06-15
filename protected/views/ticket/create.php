<?php
/* @var $this TicketController */
/* @var $model Ticket */

//para combos de grupos
$lista_grupos = $grupos;

?>

<script>

$(function() {

  var extensionesValidas = ".jpg";
  var textExtensionesValidas = "(.jpg)";
  var pesoPermitido = 512;
  var idInput = "valid_sop";
  var idMsg = "error_sop";

  $('#Ticket_Prioridad').val(3).trigger('change');

  $("#valida_form").click(function() {
      var form = $("#ticket-form");
      var settings = form.data('settings') ;

      var soporte = $('#Ticket_Soporte').val();

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

    $("#Ticket_Soporte").change(function () {

        $('#error_sop').html('');
        $('#error_sop').hide();

      if(validarExtension(this, extensionesValidas, textExtensionesValidas, idInput, idMsg)) {

          if(validarPeso(this, pesoPermitido, idInput, idMsg)) {

            $('#valid_sop').val(1);

          }
      }   
    });

    $("#Ticket_Id_Grupo").change(function () {
      vlr = $("#Ticket_Id_Grupo").val();
      if(vlr != ""){
        var data = {grupo: vlr}
        $.ajax({ 
          type: "POST", 
          url: "<?php echo Yii::app()->createUrl('ticket/getnovedades'); ?>",
          data: data,
          dataType: 'json',
          success: function(data){ 
            $("#Ticket_Id_Novedad").html('');
            $("#Ticket_Id_Novedad").append('<option value=""></option>');
            $.each(data, function(i,item){
                $("#Ticket_Id_Novedad").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
            });
            $("#div_novedad").show();
          }
        });
      }else{
        $("#Ticket_Id_Novedad").val('');
        $("#div_novedad").hide();
      }
    });

    $("#Ticket_Id_Novedad").change(function () {
      vlr = $("#Ticket_Id_Novedad").val();
      if(vlr != ""){
        var data = {novedad: vlr}
        $.ajax({ 
          type: "POST", 
          url: "<?php echo Yii::app()->createUrl('ticket/getnovedadesdet'); ?>",
          data: data,
          dataType: 'json',
          success: function(data){ 
            $("#Ticket_Id_Novedad_Det").html('');
            $("#Ticket_Id_Novedad_Det").append('<option value=""></option>');
            $.each(data, function(i,item){
                $("#Ticket_Id_Novedad_Det").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
            });
            $("#div_novedad_det").show();
          }
        });
      }else{
        $("#Ticket_Id_Novedad_Det").val('');
        $("#div_novedad_det").hide();
      }
    });

});

</script>

<h4>Registro de ticket</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_grupos'=>$lista_grupos)); ?>
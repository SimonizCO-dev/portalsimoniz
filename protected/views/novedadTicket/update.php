<?php
/* @var $this NovedadTicketController */
/* @var $model NovedadTicket */

//para combos de grupos
$lista_grupos = CHtml::listData($grupos, 'Id_Dominio', 'Dominio');

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres');

?>

<script>

$(function() {

  $('#NovedadTicket_Usuarios').val(<?php echo $json_usuarios_nov_ticket_activos ?>).trigger('change');
  
  $("#div_padre").show();
  loadopc(<?php echo $model->Id_Grupo; ?>);
  
	$("#valida_form").click(function() {
    	var form = $("#novedad-ticket-form");
    	var settings = form.data('settings') ;

      	settings.submitting = true ;
      	$.fn.yiiactiveform.validate(form, function(messages) {
          	if($.isEmptyObject(messages)) {
              	$.each(settings.attributes, function () {
                 	$.fn.yiiactiveform.updateInput(this,messages,form); 
             	});
                 
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

    $('#NovedadTicket_Id_Grupo').change(function() {
        
        $("#NovedadTicket_Id_Novedad_Padre").html('');
        $("#NovedadTicket_Id_Novedad_Padre").append('<option value=""></option>');  

        if($(this).val() != ""){
            $('#div_padre').show();
            loadopc($(this).val());
        }else{
            $('#div_padre').hide();
        }
    });

});

function loadopc(grupo){
    //debugger;
    var p = <?php echo $p ?>;
  
    var data = {grupo: grupo, id: <?php echo $model->Id_Novedad; ?>}
    $.ajax({ 
      type: "POST", 
      url: "<?php echo Yii::app()->createUrl('novedadticket/loadopc'); ?>",
      data: data,
      dataType: 'json',
      success: function(data){ 
        var opcs = data;
        $("#NovedadTicket_Id_Novedad_Padre").html('');
        $("#NovedadTicket_Id_Novedad_Padre").append('<option value=""></option>');
        $('#NovedadTicket_Id_Novedad_Padre').val('').trigger('change');
        $.each(opcs, function(i,item){
            $("#NovedadTicket_Id_Novedad_Padre").append('<option value="'+opcs[i].id+'">'+opcs[i].text+'</option>');
        });

        $("#div_padre").show();

        var p = <?php echo $p ?>;
  
        if(p != 0){
          $('#NovedadTicket_Id_Novedad_Padre').val(p).trigger('change');
        }

      }  
    });
}
   	
</script>

<h4>Actualización novedad de ticket</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_grupos' => $lista_grupos, 'lista_usuarios' => $lista_usuarios)); ?>
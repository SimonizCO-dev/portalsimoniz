<?php
/* @var $this ActividadController */
/* @var $model Actividad */

//para combos de grupos
$lista_grupos = $grupos;

//para combos de tipos
$lista_tipos = $tipos;

//para combos de usuarios
$lista_usuarios = $usuarios;

?>

<script>

$(function() {
	  
    $('#Actividad_Id_Grupo').val(<?php echo $model->Id_Grupo ?>).trigger('change');
    var estado = $('#Actividad_Estado').val();

    if(estado == 3){
      $('#user_deleg').show();
    }else{
      $('#user_deleg').hide();
    }
    

    $("#valida_form").click(function() {

      var form = $("#actividad-form");
      var settings = form.data('settings');

      var estado = $("#Actividad_Estado").val();
      var fecha_cierre = $("#Actividad_Fecha_Cierre").val();
      var hora_cierre = $("#Actividad_Hora_Cierre").val();
      var usuario_deleg = $("#Actividad_Id_Usuario_Deleg").val();

      $valid = 0;

      if(estado == 2){   

        if(fecha_cierre != "" && hora_cierre != ""){
            $valid = 1;
        }else{
            $valid = 0;
        }

      }

      if(estado == 3){   

        if(usuario_deleg != ""){
            $valid = 1;
        }else{
            $valid = 0;
        }

      }

      if(estado == 1 || estado == 4 || estado == 5 || estado == 6 || estado == 7){   
        $valid = 1;
      }

      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
            $.each(settings.attributes, function () {
                $.fn.yiiactiveform.updateInput(this,messages,form); 
            });

            if($valid == 1){
                //se envia el form
                form.submit();
                loadershow();
            }else{
              if(estado == 2){   
    
                if(fecha_cierre == ""){
                    $('#Actividad_Fecha_Cierre_em_').html('Fecha de cierre es requerido.');
                    $('#Actividad_Fecha_Cierre_em_').show(); 
                }

                if(hora_cierre == ""){
                    $('#Actividad_Hora_Cierre_em_').html('Hora de cierre es requerido.');
                    $('#Actividad_Hora_Cierre_em_').show(); 
                }

              }

              if(estado == 3){   

                if(usuario_deleg == ""){
                    $('#Actividad_Id_Usuario_Deleg_em_').html('Cedido a es requerido.');
                    $('#Actividad_Id_Usuario_Deleg_em_').show(); 
                } 
              }
            }
            
          } else {
              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              if(estado == 2){   
    
                if(fecha_cierre == ""){
                    $('#Actividad_Fecha_Cierre_em_').html('Fecha de cierre es requerido.');
                    $('#Actividad_Fecha_Cierre_em_').show(); 
                }

                if(hora_cierre == ""){
                    $('#Actividad_Hora_Cierre_em_').html('Hora de cierre es requerido.');
                    $('#Actividad_Hora_Cierre_em_').show(); 
                }

              }

              if(estado == 3){   

                if(usuario_deleg == ""){
                    $('#Actividad_Id_Usuario_Deleg_em_').html('Cedido a es requerido.');
                    $('#Actividad_Id_Usuario_Deleg_em_').show(); 
                }
                 
              }

              settings.submitting = false ;
          }
      });
    });

    $('#Actividad_Estado').change(function() {
      
      var value = $(this).val();
        
      if(value != ""){

        $('#Actividad_Fecha_Cierre_em_').html('');
        $('#Actividad_Hora_Cierre_em_').html('');
        $('#Actividad_Id_Usuario_Deleg_em_').html('');
        $('#Actividad_Fecha_Cierre').val('');
        $('#Actividad_Hora_Cierre').val('');
        $('#Actividad_Id_Usuario_Deleg').val('');

        if(value == 2){
           //COMPLETADA
          $('#user_deleg').hide();
          $('#fecha_cierre').show();
          $('#hora_cierre').show();

        }

        if(value == 3){
          //EN ESPERA
          $('#user_deleg').show();
          $('#fecha_cierre').hide();
          $('#hora_cierre').hide();
        }

        if(value == 1 || value == 4 || value == 5 || value == 6 || value == 7){
          //RECIBIDO, EVALUADO, ANULADO, RECHAZADO, APROBADO
          $('#user_deleg').hide();
          $('#fecha_cierre').hide();
          $('#hora_cierre').hide();

        }

      }else{
        $("#user_deleg").hide();
        $('#fecha_cierre').hide();
        $('#hora_cierre').hide();
      }
     
    });

    $("#Actividad_Id_Grupo").change(function () {
      vlr = $("#Actividad_Id_Grupo").val();
      if(vlr != ""){
        var data = {grupo: vlr}
        $.ajax({ 
          type: "POST", 
          url: "<?php echo Yii::app()->createUrl('actividad/gettipos'); ?>",
          data: data,
          dataType: 'json',
          success: function(data){ 
            $("#Actividad_Id_Tipo").html('');
            $("#Actividad_Id_Tipo").append('<option value=""></option>');
            $.each(data, function(i,item){
                $("#Actividad_Id_Tipo").append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
            });
            $("#Actividad_Id_Tipo").val('').trigger('change');
            $("#div_tipo").show();
          }
        });
      }else{
        $("#Actividad_Id_Tipo").val('').trigger('change');
        $("#div_tipo").hide();
      }
    });

    $('#Actividad_Fecha_Cierre').change(function() {
        var estado = $("#Actividad_Estado").val();
        var val = $('#Actividad_Fecha_Cierre').val();
        if(estado == 2){   
            if(val == ""){
                $('#Actividad_Fecha_Cierre_em_').html('Fecha de cierre es requerido.');
                $('#Actividad_Fecha_Cierre_em_').show(); 
            }else{
                $('#Actividad_Fecha_Cierre_em_').html('');
                $('#Actividad_Fecha_Cierre_em_').hide(); 
            }

        }else{
            $('#Actividad_Fecha_Cierre_em_').html('');
            $('#Actividad_Fecha_Cierre_em_').hide(); 
        
        }
      
    });

    $('#Actividad_Hora_Cierre').change(function() {
        var estado = $("#Actividad_Estado").val();
        var val = $('#Actividad_Hora_Cierre').val();
        if(estado == 2){   
            if(val == ""){
                $('#Actividad_Hora_Cierre_em_').html('Hora de cierre es requerido.');
                $('#Actividad_Hora_Cierre_em_').show(); 
            }else{
                $('#Actividad_Hora_Cierre_em_').html('');
                $('#Actividad_Hora_Cierre_em_').hide(); 
            }

        }else{
            $('#Actividad_Hora_Cierre_em_').html('');
            $('#Actividad_Hora_Cierre_em_').hide(); 
        
        }
      
    });

    $('#Actividad_Id_Usuario_Deleg').change(function() {
        var estado = $("#Actividad_Estado").val();
        var val = $('#Actividad_Id_Usuario_Deleg').val();
        if(estado == 3){   
            if(val == ""){
                $('#Actividad_Id_Usuario_Deleg_em_').html('Cedido a es requerido.');
                $('#Actividad_Id_Usuario_Deleg_em_').show(); 
            }else{
                $('#Actividad_Id_Usuario_Deleg_em_').html('');
                $('#Actividad_Id_Usuario_Deleg_em_').hide(); 
            }

        }else{
            $('#Actividad_Id_Usuario_Deleg_em_').html('');
            $('#Actividad_Id_Usuario_Deleg_em_').hide(); 
        
        }
      
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

	$("#Actividad_Fecha_Cierre").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	  startDate: '<?php echo $model->Fecha ?>',
	});

});

</script>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Resumen de actividad</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=actividad/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <?php if($model->Estado != 2 && $model->Estado != 5){ ?>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
        <?php } ?>
  </div>
</div>

<?php $this->renderPartial('_form2', array('model'=>$model, 'hist'=>$hist, 'lista_grupos'=>$lista_grupos, 'lista_tipos'=>$lista_tipos, 'lista_usuarios'=>$lista_usuarios)); ?>
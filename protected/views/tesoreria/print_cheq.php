<?php
/* @var $this TesoreriaController */
/* @var $model Tesoreria */

?>

<div id="div_mensaje" style="display: none;"></div>

<h4>Impresión de cheque</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'tesoreria-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableClientValidation'=>true,
  'clientOptions'=>array(
    'validateOnSubmit'=>true,
  ),
)); ?>

<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'cia', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'cia'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Tesoreria[cia]',
              'id'=>'Tesoreria_cia',
              'data'=> array(2 => '2 - SIMONIZ', 3 => '3 - TITAN', 4 => '4 - COMSTAR', 5 => '5 - PANSELL'),
              'htmlOptions'=>array(),
              'options'=>array(
                'placeholder'=>'Seleccione...',
                'width'=> '100%',
                'allowClear'=>true,
              ),
          ));
      ?>
    </div>
   </div>
   <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'co', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'co'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Tesoreria[co]',
              'id'=>'Tesoreria_co',
              'data'=> $lista_co,
              'htmlOptions'=>array(),
              'options'=>array(
                'placeholder'=>'Seleccione...',
                'width'=> '100%',
                'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'tipo'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Tesoreria[tipo]',
              'id'=>'Tesoreria_tipo',
              'data'=> $lista_tipos,
              'htmlOptions'=>array(),
              'options'=>array(
                'placeholder'=>'Seleccione...',
                'width'=> '100%',
                'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->error($model,'consecutivo', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'consecutivo'); ?>
        <?php echo $form->numberField($model,'consecutivo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'firma', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'firma'); ?>
      <?php echo $form->textField($model,'firma', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off')); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-search"></i> Buscar</button>
      <button type="button" class="btn btn-primary btn-sm" id="print" style="display: none;"><i class="fas fa-print"></i> Imprimir</button>

    </div>
</div>    

<div class="row">
    <iframe src="" id="viewer" class="col-sm-12 text-center" style="display: none;">
      
    </iframe>   
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#Tesoreria_cia").change(function() {
    $('#valida_form').show();
    $('#print').hide();
    limp_div_msg();
  });

  $("#Tesoreria_co").change(function() {
    $('#valida_form').show();
    $('#print').hide();
    limp_div_msg();
  });

  $("#Tesoreria_tipo").change(function() {
    $('#valida_form').show();
    $('#print').hide();
    limp_div_msg();
  });

  $("#Tesoreria_consecutivo").change(function() {
    $('#valida_form').show();
    $('#print').hide();
    limp_div_msg();
  });

  $("#Tesoreria_firma").change(function() {
    $('#valida_form').show();
    $('#print').hide();
    limp_div_msg();
  });

  $("#valida_form").click(function() {

      var form = $("#tesoreria-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      limp_div_msg();

      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              var cia = $('#Tesoreria_cia').val();
              var co = $('#Tesoreria_co').val();
              var tipo = $('#Tesoreria_tipo').val();
              var consecutivo = $('#Tesoreria_consecutivo').val();
              var firma = $('#Tesoreria_firma').val();

              var data = {
                cia: cia, 
                co: co, 
                tipo: tipo,
                consecutivo: consecutivo,
                firma: firma
              }

              $.ajax({ 
                  type: "POST", 
                  url: "<?php echo Yii::app()->createUrl('tesoreria/existcheq'); ?>",
                  data: data,
                  beforeSend: function(){
                      $(".ajax-loader").fadeIn('fast'); 
                  },
                  success: function(resp){

                      if(resp == 1){
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Este cheque ya fue impreso.'); 
                      }

                      if(resp == 0){
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>No se encontraron datos para un cheque con los criterios de busqueda utilizados.'); 
                      }


                      if(resp == 2){
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Por favor oprima el botón imprimir.');
                        
                        var name_file = cia+"_"+co+"_"+tipo+"_"+consecutivo+".pdf";
                        var iframe = $("#viewer");

                        var url = "<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/cheques/'; ?>"+name_file;
                        iframe.attr('src',url);
                        $('#print').show();
                        $('#valida_form').hide();
                      }

                      
                      $("#div_mensaje").fadeIn('fast');
                      $(".ajax-loader").fadeOut('fast');
                    
                  }
              });

          } else {

              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              settings.submitting = false ;
          }
      });
  });

  $("#print").click(function() {
    var cia = $('#Tesoreria_cia').val();
    var co = $('#Tesoreria_co').val();
    var tipo = $('#Tesoreria_tipo').val();
    var consecutivo = $('#Tesoreria_consecutivo').val();
    var firma = $('#Tesoreria_firma').val();

    var data = {
      cia: cia, 
      co: co, 
      tipo: tipo,
      consecutivo: consecutivo,
      firma: firma
    }

    $.ajax({ 
        type: "POST", 
        url: "<?php echo Yii::app()->createUrl('tesoreria/regimpcheq'); ?>",
        data: data,
        beforeSend: function(){
            $(".ajax-loader").fadeIn('fast'); 
        },
        success: function(resp){

            if(resp == 0){

              $('#Tesoreria_cia').val('').trigger('change');
              $('#Tesoreria_co').val('').trigger('change');
              $('#Tesoreria_tipo').val('').trigger('change');
              $('#Tesoreria_consecutivo').val('');
              $('#Tesoreria_firma').val('');
              $('#print').hide();
              $('#valida_form').show();

              $('html, body').animate({scrollTop:0}, 'fast');
              $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
              $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>No se registro la impresión del cheque '+cia+'-'+co+'-'+tipo+'-'+consecutivo+' en el sistema.');

              $("#div_mensaje").fadeIn('fast');
              $(".ajax-loader").fadeOut('fast');

              var iframe = $("#viewer");
              var url = "#";
              iframe.attr('src',url);
            }

            if(resp == 1){

              $('#Tesoreria_cia').val('').trigger('change');
              $('#Tesoreria_co').val('').trigger('change');
              $('#Tesoreria_tipo').val('').trigger('change');
              $('#Tesoreria_consecutivo').val('');
              $('#Tesoreria_firma').val('');
              $('#print').hide();
              $('#valida_form').show();

              $('html, body').animate({scrollTop:0}, 'fast');
              $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
              $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>Se registro la impresión del cheque '+cia+'-'+co+'-'+tipo+'-'+consecutivo+' en el sistema.');

              $("#div_mensaje").fadeIn('fast');
              $(".ajax-loader").fadeOut('fast');

              var objFra = document.getElementById('viewer');
              objFra.contentWindow.focus();
              objFra.contentWindow.print();

            }
          
        }
    });

  });
  
});

function resetfields(){
  $('#Tesoreria_cia').val('').trigger('change');
  $('#Tesoreria_co').val('').trigger('change');
  $('#Tesoreria_tipo').val('').trigger('change');
  $('#Tesoreria_consecutivo').val('');
  $('#Tesoreria_firma').val('');
  $('#print').hide();
  $('#valida_form').show();
  var iframe = $("#viewer");
  var url = "#";
  iframe.attr('src',url);
  limp_div_msg();
}

</script>

<?php
/* @var $this ReportePaController */
/* @var $model ReportePa */


//para combos de tipos de equipo
$lista_tipos_equipo = CHtml::listData($tipos_equipo, 'Id_Dominio', 'Dominio');

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Pa_Empresa', 'Descripcion');

?>

<div id="div_mensaje" style="display: none;"></div>

<h4>Soporte(s) equipo(s)</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reporte-pa-form',
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
      <?php echo $form->error($model,'opc', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'opc'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'ReportePa[opc]',
              'id'=>'ReportePa_opc',
              'data'=> array(1 => 'INDIVIDUAL' , 2 => 'GRUPO'),
              'htmlOptions'=>array(
              ),
              'options'=>array(
                  'placeholder'=>'Seleccione...',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-8" id="div_equipo" style="display: none;">
    <div class="form-group">
          <?php echo $form->error($model,'equipo', array('class' => 'badge badge-warning float-right')); ?>
          <?php echo $form->label($model,'equipo'); ?>

          <?php echo $form->textField($model,'equipo'); ?>
          <?php
              $this->widget('ext.select2.ESelect2', array(
                  'selector' => '#ReportePa_equipo',
                  'options'  => array(
                      'allowClear' => true,
                      'minimumInputLength' => 3,
                      'width' => '100%',
                      'language' => 'es',
                      'ajax' => array(
                          'url' => Yii::app()->createUrl('ReportePa/SearchEquipo'),
                          'dataType'=>'json',
                          'data'=>'js:function(term){return{q: term};}',
                          'results'=>'js:function(data){ return {results:data};}'                   
                      ),
                      'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ReportePa_equipo"); return "No se encontraron resultados"; }',
                      'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'ReportePa_equipo\')\">Limpiar campo</button>"; }',
                  ),
              ));
          ?>
      </div>
  </div>
  <div class="col-sm-4" id="div_f_i" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'fecha_compra_inicial', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'fecha_compra_inicial'); ?>
      <?php echo $form->textField($model,'fecha_compra_inicial', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4" id="div_f_f" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'fecha_compra_final', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'fecha_compra_final'); ?>
      <?php echo $form->textField($model,'fecha_compra_final', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div>
</div>
<div class="row" id="div_empresa" style="display: none;">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'empresa_compra', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'empresa_compra'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'ReportePa[empresa_compra]',
              'id'=>'ReportePa_empresa_compra',
              'data'=>$lista_empresas,
              'htmlOptions'=>array(
                //'multiple'=>'multiple',
              ),
              'options'=>array(
                  'placeholder'=>'Seleccione...',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-8" id="div_tipos" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'tipo_equipo', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'tipo_equipo'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'ReportePa[tipo_equipo]',
              'id'=>'ReportePa_tipo_equipo',
              'data'=>$lista_tipos_equipo,
              'htmlOptions'=>array(
                'multiple'=>'multiple',
              ),
              'options'=>array(
                  'placeholder'=>'TODOS',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>          
</div>

<div class="row mb-2">
    <div class="col-sm-6"> 
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-archive"></i> Generar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

  $('#ReportePa_opc').change(function(){
    
    opc = this.value;
    
    //Se limpia todo

    $('#div_equipo').hide();
    $('#ReportePa_equipo').val('').trigger('change');
    $('#s2id_ReportePa_equipo span').html("");
    
    $('#ReportePa_equipo_em_').html('');
    $('#ReportePa_equipo_em_').hide();
    
    $('#div_f_i').hide();
    $("#ReportePa_fecha_compra_inicial").val(); 
    
    $('#div_f_f').hide();
    $("#ReportePa_fecha_compra_final").val();
    
    $('#div_empresa').hide();
    $('#ReportePa_empresa_compra_em_').html('');
    $('#ReportePa_empresa_compra_em_').hide();
    $('#ReportePa_empresa_compra').val('').trigger('change');
    
    $('#div_tipos').hide();
    $('#ReportePa_tipo_equipo').val('').trigger('change');

    if(opc != ""){
      if(opc == 1){
        //individual
        $('#div_equipo').show();
      }else{
        //grupal
        $('#div_f_i').show();
        $('#div_f_f').show();
        $('#div_empresa').show();
        $('#div_tipos').show();
      }
    }
    
  });

  $('#ReportePa_equipo').change(function(){
    if(this.value != ""){
      $('#ReportePa_equipo_em_').html('');
      $('#ReportePa_equipo_em_').hide(); 
    }
  });

  $('#ReportePa_empresa_compra').change(function(){
    if(this.value != ""){
      $('#ReportePa_empresa_compra_em_').html('');
      $('#ReportePa_empresa_compra_em_').hide(); 
    }
  });

  $("#valida_form").click(function() {

      var form = $("#reporte-pa-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
        if($.isEmptyObject(messages)) {
          $.each(settings.attributes, function () {
             $.fn.yiiactiveform.updateInput(this,messages,form); 
          });

          var opc = $('#ReportePa_opc').val();
          var serial = $('#ReportePa_equipo').val();
          var fecha_compra_inicial = $('#ReportePa_fecha_compra_inicial').val();
          var fecha_compra_final = $('#ReportePa_fecha_compra_final').val();
          var empresa_compra = $('#ReportePa_empresa_compra').val();

          var cad_tipos_equipo = "";

          $('#ReportePa_tipo_equipo :selected').each(function(i, sel){ 
              cad_tipos_equipo += $(sel).val()+','; 
          });

          if(cad_tipos_equipo != ""){
            var tipos_equipo = cad_tipos_equipo.slice(0,-1);
          }else{
            var tipos_equipo = "";  
          }

          if(opc == 1){
            //individual
            if(serial == ""){

              if(serial == ""){
                $('#ReportePa_equipo_em_').html('Serial es requerido.');
                $('#ReportePa_equipo_em_').show(); 
              }

              $valid = 0;

            }else{
              $('#ReportePa_equipo_em_').html('');
              $('#ReportePa_equipo_em_').hide();

              $valid = 1;
            }
          }else{
            //grupo
            if(empresa_compra == ""){
              
              if(empresa_compra == ""){
                $('#ReportePa_empresa_compra_em_').html('Empresa es requerido.');
                $('#ReportePa_empresa_compra_em_').show();
              }

              $valid = 0;

            }else{
              $('#ReportePa_empresa_compra_em_').html('');
              $('#ReportePa_empresa_compra_em_').hide();

              $valid = 1;
            }
          }

          if($valid == 1){
            var data = {
              opc: opc, 
              serial: serial, 
              fecha_compra_inicial: fecha_compra_inicial,
              fecha_compra_final: fecha_compra_final,
              empresa_compra: empresa_compra,
              tipos_equipo: tipos_equipo
            }
            
            $.ajax({ 
              type: "POST", 
              url: "<?php echo Yii::app()->createUrl('ReportePa/soportese'); ?>",
              data: data,
              dataType: "json",
              beforeSend: function(){
                $(".ajax-loader").fadeIn('fast'); 
              },
              success: function(data){

                var resp = data.resp; 
                var mensaje = data.msg;
                var ruta = data.ruta;  
                var archivo = data.archivo;

                if(resp == 0){

                  $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5><p>'+mensaje+'</p>');

                  $("#div_mensaje").fadeIn('fast');
                  $(".ajax-loader").hide('fast');
                }

                if(resp == 1){

                  $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-check-circle"></i>Realizado</h5><p>'+mensaje+'</p>'); 
                      
                  var link = document.createElement("a");
                  link.download = archivo;
                  link.href = ruta;
                  link.click(); 

                  $("#div_mensaje").fadeIn('fast');
                  $(".ajax-loader").hide('fast');
                }                 
              }
            });
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

  $("#ReportePa_fecha_compra_inicial").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#ReportePa_fecha_compra_final').datepicker('setStartDate', minDate);
  });

  $("#ReportePa_fecha_compra_final").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#ReportePa_fecha_compra_inicial').datepicker('setEndDate', maxDate);
  });

});

function resetfields(){
  $('#ReportePa_opc').val('').trigger('change');
}

//función para limpiar el mensaje retornado por el ajax
function limp_div_msg(){
  $("#div_mensaje").hide();  
  classact = $('#div_mensaje').attr('class');
  $("#div_mensaje").removeClass(classact);
  $("#mensaje").html('');
}
  
</script>






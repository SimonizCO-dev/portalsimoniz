<?php
/* @var $this ComisionController */
/* @var $model Comision */

//para combo de estados de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

?>

<div id="div_mensaje" style="display: none;"></div>

<h4>Liquidación CMS</h4>

  <?php $form=$this->beginWidget('CActiveForm', array(
  	'id'=>'comision-form',
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
        <?php echo $form->error($model,'mes', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'mes'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Comision[mes]',
                'id'=>'Comision_mes',
                'data'=> array(1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE',10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'),
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
        <?php echo $form->error($model,'anio', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'anio'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Comision[anio]',
                'id'=>'Comision_anio',
                'data'=> array(date("Y") - 1 => date("Y") - 1, date("Y") => date("Y")),
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
        <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'tipo'); ?>
        
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Comision[tipo]',
                'id'=>'Comision_tipo',
                'data'=>$lista_tipos,
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
        <?php echo $form->error($model,'liquidacion', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'liquidacion'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Comision[liquidacion]',
                'id'=>'Comision_liquidacion',
                'data'=>array(1 => 'INDIVIDUAL', 2 => 'TODOS LOS VENDEDORES'),
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
  <div class="row" id="div_vendedor" style="display: none;">
    <div class="col-sm-8">
      <div class="form-group">
            <?php echo $form->error($model,'id_vendedor', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'id_vendedor'); ?>
            <?php echo $form->textField($model,'id_vendedor'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Comision_id_vendedor',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('comision/SearchVendedor'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term, tipo: $("#Comision_tipo").val()};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Comision_id_vendedor"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Comision_id_vendedor\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-8">
      <div class="form-group">
        <?php echo $form->error($model,'observaciones', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'observaciones'); ?>
        <?php echo $form->textArea($model,'observaciones',array('class' => 'form-control form-control-sm', 'rows'=>1, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
      </div>
    </div>
  </div>

  
  <div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Liquidar</button>
    </div>
  </div>

<div class="row" id="resultados" style="display: none;">
    <div class="col-lg-12 table-responsive" id="content">
    <!-- contenido via ajax -->
    </div>
</div>


<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#valida_form").click(function() {

      var form = $("#comision-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;

      var valid = 1;

      var liquidacion = $('#Comision_liquidacion').val();

      var obs = $('#Comision_observaciones').val();
      var mes = $('#Comision_mes').val();
      var anio = $('#Comision_anio').val();

      if(liquidacion == 1){
        var vendedor = $('#Comision_id_vendedor').val();
        if(vendedor == ""){
          valid = 0;
        }
      }

      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages) && valid == 1) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
             
              if(liquidacion == 1){

                //LIQ. INDIVIDUAL

                vend = $('#Comision_id_vendedor').val();
                tip = $('#Comision_tipo').val();
                url = "<?php echo Yii::app()->createUrl('comision/calcliqind'); ?>";

                //se limpia el div de el mensaje antes de hacer la petición
                limp_div_msg();

                //AJAX
                var data = {mes: mes, anio: anio, tipo: tip, liquidacion: liquidacion, vendedor: vend, observaciones: obs}
                $.ajax({ 
                  type: "POST", 
                  url: url,
                  data: data,
                  dataType: 'json',
                  beforeSend: function(){
                    $(".ajax-loader").fadeIn('fast'); 
                  },
                  success: function(data){

                    var res = data.res; 
                    var mensaje = data.msg; 

                    if(res == 0){
                      //EL PROCESO NO GENERO NINGUNA LIQUIDACIÓN
                      $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>'+mensaje);
                    }

                    if(res == 1){
                      //EL PROCESO GENERO NINGUNA LIQUIDACIÓN
                      $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-check-circle"></i>Realizado</h5>'+mensaje);  
                    }

                    $("#div_mensaje").fadeIn('fast');
                    $(".ajax-loader").fadeOut('fast');

                    
                  },
                });
 
              }else{

                //LIQ. TODOS x TIPO

                vend = 0;
                tip = $("#Comision_tipo").val();
                url = "<?php echo Yii::app()->createUrl('comision/calcliqall'); ?>";

                //se limpia el div de el mensaje antes de hacer la petición
                limp_div_msg();

                //AJAX
                var data = {mes: mes, anio: anio, tipo: tip, liquidacion: liquidacion, vendedor: vend, observaciones: obs}
                $.ajax({ 
                  type: "POST", 
                  url: url,
                  dataType: 'json',
                  data: data,
                  beforeSend: function(){
                    $(".ajax-loader").fadeIn('fast'); 
                  },
                  success: function(data){

                    var res = data.res; 
                    var mensaje = data.msg; 

                    if(res == 0){
                      //EL PROCESO NO GENERO NINGUNA LIQUIDACIÓN
                      $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-info-circle"></i>Info</h5>'+mensaje);
                    }

                    if(res == 1){
                      //EL PROCESO GENERO NINGUNA LIQUIDACIÓN
                      $("#div_mensaje").addClass("alert alert-primary alert-dismissible");
                      $("#div_mensaje").html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-check-circle"></i>Realizado</h5>'+mensaje);  
                    }

                    $("#div_mensaje").fadeIn('fast');
                    $(".ajax-loader").fadeOut('fast');

                    
                  },
                });  

              }

              //se resetea el formulario para una nueva liquidación
              $('#Comision_mes').val('').trigger('change');
              $('#Comision_anio').val('').trigger('change');
              $('#Comision_tipo').val('').trigger('change');
              $('#Comision_liquidacion').val('').trigger('change');
              $('#Comision_id_vendedor').val('').trigger('change');
              $('#s2id_Comision_id_vendedor span').html("");
              $('#div_vendedor').hide();
              $('#Comision_observaciones').val('');

          } else {
              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              if(liquidacion == 1){
                var vendedor = $('#Comision_id_vendedor').val();
                if(vendedor == ""){
                    $('#Comision_id_vendedor_em_').html('Vendedor es requerido.');
                    $('#Comision_id_vendedor_em_').show();
                }
              }

              settings.submitting = false ;
          }
      });
  });

  $('#Comision_tipo').change(function() {  
      var tipo = $(this).val();
      var liquidacion = $('#Comision_liquidacion').val();
      
      $('#Comision_id_vendedor_em_').html('');
      $('#Comision_id_vendedor_em_').hide();
      $('#Comision_id_vendedor').val('').trigger('change');
      $('#s2id_Comision_id_vendedor span').html("");

      if(tipo != "" && liquidacion != ""){
          if(liquidacion == 1){
            $('#div_vendedor').show();
          }
          if(liquidacion == 2){
            $('#div_vendedor').hide();
          }
      }else{
          $('#div_vendedor').hide();
      }
  });

  $('#Comision_liquidacion').change(function() {  
      var tipo = $('#Comision_tipo').val();
      var liquidacion = $(this).val();
      
      $('#Comision_id_vendedor_em_').html('');
      $('#Comision_id_vendedor_em_').hide();
      $('#Comision_id_vendedor').val('').trigger('change');
      $('#s2id_Comision_id_vendedor span').html("");

      if(tipo != "" && liquidacion != ""){
          if(liquidacion == 1){
            $('#div_vendedor').show();
          }
          if(liquidacion == 2){
            $('#div_vendedor').hide();
          }
      }else{
          $('#div_vendedor').hide();
      }
  });

});

function view_progress(){

  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('comision/progliq'); ?>",
    success: function(content){
    
      if(content != ""){
        $(".ajax-loader").hide();  
        $('#content').html('');
        $('#formulario').hide();
        $('#resultados').show(); 
        $('#content').html(content);

      }else{
        //reset fields
        location.reload();
      }
    }  
  });
}

</script>

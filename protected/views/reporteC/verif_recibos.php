<h4>Verificación de recibos</h4>

<?php 

header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$form=$this->beginWidget('CActiveForm', array(
  'id'=>'reporte-c-form',
  // Please note: When you enable ajax validation, make sure the corresponding
  // controller action is handling ajax validation correctly.
  // There is a call to performAjaxValidation() commented in generated controller code.
  // See class documentation of CActiveForm for details on this.
  'enableClientValidation'=>true,
  'clientOptions'=>array(
    'validateOnSubmit'=>true,
  ),
));

?>

<?php echo $form->hiddenField($model,'opc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
<?php echo $form->hiddenField($model,'recibos', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
<?php echo $form->hiddenField($model,'opc_ver', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
<?php echo $form->hiddenField($model,'obs_ver', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
<?php echo $form->hiddenField($model,'fec_ver', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
<?php echo $form->hiddenField($model,'fec_che', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
<?php echo $form->hiddenField($model,'obs_rec', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label>Ruta / Recibo</label>
            <?php echo $form->textField($model,'ruta', array('class' => 'form-control form-control-sm', 'maxlength' => '60', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'fecha_cheque'); ?>
            <?php echo $form->textField($model,'fecha_cheque', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>   
</div> 

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="buscarrecibos();"><i class="fa fa-search"></i> Buscar</button>
    </div>
</div>  

<?php

echo $form->error($model,'opc', array('class' => 'badge badge-warning float-right mb-2'));
echo '<div id="contenido"></div>';

?>

<div class="row mb-2">
    <div class="col-sm-12">  
        <button type="button" class="btn btn-primary btn-sm" onclick="window.location.reload();" id="btn_refresh"><i class="fas fa-sync"></i> Actualizar vista</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="valida_opciones_che();" id="btn_submit_ver_che"><i class="fas fa-save"></i> Guardar fecha(s) de cheque(s) seleccionado(s)</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="valida_opciones_ver();" id="btn_submit_ver"><i class="fas fa-check"></i> Verificar recibo(s) seleccionado(s)</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="valida_opciones_obs();" id="btn_submit_obs"><i class="fas fa-save"></i> Guardar observacion(es) seleccionada(s)</button>
    </div>
</div>  

<?php $this->endWidget(); ?>


<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>  
            <div class="modal-body text-center">  
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>

$(function() {

  $(".form-control form-control-sm").keydown(function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
    }
  });

  $('#btn_submit_ver').hide();
  cargarrecibosverif(0,0,0);

});

function rotateimage(id){
  $('.ajax-loader').fadeIn('fast');
  var data = {id: id}
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('controlrecibos/rotateimage'); ?>",
    data: data,
    dataType: 'json',
    success: function(id){
      var url = "<?php echo Yii::app()->createUrl('controlRecibos/ViewRecibo&id='); ?>";
      $('.modal-body').load(url+id,function(){
        $('#myModal').modal({show:true});
      }); 
      $('.ajax-loader').fadeOut('fast');
    }
  });
}

function resetmsn(){
  $('#ReporteC_opc_em_').hide();
  $('#ReporteC_opc_em_').html('');
}

function uncheckgroup(sel){
  $('#ReporteC_opc_em_').hide();
  $('#ReporteC_opc_em_').html('');
  $('input[name="' + sel.name + '"]').not(sel).prop('checked', false);

  var id = sel.id;

  if($(sel).prop('checked')) {
    var valor = sel.value;
    if(valor == 1){
      $('#b_'+id).val('');
      $('#b_'+id).hide();
      $('#m_'+id).val('');
      $('#m_'+id).hide();
      $('#l_'+id).show();
    }
    if(valor == 2){
      $('#b_'+id).val('');
      $('#b_'+id).show();
      $('#m_'+id).val('');
      $('#m_'+id).hide();
      $('#l_'+id).hide();
    }
    if(valor == 3 || valor == 4){
      $('#b_'+id).val('');
      $('#b_'+id).hide();
      $('#m_'+id).val('');
      $('#m_'+id).show();
      $('#l_'+id).hide();
    }
  }else{
    $('#b_'+id).val('');
    $('#b_'+id).hide();
    $('#m_'+id).val('');
    $('#m_'+id).hide();
    $('#l_'+id).show();   
  }
}

function hidemsg(){
  $('#ReporteC_opc_em_').hide();
  $('#ReporteC_opc_em_').html('');
}

function buscarrecibos(){
  //se buscan los recibos por el filtro
  var ruta = $('#ReporteC_ruta').val();
  var fecha_cheq = $('#ReporteC_fecha_cheque').val();

  if(ruta == "" && fecha_cheq == ""){
    
    cargarrecibosverif(0,0,0);
  
  }else{
    
    if(ruta != "" && fecha_cheq == ""){

      cargarrecibosverif(1,ruta,0);

    }

    if(ruta == "" && fecha_cheq != ""){

      cargarrecibosverif(2,0,fecha_cheq);

    }

    if(ruta != "" && fecha_cheq != ""){

      cargarrecibosverif(3,ruta,fecha_cheq);

    }

  }

}

function cargarrecibosverif(opc,ruta,fecha_cheq){
  //funcion para cargar los recibos por verificar
  $('#contenido').html('');

  $('#btn_submit_ver_che').hide();
  $('#btn_submit_ver').hide();
  $('#btn_submit_obs').hide();

  $('.ajax-loader').fadeIn('fast');
  var data = {opc: opc, ruta: ruta, fecha_cheq: fecha_cheq}
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('controlrecibos/CargarRecibosVerif'); ?>",
    data: data,
    dataType: 'json',
    success: function(data){

      $('#contenido').html(data.info);
      $('.ajax-loader').fadeOut('fast');

      if(data.opc == 1){
        //hay info
        $('#btn_submit_ver').show();   
      }

      if(data.opc_che == 1){
        //hay info
        $('#btn_submit_ver_che').show();   
      }

      if(data.opc_obs == 1){
        //hay info
        $('#btn_submit_obs').show();   
      } 

    }
  });
}

function resetfields(){
  $('#ReporteC_ruta').val('');
  $('#ReporteC_fecha_cheque').val('');
  cargarrecibosverif(0,0,0); 
}

function viewrecibo(id){
  var url = "<?php echo Yii::app()->createUrl('controlRecibos/ViewRecibo&id='); ?>";
  $('.modal-body').load(url+id,function(){
    $('#myModal').modal({show:true});
  });
}

function valida_opciones_ver(){
  
  $('#ReporteC_recibos').val('');
  $('#ReporteC_opc_ver').val('');
  $('#ReporteC_fec_ver').val('');
  $('#ReporteC_obs_rec').val('');
  $('#ReporteC_fec_che').val('' );
  $('#ReporteC_opc_em_').hide();
  $('#ReporteC_opc_em_').html('');
      
  var ids_selected = ''; 
  var val_selected = '';
  var fec_selected = '';
  var obs_selected = '';

  var v = 1;

    $('input.grupo[type=checkbox]').each(function(){
        if (this.checked) {

            valor = $(this).val();
            id = $(this).attr('id');

            if(valor == 1){
              //aprobado
              var obs = 0;
              obs_selected += obs+','; 
            }

            if(valor == 2){
              //rechazado por banco
              var b_c = $('#b_'+id).val();
              if(b_c == ""){
                var obs = 0;
                obs_selected += obs+','; 
                v = 0;
              }else{
                $('#ReporteC_opc_em_').hide();
                $('#ReporteC_opc_em_').html('');
                obs_selected += b_c+','; 
              }

            }

            if(valor == 3 || valor == 4){
              //rechazado por valor / fecha
              var m_r = $('#m_'+id).val();
              if(m_r == ""){
                var obs = 0;
                obs_selected += obs+','; 
                v = 0;
              }else{
                $('#ReporteC_opc_em_').hide();
                $('#ReporteC_opc_em_').html('');
                obs_selected += m_r+','; 
              }
            }

          
            ids_selected += $(this).attr('id')+',';
            val_selected += $(this).val()+',';
            id = $(this).attr('id');
            fec_selected += $("#date_"+id).val()+',';
        }
    });
    
    var cadena_ids = ids_selected.slice(0,-1);
    var cadena_val = val_selected.slice(0,-1);
    var cadena_fec = fec_selected.slice(0,-1);
    var cadena_obs = obs_selected.slice(0,-1);
    
    $('#ReporteC_recibos').val(cadena_ids);
    $('#ReporteC_opc_ver').val(cadena_val);
    $('#ReporteC_fec_ver').val(cadena_fec);
    $('#ReporteC_obs_ver').val(cadena_obs);
    
    if($('#ReporteC_recibos').val() == ""){
      $('#ReporteC_opc').val('');
      $('#ReporteC_opc_em_').show();
      $('#ReporteC_opc_em_').html('Debe verificar por lo menos 1 recibo.');   
    }else{
      if(v == 0){
        $('#ReporteC_opc').val('');
        $('#ReporteC_opc_em_').show();
        $('#ReporteC_opc_em_').html('Debe digitar una observación por cada recibo rechazado.'); 
      }else{
        $('#ReporteC_opc').val(1);
        $('#ReporteC_opc_em_').hide();
        $('#ReporteC_opc_em_').html('');

        //se envia el form
        var form = $("#reporte-c-form");
        form.submit();
        loadershow();
      }
    }
}


function valida_opciones_che(){
  
  $('#ReporteC_recibos').val('');
  $('#ReporteC_opc_ver').val('');
  $('#ReporteC_fec_ver').val('');
  $('#ReporteC_obs_rec').val('');
  $('#ReporteC_fec_che').val('' );
  $('#ReporteC_opc_em_').hide();
  $('#ReporteC_opc_em_').html('');

  var ids_selected = ''; 
  var fec_selected = '';    
    $('input.cheq[type=checkbox]').each(function(){
        if (this.checked) {
            ids_selected += $(this).attr('id')+',';
            id = $(this).attr('id');
            fec_selected += $("#date_che_"+id).val()+',';
        }
    });
    
    var cadena_ids = ids_selected.slice(0,-1);
    var cadena_fec = fec_selected.slice(0,-1);
    
    $('#ReporteC_recibos').val(cadena_ids);
    $('#ReporteC_fec_che').val(cadena_fec);
    
    if($('#ReporteC_recibos').val() == ""){
      $('#ReporteC_opc').val('');
      $('#ReporteC_opc_em_').show();
      $('#ReporteC_opc_em_').html('Debe seleccionar por lo menos 1 fecha de cheque.');
    }else{
      $('#ReporteC_opc').val(2);
      $('#ReporteC_opc_em_').hide();
      $('#ReporteC_opc_em_').html('');
  
      //se envia el form
      var form = $("#reporte-c-form");
      form.submit();
      loadershow();      

    }
}

function valida_opciones_obs(){
  
  $('#ReporteC_recibos').val('');
  $('#ReporteC_opc_ver').val('');
  $('#ReporteC_fec_ver').val('');
  $('#ReporteC_obs_rec').val('');
  $('#ReporteC_fec_che').val('' );
  $('#ReporteC_opc_em_').hide();
  $('#ReporteC_opc_em_').html('');

  var v = 1;

  var ids_selected = ''; 
  var obs_selected = '';    
  $('input.obs[type=checkbox]').each(function(){
      if (this.checked) {
          ids_selected += $(this).attr('id')+',';
          id = $(this).attr('id');
          obs = $("#obs_"+id).val();

          if(obs == ""){
            var obs = 0;
            obs_selected += obs+','; 
            v = 0;
          }else{
            $('#ReporteC_opc_em_').hide();
            $('#ReporteC_opc_em_').html('');
            obs_selected += obs+'||'; 
          }
      }
  });
  
  var cadena_ids = ids_selected.slice(0,-1);
  var cadena_obs = obs_selected.slice(0,-2);
  
  $('#ReporteC_recibos').val(cadena_ids);
  $('#ReporteC_obs_rec').val(cadena_obs);
  
  if($('#ReporteC_recibos').val() == ""){
    $('#ReporteC_opc').val('');
    $('#ReporteC_opc_em_').show();
    $('#ReporteC_opc_em_').html('Debe seleccionar por lo menos 1 observación.');
  }else{
    if(v == 0){
      $('#ReporteC_opc').val('');
      $('#ReporteC_opc_em_').show();
      $('#ReporteC_opc_em_').html('Debe digitar una observación por cada recibo.'); 
    }else{
      $('#ReporteC_opc').val(3);
      $('#ReporteC_opc_em_').hide();
      $('#ReporteC_opc_em_').html('');

      //se envia el form
      var form = $("#reporte-c-form");
      form.submit();
      loadershow(); 

    }
  }
}

</script>




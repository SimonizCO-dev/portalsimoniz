<h4>Aplicación de recibos</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
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

<?php echo $form->hiddenField($model,'recibos', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label>Ruta / Recibo</label>
            <?php echo $form->textField($model,'ruta', array('class' => 'form-control form-control-sm', 'maxlength' => '60', 'autocomplete' => 'off')); ?>
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

echo $form->error($model,'recibos', array('class' => 'badge badge-warning float-right mb-2'));
echo '<div id="contenido"></div>';

?>

<div class="row mb-2">
    <div class="col-sm-12">  
        <button type="button" class="btn btn-primary btn-sm" onclick="window.location.reload();" id="btn_refresh"><i class="fas fa-sync"></i> Actualizar vista</button>
        <button type="submit" class="btn btn-primary btn-sm" onclick="return valida_opciones(event);" id="btn_submit"><i class="fas fa-check"></i> Aplicar recibo(s) seleccionado(s)</button>
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

  $('#btn_submit').hide();
  cargarrecibosaplic(0); 

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

function check_uncheck_all(){

  $('input:checkbox.checks').each(function(){
    if (this.checked) {
      $(this).prop('checked', false);
    } else {
      $(this).prop('checked', true);
    }
  });
}

function buscarrecibos(){
  //se buscan los recibos por el filtro
  var filtro = $('#ReporteC_ruta').val();

  if(filtro == ''){
    cargarrecibosaplic(0);  
  }else{
    cargarrecibosaplic(filtro);
  }

}

function cargarrecibosaplic(filt){
  //funcion para cargar los recibos por verificar
  $('#contenido').html('');
  $('#btn_submit').hide();
  $('.ajax-loader').fadeIn('fast');
  var data = {filtro: filt}
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('controlrecibos/CargarRecibosAplic'); ?>",
    data: data,
    dataType: 'json',
    success: function(data){

      $('#contenido').html(data.info);
      $('.ajax-loader').fadeOut('fast');

      if(data.opc == 1){
        //hay info
        $('#btn_submit').show();   
      }   
    }
  });
}

function resetfields(){
  $('#ReporteC_ruta').val('');
  cargarrecibosaplic(0); 
}

function viewrecibo(id){
  var url = "<?php echo Yii::app()->createUrl('controlRecibos/ViewRecibo&id='); ?>";
  $('.modal-body').load(url+id,function(){
    $('#myModal').modal({show:true});
  });
}


function valida_opciones(){
  var ids_selected = '';    
    $('input:checkbox.checks').each(function(){
        if (this.checked) {
            ids_selected += $(this).val()+',';
        }
    });
    
    var cadena_ids = ids_selected.slice(0,-1);
    
    $('#ReporteC_recibos').val(cadena_ids);
  
    return true;  
}

</script>




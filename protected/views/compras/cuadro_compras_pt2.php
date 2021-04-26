<?php
/* @var $this ComprasController */
/* @var $model Compras */
?>

<h4>Cuadro de compras PT importados</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'compras-form',
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
    <div class="col-sm-8">
      <div class="form-group">
        <?php echo $form->error($model,'opcion', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'opcion'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[opcion]',
            'id'=>'Compras_opcion',
            'data'=>array(16 => 'PROVEEDOR', 1 => 'ESTADO', 2 => 'ORIGEN - ESTADO', 3 => 'ORIGEN', 4 => 'MARCA - ESTADO', 5 => 'LINEA - ESTADO', 6 => 'DESC. ORACLE - ESTADO', 7 => 'MARCA', 8 => 'LINEA', 9 => 'ORACLE', 10 => 'ORIGEN - MARCA', 11 => 'ORIGEN - LINEA', 12 => 'ORIGEN - DESC. ORACLE', 13 => 'ORIGEN - MARCA - ESTADO', 14 => 'ORIGEN - LINEA - ESTADO', 15 => 'ORIGEN - DESC. ORACLE - ESTADO', 17 => 'SIN FILTROS'),
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
    <div class="col-sm-4" id="origen" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_origen" style="display: none;"></div>
        <?php echo $form->label($model,'origen'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[origen]',
            'id'=>'Compras_origen',
            'data'=>$lista_origenes,
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
    <div class="col-sm-4" id="marca" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_marca" style="display: none;"></div>
        <?php echo $form->label($model,'marca'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[marca]',
            'id'=>'Compras_marca',
            'data'=>$lista_marcas,
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
    <div class="col-sm-4" id="linea" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_linea" style="display: none;"></div>
        <?php echo $form->label($model,'linea'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[linea]',
            'id'=>'Compras_linea',
            'data'=>$lista_lineas,
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
    <div class="col-sm-4" id="oracle" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_oracle" style="display: none;"></div>
        <?php echo $form->label($model,'des_ora'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[des_ora]',
            'id'=>'Compras_des_ora',
            'data'=>$lista_oracle,
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
    <div class="col-sm-4" id="proveedor" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_proveedor" style="display: none;"></div>
        <?php echo $form->label($model,'proveedor'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[proveedor]',
            'id'=>'Compras_proveedor',
            'data'=>$lista_pro,
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
    <div class="col-sm-4" id="estado" style="display: none;">
      <div class="form-group">
        <div class="badge badge-warning float-right" id="error_estado" style="display: none;"></div>
        <?php echo $form->label($model,'estado'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
            'name'=>'Compras[estado]',
            'id'=>'Compras_estado',
            'data'=>$lista_estados,
            'htmlOptions'=>array(
                'multiple'=>'multiple',
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
</div>
    
<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-excel"></i> Generar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#Compras_opcion").change(function() {

    var origen = $('#Compras_origen').val();
    var marca  = $('#Compras_marca').val();
    var linea  = $('#Compras_linea').val();
    var oracle = $('#Compras_des_ora').val();
    var proveedor = $('#Compras_proveedor').val();
    var estado = $('#Compras_estado').val();
      
    if($(this).val() == ""){

      $('#error_origen').hide();
      $('#error_origen').html('');
      $('#Compras_origen').val('').trigger('change');
      $('#origen').hide();

      $('#error_marca').hide();
      $('#error_marca').html('');
      $('#Compras_marca').val('').trigger('change');
      $('#marca').hide();

      $('#error_linea').hide();
      $('#error_linea').html('');
      $('#Compras_linea').val('').trigger('change');
      $('#linea').hide();

      $('#error_oracle').hide();
      $('#error_oracle').html('');
      $('#Compras_des_ora').val('').trigger('change');
      $('#oracle').hide();

      $('#error_proveedor').hide();
      $('#error_proveedor').html('');
      $('#Compras_proveedor').val('').trigger('change');
      $('#proveedor').hide();

      $('#error_estado').hide();
      $('#error_estado').html('');
      $('#Compras_estado').val('').trigger('change');
      $('#estado').hide();

    }else{
      
      if($(this).val() == 1){
        /*ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 2){
        /*ORIGEN - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 3){
        /*ORIGEN*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 4){
        /*CRI/MARCA - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').show();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 5){
        /*CRI/LINEA - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').show();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 6){
        /*CRI/ORACLE - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').show();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 7){
        /*CRI/MARCA*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').show();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 8){
        /*CRI/LINEA*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').show();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 9){
        /*CRI/ORACLE*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').show();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 10){
        /*ORIGEN - CRI/MARCA*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').show();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 11){
        /*ORIGEN - CRI/LINEA*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').show();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 12){
        /*ORIGEN - CRI/ORACLE*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').show();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 13){
        /*ORIGEN - CRI/MARCA - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').show();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 14){
        /*ORIGEN - CRI/LINEA - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').show();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 15){
        /*ORIGEN - CRI/ORACLE - ESTADO*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').show();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').show();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').show();

      }

      if($(this).val() == 16){
        /*PROVEEDOR*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').show();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

      if($(this).val() == 17){
        /*SIN FILTROS*/

        $('#error_origen').hide();
        $('#error_origen').html('');
        $('#Compras_origen').val('').trigger('change');
        $('#origen').hide();

        $('#error_marca').hide();
        $('#error_marca').html('');
        $('#Compras_marca').val('').trigger('change');
        $('#marca').hide();

        $('#error_linea').hide();
        $('#error_linea').html('');
        $('#Compras_linea').val('').trigger('change');
        $('#linea').hide();

        $('#error_oracle').hide();
        $('#error_oracle').html('');
        $('#Compras_des_ora').val('').trigger('change');
        $('#oracle').hide();

        $('#error_proveedor').hide();
        $('#error_proveedor').html('');
        $('#Compras_proveedor').val('').trigger('change');
        $('#proveedor').hide();

        $('#error_estado').hide();
        $('#error_estado').html('');
        $('#Compras_estado').val('').trigger('change');
        $('#estado').hide();

      }

    }
  });
  

  $("#valida_form").click(function() {
      var form = $("#compras-form");
      var settings = form.data('settings');
      var opcion = $('#Compras_opcion').val();
      var origen = $('#Compras_origen').val();
      var marca  = $('#Compras_marca').val();
      var linea  = $('#Compras_linea').val();
      var oracle = $('#Compras_des_ora').val();
      var proveedor = $('#Compras_proveedor').val();
      var estado = $('#Compras_estado').val();


      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              if(opcion == 1){
                /*ESTADO*/

                if(estado == ""){
                  
                  $('#error_estado').show();
                  $('#error_estado').html('Estado es requerido.');

                }else{
                  
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 2){
                /*ORIGEN - ESTADO*/

                if(origen == "" || estado == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 3){
                /*ORIGEN*/

                if(origen == ""){

                  $('#error_origen').show();
                  $('#error_origen').html('Origen es requerido.');
                                    
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 4){
                /*CRI/MARCA - ESTADO*/

                if(marca == "" || estado == ""){

                  if(marca == ""){
                    $('#error_marca').show();
                    $('#error_marca').html('Marca es requerido.');
                  }else{
                    $('#error_marca').hide();
                    $('#error_marca').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_marca').hide();
                  $('#error_marca').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 5){
                /*CRI/LINEA - ESTADO*/

                if(linea == "" || estado == ""){

                  if(linea == ""){
                    $('#error_linea').show();
                    $('#error_linea').html('línea es requerido.');
                  }else{
                    $('#error_linea').hide();
                    $('#error_linea').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_linea').hide();
                  $('#error_linea').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 6){
                /*CRI/ORACLE - ESTADO*/

                if(oracle == "" || estado == ""){

                  if(oracle == ""){
                    $('#error_oracle').show();
                    $('#error_oracle').html('Desc. oracle es requerido.');
                  }else{
                    $('#error_oracle').hide();
                    $('#error_oracle').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_oracle').hide();
                  $('#error_oracle').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 7){
                /*CRI/MARCA*/

                if(marca == ""){

                  $('#error_marca').show();
                  $('#error_marca').html('Marca es requerido.');
                                    
                }else{
                  
                  $('#error_marca').hide();
                  $('#error_marca').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 8){
                /*CRI/LINEA*/

                if(linea == ""){

                  $('#error_linea').show();
                  $('#error_linea').html('Línea es requerido.');
                                    
                }else{
                  
                  $('#error_linea').hide();
                  $('#error_linea').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 9){
                /*CRI/ORACLE*/

                if(oracle == ""){

                  $('#error_oracle').show();
                  $('#error_oracle').html('Desc. oracle es requerido.');
                                    
                }else{
                  
                  $('#error_oracle').hide();
                  $('#error_oracle').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 10){
                /*ORIGEN - CRI/MARCA*/

                if(origen == "" || marca == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(marca == ""){
                    $('#error_marca').show();
                    $('#error_marca').html('Marca es requerido.');
                  }else{
                    $('#error_marca').hide();
                    $('#error_marca').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_marca').hide();
                  $('#error_marca').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 11){
                /*ORIGEN - CRI/LINEA*/

                if(origen == "" || linea == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(linea == ""){
                    $('#error_linea').show();
                    $('#error_linea').html('Línea es requerido.');
                  }else{
                    $('#error_linea').hide();
                    $('#error_linea').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_linea').hide();
                  $('#error_linea').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 12){
                /*ORIGEN - CRI/ORACLE*/

                if(origen == "" || oracle == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(oracle == ""){
                    $('#error_oracle').show();
                    $('#error_oracle').html('Desc. oracle es requerido.');
                  }else{
                    $('#error_oracle').hide();
                    $('#error_oracle').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_oracle').hide();
                  $('#error_oracle').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 13){
                /*ORIGEN - CRI/MARCA - ESTADO*/

                if(origen == "" || marca == "" || estado == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(marca == ""){
                    $('#error_marca').show();
                    $('#error_marca').html('Marca es requerido.');
                  }else{
                    $('#error_marca').hide();
                    $('#error_marca').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_marca').hide();
                  $('#error_marca').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 14){
                /*ORIGEN - CRI/LINEA - ESTADO*/

                if(origen == "" || linea == "" || estado == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(linea == ""){
                    $('#error_linea').show();
                    $('#error_linea').html('Línea es requerido.');
                  }else{
                    $('#error_linea').hide();
                    $('#error_linea').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_linea').hide();
                  $('#error_linea').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 15){
                /*ORIGEN - CRI/ORACLE - ESTADO*/

                if(origen == "" || oracle == "" || estado == ""){

                  if(origen == ""){
                    $('#error_origen').show();
                    $('#error_origen').html('Origen es requerido.');
                  }else{
                    $('#error_origen').hide();
                    $('#error_origen').html('');
                  }

                  if(oracle == ""){
                    $('#error_oracle').show();
                    $('#error_oracle').html('Desc. oracle es requerido.');
                  }else{
                    $('#error_oracle').hide();
                    $('#error_oracle').html('');
                  }

                  if(estado == ""){
                    $('#error_estado').show();
                    $('#error_estado').html('Estado es requerido.');
                  }else{
                    $('#error_estado').hide();
                    $('#error_estado').html('');
                  }
                  
                }else{
                  
                  $('#error_origen').hide();
                  $('#error_origen').html('');
                  $('#error_oracle').hide();
                  $('#error_oracle').html('');
                  $('#error_estado').hide();
                  $('#error_estado').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 16){
                /*PROVEEDOR*/

                if(proveedor == ""){

                  $('#error_proveedor').show();
                  $('#error_proveedor').html('Proveedor es requerido.');
                                    
                }else{
                  
                  $('#error_proveedor').hide();
                  $('#error_proveedor').html('');

                  //se envia el form
                  form.submit();
                  loadershow();

                }

              }

              if(opcion == 17){
                /*SIN FILTROS*/

                //se envia el form
                form.submit();
                loadershow();

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




});

function resetfields(){
  $('#Compras_opcion').val('').trigger('change');
}

</script>

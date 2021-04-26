<?php
/* @var $this VentasController */
/* @var $model Ventas */

?>

<h4>Lista de precios</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ventas-form',
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
        <?php echo $form->error($model,'tipo', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'tipo'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Ventas[tipo]',
                'id'=>'Ventas_tipo',
                'data'=> array(1 => 'Rango marcas', 2 => 'Rango oracle'),
                'options'=>array(
                    'placeholder'=>'Seleccione...',
                    'width'=> '100%',
                    'allowClear'=>true,
                ),
            ));
        ?>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->error($model,'lista', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'lista'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Ventas[lista]',
              'id'=>'Ventas_lista',
              'data'=> $lista_pr,
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
   <div class="col-sm-6" id="marca_inicial" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'marca_inicial', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'marca_inicial'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Ventas[marca_inicial]',
              'id'=>'Ventas_marca_inicial',
              'data'=> $lista_marcas,
              'options'=>array(
                  'placeholder'=>'Seleccione...',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-6" id="marca_final" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'marca_final', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'marca_final'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Ventas[marca_final]',
              'id'=>'Ventas_marca_final',
              'data'=> $lista_marcas,
              'options'=>array(
                  'placeholder'=>'Seleccione...',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-6" id="oracle_inicial" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'des_ora_ini', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'des_ora_ini'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Ventas[des_ora_ini]',
              'id'=>'Ventas_des_ora_ini',
              'data'=> $lista_oracle,
              'options'=>array(
                  'placeholder'=>'Seleccione...',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-6" id="oracle_final" style="display: none;">
    <div class="form-group">
      <?php echo $form->error($model,'des_ora_fin', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'des_ora_fin'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'Ventas[des_ora_fin]',
              'id'=>'Ventas_des_ora_fin',
              'data'=> $lista_oracle,
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
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-file-pdf"></i> Generar</button>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 table-responsive" id="resultados">
    <!-- contenido via ajax -->
    </div>
</div>  

<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#Ventas_tipo").change(function() {

    var val = $(this).val();

    if(val == ""){

      $('#marca_inicial').hide();
      $('#marca_final').hide();
      $('#oracle_inicial').hide();
      $('#oracle_final').hide();

      $('#Ventas_marca_inicial').val('').trigger('change');
      $('#Ventas_marca_final').val('').trigger('change');
      $('#Ventas_des_ora_ini').val('').trigger('change');
      $('#Ventas_des_ora_fin').val('').trigger('change');
      $('#Ventas_marca_inicial_em_').html('');
      $('#Ventas_marca_inicial_em_').hide();  
      $('#Ventas_marca_final_em_').html('');
      $('#Ventas_marca_final_em_').hide();  
      $('#Ventas_des_ora_ini_em_').html('');
      $('#Ventas_des_ora_ini_em_').hide();  
      $('#Ventas_des_ora_fin_em_').html('');
      $('#Ventas_des_ora_fin_em_').hide();  
    }else{
        
      if(val == 1){
        //rango marcas
        $('#marca_inicial').show();
        $('#marca_final').show();
        $('#oracle_inicial').hide();
        $('#oracle_final').hide();
      }

      if(val == 2){
        //rango oracle
        $('#marca_inicial').hide();
        $('#marca_final').hide();
        $('#oracle_inicial').show();
        $('#oracle_final').show();
      }

      $('#Ventas_marca_inicial').val('').trigger('change');
      $('#Ventas_marca_final').val('').trigger('change');
      $('#Ventas_des_ora_ini').val('').trigger('change');
      $('#Ventas_des_ora_fin').val('').trigger('change');
      $('#Ventas_marca_inicial_em_').html('');
      $('#Ventas_marca_inicial_em_').hide();  
      $('#Ventas_marca_final_em_').html('');
      $('#Ventas_marca_final_em_').hide();  
      $('#Ventas_des_ora_ini_em_').html('');
      $('#Ventas_des_ora_ini_em_').hide();  
      $('#Ventas_des_ora_fin_em_').html('');
      $('#Ventas_des_ora_fin_em_').hide(); 

    }    
  });

  $("#valida_form").click(function() {

    var form = $("#ventas-form");
    
    var tipo =  $('#Ventas_tipo').val();
    var lista =  $('#Ventas_lista').val();
    var marca_inicial =  $('#Ventas_marca_inicial').val();
    var marca_final =  $('#Ventas_marca_final').val();
    var oracle_inicial =  $('#Ventas_des_ora_ini').val();
    var oracle_final =  $('#Ventas_des_ora_fin').val();

    if(tipo != ""){
      if(tipo == 1){
        if(lista != "" && marca_inicial != "" && marca_final != ""){
          //se envia el form
          form.submit();
          loadershow();
        }else{
          if(lista == ""){
            $('#Ventas_lista_em_').html('Lista es requerido.');
            $('#Ventas_lista_em_').show(); 
          }

          if(marca_inicial == ""){
            $('#Ventas_marca_inicial_em_').html('Línea inicial es requerido.');
            $('#Ventas_marca_inicial_em_').show();    
          }

          if(marca_final == ""){
            $('#Ventas_marca_final_em_').html('Línea final es requerido.');
            $('#Ventas_marca_final_em_').show();    
          }
        }
      }else{
        if(lista != "" && oracle_inicial != "" && oracle_final != ""){
          //se envia el form
          form.submit();
          loadershow();
        }else{
          if(lista == ""){
            $('#Ventas_lista_em_').html('Lista es requerido.');
            $('#Ventas_lista_em_').show(); 
          }

          if(oracle_inicial == ""){
              $('#Ventas_des_ora_ini_em_').html('Desc. oracle inicial es requerido.');
              $('#Ventas_des_ora_ini_em_').show();    
          }

          if(oracle_final == ""){
              $('#Ventas_des_ora_fin_em_').html('Desc. oracle final es requerido.');
              $('#Ventas_des_ora_fin_em_').show();    
          }
        }
      }
    }else{
      $('#Ventas_tipo_em_').html('Tipo es requerido.');
      $('#Ventas_tipo_em_').show(); 
    }
  });

});


function resetfields(){
  $('#Ventas_tipo').val('').trigger('change');
  $('#Ventas_lista').val('').trigger('change');
}

</script>

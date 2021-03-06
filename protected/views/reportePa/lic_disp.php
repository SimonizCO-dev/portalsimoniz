<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//para combos de clases de licencia
$lista_clasif_lic = CHtml::listData($clasif_lic, 'Id_Dominio', 'Dominio');

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Pa_Empresa', 'Descripcion');

?>

<h4>Licencias disponibles</h4>

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
      <?php echo $form->error($model,'empresa_compra', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'empresa_compra'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'ReportePa[empresa_compra]',
              'id'=>'ReportePa_empresa_compra',
              'data'=>$lista_empresas,
              'htmlOptions'=>array(
                'multiple'=>'multiple',
              ),
              'options'=>array(
                  'placeholder'=>'TODAS',
                  'width'=> '100%',
                  'allowClear'=>true,
              ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'clasif', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'clasif'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'ReportePa[clasif]',
              'id'=>'ReportePa_clasif',
              'data'=>$lista_clasif_lic,
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
  <div class="col-sm-4">
  	<div class="form-group">
		  <?php echo $form->error($model,'opcion_exp', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'opcion_exp'); ?><br>
		  <?php 
			    echo $form->radioButtonList($model,'opcion_exp',
		    	array('3'=>'<i class="fa fa-desktop" aria-hidden="true"></i> Pantalla','2'=>'<i class="far fa-file-excel" aria-hidden="true"></i> EXCEL'),
		    	array(
		        	'template'=>'{input}{label}',
		        	'separator'=>'',
		        	'labelOptions'=>array(
		            	'style'=> '
		                	padding-left:1%;
		                	padding-right:5%;
	            	'),
	          	)                              
	      	);
		  ?>			
  	</div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button> 
      <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fa fa-cogs"></i> Generar</button>
    </div>
</div>
   
<div class="row">
    <div class="col-lg-12 table-responsive" id="resultados" style="font-size: 10px">
    <!-- contenido via ajax -->
    </div>
</div>  

<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#valida_form").click(function() {

      var form = $("#reporte-pa-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              $("#resultados").html(''); 
              //se envia el form
              if($("input:radio:checked").val() == 3){
                reporte_pantalla();
              }else{
                form.submit();
                $(".ajax-loader").show('fast');
                setTimeout(function(){ $(".ajax-loader").hide('fast'); }, 5000); 
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

function reporte_pantalla(){

  var cad_empresas = "";

  $('#ReportePa_empresa_compra :selected').each(function(i, sel){ 
      cad_empresas += $(sel).val()+','; 
  });

  if(cad_empresas != ""){
    var empresa_compra = cad_empresas.slice(0,-1);
  }else{
    var empresa_compra = "";  
  }

  var clasif = $('#ReportePa_clasif').val();

  var data = {
    empresa_compra: empresa_compra, 
    clasif: clasif
  }
  
  $(".ajax-loader").show('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('reportepa/licdisppant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").hide('fast');
      $("#resultados").html(data); 
    }
  });

}

function resetfields(){

  $('#ReportePa_empresa_compra').val('').trigger('change');
  $('#ReportePa_clasif').val('').trigger('change');
  $("#resultados").html(''); 

}
  
</script>



<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion');

//para combos de motivos
$lista_motivos = CHtml::listData($motivos_retiro, 'Id_Dominio', 'Dominio');

?>

<h4>Reporte contratos finalizados</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'reporte-th-form',
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
        <?php echo $form->error($model,'fecha_inicial_fin', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'fecha_inicial_fin'); ?>
        <?php echo $form->textField($model,'fecha_inicial_fin', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'fecha_final_fin', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'fecha_final_fin'); ?>
        <?php echo $form->textField($model,'fecha_final_fin', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'motivo_retiro', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'motivo_retiro'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'ReporteTh[motivo_retiro]',
                'id'=>'ReporteTh_motivo_retiro',
                'data'=>$lista_motivos,
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
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'liquidado', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'liquidado'); ?>
        <?php
            $opcs = array(1 => 'SI', 2 => 'NO');
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'ReporteTh[liquidado]',
                'id'=>'ReporteTh_liquidado',
                'data'=>$opcs,
                'value' => $model->liquidado,
                'htmlOptions'=>array(),
                'options'=>array(
                    'placeholder'=>'SI / NO',
                    'width'=> '100%',
                    'allowClear'=>true,
                ),
            ));
        ?>
        </div>
    </div>           
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'empresa', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'empresa'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'ReporteTh[empresa]',
                    'id'=>'ReporteTh_empresa',
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
			<?php echo $form->error($model,'opcion_exp', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'opcion_exp'); ?><br>
			<?php 
				echo $form->radioButtonList($model,'opcion_exp',
			    	array('3'=>'<i class="fa fa-desktop" aria-hidden="true"></i> Pantalla','1'=>'<i class="far fa-file-pdf" aria-hidden="true"></i> PDF','2'=>'<i class="far fa-file-excel" aria-hidden="true"></i> EXCEL'),
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
    <div class="col-lg-12 table-responsive" id="resultados" style="font-size: 10px !important;">
    <!-- contenido via ajax -->
    </div>
</div>  

<?php $this->endWidget(); ?>

<script>

$(function() {

  $("#valida_form").click(function() {

      var form = $("#reporte-th-form");
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
                $(".ajax-loader").fadeIn('fast');
                setTimeout(function(){ $(".ajax-loader").fadeOut('fast'); }, 5000); 
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

  $("#ReporteTh_fecha_inicial_fin").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#ReporteTh_fecha_final_fin').datepicker('setStartDate', minDate);
  });

  $("#ReporteTh_fecha_final_fin").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#ReporteTh_fecha_inicial_fin').datepicker('setEndDate', maxDate);
  });

});

function reporte_pantalla(){

  var motivo_retiro = $("#ReporteTh_motivo_retiro").val();
  var liquidado = $("#ReporteTh_liquidado").val();
  var fecha_inicial_fin = $("#ReporteTh_fecha_inicial_fin").val();
  var fecha_final_fin = $("#ReporteTh_fecha_final_fin").val();
  var empresa = $("#ReporteTh_empresa").val();
  var data = {motivo_retiro: motivo_retiro, liquidado: liquidado, fecha_inicial_fin: fecha_inicial_fin, fecha_final_fin: fecha_final_fin, empresa: empresa}
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('reporteth/contratosfinalizadospant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

function resetfields(){
  $('#ReporteTh_fecha_inicial_fin').val('');
  $('#ReporteTh_fecha_final_fin').val('');
  $('#ReporteTh_motivo_retiro').val('').trigger('change');
  $('#ReporteTh_liquidado').val('').trigger('change');
  $('#ReporteTh_empresa').val('').trigger('change');
  $("#resultados").html(''); 
}

</script> 


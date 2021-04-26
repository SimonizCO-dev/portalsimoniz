<?php
/* @var $this ReporteController */
/* @var $model Reporte */

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion');

//para combos de motivos
$lista_motivos = CHtml::listData($motivos_sanciones, 'Id_Dominio', 'Dominio');

?>

<h3>Reporte sanciones de empleados</h3>

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
    <div class="col-sm-8">
      <div class="form-group">
            <?php echo $form->error($model,'id_empleado', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'id_empleado'); ?>

            <?php echo $form->textField($model,'id_empleado'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Reporte_id_empleado',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 5,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('empleado/SearchEmpleado'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Reporte_id_empleado"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-success btn-xs pull-right\" onclick=\"clear_select2_ajax(\'Reporte_id_empleado\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'fecha_inicial_reg', array('class' => 'pull-right badge bg-red')); ?>
        <?php echo $form->label($model,'fecha_inicial_reg'); ?>
        <?php echo $form->textField($model,'fecha_inicial_reg', array('class' => 'form-control', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'fecha_final_reg', array('class' => 'pull-right badge bg-red')); ?>
        <?php echo $form->label($model,'fecha_final_reg'); ?>
        <?php echo $form->textField($model,'fecha_final_reg', array('class' => 'form-control', 'readonly' => true)); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'fecha_inicial', array('class' => 'pull-right badge bg-red')); ?>
        <?php echo $form->label($model,'fecha_inicial'); ?>
        <?php echo $form->textField($model,'fecha_inicial', array('class' => 'form-control', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'fecha_final', array('class' => 'pull-right badge bg-red')); ?>
        <?php echo $form->label($model,'fecha_final'); ?>
        <?php echo $form->textField($model,'fecha_final', array('class' => 'form-control', 'readonly' => true)); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'motivo', array('class' => 'pull-right badge bg-red')); ?>
        <?php echo $form->label($model,'motivo'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'Reporte[motivo]',
                'id'=>'Reporte_motivo',
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
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'empresa', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'empresa'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Reporte[empresa]',
                    'id'=>'Reporte_empresa',
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
  </div>
  <div class="row">
    <div class="col-sm-4">
    	<div class="form-group">
			<?php echo $form->error($model,'opcion_exp', array('class' => 'pull-right badge bg-red')); ?>
          	<?php echo $form->label($model,'opcion_exp'); ?><br>
			<?php 
				echo $form->radioButtonList($model,'opcion_exp',
			    	array('3'=>'<i class="fa fa-desktop" aria-hidden="true"></i> Pantalla','1'=>'<i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF','2'=>'<i class="fa fa-file-excel-o" aria-hidden="true"></i> EXCEL'),
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

<div class="btn-group" style="padding-bottom: 2%">
    <button type="button" class="btn btn-success" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
    <button type="button" class="btn btn-success" id="valida_form"><i class="fa fa-bar-chart"></i> Generar</button>
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

  $("#Reporte_fecha_inicial_reg").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#Reporte_fecha_final_reg').datepicker('setStartDate', minDate);
  });

  $("#Reporte_fecha_final_reg").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#Reporte_fecha_inicial_reg').datepicker('setEndDate', maxDate);
  });

  $("#Reporte_fecha_inicial").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var minDate = new Date(selected.date.valueOf());
    $('#Reporte_fecha_final').datepicker('setStartDate', minDate);
  });

  $("#Reporte_fecha_final").datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  }).on('changeDate', function (selected) {
    var maxDate = new Date(selected.date.valueOf());
    $('#Reporte_fecha_inicial').datepicker('setEndDate', maxDate);
  });

});

function reporte_pantalla(){

  var motivo = $("#Reporte_motivo").val();
  var fecha_inicial = $("#Reporte_fecha_inicial").val();
  var fecha_final = $("#Reporte_fecha_final").val();
  var empresa = $("#Reporte_empresa").val();
  var fecha_inicial_reg = $("#Reporte_fecha_inicial_reg").val();
  var fecha_final_reg = $("#Reporte_fecha_final_reg").val();
  var id_empleado = $("#Reporte_id_empleado").val();
  
  var data = {motivo: motivo, fecha_inicial: fecha_inicial, fecha_final: fecha_final, empresa: empresa, fecha_inicial_reg: fecha_inicial_reg, fecha_final_reg: fecha_final_reg, id_empleado: id_empleado}
  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('reporte/sancionespant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

function resetfields(){
  $('#Reporte_id_empleado').val('').trigger('change');
  $('#s2id_Reporte_id_empleado span').html("");
  $('#Reporte_fecha_inicial_reg').val('');
  $('#Reporte_fecha_final_reg').val('');
  $('#Reporte_fecha_inicial').val('');
  $('#Reporte_fecha_final').val('');
  $('#Reporte_motivo').val('').trigger('change');
  $('#Reporte_empresa').val('').trigger('change');
}

function clear_select2_ajax(id){
    $('#'+id+'').val('').trigger('change');
    $('#s2id_'+id+' span').html("");
}

</script> 


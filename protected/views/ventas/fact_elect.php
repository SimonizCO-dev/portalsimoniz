<?php
/* @var $this InventarioController */
/* @var $model Inventario */

?>

<h4>Consulta facturas electrónicas</h4>

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
      <?php echo $form->textField($model,'tipo', array('class' => 'form-control form-control-sm', 'maxlength' => '3', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->error($model,'cons_inicial', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'cons_inicial'); ?>
        <?php echo $form->numberField($model,'cons_inicial', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
        <?php echo $form->error($model,'cons_final', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'cons_final'); ?>
        <?php echo $form->numberField($model,'cons_final', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
    </div>
  </div>
</div>

<div class="row">  
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
    <div class="col-lg-12 table-responsive" id="resultados">
    <!-- contenido via ajax -->
    </div>
</div>  


<?php $this->endWidget(); ?>

<script>

$(function() {
 // $(".ajax-loader").show();
  $("#valida_form").click(function() {

      var form = $("#ventas-form");
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
                Ventas_pantalla();
              }else{
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

function Ventas_pantalla(){

  var tipo = $("#Ventas_tipo").val();
  var cons_inicial =  $("#Ventas_cons_inicial").val();
  var cons_final =  $("#Ventas_cons_final").val();

  var data = {tipo: tipo, cons_inicial: cons_inicial, cons_final: cons_final}

  $(".ajax-loader").fadeIn('fast');
  $.ajax({ 
    type: "POST", 
    url: "<?php echo Yii::app()->createUrl('ventas/consultafactelectpant'); ?>",
    data: data,
    success: function(data){ 
      $(".ajax-loader").fadeOut('fast');
      $("#resultados").html(data); 
    }
  });

}

function resetfields(){
  $("#resultados").html(''); 
  $('#Ventas_tipo').val('');
  $('#Ventas_cons_inicial').val('');
  $('#Ventas_cons_final').val('');
}

</script>
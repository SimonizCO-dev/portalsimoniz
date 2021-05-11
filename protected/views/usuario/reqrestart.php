<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'usuario-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-login">
    <div class="card-body login-card-body">
    	<div class="login-logo js-tilt" data-tilt>
			<img src="<?php echo Yii::app()->baseUrl."/images/login-logo.png"; ?>">
		</div>
		<p class="login-box-msg">Restablecimiento de password</p>      
		<div class="form-group">
			<?php echo $form->label($model,'n_ident', array('class' => 'control-label')); ?>
			<div class="input-group mb-3">
		  		<?php echo $form->numberField($model,'n_ident', array('class' => 'form-control', 'placeholder' => '# Identificación', 'autocomplete' => 'off')); ?>
		  		<div class="input-group-append">
		    		<div class="input-group-text">
		      			<span class="fas fa-address-card"></span>
		    		</div>
		  		</div>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->error($model,'n_ident', array('class' => 'badge badge-warning')); ?>
		</div>
        <div class="row text-center mb-3">
        	<div class="col-6"> 
        		<button type="button" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/login'; ?>';" class="btn btn-block btn-login btn-sm"><i class="fas fa-reply"></i> Volver</button>
        	</div>
          	<div class="col-6">
            	<button type="button" id="valida_form" class="btn btn-block btn-login btn-sm"><i class="fas fa-paper-plane"></i> Enviar solicitud</button>
          	</div>
          	<!-- /.col -->
        </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<?php $this->endWidget(); ?>

<script>

$(function() {

	$("#valida_form").click(function() {
    	var form = $("#usuario-form");
    	var settings = form.data('settings') ;

      	settings.submitting = true ;
      	$.fn.yiiactiveform.validate(form, function(messages) {
          	if($.isEmptyObject(messages)) {
              	$.each(settings.attributes, function () {
                 	$.fn.yiiactiveform.updateInput(this,messages,form); 
               	});
                   
			form.submit();
			loadershow();

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
   	
</script>
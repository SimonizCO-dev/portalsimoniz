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

		<?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-primary alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check-circle"></i>Realizado</h5>
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    	<?php endif; ?> 
    	<?php if(Yii::app()->user->hasFlash('warning')):?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info-circle"></i>Cuidado</h5>
            <?php echo Yii::app()->user->getFlash('warning'); ?>
        </div>
    	<?php endif; ?> 

    	<?php if($opc == 1){ ?>

		<div class="form-group">
			<?php echo $form->label($model,'Usuario', array('class' => 'control-label')); ?>
			<?php echo $form->error($model,'Usuario', array('class' => 'badge badge-warning float-right')); ?>
			<div class="input-group mb-3">
		  		<?php echo $form->textField($model,'Usuario', array('class' => 'form-control', 'placeholder' => 'Usuario', 'autocomplete' => 'off', 'readonly' => true)); ?>
		  		<div class="input-group-append">
		    		<div class="input-group-text">
		      			<span class="fas fa-user"></span>
		    		</div>
		  		</div>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model,'new_password', array('class' => 'control-label')); ?>
			<div class="input-group mb-3">
		  		<?php echo $form->passwordField($model,'new_password', array('class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off')); ?>
		  		<div class="input-group-append">
		    		<div class="input-group-text">
		      			<span class="fas fa-lock"></span>
		    		</div>
		  		</div>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->error($model,'new_password', array('class' => 'badge badge-warning')); ?>
		</div>
		<div class="form-group">
			<?php echo $form->label($model,'repeat_password', array('class' => 'control-label')); ?>
			<div class="input-group mb-3">
		  		<?php echo $form->passwordField($model,'repeat_password', array('class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off')); ?>
		  		<div class="input-group-append">
		    		<div class="input-group-text">
		      			<span class="fas fa-lock"></span>
		    		</div>
		  		</div>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->error($model,'repeat_password', array('class' => 'badge badge-warning')); ?>
		</div>
        <div class="row text-center mb-3">
        	<div class="col-6"> 
        		<button type="button" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/login'; ?>';" class="btn btn-block btn-login btn-sm"><i class="fas fa-reply"></i> Volver a login</button>
        	</div>
          	<div class="col-6">
            	<button type="button" id="valida_form" class="btn btn-block btn-login btn-sm"><i class="fas fa-paper-plane"></i> Guardar cambios</button>
          	</div>
          	<!-- /.col -->
        </div>

    	<?php }else{ ?>

    	<div class="row text-center mb-3">
        	<button type="button" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/reqrestart'; ?>';" class="btn btn-block btn-login btn-sm"><i class="fas fa-reply"></i> Volver a realizar solicitud</button>
      	</div>

    	<?php } ?>

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
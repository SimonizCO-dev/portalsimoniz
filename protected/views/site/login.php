<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
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
		<div class="form-group">
			<?php echo $form->label($model,'username', array('class' => 'control-label')); ?>
			<?php echo $form->error($model,'username', array('class' => 'badge badge-warning float-right')); ?>
			<div class="input-group mb-3">
		  		<?php echo $form->textField($model,'username', array('class' => 'form-control', 'placeholder' => 'Usuario', 'autocomplete' => 'off')); ?>
		  		<div class="input-group-append">
		    		<div class="input-group-text">
		      			<span class="fas fa-user"></span>
		    		</div>
		  		</div>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model,'password', array('class' => 'control-label')); ?>
			<?php echo $form->error($model,'password', array('class' => 'badge badge-warning float-right')); ?>
			<div class="input-group mb-3">
		  		<?php echo $form->passwordField($model,'password', array('class' => 'form-control', 'placeholder' => 'Password', 'autocomplete' => 'off')); ?>
		  		<div class="input-group-append">
		    		<div class="input-group-text">
		      			<span class="fas fa-lock"></span>
		    		</div>
		  		</div>
			</div>
		</div>
        <div class="row text-center mb-3">
        	<button type="submit" class="btn btn-block btn-login btn-sm"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
      	</div>
        <p class="mb-1">
        	<a href="<?php echo Yii::app()->getBaseUrl(true)."/index.php?r=usuario/reqrestart"; ?>" class="text-warning">He olvidado mi password</a>
      	</p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<?php $this->endWidget(); ?>
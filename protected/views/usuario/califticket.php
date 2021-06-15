<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

?>

<?php 

//Vista sin login
if($vista == 0){ 

?>

<div class="login-box" style="width: 700px !important;">
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

    	<?php if($opc == 1){ ?>

			<center>
	       
	      <h4>Califica nuestro servicio</h4>

	      <p>El ticket ID <?php echo $modelticket->Id_Ticket; ?> ha sido cerrado:</p><br>

	      <strong>Descripción del caso: </strong><p><?php echo $modelticket->Solicitud; ?></p>

	      <?php if($modelticket->Notas != ""){ ?>

	      <strong>Notas: </strong><p><?php echo $modelticket->Notas; ?></p>

	    	<?php } ?>

	      <hr>

	      <p>Seleccione o pulse el emoji para calificar este servicio.</p>

	      <div class="row text-center" >
	          
	          <div class="col-sm-4">
	              <div class="form-group">
	                  <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/finticket&id='.$modelticket->Id_Ticket.'&v='.$vista.'&c=1';?>" class="calif">
	                  <h1 class="fas fa-frown text-warning" title="POR MEJORAR"></h1>
	                  <p class="text-warning">POR MEJORAR</p>
	                  </a>
	              </div>
	          </div>
	          
	          <div class="col-sm-4">
	              <div class="form-group">
	                  <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/finticket&id='.$modelticket->Id_Ticket.'&v='.$vista.'&c=2';?>" class="calif">
	                  <h1 class="fas fa-meh text-primary" title="NEUTRO"></h1>
	                  <p class="text-primary">NEUTRO</p>
	                </a>
	              </div>
	          </div>
	          
	          
	          <div class="col-sm-4">
	              <div class="form-group">
	                  <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/finticket&id='.$modelticket->Id_Ticket.'&v='.$vista.'&c=3';?>" class="calif">
	                  <h1 class="fas fa-laugh text-success" title="BUENO"></h1>
	                  <p class="text-success">BUENO</p>
	                  </a> 
	              </div>
	          </div>   
	      </div>
	                           
	    </center>


    	<?php }else{ ?>

    	<div class="row text-center mb-3">
        	<button type="button" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/login'; ?>';" class="btn btn-block btn-login btn-sm"><i class="fas fa-reply"></i> Volver</button>
      	</div>

    	<?php } ?>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<?php 

//Vista con login
}else{ 

?>
	
	<?php if($opc == 1){ ?>

	<center>
     
    <h4>Califica nuestro servicio</h4>

    <p>El ticket ID <?php echo $modelticket->Id_Ticket; ?> ha sido cerrado:</p><br>

    <strong>Descripción del caso: </strong><p><?php echo $modelticket->Solicitud; ?></p>

    <?php if($modelticket->Notas != ""){ ?>

    <strong>Notas: </strong><p><?php echo $modelticket->Notas; ?></p>

  	<?php } ?>

    <hr>

    <p>Seleccione o pulse el emoji para calificar este servicio.</p>

    <div class="row text-center">
        
        <div class="col-sm-4">
            <div class="form-group">
                <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/finticket&id='.$modelticket->Id_Ticket.'&v='.$vista.'&c=1';?>" class="calif">
                <h1 class="fas fa-frown text-warning" title="POR MEJORAR"></h1>
                <p class="text-warning">POR MEJORAR</p>
                </a>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/finticket&id='.$modelticket->Id_Ticket.'&v='.$vista.'&c=2';?>" class="calif">
                <h1 class="fas fa-meh text-primary" title="NEUTRO"></h1>
                <p class="text-primary">NEUTRO</p>
              </a>
            </div>
        </div>
        
        
        <div class="col-sm-4">
            <div class="form-group">
                <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/finticket&id='.$modelticket->Id_Ticket.'&v='.$vista.'&c=3';?>" class="calif">
                <h1 class="fas fa-laugh text-success" title="BUENO"></h1>
                <p class="text-success">BUENO</p>
                </a> 
            </div>
        </div>   
    </div>
                         
  </center>

  <?php } ?>

<?php } ?>


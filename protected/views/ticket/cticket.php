<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

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

<p>Seleccione o pulse el emoji para calificar este servicio:</p>

<div class="row text-center" >
    
    <div class="col-sm-4">
        <div class="form-group">
            <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ticket/fticket&id='.$modelticket->Id_Ticket.'&c=1';?>" class="calif">
            <h1 class="fas fa-frown text-warning" title="POR MEJORAR"></h1>
            <p class="text-warning">POR MEJORAR</p>
            </a>
        </div>
    </div>
    
    <div class="col-sm-4">
        <div class="form-group">
            <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ticket/fticket&id='.$modelticket->Id_Ticket.'&c=2';?>" class="calif">
            <h1 class="fas fa-meh text-primary" title="NEUTRO"></h1>
            <p class="text-primary">NEUTRO</p>
          </a>
        </div>
    </div>
    
    
    <div class="col-sm-4">
        <div class="form-group">
            <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ticket/fticket&id='.$modelticket->Id_Ticket.'&c=3';?>" class="calif">
            <h1 class="fas fa-laugh text-success" title="BUENO"></h1>
            <p class="text-success">BUENO</p>
            </a> 
        </div>
    </div>   
</div>
                     
</center>

<?php } ?>

<script type="text/javascript">

$(function() {

    $(".calif").click(function() {

        showloader();
           
    });

 });    

</script>


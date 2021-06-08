<?php
/* @var $this TicketController */
/* @var $model Ticket */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ticket-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Resumen de ticket ID <?php echo $model->Id_Ticket; ?></h4>
  </div>
  <div class="col-sm-6 text-right">  
  	<?php if($model->Soporte != null){ ?>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-soporte"><i class="fas fa-image"></i> Ver Soporte</button>
   	<?php } ?>
   	<?php if($model->Estado == 1){ ?>
        <button type="button" class="btn btn-primary btn-sm" id="asig_t"><i class="fas fa-ticket-alt"></i> Tomar este ticket</button>
   	<?php } ?>
  	<button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ticket/asig'; ?>';"><i class="fa fa-reply"></i> Volver</button>   
  </div>
</div>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Creacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Creacion'); ?>
            <p><?php echo UtilidadesVarias::textofecha($model->Fecha_Creacion); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Creacion', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->idusuariocre->Nombres; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
          <?php echo $form->label($model,'Id_Tipo'); ?>
          <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
          <p><?php echo $model->DescTipo($model->Id_Tipo); ?></p>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
          <?php echo $form->label($model,'Prioridad'); ?>
          <?php echo $form->error($model,'Prioridad', array('class' => 'badge badge-warning float-right')); ?>
          <p><?php echo $model->DescPrioridad($model->Prioridad); ?></p>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->idgrupo->Dominio; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->idgrupo->Dominio; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Novedad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Novedad', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->idnovedad->Novedad; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Novedad_Det', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Novedad_Det', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php if($model->Id_Novedad_Det == ""){ echo "-"; }else{ echo $model->idnovedaddet->Novedad; } ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->label($model,'Solicitud', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Solicitud', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->Solicitud; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Asig', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Asig', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php if($model->Id_Usuario_Asig == ""){ echo "-"; }else{ echo $model->idusuarioasig->Nombres; } ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Asig', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Asig', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php if($model->Fecha_Asig == ""){ echo "-"; }else{ echo UtilidadesVarias::textofecha($model->Fecha_Asig); } ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->DescEstado($model->Estado); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Actualizacion', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Actualizacion', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Cierre', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Cierre', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php if($model->Fecha_Cierre == ""){ echo "-"; }else{ echo UtilidadesVarias::textofecha($model->Fecha_Cierre); } ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Calificacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Calificacion', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php if($model->Fecha_Calificacion == ""){ echo "-"; }else{ echo UtilidadesVarias::textofecha($model->Fecha_Calificacion); } ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
        	<?php echo $form->label($model,'Calificacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Calificacion', array('class' => 'badge badge-warning float-right')); ?>
           	<?php echo $model->DescCalif($model->Calificacion); ?>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>	

<h5>Historico</h5>

<?php

/*$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'hist-ticket-grid',
    'dataProvider'=>$hist->search(),
    //'filter'=>$model,
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
    'columns'=>array(
        'Texto',
        array(
            'name' => 'Fecha_Registro',
            'value' => 'UtilidadesVarias::textofechahora($data->Fecha_Registro)',
        ),
        array(
            'name'=>'Id_Usuario_Registro',
            'value'=>'$data->idusuarioreg->Nombres',
        ),
    ),
));*/

?>

<div class="modal fade" id="modal-soporte">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
            	<?php if($model->Soporte != null){ ?>
			        <img src="<?php echo $model->Soporte; ?>" class="img-responsive center-block">
			   	<?php } ?>	
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

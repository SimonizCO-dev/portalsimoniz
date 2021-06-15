<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */

$lista_usuarios = array();
foreach ($usuarios_asig as $u) {
    $lista_usuarios[$u->Id_Usuario] = $u->idusuario->Nombres;    
}

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
    'htmlOptions' => array(
    ),
)); ?>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Creacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Creacion'); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>
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
            <?php echo $form->label($model,'Fecha_Asig', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Fecha_Asig', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php if($model->Fecha_Asig == ""){ echo "-"; }else{ echo UtilidadesVarias::textofechahora($model->Fecha_Asig); } ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Asig', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Asig', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Ticket[Id_Usuario_Asig]',
                    'id'=>'Ticket_Id_Usuario_Asig',
                    'data'=>$lista_usuarios,
                    'value' => $model->Id_Usuario_Asig,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Ticket[Estado]',
                    'id'=>'Ticket_Estado',
                    'data'=>$estados,
                    'value' => $model->Estado,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Usuario_Actualizacion', array('class' => 'badge badge-warning float-right')); ?>
            <p><?php echo $model->idusuarioact->Nombres; ?></p>
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
    <div class="col-sm-8" id="notas" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Notas', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Notas', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textArea($model,'Notas',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'maxlength'=>5000, 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>

<h5>Historico</h5>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
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
));

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

<?php $this->endWidget(); ?>	
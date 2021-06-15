<?php
/* @var $this TicketController */
/* @var $model Ticket */
/* @var $form CActiveForm */
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
        'enctype' => 'multipart/form-data'
    ),
)); ?>


<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Tipo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Tipo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Ticket[Id_Tipo]',
                    'id'=>'Ticket_Id_Tipo',
                    'data'=>array(1 => 'INCIDENCIA', 2 => 'REQUERIMIENTO'),
                    'value' => $model->Id_Tipo,
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
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Prioridad', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Prioridad', array('class' => 'badge badge-warning float-right')); ?>
            <?php $prioridades = array(1 => 'ALTA', 2 => 'MEDIA', 3 => 'BAJA'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Ticket[Prioridad]',
                    'id'=>'Ticket_Prioridad',
                    'data'=>$prioridades,
                    'value' => $model->Prioridad,
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
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Ticket[Id_Grupo]',
                    'id'=>'Ticket_Id_Grupo',
                    'data'=>$lista_grupos,
                    'value' => $model->Id_Grupo,
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
    <div class="col-sm-4" id="div_novedad" style="display: none;">
        <div class="form-group">
          <?php echo $form->label($model,'Id_Novedad', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Id_Novedad', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                'name'=>'Ticket[Id_Novedad]',
                'id'=>'Ticket_Id_Novedad',
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
    <div class="col-sm-4" id="div_novedad_det" style="display: none;">
        <div class="form-group">
          <?php echo $form->label($model,'Id_Novedad_Det', array('class' => 'control-label')); ?>
          <?php echo $form->error($model,'Id_Novedad_Det', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                'name'=>'Ticket[Id_Novedad_Det]',
                'id'=>'Ticket_Id_Novedad_Det',
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
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Solicitud', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Solicitud', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textArea($model,'Solicitud',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'maxlength'=>5000, 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-sm-8">
    <div class="form-group">
      <?php echo $form->label($model,'Soporte', array('class' => 'control-label')); ?>
      <div class="badge badge-warning float-right" id="error_sop" style="display: none;"></div><br>
      <input type="hidden" id="valid_sop" value="1">
      <?php echo $form->fileField($model, 'Soporte'); ?>
    </div>
  </div>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>	
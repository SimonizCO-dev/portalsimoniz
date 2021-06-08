<?php
/* @var $this NovedadTicketController */
/* @var $model NovedadTicket */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'novedad-ticket-form',
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
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'NovedadTicket[Id_Grupo]',
                    'id'=>'NovedadTicket_Id_Grupo',
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
    <div class="col-sm-4" id="div_padre" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Novedad_Padre', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Novedad_Padre', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'NovedadTicket[Id_Novedad_Padre]',
                    'id'=>'NovedadTicket_Id_Novedad_Padre',
                    'value' => $model->Id_Novedad_Padre,
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
          	<?php echo $form->label($model,'Novedad'); ?>
            <?php echo $form->error($model,'Novedad', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Novedad', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
      <div class="form-group">
          <?php echo $form->label($model,'Usuarios'); ?>
          <?php echo $form->error($model,'Usuarios', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'NovedadTicket[Usuarios]',
                  'id'=>'NovedadTicket_Usuarios',
                  'data'=>$lista_usuarios,
                  'htmlOptions'=>array(
                      'multiple'=>'multiple',
                  ),
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

<?php if(!$model->isNewRecord){ ?>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Estado'); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'NovedadTicket[Estado]',
                    'id'=>'NovedadTicket_Estado',
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
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>

<?php } ?>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=novedadticket/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>
<?php $this->endWidget(); ?>
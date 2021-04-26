<?php
/* @var $this FichaItemUsuarioController */
/* @var $model FichaItemUsuario */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ficha-item-usuario-form',
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
        <?php echo $form->label($model,'Proceso'); ?>
      	<?php echo $form->error($model,'Proceso', array('class' => 'badge badge-warning float-right')); ?>
      	<p><?php echo $model->Proceso; ?></p>
        
      </div>
  </div>
</div>
<div class="row">   
  <div class="col-sm-12">
    <div class="form-group">
          <?php echo $form->label($model,'Id_Users_Reg'); ?>
      <?php echo $form->error($model,'Id_Users_Reg', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'FichaItemUsuario[Id_Users_Reg]',
                  'id'=>'FichaItemUsuario_Id_Users_Reg',
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
<div class="row">   
  <div class="col-sm-12">
    <div class="form-group">
          <?php echo $form->label($model,'Id_Users_Notif'); ?>
      <?php echo $form->error($model,'Id_Users_Notif', array('class' => 'badge badge-warning float-right')); ?>
          <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'FichaItemUsuario[Id_Users_Notif]',
                  'id'=>'FichaItemUsuario_Id_Users_Notif',
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
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Hora_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Hora_Creacion); ?></p>
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
            <?php echo $form->label($model,'Fecha_Hora_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Hora_Actualizacion); ?></p>
        </div>
    </div>
</div>
<?php } ?>

<div class="row mb-4">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitemusuario/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

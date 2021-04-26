<?php
/* @var $this TipoActController */
/* @var $model TipoAct */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tipo-act-form',
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
            <?php echo $form->label($model,'Clasificacion', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Clasificacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php $clasif = array(1 => 'GENERAL', 2 => 'DISPONIBILIDAD'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'TipoAct[Clasificacion]',
                    'id'=>'TipoAct_Clasificacion',
                    'data'=>$clasif,
                    'value' => $model->Clasificacion,
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
            <?php echo $form->label($model,'Id_Grupo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Grupo', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'TipoAct[Id_Grupo]',
                    'id'=>'TipoAct_Id_Grupo',
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
            <?php echo $form->label($model,'Padre', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Padre', array('class' => 'badge badge-warning float-right')); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'TipoAct[Padre]',
                    'id'=>'TipoAct_Padre',
                    'value' => $model->Padre,
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
          	<?php echo $form->label($model,'Tipo'); ?>
            <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
		    <?php echo $form->textField($model,'Tipo', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
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
                  'name'=>'TipoAct[Usuarios]',
                  'id'=>'TipoAct_Usuarios',
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
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Cantidad'); ?>
            <?php echo $form->error($model,'Cantidad', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->numberField($model,'Cantidad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Inicio', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Fecha_Inicio'); ?>
            <?php echo $form->textField($model,'Fecha_Inicio', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Fin', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Fecha_Fin'); ?>
            <?php echo $form->textField($model,'Fecha_Fin', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Ind_Alto'); ?>
            <?php echo $form->error($model,'Ind_Alto', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->numberField($model,'Ind_Alto', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'max' => '100', 'step' => '0.01')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Ind_Medio'); ?>
            <?php echo $form->error($model,'Ind_Medio', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->numberField($model,'Ind_Medio', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'max' => '100', 'step' => '0.01')); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Ind_Bajo'); ?>
            <?php echo $form->error($model,'Ind_Bajo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->numberField($model,'Ind_Bajo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'min' => '0', 'max' => '100', 'step' => '0.01')); ?>
        </div>
    </div>
</div>


<?php if(!$model->isNewRecord){ ?>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Estado'); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados = array(0 => "INACTIVO", 1 => "EN CURSO", 2 => "FINALIZADO", 3 => "POSPUESTO"); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'TipoAct[Estado]',
                    'id'=>'TipoAct_Estado',
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
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=tipoact/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>
<?php $this->endWidget(); ?>
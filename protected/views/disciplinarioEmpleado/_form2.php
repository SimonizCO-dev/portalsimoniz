<?php
/* @var $this DisciplinarioEmpleadoController */
/* @var $model DisciplinarioEmpleado */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'disciplinario-empleado-form',
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
  <div class="col-sm-6">
      <div class="form-group">
        <input type="hidden" id="fecha_min" value="<?php echo $fecha_min; ?>">   
        <?php echo $form->label($model,'Id_Empleado'); ?>
        <?php echo '<p>'.UtilidadesEmpleado::nombreempleado($e).'</p>'; ?> 
      </div>
  </div>
  <div class="col-sm-3">
    <div class="form-group">
      <?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Fecha'); ?>
      <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-3">
    <div class="form-group">
      <?php echo $form->error($model,'Id_M_Disciplinario', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Id_M_Disciplinario'); ?>
      <?php
          $this->widget('ext.select2.ESelect2',array(
              'name'=>'DisciplinarioEmpleado[Id_M_Disciplinario]',
              'id'=>'DisciplinarioEmpleado_Id_M_Disciplinario',
              'data'=>$lista_motivos,
              'value'=>$model->Id_M_Disciplinario,
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
  <div class="col-sm-6">
    <div class="form-group">
      <?php echo $form->error($model,'Id_Empleado_Imp', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Id_Empleado_Imp'); ?>
      <?php echo $form->textField($model,'Id_Empleado_Imp'); ?>
      <?php
        $this->widget('ext.select2.ESelect2', array(
            'selector' => '#DisciplinarioEmpleado_Id_Empleado_Imp',
            'options'  => array(
              'allowClear' => true,
              'minimumInputLength' => 5,
                  'width' => '100%',
                  'language' => 'es',
                  'ajax' => array(
                        'url' => Yii::app()->createUrl('empleado/SearchEmpleado'),
                    'dataType'=>'json',
                      'data'=>'js:function(term){return{q: term};}',
                      'results'=>'js:function(data){ return {results:data};}'                   
                ),
                'formatNoMatches'=> 'js:function(){ clear_select2_ajax("DisciplinarioEmpleado_Id_Empleado_Imp"); return "No se encontraron resultados"; }',
                'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'DisciplinarioEmpleado_Id_Empleado_Imp\')\">Limpiar campo</button>"; }',
                'initSelection'=>'js:function(element,callback) {
                      var id=$(element).val(); // read #selector value
                      if ( id !== "" ) {
                        $.ajax("'.Yii::app()->createUrl('empleado/SearchEmpleadoById').'", {
                            data: { id: id },
                            dataType: "json"
                        }).done(function(data,textStatus, jqXHR) { callback(data[0]); });
                     }
                  }',
            ),
          ));
      ?>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="form-group">
      <?php echo $form->error($model,'Observacion', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Observacion'); ?>
      <?php echo $form->textArea($model,'Observacion',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Orden_No', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Orden_No'); ?>
      <?php echo $form->textField($model,'Orden_No', array('class' => 'form-control form-control-sm', 'maxlength' => '50', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
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
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/view&id='.$e; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>
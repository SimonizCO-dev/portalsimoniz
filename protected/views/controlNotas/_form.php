<?php
/* @var $this ControlNotasController */
/* @var $model ControlNotas */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'control-notas-form',
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
      <?php echo $form->error($model,'Id_Cliente', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Id_Cliente'); ?>
      <?php echo $form->textField($model,'Id_Cliente'); ?>
      <?php
      $this->widget('ext.select2.ESelect2', array(
          'selector' => '#ControlNotas_Id_Cliente',

          'options'  => array(
            'allowClear' => true,
            'minimumInputLength' => 5,
                'width' => '100%',
                'language' => 'es',
                'ajax' => array(
                      'url' => Yii::app()->createUrl('controlNotas/SearchCliente'),
                  'dataType'=>'json',
                    'data'=>'js:function(term){return{q: term};}',
                    'results'=>'js:function(data){ return {results:data};}'
                             
              ),
              'formatNoMatches'=> 'js:function(){ clear_select2_ajax("ControlNotas_Id_Cliente"); return "No se encontraron resultados"; }',
              'formatInputTooShort' =>  'js:function(){ return "Digite más de 5 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'ControlNotas_Id_Cliente\')\">Limpiar campo</button>"; }',
              'initSelection'=>'js:function(element,callback) {
                 	var id=$(element).val(); // read #selector value
                 	if ( id !== "" ) {
                   	$.ajax("'.Yii::app()->createUrl('controlNotas/SearchClienteById').'", {
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
  <div class="col-sm-3">
  	<div class="form-group">
			<?php echo $form->error($model,'Nota', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Nota'); ?>
	    <?php echo $form->textField($model,'Nota', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
  	</div>
  </div>
  <div class="col-sm-3">
  	<div class="form-group">
			<?php echo $form->error($model,'Factura', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Factura'); ?>
	    <?php echo $form->textField($model,'Factura', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
  	</div>
  </div>
</div>

<div class="row">
	<div class="col-sm-4">
  	<div class="form-group">
			<?php echo $form->error($model,'Valor_Factura', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Valor_Factura'); ?>
	    <?php echo $form->numberField($model,'Valor_Factura', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number')); ?>
  	</div>
  </div>	
  <div class="col-sm-4">
  	<div class="form-group">
			<?php echo $form->error($model,'Porc_Desc', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Porc_Desc'); ?>
	    <?php echo $form->numberField($model,'Porc_Desc', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'step' => '0.01' )); ?>
  	</div>
  </div>
  <div class="col-sm-4">
  	<div class="form-group">
			<?php echo $form->error($model,'Valor_Descuento', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Valor_Descuento'); ?>
	    <?php echo $form->numberField($model,'Valor_Descuento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'readonly' => true)); ?>
  	</div>
  </div>
</div>


<div class="row">
	<div class="col-sm-4">
  	<div class="form-group">
  		<?php echo $form->error($model,'Fecha_Factura', array('class' => 'badge badge-warning float-right')); ?>
    	<?php echo $form->label($model,'Fecha_Factura'); ?>
	    <?php echo $form->textField($model,'Fecha_Factura', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
  	<div class="form-group">
			<?php echo $form->error($model,'Fecha_Pago', array('class' => 'badge badge-warning float-right')); ?>
	  	<?php echo $form->label($model,'Fecha_Pago'); ?>
	    <?php echo $form->textField($model,'Fecha_Pago', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
    </div>
  </div>
  <div class="col-sm-4">
  	<div class="form-group">
			<?php echo $form->error($model,'Dias_Pago', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Dias_Pago'); ?>
	    <?php echo $form->numberField($model,'Dias_Pago', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'readonly' => true)); ?>
  	</div>
  </div>
</div>
<div class="row">
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo $form->error($model,'Recibo', array('class' => 'badge badge-warning float-right')); ?>
      <?php echo $form->label($model,'Recibo'); ?>
      <?php echo $form->textField($model,'Recibo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
    </div>
  </div>
  <div class="col-sm-8">
		<div class="form-group">
			<?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
			<?php echo $form->label($model,'Observaciones'); ?>
			<?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'onkeyup' => 'convert_may(this)')); ?>
		</div>
	</div>
</div>
<div class="row">
  <?php if($model->isNewRecord){ ?>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->hiddenField($model,'Respuesta', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'type' => 'number', 'value' => 0)); ?>
      </div>
    </div>
  <?php }else{ ?>
    <div class="col-sm-4">
      <div class="form-group">
        <?php echo $form->error($model,'Respuesta', array('class' => 'badge badge-warning float-right')); ?>
        <?php echo $form->label($model,'Respuesta'); ?>
        <?php
            $this->widget('ext.select2.ESelect2',array(
                'name'=>'ControlNotas[Respuesta]',
                'id'=>'ControlNotas_Respuesta',
                'data'=>array(0 => "EN ELAB.", 1 => "APROBADO", 2 => "NO APROBADO"),
                'value' => $model->Respuesta,
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
  <?php } ?>
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=controlNotas/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<?php $this->endWidget(); ?>

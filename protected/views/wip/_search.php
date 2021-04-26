<?php
/* @var $this WipController */
/* @var $model Wip */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<div class="row">    
	<div class="col-sm-4">
    	<div class="form-group">
          	<?php echo $form->label($model,'WIP'); ?>
		    <?php echo $form->textField($model,'WIP', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'ID_ITEM', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'ID_ITEM'); ?>
            <?php echo $form->textField($model,'ID_ITEM'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Wip_ID_ITEM',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('reporte/SearchItem'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Wip_ID_ITEM"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Wip_ID_ITEM\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'CADENA', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'CADENA'); ?>
            <?php
              $this->widget('ext.select2.ESelect2',array(
                  'name'=>'Wip[CADENA]',
                  'id'=>'Wip_CADENA',
                  'data'=> $lista_cadenas,
                  'htmlOptions'=>array(
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
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'RESPONSABLE', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'RESPONSABLE'); ?>
            <?php echo $form->textField($model,'RESPONSABLE', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div>
    <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'CANT_OC_AL_DIA', array('class' => 'pull-right badge bg-red')); ?>
          <?php echo $form->label($model,'CANT_OC_AL_DIA'); ?>
          <?php echo $form->numberField($model,'CANT_OC_AL_DIA', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
      </div>
    </div>    
</div>
<div class="row">
  <div class="col-sm-4">
      <div class="form-group">
          <?php echo $form->error($model,'CANT_PENDIENTE', array('class' => 'pull-right badge bg-red')); ?>
          <?php echo $form->label($model,'CANT_PENDIENTE'); ?>
          <?php echo $form->numberField($model,'CANT_PENDIENTE', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
      </div>
  </div>
  <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'INVENTARIO_TOTAL', array('class' => 'pull-right badge bg-red')); ?>
            <?php echo $form->label($model,'INVENTARIO_TOTAL'); ?>
            <?php echo $form->numberField($model,'INVENTARIO_TOTAL', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div>
    </div>
</div> 
<div class="row mb-2">
    <div class="col-sm-6">  
      <button type="button" class="btn btn-primary btn-sm" onclick="resetfields();"><i class="fa fa-eraser"></i> Limpiar filtros</button>
      <button type="submit" class="btn btn-primary btn-sm" id="yt0"><i class="fa fa-search"></i> Buscar</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">

function resetfields(){	
	$('#Wip_WIP').val('');
	$('#Wip_ID_ITEM').val('').trigger('change');
	$('#s2id_Wip_ID_ITEM span').html("");
  $('#Wip_CADENA').val('').trigger('change');
  $('#Wip_RESPONSABLE').val('');
  $('#Wip_CANT_OC_AL_DIA').val('');
  $('#Wip_CANT_PENDIENTE').val('');
  $('#Wip_INVENTARIO_TOTAL').val('');
	$('#wip-grid').yiiGridView('update', {
      data: $('.search-form form').serialize()
    });
    return false;
}

</script>
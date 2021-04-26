<?php
/* @var $this IDoctoController */
/* @var $model IDocto */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'idocto-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<div id="div_mensaje" style="display: none;"></div>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Tipo_Docto', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Tipo_Docto'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'IDocto[Id_Tipo_Docto]',
                    'id'=>'IDocto_Id_Tipo_Docto',
                    'data'=>$lista_tipos,
                    'value' => $model->Id_Tipo_Docto,
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
            <?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha'); ?>
            <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm datepicker', 'readonly' => true)); ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Referencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Referencia'); ?>
            <?php echo $form->textField($model,'Referencia', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)')); ?>
        </div>
    </div> 
</div>
<div class="row">
	<div class="col-sm-8">
        <div class="form-group">
        	<?php echo $form->hiddenField($model,'cad_item'); ?>
            <?php echo $form->hiddenField($model,'cad_bodega_origen'); ?>
            <?php echo $form->hiddenField($model,'cad_bodega_destino'); ?>
            <?php echo $form->hiddenField($model,'cad_cant'); ?>
            <?php echo $form->hiddenField($model,'cad_vlr'); ?>

            <?php echo $form->error($model,'Id_Tercero', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Tercero'); ?>
            <?php echo $form->textField($model,'Id_Tercero'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#IDocto_Id_Tercero',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('iTercero/SearchTercero'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDocto_Id_Tercero"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDocto_Id_Tercero\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>	
</div>
<div class="row" id="empleado" style="display: none;">
	<div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Emp', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Emp'); ?>
            <?php echo $form->textField($model,'Id_Emp'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#IDocto_Id_Emp',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('iDocto/SearchEmpleado'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDocto_Id_Emp"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDocto_Id_Emp\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>	
</div>
<div class="row" id="notas" style="display: none;">
	<div class="col-sm-8">
	    <div class="form-group">
	        <?php echo $form->error($model,'Notas', array('class' => 'badge badge-warning float-right')); ?>
	        <?php echo $form->label($model,'Notas'); ?>
	        <?php echo $form->textArea($model,'Notas',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)', 'maxlength' => '200')); ?>
	    </div>
    </div>	
</div>

<div class="row mb-2" id="btn_volver">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=iDocto/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
    </div>
</div>

<div id="det_add" style="display: none;">
	<h5>Detalle</h5>
	<div class="row">
	    <div class="col-sm-8">
	        <div class="form-group">
	            <?php echo $form->error($model,'det_item', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_item'); ?>
	            <?php echo $form->textField($model,'det_item'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2', array(
	                    'selector' => '#IDocto_det_item',
	                    'options'  => array(
	                        'minimumInputLength' => 3,
	                        'width' => '100%',
	                        'language' => 'es',
	                        'ajax' => array(
	                            'url' => Yii::app()->createUrl('iItem/SearchItem'),
	                            'dataType'=>'json',
	                            'data'=>'js:function(term){return{q: term};}',
	                            'results'=>'js:function(data){ return {results:data};}'                
	                        ),
	                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDocto_det_item"); return "No se encontraron resultados"; }',
	                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDocto_det_item\')\">Limpiar campo</button>"; }',
	                    ),
	                ));
	                ?>
	        </div>
	    </div>
	    <div class="col-sm-4" id="bodega_origen">
	        <div class="form-group">
	            <?php echo $form->error($model,'det_bodega_origen', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_bodega_origen'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'IDocto[det_bodega_origen]',
	                    'id'=>'IDocto_det_bodega_origen',
	                    'data'=>$lista_bodegas,
	                    'value' => $model->det_bodega_origen,
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
	    <div class="col-sm-4" id="bodega_destino">
	        <div class="form-group">
	            <?php echo $form->error($model,'det_bodega_destino', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_bodega_destino'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'IDocto[det_bodega_destino]',
	                    'id'=>'IDocto_det_bodega_destino',
	                    'data'=>$lista_bodegas,
	                    'value' => $model->det_bodega_destino,
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
	    <div class="col-sm-4" id="bodega_origen_tr">
	        <div class="form-group">
	            <?php echo $form->error($model,'det_bodega_tr_origen', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_bodega_tr_origen'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'IDocto[det_bodega_tr_origen]',
	                    'id'=>'IDocto_det_bodega_tr_origen',
	                    'data'=>$lista_bodegas,
	                    'value' => $model->det_bodega_tr_origen,
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
	    <div class="col-sm-4" id="bodega_destino_tr">
	        <div class="form-group">
	            <?php echo $form->error($model,'det_bodega_tr_destino', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_bodega_tr_destino'); ?>
	            <?php
	                $this->widget('ext.select2.ESelect2',array(
	                    'name'=>'IDocto[det_bodega_tr_destino]',
	                    'id'=>'IDocto_det_bodega_tr_destino',
	                    'value' => $model->det_bodega_tr_destino,
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
	            <?php echo $form->error($model,'det_cant', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_cant'); ?>
	            <?php echo $form->numberField($model,'det_cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off','maxlength' => '250', 'min' => 1)); ?>
	        </div>
	    </div>
	    <div class="col-sm-4" id="valor">
	        <div class="form-group">
	            <?php echo $form->error($model,'det_vlr', array('class' => 'badge badge-warning float-right')); ?>
	            <?php echo $form->label($model,'det_vlr'); ?>
	            <?php echo $form->numberField($model,'det_vlr', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off','maxlength' => '250', 'min' => 1)); ?>
	        </div>
	    </div> 
	</div>
	
	<div class="row mb-4">
	    <div class="col-sm-6">  
	        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=idocto/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
	        <button type="button" class="btn btn-primary btn-sm" onclick="add_item();"><i class="fa fa-plus" ></i> Agregar registro</button>
	    </div>
	</div>

</div>

<div id="contenido"></div>

<div class="row mb-2" id="btn_save" style="display: none;">
    <div class="col-sm-6">  
        <button type="submit" class="btn btn-primary btn-sm" onclick="return valida_opciones(event);"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>

<?php $this->endWidget(); ?>
<?php
/* @var $this IDoctoMovtoController */
/* @var $model IDoctoMovto */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'idocto-movto-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); 

?>

<div class="row">
	<div class="col-sm-4">
	    <div class="form-group">
	    	<?php echo $form->hiddenField($model,'Id_Docto', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off','value' => $id)); ?>
	        <?php echo $form->label($model,'tipo_docto'); ?>
	        <?php echo '<p>'.$modelo_docto->idtipodocto->Descripcion.'</p>'; ?>
	    </div>
	</div>
	<div class="col-sm-4">
	    <div class="form-group">
	        <?php echo $form->label($model,'consecutivo_docto'); ?>
	        <?php echo '<p>'.$modelo_docto->Consecutivo.'</p>'; ?>
	    </div>
	</div>
</div>
<div class="row">
	<div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Item', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Item'); ?>
            <?php echo $form->textField($model,'Id_Item'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#IDoctoMovto_Id_Item',
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
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("IDoctoMovto_Id_Item"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'IDoctoMovto_Id_Item\')\">Limpiar campo</button>"; }',
                    ),
                ));
                ?>
        </div>
    </div>

    <?php if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->sal || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->ajs || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->sad) { ?>

	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Bodega_Org', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Bodega_Org'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'IDoctoMovto[Id_Bodega_Org]',
                    'id'=>'IDoctoMovto_Id_Bodega_Org',
                    'data'=>$lista_bodegas,
                    'value' => $model->Id_Bodega_Org,
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

    <?php if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->ent || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->aje || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev) { ?>

	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Bodega_Dst', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Bodega_Dst'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'IDoctoMovto[Id_Bodega_Dst]',
                    'id'=>'IDoctoMovto_Id_Bodega_Dst',
                    'data'=>$lista_bodegas,
                    'value' => $model->Id_Bodega_Dst,
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

<div class="row">

	<?php if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->trb) { ?>

    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Bodega_Org', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Bodega_Org'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'IDoctoMovto[Id_Bodega_Org]',
                    'id'=>'IDoctoMovto_Id_Bodega_Org',
                    'data'=>$lista_bodegas,
                    'value' => $model->Id_Bodega_Org,
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
            <?php echo $form->error($model,'Id_Bodega_Dst', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Bodega_Dst'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'IDoctoMovto[Id_Bodega_Dst]',
                    'id'=>'IDoctoMovto_Id_Bodega_Dst',
                    'value' => $model->Id_Bodega_Dst,
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

	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Cantidad', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Cantidad'); ?>
            <?php echo $form->numberField($model,'Cantidad', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off','maxlength' => '250', 'min' => 1)); ?>
        </div>
    </div>

<?php if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->ent) { ?>

	<div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Vlr_Unit_Item', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Vlr_Unit_Item'); ?>
            <?php echo $form->numberField($model,'Vlr_Unit_Item', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off','maxlength' => '250', 'min' => 1)); ?>
        </div>
    </div>

<?php
 } ?>

</div>

<div class="row mb-4">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=idocto/update&id='.$id; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="add_item();"><i class="fas fa-save" ></i> Agregar</button>
    </div>
</div>

<?php $this->endWidget(); ?>
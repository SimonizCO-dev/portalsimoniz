<?php
/* @var $this SugeridoController */
/* @var $model Sugerido */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sugerido-form',
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
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Cargo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Cargo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$model->idcargo->Cargo.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Subarea', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Subarea', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$model->idsubarea->Subarea.'</p>' ?>
        </div>
    </div>
     <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Area', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Area', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo '<p>'.$model->idarea->Area.'</p>' ?>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Sugerido[Estado]',
                    'id'=>'Sugerido_Estado',
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

<?php $this->endWidget(); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'elemento-sugerido-grid',
    'dataProvider'=>$elem_asoc->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
    'columns'=>array(
        'Cantidad',
        array(
            'name'=>'Id_A_Elemento',
            'value'=>'UtilidadesSugerido::areasubareaelemento($data->Id_A_Elemento)',
        ),
        array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{act}{inact}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Actualizar'),
                    'url'=>'Yii::app()->createUrl("elementosugerido/update", array("id"=>$data->Id_E_Sugerido))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1 && $data->idsugerido->Estado == 1)',
                ),
                'act'=>array(
                    'label'=>'<i class="fas fa-toggle-on text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("elementosugerido/act", array("id"=>$data->Id_E_Sugerido, "opc"=>2))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 0 && $data->idsugerido->Estado == 1)',
                    'options'=>array('title'=>' Activar', 'confirm'=>'Esta seguro de activar el elemento ?'),
                ),
                'inact'=>array(
                    'label'=>'<i class="fas fa-toggle-off actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("elementosugerido/inact", array("id"=>$data->Id_E_Sugerido, "opc"=>2))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1 && $data->idsugerido->Estado == 1)',
                    'options'=>array('title'=>' Inactivar', 'confirm'=>'Esta seguro de inactivar el elemento ?'),
                ),
            )
        ),
    ),
)); ?>
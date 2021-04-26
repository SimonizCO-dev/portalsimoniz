<?php
/* @var $this IExistenciaController */
/* @var $model IExistencia */

?>

<h4>Visualizando existencia de item</h4>

<div class="table-responsive">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
            'name' => 'Id_Item',
            'type' => 'raw',
            'value' => $model->DescItem($model->Id_Item),
        ),
		array(
            'name'=>'Id_Bodega',
            'value' => ($model->Id_Bodega == "") ? "N/A" : $model->idbodega->Descripcion,
        ),
		'Cantidad',
		array(
            'name'=>'Fecha_Ult_Ent',
            'value' => ($model->Fecha_Ult_Ent == "") ? "N/A" : UtilidadesVarias::textofecha($model->Fecha_Ult_Ent),
        ),
        array(
            'name'=>'Fecha_Ult_Sal',
            'value' => ($model->Fecha_Ult_Sal == "") ? "N/A" : UtilidadesVarias::textofecha($model->Fecha_Ult_Sal),
        ),
        array(
            'name'=>'Id_Usuario_Actualizacion',
            'value'=>$model->idusuarioact->Usuario,
        ),
        array(
            'name'=>'Fecha_Actualizacion',
            'value'=>UtilidadesVarias::textofechahora($model->Fecha_Actualizacion),
        ),
	),
)); ?>

</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-success btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=iExistencia/admin'; ?>';"><i class="fa fa-reply"></i> Volver </button>
    </div>
</div>
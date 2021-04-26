<?php
/* @var $this NegContController */
/* @var $model NegCont */

?>

<h3>Visualizando negociación de contrato</h3>

<div class="table-responsive">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id_Neg',
		array(
            'name'=>'Id_Contrato',
            'value'=>$model->DescContrato($model->Id_Contrato),
        ),
		'Item',
		array(
            'name'=>'Costo',
            'value'=>number_format($model->Costo, 0),
        ),
		array(
		    'name' => 'Moneda',
		    'value' => $model->moneda->Dominio,
		),
		array(
            'name'=>'Porc_Desc',
            'value'=>number_format($model->Porc_Desc, 2),
        ),
		array(
		    'name'=>'costo_final',
		    'value' => $model->CostoFinal($model->Id_Neg),
		),
		array(
            'name' => 'Estado',
            'value' => UtilidadesVarias::textoestado1($model->Estado),
        ),
		array(
            'name'=>'Id_Usuario_Creacion',
            'value'=>$model->idusuariocre->Usuario,
        ),
        array(
            'name'=>'Fecha_Creacion',
            'value'=>UtilidadesVarias::textofechahora($model->Fecha_Creacion),
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

<div class="btn-group" style="padding-bottom: 2%">
    <button type="button" class="btn btn-success" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/view&id='.$model->Id_Contrato; ?>';"><i class="fa fa-reply"></i> Volver </button>
</div>

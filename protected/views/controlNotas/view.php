<?php
/* @var $this ControlNotasController */
/* @var $model ControlNotas */
?>

<h4>Visualizando nota</h4>

<div class="table">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id_Control',
		array(
            'name'=>'Id_Cliente',
            'value'=>$model->Desc_Cliente($model->Id_Cliente),
        ),
		'Nota',
		'Factura',
		array(
            'name'=>'Valor_Factura',
            'value'=>number_format($model->Valor_Factura, 0),
        ),
        array(
            'name'=>'Porc_Desc',
            'value'=>number_format($model->Porc_Desc, 2),
        ),
        array(
            'name'=>'Valor_Descuento',
            'value'=>number_format($model->Valor_Descuento, 0),
        ),
		array(
            'name'=>'Fecha_Factura',
            'value'=>UtilidadesVarias::textofecha($model->Fecha_Factura),
        ),
        array(
            'name'=>'Fecha_Pago',
            'value'=>UtilidadesVarias::textofecha($model->Fecha_Pago),
        ),
		'Dias_Pago',
        'Recibo',
		'Observaciones',
        array(
            'name'=>'Respuesta',
            'value'=>$model->Desc_Respuesta($model->Respuesta),
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
    <?php if($opc == 1 ){ ?>

    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=controlNotas/admin'; ?>';"><i class="fa fa-reply"></i> Volver </button>

    <?php }else{ ?>

    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=controlNotas/consulta'; ?>';"><i class="fa fa-reply"></i> Volver </button>

    <?php } ?>
</div>

<?php
/* @var $this CControlCmsController */
/* @var $model CControlCms */

?>

<h3>Resumen de liquidación</h3>

<div class="table-responsive">



<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ROWID',
		'ID_BASE',
        array(
            'name' => 'MES',
            'value' => $model->Desc_Mes($model->MES),
        ),
        array(
            'name' => 'ANIO',
            'value' => $model->ANIO,
        ),
		array(
            'name'=>'TIPO',
            'value'=>$model->tipo->Dominio,
        ),
        array(
            'name'=>'LIQUIDACION',
            'value'=>$model->Desc_Liq($model->LIQUIDACION),
        ),
        array(
            'name' => 'VENDEDOR',
            'value' => ($model->VENDEDOR == "") ? "N/A" : $model->Desc_Vend($model->VENDEDOR),
        ),
		'OBSERVACION',
		array(
            'name'=>'ID_USUARIO_CREACION',
            'value'=>$model->idusuariocre->Usuario,
        ),
        array(
            'name'=>'FECHA_CREACION',
            'value'=>UtilidadesVarias::textofechahora($model->FECHA_CREACION),
        ),
        array(
            'name'=>'ID_USUARIO_ACTUALIZACION',
            'value'=>$model->idusuarioact->Usuario,
        ),
        array(
            'name'=>'FECHA_ACTUALIZACION',
            'value'=>UtilidadesVarias::textofechahora($model->FECHA_ACTUALIZACION),
        ),
        array(
            'name' => 'ESTADO',
            'value' => UtilidadesVarias::textoestado1($model->ESTADO),
        ),
	),
)); ?>

</div>

<div class="btn-group" style="padding-bottom: 2%">
   <button type="button" class="btn btn-success" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cControlCms/admin'; ?>';"><i class="fa fa-reply"></i> Volver </button>
</div>


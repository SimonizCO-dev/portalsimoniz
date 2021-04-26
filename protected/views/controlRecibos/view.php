<?php
/* @var $this ControlRecibosController */
/* @var $model ControlRecibos */

?>

<h4>Visualizando resumen de recibo</h4>

<div class="table">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Id_Control',
		'Recibo',
		array(
            'name'=>'Opc',
            'value'=>$model->Desc_Opc($model->Opc),
        ),
		array(
            'name'=>'Verificacion',
            'value' => ($model->Verificacion == "") ? "-" : $model->Desc_Verif($model->Verificacion),
        ),
        array(
            'name'=>'Fecha_Banco',
            'value'=>($model->Fecha_Banco == "") ? "N/A" : UtilidadesVarias::textofecha($model->Fecha_Banco),
        ),
        array(
            'name'=>'Banco_Correcto',
            'value'=>($model->Banco_Correcto == "") ? "-" : $model->Desc_Banco($model->Banco_Correcto),
        ),
        array(
            'name'=>'Motivo_Rechazo',
            'value'=>($model->Motivo_Rechazo == "") ? "-" : $model->Desc_Motivo_Rechazo($model->Motivo_Rechazo),
        ),
		array(
            'name'=>'Id_Usuario_Carga',
            'value'=>$model->idusuariocarga->Usuario,
        ),
        array(
            'name'=>'Fecha_Hora_Carga',
            'value'=>UtilidadesVarias::textofechahora($model->Fecha_Hora_Carga),
        ),
        array(
            'name'=>'Id_Usuario_Verif',
            'value'=>($model->Id_Usuario_Verif == "") ? "-" : $model->idusuarioverif->Usuario,
        ),
        array(
            'name'=>'Fecha_Hora_Verif',
            'value'=>($model->Fecha_Hora_Verif == "") ? "-" : UtilidadesVarias::textofechahora($model->Fecha_Hora_Verif),
        ),
        array(
            'name'=>'Observaciones',
            'value'=>($model->Observaciones == "") ? "-" : $model->Observaciones,
        ),
        array(
            'name'=>'Fecha_Hora_Obs',
            'value'=>($model->Fecha_Hora_Obs == "") ? "-" : UtilidadesVarias::textofechahora($model->Fecha_Hora_Obs),
        ),
        array(
            'name'=>'Id_Usuario_Aplic',
            'value'=>($model->Id_Usuario_Aplic == "") ? "-" : $model->idusuarioaplic->Usuario,
        ),
        array(
            'name'=>'Fecha_Hora_Aplic',
            'value'=>($model->Fecha_Hora_Aplic == "") ? "-" : UtilidadesVarias::textofechahora($model->Fecha_Hora_Aplic),
        ),
        array(
            'name'=>'Id_Usuario_Rec_Fis',
            'value'=>($model->Id_Usuario_Rec_Fis == "") ? "-" : $model->idusuariorecfis->Usuario,
        ),
        array(
            'name'=>'Fecha_Hora_Rec_Fis',
            'value'=>($model->Fecha_Hora_Rec_Fis == "") ? "-" : UtilidadesVarias::textofechahora($model->Fecha_Hora_Rec_Fis),
        ),
	),
)); ?>

</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=controlRecibos/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
    </div>
</div>
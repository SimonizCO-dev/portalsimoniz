<?php
/* @var $this ControlNotasController */
/* @var $model ControlNotas */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#control-notas-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

?>

<div class="row mb-2">
    <div class="col-sm-3">
        <h4>Consulta de notas</h4>
    </div>
    <div class="col-sm-9 text-right">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'control-notas-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Control',
		array(
            'name'=>'Id_Cliente',
            'value'=>'$data->Desc_Cliente($data->Id_Cliente)',
        ),
		array(
            'name' => 'Nota',
            'type' => 'raw',
            'value' => '($data->Nota == "") ? "SIN ASIGNAR" : $data->Nota',
        ),
		'Factura',
		//'Valor_Factura',
		//'Porc_Desc',
		//'Valor_Descuento',
		array(
            'name'=>'Fecha_Factura',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Factura)',
        ),
        array(
            'name'=>'Fecha_Pago',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Pago)',
        ),
        'Recibo',
		//'Dias_Pago',
		//'Observaciones',
        array(
            'name'=>'Respuesta',
            'value'=>'$data->Desc_Respuesta($data->Respuesta)',
        ),
		/*'Id_Usuario_Creacion',
		'Id_Usuario_Actualizacion',
		'Fecha_Creacion',
		'Fecha_Actualizacion',*/
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
                    'url'=>'Yii::app()->createUrl("controlNotas/view", array("id"=>$data->Id_Control, "opc"=>2))',
                ),
            )
		),
	),
)); ?>

<div class="modal fade" id="modal-search">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Busqueda avanzada</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_usuarios' => $lista_usuarios,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<?php
/* @var $this RevRecibosController */
/* @var $model RevRecibos */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#rev-recibos-grid').yiiGridView('update', {
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
    <div class="col-sm-6">
        <h4>Log de reversiones</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rev-recibos-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		array(
            'name'=>'recibo',
            'value'=>'$data->idcontrol->Recibo',
        ),
		array(
            'name'=>'Id_Usuario_Rev',
            'value'=>'$data->idusuariorev->Usuario',
        ),
        array(
            'name'=>'Fecha_Hora_Rev',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Hora_Rev)',
        ),
        array(
            'name' => 'Opc',
            'type' => 'raw',
            'value' => '($data->Opc == "2") ? "VERIFICADO" : "APLICADO"',
        ),
		/*
		'Id_Usuario_Verif',
		'Fecha_Hora_Verif',
		'Id_Usuario_Aplic',
		'Fecha_Hora_Aplic',
		*/
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
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

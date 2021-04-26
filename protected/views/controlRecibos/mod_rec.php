<?php
/* @var $this ControlRecibosController */
/* @var $model ControlRecibos */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#control-recibos-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");
?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Modificación de recibos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'control-recibos-grid',
	'dataProvider'=>$model->search_mod_rec(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Recibo',
        array(
            'name'=>'Id_Usuario_Carga',
            'value'=>'$data->idusuariocarga->Usuario',
        ),
        array(
            'name'=>'Fecha_Hora_Carga',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Hora_Carga)',
        ),
		array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'<i class="fas fa-sync actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Cambiar imagen de recibo'),
                    'url'=>'Yii::app()->createUrl("controlRecibos/update", array("id"=>$data->Id_Control))',
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
                <?php $this->renderPartial('_search_mod_rec',array(
                    'model'=>$model,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

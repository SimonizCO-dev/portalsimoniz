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
        <h4>Reversión estado de recibos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'control-recibos-grid',
	'dataProvider'=>$model->search_estados(),
	//'filter'=>$model,
    'enableSorting' => false,
	'columns'=>array(
		'Recibo',
		array(
            'name'=>'Opc',
            'value'=>'$data->Desc_Opc($data->Opc)',
        ),
        array(
            'name'=>'Verificacion',
            'value' => '($data->Verificacion == "") ? "NO ASIGNADO" : $data->Desc_Verif($data->Verificacion)',
        ), 
		array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'<i class="fa fa-arrow-circle-left actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Revertir estado de recibo'),
                    'url'=>'Yii::app()->createUrl("controlRecibos/revrec", array("id"=>$data->Id_Control))',
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
                <?php $this->renderPartial('_search_estados',array(
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
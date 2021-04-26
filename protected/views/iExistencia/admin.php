<?php
/* @var $this IExistenciaController */
/* @var $model IExistencia */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('iexistencia-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
    $('#iexistencia-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de bodegas
$lista_bodegas = CHtml::listData($bodegas, 'Id', 'Descripcion'); 

//para combos de lineas
$lista_lineas = CHtml::listData($lineas, 'Id', 'Descripcion');  

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Consulta de existencias x bodega</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'iexistencia-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		array(
            'header' => 'Línea',
            'type' => 'raw',
            'value' => '$data->iditem->idlinea->Descripcion',
        ),
        array(
            'name' => 'Id_Item',
            'type' => 'raw',
            'value' => '$data->DescItem($data->Id_Item)',
        ),
		array(
            'name'=>'Id_Bodega',
            'value' => '($data->Id_Bodega == "") ? "N/A" : $data->idbodega->Descripcion',
        ),
        array(
            'name'=>'Cantidad',
            'value'=>'$data->Cantidad',
            'cssClassExpression' => 'UtilidadesVarias::estadoexiststock($data->Id_Item, $data->Cantidad)',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
		array(
            'name'=>'Fecha_Ult_Ent',
            'value' => '($data->Fecha_Ult_Ent == "") ? "N/A" : UtilidadesVarias::textofecha($data->Fecha_Ult_Ent)',
        ),
        array(
            'name'=>'Fecha_Ult_Sal',
            'value' => '($data->Fecha_Ult_Sal == "") ? "N/A" : UtilidadesVarias::textofecha($data->Fecha_Ult_Sal)',
        ),
		/*
		'Id_Usuario_Actualizacion',
		'Fecha_Actualizacion',
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
                    'lista_bodegas'=>$lista_bodegas,
                    'lista_lineas'=>$lista_lineas,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

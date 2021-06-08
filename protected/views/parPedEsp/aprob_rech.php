<?php
/* @var $this ParPedEspController */
/* @var $model ParPedEsp */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#par-ped-esp-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
  $('#modal-search').modal('hide');
    return false;
});
");
?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Revisión doctos de param. pedidos especiales</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'par-ped-esp-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Consecutivo',
		array(
            'name'=>'Fecha',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
        ),
		array(
            'name' => 'Nit',
            'value' => '$data->DescCliente($data->Id_Par_Ped_Esp)',
        ),
		array(
            'name' => 'Estado',
            'value' => '$data->DescEstado($data->Estado)',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{apro}{rech}',
            'buttons'=>array(
                'apro'=>array(
                    'label'=>'<i class="fas fa-check-circle actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("parPedEsp/aprodoc", array("id"=>$data->Id_Par_Ped_Esp))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                    'options'=>array('title'=>'Aprobar'),
                    'click'=>"
                    function() {
                        if(!confirm('Esta seguro de aprobar este documento ?')) {
                            return false;    
                        }
                    }",

                ),
                'rech'=>array(
                    'label'=>'<i class="fas fa-times-circle actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("parPedEsp/rechdoc", array("id"=>$data->Id_Par_Ped_Esp))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                    'options'=>array('title'=>'Rechazar'),
                    'click'=>"
                    function() {
                        if(!confirm('Esta seguro de rechazar este documento ? ?')) {
                            return false;    
                        }
                    }",

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
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php
/* @var $this NetworkController */
/* @var $model Network */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('network-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
	$('#network-grid').yiiGridView('update', {
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
        <h4>Administración de redes</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=network/create'; ?>';"><i class="fa fa-plus"></i> Nuevo segmento</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=network/asigipdhcp'; ?>';"><i class="fa fa-plus"></i> Asociar IP(s) a DHCP</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'network-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		array(
            'name' => 'ip',
            'value' => '$data->Ip($data->Id)',
        ),
        array(
            'name' => 'Estado',
            'value' => '$data->DescEstado($data->Id)',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{asig}{lib}',
            'buttons'=>array(
                'asig'=>array(
                    'label'=>'<i class="fa fa-sitemap actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("network/asig", array("id"=>$data->Id))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                    'options'=>array('title'=>'Asignar IP'),
                ),
                'lib'=>array(
                    'label'=>'<i class="fas fa-unlink actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("network/lib", array("id"=>$data->Id, "opc"=>1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 2 || $data->Estado == 3)',
                    'click'=>'function(){if (window.confirm("Esta seguro de liberar esta IP ?")) { return true; }else{ return false;}}',
                    'options'=>array('title'=>'Liberar IP'),
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
                    'lista_usuarios'=>$lista_usuarios,
                    'lista_net'=>$lista_net,
                    'lista_seg'=>$lista_seg,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


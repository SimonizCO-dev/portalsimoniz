<?php
/* @var $this EquipoController */
/* @var $model Equipo */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('equipo-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
	$('#equipo-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de tipos de equipo
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Pa_Empresa', 'Descripcion'); 

//para combos de proveedores
$lista_proveedores = CHtml::listData($proveedores, 'Id_Proveedor', 'Proveedor'); 

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración de equipos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=equipo/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'equipo-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Equipo',
		array(
            'name'=>'Tipo_Equipo',
            'value'=>'$data->tipoequipo->Dominio',
        ),
        'Serial',
        array(
            'name'=>'Empresa_Compra',
            'value'=>'$data->empresacompra->Descripcion',
        ),
        'Numero_Factura',
        array(
            'name'=>'Fecha_Compra',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Compra)',
        ),
        array(
            'name'=>'Proveedor',
            'value'=>'$data->proveedor->Proveedor',
        ),
        array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}{update}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
                ),
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Actualizar'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
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
                    'lista_tipos'=>$lista_tipos,
                    'lista_empresas'=>$lista_empresas,
                    'lista_proveedores'=>$lista_proveedores,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

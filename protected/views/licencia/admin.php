<?php
/* @var $this LicenciaController */
/* @var $model Licencia */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('licencia-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
	$('#licencia-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de clases
$lista_clases = CHtml::listData($clases, 'Id_Dominio', 'Dominio');

//para combos de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combos de versiones
$lista_versiones = CHtml::listData($versiones, 'Id_Dominio', 'Dominio');

//para combos de productos
$lista_productos = CHtml::listData($productos, 'Id_Dominio', 'Dominio');

//para combos de ubicaciones
$lista_ubicaciones = CHtml::listData($ubicaciones, 'Id_Dominio', 'Dominio');

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Pa_Empresa', 'Descripcion');

//para combos de estados
$lista_estados = CHtml::listData($estados, 'Id_Dominio', 'Dominio');

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración de licencias</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=licencia/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'licencia-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Lic',
		array(
            'name'=>'Clasificacion',
            'value'=>'$data->clasificacion->Dominio',
        ),
        array(
            'name'=>'Tipo',
            'value'=>'$data->tipo->Dominio',
        ),

        array(
            'name'=>'Version',
            'value'=>'$data->version->Dominio',
        ),
        array(
            'name' => 'Producto',
            'value' => '($data->Producto == "") ? "-" : $data->producto->Dominio',
        ),
        array(
            'name' => 'Num_Licencia',
            'value' => '($data->Num_Licencia == "") ? "-" : $data->Num_Licencia',
        ),
        'Cant_Usuarios',
        array(
            'name' => 'cant_usuarios_disp',
            'value' => '$data->CantUsuariosRest($data->Id_Lic)',
        ),
        array(
            'name' => 'Empresa_Compra',
            'value' => '($data->Empresa_Compra == "") ? "-" : $data->empresacompra->Descripcion',
        ),
        array(
            'name' => 'Ubicacion',
            'value' => '($data->Ubicacion == "") ? "-" : $data->ubicacion->Dominio',
        ),
        'Numero_Factura',
        /*
        array(
            'name'=>'Empresa_Compra',
            'value'=>'$data->empresacompra->Descripcion',
        ),
        array(
            'name'=>'Proveedor',
            'value'=>'$data->proveedor->Proveedor',
        ),
		'Password',
		'Token',
		'Notas',
		'Numero_Inventario',
		'Numero_Factura',
		'Fecha_Factura',
		'Valor_Comercial',
		'Fecha_Inicio',
		'Fecha_Final',
		'Fecha_Inicio_Sop',
		'Fecha_Final_Sop',
		'Doc_Soporte',
		'Doc_Soporte2',
		'Id_Usuario_Creacion',
		'Fecha_Creacion',
		'Id_Usuario_Actualizacion',
		'Fecha_Actualizacion',
		*/
		array(
            'name' => 'Estado',
            'value' => '($data->Estado == "") ? "-" : $data->estado->Dominio',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}{update}{ret}',
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
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado != Yii::app()->params->estado_lic_ret)',
                ),
                'ret'=>array(
                    'label'=>'<i class="fa fa-toggle-off actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("licencia/ret", array("id"=>$data->Id_Lic))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->CantUsuariosRest($data->Id_Lic) == $data->Cant_Usuarios && $data->Estado != Yii::app()->params->estado_lic_ret)',
                    'click'=>'function(){if (window.confirm("Esta seguro de retirar esta licencia ?")) { return true; }else{ return false;}}',
                    'options'=>array('title'=>'Retirar licencia'),
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
                    'lista_clases'=>$lista_clases,
                    'lista_tipos'=>$lista_tipos,
                    'lista_versiones'=>$lista_versiones,
                    'lista_productos'=>$lista_productos,
                    'lista_ubicaciones'=>$lista_ubicaciones,
                    'lista_empresas'=>$lista_empresas,
                    'lista_estados'=>$lista_estados,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


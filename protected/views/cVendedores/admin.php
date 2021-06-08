<?php
/* @var $this CVendedoresController */
/* @var $model CVendedores */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('cvendedores-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
	$('#cvendedores-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combo de estados de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

//para combo de estados de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración de vendedores</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cvendedores-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'ID',
		'ROWID',
		'NIT_VENDEDOR',
		'NOMBRE_VENDEDOR',
		array(
            'name'=>'EMAIL',
            'value' => '($data->EMAIL == "") ? "NO ASIGNADO" : $data->EMAIL',
        ),
        array(
            'name'=>'EMAIL_PERSONAL',
            'value' => '($data->EMAIL_PERSONAL == "") ? "NO ASIGNADO" : $data->EMAIL_PERSONAL',
        ),
        array(
            'name'=>'TELEFONO',
            'value' => '($data->TELEFONO == "") ? "NO ASIGNADO" : $data->TELEFONO',
        ),
        array(
            'name'=>'CIUDAD',
            'value' => '($data->CIUDAD == "") ? "NO ASIGNADO" : $data->CIUDAD',
        ),
		'ID_VENDEDOR',
		'RUTA',
		'NOMBRE_RUTA',
		'ESTADO',
		array(
            'name'=>'TIPO',
            'value' => '($data->TIPO == "") ? "NO ASIGNADO" : $data->tipo->Dominio',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}',
            'buttons'=>array(
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
                    'lista_usuarios' => $lista_usuarios,
                    'lista_tipos' => $lista_tipos,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
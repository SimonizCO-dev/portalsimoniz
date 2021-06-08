<?php
/* @var $this GdAudDocumentoController */
/* @var $model GdAudDocumento */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('gd-aud-documento-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
	$('#gd-aud-documento-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

//para combos de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Tipo', 'Descripcion'); 

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Historial de consultas / descargas de documentos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a excel</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gd-aud-documento-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
        'Id_Documento',
        array(
            'name' => 'clasif_doc',
            'value' => 'UtilidadesVarias::descclasif($data->iddocumento->Clasificacion)',
        ),
        array(
            'name'=>'tipo_doc',
            'value'=>'$data->iddocumento->tipo->Descripcion',
        ),
        array(
            'name' => 'num_doc',
            'value'=>'$data->iddocumento->Num_Documento',
        ),
        array(
            'name' => 'tit_doc',
            'value' => '$data->iddocumento->Titulo',
        ),
        array(
            'name' => 'n_v_doc',
            'value' => '$data->iddocumento->Nivel_Revision',
        ),
		array(
            'name' => 'Accion',
            'value' => 'UtilidadesVarias::textoaccion($data->Accion)',
        ),
		array(
            'name'=>'Id_Usuario',
            'value'=>'$data->idusuario->Usuario',
        ),
        array(
            'name'=>'Fecha_Hora',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Hora)',
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

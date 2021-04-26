<?php
/* @var $this AreaDocumentoController */
/* @var $model AreaDocumento */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#gd-area-documento-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Usuario', 'Usuario'); 

//para combos de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Tipo', 'Descripcion');

//para combos de areas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area');

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Consulta de áreas por documento</h4>
  </div>
  <div class="col-sm-6 text-right">  
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gd-area-documento-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_A_Documento',
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
            'name' => 'Id_Area',
            'value' => '$data->idarea->Area',
        ),
		array(
            'name'=>'Id_Usuario_Creacion',
            'value'=>'$data->idusuariocre->Usuario',
        ),
        array(
            'name'=>'Fecha_Creacion',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
        ),
        array(
            'name'=>'Id_Usuario_Actualizacion',
            'value'=>'$data->idusuarioact->Usuario',
        ),
        array(
            'name'=>'Fecha_Actualizacion',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
        ),
		array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
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
                    'lista_tipos' => $lista_tipos,
                    'lista_areas' => $lista_areas,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



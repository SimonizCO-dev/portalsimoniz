<?php
/* @var $this SugeridoController */
/* @var $model Sugerido */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#sugerido-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");
//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

//para combo de areas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area');

//para combo de subareas
$lista_subareas = CHtml::listData($subareas, 'Id_Subarea', 'Subarea');

//para combo de cargos
$lista_cargos = CHtml::listData($cargos, 'Id_Cargo', 'Cargo');  

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración de sugeridos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=sugerido/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sugerido-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Sugerido',
        array(
            'name'=>'Id_Cargo',
            'value'=>'$data->idcargo->Cargo',
        ),
        array(
            'name'=>'Id_Subarea',
            'value'=>'$data->idsubarea->Subarea',
        ),
        array(
            'name'=>'Id_Area',
            'value'=>'$data->idarea->Area',
        ),
        array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
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
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_usuarios' => $lista_usuarios,
                    'lista_areas' => $lista_areas,
                    'lista_subareas' => $lista_subareas,
                    'lista_cargos' => $lista_cargos,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

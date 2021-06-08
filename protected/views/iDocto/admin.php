<?php
/* @var $this IDoctoController */
/* @var $model IDocto */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#idocto-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de tipos
$lista_tipos = CHtml::listData($tipos, 'Id', 'Descripcion'); 

//para combos de bodegas
$lista_bodegas = CHtml::listData($bodegas, 'Id', 'Descripcion'); 

//para combos de estados
$lista_estados = CHtml::listData($estados, 'Id', 'Descripcion'); 

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

?>

<?php if($v == 1) { ?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Administración de documentos</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=idocto/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'idocto-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		//'Id',
		array(
            'name' => 'Id_Tipo_Docto',
            'type' => 'raw',
            'value' => '$data->idtipodocto->Descripcion',
        ),
		'Consecutivo',
		array(
            'name'=>'Fecha',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha)',
        ),
		'Referencia',
		array(
            'name' => 'Id_Tercero',
            'type' => 'raw',
            'value' => '$data->DescTercero($data->Id_Tercero)',
        ),
        array(
            'name' => 'Id_Estado',
            'type' => 'raw',
            'value' => '$data->idestado->Descripcion',
        ),
        array(
			'class'=>'CButtonColumn',
            'template'=>'{view}{update}{reppdf}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
                ),
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Modificar'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Id_Estado == Yii::app()->params->elab)',
                ),
                'reppdf'=>array(
                    'label'=>'<i class="fas fa-file-pdf actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Generar reporte en PDF'),
                    'url'=>'Yii::app()->createUrl("iDocto/genrepdoc", array("id"=>$data->Id))',
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
                    'lista_bodegas'=>$lista_bodegas,
                    'lista_estados'=>$lista_estados,
                    'lista_usuarios' => $lista_usuarios,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php } else { ?>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene bodegas / tipos de docto asociados.
</div>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Administración de documentos</h4>
  </div>
  <div class="col-sm-6 text-right">  
  </div>
</div>

<?php } ?>

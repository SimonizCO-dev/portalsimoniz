<?php
/* @var $this DocumentoController */
/* @var $model Documento */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#gd-documento-grid').yiiGridView('update', {
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

if($error == 0){

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Lista de documentos externos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gd-documento-grid',
	'dataProvider'=>$model->searchdocse(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
  'enableSorting' => false,
	'columns'=>array(
    array(
        'name'=>'Tipo',
        'value'=>'$data->tipo->Descripcion',
    ),
    'Num_Documento',
    'Titulo',
    'Descripcion', 
		array(
      'class'=>'CButtonColumn',
      'template'=>'{view_download}',
      'buttons'=>array(
        'view_download'=>array(
          'label'=>'<i class="fas fa-file-alt actions text-dark"></i>',
          'imageUrl'=>false,
          'options'=>array('title'=>'Visualizar / Descargar'),
          'url'=>'Yii::app()->createUrl("gddocumento/viewe", array("id"=>$data->Id_Documento))',
        ),
      )
    ),
	),
)); 

?>

<div class="modal fade" id="modal-search">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Busqueda avanzada</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search_docs',array(
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

<?php }else{ ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Lista de documentos externos</h4>
    </div>
</div>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene permisos para visualizar la lista.
</div> 

<?php } ?>
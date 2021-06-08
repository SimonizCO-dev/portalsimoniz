<?php
/* @var $this FichaItemController */
/* @var $model FichaItem */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#ficha-item-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

$usuarios_desarrollo = UtilidadesVarias::usuariosfichaitem(1);
$usuarios_comercial = UtilidadesVarias::usuariosfichaitem(3);

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres');

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Solicitudes creación / actualización de ítems</h4>
  </div>
  <div class="col-sm-6 text-right"> 
    <?php if(in_array(Yii::app()->user->getState('id_user'), $usuarios_desarrollo)){ ?>
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/create&s=1'; ?>';"><i class="fa fa-plus"></i> Solicitud de creación</button>
    <?php } ?>
    <?php if(in_array(Yii::app()->user->getState('id_user'), $usuarios_comercial)){ ?>
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=fichaitem/create2&s=1'; ?>';"><i class="fa fa-plus"></i> Solicitud de actualización</button> 
    <?php } ?>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ficha-item-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id',
        array(
            'name' => 'Pais',
            'value' => '$data->DescPais($data->Pais)',
        ),
		array(
            'name' => 'Tipo',
            'value' => '$data->DescTipo($data->Tipo)',
        ),
		array(
            'name' => 'Tipo_Producto',
            'value' => '$data->DescTipoProducto($data->Tipo_Producto)',
        ),
        array(
		    'name'=>'Codigo_Item',
		    'type'=>'raw',
		    'value'=>'($data->Codigo_Item) == "" ? "-" : $data->Codigo_Item	',

		),
		array(
		    'name'=>'Descripcion_Corta',
		    'type'=>'raw',
		    'value'=>'($data->Descripcion_Corta) == "" ? "-" : $data->Descripcion_Corta	',

		),
        array(
            'name'=>'Id_Usuario_Solicitud',
            'value'=>'$data->idusuariosol->Nombres',
        ),
        array(
            'name'=>'Fecha_Hora_Solicitud',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Hora_Solicitud)',
        ),
        array(
            'name'=>'Id_Usuario_Actualizacion',
            'value'=>'$data->idusuarioact->Nombres',
        ),
        array(
            'name'=>'Fecha_Hora_Actualizacion',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Hora_Actualizacion)',
        ),
		array(
            'name'=>'Step',
            'value'=>'$data->DescStep($data->Step)',
        ),
        array(
            'name'=>'Estado_Solicitud',
            'value'=>'$data->DescEstado($data->Estado_Solicitud)',
        ),

        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{update2}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Revisar'),
                    'url'=>'Yii::app()->createUrl("fichaitem/update", array("id"=>$data->Id, "s"=>$data->Step))',
                    'visible'=> '($data->Tipo == 1)',
                ),
                'update2'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Revisar'),
                    'url'=>'Yii::app()->createUrl("fichaitem/update2", array("id"=>$data->Id, "s"=>$data->Step))',
                    'visible'=> '($data->Tipo == 2)',
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
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


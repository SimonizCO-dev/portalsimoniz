<?php
/* @var $this PedComEnvioController */
/* @var $model PedComEnvio */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#ped-com-envio-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres'); 

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Administración envío de pedidos</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=pedcomenvio/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ped-com-envio-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id',
		array(
	      'name' => 'Id_Usuario',
	      'value' => '$data->idusuario->Nombres',
	  	),
		'Emails',
		array(
	      'name' => 'Id_Usuario_Creacion',
	      'value' => '$data->idusuariocre->Usuario',
	  	),
		array(
	      'name'=>'Fecha_Creacion',
	      'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
	  	),
	  	array(
	      'name' => 'Id_Usuario_Actualizacion',
	      'value' => '$data->idusuariocre->Usuario',
	  	),
	  	array(
	      'name'=>'Fecha_Actualizacion',
	      'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
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
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
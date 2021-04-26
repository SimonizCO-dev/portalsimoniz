<?php
/* @var $this PedComController */
/* @var $model PedCom */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#ped-com-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Pedidos comerciales</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=pedCom/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ped-com-grid',
	'dataProvider'=>$model->search(),
  'pager'=>array(
      'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
  ),
  'enableSorting' => false,
	'columns'=>array(
		'Id_Ped_Com',
		/*array(
      'name' => 'Id_Usuario',
      'value' => '$data->idusuario->Nombres',
  	),*/
  	array(
        'name' => 'Fecha',
        'value' => '($data->Fecha == "") ? "-" : UtilidadesVarias::textofecha($data->Fecha)',
    ),
    array(
      'name' => 'Cliente',
      'value' => '$data->DescCliente($data->Cliente)',
  	),
  	/*array(
      'name'=>'Fecha_Creacion',
      'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
  	),
  	array(
      'name'=>'Fecha_Actualizacion',
      'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
  	),*/
  	array(
      'name' => 'Estado',
      'value' => '$data->DescEstado($data->Estado)',
  	),
    array(
			'class'=>'CButtonColumn',
      'template'=>'{update}',
      'buttons'=>array(
        'update'=>array(
            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
            'imageUrl'=>false,
            'options'=>array('title'=>'Modificar'),
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
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php
/* @var $this SolPromController */
/* @var $model SolProm */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle('fast');
	return false;
});
$('.search-form form').submit(function(){
    $('#sol-prom-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

$usuarios_registro = UtilidadesVarias::usuariossolprom(1);

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Nombres'); 

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Solicitudes de promociones</h4>
  </div>
  <div class="col-sm-6 text-right">
  		<?php if(in_array(Yii::app()->user->getState('id_user'), $usuarios_registro)){ ?>
      	<button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=solprom/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
      	<?php } ?>
    	<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sol-prom-grid',
	'dataProvider'=>$model->search(),
  'pager'=>array(
      'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
  ),
  'enableSorting' => false,
	'columns'=>array(
		'Num_Sol',
		'Responsable',
		array(
          'name' => 'Tipo',
          'value' => '$data->DescTipo($data->Tipo)',
      	),
		array(
          'name' => 'Cliente',
          'value' => '$data->DescCliente($data->Cliente)',
      	),
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
                    'options'=>array('title'=>'Revisar'),
                    'url'=>'Yii::app()->createUrl("solprom/update", array("id"=>$data->Id_Sol_Prom, "s"=>$data->Estado))',
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

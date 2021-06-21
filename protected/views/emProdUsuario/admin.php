<?php
/* @var $this EmProdUsuarioController */
/* @var $model EmProdUsuario */
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#em-prod-usuario-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row mb-2">
  <div class="col-sm-9">
    <h4>Administracíón de usuarios para notificaciones emisiones de producto</h4>
  </div>
  <div class="col-sm-3 text-right"> 
  
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'em-prod-usuario-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
        array(
            'name' => 'Id_Users_Notif',
            'value' => '$data->DescUsers($data->Id_Users_Notif)',
        ),
		array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'buttons'=>array(
                
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Actualizar'),
                ),
            )
        ),
	),
)); ?>

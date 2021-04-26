<?php
/* @var $this FichaItemUsuarioController */
/* @var $model FichaItemUsuario */
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ficha-item-usuario-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row mb-2">
  <div class="col-sm-9">
    <h4>Administracíón de usuarios y notificaciones por proceso</h4>
  </div>
  <div class="col-sm-3 text-right"> 
  
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ficha-item-usuario-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Proceso',
        array(
            'name' => 'Id_Users_Reg',
            'value' => '$data->DescUsers($data->Id_Users_Reg)',
        ),
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

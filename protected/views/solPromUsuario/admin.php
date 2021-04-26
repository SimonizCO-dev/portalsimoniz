<?php
/* @var $this SolPromUsuarioController */
/* @var $model SolPromUsuario */

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Administracíón de usuarios y notificaciones por proceso</h4>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sol-prom-usuario-grid',
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
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                ),
            )
		),
	),
)); ?>

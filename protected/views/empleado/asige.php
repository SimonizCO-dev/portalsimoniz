<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle('fast');
	return false;
});
$('.search-form form').submit(function(){
	$('#empleado-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Usuario', 'Usuario');

//para combos de tipos de identificación
$lista_tipos_ident = CHtml::listData($tipos_ident, 'Id_Dominio', 'Dominio'); 

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion'); 

?>

<h3>Lista de colaboradores sin evaluador</h3>

<div class="btn-group" style="padding-bottom: 2%">
    <button type="button" class="btn btn-success search-button"><i class="fa fa-filter"></i> Busqueda avanzada</button>
</div>

<div class="search-form" style="display:none;">
<?php $this->renderPartial('_searchev',array(
	'model'=>$model,
	'lista_usuarios' => $lista_usuarios,
	'lista_tipos_ident' => $lista_tipos_ident,
	'lista_empresas' => $lista_empresas,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'empleado-grid',
	'dataProvider'=>$model->searcheval(),
	//'filter'=>$model,
    'enableSorting' => false,
	'columns'=>array(
		'Id_Empleado',
		array(
            'name'=>'Id_Tipo_Ident',
            'value'=>'$data->idtipoident->Dominio',
        ),
        'Identificacion',
		'Apellido',
        'Nombre',
		array(
            'name'=>'Id_Empresa',
            'value'=>'$data->idempresa->Descripcion',
        ),	
		array(
			'class'=>'CButtonColumn',
            'template'=>'{asigeval}',
            'buttons'=>array(
                'asigeval'=>array(
                    'label'=>'<i class="fa fa-users actions text-black"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Asignar evaluador'),
                    'url'=>'Yii::app()->createUrl("empleado/asigeval", array("id"=>$data->Id_Empleado, "opc"=>2))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                ),
            )
		),
	),
)); ?>

<?php
/* @var $this EmpleadoController */
/* @var $model Empleado */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#empleado-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');

//para combos de tipos de identificación
$lista_tipos_ident = CHtml::listData($tipos_ident, 'Id_Dominio', 'Dominio'); 

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion'); 

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Lista de colaboradores sin evaluador</h4>
    </div>
    <div class="col-sm-6 text-right">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'empleado-grid',
	'dataProvider'=>$model->searcheval(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
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
                    'label'=>'<i class="fa fa-users actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Asignar evaluador'),
                    'url'=>'Yii::app()->createUrl("empleado/asigeval", array("id"=>$data->Id_Empleado, "opc"=>2))',
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
                <?php $this->renderPartial('_searchev',array(
                    'model'=>$model,
                    'lista_usuarios' => $lista_usuarios,
					'lista_tipos_ident' => $lista_tipos_ident,
					'lista_empresas' => $lista_empresas,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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

$array_empresas = Yii::app()->user->getState('array_empresas');


if(empty($array_empresas)) {

?>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene empresas asociadas.
</div> 

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Administración de empleados</h4>
  </div>
  <div class="col-sm-6 text-right">  
  </div>
</div>

<?php

}else{

$cadena_empresas = implode(",",$array_empresas);

$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN (0,'.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');

//para combos de tipos de identificación
$lista_tipos_ident = CHtml::listData($tipos_ident, 'Id_Dominio', 'Dominio'); 

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Empresa', 'Descripcion'); 

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración de empleados</h4>
    </div>
    <div class="col-sm-6 text-right">
        <?php if(Yii::app()->user->getState('niv_det_vis_emp') == Yii::app()->params->niv_det_vis_emp_nomina){ ?>
            <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <?php } ?>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'empleado-grid',
	'dataProvider'=>$model->search(),
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
            'name' => 'Telefono',
            'value' => '($data->Telefono == "") ? "SIN ASIGNAR" : $data->Telefono',
        ),
        array(
            'name' => 'Correo',
            'value' => '($data->Correo == "") ? "SIN ASIGNAR" : $data->Correo',
        ),
		array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
        ),	
		array(
			'class'=>'CButtonColumn',
            'template'=>'{view}{update}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fas fa-id-badge actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Ver elementos asociados'),
                ),
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Actualizar'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && Yii::app()->user->getState("niv_det_vis_emp") == Yii::app()->params->niv_det_vis_emp_nomina)',
                ),
                /*'asigeval'=>array(
                    'label'=>'<i class="fa fa-users actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Asignar evaluador'),
                    'url'=>'Yii::app()->createUrl("empleado/asigeval", array("id"=>$data->Id_Empleado, "opc"=>1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                ),*/
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

<?php } ?>
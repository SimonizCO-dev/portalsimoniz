<?php
/* @var $this RegImpController */
/* @var $model RegImp */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#em-prod-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//usuarios para registro


$u_reg = EmProdUsuario::model()->findByPk(1)->Id_Users_Notif;


$usuarios_reg = explode(",", $u_reg);

if(in_array(Yii::app()->user->getState('id_user'), $usuarios_reg)){
    $vis = 1;
}else{
    $vis = 0;
}

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración emisiones de producto I + D + I</h4>
    </div>
    <div class="col-sm-6 text-right">
        <?php if($vis == 1){ ?>  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprod/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <?php } ?>
        <?php if(Yii::app()->user->getState('id_user') == 9){ ?>  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprodusuario/update&id=1'; ?>';"><i class="fas fa-users"></i> Usuarios registro</button>
        <?php } ?>
     <!-- <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php // echo Yii::app()->getBaseUrl(true).'/index.php?r=emprodusuario/update&id=2'; ?>';"><i class="fas fa-users"></i> Usuarios validación</button> -->
        
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'em-prod-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Em_Prod',
		'Codigo',
        'Notas',
        array(
            'name' => 'val_us',
            'value' => '$data->ResumenUsers($data->Id_Em_Prod)',
        ),		
        array(
            'class'=>'CButtonColumn',
            'template'=>'{consulta}{update}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true) && ('.$vis.' == 1)',
                ),
                'consulta'=>array(
                    'label'=>'<i class="fa fa-trash actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Eliminar'),            
                    'url'=>'Yii::app()->createUrl("emprod/consulta", array("id"=>$data->Id_Em_Prod,"cons"=>1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true) && ('.$vis.' == 1)',
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
                <div class="search-form" style="display ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_usuarios'=>$lista_usuarios,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

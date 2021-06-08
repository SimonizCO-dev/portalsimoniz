<?php
/* @var $this CControlCmsController */
/* @var $model CControlCms */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#ccontrol-cms-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');
?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Estado de liquidaciones</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ccontrol-cms-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'ID_BASE',
        array(
            'name' => 'MES',
            'value' => '$data->Desc_Mes($data->MES)',
        ),
        array(
            'name' => 'ANIO',
            'value' => '$data->ANIO',
        ),
		array(
            'name'=>'TIPO',
            'value'=>'$data->tipo->Dominio',
        ),
        array(
            'name'=>'LIQUIDACION',
            'value'=>'$data->Desc_Liq($data->LIQUIDACION)',
        ),
        
		array(
            'name' => 'VENDEDOR',
            'value' => '($data->VENDEDOR == "") ? "N/A" : $data->Desc_Vend($data->VENDEDOR)',
        ),
        'OBSERVACION',
        array(
            'name' => 'ESTADO',
            'value' => 'UtilidadesVarias::textoestado1($data->ESTADO)',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{notif}{rev}',
            'buttons'=>array(              
                'notif'=>array(
                    'label'=>'<i class="fa fa-envelope actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("cControlCms/notifliq", array("id"=>$data->ROWID))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
                    'options'=>array('title'=>'Enviar detalle via e-mail'),

                ),
                'rev'=>array(
                    'label'=>'<i class="fa fa-arrow-circle-left actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("cControlCms/revliq", array("id"=>$data->ROWID))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
                    'click'=>'function(){if (window.confirm("Esta seguro de revertir esta liquidación ?")) { return true; }else{ return false;}}',
                    'options'=>array('title'=>'Revertir liquidación'),
                ),
            )
        ),
	),
)); 

?>

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
                    'lista_tipos' => $lista_tipos,
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
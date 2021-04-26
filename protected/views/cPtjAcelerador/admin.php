<?php
/* @var $this CPtjAceleradorController */
/* @var $model CPtjAcelerador */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#cptj-acelerador-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combo  de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combo de aceleradores
$lista_aceler = CHtml::listData($aceler, 'Id_Dominio', 'Dominio');  

//para combo  de planes
$lista_planes = CHtml::listData($planes, 'Id_Plan', 'Plan_Descripcion'); 

//para combo  de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración porcentajes acelerador</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cptjacelerador/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'cptj-acelerador-grid',
    'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
    'columns'=>array(
        'ROWID',
        array(
            'name'=>'TIPO',
            'value' => '($data->TIPO == "") ? "-" : $data->tipo->Dominio',
        ),
        array(
            'name'=>'ID_ACELERADOR',
            'value' => '($data->ID_ACELERADOR == "") ? "-" : $data->acelerador->Dominio',
        ),
        array(
            'name'=>'ITEM',
            'value' => '($data->ITEM == "") ? "-" : $data->Desc_Item($data->ITEM)',
        ),
        array(
            'name'=>'ID_PLAN',
            'value' => '($data->ID_PLAN == "") ? "-" : $data->Desc_Plan($data->ID_PLAN)',
        ),
        array(
            'name'=>'CRITERIO',
            'value' => '($data->CRITERIO == "") ? "-" : $data->Desc_Criterio($data->CRITERIO)',
        ),
        array(
            'name'=>'PORCENTAJE',
            'value' => 'number_format($data->PORCENTAJE, 2)',
            'htmlOptions'=>array('style' => 'text-align: right;')
        ),
        array(
            'name'=>'FECHA_INICIAL',
            'value'=>'UtilidadesVarias::textofecha($data->FECHA_INICIAL)',
        ),
        array(
            'name'=>'FECHA_FINAL',
            'value'=>'UtilidadesVarias::textofecha($data->FECHA_FINAL)',
        ),
        array(
            'name' => 'ESTADO',
            'value' => 'UtilidadesVarias::textoestado1($data->ESTADO)',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'buttons'=>array(
                'update'=>array(
                    'label'=>'<i class="fa fa-ban actions text-dark"></i>',
                    'imageUrl'=>false,
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
                    'url'=>'Yii::app()->createUrl("CPtjAcelerador/offconfig", array("id"=>$data->ROWID))',
                    'click'=>'function(){if (window.confirm("Esta seguro de inactivar esta configuración ?")) { return true; }else{ return false;}}',
                    'options'=>array('title'=>'Inactivar configuración'),
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
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model, 
                    'lista_tipos' => $lista_tipos,
                    'lista_aceler' => $lista_aceler,
                    'lista_planes' => $lista_planes,
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
<?php
/* @var $this TipoActController */
/* @var $model TipoAct */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#tipo-act-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

//para combos de grupos
$lista_grupos = CHtml::listData($grupos, 'Id_Dominio', 'Dominio');

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración tipos de actividad</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=tipoact/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tipo-act-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Tipo',
        array(
            'name'=>'Clasificacion',
            'value' => '$data->DescClasif($data->Clasificacion)',
        ),
        array(
            'name'=>'Grupo',
            'value'=>'$data->idgrupo->Dominio',
        ),
        array(
            'name' => 'Padre',
            'value' => '($data->Padre == "") ? "-" : $data->idpadre->Tipo',
        ),
		'Tipo',
        array(
            'name' => 'Cantidad',
            'value' => '($data->Cantidad == "") ? "-" : $data->Cantidad',
        ),
        array(
            'name' => 'Fecha_Inicio',
            'value' => '($data->Fecha_Inicio == "") ? "-" : UtilidadesVarias::textofecha($data->Fecha_Inicio)',
        ),
        array(
            'name' => 'Fecha_Fin',
            'value' => '($data->Fecha_Fin == "") ? "-" : UtilidadesVarias::textofecha($data->Fecha_Fin)',
        ),
        array(
            'name' => 'Ind_Alto',
            'value' => '($data->Ind_Alto == "") ? "-" : number_format($data->Ind_Alto, 2)',
        ),
        array(
            'name' => 'Ind_Medio',
            'value' => '($data->Ind_Medio == "") ? "-" : number_format($data->Ind_Medio, 2)',
        ),
        array(
            'name' => 'Ind_Bajo',
            'value' => '($data->Ind_Bajo == "") ? "-" : number_format($data->Ind_Bajo, 2)',
        ),
		array(
            'name'=>'Estado',
            'value' => '$data->DescEstado($data->Estado)',
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
                    'lista_usuarios' => $lista_usuarios,
                    'lista_grupos' => $lista_grupos,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
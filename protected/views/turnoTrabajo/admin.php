<?php
/* @var $this TurnoTrabajoController */
/* @var $model TurnoTrabajo */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#turno-trabajo-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración turnos de trabajo</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=turnotrabajo/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'turno-trabajo-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Turno_Trabajo',
		'Rango_Dias1',
		array(
            'name'=>'Entrada1',
            'value' => '$data->HoraAmPm($data->Entrada1)',
        ),
        array(
            'name'=>'Salida1',
            'value' => '$data->HoraAmPm($data->Salida1)',
        ),
		array(
            'name'=>'Rango_Dias2',
            'value' => '($data->Rango_Dias2 == "") ? "-" : $data->Rango_Dias2',
        ),
		array(
            'name'=>'Entrada2',
            'value' => '($data->Entrada2 == "") ? "-" : $data->HoraAmPm($data->Entrada2)',
        ),
        array(
            'name'=>'Salida2',
            'value' => '($data->Salida2 == "") ? "-" : $data->HoraAmPm($data->Salida2)',
        ),
		array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
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
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

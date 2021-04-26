<?php
/* @var $this CPresupuestoController */
/* @var $model CPresupuesto */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#cpresupuesto-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");
?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración presupuesto x vendedor</h4>
    </div>
    <div class="col-sm-6 text-right"> 
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>

        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Asignación masiva
            </button>
            <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/files/talento_humano/plantillas/plantilla_pres_x_vend.xlsx'; ?>">Plantilla asignación masiva</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cpresupuesto/imp'; ?>">Asignación masiva</a></li>
            </ul>
        
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cpresupuesto-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'ID_PRESUPUESTO_VEND',
		'NIT_VENDEDOR',
		array(
            'name'=>'PRESUPUESTO',
            'value' => 'number_format($data->PRESUPUESTO, 2)',
            'htmlOptions'=>array('style' => 'text-align: right;')
        ),
		array(
            'name' => 'ESTADO',
            'value' => 'UtilidadesVarias::textoestado1($data->ESTADO)',
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
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

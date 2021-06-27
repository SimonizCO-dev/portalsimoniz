<?php
/* @var $this ContController */
/* @var $model Cont */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
    $('#cont-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de empresas
$lista_empresas = CHtml::listData($empresas, 'Id_Pa_Empresa', 'Descripcion');

//para combos de tipos de periodicidad
$lista_period = CHtml::listData($period, 'Id_Dominio', 'Dominio');

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Administración de contratos</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cont-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Contrato',
        array(
            'name'=>'Tipo',
            'value'=>'$data->DescTipo($data->Tipo)',
        ),
		array(
            'name'=>'Empresa',
            'value'=>'$data->empresa->Descripcion',
        ),
        'Razon_Social',
		'Concepto_Contrato',
        array(
            'name'=>'Periodicidad',
            'value'=>'$data->periodicidad->Dominio',
        ),
		'Area',
        array(
            'name' => 'vlr_cont',
            'value' => '$data->VlrCont($data->Id_Contrato)',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
		array(
            'name'=>'Fecha_Inicial',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Inicial)',
        ),
		array(
            'name'=>'Fecha_Final',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Final)',
        ),
        array(
            'name'=>'Fecha_Ren_Can',
            'value'=>'UtilidadesVarias::textofecha($data->Fecha_Ren_Can)',
            'cssClassExpression' => 'UtilidadesVarias::estadofechavencimiento($data->Id_Contrato)',
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
                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
                ),
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

<div class="modal fade" id="modal-search">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Busqueda avanzada</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_empresas' => $lista_empresas,
                    'lista_period' => $lista_period,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body" id="modal-info">
                
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>

$(function() {
  $('.ajax-loader').fadeIn('fast');  
  var url = "<?php echo Yii::app()->createUrl('Cont/ViewRes'); ?>";
  $('#modal-info').load(url,function(){
    $('#modal').modal({show:true});
    $('.ajax-loader').fadeOut('fast');
  });

});

function filtro(valor){
    $('.ajax-loader').fadeIn('fast');
    $('#Cont_view').val(valor).trigger('change');
    $('#yt0').click();
    $('#modal').modal('toggle');
    setTimeout(function(){ $('.ajax-loader').fadeOut('fast'); }, 3000);
}

</script>
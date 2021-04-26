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
        <h4>Reporte base de comisiones</h4>
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
		/*array(
            'name'=>'ID_USUARIO_CREACION',
            'value'=>'$data->idusuariocre->Usuario',
        ),
        array(
            'name'=>'FECHA_CREACION',
            'value'=>'UtilidadesVarias::textofechahora($data->FECHA_CREACION)',
        ),
        array(
            'name'=>'ID_USUARIO_ACTUALIZACION',
            'value'=>'$data->idusuarioact->Usuario',
        ),
        array(
            'name'=>'FECHA_ACTUALIZACION',
            'value'=>'UtilidadesVarias::textofechahora($data->FECHA_ACTUALIZACION)',
        ),*/
        array(
            'name' => 'ESTADO',
            'value' => 'UtilidadesVarias::textoestado1($data->ESTADO)',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{reppdf}{repexcel}',
            'buttons'=>array(
                'reppdf'=>array(
                    'label'=>'<i class="fas fa-file-pdf actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Generar reporte en PDF'),
                    'url'=>'Yii::app()->createUrl("cControlCms/genrepliq", array("id"=>$data->ID_BASE, "opc" => 1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
                ),
                'repexcel'=>array(
                    'label'=>'<i class="fas fa-file-excel actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Generar reporte en EXCEL'),
                    'url'=>'Yii::app()->createUrl("cControlCms/genrepliq", array("id"=>$data->ID_BASE, "opc" => 2))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
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

<script type="text/javascript">
    
$(function () {
    $(".actions").click(function() {
        $(".ajax-loader").fadeIn('fast'); 
        setTimeout(function(){ $(".ajax-loader").fadeOut('fast');  }, 10000);
    });
});

</script>

<?php
/* @var $this CControlCmsController */
/* @var $model CControlCms */

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle('fast');
	return false;
});
$('.search-form form').submit(function(){
	$('#ccontrol-cms-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

//para combos de usuarios
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio');

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Usuario', 'Usuario');
?>

<h3>Reporte base de comisiones</h3>

<div class="btn-group" style="padding-bottom: 2%">
    <button type="button" class="btn btn-success search-button"><i class="fa fa-filter"></i> Busqueda avanzada</button>
</div>

<div class="search-form" style="display:none;">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
	'lista_tipos' => $lista_tipos,
	'lista_usuarios' => $lista_usuarios,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ccontrol-cms-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
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
                    'label'=>'<i class="fa fa-file-pdf-o actions text-black"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Generar reporte en PDF'),
                    'url'=>'Yii::app()->createUrl("cControlCms/genrepliq2", array("id"=>$data->ID_BASE, "opc" => 1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
                ),
                'repexcel'=>array(
                    'label'=>'<i class="fa fa-file-excel-o actions text-black"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Generar reporte en EXCEL'),
                    'url'=>'Yii::app()->createUrl("cControlCms/genrepliq2", array("id"=>$data->ID_BASE, "opc" => 2))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->ESTADO == 1)',
                ), 
            )
        ),
	),
)); 

?>

<script type="text/javascript">
    
$(function () {
    $(".actions").click(function() {
        $(".ajax-loader").fadeIn('fast'); 
        setTimeout(function(){ $(".ajax-loader").fadeOut('fast');  }, 10000);
    });
});

</script>

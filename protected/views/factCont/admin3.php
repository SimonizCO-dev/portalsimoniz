<?php
/* @var $this FactContController */
/* @var $model FactCont */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('fact-cont-grid',{
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 20000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
    $('#fact-cont-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario');

//para combos de areas
$lista_areas = CHtml::listData($areas, 'Id_Area', 'Area');

?>

<div id="div_mensaje" style="display: none;">
</div>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Actualización estado de facturas</h4>
  </div>
  <div class="col-sm-6 text-right"> 
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'fact-cont-grid',
	'dataProvider'=>$model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_Fact',
		array(
			'name' => 'Empresa',
			'value' => '$data->DescEmpresa($data->Empresa)',
		),
		array(
            'name' => 'Area',
            'value' => '$data->area->Area',
        ),
		'Num_Factura',
		array(
			'name'=>'Fecha_Factura',
			'value'=>'UtilidadesVarias::textofecha($data->Fecha_Factura)',
		),
		array(
			'name'=>'Fecha_Radicado',
			'value'=>'UtilidadesVarias::textofecha($data->Fecha_Radicado)',
		),
		array(
			'name' => 'Proveedor',
			'value' => '$data->DescProveedor($data->Proveedor)',
		),
		array(
			'name'=>'Valor',
			'value'=>function($data){
				return number_format($data->Valor, 2);
			},
			'htmlOptions'=>array('style' => 'text-align: right;'),
		),
		array(
			'name' => 'Moneda',
			'value' => '$data->DescMoneda($data->Moneda)',
		),
		array(
			'name' => 'Estado',
			'value' => '$data->DescEstado($data->Estado)',
		),

		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{recibir}{rechazar}{anular}{revertir}',
			'buttons'=>array(
				'view'=>array(
					'label'=>'<i class="fa fa-eye actions text-dark"></i>',
					'imageUrl'=>false,
					'options'=>array('title'=>'Visualizar'),
					'url'=>'Yii::app()->createUrl("factCont/view", array("id"=>$data->Id_Fact, "opc"=> 3))',
				),
				'recibir' => array(
                    'label'=>'<i class="fas fa-check-circle actions text-dark"></i>',
                    'imageUrl'=>false, 
                    'url'=>'Yii::app()->createUrl("factCont/recibir", array("id"=>$data->Id_Fact))', 
                    'visible'=> '($data->Estado == 1)',                 
                    'options'=>array('title'=>' Recibir factura'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de recibir esta factura ?')) {

                            $.fn.yiiGridView.update('fact-cont-grid', {
                                type:'POST',
                                dataType: 'json',
                                url:$(this).attr('href'),
                                success:function(data) {

                                    var res = data.res; 
                                    var mensaje = data.msg;

                                    if(res == 0){
                                        $('#div_mensaje').addClass('alert alert-warning alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-info-circle\"></i>Info</h5>'+mensaje);
                                    }

                                    if(res == 1){
                                        $('#div_mensaje').addClass('alert alert-primary alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-check-circle\"></i>Realizado</h5>'+mensaje);
                                    }

                                    $('#div_mensaje').fadeIn('fast');
                                    $.fn.yiiGridView.update('fact-cont-grid');
                                }
                            })
                            return false;
                        }else{
                            return false;    
                        }
                    }",
                ),
                'rechazar' => array(
                    'label'=>'<i class="fas fa-minus-circle actions text-dark"></i>',
                    'imageUrl'=>false, 
                    'url'=>'Yii::app()->createUrl("factCont/rechazar", array("id"=>$data->Id_Fact))',
                    'visible'=> '($data->Estado == 1)',                    
                    'options'=>array('title'=>' Rechazar factura'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de rechazar esta factura ?')) {

                            $.fn.yiiGridView.update('fact-cont-grid', {
                                type:'POST',
                                dataType: 'json',
                                url:$(this).attr('href'),
                                success:function(data) {

                                    var res = data.res; 
                                    var mensaje = data.msg;

                                    if(res == 0){
                                        $('#div_mensaje').addClass('alert alert-warning alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-info-circle\"></i>Info</h5>'+mensaje);
                                    }

                                    if(res == 1){
                                        $('#div_mensaje').addClass('alert alert-primary alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-check-circle\"></i>Realizado</h5>'+mensaje);
                                    }

                                    $('#div_mensaje').fadeIn('fast');
                                    $.fn.yiiGridView.update('fact-cont-grid');
                                }
                            })
                            return false;
                        }else{
                            return false;    
                        }
                    }",
                ),
                'anular' => array(
                    'label'=>'<i class="fa fas fa-times-circle actions text-dark"></i>',
                    'imageUrl'=>false, 
                    'url'=>'Yii::app()->createUrl("factCont/anular", array("id"=>$data->Id_Fact, "opc"=> 3, "e"=> 0))',
                    'visible'=> '($data->Estado == 1 || $data->Estado == 2 || $data->Estado == 3)',                  
                    'options'=>array('title'=>' Anular factura'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de anular esta factura ?')) {

                            $.fn.yiiGridView.update('fact-cont-grid', {
                                type:'POST',
                                dataType: 'json',
                                url:$(this).attr('href'),
                                success:function(data) {

                                    var res = data.res; 
                                    var mensaje = data.msg;

                                    if(res == 0){
                                        $('#div_mensaje').addClass('alert alert-warning alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-info-circle\"></i>Info</h5>'+mensaje);
                                    }

                                    if(res == 1){
                                        $('#div_mensaje').addClass('alert alert-primary alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-check-circle\"></i>Realizado</h5>'+mensaje);
                                    }

                                    $('#div_mensaje').fadeIn('fast');
                                    $.fn.yiiGridView.update('fact-cont-grid');
                                }
                            })
                            return false;
                        }else{
                            return false;    
                        }
                    }",
                ),
                'revertir' => array(
                    'label'=>'<i class="fa fas fa-arrow-circle-left actions text-dark"></i>',
                    'imageUrl'=>false, 
                    'url'=>'Yii::app()->createUrl("factCont/revertir", array("id"=>$data->Id_Fact))', 
                    'visible'=> '($data->Estado == 2 || $data->Estado == 3)',                   
                    'options'=>array('title'=>' Revertir factura'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de revertir esta factura ?')) {

                            $.fn.yiiGridView.update('fact-cont-grid', {
                                type:'POST',
                                dataType: 'json',
                                url:$(this).attr('href'),
                                success:function(data) {

                                    var res = data.res; 
                                    var mensaje = data.msg;

                                    if(res == 0){
                                        $('#div_mensaje').addClass('alert alert-warning alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-info-circle\"></i>Info</h5>'+mensaje);
                                    }

                                    if(res == 1){
                                        $('#div_mensaje').addClass('alert alert-primary alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-check-circle\"></i>Realizado</h5>'+mensaje);
                                    }

                                    $('#div_mensaje').fadeIn('fast');
                                    $.fn.yiiGridView.update('fact-cont-grid');
                                }
                            })
                            return false;
                        }else{
                            return false;    
                        }
                    }",
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
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_usuarios' => $lista_usuarios,
                    'lista_areas' => $lista_areas,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
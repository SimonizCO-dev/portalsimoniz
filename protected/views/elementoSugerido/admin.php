<?php

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#elemento-sugerido-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
    $('#modal-search').modal('hide');
	return false;
});
");

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

//para combo de sugeridos
$lista_sugeridos = array();
foreach ($sugeridos as $sug) {
	$lista_sugeridos[$sug->Id_Sugerido] = $sug->idcargo->Cargo.' - '.$sug->idsubarea->Subarea.' / '.$sug->idarea->Area;
}

//para combo de elementos por area
$lista_elementos = array();
foreach ($elementos_area as $elem) {

    $id_elemento = $elem->Id_Elemento;
    $id_subarea = $elem->Id_Subarea;
    $id_area = $elem->Id_Area;

    if(is_null($id_elemento)){
        $elemento = 'SIN ASIGNAR';
    }else{
        $elemento = $elem->idelemento->Elemento;
    }

    if(is_null($id_subarea)){
        $subarea = 'SIN ASIGNAR';
    }else{
        $subarea = $elem->idsubarea->Subarea;
    }

    if(is_null($id_area)){
        $area = 'SIN ASIGNAR';
    }else{
        $area = $elem->idarea->Area;
    }

	$lista_elementos[$elem->Id_A_elemento] = $elemento.' ('.$subarea.' / '.$area.')';
}

?>

<div id="div_mensaje" style="display: none;"></div>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Consulta de elementos por sugerido</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'elemento-sugerido-grid',
	'dataProvider'=>$model->search(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		'Id_E_Sugerido',
		array(
            'name'=>'Id_Sugerido',
            'value'=>'UtilidadesSugerido::sugerido($data->Id_Sugerido)',
        ),
        'Cantidad',
        array(
            'name'=>'Id_A_Elemento',
            'value'=>'UtilidadesSugerido::areasubareaelemento($data->Id_A_Elemento)',
        ),
        array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
        ),
		array(
            'class'=>'CButtonColumn',
            'template'=>'{act}{inact}',
            'buttons'=>array(
                'act'=>array(
                    'label'=>'<i class="fas fa-toggle-on actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("elementosugerido/act", array("id"=>$data->Id_E_Sugerido, "opc"=>1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 0 && $data->idsugerido->Estado == 1)',
                    'options'=>array('title'=>' Inactivar'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de activar el elemento ?')) {

                            $.fn.yiiGridView.update('elemento-sugerido-grid', {
                                type:'POST',
                                dataType: 'json',
                                url:$(this).attr('href'),
                                success:function(data) {

                                    var res = data.res; 
                                    var mensaje = data.msg;

                                    if(res == 0){
                                        $('#div_mensaje').addClass('alert alert-warning alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-info-circle\"></i>Info</h5>'+mensaje+);
                                    }

                                    if(res == 1){
                                        $('#div_mensaje').addClass('alert alert-primary alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-check-circle\"></i>Realizado</h5>'+mensaje);
                                    }


                                    $('#div_mensaje').fadeIn('fast');
                                    $.fn.yiiGridView.update('elemento-sugerido-grid');
                                }
                            })
                            return false;
                        }else{
                            return false;    
                        }
                    }",
                ),
                'inact'=>array(
                    'label'=>'<i class="fas fa-toggle-off actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("elementosugerido/inact", array("id"=>$data->Id_E_Sugerido, "opc"=>1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1 && $data->idsugerido->Estado == 1)',
                    'options'=>array('title'=>' Inactivar'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de inactivar el elemento ?')) {

                            $.fn.yiiGridView.update('elemento-sugerido-grid', {
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
                                    $.fn.yiiGridView.update('elemento-sugerido-grid');
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
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_usuarios'=>$lista_usuarios,
                    'lista_sugeridos'=>$lista_sugeridos,
                    'lista_elementos'=>$lista_elementos,
                )); ?>
                </div><!-- search-form -->
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

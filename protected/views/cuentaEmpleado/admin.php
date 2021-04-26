<?php
/* @var $this CuentaEmpleadoController */
/* @var $model CuentaEmpleado */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('cuenta-empleado-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
    $('#cuenta-empleado-grid').yiiGridView('update', {
        data: $(this).serialize()
    });
    $('#modal-search').modal('hide');
    return false;
});
");

//para combos de clases de cuenta / usuario
$lista_clases = CHtml::listData($clases, 'Id_Dominio', 'Dominio'); 

//para combos de usuarios
$lista_usuarios = CHtml::listData($usuarios, 'Id_Usuario', 'Usuario'); 

?>

<div id="div_mensaje" style="display: none;"></div>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Consulta usuario(s) / cuenta(s) por empleado</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
        <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cuenta-empleado-grid',
	'dataProvider'=>$model->searchhist(),
	'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
        array(
            'name'=>'Id_Cuenta',
            'value'=>'$data->DescCuentaUsuario($data->Id_Cuenta)',
        ),
        array(
            'name'=>'Id_Empleado',
            'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Empleado)',
        ),
        array(
            'header'=>'Empresa',
            'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::empresaactualempleado($data->Id_Empleado)',
        ),
        array(
            'header'=>'Cargo',
            'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::cargoactualempleado($data->Id_Empleado)',
        ),
		/*array(
            'name'=>'Id_Usuario_Creacion',
            'value'=>'$data->idusuariocre->Usuario',
        ),
        array(
            'name'=>'Fecha_Creacion',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
        ),
        array(
            'name'=>'Id_Usuario_Actualizacion',
            'value'=>'$data->idusuarioact->Usuario',
        ),
        array(
            'name'=>'Fecha_Actualizacion',
            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
        ),*/
        array(
            'name' => 'Estado',
            'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
        ),
		array(
			'class'=>'CButtonColumn',
	        'template'=>'{viewcuenta}{upd}',
	        'buttons'=>array(
                'viewcuenta' => array(
                    'label'=>'<i class="fas fa-address-card actions text-dark"></i>',
                    'imageUrl'=>false,                    
                    'url'=>'Yii::app()->createUrl("cuenta/view", array("id"=>$data->Id_Cuenta))',
                    'options'=>array('title'=>' Ver detalle de cuenta en nueva pestaña', 'target' => '_new'),
                ),
                'upd' => array(
                    'label'=>'<i class="fa fa-user-times actions text-dark"></i>',
                    'imageUrl'=>false,                    
                    'url'=>'Yii::app()->createUrl("cuentaempleado/inact", array("id"=>$data->Id_Cuenta_Emp, "opc"=>1))',
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                    'options'=>array('title'=>' Desvincular empleado'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de desvincular el empleado de esta cuenta ?')) {

                            $.fn.yiiGridView.update('cuenta-empleado-grid', {
                                type:'POST',
                                dataType: 'json',
                                url:$(this).attr('href'),
                                success:function(data) {

                                    var res = data.res; 
                                    var mensaje = data.msg;

                                    if(res == 0){
                                        $('#div_mensaje').addClass('alert alert-warning alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-info-circle\"></i>Info</h5><p>'+mensaje+'</p>');
                                    }

                                    if(res == 1){
                                        $('#div_mensaje').addClass('alert alert-success alert-dismissible');
                                        $('#div_mensaje').html('<button type=\"button\" class=\"close\" aria-hidden=\"true\" onclick=\"limp_div_msg();\">×</button><h5><i class=\"icon fas fa-check-circle\"></i>Realizado</h5><p>'+mensaje+'</p>');
                                    }


                                    $('#div_mensaje').fadeIn('fast');
                                    $.fn.yiiGridView.update('cuenta-empleado-grid');
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
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="search-form" style="display:; ">
                <?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    'lista_clases' => $lista_clases,
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
    
    //función para limpiar el mensaje retornado por el ajax
    function limp_div_msg(){
        $("#div_mensaje").hide();  
        classact = $('#div_mensaje').attr('class');
        $("#div_mensaje").removeClass(classact);
        $("#mensaje").html('');
    }

</script>

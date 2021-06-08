<?php
/* @var $this TicketController */
/* @var $model Ticket */

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#ticket-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	$('#modal-search').modal('hide');
	return false;
});
");

//para combos de grupos
$lista_grupos = $grupos;

?>

<?php /*$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ticket-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'Id_Ticket',
		'Id_Grupo',
		'Id_Tipo',
		'Solicitud',
		'Fecha_Asig',
		'Id_Usuario_Asig',
		'Fecha_Cierre',
		'Calificacion',
		'Fecha_Calificacion',
		'Id_Usuario_Creacion',
		'Fecha_Creacion',
		'Id_Usuario_Actualizacion',
		'Fecha_Actualizacion',
		'Estado',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); */?>


<?php if($a === 1) { ?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Asignación / administración de tickets</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'ticket-grid',
    'dataProvider'=>$model->asig(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
    'columns'=>array(
        'Id_Ticket',
        array(
            'name' => 'Fecha_Creacion',
            'value' => 'UtilidadesVarias::textofecha($data->Fecha_Creacion)',
        ),
        array(
            'name'=>'Id_Usuario_Creacion',
            'value' => '$data->idusuariocre->Nombres',
        ),
        array(
            'name' => 'Id_Tipo',
            'value' => '$data->DescTipo($data->Id_Tipo)',
        ),
        array(
            'name' => 'Grupo',
            'value' => '$data->idgrupo->Dominio',
        ),
        
        'Solicitud',
        array(
            'name' => 'Estado',
            'value' => '$data->DescEstado($data->Estado)',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view}{asig}{update}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fas fa-clipboard actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar ticket'),
                    'url'=>'Yii::app()->createUrl("ticket/view", array("id"=>$data->Id_Ticket))', 
                ),
                'asig'=>array(
                    'label'=>'<i class="fas fa-user-check actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Asignarme este ticket'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Id_Usuario_Asig == "")',
                    //'imageUrl'=>false, 
                    'url'=>'Yii::app()->createUrl("ticket/asigt", array("id"=>$data->Id_Ticket))', 
                    //'visible'=> '($data->Estado == 1)',                 
                    //'options'=>array('title'=>' Recibir factura'),
                    'click'=>"
                    function() {
                        if(confirm('Esta seguro de asignarse este ticket ?')) {

                            $.fn.yiiGridView.update('ticket-grid', {
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
                'update'=>array(
                    'label'=>'<i class="fas fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Actualizar ticket'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Id_Usuario_Asig == Yii::app()->user->getState("id_user"))',
                    'url'=>'Yii::app()->createUrl("ticket/update", array("id"=>$data->Id_Ticket))', 
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
                <?php $this->renderPartial('_searchasig',array(
                    'model'=>$model,
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

<?php }else{ ?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Asignación de tickets</h4>
  </div>
  <div class="col-sm-6 text-right">  
  </div>
</div>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene novedades de actividades asociadas.
</div> 

<?php } ?>

<?php
/* @var $this WipController */
/* @var $model Wip */

Yii::app()->clientScript->registerScript('search', "
$('#export-excel').on('click',function() {
    $.fn.yiiGridView.export();
});
$.fn.yiiGridView.export = function() {
    $.fn.yiiGridView.update('wip-grid',{ 
        success: function() {
            window.location = '". $this->createUrl('exportexcel')  . "';
            $(\".ajax-loader\").fadeIn('fast');
            setTimeout(function(){ $(\".ajax-loader\").fadeOut('fast'); }, 10000);
        },
        data: $('.search-form form').serialize() + '&export=true'
    });
}
$('.search-form form').submit(function(){
	$('#wip-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	$('#modal-search').modal('hide');
	return false;
});
");
?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Administración de WIP</h4>
  </div>
  <div class="col-sm-6 text-right"> 
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=wip/create'; ?>';"><i class="fa fa-plus"></i> Nuevo registro</button> 
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-search"><i class="fa fa-filter"></i> Busqueda avanzada</button>
    <button type="button" class="btn btn-primary btn-sm" id="export-excel"><i class="fas fa-file-excel"></i> Exportar a EXCEL</button>
  </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'wip-grid',
	'dataProvider' => $model->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		//'ID',
		//'CONSECUTIVO',
		array(
            'name'=>'WIP',
    	),
    	array(
            'header' => 'Cadena / Observaciones',
            'type' => 'raw',
            'value' => '$data->desccadena($data->ID)',
        ),
		'ID_ITEM',
		array(
            'name' => 'DESCRIPCION',
            'value' => 'substr($data->DESCRIPCION, 0, 35)',
        ),
		'RESPONSABLE',
		array(
            'class'=>'CButtonColumn',
            'template'=>'{view}{update}{notif}',
            'buttons'=>array(
                'view'=>array(
                    'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Visualizar'),
                    'visible'=> '$data->vbtnview($data->FECHA_CUMPLIDO)',
                    'url'=>'Yii::app()->createUrl("Wip/view", array("id"=>$data->ID))',
                ),
                'update'=>array(
                    'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                    'imageUrl'=>false,
                    'options'=>array('title'=>'Actualizar'),
                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->vbtnupdate($data->FECHA_CUMPLIDO) == true)',
                    'url'=>'Yii::app()->createUrl("Wip/update", array("id"=>$data->ID))',
                ),
                'notif'=>array(
                    'label'=>'<i class="fa fa-envelope actions text-dark"></i>',
                    'imageUrl'=>false,
                    'url'=>'Yii::app()->createUrl("Wip/notifwip", array("id"=>$data->ID))',
                    'options'=>array('title'=>'Enviar WIP via e-mail'),

                ),   
            )
        ),
        /*array(
	        'name'=>'',
	        'type'=>'html',
	        'value'=>function($data){
	            echo '<button type="button" class="btn btn-default btn-sm btn-rep text-dar" data-toggle="modal" data-target="#modal-wip" onclick="firmawip('.$data->ID.');"><i class="fas fa-file-signature"></i> Firma / PDF</button>';
	        },
	    ),*/   
	),
)); ?>

<div class="modal fade" id="modal-wip">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">WIP</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<input class="form-control form-control-sm" autocomplete="off" name="id_wip" id="id_wip" type="hidden">  
		  	<div class="col-sm-6">
		        <div class="form-group">
		        	<div class="badge badge-warning float-right" id="error_firma" style="display: none"></div>
	                <label>Firma</label>
	                <input class="form-control form-control-sm" autocomplete="off" name="firma_wip" id="firma_wip" onkeyup="convert_may(this)" type="text">
	            </div>
		    </div>
		    <div class="col-sm-6">
		        <div class="form-group">
		        	<div class="badge badge-warning float-right" id="error_cargo" style="display: none"></div>
	                <label>Cargo</label>     
	                <input class="form-control form-control-sm" autocomplete="off" name="cargo_wip" id="cargo_wip" onkeyup="convert_may(this)" type="text">        
	            </div>
		    </div>
		    
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal" id="close_modal"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-success btn-sm" id="gen_wip"><i class="fas fa-file-pdf"></i> Generar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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
                    'lista_cadenas'=>$lista_cadenas,
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

	$(function(){

		$("#firma_wip").change(function() {
			val = $(this).val();
			if(val != ""){
				$('#error_firma').html('');
				$('#error_firma').hide();
			}
		});

		$("#cargo_wip").change(function() {
			val = $(this).val();
			if(val != ""){
				$('#error_cargo').html('');
				$('#error_cargo').hide();
			}
		});

		$("#close_modal").click(function() {
  			$('#id_wip').val('');
  			$('#firma_wip').val('');
  			$('#cargo_wip').val('');
  			$('#error_firma').html('');
			$('#error_firma').hide();
			$('#error_cargo').html('');
			$('#error_cargo').hide();
		});

		$("#gen_wip").click(function() {
			
			var id = $('#id_wip').val();
			var firma = $('#firma_wip').val();
			var cargo = $('#cargo_wip').val();

			if(firma != "" && cargo != ""){

				$('#error_firma').html('');
				$('#error_firma').hide();
				$('#error_cargo').html('');
				$('#error_cargo').hide();
				$('#modal-wip').modal('hide');
				$('#firma_wip').val('');
  				$('#cargo_wip').val('');
				var url = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Wip/genrepwip'; ?>';
				var vars = '&id='+id+'&firma='+firma+'&cargo='+cargo;
				comp_url = url+vars;

  				location.href = comp_url;
				$('#wip-grid').yiiGridView.update('wip-grid');

			}else{
				if(firma == ""){
					$('#error_firma').html('Firma es requerido.');
					$('#error_firma').show();
				}

				if(cargo == ""){
					$('#error_cargo').html('Cargo es requerido.');
					$('#error_cargo').show();
				}
			}

  			//window.location = url;
		});

	});
	
	function firmawip(idwip){
		$('#id_wip').val('');
		$('#firma_wip').val('');
		$('#cargo_wip').val('');
		$('#error_firma').html('');
		$('#error_firma').hide();
		$('#error_cargo').html('');
		$('#error_cargo').hide();
		$('#id_wip').val(idwip);
	}

</script>

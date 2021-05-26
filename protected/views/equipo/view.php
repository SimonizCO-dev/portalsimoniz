<?php
/* @var $this EquipoController */
/* @var $model Equipo */

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Detalle de equipo</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=equipo/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar soporte</button>
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          Acciones
        </button>
        <?php if ($asociacion == 1) { ?>  
        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=licenciaEquipo/create&e='.$model->Id_Equipo; ?>">Asociar licencia a equipo</a></li>
        <?php if ($asociacion == 1 && $asoc_emp == 1) { ?> 
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empEquipo/create&e='.$model->Id_Equipo; ?>">Asociar empleado a equipo</a></li>
        <?php } ?>
        <?php if ($asociacion == 1 && $n_ip_act < 2) { ?> 
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=network/asig2&e='.$model->Id_Equipo; ?>">Asociar IP a equipo</a></li>
        <?php } ?>
        </ul>
        <?php } ?>        
    </div>
</div>

<div id="info_e">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#lic">licencia(s)</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#emp">Empleado(s)</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#network">Red</a></li>
    </ul>

    <!-- Tab panes -->
     <div class="tab-content">
        <div id="info" class="tab-pane active"><br>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>ID</label>
                        <?php echo '<p>'.$model->Id_Equipo.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Tipo</label>
                        <?php echo '<p>'.$model->tipoequipo->Dominio.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Serial</label>
                        <?php echo '<p>'.$model->Serial.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Modelo</label>
                        <?php echo '<p>'.$model->Modelo.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>MAC LAN</label>
                        <?php echo '<p>'.$model->MAC1.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>MAC WAN</label>
                        <?php echo '<p>'.$model->MAC2.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Empresa que compro</label>
                        <?php echo '<p>'.$model->empresacompra->Descripcion.'</p>';?>
                    </div>
                </div>
                 <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de compra</label>
                        <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Compra).'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Proveedor</label>
                        <?php echo '<p>'.$model->proveedor->Proveedor.'</p>'; ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>N° de factura</label>
                        <?php echo '<p>'.$model->Numero_Factura.'</p>'; ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>N° de Inventario</label>
                        <?php echo '<p>'.$model->Numero_Inventario.'</p>'; ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Usuario que creo</label>
                        <?php echo '<p>'.$model->idusuariocre->Usuario.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de creación</label>
                        <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Ultimo usuario que actualizó</label>
                        <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Ultima fecha de actualización</label>
                        <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Estado</label>
                        <?php echo '<p>'.UtilidadesVarias::textoestado1($model->Estado).'</p>'; ?>
                    </div>
                </div>  
            </div>            
        </div>
        <div id="lic" class="tab-pane fade"><br>
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'licencia-equipo-grid',
                'dataProvider'=>$licencias->search(),
                'pager'=>array(
                    'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                ),
                'enableSorting' => false,
                'columns'=>array(
                    array(
                        'name'=>'Id_Licencia',
                        'value'=>'$data->idlicencia->DescLicencia($data->Id_Licencia)',
                    ),
                    array(
                        'name' => 'Estado',
                        'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{viewlicencia}{update}',
                        'buttons'=>array(
                            'viewlicencia' => array(
                                'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                                'imageUrl'=>false,                    
                                'url'=>'Yii::app()->createUrl("Licencia/view", array("id"=>$data->Id_Licencia))',
                                'options'=>array('title'=>' Ver detalle de licencia en nueva pestaña', 'target' => '_new'),
                            ),
                            'update'=>array(
                                'label'=>'<i class="fas fa-unlink actions text-dark"></i>',
                                'imageUrl'=>false,
                                'url'=>'Yii::app()->createUrl("licenciaEquipo/inact", array("id"=>$data->Id_Lic_Equ, "opc"=>2))',
                                'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',

                                'click'=>'function(){if (window.confirm("Esta seguro de desvincular la licencia de este equipo ?")) { return true; }else{ return false;}}',
                                'options'=>array('title'=>'Desvincular licencia'),
                            ),
                        )
                    ),
                ),
            )); ?>               
        </div>
        <div id="emp" class="tab-pane fade"><br>
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'emp-equipo-grid',
                'dataProvider'=>$emp->search(),
                'pager'=>array(
                    'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                ),
                'enableSorting' => false,
                'columns'=>array(
                    array(
                        'name'=>'Id_Emp',
                        'value'=>'UtilidadesEmpleado::nombreempleado($data->Id_Emp)',
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
                                'label'=>'<i class="fas fa-unlink actions text-dark"></i>',
                                'imageUrl'=>false,
                                'url'=>'Yii::app()->createUrl("empEquipo/inact", array("id"=>$data->Id_Emp_Equ, "opc"=>2))',
                                'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',

                                'click'=>'function(){if (window.confirm("Esta seguro de desvincular el empleado de este equipo ?")) { return true; }else{ return false;}}',
                                'options'=>array('title'=>'Desvincular empleado'),
                            ),
                        )
                    ),
                ),
            )); ?>               
        </div>
        <div id="network" class="tab-pane fade"><br>
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'network-equipo-grid',
                'dataProvider'=>$network->search(),
                'pager'=>array(
                    'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                ),
                'enableSorting' => false,
                'columns'=>array(
                    array(
                        'name'=>'ip',
                        'value'=>'$data->idnetwork->Ip($data->Id_Network)',
                    ),
                    array(
                        'name' => 'Estado',
                        'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{lib}',
                        'buttons'=>array(
                            'lib'=>array(
                                'label'=>'<i class="fas fa-unlink actions text-dark"></i>',
                                'imageUrl'=>false,
                                'url'=>'Yii::app()->createUrl("network/lib", array("id"=>$data->Id_Network, "opc"=>2))',
                                'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                                'click'=>'function(){if (window.confirm("Esta seguro de liberar esta IP ?")) { return true; }else{ return false;}}',
                                'options'=>array('title'=>'Liberar IP'),
                            ),
                        )
                    ),
                ),
            )); ?>   
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script type="text/javascript">

$(function() {

    var archivo =  "<?php echo $model->Doc_Soporte; ?>"; 
    var ext = archivo.split('.').pop();

    if($.trim(ext) == "pdf"){
        renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/panel_adm/docs_equipos_licencias/equipos/'.$model->Doc_Soporte; ?>');
    }else{
        $('#img').attr('src', '<?php echo Yii::app()->baseUrl."/files/panel_adm/docs_equipos_licencias/equipos/".$model->Doc_Soporte; ?>');
    }

    //loadershow();

    $('#toogle_button').click(function(){
        
        var archivo =  "<?php echo $model->Doc_Soporte; ?>"; 
        var ext = archivo.split('.').pop();

        if($.trim(ext) == "pdf"){
            $('#viewer').toggle('fast');
            $('#info_e').toggle('fast');
        }else{
            $('#viewer_img').toggle('fast');
            $('#info_e').toggle('fast');
        }
        
        return false;

    });

});
   
</script>

<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none;">
    </div>
    <div id="viewer_img" class="col-sm-12 text-center" style="display: none;">
        <img id="img" class="img-responsive"/>
    </div>  
</div>

<?php
/* @var $this LicenciaController */
/* @var $model Licencia */

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Detalle de licencia</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=licencia/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar soporte</button>
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          Acciones
        </button>
        <?php if ($asociacion == 1) { ?>  
        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=licenciaEquipo/create2&l='.$model->Id_Lic; ?>">Asociar equipo a licencia</a></li>
       </ul>
       <?php } ?>        
    </div>
</div>

<div id="info_e">
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#equ">Equipo(s)</a></li>
    </ul>
    <!-- Tab panes -->
     <div class="tab-content">
        <div id="info" class="tab-pane active"><br>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>ID</label>
                        <?php echo '<p>'.$model->Id_Lic.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Clasif.</label>
                        <?php echo '<p>'.$model->clasificacion->Dominio.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Tipo</label>
                        <?php echo '<p>'.$model->tipo->Dominio.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Versión</label>
                        <?php if($model->Version == "") { $Version = "-"; } else { $Version = $model->version->Dominio; } ?>
                        <?php echo '<p>'. $model->version->Dominio.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Producto</label>
                        <?php if($model->Producto == "") { $Producto = "-"; } else { $Producto = $model->producto->Dominio; } ?>
                        <?php echo '<p>'.$Producto.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>ID licencia</label>
                        <?php echo '<p>'.$model->Id_Licencia.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>N° de licencia</label>
                        <?php echo '<p>'.$model->Num_Licencia.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Token</label>
                        <?php if($model->Token == "") { $Token = "-"; } else { $Token = $model->Token; } ?>
                        <?php echo '<p>'.$Token.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Empresa que compro</label>
                        <?php if($model->Empresa_Compra == "") { $Empresa_Compra = "-"; } else { $Empresa_Compra = $model->empresacompra->Descripcion; } ?>
                        <?php echo '<p>'.$Empresa_Compra.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Proveedor</label>
                        <?php if($model->Proveedor == "") { $Proveedor = "-"; } else { $Proveedor = $model->proveedor->Proveedor; } ?>
                        <?php echo '<p>'.$Proveedor.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>N° de factura</label>
                        <?php if($model->Numero_Factura == "") { $Numero_Factura = "-"; } else { $Numero_Factura = $model->Numero_Factura; } ?>
                        <?php echo '<p>'.$Numero_Factura.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de factura</label>
                        <?php if($model->Fecha_Factura == "") { $Fecha_Factura = "-"; } else { $Fecha_Factura = UtilidadesVarias::textofecha($model->Fecha_Factura); } ?>
                        <?php echo '<p>'.$Fecha_Factura.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Vlr. comercial</label>
                        <?php if($model->Valor_Comercial === NULL) { $Valor_Comercial = "-"; } else { $Valor_Comercial = $model->Valor_Comercial; } ?>
                        <?php echo '<p>'.$Valor_Comercial.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de inicio</label>
                        <?php if($model->Fecha_Inicio == "") { $Fecha_Inicio = "-"; } else { $Fecha_Inicio = UtilidadesVarias::textofecha($model->Fecha_Inicio); } ?>
                        <?php echo '<p>'.$Fecha_Inicio.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de fin.</label>
                        <?php if($model->Fecha_Final == "") { $Fecha_Final = "-"; } else { $Fecha_Final = UtilidadesVarias::textofecha($model->Fecha_Final); } ?>
                        <?php echo '<p>'.$Fecha_Final.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de inicio sop.</label>
                        <?php if($model->Fecha_Inicio_Sop == "") { $Fecha_Inicio_Sop = "-"; } else { $Fecha_Inicio_Sop = UtilidadesVarias::textofecha($model->Fecha_Inicio_Sop); } ?>
                        <?php echo '<p>'.$Fecha_Inicio_Sop.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de fin. sop.</label>
                        <?php if($model->Fecha_Final_Sop == "") { $Fecha_Final_Sop = "-"; } else { $Fecha_Final_Sop = UtilidadesVarias::textofecha($model->Fecha_Final_Sop); } ?>
                        <?php echo '<p>'.$Fecha_Final_Sop.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>N° de Inventario</label>
                        <?php if($model->Numero_Inventario == "") { $Numero_Inventario = "-"; } else { $Numero_Inventario = $model->Numero_Inventario; } ?>
                        <?php echo '<p>'.$Numero_Inventario.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Cuenta de registro</label>
                        <?php if($model->Cuenta_Registro == "") { $Cuenta_Registro = "-"; } else { $Cuenta_Registro = $model->Cuenta_Registro; } ?>
                        <?php echo '<p>'.$Cuenta_Registro.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3" style="word-wrap: break-word;">
                    <div class="form-group">
                        <label>Link</label>
                        <?php if($model->Link == "") { $Link = "-"; } else { $Link = $model->Link; } ?>
                        <?php echo '<p>'.$Link.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Password</label>
                        <?php if($model->Password == "") { $Password = "-"; } else { $Password = $model->Password; } ?>
                        <?php echo '<p>'.$Password.'</p>';?>
                    </div>
                </div>
                 <div class="col-sm-3">
                    <div class="form-group">
                        <label>Usuarios x lic.</label>
                        <?php echo '<p>'.$model->Cant_Usuarios.'</p>'; ?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Ubicación</label>
                        <?php if($model->Ubicacion == "") { $Ubicacion = "-"; } else { $Ubicacion = $model->ubicacion->Dominio; } ?>
                        <?php echo '<p>'.$Ubicacion.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3" style="word-wrap: break-word;">
                    <div class="form-group">
                        <label>Notas</label>
                        <?php if($model->Notas == "") { $Notas = "-"; } else { $Notas = $model->Notas; } ?>
                        <?php echo '<p>'.$Notas.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Usuario que creo</label>
                        <?php echo '<p>'.$model->idusuariocre->Usuario.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de creación</label>
                        <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Usuario que actualizó</label>
                        <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Fecha de actualización</label>
                        <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>';?>
                    </div>
                </div>    
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Estado</label>
                        <?php echo '<p>'.$model->estado->Dominio.'</p>';?>
                    </div>
                </div>  
            </div>
        </div>
        <div id="equ" class="tab-pane fade"><br>
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'licencia-equipo-grid',
                'dataProvider'=>$licencias->search(),
                'pager'=>array(
                    'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
                ),
                'enableSorting' => false,
                'columns'=>array(
                    array(
                        'name'=>'Id_Equipo',
                        'value'=>'UtilidadesVarias::descequipo($data->Id_Equipo)',
                    ),
                    array(
                        'name' => 'Estado',
                        'value' => 'UtilidadesVarias::textoestado1($data->Estado)',
                    ),
                    array(
                        'class'=>'CButtonColumn',
                        'template'=>'{viewequipo}{update}',
                        'buttons'=>array(
                            'viewequipo' => array(
                                'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                                'imageUrl'=>false,                    
                                'url'=>'Yii::app()->createUrl("Equipo/view", array("id"=>$data->Id_Equipo))',
                                'options'=>array('title'=>' Ver detalle de equipo en nueva pestaña', 'target' => '_new'),
                            ),
                            'update'=>array(
                                'label'=>'<i class="fa fa-times actions text-dark"></i>',
                                'imageUrl'=>false,
                                'url'=>'Yii::app()->createUrl("licenciaEquipo/inact", array("id"=>$data->Id_Lic_Equ, "opc"=>1))',
                                'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                                'options'=>array('title'=>' Desvincular equipo', 'confirm'=>'Esta seguro de desvincular el equipo de esta licencia ?'),
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
    var archivo2 =  "<?php echo $model->Doc_Soporte2; ?>";
    var ext = archivo.split('.').pop();

    if($.trim(ext) == "pdf"){
        renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/panel_adm/docs_equipos_licencias/licencias/'.$model->Doc_Soporte; ?>');
    }else{
        $('#img').attr('src', '<?php echo Yii::app()->baseUrl."/files/panel_adm/docs_equipos_licencias/licencias/".$model->Doc_Soporte; ?>');
    }

    if(archivo2 != ""){
        $('#img2').attr('src', '<?php echo Yii::app()->baseUrl."/files/panel_adm/docs_equipos_licencias/licencias/".$model->Doc_Soporte2; ?>');
    }

    loadershow();

    $('#toogle_button').click(function(){

        var archivo =  "<?php echo $model->Doc_Soporte; ?>"; 
        var archivo2 =  "<?php echo $model->Doc_Soporte2; ?>"; 
        var ext = archivo.split('.').pop();

        if($.trim(ext) == "pdf"){
            $('#viewer').toggle('fast');
            $('#info_e').toggle('fast');

            if(archivo2 != ""){
                $('#viewer_img2').toggle('fast');    
            }

        }else{
            $('#viewer_img').toggle('fast');
            $('#info_e').toggle('fast');

            if(archivo2 != ""){
                $('#viewer_img2').toggle('fast');    
            }
        }
        
        return false;

    });

});
   
</script>


<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none; padding-bottom: 2%;">
    </div>
    <div id="viewer_img" class="col-sm-12 text-center" style="display: none; padding-bottom: 2%;">
        <img id="img" class="img-responsive"/>
    </div> 
    <div id="viewer_img2" class="col-sm-12 text-center" style="display: none; padding-bottom: 2%;">
        <img id="img2" class="img-responsive"/>
    </div> 
</div>



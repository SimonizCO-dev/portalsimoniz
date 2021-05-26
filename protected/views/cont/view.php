<?php
/* @var $this ContController */
/* @var $model Cont */

?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Detalle de contrato</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cont/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          Acciones
        </button>
        <?php if ($asociacion == 1) { ?>  
        <ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 48px, 0px);">
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=negcont/create&c='.$model->Id_Contrato; ?>">Asociar negociación</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=anexocont/create&c='.$model->Id_Contrato; ?>">Asociar anexo</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=itemcont/create&c='.$model->Id_Contrato; ?>">Asociar ítem</a></li>
            <li class="dropdown-item small"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=factitemcont/create&c='.$model->Id_Contrato; ?>">Asociar factura</a></li>
       </ul>
       <?php } ?>        
    </div>
</div>

<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#info">Información</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#neg">Negociaciones</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ane">Anexo(s)</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#ite">Item(s</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#pag">Factura(s)</a></li>
</ul>
<!-- Tab panes -->
 <div class="tab-content">
    <div id="info" class="tab-pane active"><br>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>ID</label>
                    <?php echo '<p>'.$model->Id_Contrato.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Tipo</label>
                    <?php echo '<p>'.$model->DescTipo($model->Tipo).'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Empresa</label>
                    <?php echo '<p>'.$model->empresa->Descripcion.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Razón social</label>
                    <?php echo '<p>'.$model->Razon_Social.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Concepto</label>
                    <?php echo '<p>'. $model->Concepto_Contrato.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Contacto</label>
                    <?php if($model->Contacto == "") { $Contacto = "-"; } else { $Contacto = $model->Contacto; } ?>
                    <?php echo '<p>'.$Contacto.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Teléfono contacto</label>
                    <?php if($model->Telefono_Contacto == "") { $Telefono_Contacto = "-"; } else { $Telefono_Contacto = $model->Telefono_Contacto; } ?>
                    <?php echo '<p>'.$Telefono_Contacto.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>E-mail contacto</label>
                    <?php if($model->Email_Contacto == "") { $Email_Contacto = "-"; } else { $Email_Contacto = $model->Email_Contacto; } ?>
                    <?php echo '<p>'.$Email_Contacto.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Periodicidad de pago</label>
                    <?php echo '<p>'. $model->periodicidad->Dominio.'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Fecha de inicio</label>
                    <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Inicial).'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Fecha de fin.</label>
                    <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Final).'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Fecha de ren. / canc.</label>
                    <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Ren_Can).'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Valor</label>
                    <?php echo '<p>'.$model->VlrCont($model->Id_Contrato).'</p>';?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Área</label>
                    <?php echo '<p>'.$model->Area.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3" style="word-wrap: break-word;">
                <div class="form-group">
                    <label>Observaciones</label>
                    <?php echo '<p>'.$model->Observaciones.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Días de alerta (antelación)</label>
                    <?php echo '<p>'.$model->Dias_Alerta.'</p>';?>
                </div>
            </div>
            <div class="col-sm-3">
            
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
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Estado</label>
                    <?php echo '<p>'.UtilidadesVarias::textoestado1($model->Estado).'</p>';?>
                </div>
            </div>  
        </div>    
    </div>
    <div id="neg" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'neg-cont-grid',
            'dataProvider'=>$neg->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                'Id_Neg',
                'Item',
                array(
                    'name'=>'Costo',
                    'value'=>function($data){
                        return number_format($data->Costo, 2);
                    },
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name' => 'Moneda',
                    'value' => '$data->moneda->Dominio',
                ),
                array(
                    'name'=>'Porc_Desc',
                    'value'=>function($data){
                        return number_format($data->Porc_Desc, 2);
                    },
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name'=>'costo_final',
                    'value' => '$data->CostoFinal($data->Id_Neg)',
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name'=>'Estado',
                    'value'=>'UtilidadesVarias::textoestado1($data->Estado)',
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array(
                        'update'=>array(
                            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Actualizar'),
                            'url'=>'Yii::app()->createUrl("negcont/update", array("id"=>$data->Id_Neg))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                        ),
                    )
                ),
            ),
        )); ?>   
    </div>
    <div id="ane" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'anexo-cont-grid',
            'dataProvider'=>$anexos->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                'Id_Anexo',
                'Titulo',
                'Descripcion',
                array(
                    'name'=>'Estado',
                    'value'=>'UtilidadesVarias::textoestado1($data->Estado)',
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array(
                        'update'=>array(
                            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Actualizar'),
                            'url'=>'Yii::app()->createUrl("anexocont/update", array("id"=>$data->Id_Anexo))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                        ),
                    )
                ),
            ),
        )); ?>   
    </div>
    <div id="ite" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'item-cont-grid',
            'dataProvider'=>$items->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                'Id_Item',
                'Id',
                'Item',
                'Descripcion',
                array(
                    'name'=>'Cant',
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name'=>'Vlr_Unit',
                    'value'=>function($data){
                        return number_format($data->Vlr_Unit, 2);
                    },
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name' => 'Moneda',
                    'value' => '$data->moneda->Dominio',
                ),
                array(
                    'name'=>'Iva',
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name'=>'vlr_total',
                    'value'=>function($data){
                        return number_format($data->VlrTotalItem($data->Id_Item), 2);
                    },
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                
                array(
                    'name'=>'Estado',
                    'value'=>'UtilidadesVarias::textoestado1($data->Estado)',
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{update}',
                    'buttons'=>array(
                        'update'=>array(
                            'label'=>'<i class="fa fa-pen actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Actualizar'),
                            'url'=>'Yii::app()->createUrl("itemcont/update", array("id"=>$data->Id_Item))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true)',
                        ),
                    )
                ),
            ),
        )); ?>    
    </div>
    <div id="pag" class="tab-pane fade"><br>
        <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'fact-item-cont-grid',
            'dataProvider'=>$facturas->search(),
            'pager'=>array(
                'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
            ),
            'enableSorting' => false,
            'columns'=>array(
                'Id_Fac',
                'Numero_Factura',
                array(
                    'name'=>'Fecha_Factura',
                    'value'=>'UtilidadesVarias::textofecha($data->Fecha_Factura)',
                ),
                array(
                    'name' => 'vlr_total',
                    'value' => '$data->TotalItems($data->Id_Fac)',
                    'htmlOptions'=>array('style' => 'text-align: right;'),
                ),
                array(
                    'name' => 'Estado',
                    'value' => '$data->DescEstado($data->Estado)',
                ),
                array(
                    'class'=>'CButtonColumn',
                    'template'=>'{view}{anul}',
                    'buttons'=>array(
                        'view'=>array(
                            'label'=>'<i class="fa fa-eye actions text-dark"></i>',
                            'imageUrl'=>false,
                            'options'=>array('title'=>'Visualizar'),
                            'url'=>'Yii::app()->createUrl("factitemcont/view", array("id"=>$data->Id_Fac))',
                        ),
                        'anul'=>array(
                            'label'=>'<i class="fa fa-ban actions text-dark"></i>',
                            'imageUrl'=>false,
                            'url'=>'Yii::app()->createUrl("factitemcont/Anul", array("id"=>$data->Id_Fac))',
                            'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
                            'options'=>array('title'=>'Anular', 'confirm'=>'Esta seguro de anular esta factura ?'),
                        ),
                    )
                ),
            ),
        )); ?>    
    </div>
</div>
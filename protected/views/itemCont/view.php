<?php
/* @var $this ItemContController */
/* @var $model ItemCont */

?>

<h3>Detalle item de contrato</h3>

<div class="btn-group">
    <button type="button" class="btn btn-success" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=Cont/view&id='.$model->Id_Contrato; ?>';"><i class="fa fa-reply"></i> Volver </button>
</div>

<div class="nav-tabs-custom" style="margin-top: 2%;">
    <ul class="nav nav-tabs" style="font-size: 12px !important;">
      <li class="active"><a href="#info" data-toggle="tab">Información</a></li>
      <li><a href="#his" data-toggle="tab">Historial</a></li>
    </ul>
    <div class="tab-content">
        <div class="active tab-pane" id="info">
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <label>ID contrato</label>
                        <?php echo '<p>'.$model->DescContrato($model->Id_Contrato).'</p>';?>
                    </div>
                </div>
            </div>
        	<div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>ID</label>
                        <?php echo '<p>'.$model->Id_Item.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>ID de item</label>
                        <?php echo '<p>'.$model->Id.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Item</label>
                        <?php echo '<p>'.$model->Item.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-9" style="word-wrap: break-word;">
                    <div class="form-group">
                        <label>Descripción</label>
                        <?php echo '<p>'. $model->Descripcion.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Cant.</label>
                        <?php echo '<p>'.$model->Cant.'</p>';?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Vlr. unit.</label>
                        <?php echo '<p>'.number_format($model->Vlr_Unit, 0).'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Moneda</label>
                        <?php echo '<p>'.$model->moneda->Dominio.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Iva</label>
                        <?php echo '<p>'.$model->Iva.'</p>';?>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Vlr. total</label>
                        <?php echo '<p>'.number_format($model->VlrTotalItem($model->Id_Item), 0).'</p>';?>
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
        </div>
        <div class="tab-pane" id="his">
        	<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'hist-item-cont-grid',
				'dataProvider'=>$historial->search(),
                //'filter'=>$model,
                'enableSorting' => false,
				'columns'=>array(
					'Novedad',
					array(
			            'name'=>'Id_Usuario_Creacion',
			            'value'=>'$data->idusuariocre->Usuario',
			        ),
			        array(
			            'name'=>'Fecha_Creacion',
			            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
			        ),
				),
			)); ?>
        </div>
    </div>
    <!-- /.tab-content -->
</div>
<!-- /.nav-tabs-custom -->

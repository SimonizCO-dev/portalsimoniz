<?php
/* @var $this IDoctoController */
/* @var $model IDocto */

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Visualizando documento</h4>
  </div>
  <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=idocto/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
  </div>
</div>

<div class="row">
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Tipo</label>
          	<p><?php echo $model->idtipodocto->Descripcion; ?></p>            	
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Consecutivo</label>
          	<p><?php echo $model->Consecutivo; ?></p>			   
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Fecha</label>
          	<p><?php echo UtilidadesVarias::textofecha($model->Fecha); ?></p>				    
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Referencia</label>
          	<p><?php echo $model->Referencia; ?></p>            	
        </div>
    </div>
    <div class="col-sm-8">
    	<div class="form-group">
          	<label>Tercero</label>
          	<p><?php echo $model->DescTercero($model->Id_Tercero); ?></p> 			   
        </div>
    </div>
</div>

<?php if($model->Id_Tipo_Docto == Yii::app()->params->sad){ ?>

<div class="row">
    <div class="col-sm-8">
    	<div class="form-group">
          	<label>Empleado</label>
          	<p><?php echo $model->DescEmpleado($model->Id_Emp); ?></p> 			   
        </div>
    </div>
</div>

<?php } ?>

<?php if($model->Id_Tipo_Docto == Yii::app()->params->aje || $model->Id_Tipo_Docto == Yii::app()->params->ajs){ ?>

<div class="row">
    <div class="col-sm-12">
    	<div class="form-group">
          	<label>Notas</label>
          	<p><?php echo $model->Notas; ?></p> 			   
        </div>
    </div>
</div>

<?php } ?>

<div class="row">
	<div class="col-sm-4">
    	<div class="form-group">
          	<label>Vlr. total</label>
          	<p><?php echo number_format($model->Vlr_Total, 2); ?></p> 				    
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Estado</label>
          	<p><?php echo $model->idestado->Descripcion; ?></p> 	           	
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Usuario que creo</label>	
          	<p><?php echo $model->idusuariocre->Usuario; ?></p> 			   
        </div>
    </div> 
</div>
<div class="row">
	<div class="col-sm-4">
    	<div class="form-group">
          	<label>Fecha de creación</label>
          	<p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p> 				    
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Ultimo usuario que actualizó</label>
          	<p><?php echo $model->idusuarioact->Usuario; ?></p> 	            	
        </div>
    </div>
    <div class="col-sm-4">
    	<div class="form-group">
          	<label>Ultima fecha de actualización</label>
          	<p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p> 				   
        </div>
    </div>
    <div class="col-sm-4">
    	
    </div>
</div>

<h5>Detalle</h5>

<?php 

//detalle

if($model->Id_Tipo_Docto == Yii::app()->params->ent){
	//entrada
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Dst',
	            'type' => 'raw',
	            'value' => '$data->idbodegadst->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

if($model->Id_Tipo_Docto == Yii::app()->params->sal){
	//salida
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Org',
	            'type' => 'raw',
	            'value' => '$data->idbodegaorg->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

if($model->Id_Tipo_Docto == Yii::app()->params->trb){
	//transferencia
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Org',
	            'type' => 'raw',
	            'value' => '$data->idbodegaorg->Descripcion',
	        ),array(
	            'name' => 'Id_Bodega_Dst',
	            'type' => 'raw',
	            'value' => '$data->idbodegadst->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

if($model->Id_Tipo_Docto == Yii::app()->params->aje){
	//ajuste por entrada
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Dst',
	            'type' => 'raw',
	            'value' => '$data->idbodegadst->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

if($model->Id_Tipo_Docto == Yii::app()->params->ajs){
	//ajuste por salida
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Org',
	            'type' => 'raw',
	            'value' => '$data->idbodegaorg->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

if($model->Id_Tipo_Docto == Yii::app()->params->sad){
	//salida de dotación
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Org',
	            'type' => 'raw',
	            'value' => '$data->idbodegaorg->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

if($model->Id_Tipo_Docto == Yii::app()->params->dev){
	//devolución
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'idocto-movto-grid',
		'dataProvider'=>$detalle->search(),
	    'pager'=>array(
	        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
	    ),
	    'enableSorting' => false,
		'columns'=>array(
			array(
	            'name' => 'Id_Item',
	            'type' => 'raw',
	            'value' => '$data->DescItem($data->Id_Item)',
	        ),
			array(
	            'name' => 'Id_Bodega_Dst',
	            'type' => 'raw',
	            'value' => '$data->idbodegadst->Descripcion',
	        ),
			array(
                'name'=>'Cantidad',
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
                'name'=>'Vlr_Unit_Item',
                'value'=>function($data){
                    return number_format($data->Vlr_Unit_Item, 2);
                },
                'htmlOptions'=>array('style' => 'text-align: right;'),
            ),
			array(
            	'name'=>'Id_Usuario_Actualizacion',
            	'value'=>'$data->idusuarioact->Usuario',
	        ),
			array(
	            'name'=>'Fecha_Actualizacion',
	            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Actualizacion)',
	        ),
		),
	));
}

?>


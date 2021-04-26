<?php
/* @var $this PedComController */
/* @var $model PedCom */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ped-com-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div class="row">
    <div class="col-sm-2">
    	<div class="form-group">
          	<?php echo $form->label($model,'Id_Ped_Com'); ?>
          	<?php echo $form->hiddenField($model,'Estado', array('class' => 'form-control form-control-sm')); ?>
          	<p><?php echo $model->Id_Ped_Com; ?></p>            	
        </div>
    </div>
    <div class="col-sm-2">
    	<div class="form-group">
          	<?php echo $form->label($model,'Fecha'); ?>
          	<p><?php if($model->Fecha === NULL){  echo "-";  }else{ echo UtilidadesVarias::textofecha($model->Fecha); } ?></p>              	
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario'); ?>
         	<p><?php echo Yii::app()->user->getState('name_user'); ?></p>
        </div>
    </div>
    <div class="col-sm-6">
    	<div class="form-group">
	      <?php echo $form->label($model,'Cliente'); ?>
	      <p><?php echo $model->DescCliente($model->Cliente); ?></p>
    	</div>
  	</div>
</div>
<div class="row">
  	<div class="col-sm-4">
	    <div class="form-group">
			<?php echo $form->label($model,'Sucursal', array('class' => 'control-label')); ?>
			<p><?php echo $model->DescSucursal($model->Cliente, $model->Sucursal); ?></p>
	    </div>
	</div>
	<div class="col-sm-4">
	    <div class="form-group">
			<?php echo $form->label($model,'Punto_Envio', array('class' => 'control-label')); ?>
			<p><?php echo $model->DescPuntoEnvio($model->Cliente, $model->Sucursal, $model->Punto_Envio); ?></p>
	    </div>
	</div>
	<div class="col-sm-4">
	    <div class="form-group">
			<?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
			<p><?php echo $model->DescEstado($model->Estado); ?></p>
	    </div>
	</div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuarioact->Nombres; ?></p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-sm-12">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=pedcom/control'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <?php if($model->Estado == 2){ ?>
        	<button type="button" class="btn btn-primary btn-sm" id="rechazo_form"><i class="fas fa-times-circle"></i> Rechazar</button>
        	<button type="button" class="btn btn-primary btn-sm" id="carga_form"><i class="fas fa-check-circle"></i> Marcar como cargado</button>
        <?php } ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<h5>Detalle</h5>

<?php 

//detalle

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'det-ped-com-grid',
	'dataProvider'=>$detalle->search(),
    'pager'=>array(
        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
    ),
    'enableSorting' => false,
	'columns'=>array(
		array(
	        'name' => 'Item',
	       	'value' => '$data->DescItem($data->Item)',
	    ),
		array(
            'name'=>'Und_Emp',
            'value' => '($data->Und_Emp == "") ? "-" : $data->Und_Emp',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
		array(
            'name'=>'Un_Sol',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
	),
));

?>


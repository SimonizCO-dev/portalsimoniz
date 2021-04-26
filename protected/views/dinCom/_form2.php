<?php
/* @var $this DinComController */
/* @var $model DinCom */
/* @var $form CActiveForm */

$array_cri_cliente = array(3, 9, 12, 16, 17);

$array_cri_item = array(4, 12, 13, 14, 15);

$r = new Reporte;

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'din-com-form',
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
	<div class="col-sm-4">
		<div class="form-group">
		  <?php echo $form->label($model,'Pais'); ?>
		  <p><?php echo UtilidadesVarias::descpaises($model->Pais); ?></p>
		</div>
  	</div>
  	<div class="col-sm-4">
		<div class="form-group">
		  <?php echo $form->label($model,'Tipo'); ?>
	  	  <p><?php echo $model->desctipo($model->Tipo); ?></p>
		</div>
  	</div>
	<div class="col-sm-2">
	  	<div class="form-group">
	  		<?php echo $form->label($model,'Fecha_Inicio'); ?>
	  		<p><?php if($model->Fecha_Inicio){ echo UtilidadesVarias::textofecha($model->Fecha_Inicio); }else{ echo '-'; } ?></p>
	  	</div>
	</div>
	<div class="col-sm-2">
	  	<div class="form-group">
	  		<?php echo $form->label($model,'Fecha_Fin'); ?>
	  		<p><?php if($model->Fecha_Fin){ echo UtilidadesVarias::textofecha($model->Fecha_Fin); }else{ echo '-'; } ?></p>
	  	</div>
	</div>
</div>
<div class="row">		
	<div class="col-sm-6" id="div_item" style="display: none;">
      	<div class="form-group">
      		<?php echo $form->label($model,'Item'); ?>
      		<p>
      		<?php 
      		if($model->Item){ 
      			echo $r->DescItem($model->Item); 
      		}else{ 
      			echo '-'; 
      		} 
      		?>
      		</p>
      	</div>
  	</div>		
	<div class="col-sm-6" id="div_cliente" style="display: none;">
      	<div class="form-group">
          <?php echo $form->label($model,'Cliente'); ?>
          <p>
          <?php 
          if($model->Cliente){ 
            echo $r->DescCliente($model->Cliente); 
          }else{ 
            echo '-'; 
          } 
          ?>
          </p>
      	</div>
  	</div>		
	<div class="col-sm-6" id="div_l_precios" style="display: none;">
      	<div class="form-group">
			<?php echo $form->label($model,'Lista_Precios'); ?>
			<p><?php if($model->Lista_Precios){ echo $model->desclistaprecios($model->Lista_Precios); }else{ echo '-';  } ?></p>
      	</div>
  	</div>
  	<div class="col-sm-4" id="div_co" style="display: none;">
      	<div class="form-group">
			<?php echo $form->label($model,'CO'); ?>
			<p><?php if($model->CO){ echo $model->descco($model->CO); }else{ echo '-'; } ?></p>
      	</div>
  	</div>
	<div class="col-sm-3" id="div_cant_min" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Min'); ?>
            <p><?php if($model->Cant_Min){ echo $model->Cant_Min; }else{ echo '-'; } ?></p>
        </div>
    </div>
    <div class="col-sm-3" id="div_cant_max" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Max'); ?>
            <p><?php if($model->Cant_Max){ echo $model->Cant_Max; }else{ echo '-'; } ?></p>
        </div>
    </div>
    <div class="col-sm-3" id="div_cant_ped" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Ped'); ?>
            <p><?php if($model->Cant_Ped){ echo $model->Cant_Ped; }else{ echo '-'; } ?></p>
        </div>
    </div>
    <div class="col-sm-3" id="div_cant_obs" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Obs'); ?>
            <p><?php if($model->Cant_Obs){ echo $model->Cant_Obs; }else{ echo '-'; } ?></p>
        </div>
    </div>
	<div class="col-sm-3" id="div_vlr_min" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Vlr_Min'); ?>
            <p><?php if($model->Vlr_Min){ echo number_format($model->Vlr_Min, 2); }else{ echo '-'; } ?></p>
        </div>
    </div>
    <div class="col-sm-3" id="div_vlr_max" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Vlr_Max'); ?>
            <p><?php if($model->Vlr_Max){ echo number_format($model->Vlr_Max, 2); }else{ echo '-'; } ?></p>
        </div>
    </div>
	<div class="col-sm-3" id="div_desc" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Descuento'); ?>
            <p><?php if($model->Descuento){ echo number_format($model->Descuento, 2); }else{ echo '-'; } ?></p>
        </div>
    </div>
</div>


<?php if(in_array($model->Tipo, $array_cri_cliente)) { ?>

<div id="contenido_criterios_cliente" class="mb-2 mt-2">

<h5>Criterios cliente</h5>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'din-com-cliente',
	'dataProvider'=>$criterio_cliente->search(),
  'pager'=>array(
      'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
  ),
  'enableSorting' => false,
	'columns'=>array(
		array(
            'name' => 'Id_Plan',
            'value' => '$data->DescPlan($data->Id_Plan)',
        ),
		array(
            'name' => 'Id_Criterio',
            'value' => '$data->DescCriterio($data->Id_Plan, $data->Id_Criterio)',
        ),
	),
));

?>
	
</div>

<?php } ?>

<?php if(in_array($model->Tipo, $array_cri_item)) { ?>

<div id="contenido_criterios_item" class="mb-2 mt-2">

<h5>Criterios item</h5>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'din-com-item',
	'dataProvider'=>$criterio_item->search(),
  'pager'=>array(
      'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
  ),
  'enableSorting' => false,
	'columns'=>array(
		array(
            'name' => 'Id_Plan',
            'value' => '$data->DescPlan($data->Id_Plan)',
        ),
		array(
            'name' => 'Id_Criterio',
            'value' => '$data->DescCriterio($data->Id_Plan, $data->Id_Criterio)',
        ),
	),
));

?>

</div>

<?php } ?>


<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Estado'); ?>
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php $estados = Yii::app()->params->estados; ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'DinCom[Estado]',
                    'id'=>'DinCom_Estado',
                    'data'=>$estados,
                    'value' => $model->Estado,
                    'htmlOptions'=>array(),
                    'options'=>array(
                        'placeholder'=>'Seleccione...',
                        'width'=> '100%',
                        'allowClear'=>true,
                    ),
                ));
            ?>
        </div>
    </div> 
</div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=dincom/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>  
    </div>
</div>

<?php $this->endWidget(); ?>

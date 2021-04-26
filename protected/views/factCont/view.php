<?php
/* @var $this FactContController */
/* @var $model FactCont */
/* @var $form CActiveForm */
?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Visualizando factura</h4>
  </div>
  <div class="col-sm-6 text-right"> 
	<?php if($opc == 1){ ?>
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=factCont/admin'; ?>';"><i class="fa fa-reply"></i> Volver </button>
    <?php } if($opc == 2){ ?>
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=factCont/admin2'; ?>';"><i class="fa fa-reply"></i> Volver </button>
    <?php } if($opc == 3){ ?>
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=factCont/admin3'; ?>';"><i class="fa fa-reply"></i> Volver </button>
    <?php } if ($model->Doc_Soporte != "") {  ?>
	<button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar doc.</button>
<?php } ?>
  </div>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fact-pend-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<div id="info">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Empresa'); ?>
				<?php echo '<p>'.$model->DescEmpresa($model->Empresa).'</p>';?>        
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">  
				<?php echo $form->label($model,'Area'); ?>
				<?php echo '<p>'.$model->area->Area.'</p>';?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<?php echo $form->label($model,'Num_Factura'); ?>
				<?php echo '<p>'.$model->Num_Factura.'</p>';?> 
			</div>
		</div> 
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Factura'); ?>
				<?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Factura).'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Radicado'); ?>
				<?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Radicado).'</p>';?> 
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Proveedor'); ?>
				<?php echo '<p>'.$model->DescProveedor($model->Proveedor).'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Valor'); ?>
				<?php echo '<p>'.number_format($model->Valor, 2).'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Moneda'); ?>
				<?php echo '<p>'.$model->DescMoneda($model->Moneda).'</p>';?> 
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->label($model,'Observaciones'); ?>
				<?php if($model->Observaciones == ""){ $Observaciones = "-"; }else{ $Observaciones = $model->Observaciones; } ?> 
				<?php echo '<p>'.$Observaciones.'</p>';?> 
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<?php echo $form->label($model,'Estado'); ?>
				<?php echo '<p>'.$model->DescEstado($model->Estado).'</p>';?> 
				<?php echo $form->hiddenField($model,'Estado', array('class' => 'form-control form-control-sm')); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
				<?php echo '<p>'.$model->idusuariocre->Usuario.'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Creacion'); ?>
				<?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?>
				<?php echo '<p>'.$model->idusuarioact->Usuario.'</p>';?> 
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Actualizacion'); ?>
				<?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Id_Usuario_Revision'); ?>
				<?php if($model->Id_Usuario_Revision == ""){ $Usuario_Revision = "-"; }else{ $Usuario_Revision = $model->idusuariorev->Usuario; } ?> 
				<?php echo '<p>'.$Usuario_Revision.'</p>';?> 
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->label($model,'Fecha_Revision'); ?>
				<?php if($model->Fecha_Revision == ""){ $Fecha_Revision = "-"; }else{ $Fecha_Revision = UtilidadesVarias::textofechahora($model->Fecha_Revision); } ?> 
				<?php echo '<p>'.$Fecha_Revision.'</p>';?> 
			</div>
		</div>
	</div>
</div>

<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none;">
    </div>  
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script type="text/javascript">

$(function() {

	var sop = '<?php echo $model->Doc_Soporte; ?>';

	if(sop != ""){
		renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/fact_cont/'.$model->Doc_Soporte; ?>');

	    $('#toogle_button').click(function(){
	        $('#info').slideToggle('fast');
	        $('#viewer').slideToggle('fast');
	        return false;
	    });	
	}

    

});

</script>
<?php
/* @var $this CuentaController */
/* @var $model Cuenta */

$user = Yii::app()->user->getState('id_user');

?>



<div class="row mb-2">
    <div class="col-sm-4">
        <h4>Detalle emisión de producto</h4>
    </div>
    <div class="col-sm-8 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprod/consulta'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar emisión</button>
        <button type="button" class="btn btn-primary btn-sm" id="download"><i class="fas fa-file-pdf"></i> Descargue e imprima aquí la emisión</button>
        <?php if($val == 1){ ?>  
        <button type="button" class="btn btn-primary btn-sm" id="view_emis"><i class="fas fa-users"></i> Marcar como visto</button>
        <?php } ?>
        <div style="display: none;">
            <a href="<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/emision_prod/'.$model->Documento; ?>" download="<?php echo $model->Documento; ?>" style="display: none;" id="link"></a>
        </div>   
    </div>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'em-prod-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    ),
)); ?>

<div id="info">
  <div class="row">
    <div class="col-sm-3">
        <div class="form-group">
          <?php echo $form->label($model,'Codigo', array('class' => 'control-label')); ?>
          <p><?php echo $model->Codigo; ?></p>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="form-group">
            <?php echo $form->label($model,'Notas'); ?>
            <p><?php echo $model->Notas; ?></p>
        </div>
    </div>
  </div>
  <div class="row">
      <div class="col-sm-3">
          <div class="form-group">
              <?php echo $form->label($model,'Id_Usuario_Creacion', array('class' => 'control-label')); ?>
              <p><?php echo $model->idusuariocre->Nombres; ?></p>
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
              <p><?php echo $model->idusuarioact->Nombres; ?></p>
          </div>
      </div>
      <div class="col-sm-3">
          <div class="form-group">
              <?php echo $form->label($model,'Fecha_Actualizacion', array('class' => 'control-label')); ?>
              <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>
          </div>
      </div>
  </div>
</div>

<?php $this->endWidget(); ?>

<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none; padding-bottom: 2%;">
    </div>
</div>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script type="text/javascript">


$(function() {
    
    renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/portal_reportes/emision_prod/'.$model->Documento; ?>');

    $('#toogle_button').click(function(){
   
      $('#viewer').toggle('fast');
      $('#info').toggle('fast');

      return false;

    });

    $("#download").click(function() {
        $('#link')[0].click();
    });

    $("#view_emis").click(function() {

        var opcion = confirm("Se marcara la emisión como vista, esta seguro ? ");
        if (opcion == true) {
            showloader();
            location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=emprod/viewdoc2&id='.$model->Id_Em_Prod.'&u='.$user; ?>';   
        } 
        
    });

});

    
</script>
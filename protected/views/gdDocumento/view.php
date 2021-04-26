<?php
/* @var $this DocumentoController */
/* @var $model Documento */

?>

<div class="row mb-2">
  <div class="col-sm-6">
    <h4>Visualizando documento</h4>
  </div>
  <div class="col-sm-6 text-right">  
    <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=gddocumento/admin'; ?>';"><i class="fa fa-reply"></i> Volver </button>
    <button type="button" class="btn btn-primary btn-sm" id="toogle_button"><i class="fa fa-low-vision"></i> Ver / ocultar doc.</button>  
  </div>
</div>

<div id="info">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'gd-documento-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
    )); ?>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Id_Documento', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Id_Documento'); ?>
                <?php echo '<p>'.$model->Id_Documento.'</p>'; ?>
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group" style="word-wrap: break-word;">
                <?php echo $form->error($model,'areas', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'areas'); ?>
                <?php echo '<p>'.$model->Desc_Areas($model->Id_Documento).'</p>'; ?>
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Clasificacion', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Clasificacion'); ?>
                <?php echo '<p>'.UtilidadesVarias::descclasif($model->Clasificacion).'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Tipo'); ?>
                <?php echo '<p>'.$model->tipo->Descripcion.'</p>'; ?>
            </div>
        </div>
    </div>
    <div class="row"> 
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Num_Documento', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Num_Documento'); ?>
                <?php echo '<p>'.$model->Num_Documento.'</p>'; ?>
            </div>
        </div> 
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Titulo', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Titulo'); ?>
                <?php echo '<p>'.$model->Titulo.'</p>'; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div class="form-group" style="word-wrap: break-word;">
                <?php echo $form->error($model,'Descripcion', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Descripcion'); ?>
                <?php echo '<p>'.$model->Descripcion.'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Elaborado_Por', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Elaborado_Por'); ?>
                <?php echo '<p>'.$model->Elaborado_Por.'</p>'; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Fecha_Elaboracion', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Fecha_Elaboracion'); ?>
                <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Elaboracion).'</p>'; ?>
            </div>
        </div> 
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Nivel_Revision', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Nivel_Revision'); ?>
                <?php echo '<p>'.$model->Nivel_Revision.'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Fecha_Revision', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Fecha_Revision'); ?>
                <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Revision).'</p>'; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
            <?php echo $form->error($model,'Emitido_Por', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Emitido_Por'); ?>
            <?php echo '<p>'.$model->Emitido_Por.'</p>'; ?>
            </div>
        </div> 
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Fecha_Emision', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Fecha_Emision'); ?>
                <?php echo '<p>'.UtilidadesVarias::textofecha($model->Fecha_Emision).'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Aprobado_Por', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Aprobado_Por'); ?>
                <?php echo '<p>'.$model->aprobadopor->Unidad_Gerencia.'</p>'; ?>
            </div>
        </div> 
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Permite_Descarga', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Permite_Descarga'); ?>
                <?php echo '<p>'.UtilidadesVarias::textoestado2($model->Permite_Descarga).'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Copia_Controlada', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Copia_Controlada'); ?>
                <?php echo '<p>'.UtilidadesVarias::textoestado2($model->Copia_Controlada).'</p>'; ?>
            </div>
        </div>
      <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Usuario_Creacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Usuario_Creacion'); ?>
            <?php echo '<p>'.$model->idusuariocre->Usuario.'</p>'; ?>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Fecha_Creacion', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Fecha_Creacion'); ?>
                <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Creacion).'</p>'; ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo $form->error($model,'Id_Usuario_Actualizacion', array('class' => 'badge badge-warning float-right')); ?>
                <?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?>
                <?php echo '<p>'.$model->idusuarioact->Usuario.'</p>'; ?>
            </div>
        </div>
      <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Fecha_Actualizacion', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Fecha_Actualizacion'); ?>
            <?php echo '<p>'.UtilidadesVarias::textofechahora($model->Fecha_Actualizacion).'</p>'; ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'Estado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Estado'); ?>
            <?php echo '<p>'.UtilidadesVarias::textoestado1($model->Estado).'</p>'; ?>
        </div>
      </div>
    </div>

<?php $this->endWidget(); ?>

</div>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/pdf.js/pdf.js"></script>
<script type="text/javascript">

$(function() {

    renderPdfByUrl('<?php echo Yii::app()->getBaseUrl(true).'/files/gestion_documental/consulta/'.$model->Url_Consulta; ?>');

    $('#toogle_button').click(function(){
        $('#info').slideToggle('fast');
        $('#viewer').slideToggle('fast');
        return false;
    });

});
   
</script> 

<div class="row">
    <div id="viewer" class="col-sm-12 text-center" style="display: none;"></div>   
</div>
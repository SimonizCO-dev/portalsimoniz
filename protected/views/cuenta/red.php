<?php
/* @var $this CuentaController */
/* @var $model Cuenta */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cuenta-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Redirección de cuenta</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cuenta/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="view_p"><i class="fas fa-key"></i> Ver password actual</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Cuenta_Usuario'); ?><br>
        	<?php echo $model->DescCuentaUsuario($model->Id_Cuenta) ?>
        </div>
    </div>
    <div class="col-sm-3  ">
        <div class="form-group">
            <?php echo $form->error($model,'Password', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Password'); ?>
            <input type="text" name="Cuenta[Password]" id="Cuenta_Password" class="form-control form-control-sm" autocomplete="off">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
        	<?php echo $form->error($model,'Id_Cuenta_Red', array('class' => 'badge badge-warning float-right')); ?>
    		  <?php echo $form->label($model,'Id_Cuenta_Red'); ?>
          <?php echo $form->textField($model,'Id_Cuenta_Red'); ?>
          <?php
              $this->widget('ext.select2.ESelect2', array(
                  'selector' => '#Cuenta_Id_Cuenta_Red',
                  'options'  => array(
                      'allowClear' => true,
                      'minimumInputLength' => 3,
                      'width' => '100%',
                      'language' => 'es',
                      'ajax' => array(
                          'url' => Yii::app()->createUrl('cuenta/SearchCuentaRed'),
                          'dataType'=>'json',
                          'data'=>'js:function(term){return{q: term, id: '.$model->Id_Cuenta.'};}',
                          'results'=>'js:function(data){ return {results:data};}'                   
                      ),
                      'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Cuenta_Id_Cuenta_Red"); return "No se encontraron resultados"; }',
                      'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Cuenta_Id_Cuenta_Red\')\">Limpiar campo</button>"; }',
                  ),
              ));
          ?>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <p align="center"><?php echo $pass; ?></p>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php $this->endWidget(); ?>

<script type="text/javascript">

$(function() {

  $("#valida_form").click(function() {

      var form = $("#cuenta-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;

      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              //se envia el form
              $(".ajax-loader").fadeIn('fast');
              form.submit();
          } else {

              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });

              settings.submitting = false ;
          }
      });
  });

  $("#view_p").click(function() {
    $('#myModal').modal({show:true});
  });

});

</script>
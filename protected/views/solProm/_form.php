<?php
/* @var $this SolPromController */
/* @var $model SolProm */
/* @var $form CActiveForm */

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sol-prom-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

<div id="accordion">
  <div class="card card-secondary" id="link_collapse_1">
      <div class="card-header" id="info_1">
        <h5 class="mb-0">
        <a class="btn-link" data-toggle="collapse" href="#collapse_1" role="button" aria-expanded="false" aria-controls="collapse_1"><i id="img_info_1" class="fas fa-clock"></i>
        Registro
        </a>
        </h5>
      </div>
      <div id="collapse_1" class="collapse" aria-labelledby="info_1" data-parent="#accordion">
        <div class="card-body">
            <?php if($model->isNewRecord){ ?>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <?php echo $form->error($model,'Num_Sol', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Num_Sol'); ?>
                            <p><?php echo $model->Num_Sol; ?></p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->error($model,'Responsable', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Responsable'); ?>
                            <?php echo $form->textField($model,'Responsable', array('class' => 'form-control form-control-sm', 'maxlength' => '200', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Tipo'); ?>
                            <?php
                              $this->widget('ext.select2.ESelect2',array(
                                  'name'=>'SolProm[Tipo]',
                                  'id'=>'SolProm_Tipo',
                                  'data'=> $lista_tipos,
                                  'value' => $model->Tipo,
                                  'htmlOptions'=>array(
                                      'disabled'=>true,
                                  ),
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
                <div class="row" id="info_cliente_item" style="display: none;">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <?php echo $form->error($model,'Cliente', array('class' => 'badge badge-warning float-right')); ?>
                          <?php echo $form->label($model,'Cliente'); ?>
                          <?php echo $form->textField($model,'Cliente'); ?>
                          <?php
                          $this->widget('ext.select2.ESelect2', array(
                              'selector' => '#SolProm_Cliente',

                              'options'  => array(
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                    'width' => '100%',
                                    'language' => 'es',
                                    'ajax' => array(
                                          'url' => Yii::app()->createUrl('SolProm/SearchCliente'),
                                      'dataType'=>'json',
                                        'data'=>'js:function(term){return{q: term, estructura: $("#SolProm_Tipo").val()};}',
                                        'results'=>'js:function(data){ return {results:data};}'
                                                 
                                  ),
                                  'formatNoMatches'=> 'js:function(){ clear_select2_ajax("SolProm_Cliente"); return "No se encontraron resultados"; }',
                                  'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'SolProm_Cliente\')\">Limpiar campo</button>"; }',
                              ),
                            ));
                          ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <?php echo $form->error($model,'Kit', array('class' => 'badge badge-warning float-right')); ?>
                          <?php echo $form->label($model,'Kit'); ?>
                          <?php echo $form->textField($model,'Kit'); ?>
                          <?php
                          $this->widget('ext.select2.ESelect2', array(
                              'selector' => '#SolProm_Kit',

                              'options'  => array(
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                    'width' => '100%',
                                    'language' => 'es',
                                    'ajax' => array(
                                          'url' => Yii::app()->createUrl('SolProm/SearchItem'),
                                      'dataType'=>'json',
                                        'data'=>'js:function(term){return{q: term};}',
                                        'results'=>'js:function(data){ return {results:data};}'
                                                 
                                  ),
                                  'formatNoMatches'=> 'js:function(){ clear_select2_ajax("SolProm_Kit"); return "No se encontraron resultados"; }',
                                  'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'SolProm_Kit\')\">Limpiar campo</button>"; }',
                              ),

                            ));
                          ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                            <?php echo $form->label($model,'Cant'); ?>
                            <?php echo $form->error($model,'Cant', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->numberField($model,'Cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '1', 'step' => '1', 'disabled' => true)); ?>

                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Observaciones'); ?>
                            <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)', 'maxlength' => '300', 'disabled' =>true)); ?>
                        </div>
                    </div> 
                  </div>

            <?php }else{ ?>
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <?php echo $form->error($model,'Num_Sol', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Num_Sol'); ?>
                            <p><?php echo $model->Num_Sol; ?></p>
                            <?php echo $form->hiddenField($model,'Estado', array('class' => 'form-control form-control-sm')); ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->error($model,'Responsable', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Responsable'); ?>
                            <p><?php echo $model->Responsable; ?></p>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Tipo'); ?>
                            <p><?php echo $model->DescTipo($model->Tipo); ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <?php echo $form->error($model,'Cliente', array('class' => 'badge badge-warning float-right')); ?>
                          <?php echo $form->label($model,'Cliente'); ?>
                          <p><?php if($model->Cliente === NULL){  echo "-";  }else{ echo $model->DescCliente($model->Cliente); } ?></p> 
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <?php echo $form->error($model,'Kit', array('class' => 'badge badge-warning float-right')); ?>
                          <?php echo $form->label($model,'Kit'); ?>
                          <p><?php echo $model->DescKit($model->Kit); ?></p>  
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                          <?php echo $form->error($model,'Cant', array('class' => 'badge badge-warning float-right')); ?>
                          <?php echo $form->label($model,'Cant'); ?>
                          <p><?php echo $model->Cant; ?></p>  
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <?php echo $form->error($model,'Observaciones', array('class' => 'badge badge-warning float-right')); ?>
                            <?php echo $form->label($model,'Observaciones'); ?>
                            <p><?php if($model->Observaciones == ""){  echo "-";  }else{ echo $model->Observaciones; } ?></p>    
                        </div>
                    </div>
                </div>
                <h5>Detalle</h5>
                <?php 

                //detalle

                $this->widget('zii.widgets.grid.CGridView', array(
                    'id'=>'det-ped-com-grid',
                    'dataProvider'=>$detalle->search(),
                    ///'filter'=>$model,
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
                            'name'=>'Cant_Base',
                            'value'=>function($data){
                                return number_format($data->Cant_Base, 4);
                            },
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name'=>'Cant_Requerida',
                            'value'=>function($data){
                                return number_format($data->Cant_Requerida, 4);
                            },
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                        array(
                            'name'=>'Cant_Solicitada',
                            'value'=>function($data){
                                return number_format($data->Cant_Solicitada, 4);
                            },
                            'htmlOptions'=>array('style' => 'text-align: right;'),
                        ),
                    ),
                ));

                ?>
            <?php } ?>
            <div class="row mb-2">
                <div class="col-sm-6" id="buttons_1">
                </div>
            </div>
        </div>
      </div>
  </div>
  <div class="card card-secondary" id="link_collapse_2">
      <div class="card-header" id="info_2">
        <h5 class="mb-0">
        <a class="btn-link" data-toggle="collapse" href="#collapse_2" role="button" aria-expanded="false" aria-controls="collapse_2"><i id="img_info_2" class="fas fa-clock"></i>
        Rev. Gerencia
        </a>
        </h5>
      </div>
      <div id="collapse_2" class="collapse" aria-labelledby="info_2" data-parent="#accordion">
        <div class="card-body"> 
            <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                      <?php echo $form->error($model,'Fecha', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->label($model,'Fecha'); ?>
                      <?php echo $form->textField($model,'Fecha', array('class' => 'form-control form-control-sm', 'disabled' => true)); ?>
                  </div>
                </div>         
                <div class="col-sm-3">
                    <div class="form-group">
                      <?php echo $form->error($model,'Fecha_T_Entrega', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->label($model,'Fecha_T_Entrega'); ?>
                      <?php echo $form->textField($model,'Fecha_T_Entrega', array('class' => 'form-control form-control-sm', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->error($model,'Observaciones_Ger', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->label($model,'Observaciones_Ger'); ?>
                      <?php echo $form->textArea($model,'Observaciones_Ger',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)', 'maxlength' => '300', 'disabled' =>true)); ?>
                  </div>
                </div>  
            </div>
            <div class="row mb-2">
                <div class="col-sm-6" id="buttons_2">  
                </div>
            </div> 
        </div>
      </div>
  </div>
  <div class="card card-secondary" id="link_collapse_3">
      <div class="card-header" id="info_3">
        <h5 class="mb-0">
          <a class="btn-link" data-toggle="collapse" href="#collapse_3" role="button" aria-expanded="false" aria-controls="collapse_3"><i id="img_info_3" class="fas fa-clock"></i>
          Rev. Planeación
          </a>
        </h5>
      </div>
      <div id="collapse_3" class="collapse" aria-labelledby="info_3" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo $form->label($model,'Val_Compra', array('class' => 'control-label')); ?>
                        <div class="form-check">
                            <?php echo $form->checkBox($model,'Val_Compra',array('value' => '1', 'uncheckValue'=>'0', 'class' => 'form-check-input', 'disabled' =>true)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo $form->label($model,'Val_Prod', array('class' => 'control-label')); ?>
                        <div class="form-check">
                            <?php echo $form->checkBox($model,'Val_Prod',array('value' => '1', 'uncheckValue'=>'0', 'class' => 'form-check-input', 'disabled' =>true)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <?php echo $form->label($model,'Val_MT', array('class' => 'control-label')); ?>
                        <div class="form-check">
                            <?php echo $form->checkBox($model,'Val_MT',array('value' => '1', 'uncheckValue'=>'0', 'class' => 'form-check-input', 'disabled' =>true)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                      <?php echo $form->error($model,'Observaciones_Pla', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->label($model,'Observaciones_Pla'); ?>
                      <?php echo $form->textArea($model,'Observaciones_Pla',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50, 'onkeyup' => 'convert_may(this)', 'maxlength' => '300', 'disabled' =>true)); ?>
                    </div>
                </div>  
            </div>
            <div class="row mb-2">
                <div class="col-sm-6" id="buttons_3"> 
                </div>
            </div>
        </div>
      </div>
  </div>
  <div class="card card-secondary" id="link_collapse_4">
      <div class="card-header" id="info_4">
        <h5 class="mb-0">
          <a class="btn-link" data-toggle="collapse" href="#collapse_4" role="button" aria-expanded="false" aria-controls="collapse_4"><i id="img_info_4" class="fas fa-clock"></i>
          Rev. Logística
          </a>
        </h5>
      </div>
      <div id="collapse_4" class="collapse" aria-labelledby="info_4" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php echo $form->label($model,'Fecha', array('class' => 'control-label')); ?>
                        <p><?php echo UtilidadesVarias::textofecha($model->Fecha); ?></p>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php echo $form->label($model,'Fecha_T_Entrega', array('class' => 'control-label')); ?>
                        <p><?php echo UtilidadesVarias::textofecha($model->Fecha_T_Entrega); ?></p>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php echo $form->label($model,'Comp_Disp', array('class' => 'control-label')); ?>
                        <div class="form-check">
                            <?php echo $form->checkBox($model,'Comp_Disp',array('value' => '1', 'uncheckValue'=>'0', 'class' => 'form-check-input', 'disabled' =>true)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3" id="fecha_entrega" style="display: none;">
                    <div class="form-group">
                        <?php echo $form->error($model,'Fecha_Entrega', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->label($model,'Fecha_Entrega'); ?>
                        <?php echo $form->textField($model,'Fecha_Entrega', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-6" id="buttons_4">  
                </div>
            </div>
        </div>
      </div>
  </div>
</div>
<?php if(!$model->isNewRecord){ ?>
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
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Estado', array('class' => 'control-label')); ?>
            <p><?php echo $model->DescEstado($model->Estado); ?></p>
        </div>
    </div>
</div>
<?php } ?>

<?php $this->endWidget(); ?>

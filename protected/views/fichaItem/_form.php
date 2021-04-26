<?php
/* @var $this FichaItemController */
/* @var $model FichaItem */
/* @var $form CActiveForm */

$estados2 = Yii::app()->params->estados2;

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ficha-item-form',
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
        Desarrollo - Innovación / Mercadeo
        </a>
        </h5>
      </div>
      <div id="collapse_1" class="collapse" aria-labelledby="info_1" data-parent="#accordion">
        <div class="card-body">
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Pais'); ?>
                      <?php echo $form->error($model,'Pais', array('class' => 'badge badge-warning float-right')); ?>
                      <?php $paises = array(1 => 'COLOMBIA', 2 => 'ECUADOR', 3 => 'PERÚ', 4 => 'CHILE'); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Pais]',
                              'id'=>'FichaItem_Pais',
                              'data'=>$paises,
                              'value' => $model->Pais,
                              'htmlOptions'=>array(
                                  'multiple'=>'multiple',
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
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Tipo_Producto'); ?>
                      <?php echo $form->error($model,'Tipo_Producto', array('class' => 'badge badge-warning float-right')); ?>
                      <?php $tipos_p = array(1 => 'PRODUCTO TERMINADO', 2 => 'PRODUCTO EN PROCESO', 3 => 'POP' , 4 => 'MATERIA PRIMA', 5 => 'PROMOCIÓN'); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Tipo_Producto]',
                              'id'=>'FichaItem_Tipo_Producto',
                              'data'=>$tipos_p,
                              'value' => $model->Tipo_Producto,
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
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Origen'); ?>
                      <?php echo $form->error($model,'Origen', array('class' => 'badge badge-warning float-right')); ?>
                      <?php $origen = array(1 => 'NACIONAL', 2 => 'IMPORTADO'); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Origen]',
                              'id'=>'FichaItem_Origen',
                              'data'=>$origen,
                              'value' => $model->Origen,
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
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Nombre_Funcional'); ?>
                      <?php echo $form->error($model,'Nombre_Funcional', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Nombre_Funcional', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'onchange' => 'desc_corta()', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Marca_Producto'); ?>
                      <?php echo $form->error($model,'Marca_Producto', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Marca_Producto', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'onchange' => 'desc_corta()', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Caracteristicas'); ?>
                      <?php echo $form->error($model,'Caracteristicas', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Caracteristicas', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'onchange' => 'desc_corta()', 'disabled' => true)); ?>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-sm-4" id="pres" style="display: none;">
                  <div class="form-group">
                      <?php echo $form->label($model,'Presentacion'); ?>
                      <?php echo $form->error($model,'Presentacion', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Presentacion', array('class' => 'form-control form-control-sm', 'maxlength' => '10', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'onchange' => 'desc_corta()', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-4">
                  <div class="form-group" id="env" style="display: none;">
                      <?php echo $form->label($model,'Envase'); ?>
                      <?php echo $form->error($model,'Envase', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Envase', array('class' => 'form-control form-control-sm', 'maxlength' => '100', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                  </div>
              </div>
          </div>
          <div class="row" id="comp" style="display: none;">
            <div class="col-sm-8">
                <div class="form-group">
                    <?php echo $form->label($model,'comp'); ?>
                    <?php echo $form->error($model,'comp', array('class' => 'badge badge-warning float-right')); ?>
                    <?php echo $form->textField($model,'comp'); ?>
                    <?php
                        $this->widget('ext.select2.ESelect2', array(
                            'selector' => '#FichaItem_comp',
                            'options'  => array(
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'width' => '100%',
                                'language' => 'es',
                                'ajax' => array(
                                    'url' => Yii::app()->createUrl('fichaItem/SearchItem'),
                                    'dataType'=>'json',
                                    'data'=>'js:function(term){return{q: term};}',
                                    'results'=>'js:function(data){ return {results:data};}'                   
                                ),
                                'formatNoMatches'=> 'js:function(){ clear_select2_ajax("FichaItem_Codigo_Item"); return "No se encontraron resultados"; }',
                                'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-success btn-xs float-right\" onclick=\"clear_select2_ajax(\'FichaItem_Codigo_Item\')\">Limpiar campo</button>"; }',
                                'initSelection'=>'js:function(element,callback) {
                                    var id=$(element).val(); // read #selector value
                                    if ( id !== "" ) {
                                        $.ajax("'.Yii::app()->createUrl('fichaItem/SearchItemById').'", {
                                            data: { id: id },
                                            dataType: "json"
                                        }).done(function(data,textStatus, jqXHR) { callback(data[0]); });
                                   }
                                }',
                            ),
                        ));
                    ?>
                </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                    <?php echo $form->label($model,'cant'); ?>
                    <?php echo $form->error($model,'cant', array('class' => 'badge badge-warning float-right')); ?>
                    <?php echo $form->numberField($model,'cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>

                </div>
            </div>
            <div class="col-sm-1 mt-4">
              <div class="form-group">
                    <button type="button" class="btn btn-success btn-sm" id="addcomp"><i class="fas fa-plus"></i> Añadir</button>
                </div>
            </div>          
          </div>
          <?php if($s != 10){ ?>
          <div class="row">  
              <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->label($model,'Descripcion_Corta'); ?>
                      <?php echo $form->error($model,'Descripcion_Corta', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Descripcion_Corta', array('class' => 'form-control form-control-sm', 'maxlength' => '75', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->label($model,'Descripcion_Larga', array('class' => 'control-label')); ?>
                      <?php echo $form->error($model,'Descripcion_Larga', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textArea($model,'Descripcion_Larga',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'maxlength'=>140, 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                  </div>
              </div>
          </div>
          <?php } ?>
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Unidad_Medida_Inv'); ?>
                      <?php echo $form->error($model,'Unidad_Medida_Inv', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Unidad_Medida_Inv]',
                              'id'=>'FichaItem_Unidad_Medida_Inv',
                              'data'=>$lista_unidad,
                              'value' => $model->Unidad_Medida_Inv,
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
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Unidad_Medida_Compra'); ?>
                      <?php echo $form->error($model,'Unidad_Medida_Compra', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Unidad_Medida_Compra]',
                              'id'=>'FichaItem_Unidad_Medida_Compra',
                              'data'=>$lista_unidad,
                              'value' => $model->Unidad_Medida_Compra,
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
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Ind_Compra'); ?>
                      <?php echo $form->error($model,'Ind_Compra', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Ind_Compra]',
                              'id'=>'FichaItem_Ind_Compra',
                              'data'=>$estados2,
                              'value' => $model->Ind_Compra,
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
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Ind_Manufactura'); ?>
                      <?php echo $form->error($model,'Ind_Manufactura', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Ind_Manufactura]',
                              'id'=>'FichaItem_Ind_Manufactura',
                              'data'=>$estados2,
                              'value' => $model->Ind_Manufactura,
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
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Ind_Venta'); ?>
                      <?php echo $form->error($model,'Ind_Venta', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Ind_Venta]',
                              'id'=>'FichaItem_Ind_Venta',
                              'data'=>$estados2,
                              'value' => $model->Ind_Venta,
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
          <?php if($s != 7 && $s != 8 && $s != 9 && $s != 10){ ?>
          <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Medida'); ?>
                      <?php echo $form->error($model,'Un_Medida', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Un_Medida]',
                              'id'=>'FichaItem_Un_Medida',
                              'data'=>$lista_unidad,
                              'value' => $model->Un_Medida,
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
              <div class="col-sm-4" id="und_ep" style="display: none;">
                <div class="form-group">
                      <?php echo $form->label($model,'Ep_Medida'); ?>
                  <?php echo $form->error($model,'Ep_Medida', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Ep_Medida]',
                              'id'=>'FichaItem_Ep_Medida',
                              'data'=>$lista_unidad,
                              'value' => $model->Ep_Medida,
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
              <div class="col-sm-4" id="und_cad" style="display: none;">
                  <div class="form-group">
                    <?php echo $form->label($model,'Cad_Medida'); ?>
                    <?php echo $form->error($model,'Cad_Medida', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Cad_Medida]',
                              'id'=>'FichaItem_Cad_Medida',
                              'data'=>$lista_unidad,
                              'value' => $model->Cad_Medida,
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
        Finanzas / Contabilidad
        </a>
        </h5>
      </div>
      <div id="collapse_2" class="collapse" aria-labelledby="info_2" data-parent="#accordion">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->label($model,'Tipo_Inventario'); ?>
                      <?php echo $form->error($model,'Tipo_Inventario', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Tipo_Inventario]',
                              'id'=>'FichaItem_Tipo_Inventario',
                              'data'=>$lista_tipo_inv,
                              'value' => $model->Tipo_Inventario,
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
            <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->label($model,'Grupo_Impositivo'); ?>
                      <?php echo $form->error($model,'Grupo_Impositivo', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Grupo_Impositivo]',
                              'id'=>'FichaItem_Grupo_Impositivo',
                              'data'=>$lista_grupo_imp,
                              'value' => $model->Grupo_Impositivo,
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
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Exento_Impuesto'); ?>
                      <?php echo $form->error($model,'Exento_Impuesto', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Exento_Impuesto]',
                              'id'=>'FichaItem_Exento_Impuesto',
                              'data'=>$estados2,
                              'value' => $model->Exento_Impuesto,
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
          Comercial / Mercadeo
          </a>
        </h5>
      </div>
      <div id="collapse_3" class="collapse" aria-labelledby="info_3" data-parent="#accordion">
        <div class="card-body">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Origen'); ?>
                  <?php echo $form->error($model,'Crit_Origen', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Origen]',
                              'id'=>'FichaItem_Crit_Origen',
                              'data'=>$lista_origen,
                              'value' => $model->Crit_Origen,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Tipo'); ?>
                  <?php echo $form->error($model,'Crit_Tipo', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Tipo]',
                              'id'=>'FichaItem_Crit_Tipo',
                              'data'=>$lista_tipo,
                              'value' => $model->Crit_Tipo,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Clasificacion'); ?>
                  <?php echo $form->error($model,'Crit_Clasificacion', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Clasificacion]',
                              'id'=>'FichaItem_Crit_Clasificacion',
                              'data'=>$lista_clasif,
                              'value' => $model->Crit_Clasificacion,
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
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Crit_Clase'); ?>
                      <?php echo $form->error($model,'Crit_Clase', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Clase]',
                              'id'=>'FichaItem_Crit_Clase',
                              'data'=>$lista_clase,
                              'value' => $model->Crit_Clase,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Marca'); ?>
                  <?php echo $form->error($model,'Crit_Marca', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Marca]',
                              'id'=>'FichaItem_Crit_Marca',
                              'data'=>$lista_marca,
                              'value' => $model->Crit_Marca,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Submarca'); ?>
                  <?php echo $form->error($model,'Crit_Submarca', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Submarca]',
                              'id'=>'FichaItem_Crit_Submarca',
                              'data'=>$lista_submarca,
                              'value' => $model->Crit_Submarca,
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
          <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Segmento'); ?>
                  <?php echo $form->error($model,'Crit_Segmento', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Segmento]',
                              'id'=>'FichaItem_Crit_Segmento',
                              'data'=>$lista_segmento,
                              'value' => $model->Crit_Segmento,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Familia'); ?>
                  <?php echo $form->error($model,'Crit_Familia', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Familia]',
                              'id'=>'FichaItem_Crit_Familia',
                              'data'=>$lista_familia,
                              'value' => $model->Crit_Familia,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Subfamilia'); ?>
                  <?php echo $form->error($model,'Crit_Subfamilia', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Subfamilia]',
                              'id'=>'FichaItem_Crit_Subfamilia',
                              'data'=>$lista_subfamilia,
                              'value' => $model->Crit_Subfamilia,
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
          <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Linea'); ?>
                  <?php echo $form->error($model,'Crit_Linea', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Linea]',
                              'id'=>'FichaItem_Crit_Linea',
                              'data'=>$lista_linea,
                              'value' => $model->Crit_Linea,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Sublinea'); ?>
                  <?php echo $form->error($model,'Crit_Sublinea', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Sublinea]',
                              'id'=>'FichaItem_Crit_Sublinea',
                              'data'=>$lista_sublinea,
                              'value' => $model->Crit_Sublinea,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Grupo'); ?>
                  <?php echo $form->error($model,'Crit_Grupo', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Grupo]',
                              'id'=>'FichaItem_Crit_Grupo',
                              'data'=>$lista_grupo,
                              'value' => $model->Crit_Grupo,
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
          <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_UN'); ?>
                  <?php echo $form->error($model,'Crit_UN', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_UN]',
                              'id'=>'FichaItem_Crit_UN',
                              'data'=>$lista_un,
                              'value' => $model->Crit_UN,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Fabrica'); ?>
                  <?php echo $form->error($model,'Crit_Fabrica', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Fabrica]',
                              'id'=>'FichaItem_Crit_Fabrica',
                              'data'=>$lista_fabrica,
                              'value' => $model->Crit_Fabrica,
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
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Crit_Cat_Oracle'); ?>
                  <?php echo $form->error($model,'Crit_Cat_Oracle', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Crit_Cat_Oracle]',
                              'id'=>'FichaItem_Crit_Cat_Oracle',
                              'data'=>$lista_oracle,
                              'value' => $model->Crit_Cat_Oracle,
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
          <div class="row mb-4">
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
          Ingeniería
          </a>
        </h5>
      </div>
      <div id="collapse_4" class="collapse" aria-labelledby="info_4" data-parent="#accordion">
        <div class="card-body">
          <h5 class="mt-3">Logistica unidad</h5>

          <p>Peso (KG) / Largo, Ancho Y Alto (CM)</p>

          <div class="row">
              <?php if($s == 7 || $s == 8 || $s == 9 || $s == 10){ ?>
              <div class="col-sm-4">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Medida'); ?>
                      <?php echo $form->error($model,'Un_Medida', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Un_Medida]',
                              'id'=>'FichaItem_Un_Medida',
                              'data'=>$lista_unidad,
                              'value' => $model->Un_Medida,
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
            <?php } ?>
              <div class="col-sm-3">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Cant'); ?>
                      <?php echo $form->error($model,'Un_Cant', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Un_Cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Peso'); ?>
                  <?php echo $form->error($model,'Un_Peso', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Un_Peso', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'disabled' => true)); ?>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-sm-3">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Largo'); ?>
                      <?php echo $form->error($model,'Un_Largo', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Un_Largo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(1)', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Ancho'); ?>
                      <?php echo $form->error($model,'Un_Ancho', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Un_Ancho', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(1)', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Alto'); ?>
                      <?php echo $form->error($model,'Un_Alto', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Un_Alto', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(1)', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                      <?php echo $form->label($model,'Un_Volumen'); ?>
                      <?php echo $form->error($model,'Un_Volumen', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Un_Volumen', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
                  </div>
              </div>
          </div>

          <div id="log_ep">

            <h5 class="mt-3">Logistica empaque principal</h5>

            <p>Peso (KG) / Largo, Ancho Y Alto (CM)</p>

            <div class="row">
                <?php if($s == 7 || $s == 8 || $s == 9 || $s == 10){ ?>
                <div class="col-sm-4">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Medida'); ?>
                    <?php echo $form->error($model,'Ep_Medida', array('class' => 'badge badge-warning float-right')); ?>
                        <?php
                            $this->widget('ext.select2.ESelect2',array(
                                'name'=>'FichaItem[Ep_Medida]',
                                'id'=>'FichaItem_Ep_Medida',
                                'data'=>$lista_unidad,
                                'value' => $model->Ep_Medida,
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
                <?php } ?>
                <div class="col-sm-4">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Cant'); ?>
                    <?php echo $form->error($model,'Ep_Cant', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Ep_Cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Peso'); ?>
                    <?php echo $form->error($model,'Ep_Peso', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Ep_Peso', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'disabled' => true)); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Largo'); ?>
                    <?php echo $form->error($model,'Ep_Largo', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Ep_Largo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(2)', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Ancho'); ?>
                    <?php echo $form->error($model,'Ep_Ancho', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Ep_Ancho', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(2)', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Alto'); ?>
                    <?php echo $form->error($model,'Ep_Alto', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Ep_Alto', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(2)', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Ep_Volumen'); ?>
                    <?php echo $form->error($model,'Ep_Volumen', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->textField($model,'Ep_Volumen', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
                    </div>
                </div>
            </div>
          </div>
          <div id="log_se_cad">

            <h5 class="mt-3">Logistica subempaque cadena</h5>

            <p>Peso (KG) / Largo, Ancho Y Alto (CM)</p>

            <div class="row">
                <?php if($s == 7 || $s == 8 || $s == 9 || $s == 10){ ?>
                <div class="col-sm-4">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Medida'); ?>
                    <?php echo $form->error($model,'Cad_Medida', array('class' => 'badge badge-warning float-right')); ?>
                        <?php
                            $this->widget('ext.select2.ESelect2',array(
                                'name'=>'FichaItem[Cad_Medida]',
                                'id'=>'FichaItem_Cad_Medida',
                                'data'=>$lista_unidad,
                                'value' => $model->Cad_Medida,
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
                <?php } ?>
                <div class="col-sm-4">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Cant'); ?>
                    <?php echo $form->error($model,'Cad_Cant', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Cad_Cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Peso'); ?>
                    <?php echo $form->error($model,'Cad_Peso', array('class' => 'badge badge-warning float-right')); ?>
                       <?php echo $form->numberField($model,'Cad_Peso', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'disabled' => true)); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Largo'); ?>
                    <?php echo $form->error($model,'Cad_Largo', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Cad_Largo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(3)', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Ancho'); ?>
                    <?php echo $form->error($model,'Cad_Ancho', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Cad_Ancho', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(3)', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Alto'); ?>
                    <?php echo $form->error($model,'Cad_Alto', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->numberField($model,'Cad_Alto', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '0.0001', 'onchange' => 'calculo_volumen(3)', 'disabled' => true)); ?>
                    </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                        <?php echo $form->label($model,'Cad_Volumen'); ?>
                    <?php echo $form->error($model,'Cad_Volumen', array('class' => 'badge badge-warning float-right')); ?>
                        <?php echo $form->textField($model,'Cad_Volumen', array('class' => 'form-control form-control-sm', 'readonly' => true)); ?>
                    </div>
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
  <div class="card card-secondary" id="link_collapse_5">
      <div class="card-header" id="info_5">
        <h5 class="mb-0">
          <a class="btn-link" data-toggle="collapse" href="#collapse_5" role="button" aria-expanded="false" aria-controls="collapse_5"><i id="img_info_5" class="fas fa-clock"></i>
          Datos Maestros
          </a>
        </h5>
      </div>
      <div id="collapse_5" class="collapse" aria-labelledby="info_5" data-parent="#accordion">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                  <?php echo $form->label($model,'Codigo_Item'); ?>
                  <?php echo $form->error($model,'Codigo_Item', array('class' => 'badge badge-warning float-right')); ?>
                  <?php echo $form->textField($model,'Codigo_Item', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off', 'onkeypress' => 'return soloNumeros(event);', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
              </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->label($model,'Referencia'); ?>
                    <?php echo $form->error($model,'Referencia', array('class' => 'badge badge-warning float-right')); ?>
                    <?php echo $form->textField($model,'Referencia', array('class' => 'form-control form-control-sm', 'maxlength' => '20', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->label($model,'Maneja_Lote'); ?>
                    <?php echo $form->error($model,'Maneja_Lote', array('class' => 'badge badge-warning float-right')); ?>
                    <?php
                        $this->widget('ext.select2.ESelect2',array(
                            'name'=>'FichaItem[Maneja_Lote]',
                            'id'=>'FichaItem_Maneja_Lote',
                            'data'=>$estados2,
                            'value' => $model->Maneja_Lote,
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
          <?php if($s == 10){ ?>
          <div class="row">  
              <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->label($model,'Descripcion_Corta'); ?>
                      <?php echo $form->error($model,'Descripcion_Corta', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textField($model,'Descripcion_Corta', array('class' => 'form-control form-control-sm', 'maxlength' => '75', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-6">
                  <div class="form-group">
                      <?php echo $form->label($model,'Descripcion_Larga', array('class' => 'control-label')); ?>
                      <?php echo $form->error($model,'Descripcion_Larga', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->textArea($model,'Descripcion_Larga',array('class' => 'form-control form-control-sm', 'rows'=>2, 'cols'=>50, 'maxlength'=>140, 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                  </div>
              </div>
          </div>
        <?php } ?>
          <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Tiempo_Reposicion'); ?>
                      <?php echo $form->error($model,'Tiempo_Reposicion', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Tiempo_Reposicion', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Cant_Moq'); ?>
                      <?php echo $form->error($model,'Cant_Moq', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Cant_Moq', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>
                  </div>
              </div>
              <div class="col-sm-4">
                  <div class="form-group">
                      <?php echo $form->label($model,'Stock_Minimo'); ?>
                      <?php echo $form->error($model,'Stock_Minimo', array('class' => 'badge badge-warning float-right')); ?>
                      <?php echo $form->numberField($model,'Stock_Minimo', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'min' => '0', 'step' => '1', 'disabled' => true)); ?>
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                  <?php echo $form->label($model,'Posicion_Arancelar'); ?>
                  <?php echo $form->error($model,'Posicion_Arancelar', array('class' => 'badge badge-warning float-right')); ?>
                  <?php echo $form->textField($model,'Posicion_Arancelar', array('class' => 'form-control form-control-sm', 'maxlength' => '10', 'autocomplete' => 'off', 'onkeypress' => 'return soloNumeros(event);', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                      <?php echo $form->label($model,'Instalaciones'); ?>
                  <?php echo $form->error($model,'Instalaciones', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Instalaciones]',
                              'id'=>'FichaItem_Instalaciones',
                              'data'=>$lista_ins,
                              'value' => $model->Instalaciones,
                              'htmlOptions'=>array(
                                  'multiple'=>'multiple',
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
          <div class="row">   
              <div class="col-sm-12">
                <div class="form-group">
                      <?php echo $form->label($model,'Bodegas'); ?>
                  <?php echo $form->error($model,'Bodegas', array('class' => 'badge badge-warning float-right')); ?>
                      <?php
                          $this->widget('ext.select2.ESelect2',array(
                              'name'=>'FichaItem[Bodegas]',
                              'id'=>'FichaItem_Bodegas',
                              'data'=>$lista_bodegas,
                              'value' => $model->Bodegas,
                              'htmlOptions'=>array(
                                  'multiple'=>'multiple',
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
          <div class="row">
            <div id="un_gtin" class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->label($model,'Un_Gtin'); ?>
                    <?php echo $form->error($model,'Un_Gtin', array('class' => 'badge badge-warning float-right')); ?>
                    <?php echo $form->textField($model,'Un_Gtin', array('class' => 'form-control form-control-sm', 'maxlength' => '13', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                </div>
            </div>
            <div id="ep_gtin" class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->label($model,'Ep_Gtin'); ?>
                    <?php echo $form->error($model,'Ep_Gtin', array('class' => 'badge badge-warning float-right')); ?>
                    <?php echo $form->textField($model,'Ep_Gtin', array('class' => 'form-control form-control-sm', 'maxlength' => '14', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                </div>
            </div>
            <div id="cad_gtin" class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->label($model,'Cad_Gtin'); ?>
                    <?php echo $form->error($model,'Cad_Gtin', array('class' => 'badge badge-warning float-right')); ?>
                    <?php echo $form->textField($model,'Cad_Gtin', array('class' => 'form-control form-control-sm', 'maxlength' => '14', 'onkeypress' => 'return soloNumeros(event);', 'autocomplete' => 'off', 'onkeyup' => 'convert_may(this)', 'disabled' => true)); ?>
                </div>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-sm-6" id="buttons_5">                  
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
            <?php echo $form->label($model,'Id_Usuario_Solicitud', array('class' => 'control-label')); ?>
            <p><?php echo $model->idusuariosol->Nombres; ?></p>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Hora_Solicitud', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Hora_Solicitud); ?></p>
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
            <?php echo $form->label($model,'Fecha_Hora_Actualizacion', array('class' => 'control-label')); ?>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Hora_Actualizacion); ?></p>
        </div>
    </div>
</div>
<?php } ?>

<?php $this->endWidget(); ?>
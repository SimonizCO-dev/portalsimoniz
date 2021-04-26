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
	<div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Codigo_Item'); ?>
            <?php echo $form->error($model,'Codigo_Item', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->textField($model,'Codigo_Item'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#FichaItem_Codigo_Item',
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
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'FichaItem_Codigo_Item\')\">Limpiar campo</button>"; }',
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
</div>
<div id="div_info" style="display: none;">
    <div class="row" id="div_cants">
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

    <h5 class="mt-3">Criterios</h5>

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

    <div class="row mb-4" id="buttons_1">
        <div class="col-sm-6">
            <?php if($model->Step != 9){ ?>
                <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> Guardar</button>
            <?php }else{ ?>
                <button type="button" class="btn btn-primary btn-sm" id="rechazar_form"><i class="fas fa-exclamation-circle"></i> Solicitar revisión</button>
                <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-check-circle"></i> Aprobar</button>
            <?php } ?>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
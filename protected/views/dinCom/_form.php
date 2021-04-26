<?php
/* @var $this DinComController */
/* @var $model DinCom */
/* @var $form CActiveForm */
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
		  <?php echo $form->error($model,'Pais', array('class' => 'badge badge-warning float-right')); ?>
		  <?php
		      $this->widget('ext.select2.ESelect2',array(
		          'name'=>'DinCom[Pais]',
		          'id'=>'DinCom_Pais',
		          'data'=>UtilidadesVarias::listapaises(),
		          'value' => $model->Pais,
		          'htmlOptions'=>array(
		              'multiple'=>'multiple',
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
		  <?php echo $form->label($model,'Tipo'); ?>
		  <?php echo $form->error($model,'Tipo', array('class' => 'badge badge-warning float-right')); ?>
		  <?php $tipos = array(1 => 'ITEM', 2 => 'CLIENTE', 3 => 'CRITERIO CLIENTE', 4 => 'CRITERIO ITEM', 5 => 'OBSEQUIO', 6 => 'LISTA PRECIOS', 7 => 'CO', 8 => 'ITEM / CLIENTE', 9 => 'ITEM / CRITERIO CLIENTE', 10 => 'ITEM / LISTA DE PRECIOS ', 11 => 'ITEM / CO', 12 => 'CRITERIO ITEM / CRITERIO CLIENTE', 13 => 'CRITERIO ITEM / CLIENTE', 14 => 'CRITERIO ITEM / LISTA DE PRECIOS', 15 => 'CRITERIO ITEM / CO', 16 => 'CRITERIO CLIENTE / LISTA DE PRECIOS', 17 => 'CRITERIO CLIENTE / CO', 18 => 'CLIENTE / LISTA DE PRECIOS', 19 => 'CLIENTE / CO', 20 => 'LISTA DE PRECIOS / CO'); ?>
		  <?php
		      $this->widget('ext.select2.ESelect2',array(
		          'name'=>'DinCom[Tipo]',
		          'id'=>'DinCom_Tipo',
		          'data'=>$tipos,
		          'value' => $model->Tipo,
		          'htmlOptions'=>array(
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
	<div class="col-sm-2">
	  	<div class="form-group">
	        <?php echo $form->error($model,'Fecha_Inicio', array('class' => 'badge badge-warning float-right')); ?>
	        <?php echo $form->label($model,'Fecha_Inicio'); ?>
	        <?php echo $form->textField($model,'Fecha_Inicio', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'readonly' => true)); ?>
	  	</div>
	</div>
	<div class="col-sm-2">
	  	<div class="form-group">
	        <?php echo $form->error($model,'Fecha_Fin', array('class' => 'badge badge-warning float-right')); ?>
	        <?php echo $form->label($model,'Fecha_Fin'); ?>
	        <?php echo $form->textField($model,'Fecha_Fin', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off', 'readonly' => true)); ?>
	  	</div>
	</div>
</div>
<div class="row">		
	<div class="col-sm-6" id="div_item" style="display: none;">
      	<div class="form-group">
      		<?php echo $form->label($model,'Item'); ?>
			<div class="badge badge-warning float-right" id="Error_Item" style="display: none;"></div>
			<?php echo $form->textField($model,'Item'); ?>
			<?php
			  $this->widget('ext.select2.ESelect2', array(
			      'selector' => '#DinCom_Item',
			      'options'  => array(
			          'allowClear' => true,
			          'minimumInputLength' => 3,
			          'width' => '100%',
			          'language' => 'es',
			          'ajax' => array(
			              'url' => Yii::app()->createUrl('reporte/SearchItem'),
			              'dataType'=>'json',
			              'data'=>'js:function(term){return{q: term};}',
			              'results'=>'js:function(data){ return {results:data};}'                   
			          ),
			          'formatNoMatches'=> 'js:function(){ clear_select2_ajax("DinCom_Item"); return "No se encontraron resultados"; }',
			          'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'DinCom_Item\')\">Limpiar campo</button>"; }',
			      ),
			  ));
			?>
      	</div>
  	</div>		
	<div class="col-sm-6" id="div_cliente" style="display: none;">
      	<div class="form-group">
			<?php echo $form->label($model,'Cliente'); ?>
			<div class="badge badge-warning float-right" id="Error_Cliente" style="display: none;"></div>
			<?php echo $form->textField($model,'Cliente'); ?>
			<?php
			$this->widget('ext.select2.ESelect2', array(
			  'selector' => '#DinCom_Cliente',
			  'options'  => array(
			    'allowClear' => true,
			    'minimumInputLength' => 3,
			        'width' => '100%',
			        'language' => 'es',
			        'ajax' => array(
			              'url' => Yii::app()->createUrl('reporte/SearchCliente'),
			          'dataType'=>'json',
			            'data'=>'js:function(term){return{q: term};}',
			            'results'=>'js:function(data){ return {results:data};}'
			                     
			      ),
			      'formatNoMatches'=> 'js:function(){ clear_select2_ajax("DinCom_Cliente"); return "No se encontraron resultados"; }',
			      'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'DinCom_Cliente\')\">Limpiar campo</button>"; }',
			  ),

			));
			?>
      	</div>
  	</div>		
	<div class="col-sm-6" id="div_l_precios" style="display: none;">
      	<div class="form-group">
			<?php echo $form->label($model,'Lista_Precios'); ?>
			<div class="badge badge-warning float-right" id="Error_Lista_Precios" style="display: none;"></div>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			      'name'=>'DinCom[Lista_Precios]',
			      'id'=>'DinCom_Lista_Precios',
			      'data'=>$lp,
			      'options'=>array(
			          'placeholder'=>'Seleccione...',
			          'width'=> '100%',
			          'allowClear'=>true,
			      ),
			  ));
			?>
      	</div>
  	</div>
  	<div class="col-sm-4" id="div_co" style="display: none;">
      	<div class="form-group">
			<?php echo $form->label($model,'CO'); ?>
			<div class="badge badge-warning float-right" id="Error_CO" style="display: none;"></div>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			      'name'=>'DinCom[CO]',
			      'id'=>'DinCom_CO',
			      'data'=>$co,
			      'options'=>array(
			          'placeholder'=>'Seleccione...',
			          'width'=> '100%',
			          'allowClear'=>true,
			      ),
			  ));
			?>
      	</div>
  	</div>
	<div class="col-sm-3" id="div_cant_min" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Min'); ?>
            <div class="badge badge-warning float-right" id="Error_Cant_Min" style="display: none;"></div>
            <?php echo $form->numberField($model,'Cant_Min', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '1')); ?>
        </div>
    </div>
    <div class="col-sm-3" id="div_cant_max" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Max'); ?>
            <div class="badge badge-warning float-right" id="Error_Cant_Max" style="display: none;"></div>
            <?php echo $form->numberField($model,'Cant_Max', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '1')); ?>
        </div>
    </div>
    <div class="col-sm-3" id="div_cant_ped" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Ped'); ?>
            <div class="badge badge-warning float-right" id="Error_Cant_Ped" style="display: none;"></div>
            <?php echo $form->numberField($model,'Cant_Ped', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '1')); ?>
        </div>
    </div>
    <div class="col-sm-3" id="div_cant_obs" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Cant_Obs'); ?>
            <div class="badge badge-warning float-right" id="Error_Cant_Obs" style="display: none;"></div>
            <?php echo $form->numberField($model,'Cant_Obs', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '1')); ?>
        </div>
    </div>
	<div class="col-sm-3" id="div_vlr_min" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Vlr_Min'); ?>
            <div class="badge badge-warning float-right" id="Error_Vlr_Min" style="display: none;"></div>
            <?php echo $form->numberField($model,'Vlr_Min', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '0.01')); ?>
        </div>
    </div>
    <div class="col-sm-3" id="div_vlr_max" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Vlr_Max'); ?>
            <div class="badge badge-warning float-right" id="Error_Vlr_Max" style="display: none;"></div>
            <?php echo $form->numberField($model,'Vlr_Max', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '0.01')); ?>
        </div>
    </div>
	<div class="col-sm-3" id="div_desc" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Descuento'); ?>
            <div class="badge badge-warning float-right" id="Error_Descuento" style="display: none;"></div>
            <?php echo $form->numberField($model,'Descuento', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off' , 'step' => '0.01')); ?>
        </div>
    </div>
</div>
<div class="row" id="div_p_cri_cliente" style="display: none;">		
	<div class="col-sm-4">
      	<div class="form-group">
			<?php echo $form->label($model,'Id_Plan_Cliente'); ?>
			<div class="badge badge-warning float-right" id="Error_Id_Plan_Cliente" style="display: none;"></div>
			<?php echo $form->hiddenField($model,'Cad_Plan_Cliente'); ?>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			      'name'=>'DinCom[Id_Plan_Cliente]',
			      'id'=>'DinCom_Id_Plan_Cliente',
			      'data'=>UtilidadesVarias::listaplanescliente(),
			      'options'=>array(
			          'placeholder'=>'Seleccione...',
			          'width'=> '100%',
			          'allowClear'=>true,
			      ),
			  ));
			?>
      	</div>
  	</div>
  	<div class="col-sm-6" id="div_cri_cli" style="display: none;">
      	<div class="form-group">
      		<?php echo $form->label($model,'Id_Criterio_Cliente'); ?>
      		<?php echo $form->hiddenField($model,'Cad_Criterio_Cliente'); ?>
  			<div class="badge badge-warning float-right" id="Error_Id_Criterio_Cliente" style="display: none;"></div>
  			<div class="badge badge-warning float-right" id="Error_Cad_Criterio_Cliente" style="display: none;"></div>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			      'name'=>'DinCom[Id_Criterio_Cliente]',
			      'id'=>'DinCom_Id_Criterio_Cliente',
			      'options'=>array(
			          'placeholder'=>'Seleccione...',
			          'width'=> '100%',
			          'allowClear'=>true,
			      ),
			  ));
			?>
      	</div>
  	</div>
  	<div class="col-sm-2 mt-4" id="div_btn_cri_cli" style="display: none;">
  		<button type="button" class="btn btn-primary btn-sm" onclick="add_criterio_cliente();"><i class="fas fa-plus"></i> Añadir</button>
  	</div>
</div>
<div id="contenido_criterios_cliente" class="mb-2 mt-2"></div>
<div class="row" id="div_p_cri_item" style="display: none;">		
	<div class="col-sm-4">
      	<div class="form-group">
			<?php echo $form->label($model,'Id_Plan_Item'); ?>
			<?php echo $form->hiddenField($model,'Cad_Plan_Item'); ?>
			<div class="badge badge-warning float-right" id="Error_Id_Plan_Item" style="display: none;"></div>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			      'name'=>'DinCom[Id_Plan_Item]',
			      'id'=>'DinCom_Id_Plan_Item',
			      'data'=>UtilidadesVarias::listaplanesitem(),
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
      	<div class="form-group" id="div_cri_item" style="display: none;">
  			<?php echo $form->label($model,'Id_Criterio_Item'); ?>
  			<?php echo $form->hiddenField($model,'Cad_Criterio_Item'); ?>
  			<div class="badge badge-warning float-right" id="Error_Id_Criterio_Item" style="display: none;"></div>
			<div class="badge badge-warning float-right" id="Error_Cad_Criterio_Item" style="display: none;"></div>
			<?php
			  $this->widget('ext.select2.ESelect2',array(
			      'name'=>'DinCom[Id_Criterio_Item]',
			      'id'=>'DinCom_Id_Criterio_Item',
			      'options'=>array(
			          'placeholder'=>'Seleccione...',
			          'width'=> '100%',
			          'allowClear'=>true,
			      ),
			  ));
			?>
      	</div>
  	</div>
  	<div class="col-sm-2 mt-4" id="div_btn_cri_item" style="display: none;">
  		<button type="button" class="btn btn-primary btn-sm" onclick="add_criterio_item();"><i class="fas fa-plus"></i> Añadir</button>
  	</div>
</div>

<div id="contenido_criterios_item" class="mb-2 mt-2"></div>

<div class="row mb-2">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=dincom/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>  
    </div>
</div>

<?php $this->endWidget(); ?>

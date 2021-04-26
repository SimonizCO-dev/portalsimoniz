 <?php
/* @var $this CuentaController */
/* @var $model Cuenta */
/* @var $form CActiveForm */
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
        <h4>Actualización de cuenta / usuario</h4>
    </div>
    <div class="col-sm-6 text-right">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=cuenta/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" id="btn_asoc" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=CuentaEmpleado/create&id='.$model->Id_Cuenta; ?>';"><i class="fa fa-plus"></i> Asociar empleado</button>
        <button type="button" class="btn btn-primary btn-sm" id="view_p"><i class="fas fa-key"></i> Ver password actual</button>
        <button type="button" class="btn btn-primary btn-sm" id="valida_form"><i class="fas fa-save"></i> <?php if($model->isNewRecord){echo 'Crear';}else{ echo 'Guardar';} ?></button>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'Clasificacion'); ?><br>
        	<p><?php echo $model->clasificacion->Dominio; ?></p>
        </div>
    </div>
    <div class="col-sm-8">
   		<div class="badge badge-warning float-right" id="error_dup" style="display: none;"></div>
   	</div>
</div>
<div class="row">
	<div class="col-sm-4" id="div_cuenta_usuario" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_cuenta_usuario" style="display: none;"></div>
            <?php echo $form->label($model,'Cuenta_Usuario'); ?><br>
            <p><?php echo $model->Cuenta_Usuario; ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_dominio" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Dominio'); ?><br>
            <p><?php if($model->Dominio != "" ){ echo $model->dominioweb->Dominio; } ?></p>
        </div>
    </div>
    <div class="col-sm-4" id="div_password" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_password" style="display: none;"></div>
            <?php echo $form->label($model,'Password'); ?>
            <input type="text" name="Cuenta[Password]" id="Cuenta_Password" class="form-control form-control-sm" autocomplete="off">
        </div>
    </div>
    <div class="col-sm-4" id="div_tipo_cuenta" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_tipo_cuenta" style="display: none;"></div>
            <?php echo $form->label($model,'Tipo_Cuenta'); ?>
            <?php
        		$this->widget('ext.select2.ESelect2',array(
					'name'=>'Cuenta[Tipo_Cuenta]',
					'id'=>'Cuenta_Tipo_Cuenta',
					'data'=>$lista_tipos,
					'value' => $model->Tipo_Cuenta,
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
    <div class="col-sm-4" id="div_tipo_acceso" style="display: none;">
        <div class="form-group">
        	<div class="badge badge-warning float-right" id="error_tipo_acceso" style="display: none;"></div>
            <?php echo $form->label($model,'Tipo_Acceso'); ?>
		    <?php
        		$this->widget('ext.select2.ESelect2',array(
					'name'=>'Cuenta[Tipo_Acceso]',
					'id'=>'Cuenta_Tipo_Acceso',
					'data'=> array(1 => 'GENÉRICO', 2 => 'PERSONAL'),
					'value' => $model->Tipo_Acceso,
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
    <div class="col-sm-8" id="div_observaciones" style="display: none;">
        <div class="form-group">
            <?php echo $form->label($model,'Observaciones'); ?>
            <?php echo $form->textArea($model,'Observaciones',array('class' => 'form-control form-control-sm', 'rows'=>3, 'cols'=>50)); ?>
        </div>
    </div>
    <div class="col-sm-4" id="div_ext" style="display: none;">
    	<div class="form-group">
      		<?php echo $form->label($model,'Ext'); ?>
      		<?php echo $form->textField($model,'Ext', array('class' => 'form-control form-control-sm', 'maxlength' => '10', 'autocomplete' => 'off')); ?>
    	</div>
 	</div>
    <div class="col-sm-4" id="div_estado" style="display: none;">
        <div class="form-group">
            <div class="badge badge-warning float-right" id="error_estado" style="display: none;"></div>
            <?php echo $form->label($model,'Estado'); ?>
            <?php
                $this->widget('ext.select2.ESelect2',array(
                    'name'=>'Cuenta[Estado]',
                    'id'=>'Cuenta_Estado',
                    'data'=>$lista_estados,
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
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Creacion'); ?><br>
            <p><?php echo $model->idusuariocre->Usuario; ?></p>                
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Creacion'); ?><br>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Creacion); ?></p>                  
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Usuario_Actualizacion'); ?><br>
            <p><?php echo $model->idusuarioact->Usuario; ?></p>                     
        </div>
    </div>
    <div class="col-sm-3">
        <div class="form-group">
            <?php echo $form->label($model,'Fecha_Actualizacion'); ?><br>
            <p><?php echo UtilidadesVarias::textofechahora($model->Fecha_Actualizacion); ?></p>                    
        </div>
    </div>
</div>


<h4>Detalle</h4>

<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#emp">Empleados asoc.</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#log">Log</a></li>
</ul>

<!-- Tab panes -->
 <div class="tab-content">
    <div id="emp" class="tab-pane active"><br>
     	<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'cuenta-empleado-grid',
			'dataProvider'=>$emp_asoc->search(),
			'pager'=>array(
		        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
		    ),
		    'enableSorting' => false,
			'columns'=>array(
				array(
		            'name'=>'Id_Empleado',
		            'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::nombreempleado($data->Id_Empleado)',
		        ),
		        array(
                    'header'=>'Empresa',
                    'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::empresaactualempleado($data->Id_Empleado)',
                ),
                array(
                    'header'=>'Cargo',
                    'value' => '($data->Id_Empleado == "") ? "-" :  UtilidadesEmpleado::cargoactualempleado($data->Id_Empleado)',
                ),
				array(
					'class'=>'CButtonColumn',
		            'template'=>'{update}',
		            'buttons'=>array(
		                'update'=>array(
		                    'label'=>'<i class="fa fa-user-times actions text-dark"></i>',
		                    'imageUrl'=>false,
		                    'url'=>'Yii::app()->createUrl("CuentaEmpleado/inact", array("id"=>$data->Id_Cuenta_Emp, "opc"=>2))',
		                    'visible'=> '(Yii::app()->user->getState("permiso_act") == true && $data->Estado == 1)',
		                    'options'=>array('title'=>' Desvincular empleado', 'confirm'=>'Esta seguro de desvincular el empleado de esta cuenta ?'),
		                ),
		            )
				),
			),
		));

		?>   
    </div>
    <div id="log" class="tab-pane"><br>
     	<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'cuenta-empleado-grid',
			'dataProvider'=>$nov_cue->search(),
			'pager'=>array(
		        'cssFile'=>Yii::app()->getBaseUrl(true).'/css/pager.css',
		    ),
		    'enableSorting' => false,
			'columns'=>array(
				'Novedades',
				array(
		            'name'=>'Id_Usuario_Creacion',
		            'value'=>'$data->idusuariocre->Usuario',
		        ),
		        array(
		            'name'=>'Fecha_Creacion',
		            'value'=>'UtilidadesVarias::textofechahora($data->Fecha_Creacion)',
		        ),
			),
		));

		?>   
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
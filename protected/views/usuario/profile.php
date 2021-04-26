<?php
/* @var $this UsuarioController */
/* @var $model Usuario */
?>

<!-- Content Header (Page header) -->

<div class="row">
  <div class="col-sm-6">
    <h4>Configuración de usuario</h4>
  </div>
  <div class="col-sm-6">

  </div>
</div>

<div class="row">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center mb-2">
          <?php if(Yii::app()->user->getState('avatar_user') == 1) { ?>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/w.png" class="profile-user-img img-fluid img-circle" alt="Avatar">
          <?php }else{ ?>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/m.png" class="profile-user-img img-fluid img-circle" alt="Avatar">
          <?php } ?>
        </div>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Usuario</b> <a class="float-right"><?php echo Yii::app()->user->getState('name_user'); ?></a>
          </li>
          <li class="list-group-item">
            <b>Nombres</b> <a class="float-right"><?php echo Yii::app()->user->getState('username_user'); ?></a>
          </li>
          <li class="list-group-item">
            <b>Email</b> <a class="float-right"><?php echo Yii::app()->user->getState('email_user'); ?></a>
          </li>
        </ul>

        <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/logout'; ?>" class="btn btn-primary btn-block"><b><i class="nav-icon fas fa-sign-out-alt"></i> Cerrar sesión</b></a>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
  <div class="col-md-9">
    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item"><a class="nav-link active" href="#credenciales" data-toggle="tab">Cambios de credenciales</a></li>
          <li class="nav-item"><a class="nav-link" href="#perfiles" data-toggle="tab">Perfiles asociados</a></li>
          <li class="nav-item"><a class="nav-link" href="#empresas" data-toggle="tab">Empresas asociadas</a></li>
          <li class="nav-item"><a class="nav-link" href="#areas" data-toggle="tab">Áreas asociados</a></li>
          <li class="nav-item"><a class="nav-link" href="#tipos_docto" data-toggle="tab">Tipos de documento (Suministros) asociados</a></li>
          <li class="nav-item"><a class="nav-link" href="#bodegas" data-toggle="tab">Bodegas (Suministros) asociadas</a></li>
        </ul>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content">
          <div class="active tab-pane" id="credenciales">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'change-password-form',
                'htmlOptions'=>array(
                  'class'=>'form-horizontal',
                ),
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
                <div class="col-4">
                  <?php echo $form->label($model,'old_password',array('class' => 'control-label')); ?>
                  <?php echo $form->passwordField($model,'old_password', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
                </div>
                <div class="col-6">
                  <?php echo $form->error($model,'old_password', array('class' => 'badge badge-warning float-left')); ?>
                </div>
              </div>

              <div class="row">
                <div class="col-4">
                  <?php echo $form->label($model,'new_password',array('class' => 'control-label')); ?>
                  <?php echo $form->passwordField($model,'new_password', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
                </div>
                <div class="col-6">
                  <?php echo $form->error($model,'new_password', array('class' => 'badge badge-warning float-left')); ?>
                </div>
              </div>

              <div class="row">
                <div class="col-4">
                  <?php echo $form->label($model,'repeat_password',array('class' => 'control-label')); ?>
                  <?php echo $form->passwordField($model,'repeat_password', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
                </div>
                <div class="col-6">
                  <?php echo $form->error($model,'repeat_password', array('class' => 'badge badge-warning float-left')); ?>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-sm-6">  
                  <button type="submit" class="btn btn-primary btn-sm" ><i class="fas fa-key"></i> Guardar cambios</button>
                </div>
              </div>

              <!-- /.row -->
              <?php $this->endWidget(); ?>  
          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="perfiles">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-sm table-hover">
                  <thead>
                    <tr>
                      <th>Perfil</th>
                      <th>Usuario que actualizó</th>
                      <th>Fecha de actualización</th>
                      <th>Estado</th>
                    </tr>
                  </thead><tbody>
                  <?php
                    foreach ($perfiles as $p) {
                      echo '<tr>';
                      echo '<td>'.$p->idperfil->Descripcion.'</td>';
                      echo '<td>'.$p->idusuarioact->Usuario.'</td>';
                      echo '<td>'.UtilidadesVarias::textofechahora($p->Fecha_Actualizacion).'</td>';
                      echo '<td>'.UtilidadesVarias::textoestado1($p->Estado).'</td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody></table>   
              </div>
            </div>  
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="empresas">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-sm table-hover">
                  <thead>
                    <tr>
                      <th>Empresa</th>
                      <th>Usuario que actualizó</th>
                      <th>Fecha de actualización</th>
                      <th>Estado</th>
                    </tr>
                  </thead><tbody>
                  <?php
                    foreach ($empresas as $e) {
                      echo '<tr>';
                      echo '<td>'.$e->idempresa->Descripcion.'</td>';
                      echo '<td>'.$e->idusuarioact->Usuario.'</td>';
                      echo '<td>'.UtilidadesVarias::textofechahora($e->Fecha_Actualizacion).'</td>';
                      echo '<td>'.UtilidadesVarias::textoestado1($e->Estado).'</td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody></table>   
              </div>
            </div> 
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="areas">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-sm table-hover">
                  <thead>
                    <tr>
                      <th>Área</th>
                      <th>Usuario que actualizó</th>
                      <th>Fecha de actualización</th>
                      <th>Estado</th>
                    </tr>
                  </thead><tbody>
                  <?php
                  if(!empty($areas)){
                    foreach ($areas as $a) {
                      echo '<tr>';
                      echo '<td>'.$a->idarea->Area.'</td>';
                      echo '<td>'.$a->idusuarioact->Usuario.'</td>';
                      echo '<td>'.UtilidadesVarias::textofechahora($a->Fecha_Actualizacion).'</td>';
                      echo '<td>'.UtilidadesVarias::textoestado1($a->Estado).'</td>';
                      echo '</tr>';
                    }
                  }else{
                    echo '<tr>';
                    echo '<td colspan="4">No se encontraron resultados.</td>';
                    echo '</tr>';
                  }
                  ?>
                </tbody></table>   
              </div>
            </div> 
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="tipos_docto">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-sm table-hover">
                  <thead>
                    <tr>
                      <th>Tipo de docto</th>
                      <th>Usuario que actualizó</th>
                      <th>Fecha de actualización</th>
                      <th>Estado</th>
                    </tr>
                  </thead><tbody>
                  <?php
                  if(!empty($tipos_docto)){
                    foreach ($tipos_docto as $td) {
                      echo '<tr>';
                      echo '<td>'.$td->idtipodocto->Descripcion.'</td>';
                      echo '<td>'.$td->idusuarioact->Usuario.'</td>';
                      echo '<td>'.UtilidadesVarias::textofechahora($td->Fecha_Actualizacion).'</td>';
                      echo '<td>'.UtilidadesVarias::textoestado1($td->Estado).'</td>';
                      echo '</tr>';
                    }
                  }else{
                    echo '<tr>';
                    echo '<td colspan="4">No se encontraron resultados.</td>';
                    echo '</tr>';
                  }
                  ?>
                </tbody></table>   
              </div>
            </div>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="bodegas">
            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-sm table-hover">
                  <thead>
                    <tr>
                      <th>Bodega</th>
                      <th>Usuario que actualizó</th>
                      <th>Fecha de actualización</th>
                      <th>Estado</th>
                    </tr>
                  </thead><tbody>
                  <?php
                  if(!empty($bodegas)){
                    foreach ($bodegas as $b) {
                      echo '<tr>';
                      echo '<td>'.$b->idbodega->Descripcion.'</td>';
                      echo '<td>'.$b->idusuarioact->Usuario.'</td>';
                      echo '<td>'.UtilidadesVarias::textofechahora($b->Fecha_Actualizacion).'</td>';
                      echo '<td>'.UtilidadesVarias::textoestado1($b->Estado).'</td>';
                      echo '</tr>';
                    }
                  }else{
                    echo '<tr>';
                    echo '<td colspan="4">No se encontraron resultados.</td>';
                    echo '</tr>';
                  }
                  ?>
                </tbody></table>   
              </div>
            </div> 
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div><!-- /.card-body -->
    </div>
    <!-- /.nav-tabs-custom -->
  </div>
  <!-- /.col -->
</div>
<!-- /.content -->
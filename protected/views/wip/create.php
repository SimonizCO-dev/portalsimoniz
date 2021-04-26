<?php
/* @var $this WipController */
/* @var $model Wip */

?>

<div id="div_mensaje" style="display: none;"></div>

<h4>Creación de WIP</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'n_wip'=>$n_wip, 'lista_cadenas'=>$lista_cadenas, 'fecha_min' => $fecha_min)); ?>
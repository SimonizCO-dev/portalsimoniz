<?php
/* @var $this DocumentoController */
/* @var $model Documento */

if($tipo == 1){

?>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene permisos para visualizar la lista.
</div> 

<?php

}else{

?>

<div class="alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    Este usuario no tiene permisos para visualizar el documento.
</div> 

<?php

}

?>






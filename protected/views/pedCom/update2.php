<?php
/* @var $this PedComController */
/* @var $model PedCom */

?>

<script>

$(function() {

  	$("#carga_form").click(function() {

    	var opcion = confirm("¿ Esta seguro de confirmar el pedido ? ");
    	if (opcion == true) {
        	var form = $("#ped-com-form");
	    	var settings = form.data('settings') ;
	      	$("#PedCom_Estado").val(4);
			form.submit();
			loadershow();
		} 
    	
    });

    $("#rechazo_form").click(function() {

    	var opcion = confirm("¿ Esta seguro de rechazar el pedido ? ");
    	if (opcion == true) {
        	var form = $("#ped-com-form");
	    	var settings = form.data('settings') ;
	      	$("#PedCom_Estado").val(3);
			form.submit();
			loadershow();
		} 
    	
    });

 });

</script>

<h4>Revision de pedido</h4>

<?php $this->renderPartial('_form3', array('model'=>$model, 'detalle'=>$detalle)); ?>
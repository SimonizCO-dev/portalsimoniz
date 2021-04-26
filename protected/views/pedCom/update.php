<?php
/* @var $this PedComController */
/* @var $model PedCom */

?>

<script>

$(function() {

    $("#anula_form").click(function() {

        var opcion = confirm("¿ Esta seguro de anular el pedido ? ");
        if (opcion == true) {
            var form = $("#ped-com-form");
            var settings = form.data('settings') ;
            $("#PedCom_Estado").val(0);
            form.submit();
            loadershow();
        } 
        
    });

    $("#envio_form").click(function() {

        var opcion = confirm("¿ Esta seguro de enviar el pedido ? ");
        if (opcion == true) {
            var form = $("#ped-com-form");
            var settings = form.data('settings') ;
            $("#PedCom_Estado").val(2);
            form.submit();
            loadershow();
        } 
        
    });

 });

</script>

<h4>Detalle de pedido</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'detalle'=>$detalle)); ?>
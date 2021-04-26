<?php
/* @var $this DetPedComController */
/* @var $model DetPedCom */

?>

<script>

$(function() {

    $("#valida_form").click(function() {
        var form = $("#det-ped-com-form");
        var settings = form.data('settings') ;

        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
            if($.isEmptyObject(messages)) {
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });
                 
                form.submit();
                loadershow();
      
            } else {
                settings = form.data('settings'),
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });
                settings.submitting = false ;
            }
        });
    });

    $("#DetPedCom_Item").change(function() {
        
        var item = $(this).val();

        if(item != ""){
            var data = {item: item}
            $.ajax({ 
                type: "POST", 
                url: "<?php echo Yii::app()->createUrl('DetPedCom/GetUndItem'); ?>",
                data: data,
                dataType: 'json',
                success: function(data){
                    $("#info_und_emp").show();
                    $("#info_unds_sol").show();
                    $("#DetPedCom_Und_Emp").val(data.Und_Emp);
                    $("#DetPedCom_Un_Sol").val(data.Und);
                }
            });
        }else{
            $("#info_und_emp").hide();
            $("#info_unds_sol").hide();
            $("#DetPedCom_Und_Emp").val('');
            $("#DetPedCom_Un_Sol").val('');   
        }
    });

});

</script>

<h4>Registro de ítem</h4>

<?php $this->renderPartial('_form', array('model'=>$model, 'cab'=>$cab, 'detalle'=>$detalle)); ?>
<?php
/* @var $this NetworkController */
/* @var $model Network */

?>

<script type="text/javascript">

$(function() {

	$("#valida_form").click(function() {
      var form = $("#network-form");
      var settings = form.data('settings') ;
      settings.submitting = true ;
      $.fn.yiiactiveform.validate(form, function(messages) {
          if($.isEmptyObject(messages)) {
	            $.each(settings.attributes, function () {
	                $.fn.yiiactiveform.updateInput(this,messages,form); 
	            });

	            var id_1 = $('#Network_id_red_1').val();
	            var id_2 = $('#Network_id_red_2').val();
	            var segment = $('#Network_Segment').val();
              	
                var data = {id_1: id_1, id_2: id_2, segment: segment}
	  	        $.ajax({ 
	  	            type: "POST", 
	  	            url: "<?php echo Yii::app()->createUrl('network/existsegment'); ?>",
	  	            data: data,
	  	            success: function(response){

	  	                if(response == 0){
	  	                    //se encontro un segmento existente
	  	                    $('#Network_Segment_em_').html('Esta red ya esta registrada.');
	  	                    $('#Network_Segment_em_').show();
	  	                }

	  	                if(response == 1){
	  	                    //si el segmento no existe
	  	                    $.each(settings.attributes, function () {
				                $.fn.yiiactiveform.updateInput(this,messages,form); 
				            });
	            			form.submit();
                			loadershow();
	  	                }

	  	            }
	  	        });

          } else {
              settings = form.data('settings'),
              $.each(settings.attributes, function () {
                 $.fn.yiiactiveform.updateInput(this,messages,form); 
              });
              settings.submitting = false ;
          }
      });
  	});

});

</script>

<h4>Creación de segmento de red</h4>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
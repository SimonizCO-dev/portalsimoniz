<?php
/* @var $this NegContController */
/* @var $model NegCont */

//para combos de monedas
$lista_monedas = CHtml::listData($monedas, 'Id_Dominio', 'Dominio');

?>

<script type="text/javascript">

$(function() {

  	calcostofinal();

	$("#valida_form").click(function() {
      var form = $("#neg-cont-form");
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

  	$("#NegCont_Costo").change(function() {
	  var costo = $(this).val();
	  var porc_desc = $('#NegCont_Porc_Desc').val();

	  if(costo != "" && porc_desc){
	      
	    var data = {costo: costo, porc_desc: porc_desc}
	    $.ajax({ 
	        type: "POST", 
	        url: "<?php echo Yii::app()->createUrl('negCont/costofinal'); ?>",
	        data: data,
	        success: function(response){
	           $('#costo_final').text(response);
	        }
	    });

	  }else{
	    $('#costo_final').text('-');
	  }

	});

	$("#NegCont_Porc_Desc").change(function() {
	  var costo = $('#NegCont_Costo').val();
	  var porc_desc = $(this).val();

	  if(costo != "" && porc_desc){

	    var data = {costo: costo, porc_desc: porc_desc}
	    $.ajax({ 
	        type: "POST", 
	        url: "<?php echo Yii::app()->createUrl('negCont/costofinal'); ?>",
	        data: data,
	        success: function(response){
	           $('#costo_final').text(response);
	        }
	    });

	  }else{
	    $('#costo_final').text('-');
	  }

	});

});	

function calcostofinal(){
	var costo = $('#NegCont_Costo').val();
	var porc_desc = $('#NegCont_Porc_Desc').val();

	if(costo != "" && porc_desc){

		var data = {costo: costo, porc_desc: porc_desc}
		$.ajax({ 
		    type: "POST", 
		    url: "<?php echo Yii::app()->createUrl('negCont/costofinal'); ?>",
		    data: data,
		    success: function(response){
		       $('#costo_final').text(response);
		    }
		});

	}else{
		$('#costo_final').text('-');
	}
}

</script>

<h4>Actualizando negociación de contrato</h4>

<?php $this->renderPartial('_form2', array('model'=>$model, 'c'=>$c, 'lista_monedas'=>$lista_monedas)); ?>
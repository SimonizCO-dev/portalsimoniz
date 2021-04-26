<?php
/* @var $this PerfilController */
/* @var $model Perfil */

?>

<script>

$(function() {
	//funcion para cargar el tree de opciones de menu
	showloader();
	$.ajax({ 
		type: "POST", 
		url: "<?php echo Yii::app()->createUrl('perfil/setmenu'); ?>",
		dataType: 'json',
		success: function(data){
			if (data.length > 0) {
			    $.each(data, function(i1) {
			    	//nivel 1
		    		id1 = data[i1]['id'];
		    		text1 = data[i1]['text'];
		    		children1 = data[i1]['children'];
		    		$("#tree").append('<li id="li_'+id1+'"><input type="checkbox" value="'+id1+'"><span> '+text1+'</span></li>');
		    		if (children1.length > 0) {
		    			//nivel 2
		    			$("#li_"+id1+"").append('<ul id="ul_'+id1+'"></ul>');
		    			$.each(children1, function(i2) {
		    				id2 = children1[i2]['id'];
				    		text2 = children1[i2]['text'];
				    		children2 = children1[i2]['children'];
				    		$("#ul_"+id1+"").append('<li id="li_'+id2+'"><input type="checkbox" value="'+id2+'"><span> '+text2+'</span></li>');
				    		if (children2.length > 0) {
				    			//nivel 3
				    			$("#li_"+id2+"").append('<ul id="ul_'+id2+'"></ul>');
				    			$.each(children2, function(i3) {
				    				id3 = children2[i3]['id'];
						    		text3 = children2[i3]['text'];
						    		children3 = children2[i3]['children'];
						    		$("#ul_"+id2+"").append('<li id="li_'+id3+'"><input type="checkbox" value="'+id3+'"><span> '+text3+'</span></li>');
						    		if (children3.length > 0) {
						    			//nivel 4
						    			$("#li_"+id3+"").append('<ul id="ul_'+id3+'"></ul>');
						    			$.each(children3, function(i4) {
						    				id4 = children3[i4]['id'];
								    		text4 = children3[i4]['text'];
								    		children4 = children3[i4]['children'];
								    		$("#ul_"+id3+"").append('<li id="li_'+id4+'"><input type="checkbox" value="'+id4+'"><span> '+text4+'</span></li>');
						    			});	
									}	
				    			});
							}	
		    			});
					}
			    });
			}
			//se inicializa el tree
			$('#example-0 div').tree({
		        collapseUiIcon: 'ui-icon-plus',
		        expandUiIcon: 'ui-icon-minus',
		        leafUiIcon: 'ui-icon-bullet',
		    });
			//se colapsa el tree
			$(".ui-icon-minus").trigger("click");
			//se muestra el tree
			$("#tree").fadeIn();
 			hideloader();
		}
	});

	$("#valida_form").click(function() {
    	var form = $("#perfil-form");
    	var settings = form.data('settings') ;

    	var selected = '';    
	    $('input[type=checkbox]').each(function(){
	        if (this.checked) {
	            selected += $(this).val()+',';
	        }
	    });
	    var cadena = selected.slice(0,-1);
	    $('#Perfil_opciones_menu').val(cadena);
    

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
});
   	
</script>

<h4>Creación de perfil</h4>    
<?php $this->renderPartial('_form', array('model'=>$model)); ?>  
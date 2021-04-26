<?php
/* @var $this PerfilController */
/* @var $model Perfil */

?>

<script>

$(function() {
	//funcion para cargar el tree de opciones de menu
	showloader(); 
	var data = {id_perfil: <?php echo $model->Id_Perfil; ?>}
	$.ajax({ 
		type: "POST", 
		url: "<?php echo Yii::app()->createUrl('perfil/getmenu'); ?>",
		data: data,
		dataType: 'json',
		success: function(data){
			if (data.length > 0) {
			    $.each(data, function(i1) {
			    	//nivel 1
		    		id1 = data[i1]['id'];
		    		text1 = data[i1]['text'];
		    		children1 = data[i1]['children'];
		    		check1 = data[i1]['check'];
		    		if (check1 == 1) { checked1 = 'checked'; } else { checked1 = ''; }
		    		$("#tree").append('<li id="li_'+id1+'"><input type="checkbox" '+checked1+' value="'+id1+'"><span> '+text1+'</span></li>');
		    		if (children1.length > 0) {
		    			//nivel 2
		    			$("#li_"+id1+"").append('<ul id="ul_'+id1+'"></ul>');
		    			$.each(children1, function(i2) {
		    				id2 = children1[i2]['id'];
				    		text2 = children1[i2]['text'];
				    		children2 = children1[i2]['children'];
				    		check2 = children1[i2]['check'];
		    				if (check2 == 1) { checked2 = 'checked'; } else { checked2 = ''; }
				    		$("#ul_"+id1+"").append('<li id="li_'+id2+'"><input type="checkbox" '+checked2+' value="'+id2+'"><span> '+text2+'</span></li>');
				    		if (children2.length > 0) {
				    			//nivel 3
				    			$("#li_"+id2+"").append('<ul id="ul_'+id2+'"></ul>');
				    			$.each(children2, function(i3) {
				    				id3 = children2[i3]['id'];
						    		text3 = children2[i3]['text'];
						    		children3 = children2[i3]['children'];
						    		check3 = children2[i3]['check'];
		    						if (check3 == 1) { checked3 = 'checked'; } else { checked3 = ''; }
						    		$("#ul_"+id2+"").append('<li id="li_'+id3+'"><input type="checkbox" '+checked3+' value="'+id3+'"><span> '+text3+'</span></li>');
						    		if (children3.length > 0) {
						    			//nivel 4
						    			$("#li_"+id3+"").append('<ul id="ul_'+id3+'"></ul>');
						    			$.each(children3, function(i4) {
						    				id4 = children3[i4]['id'];
								    		text4 = children3[i4]['text'];
								    		children4 = children3[i4]['children'];
								    		check4 = children3[i4]['check'];
		    								if (check4 == 1) { checked4 = 'checked'; } else { checked4 = ''; }
								    		$("#ul_"+id3+"").append('<li id="li_'+id4+'"><input type="checkbox" '+checked4+' value="'+id4+'"><span> '+text4+'</span></li>');
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

<h4>Actualización de perfil</h4> 
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
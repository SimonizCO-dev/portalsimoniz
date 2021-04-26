<?php
/* @var $this CAceleradorCmsController */
/* @var $model CAceleradorCms */

//para combo de estados de tipos
$lista_tipos = CHtml::listData($tipos, 'Id_Dominio', 'Dominio'); 

//para combo de aceleradores
$lista_aceler = CHtml::listData($aceler, 'Id_Dominio', 'Dominio');  

//para combo de estados de planes
$lista_planes = CHtml::listData($planes, 'Id_Plan', 'Plan_Descripcion'); 

?>

<script type="text/javascript">

$(function() {

    $("#valida_form").click(function() {
        var form = $("#cptj-acelerador-form");
        var settings = form.data('settings') ;

        var valid = 1;
        var acelerador = $("#CPtjAcelerador_ID_ACELERADOR").val();

        if(acelerador == ""){

            valid = 0;

        }else{
            if(acelerador == <?php echo Yii::app()->params->ac_item; ?>){
                
                var item = $("#CPtjAcelerador_ITEM").val();

                if(item == ""){
                    $('#CPtjAcelerador_ITEM_em_').html('Item es requerido.');
                    $('#CPtjAcelerador_ITEM_em_').show();
                    valid = 0;
                }
            }

            if(acelerador == <?php echo Yii::app()->params->ac_criterio; ?>){

                var plan = $("#CPtjAcelerador_ID_PLAN").val();
                var criterio = $("#CPtjAcelerador_CRITERIO").val();

                if(plan == "" || criterio == ""){
                    
                    valid = 0;

                }
            }

            $('#CPtjAcelerador_TIPO_em_').html();
            $('#CPtjAcelerador_TIPO_em_').hide();
        }

        settings.submitting = true ;
        $.fn.yiiactiveform.validate(form, function(messages) {
            if($.isEmptyObject(messages) && valid == 1) {
                $.each(settings.attributes, function () {
                    $.fn.yiiactiveform.updateInput(this,messages,form); 
                });

                //var porc = $("#CPtjAcelerador_PORCENTAJE").val();
                var fecha_inicial = $("#CPtjAcelerador_FECHA_INICIAL").val();
                var fecha_final = $("#CPtjAcelerador_FECHA_FINAL").val();
                var tipo = $("#CPtjAcelerador_TIPO").val();

                if(acelerador == <?php echo Yii::app()->params->ac_item; ?>){
                    item = $("#CPtjAcelerador_ITEM").val();
                    plan = 0
                    criterio = 0;
                }else{
                    item = 0;
                    plan = $("#CPtjAcelerador_ID_PLAN").val();
                    criterio = $("#CPtjAcelerador_CRITERIO").val();   
                }


                var data = {tipo: tipo, acelerador: acelerador, item: item, plan: plan, criterio: criterio, fecha_inicial: fecha_inicial, fecha_final: fecha_final}
                $.ajax({ 
                  type: "POST", 
                  url: "<?php echo Yii::app()->createUrl('CPtjAcelerador/verifconfig'); ?>",
                  data: data,
                  dataType: 'json',
                  success: function(data){
                    var valid =data['valid'];
                    var id =data['id'];
                    if(valid == 0){
                        $('#mensaje').text('Ya existe un porcentaje de acelerador con estos parametros (ID '+id+'), para poder crearla debe inactivar el registro indicado.');
                        $('#div_mensaje').show();
                        settings.submitting = false ;
                    }else{
                        //se envia el form
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

                if(acelerador == ""){
                    $('#CPtjAcelerador_TIPO_em_').html('Tipo es requerido.');
                    $('#CPtjAcelerador_TIPO_em_').show();
                }else{
                    if(acelerador == <?php echo Yii::app()->params->ac_item; ?>){
                        
                        var item = $("#CPtjAcelerador_ITEM").val();

                        if(item == ""){
                            $('#CPtjAcelerador_ITEM_em_').html('Item es requerido.');
                            $('#CPtjAcelerador_ITEM_em_').show();
                        }else{
                            $('#CPtjAcelerador_ITEM_em_').html();
                            $('#CPtjAcelerador_ITEM_em_').hide();
                        }
                    }

                    if(acelerador == <?php echo Yii::app()->params->ac_criterio; ?>){

                        var plan = $("#CPtjAcelerador_ID_PLAN").val();
                        var criterio = $("#CPtjAcelerador_CRITERIO").val();

                        if(plan == "" || criterio == ""){
                            
                            if(plan == ""){
                                $('#CPtjAcelerador_ID_PLAN_em_').html('Plan es requerido.');
                                $('#CPtjAcelerador_ID_PLAN_em_').show();
                            }else{
                                $('#CPtjAcelerador_ID_PLAN_em_').html();
                                $('#CPtjAcelerador_ID_PLAN_em_').hide();
                            }

                            if(criterio == ""){
                                $('#CPtjAcelerador_CRITERIO_em_').html('Criterio es requerido.');
                                $('#CPtjAcelerador_CRITERIO_em_').show();
                            }else{
                                $('#CPtjAcelerador_CRITERIO_em_').html();
                                $('#CPtjAcelerador_CRITERIO_em_').hide();
                            }

                        }else{
                            $('#CPtjAcelerador_ID_PLAN_em_').html();
                            $('#CPtjAcelerador_ID_PLAN_em_').hide();
                            $('#CPtjAcelerador_CRITERIO_em_').html();
                            $('#CPtjAcelerador_CRITERIO_em_').hide();
                        }
                    }

                    $('#CPtjAcelerador_TIPO_em_').html();
                    $('#CPtjAcelerador_TIPO_em_').hide();
                }
          }
      });
    });

	//variables para el lenguaje del datepicker
	$.fn.datepicker.dates['es'] = {
	  days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
	  daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
	  daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
	  months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
	  monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
	  today: "Hoy",
	  clear: "Limpiar",
	  format: "yyyy-mm-dd",
	  titleFormat: "MM yyyy",
	  weekStart: 1
	};

	$("#CPtjAcelerador_FECHA_INICIAL").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	}).on('changeDate', function (selected) {
       hidemsg();
	   var minDate = new Date(selected.date.valueOf());
	   $('#CPtjAcelerador_FECHA_FINAL').datepicker('setStartDate', minDate);
	});

	$("#CPtjAcelerador_FECHA_FINAL").datepicker({
	  language: 'es',
	  autoclose: true,
	  orientation: "right bottom",
	  startDate: '<?php echo $model->FECHA_INICIAL; ?>',
	}).on('changeDate', function (selected) {
       hidemsg();   
	   var maxDate = new Date(selected.date.valueOf());
	   $('#CPtjAcelerador_FECHA_INICIAL').datepicker('setEndDate', maxDate);
	});



    $('#CPtjAcelerador_ID_ACELERADOR').change(function() {
        viewfields($(this).val());
        hidemsg();
    });

    $('#CPtjAcelerador_PORCENTAJE').change(function() {
        hidemsg();
    });

    $('#CPtjAcelerador_ID_PLAN').change(function() {
        
        hidemsg();
        $("#CPtjAcelerador_CRITERIO").html('');
        $("#CPtjAcelerador_CRITERIO").append('<option value=""></option>');  

        if($(this).val() != ""){
            $('#div_criterio').show();
            loadcriterios($(this).val());
        }else{
            $('#div_criterio').hide();
        }
    });

    $('#CPtjAcelerador_CRITERIO').change(function() {
        hidemsg();
    });

});


function viewfields(acelerador){

    if(acelerador != ""){
        
        if(acelerador == <?php echo Yii::app()->params->ac_item; ?>){
            $('#div_item').show();  
            $('#div_plan').hide();
            $('#CPtjAcelerador_ID_PLAN').val('').trigger('change');
            $('#div_criterio').hide(); 
            $("#CPtjAcelerador_CRITERIO").html('');
            $("#CPtjAcelerador_CRITERIO").append('<option value=""></option>');
            $('#CPtjAcelerador_CRITERIO').val('').trigger('change');    
        }

        if(acelerador == <?php echo Yii::app()->params->ac_criterio; ?>){
            $('#div_item').hide();
            $('#CPtjAcelerador_ITEM').val('').trigger('change');
            $('#s2id_CPtjAcelerador_ITEM span').html("");
           
            $('#div_plan').show();

            $('#div_criterio').hide(); 
        }   
    }else{
        $('#div_item').hide();
        $('#CPtjAcelerador_ITEM').val('').trigger('change');
        $('#s2id_CPtjAcelerador_ITEM span').html("");
        $('#div_plan').hide();
        $('#CPtjAcelerador_ID_PLAN').val('').trigger('change');
        $('#div_criterio').hide(); 
        $("#CPtjAcelerador_CRITERIO").html('');
        $("#CPtjAcelerador_CRITERIO").append('<option value=""></option>');
        $('#CPtjAcelerador_CRITERIO').val('').trigger('change');  
    }

}

function loadcriterios(plan){

    
    var data = {plan: plan}
    $.ajax({ 
      type: "POST", 
      url: "<?php echo Yii::app()->createUrl('CPtjAcelerador/loadcriterios'); ?>",
      data: data,
      dataType: 'json',
      success: function(data){ 
        var criterios = data;
        $("#CPtjAcelerador_CRITERIO").html('');
        $("#CPtjAcelerador_CRITERIO").append('<option value=""></option>');
        $('#CPtjAcelerador_CRITERIO').val('').trigger('change');
        $.each(criterios, function(i,item){
            $("#CPtjAcelerador_CRITERIO").append('<option value="'+criterios[i].id+'">'+criterios[i].text+'</option>');
        });

        $("#div_criterio").show();

      }  
    });

}

function hidemsg(){
    $('#mensaje').text('');
    $('#div_mensaje').hide();
}

</script>

<div class="alert alert-warning alert-dismissible" id="div_mensaje" style="display: none;">
    <h5><i class="icon fas fa-info-circle"></i>Info</h5>
    <p id="mensaje"></p>
</div>

<h4>Creación de porcentaje acelerador</h4>   

<?php $this->renderPartial('_form', array('model'=>$model, 'lista_tipos'=>$lista_tipos, 'lista_aceler' => $lista_aceler, 'lista_planes'=>$lista_planes)); ?>
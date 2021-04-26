<?php
/* @var $this ElementoEmpleadoController */
/* @var $model ElementoEmpleado */

?>

<h4>Entrega de elementos / herramientas por empleado</h4>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'elemento-empleado-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<div class="row">
    <div class="col-sm-8">
        <div class="form-group">
            <?php echo $form->label($model,'Id_Empleado', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'Id_Empleado', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'Id_Contrato', array('class' => 'form-control', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_contrato)); ?>
            <?php echo $form->hiddenField($model,'Id_Empleado', array('class' => 'form-control', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $e)); ?>
            <?php echo '<p>'.$empleado.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'unidad_gerencia', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'unidad_gerencia', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'unidad_gerencia', array('class' => 'form-control', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_unidad_gerencia)); ?>
            <?php echo '<p>'.$unidad_gerencia.'</p>'; ?> 
        </div>
    </div>   
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'area', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'area', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'area', array('class' => 'form-control', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_area)); ?>
            <?php echo '<p>'.$area.'</p>'; ?> 
        </div>
    </div> 
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'subarea', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'subarea', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'subarea', array('class' => 'form-control', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_subarea)); ?>
            <?php echo '<p>'.$subarea.'</p>'; ?> 
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->label($model,'cargo', array('class' => 'control-label')); ?>
            <?php echo $form->error($model,'cargo', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->hiddenField($model,'cargo', array('class' => 'form-control', 'maxlength' => '50', 'autocomplete' => 'off', 'value' => $id_cargo)); ?>
            <?php echo '<p>'.$cargo.'</p>'; ?> 
        </div>
    </div>   
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Elementos pend. por entrega</label>
            <?php echo $form->hiddenField($model,'elementos', array('class' => 'form-control', 'autocomplete' => 'off')); ?>
            <div id="op">
                <div>
                    <ul id="tree" style="display: none;">
                    </ul>
                </div>
            </div>            
        </div>
    </div>
     <div class="col-sm-12">
        <div class="form-group">
            <label>Herramientas pend. por entrega</label>
            <?php echo $form->hiddenField($model,'herramientas', array('class' => 'form-control', 'autocomplete' => 'off')); ?>
            <div id="op2">
                <div>
                    <ul id="tree2" style="display: none;">
                    </ul>
                </div>
            </div>            
        </div>
    </div> 
</div>

<div class="row" id="error_elem" style="display: none;">
    <div class="col-sm-12">
        <div class="badge badge-warning float-right" style="">Debe seleccionar por lo menos 1 elemento / herramienta.</div>   
    </div>
</div>

<div class="row mb-2" id="buttons">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=empleado/entrega' ?>';"><i class="fa fa-reply"></i> Volver</button>

        <button type="submit" class="btn btn-primary btn-sm" onclick="return valida_opciones(event);" id="btn_submit" style="display: none;"><i class="fas fa-save"></i> Entregar item(s) selecionado(s)</button>
    </div>
</div>

<?php $this->endWidget(); ?>

<script>

$(function() {

    $('.ajax-loader').fadeIn('fast');

    //funcion para cargar el tree de los elementos asignados al empleado
    var data = {id_contrato: <?php echo $id_contrato; ?>}
    $.ajax({ 
        type: "POST", 
        url: "<?php echo Yii::app()->createUrl('elemento/getelementospendentempleado'); ?>",
        data: data,
        dataType: 'json',
        success: function(data){
            if (data.length > 0) {
                $.each(data, function(indice0) {
                    id0 = data[indice0]['id'];
                    text0 = data[indice0]['text'];
                    children0 = data[indice0]['children'];
                    $("#tree").append('<li id="li_e_'+id0+'"><input type="checkbox" value="'+id0+'"><span> '+text0+'</span></li>');
                    if (children0.length > 0) {
                        //nivel 2
                        $("#li_e_"+id0+"").append('<ul id="ul_e_'+id0+'"></ul>');
                        $.each(children0, function(indice1) {
                            id1 = children0[indice1]['id'];
                            text1 = children0[indice1]['text'];
                            $("#ul_e_"+id0+"").append('<li id="li_e_'+id1+'"><input type="checkbox" value="'+id1+'" class="ele"> <span> '+text1+'</span></li>');        
                        });
                    }
                });

                //se inicializa el tree
                $('#op div').tree({
                    collapseUiIcon: 'ui-icon-plus',
                    expandUiIcon: 'ui-icon-minus',
                    leafUiIcon: 'ui-icon-bullet',
                });
                //se colapsa el tree
                $(".ui-icon-minus").trigger("click");
                //se muestra el tree
                $("#tree").fadeIn();

            }else{
                $('#op div').html('<p>No hay elementos pend. por entrega y/o este usuario no puede entregar elementos con área - subárea diferentes a las que tiene configuradas.</p>');
            }

            $('.ajax-loader').fadeOut('fast');
        }
    });

    //funcion para cargar el tree de las herramientas asignadas al empleado
    var data = {id_contrato: <?php echo $id_contrato; ?>}
    $.ajax({ 
        type: "POST", 
        url: "<?php echo Yii::app()->createUrl('herramienta/getherramientaspendentempleado'); ?>",
        data: data,
        dataType: 'json',
        success: function(data){
            if (data.length > 0) {
                $.each(data, function(indice0) {
                    id0 = data[indice0]['id'];
                    text0 = data[indice0]['text'];
                    children0 = data[indice0]['children'];
                    $("#tree2").append('<li id="li_h_'+id0+'"><input type="checkbox" value="'+id0+'"><span> '+text0+'</span></li>');
                    if (children0.length > 0) {
                        //nivel 2
                        $("#li_h_"+id0+"").append('<ul id="ul_h_'+id0+'"></ul>');
                        $.each(children0, function(indice1) {
                            id1 = children0[indice1]['id'];
                            text1 = children0[indice1]['text'];
                            $("#ul_h_"+id0+"").append('<li id="li_h_'+id1+'"><input type="checkbox" value="'+id1+'" class="her"> <span> '+text1+'</span></li>');        
                        });
                    }
                });
            
                //se inicializa el tree
                $('#op2 div').tree({
                    collapseUiIcon: 'ui-icon-plus',
                    expandUiIcon: 'ui-icon-minus',
                    leafUiIcon: 'ui-icon-bullet',
                });
                //se colapsa el tree
                $(".ui-icon-minus").trigger("click");
                //se muestra el tree
                $("#tree2").fadeIn();

            }else{
                $('#op2 div').html('<p>No hay herramientas pend. por entrega.</p>');
            }
        }
    });

    $('#op').click(function(e) {  
        $('#error_elem').hide();
    });

    $('#op2').click(function(e) {  
        $('#error_elem').hide();
    });

});

function valida_opciones(){
    
    //elementos
    var checkbox_selected_e = '';
    
    $('input.ele[type=checkbox]').each(function(){
        if (this.checked) {
            id_area_elem = $(this).val();
            checkbox_selected_e += id_area_elem+',';        
        }
    });

    var cadena_c_e = checkbox_selected_e.slice(0,-1);
    
    $('#ElementoEmpleado_elementos').val(cadena_c_e);

    ele = $('#ElementoEmpleado_elementos').val();

     //herramientas
    var checkbox_selected_h = '';
    
    $('input.her[type=checkbox]').each(function(){
        if (this.checked) {

            id_herramienta = $(this).val();
            checkbox_selected_h += id_herramienta+',';
            
        }
    });

    var cadena_c_h = checkbox_selected_h.slice(0,-1);

    $('#ElementoEmpleado_herramientas').val(cadena_c_h);

    her = $('#ElementoEmpleado_herramientas').val();

    if(ele != "" || her != ""){
        $('#buttons').hide();
        loadershow();
        return true;
    }else{
        $('#error_elem').show();
        return false;   
    }   
}

$(document).ajaxComplete(function() {
    var data_tree = $("#tree li").length; 
    var data_tree2 = $("#tree2 li").length; 

    if(data_tree == 0 && data_tree2 == 0){
        $("#btn_submit").hide();
    }else{
        $("#btn_submit").show();
    }
});
    
</script>


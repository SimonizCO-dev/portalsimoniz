<?php
/* @var $this PromocionController */
/* @var $model Promocion */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'promocion-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

<div id="div_mensaje" style="display: none;">
</div>

<div class="row">    
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->error($model,'Id_Item_Padre', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'Id_Item_Padre'); ?>
            <?php echo $form->textField($model,'Id_Item_Padre'); ?>
            <?php echo $form->hiddenField($model,'Id_Item_Hijo'); ?>
            <?php echo $form->hiddenField($model,'Cantidad'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Promocion_Id_Item_Padre',
                    'options'  => array(
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('reporte/SearchItem'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                   
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax2("Promocion_Id_Item_Padre");  return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax2(\'Promocion_Id_Item_Padre\')\">Limpiar campo</button>"; }',
                    ),
                ));
            ?>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->error($model,'comp', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'comp'); ?>
            <?php echo $form->textField($model,'comp'); ?>
            <?php
                $this->widget('ext.select2.ESelect2', array(
                    'selector' => '#Promocion_comp',
                    'options'  => array(
                        'minimumInputLength' => 3,
                        'width' => '100%',
                        'language' => 'es',
                        'ajax' => array(
                            'url' => Yii::app()->createUrl('reporte/SearchItem'),
                            'dataType'=>'json',
                            'data'=>'js:function(term){return{q: term};}',
                            'results'=>'js:function(data){ return {results:data};}'                
                        ),
                        'formatNoMatches'=> 'js:function(){ clear_select2_ajax("Promocion_comp"); return "No se encontraron resultados"; }',
                        'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'Promocion_comp\')\">Limpiar campo</button>"; }',
                    ),
                ));
                ?>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->error($model,'cant', array('class' => 'badge badge-warning float-right')); ?>
            <?php echo $form->label($model,'cant'); ?>
            <?php echo $form->numberField($model,'cant', array('class' => 'form-control form-control-sm', 'autocomplete' => 'off')); ?>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-sm-6">  
        <button type="button" class="btn btn-primary btn-sm" onclick="location.href = '<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=promocion/admin'; ?>';"><i class="fa fa-reply"></i> Volver</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="add_item();"><i class="fa fa-plus" ></i> Agregar registro</button>
    </div>
</div>

<div id="contenido"></div>

<div class="row mb-2" id="btn_save" style="display: none;">
    <div class="col-sm-6">  
        <button type="submit" class="btn btn-primary btn-sm" onclick="return valida_opciones(event);"><i class="fas fa-save"></i> Guardar</button>
    </div>
</div>
    
<?php $this->endWidget(); ?>

<script type="text/javascript">

$(function() {

    $('form').keypress(function(e){   
      if(e == 13){
          return false;
      }
    });

    $('input').keypress(function(e){
      if(e.which == 13){
          return false;
      }
    });

});

function clear_select2_ajax2(id){
    $('#'+id+'').val('').trigger('change');
    $('#s2id_'+id+' span').html("");
    $('#contenido').html('');
    $('#btn_save').hide();  
}

function add_item(){

    limp_div_msg();

    var promocion = $('#Promocion_Id_Item_Padre').val();
    var id_comp = $('#Promocion_comp').val();
    var comp = $('#s2id_Promocion_comp span').html();
    var cant = $('#Promocion_cant').val();
    
    if(promocion != "" && id_comp != "" && cant != ""){

        $(".ajax-loader").fadeIn('fast');
        
        //se consulta si el item ya esta creado para la promocion
        var data = {promocion: promocion, id_comp: id_comp}
        $.ajax({ 
            type: "POST", 
            url: "<?php echo Yii::app()->createUrl('promocion/evaluarexistencia'); ?>",
            data: data,
            dataType: 'json',
            success: function(data){
                if (data == 0) {

                    var div_contenido = $('#contenido');

                    var i = id_comp;
                    var tr = $("#tr_"+i).length;

                    if(!tr){
                    
                        var cant_f = $(".tr_items").length;

                        if(cant_f == 0){
                            div_contenido.append('<table class="table table-sm table-hover" id="table_items"><thead><tr><th>Componente</th><th>Cantidad</th><th></th></tr></thead><tbody></tbody></table>');
                        }

                        var tabla = $('#table_items');

                        tabla.append('<tr class="tr_items" id="tr_'+i+'"><td><input type="hidden" class="items" value="'+i+'"><input type="hidden" id="comp_'+i+'" value="'+i+'"><p>'+comp+'</p></td><td align="right"><input type="hidden" id="cant_'+i+'" value="'+cant+'"><p>'+cant+'</p></td><td><button type="button" class="btn btn-danger btn-sm btn-rep delete"><i class="fas fa-trash-alt"></i></button></td></tr>');

                        //se resetean los campos para seguir recibiendo detalles
                        $('#btn_save').show(); 
                        $('#Promocion_comp').val('').trigger('change');
                        $('#s2id_Promocion_comp span').html("");
                        $('#Promocion_cant').val(''); 
                        $(".ajax-loader").fadeOut('fast');

                    }else{
                        $('html, body').animate({scrollTop:0}, 'fast');
                        $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                        $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-exclamation-triangle"></i>Info</h5>Ya existe este componente en el registro actual.');    
                        $("#div_mensaje").fadeIn('fast');
                        $(".ajax-loader").fadeOut('fast');
                    }

                }else{
                    $('html, body').animate({scrollTop:0}, 'fast');
                    $("#div_mensaje").addClass("alert alert-warning alert-dismissible");
                    $("#div_mensaje").html('<button type="button" class="close" aria-hidden="true" onclick="limp_div_msg();">×</button><h5><i class="icon fas fa-exclamation-triangle"></i>Info</h5>Ya existe un registro con la misma combinación (Promoción - Componente).');    
                    $("#div_mensaje").fadeIn('fast');
                    $(".ajax-loader").fadeOut('fast');    
                } 
            }
        });

    }else{
        if(promocion == ""){
            $('#Promocion_Id_Item_Padre_em_').html('Promoción es requerido.');
            $('#Promocion_Id_Item_Padre_em_').show(); 
        }
        if(id_comp == ""){
            $('#Promocion_comp_em_').html('Componente es requerido.');
            $('#Promocion_comp_em_').show(); 
        }

        if(cant == ""){
            $('#Promocion_cant_em_').html('Cantidad es requerido.');
            $('#Promocion_cant_em_').show();    
        }
    }
}


$("body").on("click", ".delete", function (e) {
    $(this).parent().parent("tr").remove();
    var cant = $(".tr_items").length;
    
    if(cant == 0){
        $('#contenido').html('');
        $('#btn_save').hide();  
    }else{
        $('#btn_save').show();  
    }

});

function valida_opciones(){
    
    var comp_selected = '';
    var cant_selected = ''; 
    
    $('#btn_save').hide();
    limp_div_msg();
    $(".ajax-loader").fadeIn('fast');

    $("input.items").each(function() {

            comp = $(this).val();
            cant = $('#cant_'+comp).val();

            comp_selected += comp+',';
            cant_selected += cant+',';         

    });

    var cadena_comp = comp_selected.slice(0,-1);
    var cadena_val = cant_selected.slice(0,-1);
    
    $('#Promocion_Id_Item_Hijo').val(cadena_comp);
    $('#Promocion_Cantidad').val(cadena_val);

    return true;   
}

</script>
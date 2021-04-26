<?php

// setup scriptmap for jQuery, jQuery UI 1.11.4 and Bootstrap 4
$cs = Yii::app()->clientScript;
$cs->scriptMap["jquery.js"] =  Yii::app()->theme->baseUrl."/plugins/jquery/jquery.min.js";
$cs->scriptMap["jquery.min.js"] = $cs->scriptMap["jquery.js"];
$cs->scriptMap["jquery-ui.min.js"] = Yii::app()->theme->baseUrl."/plugins/jquery-ui/jquery-ui.min.js";

// register js files
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile(Yii::app()->theme->baseUrl."/plugins/bootstrap/js/bootstrap.bundle.min.js", CClientScript::POS_END);
//$cs->registerScriptFile(Yii::app()->theme->baseUrl . "/assets/js/main.js", CClientScript::POS_END);

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
  <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Jquery ui theme -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jquery-ui/themes/ui-lightness/jquery-ui.css"/>
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <!--<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">-->
  <!-- Jquery tree -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jquery-tree/src/css/jquery.tree.css"/>
</head>

<?php if(!Yii::app()->user->isGuest) { ?>

<script type="text/javascript">

$(function() {

  //funcion para cargar y mostrar las opciones de menu
  $.ajax({ 
    type: "POST",
    url: "<?php echo Yii::app()->createUrl('menu/loadmenu'); ?>", 
    dataType: 'json',
    success: function(data){
      if (data.length > 0) {
          $.each(data, function(indice0) {
            //nivel 1
            id0 = data[indice0]['id'];
            text0 = data[indice0]['text'];
            long_text0 = data[indice0]['long_text'];
            dd0 = data[indice0]['dd'];
            children0 = data[indice0]['children'];
            link0 = data[indice0]['link'];
            icon0 = data[indice0]['icon'];

            if(long_text0 != null && long_text0 != ""){ tag_title0 = 'title="'+long_text0+'"'; }else{ tag_title0 = ''; }

            if(dd0 == '1'){ tag_onclick0 = 'onclick="loadershow()"'; }else{ tag_onclick0 = '';}

            $("#sidebar-menu").append('<li id="me_li_'+id0+'"><a href="'+link0+'" id="me_a_'+id0+'" class="nav-link menu" '+tag_title0+' '+tag_onclick0+'><i class="nav-icon '+icon0+'"></i> <p id="p_'+id0+'">'+text0+'</p></a></li>');
            if (children0.length > 0) {
              //nivel 2
              $("#me_li_"+id0+"").addClass("nav-item has-treeview");
              $("#p_"+id0+"").append('<i class="right fas fa-angle-left"></i>');
              $("#me_li_"+id0+"").append('<ul class="nav nav-treeview" id="me_ul_'+id0+'"></ul>');
              $.each(children0, function(indice1) {
                id1 = children0[indice1]['id'];
                text1 = children0[indice1]['text'];
                long_text1 = children0[indice1]['long_text'];
                dd1 = children0[indice1]['dd'];
                children1 = children0[indice1]['children'];
                link1 = children0[indice1]['link'];
                icon1 = children0[indice1]['icon'];

                if(long_text1 != null & long_text1 != ""){ tag_title1 = 'title="'+long_text1+'"'; }else{ tag_title1 = ''; }

                if(dd1 == '1'){ tag_onclick1 = 'onclick="loadershow()"'; }else{ tag_onclick1 = '';}

                $("#me_ul_"+id0+"").append('<li id="me_li_'+id1+'"><a href="'+link1+'" id="me_a_'+id1+'" class="nav-link menu" '+tag_title1+' '+tag_onclick1+'><i class="nav-icon '+icon1+'"></i> <p id="p_'+id1+'">'+text1+'</p></a></li>');
                if (children1.length > 0) {
                  //nivel 3
                  $("#me_li_"+id1+"").addClass("nav-item has-treeview");
                  $("#p_"+id1+"").append('<i class="right fas fa-angle-left"></i>');
                  $("#me_li_"+id1+"").append('<ul class="nav nav-treeview" id="me_ul_'+id1+'"></ul>');
                  $.each(children1, function(indice2) {
                    id2 = children1[indice2]['id'];
                    text2 = children1[indice2]['text'];
                    long_text2 = children1[indice2]['long_text'];
                    dd2 = children1[indice2]['dd'];
                    children2 = children1[indice2]['children'];
                    link2 = children1[indice2]['link'];
                    icon2 = children1[indice2]['icon'];

                    if(long_text2 != null && long_text2 != ""){ tag_title2 = 'title="'+long_text2+'"'; }else{ tag_title2 = ''; }

                    if(dd2 == '1'){ tag_onclick2 = 'onclick="loadershow()"'; }else{ tag_onclick2 = '';}

                    $("#me_ul_"+id1+"").append('<li id="me_li_'+id2+'" class="nav-item"><a href="'+link2+'" id="me_a_'+id2+'" class="nav-link menu" '+tag_title2+' '+tag_onclick2+'><i class="nav-icon '+icon2+'"></i> <p>'+text2+'</p></a></li>'); 
                  });
                }else{
                 $("#me_li_"+id1+"").addClass("nav-item"); 
                } 
              });
            }else{
              $("#me_li_"+id0+"").addClass("nav-item");

            }
          });
          $("#sidebar-menu").fadeIn('fast');
          $('[data-toggle="tooltip"]').tooltip();
      } 
    }
  });
});

</script>


<body class="sidebar-mini layout-fixed control-sidebar-open small">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     
      <?php 

      $fact_pend_rev = UtilidadesUsuario::factareapend();

      if($fact_pend_rev == 0){
        $ind = $fact_pend_rev;
        $est = "success";
        $msg = "No hay facturas";
        $url = "#";
      }

      if($fact_pend_rev > 0 && $fact_pend_rev <=9){
        $ind = $fact_pend_rev;
        $est = "warning";
        $msg = "Hay facturas pendientes por revisar";
        $url = Yii::app()->getBaseUrl(true)."/index.php?r=factCont/admin2";
      }

      if($fact_pend_rev >= 10){
        $ind = $fact_pend_rev;
        $est = "danger";
        $msg = "Hay facturas pendientes por revisar";
        $url = Yii::app()->getBaseUrl(true)."/index.php?r=factCont/admin2";
      }

      ?>

      <?php if($fact_pend_rev > -1){ ?>

      <li class="nav-item">
        <a class="nav-link" href="<?php echo $url ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $msg; ?>">
          <i class="nav-icon fas fa-file-invoice"></i> 
          <span class="badge badge-<?php echo $est ?> navbar-badge"><?php echo $ind ?></span>
        </a>
      </li>

      <?php } ?>

      <?php 

      /*$corresp_pend_rev = UtilidadesUsuario::correspareapend();

      if($corresp_pend_rev == 0){
        $ind = $corresp_pend_rev;
        $est = "success";
        $msg = "No hay correspondencia";
        $url = "#";
      }

      if($corresp_pend_rev > 0 && $corresp_pend_rev <=9){
        $ind = $corresp_pend_rev;
        $est = "warning";
        $msg = "Hay correspondencia pendiente por revisar";
        $url = Yii::app()->getBaseUrl(true)."/index.php?r=corresp/admin2"; 
      }

      if($corresp_pend_rev >= 10){
        $ind = $corresp_pend_rev;
        $est = "danger";
        $msg = "Hay correspondencia pendiente por revisar";
        $url = Yii::app()->getBaseUrl(true)." /index.php?r=corresp/admin2"; 
      }*/

      ?>

      <!--
      <li class="nav-item">
        <a class="nav-link" href="<?php //echo $url ?>" data-toggle="tooltip" data-placement="bottom" title="<?php //echo $msg; ?>">
          <i class="nav-icon fas fa-inbox"></i> 
          <span class="badge badge-<?php //echo $est ?> navbar-badge"><?php //echo $ind ?></span>
        </a>
      </li>
      -->

      <li class="nav-item">
        <a class="nav-link" href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/info'; ?>" data-toggle="tooltip" data-placement="bottom" title="Acerca de <?php echo CHtml::encode(Yii::app()->name); ?>">
          <i class="nav-icon fas fa-question-circle"></i> 
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/profile'; ?>" data-toggle="tooltip" data-placement="bottom" title="Configuración de usuario">
          <i class="fas fa-user-cog"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/logout'; ?>" data-toggle="tooltip" data-placement="bottom" title="Salir de la aplicación">
          <i class="nav-icon fas fa-sign-out-alt"></i>
        </a>
      </li>      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/info'; ?>" class="brand-link navbar-gray-dark elevation-4">
      <img src="<?php echo Yii::app()->theme->baseUrl; ?>/logo.png"
           alt="<?php echo CHtml::encode(Yii::app()->name); ?>"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo CHtml::encode(Yii::app()->name); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php if(Yii::app()->user->getState('avatar_user') == 1) { ?>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/w.png" class="img-circle elevation-2" alt="Avatar">
          <?php }else{ ?>
            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/m.png" class="img-circle elevation-2" alt="Avatar">
          <?php } ?>          
        </div>
        <div class="info">
          <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/profile'; ?>" class="d-block"><?php echo Yii::app()->user->getState('username_user'); ?></a>
          <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/profile'; ?>" class="d-block"><?php echo Yii::app()->user->getState('name_user'); ?></a>
        </div>
      </div>

      <!-- Sidebar Menu  -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" id="sidebar-menu" role="menu" data-accordion="false" style="display: none; ">
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="ajax-loader" style="display: none;">
    </div>
    <!-- Main content -->
    <section class="content" style="padding-top: 2%">
      <div class="container-fluid">
        
        <?php if (!$this->menu): ?>

            <div class="row">
                <div class="col-lg-12">

                    <?php echo $content; ?>
                </div>
            </div>

        <?php else: ?>

            <div class="row">
                <div class="col-lg-10">
                    <?php echo $content; ?>
                </div>
                <div class="col-lg-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">Menu</div>
                            <?php
                            $this->widget('zii.widgets.CMenu', array(
                                'items'=>$this->menu,
                                'htmlOptions'=>array('class'=>'nav nav-pills nav-stacked'),
                            ));
                            ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>

      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <!-- <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div> -->
    <strong>© <?php echo CHtml::encode(Yii::app()->name) ?> - SIMONIZ <?php echo date('Y'); ?></strong>
  </footer>

</div>
<!-- ./wrapper -->

<?php } else { ?>

<body class="hold-transition login-page">

    <?php echo $content; ?>

<?php } ?>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- ChartJS -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jqvmap/maps/jquery.vmap.world.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/moment/moment.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/dist/js/adminlte.js"></script>
<!-- Jquery tree -->
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jquery-tree/src/js/jquery.tree.js"></script>

</body>

</html>

<style type="text/css">

/*Modificación botones de la grid yii*/
.actions{
  padding-left: 2%;
  padding-right: 2%; 
  font-size: 11px;
}

.actions:hover {
  transform: scale(1.2);
}

/*Estilos ventana loader*/

.ajax-loader {
  position:   fixed;
  z-index:    2000;
  top:        0;
  left:       0;
  height:     100%;
  width:      100%;
  background: rgba( 255, 255, 255, 1 ) 
  url('<?php echo Yii::app()->getBaseUrl(true); ?>/images/loading.gif') 50% 50% no-repeat;
}

td.button-column {
  white-space: nowrap;
}

.select2-results .select2-highlighted {
  background-image: linear-gradient(to bottom,#08c,#04c);

}

.select2-container-active .select2-choice, .select2-container-active .select2-choices{
  border: 1px solid #aaa !important;
  border-color: #aaa;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(193, 193, 193, 0.6);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(76, 76, 76, 0.6);
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
  border: 1px solid #aaa !important;
  border-color: #aaa;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(193, 193, 193, 0.6);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(76, 76, 76, 0.6);
}

.select2-results .select2-no-results, .select2-results .select2-searching, .select2-results .select2-selection-limit {
    background: none !important;
}

.datepicker table tr td.active, .datepicker table tr td.active.disabled, .datepicker table tr td.active.disabled:hover, .datepicker table tr td.active:hover {
  /*background-image: -moz-linear-gradient(to bottom,#212529,#212529);
  background-image: -ms-linear-gradient(to bottom,#212529,#212529);
  background-image: -webkit-gradient(linear,0 0,0 100%,from(#6c757d),to(#343a40));*/
  background-image: linear-gradient(to bottom,#08c,#04c);
}

.form-control:focus {
    border-color: #aaa;
    outline: 0;

    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(193, 193, 193, 0.6);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(76, 76, 76, 0.6);
}

.profile.active {
    color: #fff !important;
    background-color: #28a745 !important;
}

.profile:not(.active):hover {
    text-decoration: underline !important;
    color: #6c757d !important;
}

.menu:not(.active):hover {
    /*color: #2c2e2f !important;*/
    font-weight: 600 !important;
}

.d-block {
    font-size: 10px !important;
    font-weight: 500 !important;
}

.btn-rep{
  padding: 0px 10px 0px 10px !important;
  font-size:11px !important;
}

.select2-container .select2-choice, .select2-result-label {
  height: calc(1.8125rem + 2px) !important;
}

.nav-sidebar>.nav-item .nav-icon.fa, .nav-sidebar>.nav-item .nav-icon.fab, .nav-sidebar>.nav-item .nav-icon.far, .nav-sidebar>.nav-item .nav-icon.fas, .nav-sidebar>.nav-item .nav-icon.glyphicon, .nav-sidebar>.nav-item .nav-icon.ion {
    font-size: 0.9rem !important;
}

</style>

<script>

$(function() {

  /**/

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

  //inicialización de todos los datepicker de bootstrap
  $('.datepicker').datepicker({
      language: 'es',
      autoclose: true,
      orientation: "right bottom",
  });
  
});

function convert_may(e) {
    e.value = e.value.toUpperCase();
}

function convert_min(e) {
    e.value = e.value.toLowerCase();
}

function clear_select2_ajax(id){
    $('#'+id+'').val('').trigger('change');
    $('#s2id_'+id+' span').html(""); 
}

function limp_div_msg(){
    $("#div_mensaje").hide();  
    classact = $('#div_mensaje').attr('class');
    $("#div_mensaje").removeClass(classact);
    $("#div_mensaje").html('');
}

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

// Validacion de extensiones permitidas
function validarExtension(datos, extensionesValidas, textExtensionesValidas, idInput, IdMsg) {

  var ruta = datos.value;
  var extension = ruta.substring(ruta.lastIndexOf('.') + 1).toLowerCase();
  var extensionValida = extensionesValidas.indexOf(extension);

  if(extensionValida < 0) {

    $('#'+IdMsg).html('La extensión no es válida (.'+ extension+'), Solo se admite '+textExtensionesValidas+'.');
    $('#'+IdMsg).show();
    $('#'+idInput).val(0);
    return false;

  } else {

    return true;

  }
}

// Validacion de peso del fichero en kbs

function validarPeso(datos, pesoPermitido, idInput, IdMsg) {

  if (datos.files && datos.files[0]) {

        var pesoFichero = datos.files[0].size/1024;

        if(pesoFichero > pesoPermitido) {

            $('#'+IdMsg).html('El peso maximo permitido del fichero es: ' + pesoPermitido / 1024 + ' MB, Su fichero tiene: '+ (pesoFichero /1024).toFixed(2) +' MB.');
            $('#'+IdMsg).show();
            $('#'+idInput).val(0);
            return false;

        } else {

            return true;

        }

    }
}

function renderPDF(url, canvasContainer, options) {

    var options = options || { scale: 1 };
        
    function renderPage(page) {
        var viewport = page.getViewport(options.scale);
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var renderContext = {
          canvasContext: ctx,
          viewport: viewport
        };
        
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        canvasContainer.appendChild(canvas);
        
        page.render(renderContext);
    }
    
    function renderPages(pdfDoc) {
        for(var num = 1; num <= pdfDoc.numPages; num++)
            pdfDoc.getPage(num).then(renderPage);
    }

    PDFJS.disableWorker = true;
    PDFJS.getDocument(url).then(renderPages);

}

function loadershow(){
  $(".ajax-loader").fadeIn('fast');
  setTimeout(function(){$(".ajax-loader").fadeOut('fast');}, 10000);
}

</script>

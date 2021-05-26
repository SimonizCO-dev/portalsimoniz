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
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- summernote -->
  <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <!--<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
  <!-- Jquery tree -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/jquery-tree/src/css/jquery.tree.css"/>

</head>

<?php if(!Yii::app()->user->isGuest) { ?>

<body class="sidebar-mini layout-navbar-fixed sidebar-collapse small">
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

    <!-- Left navbar links -->
    <ul class="navbar-nav" id="navbar-nav-menu">
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">  
      <!-- <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-search-menu" title="Buscar">
          <i class="fas fa-search"></i>
        </a>
      </li> -->  
    </ul>
  </nav>
  <!-- /.navbar -->

  <div class="modal fade" id="modal-search-menu">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Busqueda rapida</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <p>Utilice el filtro para buscar una opción en el menú, los resultados dependeran de los permisos y opciones habilitadas sobre su usuario: </p>
            <div class="row pb-5">
              <div class="col-sm-11">
                <div class="form-group">
                  <input type="text" id="input_search">
                    <?php
                        $this->widget('ext.select2.ESelect2', array(
                            'selector' => '#input_search',
                            'options'  => array(
                                'placeholder'=>'Buscar...',
                                'allowClear' => true,
                                'minimumInputLength' => 3,
                                'width' => '100%',
                                'language' => 'es',
                                'ajax' => array(
                                    'url' => Yii::app()->createUrl('menu/SearchOpcion'),
                                    'dataType'=>'json',
                                    'data'=>'js:function(term){return{q: term};}',
                                    'results'=>'js:function(data){ return {results:data};}'                   
                                ),
                                'formatNoMatches'=> 'js:function(){ return "No se encontraron resultados"; }',
                                'formatInputTooShort' =>  'js:function(){ return "Digite más de 3 caracteres para iniciar busqueda <button type=\"button\" class=\"btn btn-primary btn-xs float-right\" onclick=\"clear_select2_ajax(\'search\')\">Limpiar campo</button>"; }',
                            ),
                        ));
                    ?>
                  </div>
                </div>
                <div class="col-sm-1">
                  <button type="button" class="btn btn-primary btn-sm" id="button-search" style="display: none;" onclick="redirectopc();"><i class="fa fa-arrow-circle-right"></i> Ir</button>
                </div>
              </div>
          </div>
      </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
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
          <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/profile'; ?>" title="Ver perfil / Cambiar credenciales" class="d-block"><?php echo Yii::app()->user->getState('username_user'); ?></a>
          <a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=usuario/profile'; ?>" title="Ver perfil / Cambiar credenciales" class="d-block"><?php echo Yii::app()->user->getState('name_user'); ?></a>
        </div>
      </div>

      <!-- Sidebar Menu  -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" id="sidebar-menu" role="menu" data-accordion="false" style="">
        <!-- <li class="nav-item"><a href="#" class="nav-link menu"><i class="nav-icon fas fa-file-invoice-dollar"></i><span class="badge badge-warning navbar-badge">3</span><p class="text">Notificación de prueba</p></a></li> -->
        <li class="nav-item"><a href="#" data-toggle="modal" data-target="#modal-search-menu" title="Busqueda de opciones en aplicación" class="nav-link menu"><i class="nav-icon fas fa-search"></i><p class="text">Busqueda avanzada</p></a></li>
        <li class="nav-item"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=ticket/create'; ?>" title="Ayuda" class="nav-link menu"><i class="nav-icon fas fa-ticket-alt"></i><p class="text">Nuevo ticket</p></a></li>
        <li class="nav-item"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/ayuda'; ?>" title="Ayuda" class="nav-link menu"><i class="nav-icon fas fa-question-circle"></i><p class="text">Ayuda</p></a></li>
        <li class="nav-item"><a href="<?php echo Yii::app()->getBaseUrl(true).'/index.php?r=site/logout'; ?>" title="Cerrar sesión / Salir de la aplicación" class="nav-link menu"><i class="nav-icon fas fa-sign-out-alt"></i><p class="text">Cerrar sesión</p></a></li></ul>
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
    <?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-primary alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check-circle"></i>Realizado</h5>
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?> 

    <?php if(Yii::app()->user->hasFlash('warning')):?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info-circle"></i>Info</h5>
            <?php echo Yii::app()->user->getFlash('warning'); ?>
        </div>
    <?php endif; ?> 
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
    <strong><?php echo CHtml::encode(Yii::app()->name) ?> © <?php echo date('Y'); ?> - Versión 1.0</strong>
  </footer>

</div>
<!-- ./wrapper -->

<?php } else { ?>

<body class="login-page" style="background: linear-gradient(-135deg,#ffe9a6,#fc5000c7);">

    <div class="ajax-loader" style="display: none;">
    </div>

    <?php echo $content; ?>

<?php } ?>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<?php if(!Yii::app()->user->isGuest) { ?>
<!-- Menu -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/dist/js/menu.js"></script>
<?php } ?>
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
<!-- bootstrap time picker -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/timepicker/bootstrap-timepicker.min.js"></script>
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
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/tilt/tilt.jquery.min.js"></script>
<script >
  $('.js-tilt').tilt({
    scale: 1.1
  })
</script>
</body>

</html>

<style type="text/css">

/*Modificación botones de la grid yii*/

.btn-login{
  color: #fff;
  background-color: #fc5000;
  border-color: #fc5000;
  box-shadow: none;
  border-radius: 10px;
}

.login-card-body{
  border-radius: 5% !important;
}

.select2-results .select2-no-results, .select2-results .select2-searching, .select2-results .select2-selection-limit {
    background: none !important;
}

.actions{
  padding-left: 5%;
  padding-right: 5%; 
  /*font-size: 11px;*/
}

.actions:hover {
  transform: scale(1.5);
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

.datepicker-dropdown { 
    font-size: 90% !important;
    font-weight: 400 !important;
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

.nav-sidebar>.nav-item .nav-icon.fa, .nav-sidebar>.nav-item .nav-icon.fab, .nav-sidebar>.nav-item .nav-icon.far, .nav-sidebar>.nav-item .nav-icon.fas, .nav-sidebar>.nav-item .nav-icon.glyphicon, .nav-sidebar>.nav-item .nav-icon.ion {
    font-size: 0.9rem !important;
}

</style>

<script>

$(function() {

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

  $('.timepicker').timepicker({
      template: false,
      showInputs: true,
      minuteStep: 15,
      defaultTime: false,
      timeFormat: 'h:mm p',
      //showMeridian: false
  });


  $("#input_search").change(function() {
    var val = $("#input_search").val();
    if(val == ""){
      $("#button-search").fadeOut('fast');
    }else{
      $("#button-search").fadeIn('fast');
    }
  });
  
});

function convert_may(e) {
    e.value = e.value.toUpperCase();
}

function convert_min(e) {
    e.value = e.value.toLowerCase();
}

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function filtronumerosletras(str) {
  let code, i, len,result='';

  for (i = 0, len = str.length; i < len; i++) {
    code = str.charCodeAt(i);
    if ((code > 47 && code < 58) || // numeric (0-9)
        (code > 64 && code < 91) || // upper alpha (A-Z)
        (code > 96 && code < 123) ||
        (code == 32)) { // space
      result+=str.charAt(i);
    }
  }
  return result;
};

function onlynumbersletters(t) {
  let value=t.value.toUpperCase();
  if (value) {
    t.value=filtronumerosletras(value);
  }
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

function renderPdfByUrl(url) {

    showloader();

    var currPage = 1; 
    var numPages = 0;
    var thePDF = null;

    //This is where you start
    PDFJS.getDocument(url).then(function(pdf) {

        //Set PDFJS global object (so we can easily access in our page functions
        thePDF = pdf;

        //How many pages it has
        numPages = pdf.numPages;

        //Start with first page
        pdf.getPage(1).then(handlePages);
    });


    function handlePages(page) {
        //This gives us the page's dimensions at full scale
        var viewport = page.getViewport(1);

        //We'll create a canvas for each page to draw it on
        var canvas = document.createElement("canvas");
        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        //Draw it on the canvas
        page.render({
            canvasContext: context,
            viewport: viewport
        });

        //Add it to the web page
        document.getElementById('viewer').appendChild(canvas);
        hideloader();

        //Move to next page
        currPage++;
        if (thePDF !== null && currPage <= numPages) {
            thePDF.getPage(currPage).then(handlePages);
        }
    }
}

function loadershow(){
  $(".ajax-loader").fadeIn('fast');
  setTimeout(function(){$(".ajax-loader").fadeOut('fast');}, 10000);
}

function showloader(){
  $(".ajax-loader").fadeIn('fast');
}

function hideloader(){
  $(".ajax-loader").fadeOut('fast');
}

function redirectopc(){
  var id_menu = $("#input_search").val();
  var data = {id_menu: id_menu}
  $.ajax({ 
    type: "POST",
    url: "<?php echo Yii::app()->createUrl('menu/getOpcion'); ?>",
    data: data, 
    dataType: 'json',
    success: function(data){
      var link = data['link'];
      loadershow();
      window.location.href = "<?php echo Yii::app()->getBaseUrl(true).'/'; ?>"+link;
    }
  });
}

function log(id_menu){
  //registro de log por opcion de menu / usuario
  var data = {id_menu: id_menu}
  $.ajax({ 
      type: "POST", 
      url: "<?php echo Yii::app()->createUrl('site/log'); ?>",
      data: data,
  });
}

</script>

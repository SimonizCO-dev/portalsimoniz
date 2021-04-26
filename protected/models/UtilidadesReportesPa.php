<?php

//clase creada para funciones relacionadas con el modelo de reportes

class UtilidadesReportesPa {
  
  public static function licdisppantalla($empresa_compra, $clasif) {
    
    //se arma el arreglo con los datos para el reporte

    $condicion = "";

    if($empresa_compra != ""){
      $a_emp = $empresa_compra;
      $condicion .= " AND L.Empresa_Compra IN (".$a_emp.")";
    }

    if($clasif != ""){
      $condicion .= " AND L.Clasificacion = ".$clasif;
    }

    $query = "
      SELECT 
      L.Id_Lic,
      E.Descripcion AS Empresa_Compra,
      C.Dominio AS Clasif, 
      T.Dominio AS Tipo, 
      V.Dominio AS Vers, 
      P.Dominio AS Prod, 
      L.Num_Licencia,
      L.Cant_Usuarios AS Usu_x_Lic,
      (L.Cant_Usuarios - (SELECT COUNT(*) FROM T_PR_LICENCIA_EQUIPO WHERE Id_Licencia = L.Id_Lic AND Estado = 1)) AS Usu_x_Lic_Disp,
      U.Dominio AS Ubic,
      L.Numero_Factura
      FROM T_PR_LICENCIA L
      LEFT JOIN T_PR_PA_EMPRESA E ON L.Empresa_Compra = E.Id_Pa_Empresa 
      LEFT JOIN T_PR_DOMINIO C ON L.Clasificacion = C.Id_Dominio
      LEFT JOIN T_PR_DOMINIO T ON L.Tipo = T.Id_Dominio 
      LEFT JOIN T_PR_DOMINIO V ON L.Version = V.Id_Dominio 
      LEFT JOIN T_PR_DOMINIO P ON L.Producto = P.Id_Dominio
      LEFT JOIN T_PR_DOMINIO U ON L.Ubicacion = U.Id_Dominio
      WHERE (L.Cant_Usuarios - (SELECT COUNT(*) FROM T_PR_LICENCIA_EQUIPO WHERE Id_Licencia = L.Id_Lic AND Estado = 1)) != 0
      AND L.Estado = ".Yii::app()->params->estado_lic_act." ".$condicion."
      ORDER BY 2,3,4,5,6
    ";

    $tabla = '
    <table class="table table-sm table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Empresa que compro</th>
          <th>Clasif.</th>
          <th>Tipo</th>
          <th>Versión</th>
          <th>Producto</th>
          <th>N° de licencia</th>
          <th>Usuarios x lic.</th>
          <th>Usuarios x lic. disp.</th>
          <th>Ubicación</th>
          <th>N° de factura</th>
        </tr>
      </thead>
      <tbody>
    ';

    $q_lic = Yii::app()->db->createCommand($query)->queryAll();

    if(!empty($q_lic)){

      $i = 0;

      foreach ($q_lic as $reg1) {

        $Id_Lic         = $reg1 ['Id_Lic']; 
        $Empresa_Compra = $reg1 ['Empresa_Compra']; 
        $Clasif         = $reg1 ['Clasif'];
        $Tipo           = $reg1 ['Tipo'];
        $Vers           = $reg1 ['Vers'];
        
        if($reg1 ['Prod'] != ""){
          $Prod   = $reg1 ['Prod'];
        }else{
          $Prod   = '-';
        }
        
        $Num_Licencia   = $reg1 ['Num_Licencia'];
        $Usu_x_Lic      = $reg1 ['Usu_x_Lic'];
        $Usu_x_Lic_Disp = $reg1 ['Usu_x_Lic_Disp'];
        $Ubic           = $reg1 ['Ubic'];
        $Numero_Factura = $reg1 ['Numero_Factura'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Id_Lic.'</td>
              <td>'.$Empresa_Compra.'</td>
              <td>'.$Clasif.'</td>
              <td>'.$Tipo.'</td>
              <td>'.$Vers.'</td>
              <td>'.$Prod.'</td>
              <td>'.$Num_Licencia.'</td>
              <td>'.$Usu_x_Lic.'</td>
              <td>'.$Usu_x_Lic_Disp.'</td>
              <td>'.$Ubic.'</td>
              <td>'.$Numero_Factura.'</td>
          </tr>';

        $i++; 

      }

    }else{

      $tabla .= ' <tr><td colspan="11" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>';

    }

    return $tabla;
  }

  public static function licvencpantalla($empresa_compra, $clasif) {
    
    //se arma el arreglo con los datos para el reporte

    $condicion = "";

    if($empresa_compra != ""){
      $a_emp = $empresa_compra;
      $condicion .= " AND L.Empresa_Compra IN (".$a_emp.")";
    }

    if($clasif != ""){
      $condicion .= " AND L.Clasificacion = ".$clasif;
    }

    $query = "
      SELECT 
      L.Id_Lic,
      E.Descripcion AS Empresa_Compra,
      C.Dominio AS Clasif, 
      T.Dominio AS Tipo, 
      V.Dominio AS Vers, 
      P.Dominio AS Prod, 
      L.Num_Licencia,
      U.Dominio AS Ubic,
      L.Numero_Factura,
      L.Fecha_Inicio,
      L.Fecha_Final
      FROM T_PR_LICENCIA L
      LEFT JOIN T_PR_PA_EMPRESA E ON L.Empresa_Compra = E.Id_Pa_Empresa 
      LEFT JOIN T_PR_DOMINIO C ON L.Clasificacion = C.Id_Dominio
      LEFT JOIN T_PR_DOMINIO T ON L.Tipo = T.Id_Dominio 
      LEFT JOIN T_PR_DOMINIO V ON L.Version = V.Id_Dominio 
      LEFT JOIN T_PR_DOMINIO P ON L.Producto = P.Id_Dominio
      LEFT JOIN T_PR_DOMINIO U ON L.Ubicacion = U.Id_Dominio
      WHERE L.Fecha_Final IS NOT NULL AND DATEDIFF(day,'".date('Y-m-d')."',L.Fecha_Final) < 90 AND L.Estado = ".Yii::app()->params->estado_lic_act." ".$condicion."
      ORDER BY 2,3,4,5,6,11
    ";

    $tabla = '
    <table class="table table-sm table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Empresa que compro</th>
          <th>Clasif.</th>
          <th>Tipo</th>
          <th>Versión</th>
          <th>Producto</th>
          <th>N° de licencia</th>
          <th>Ubicación</th>
          <th>N° de factura</th>
          <th>Fecha inicio</th>
          <th>Fecha fin</th>
        </tr>
      </thead>
      <tbody>
    ';

    $q_lic = Yii::app()->db->createCommand($query)->queryAll();

    if(!empty($q_lic)){

      $i = 0;

      foreach ($q_lic as $reg1) {

        $Id_Lic         = $reg1 ['Id_Lic']; 
        $Empresa_Compra = $reg1 ['Empresa_Compra']; 
        $Clasif         = $reg1 ['Clasif'];
        $Tipo           = $reg1 ['Tipo'];
        $Vers           = $reg1 ['Vers'];
        
        if($reg1 ['Prod'] != ""){
          $Prod   = $reg1 ['Prod'];
        }else{
          $Prod   = '-';
        }
        
        $Num_Licencia   = $reg1 ['Num_Licencia'];
        $Ubic           = $reg1 ['Ubic'];
        $Numero_Factura = $reg1 ['Numero_Factura'];
        $Fecha_Inicio   = $reg1 ['Fecha_Inicio'];
        $Fecha_Final    = $reg1 ['Fecha_Final'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Id_Lic.'</td>
              <td>'.$Empresa_Compra.'</td>
              <td>'.$Clasif.'</td>
              <td>'.$Tipo.'</td>
              <td>'.$Vers.'</td>
              <td>'.$Prod.'</td>
              <td>'.$Num_Licencia.'</td>
              <td>'.$Ubic.'</td>
              <td>'.$Numero_Factura.'</td>
              <td>'.$Fecha_Inicio.'</td>
              <td>'.$Fecha_Final.'</td>
          </tr>';

        $i++; 

      }

    }else{

      $tabla .= ' <tr><td colspan="11" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>';

    }

    return $tabla;
  }

  public static function licequipospantalla($empresa_compra, $tipo_equipo, $tipo_lic, $version) {
    
    //se arma el arreglo con los datos para el reporte

    if($tipo_lic == Yii::app()->params->version_so){
      //S.O

      $condicion = "";

      if($empresa_compra != ""){
        $condicion .= "AND t2.Empresa_Compra IN (".$empresa_compra.")";
      }

      if($tipo_equipo != ""){
        $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
      }

      if($version != ""){
        $condicion .= "AND t1.Version = ".$version;
      } 

      $query = "
        SELECT
        t2.Serial AS Serial
        ,t2.Modelo AS Modelo
        ,t3.Dominio AS Tipo_Licencia
        ,t4.Dominio AS Version
        ,t1.Num_Licencia AS Licencia
        ,t5.Proveedor
        ,t6.Descripcion AS Empresa
        FROM T_PR_EQUIPO_SO t1
        INNER JOIN T_PR_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
        INNER JOIN T_PR_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
        INNER JOIN T_PR_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
        INNER JOIN T_PR_PA_PROVEEDOR AS t5 ON t2.Proveedor = t5.Id_Pa_Proveedor
        INNER JOIN T_PR_PA_EMPRESA AS t6 ON t2.Empresa_Compra = t6.Id_Pa_Empresa
        WHERE 1 = 1
        ".$condicion."
        ORDER BY 3,5
      ";

    }

    if($tipo_lic == Yii::app()->params->version_office){
      //Office
      
       $condicion = "";

      if($empresa_compra != ""){
        $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
      }

      if($tipo_equipo != ""){
        $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
      }

      if($version != ""){AS
        $condicion .= "AND t1.Version = ".$version;
      } 

      $query = "
        SELECT
        t2.Serial AS Serial
        ,t2.Modelo AS Modelo
        ,t3.Dominio AS Tipo_Licencia
        ,t4.Dominio AS Version
        ,t1.Num_Licencia AS Licencia
        ,t1.Fecha_Inicio AS Fecha_Inicio
        ,t1.Fecha_Final AS Fecha_Fin
        ,t5.Proveedor
        ,t6.Descripcion AS Empresa
        FROM T_PR_EQUIPO_OFFICE t1
        INNER JOIN T_PR_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
        INNER JOIN T_PR_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
        INNER JOIN T_PR_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
        INNER JOIN T_PR_PA_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Pa_Proveedor
        INNER JOIN T_PR_PA_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Pa_Empresa
        WHERE 1 = 1
        ".$condicion."
        ORDER BY 3,5
      ";

    }

    if($tipo_lic == Yii::app()->params->version_adobe){
      //Adobe
      
       $condicion = "";

      if($empresa_compra != ""){
        $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
      }

      if($tipo_equipo != ""){
        $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
      }

      if($version != ""){
        $condicion .= "AND t1.Version = ".$version;
      } 

      $query = "
        SELECT
        t2.Serial AS Serial
        ,t2.Modelo AS Modelo
        ,t3.Dominio AS Tipo_Licencia
        ,t4.Dominio AS Version
        ,t1.Num_Licencia AS Licencia
        ,t1.Fecha_Inicio AS Fecha_Inicio
        ,t1.Fecha_Final AS Fecha_Fin
        ,t5.Proveedor
        ,t6.Descripcion AS Empresa
        FROM T_PR_EQUIPO_ADOBE t1
        INNER JOIN T_PR_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
        INNER JOIN T_PR_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
        INNER JOIN T_PR_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
        INNER JOIN T_PR_PA_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Pa_Proveedor
        INNER JOIN T_PR_PA_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Pa_Empresa
        WHERE 1 = 1
        ".$condicion."
        ORDER BY 3,5
      ";

    }

    if($tipo_lic == Yii::app()->params->version_autodesk){
      //Autodesk
        
       $condicion = "";

      if($empresa_compra != ""){
        $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
      }

      if($tipo_equipo != ""){
        $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
      }

      if($version != ""){
        $condicion .= "AND t1.Version = ".$version;
      } 

      $query = "
        SELECT
        t2.Serial AS Serial
        ,t2.Modelo AS Modelo
        ,t3.Dominio AS Tipo_Licencia
        ,t4.Dominio AS Version
        ,t1.Num_Licencia AS Licencia
        ,t5.Proveedor
        ,t6.Descripcion AS Empresa
        FROM T_PR_EQUIPO_AUTODESK t1
        INNER JOIN T_PR_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
        INNER JOIN T_PR_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
        INNER JOIN T_PR_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
        INNER JOIN T_PR_PA_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Pa_Proveedor
        INNER JOIN T_PR_PA_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Pa_Empresa
        WHERE 1 = 1
        ".$condicion."
        ORDER BY 3,5
      ";

    }

    if($tipo_lic == Yii::app()->params->version_antivirus){
      //Antivirus
        
       $condicion = "";

      if($empresa_compra != ""){
        $condicion .= "AND t1.Empresa_Compra IN (".$empresa_compra.")";
      }

      if($tipo_equipo != ""){
        $condicion .= "AND t2.Tipo_Equipo IN (".$tipo_equipo.")";
      }

      if($version != ""){
        $condicion .= "AND t1.Version = ".$version;
      } 

      $query = "
        SELECT
        t2.Serial AS Serial
        ,t2.Modelo AS Modelo
        ,t3.Dominio AS Tipo_Licencia
        ,t4.Dominio AS Version
        ,t1.Num_Licencia AS Licencia
        ,t5.Proveedor
        ,t6.Descripcion AS Empresa
        FROM T_PR_EQUIPO_ANTIVIRUS t1
        INNER JOIN T_PR_EQUIPO AS t2 ON t1.Id_Equipo = t2.Id_Equipo
        INNER JOIN T_PR_DOMINIO AS t3 ON t1.Tipo_Licencia = t3.Id_Dominio
        INNER JOIN T_PR_DOMINIO AS t4 ON t1.Version = t4.Id_Dominio
        INNER JOIN T_PR_PA_PROVEEDOR AS t5 ON t1.Proveedor = t5.Id_Pa_Proveedor
        INNER JOIN T_PR_PA_EMPRESA AS t6 ON t1.Empresa_Compra = t6.Id_Pa_Empresa
        WHERE 1 = 1
        ".$condicion."
        ORDER BY 3,5
      ";

    }

    if($tipo_lic == Yii::app()->params->version_so || $tipo_lic == Yii::app()->params->version_autodesk || $tipo_lic == Yii::app()->params->version_antivirus){

      $tabla = '
      <table class="table table-sm table-hover">
        <thead>
          <tr>
            <th>Serial</th>
            <th>Modelo</th>
            <th>Tipo</th>
            <th>Versión</th>
            <th>Licencia</th>
            <th>Proveedor</th>
            <th>Empresa</th>
          </tr>
        </thead>
        <tbody>
      ';

    }

    if($tipo_lic == Yii::app()->params->version_office || $tipo_lic == Yii::app()->params->version_adobe){

      $tabla = '
      <table class="table table-sm table-hover">
        <thead>
          <tr>
            <th>Serial</th>
            <th>Modelo</th>
            <th>Tipo</th>
            <th>Versión</th>
            <th>Licencia</th>
            <th>Fecha inicio</th>
            <th>Fecha fin</th>
            <th>Proveedor</th>
            <th>Empresa</th>
          </tr>
        </thead>
        <tbody>
      ';

    }

    $q_lic = Yii::app()->db->createCommand($query)->queryAll();

    if(!empty($q_lic)){

      $i = 0;

      foreach ($q_lic as $reg1) {

        if($tipo_lic == Yii::app()->params->version_so || $tipo_lic == Yii::app()->params->version_autodesk || $tipo_lic == Yii::app()->params->version_antivirus){
      
          $Serial         = $reg1 ['Serial']; 
          $Modelo         = $reg1 ['Modelo']; 
          $Tipo_Licencia  = $reg1 ['Tipo_Licencia'];
          $Version        = $reg1 ['Version'];
          $Licencia       = $reg1 ['Licencia'];
          $Proveedor      = $reg1 ['Proveedor'];
          $Empresa        = $reg1 ['Empresa'];

          if ($i % 2 == 0){
            $clase = 'odd'; 
          }else{
            $clase = 'even'; 
          }

          $tabla .= '    
          <tr class="'.$clase.'">
                <td>'.$Serial.'</td>
                <td>'.$Modelo.'</td>
                <td>'.$Tipo_Licencia.'</td>
                <td>'.$Version.'</td>
                <td>'.$Licencia.'</td>
                <td>'.$Proveedor.'</td>
                <td>'.$Empresa.'</td>
            </tr>';

          $i++;   

        }

        if($tipo_lic == Yii::app()->params->version_office || $tipo_lic == Yii::app()->params->version_adobe){

          $Serial         = $reg1 ['Serial']; 
          $Modelo         = $reg1 ['Modelo']; 
          $Tipo_Licencia  = $reg1 ['Tipo_Licencia'];
          $Version        = $reg1 ['Version'];
          $Licencia       = $reg1 ['Licencia'];
          
          if($reg1 ['Fecha_Fin'] != ""){
            $Fecha_Inicio   = $reg1 ['Fecha_Fin'];
          }else{
            $Fecha_Inicio   = '-';
          }

          if($reg1 ['Fecha_Fin'] != ""){
            $Fecha_Fin   = $reg1 ['Fecha_Fin'];
          }else{
            $Fecha_Fin   = '-';
          }
          
          $Proveedor      = $reg1 ['Proveedor'];
          $Empresa        = $reg1 ['Empresa'];

          if ($i % 2 == 0){
            $clase = 'odd'; 
          }else{
            $clase = 'even'; 
          }

          $tabla .= '    
          <tr class="'.$clase.'">
                <td>'.$Serial.'</td>
                <td>'.$Modelo.'</td>
                <td>'.$Tipo_Licencia.'</td>
                <td>'.$Version.'</td>
                <td>'.$Licencia.'</td>
                <td>'.$Fecha_Inicio.'</td>
                <td>'.$Fecha_Fin.'</td>
                <td>'.$Proveedor.'</td>
                <td>'.$Empresa.'</td>
            </tr>';

          $i++; 

        }   

      }

    }else{

      if($tipo_lic == Yii::app()->params->version_so || $tipo_lic == Yii::app()->params->version_autodesk || $tipo_lic == Yii::app()->params->version_antivirus){
      
        $tabla .= ' 
          <tr><td colspan="7" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
        ';

      }  

      if($tipo_lic == Yii::app()->params->version_office || $tipo_lic == Yii::app()->params->version_adobe){

        $tabla .= ' 
          <tr><td colspan="9" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
        ';

      }

    }

    $tabla .= '</tbody>
        </table>';

    return $tabla;
  }

}

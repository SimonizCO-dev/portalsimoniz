<?php

//clase creada para funciones relacionadas con el modelo de reportes

class UtilidadesReportes {
  
  public static function diferenciasunpantalla() {

    $query ="EXEC P_PR_COM_CONS_UN";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Item</th>
                <th>Descripción</th>
                <th>Código de inventario</th>
                <th>UN de inventario</th>
                <th>Código de criterio</th>
                <th>UN de criterio</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!is_null($q1)){
      foreach ($q1 as $reg1) {

        $item        = $reg1 ['ITEM']; 
        $desc        = $reg1 ['DESCRIPCION']; 
        $cod_inv     = $reg1 ['COD_INVENTARIO'];
        $un_inv      = $reg1 ['UN_INVENTARIO'];
        $cod_cri     = $reg1 ['COD_CRITERIO'];
        $un_cri      = $reg1 ['UN_CRITERIO'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$item.'</td>
              <td>'.$desc.'</td>
              <td>'.$cod_inv.'</td>
              <td>'.$un_inv.'</td>
              <td>'.$cod_cri.'</td>
              <td>'.$un_cri.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="6" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function consultafactelectpantalla($tipo, $cons_inicial, $cons_final) {

    $query ="SELECT * FROM T_CF_FACTURA_ELECTRONICA WHERE FE_TIPO_DOCTO = '".$tipo."' AND FE_CONSECUTIVO BETWEEN ".$cons_inicial." AND ".$cons_final." ORDER BY 12";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Cia</th>
                <th>CO</th>
                <th>Tipo de docto</th>
                <th>Desc. tipo</th>
                <th>Consecutivo</th>
                <th>Cufe</th>
                <th>Fecha de factura</th>
                <th>Fecha de creación</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    UtilidadesVarias::log($query);

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $cia  = $reg1 ['FE_CIA']; 
        $co  = $reg1 ['FE_CO']; 
        $tipo_docto  = $reg1 ['FE_TIPO_DOCTO']; 

        if($tipo_docto == "FVN") {
          $tipo = 'Factura de Venta Nacional';
        }

        if($tipo_docto == "FVX") {
          $tipo = 'Factura de Exportación';
        }

        if($tipo_docto == "FEC") {
          $tipo = 'Factura de Contingencia Facturador';
        }

        $consecutivo  = $reg1 ['FE_CONSECUTIVO'];
        $cufe  = $reg1 ['FE_CUFE'];
        $fecha_factura  = $reg1 ['FE_FECHA_FACTURA']; 
        $fecha_creacion  = $reg1 ['CREACION'];  

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
            <td>'.$cia.'</td>
            <td>'.$co.'</td>
            <td>'.$tipo_docto.'</td>
            <td>'.$tipo.'</td>
            <td>'.$consecutivo.'</td>
            <td>'.$cufe.'</td>
            <td>'.$fecha_factura.'</td>
            <td>'.$fecha_creacion.'</td>
        </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="8" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function diferenciasrutaspantalla() {

    $query ="EXEC P_PR_COM_CONS_RUTAS";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Ruta visita</th>
                <th>Ruta criterio</th>
                <th>Nit</th>
                <th>Cliente</th>
                <th>Sucursal</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $ruta_visita    = $reg1 ['RUTA_VISITA']; 
        $ruta_criterio  = $reg1 ['RUTA_CRITERIO']; 
        $nit            = $reg1 ['NIT'];
        $cliente        = $reg1 ['CLIENTE'];
        $sucursal       = $reg1 ['SUCURSAL'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$ruta_visita.'</td>
              <td>'.$ruta_criterio.'</td>
              <td>'.$nit.'</td>
              <td>'.$cliente.'</td>
              <td>'.$sucursal.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function vendedorespantalla() {

    $query ="SET NOCOUNT ON EXEC P_PR_COM_CONS_VENDEDORES";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Nit</th>
                <th>Vendedor</th>
                <th>Código</th>
                <th>Estado vendedor</th>
                <th>Celular</th>
                <th>Correo</th>
                <th>Recibo</th>
                <th>Ruta</th>
                <th>Nombre ruta</th>
                <th>Estado ruta</th>
                <th>Portafolio</th>
                <th>Coordinador</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $nit              = $reg1 ['Nit_Vendedor']; 
        $nombre_vendedor  = $reg1 ['Nombre_Vendedor']; 
        $codigo           = $reg1 ['Codigo'];
        $estado_vendedor  = $reg1 ['Estado_Vendedor'];
        $celular          = $reg1 ['Celular'];
        $correo           = $reg1 ['Correo'];
        $recibo           = $reg1 ['Recibo'];
        $ruta             = $reg1 ['Ruta'];
        $nombre_ruta      = $reg1 ['Nombre_Ruta'];
        $estado_ruta      = $reg1 ['Estado_Ruta'];
        $portafolio       = $reg1 ['Portafolio'];
        $coordinador      = $reg1 ['Coordinador'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$nit.'</td>
              <td>'.$nombre_vendedor.'</td>
              <td>'.$codigo.'</td>
              <td>'.$estado_vendedor.'</td>
              <td>'.$celular.'</td>
              <td>'.$correo.'</td>
              <td>'.$recibo.'</td>
              <td>'.$ruta.'</td>
              <td>'.$nombre_ruta.'</td>
              <td>'.$estado_ruta.'</td>
              <td>'.$portafolio.'</td>
              <td>'.$coordinador.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="12" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function logmobilepantalla($fecha_inicial, $fecha_final) {
    
    $FechaM1 = str_replace("-","",$fecha_inicial);
    $FechaM2 = str_replace("-","",$fecha_final);

    $query ="
      EXEC P_CF_COM_CONS_LOGMOBILE_FECHA
      @FECHA1 = N'".$FechaM1."',
      @FECHA2 = N'".$FechaM2."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Documento</th>
                <th>Fecha de elaboración</th>
                <th>Vendedor</th>
                <th>Cliente</th>
                <th>Error</th>
                <th>Actualizado</th>
                <th>Eliminado</th>
                <th>Fecha de registro</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!is_null($q1)){
      foreach ($q1 as $reg1) {

        $DOCUMENTO          = $reg1 ['DOCUMENTO']; 
        $FECHA_ELABORACION  = $reg1 ['FECHA_ELABORACION']; 
        $VENDEDOR  = $reg1 ['VENDEDOR'];
        $CLIENTE  = $reg1 ['CLIENTE'];
        $ERROR  = $reg1 ['ERROR'];
        $ACTUALIZADO  = $reg1 ['ACTUALIZADO'];
        $ELIMINADO  = $reg1 ['ELIMINADO'];
        $FECHA_REGISTRO  = $reg1 ['FECHA_REGISTRO'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$DOCUMENTO.'</td>
              <td>'.$FECHA_ELABORACION.'</td>
              <td>'.$VENDEDOR.'</td>
              <td>'.$CLIENTE.'</td>
              <td>'.$ERROR.'</td>
              <td>'.$ACTUALIZADO.'</td>
              <td>'.$ELIMINADO.'</td>
              <td>'.$FECHA_REGISTRO.'</td>
          </tr>';

        $i++; 

      }

    }else{
      $tabla .= ' 
        <tr><td colspan="8" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function venposentrpantalla($fecha_inicial, $fecha_final) {

    $FechaM1 = str_replace("-","",$fecha_inicial);
    $FechaM2 = str_replace("-","",$fecha_final);

    $query ="
      EXEC P_PR_COM_POS2_FONT
      @FECHA_INI = N'".$FechaM1."',
      @FECHA_FIN = N'".$FechaM2."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Item</th>
                <th>Descripción</th>
                <th>Und. medida</th>
                <th>Existencia</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Vlr. neto</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $PEDIDO           = $reg1 ['PEDIDO'];
        $CLIENTE          = $reg1 ['CLIENTE'];
        $FECHA            = $reg1 ['FECHA']; 
        $ITEM             = $reg1 ['ITEM']; 
        $DESCRIPCION      = $reg1 ['DESCRIPCION'];
        $UM               = $reg1 ['UM'];
        $EXISTENCIA       = $reg1 ['EXISTENCIA'];
        $CANTIDAD         = $reg1 ['CANTIDAD'];
        $PRECIO           = $reg1 ['PRECIO'];
        $VLR_NETO         = $reg1 ['VLR_NETO'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
          <td>'.$PEDIDO.'</td>
          <td>'.$CLIENTE.'</td>
          <td>'.$FECHA.'</td>
          <td>'.$ITEM.'</td>
          <td>'.$DESCRIPCION.'</td>
          <td>'.$UM.'</td>
          <td align="right">'.$EXISTENCIA.'</td>
          <td align="right">'.$CANTIDAD.'</td>
          <td align="right">'.number_format(($PRECIO),2,".",",").'</td>
          <td align="right">'.number_format(($VLR_NETO),2,".",",").'</td>
        </tr>';

        $i++;
       
      }
    }else{
      $tabla .= ' 
        <tr><td colspan="10" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;

  }

  public static function venposfaltpantalla($fecha_inicial, $fecha_final) {

    $FechaM1 = str_replace("-","",$fecha_inicial);
    $FechaM2 = str_replace("-","",$fecha_final);

    $query ="
      EXEC P_PR_COM_POS1_FONT
      @FECHA_INI = N'".$FechaM1."',
      @FECHA_FIN = N'".$FechaM2."'
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Fecha</th>
                <th>Item</th>
                <th>Descripción</th>
                <th>Existencia</th>
                <th>Cantidad</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $FECHA            = $reg1 ['FECHA']; 
        $ITEM             = $reg1 ['ITEM']; 
        $DESCRIPCION      = $reg1 ['DESCRIPCION'];
        $EXISTENCIA       = $reg1 ['EXISTENCIA'];
        $CANTIDAD         = $reg1 ['CANTIDAD'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $cal = $EXISTENCIA - $CANTIDAD;

        if($cal < 0){
          $tabla .= '    
          <tr class="'.$clase.'">
            <td>'.$FECHA.'</td>
            <td>'.$ITEM.'</td>
            <td>'.$DESCRIPCION.'</td>
            <td align="right">'.$EXISTENCIA.'</td>
            <td align="right">'.$CANTIDAD.'</td>
          </tr>';

          $i++;
        } 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;

  }

  public static function consultapagospantalla() {

    $query ="
      SET NOCOUNT ON
      EXEC P_PR_CONS_PAG_PSE
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Row Id</th>
                <th>Banco</th>
                <th>Nit</th>
                <th>Cliente</th>
                <th>Factura</th>
                <th># Factura</th>
                <th>Medio de pago</th>
                <th>Estado</th>
                <th>Valor</th>
                <th>Ref. pago</th>
                <th>Fecha reporte</th>
                <th>Reportado</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $Rowid  = $reg1 ['Rowid']; 
        $Banco  = $reg1 ['Banco']; 
        $Nit_Cliente  = $reg1 ['Nit_Cliente']; 
        $Cliente  = $reg1 ['Cliente']; 
        $Factura  = $reg1 ['Factura']; 
        $Numero_Factura  = $reg1 ['Numero_Factura']; 
        $Medio_Pago  = $reg1 ['Medio_Pago']; 
        $Estado  = $reg1 ['Estado']; 
        $Valor  = number_format($reg1 ['Valor'], 0, ',', '.');
        $Referencia_Pago  = $reg1 ['Referencia_Pago']; 
        $Fecha_Reporte  = $reg1 ['Fecha_Reporte']; 
        $Reportado  = $reg1 ['Reportado']; 

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Rowid.'</td>
              <td>'.$Banco.'</td>
              <td>'.$Nit_Cliente.'</td>
              <td>'.$Cliente.'</td>
              <td>'.$Factura.'</td>
              <td>'.$Numero_Factura.'</td>
              <td>'.$Medio_Pago.'</td>
              <td>'.$Estado.'</td>
              <td align="right">'.$Valor.'</td>
              <td>'.$Referencia_Pago.'</td>
              <td>'.$Fecha_Reporte.'</td>
              <td>'.$Reportado.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="12" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function auditoriapedidospantalla($co, $tipo, $consecutivo) {

    $query ="
      SET NOCOUNT ON
      EXEC P_PR_INT_WMS_CONS
      @CO  = N'".$co."',
      @TIPO  = N'".$tipo."',
      @NUM  = ".$consecutivo."
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Leido por WMS</th>
                <th>Liberado por WMS</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $LEIDO_WMS     = $reg1 ['LEIDO_WMS']; 
        $LIBERADO_WMS  = $reg1 ['LIBERADO_WMS']; 

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$LEIDO_WMS.'</td>
              <td>'.$LIBERADO_WMS.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="2" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function actrecapantalla() {

    $query ="
      SELECT 
      ROWID,
      F350_ID_TERCERO,
      F357_REFERENCIA,
      F357_VALOR_APLICAR_REAL,
      F357_ID_COBRADOR,
      F350_NOTAS,
      F353_ID_SUCURSAL_DOCTO_CRUCE,
      F358_REFERENCIA_OTROS    
      FROM Repositorio_Datos..T_IN_Recibos_Caja
      WHERE INTEGRADO = 1
    ";

    UtilidadesVarias::log($query);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Row Id</th>
                <th>Nit Tercero</th>
                <th>Referencia de pago</th>
                <th>Vlr. aplicado</th>
                <th>Id cobrador</th>
                <th>Nota</th>
                <th>Id sucursal docto. cruce</th>
                <th>Factura</th>
                <th></th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db2->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $ROWID = $reg1 ['ROWID']; 
        $F350_ID_TERCERO  = $reg1 ['F350_ID_TERCERO']; 
        $F357_REFERENCIA  = $reg1 ['F357_REFERENCIA'];
        $F357_VALOR_APLICAR_REAL  = number_format($reg1 ['F357_VALOR_APLICAR_REAL'], 0, ',', '.');
        $F357_ID_COBRADOR  = $reg1 ['F357_ID_COBRADOR'];
        $F350_NOTAS  = $reg1 ['F350_NOTAS'];
        $F353_ID_SUCURSAL_DOCTO_CRUCE  = $reg1 ['F353_ID_SUCURSAL_DOCTO_CRUCE'];
        $F358_REFERENCIA_OTROS  = $reg1 ['F358_REFERENCIA_OTROS'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
          <td>'.$ROWID.'</td>
          <td>'.$F350_ID_TERCERO.'</td>
          <td>'.$F357_REFERENCIA.'</td>
          <td align="right">'.$F357_VALOR_APLICAR_REAL.'</td>
          <td>'.$F357_ID_COBRADOR.'</td>
          <td>'.$F350_NOTAS.'</td>
          <td>'.$F353_ID_SUCURSAL_DOCTO_CRUCE.'</td>
          <td>'.$F358_REFERENCIA_OTROS.'</td>
          <td><button type="button" class="btn btn-primary btn-sm btn-rep" onclick="actreca('.$ROWID.');"><i class="fa fa-sync"></i> Actualizar</button></td>
        </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="8" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      '; 
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function listamaterialespantalla($item) {

    $q_item = Yii::app()->db->createCommand("
      SELECT   
      f120_rowid AS ROWID_ITEM,
      f121_rowid AS ROWID_ITEM_EXT
      FROM UnoEE1..t120_mc_items
      INNER JOIN UnoEE1..t121_mc_items_extensiones ON f120_rowid = f121_rowid_item
      WHERE f120_id_cia = 2 AND f120_id = ".$item
    )->queryRow();
    
    $ID = $q_item['ROWID_ITEM'];
    $ID_EXT = $q_item['ROWID_ITEM_EXT'];

    $query = "EXEC P_PR_MNF_PROM_DESGLOCE @Rowid1 = ".$ID.", @Rowid2 = ".$ID_EXT;

    UtilidadesVarias::log($query);

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1;

    if(!empty($q1)){

      $tree = '<ul id="tree">';

      foreach ($q1 as $reg1) {

        if($i > 1){
          
          if($i == 2){

            $item = trim($reg1['f_item_resumen']);

            $item_res = substr($item, 0, 40);

            $tree .= '<li><strong><span style="display:inline-block;width:575px">'.$item_res.'</span><span style="display:inline-block;width:140px">UND. MEDIDA</span><span style="display:inline-block;width:140px">CANT. REQ</span><span style="display:inline-block;width:140px">CANT. BASE</span></strong>';

            $n_a =  $reg1['f_treeview_nivel'];
          
          }else{
             
            $n_n = $reg1['f_treeview_nivel'];

            if ($n_n > $n_a) {
              //si el siguiente registro es hijo

              $item = trim($reg1['f_item_resumen']);

              $item_res = substr($item, 0, 40);

              if($n_n == 2){ $width = 535;}
              if($n_n == 3){ $width = 495;}
              if($n_n == 4){ $width = 455;}


              $tree .= '<ul><li><span style="display:inline-block;width:'.$width.'px;">'.$item_res.'</span><span style="display:inline-block;width:140px">'.$reg1['f_um'].'</span><span style="display:inline-block;width:140px;text-align:right;">'.number_format($reg1['f_cant_req'], 4, ',', '.').'</span><span style="display:inline-block;width:140px;text-align:right;">'.number_format($reg1['f_cant_base'], 4, ',', '.').'</span>';

              $n_a =  $reg1['f_treeview_nivel'];

            } elseif ($n_n == $n_a) {
              //si el siguiente registro es hijo

              $item = trim($reg1['f_item_resumen']);

              $item_res = substr($item, 0, 40);

              if($n_n == 2){ $width = 535;}
              if($n_n == 3){ $width = 495;}
              if($n_n == 4){ $width = 455;}

              $tree .= '</li><li><span style="display:inline-block;width:'.$width.'px">'.$item_res.'</span><span style="display:inline-block;width:140px">'.$reg1['f_um'].'</span><span style="display:inline-block;width:140px;text-align:right;">'.number_format($reg1['f_cant_req'], 4, ',', '.').'</span><span style="display:inline-block;width:140px;text-align:right;">'.number_format($reg1['f_cant_base'], 4, ',', '.').'</span>';

              $n_a =  $reg1['f_treeview_nivel'];

            } else {
              //si el siguiente registro no es hijo

              $tree .= '</li>';

              $diff = $n_a - $n_n;

              for ($q=1; $q <= $diff ; $q++) { 
                $tree .= '</ul></li>';
              }

              $item = trim($reg1['f_item_resumen']);

              $item_res = substr($item, 0, 40);

              if($n_n == 2){ $width = 535;}
              if($n_n == 3){ $width = 495;}
              if($n_n == 4){ $width = 455;}

              $tree .= '<li><span style="display:inline-block;width:'.$width.'px">'.$item_res.'</span><span style="display:inline-block;width:140px">'.$reg1['f_um'].'</span><span style="display:inline-block;width:140px;text-align:right;">'.number_format($reg1['f_cant_req'], 4, ',', '.').'</span><span style="display:inline-block;width:140px;text-align:right;">'.number_format($reg1['f_cant_base'], 4, ',', '.').'</span>';

              $n_a =  $reg1['f_treeview_nivel'];  

            }
          }
        }
        
        $i++;

      }

    $tree .= '</li></ul>';
      
    }else{
      $tree = '';  
    }

    
    echo $tree;
  }

  public static function kardexcantidadxitem($item, $fecha) {

    $query = "SELECT
    t7.Descripcion AS LINEA
    ,t3.Id_Item AS ITEM
    ,t1.Fecha_Actualizacion AS APROBACION
    ,t1.Referencia AS REFERENCIA
    ,t8.Id AS Id_Docto
    ,CASE WHEN Id_Tipo_Docto IN (1,4,7) THEN t2.Cantidad ELSE 0 END AS ENTRADA
    ,CASE WHEN Id_Tipo_Docto IN (2,5,6) THEN t2.Cantidad ELSE 0 END AS SALIDA
    FROM T_PR_I_DOCTO AS t1
    INNER JOIN T_PR_I_DOCTO_MOVTO AS t2 ON t1.Id=t2.Id_Docto 
    INNER JOIN T_PR_I_ITEM AS t3 ON t2.Id_Item=t3.Id 
    INNER JOIN T_PR_I_EXISTENCIA  AS t4 ON t3.Id=t4.Id_Item 
    LEFT JOIN T_PR_I_BODEGA AS t5 ON t2.Id_Bodega_Org=t5.Id
    LEFT JOIN T_PR_I_BODEGA AS t6 ON t2.Id_Bodega_Dst=t6.Id
    INNER JOIN T_PR_I_LINEA AS t7 ON t3.Id_Linea=t7.Id
    INNER JOIN T_PR_I_TIPO_DOCTO AS t8 ON t1.Id_Tipo_Docto=t8.Id
    INNER JOIN T_PR_I_TERCERO AS t9 ON t9.Id=t1.Id_Tercero
    WHERE t1.Id_Estado=2 AND CONVERT(nvarchar,t1.Fecha_Actualizacion,112) <='".$fecha."'
    AND t3.Id_Item=".$item." AND t1.Id_Estado=2";

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    if(!empty($q1)){
      
      $entrada = 0;
      $salida  = 0;

      foreach ($q1 as $reg1) {
      
        $tipo_docto = $reg1['Id_Docto'];

        if($tipo_docto == Yii::app()->params->ent || $tipo_docto == Yii::app()->params->aje || $tipo_docto == Yii::app()->params->dev){
          //ENTRADAS - AJUSTES POR ENTRADA
          $entrada = $entrada + $reg1['ENTRADA'];
        }

        if($tipo_docto == Yii::app()->params->sal || $tipo_docto == Yii::app()->params->ajs || $tipo_docto == Yii::app()->params->sad){
          //SALIDA - AJUSTE POR SALIDA
          $salida = $salida + $reg1['SALIDA'];
        }

      }

      $cantidad = $entrada - $salida;

    }else{
      $cantidad = 0;
    }
    
    return $cantidad;
  }

  























































































































































  

  

  




  



  

  

  public static function errortransfpantalla($fecha) {
    
    $FechaM = str_replace("-","",$fecha);

    $query ="
      SET NOCOUNT ON
      EXEC [dbo].[CONF_ERROR_TRANSFERENCIAS]
      @FECHA = N'".$FechaM."'
    ";

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Fecha</th>
                <th>Conector</th>
                <th>Documento</th>
                <th>Referencia</th>
                <th>Error</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $Fecha  = $reg1 ['Fecha']; 
        $Conector  = $reg1 ['Conector'];
        $Documento  = $reg1 ['Documento'];
        $Referencia  = $reg1 ['Referencia'];
        $Error  = $reg1 ['Error'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Fecha.'</td>
              <td>'.$Conector.'</td>
              <td>'.$Documento.'</td>
              <td>'.$Referencia.'</td>
              <td>'.$Error.'</td>
          </tr>';

        $i++; 

      }

    }else{
      $tabla .= ' 
        <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

  public static function compincpantalla($tipo, $cons_inicial, $cons_final) {

    $query ="

    SELECT
    CONCAT(f430_id_cia    ,f430_id_co    ,f430_id_tipo_docto    ,f430_consec_docto) as Pedido
    ,f120_id as Item
    ,CAST(f431_cant1_pedida as int) as Cant_Pedida
    ,CAST(f431_cant1_comprometida as int) as Cant_Comprometida
    ,CAST((f400_cant_existencia_1-f400_cant_comprometida_1) as int) as Cant_Existencia
    --select *
    from UnoEE1..t430_cm_pv_docto
    inner join UnoEE1..t431_cm_pv_movto on f430_rowid=f431_rowid_pv_docto
    inner join UnoEE1..t120_mc_items on f120_rowid=f431_rowid_item_ext
    LEFT join UnoEE1..t400_cm_existencia on f400_rowid_bodega=f431_rowid_bodega and f400_rowid_item_ext=f431_rowid_item_ext
    where f430_ind_remisionado=0 and f430_ind_estado in (2,3) and f431_ind_estado in (2,3)
    and (f431_cant1_pedida>f431_cant1_comprometida and (f400_cant_existencia_1-f400_cant_comprometida_1)>f431_cant1_comprometida)
    and f430_id_tipo_docto = '".$tipo."'
    and f430_consec_docto between ".$cons_inicial." and ".$cons_final;

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Pedido</th>
                <th>Item</th>
                <th>Cant. pedida</th>
                <th>Cant. comprometida</th>
                <th>Cant. existencia</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){
      foreach ($q1 as $reg1) {

        $Pedido             = $reg1 ['Pedido']; 
        $Item               = $reg1 ['Item'];
        $Cant_Pedida        = $reg1 ['Cant_Pedida'];
        $Cant_Comprometida  = $reg1 ['Cant_Comprometida'];
        $Cant_Existencia    = $reg1 ['Cant_Existencia']; 

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Pedido.'</td>
              <td>'.$Item.'</td>
              <td align="right">'.$Cant_Pedida.'</td>
              <td align="right">'.$Cant_Comprometida.'</td>
              <td align="right">'.$Cant_Existencia.'</td>
          </tr>';

        $i++; 

      }
    }else{
      $tabla .= ' 
        <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  </tbody>
        </table>';

    return $tabla;
  }

}

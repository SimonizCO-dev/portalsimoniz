<?php

//clase creada para funciones relacionadas con el modelo de reportes

class UtilidadesSuministros {

  public static function movimientospantalla($tipo, $consecutivo, $fecha_inicial, $fecha_final, $tercero, $item, $bodega_origen, $bodega_destino) {

    $condicion = "WHERE 1 = 1";

    if($tipo != null){
      $condicion .= " AND DOC.Id_Tipo_Docto = ".$tipo;
    }

    if($consecutivo != null){
      $condicion .= " AND DOC.Consecutivo = ".$consecutivo;
    }

    if($fecha_inicial != null && $fecha_final != null){
      $condicion .= " AND DOC.Fecha BETWEEN '".$fecha_inicial."' AND '".$fecha_final."'";
    }else{
      if($fecha_inicial != null && $fecha_final == null){
        $condicion .= " AND DOC.Fecha = '".$fecha_inicial."'";
      }
    }

    if($tercero != null){
      $condicion .= " AND DOC.Id_Tercero = ".$tercero;
    }

    if($item != null){
      $condicion .= " AND DET.Id_Item = ".$item;
    }

    if($bodega_origen != null){
      $condicion .= " AND DET.Id_Bodega_Org = ".$bodega_origen;
    }

    if($bodega_destino != null){
      $condicion .= " AND DET.Id_Bodega_Dst = ".$bodega_destino;
    }

    $query ="
      SELECT
      TD.Descripcion AS Desc_Tipo, 
      TD.Tipo AS Tipo, 
      DOC.Consecutivo AS Consecutivo,
      CONCAT (TER.Nit, ' - ', TER.Nombre) AS Tercero,
      DOC.Fecha AS Fecha,
      ED.Descripcion AS Estado_Docto,
      CONCAT (I.Id_Item, ' (', I.Referencia, ' - ', I.Descripcion, ')') AS Item,
      DET.Cantidad AS Cantidad,
      BO.Descripcion AS Bodega_Origen,
      BD.Descripcion AS Bodega_Destino,
      DET.Vlr_Unit_Item AS Vlr_Unit,
      UC.Usuario AS Usuario_Creacion,
      UA.Usuario AS Usuario_Actualizacion
      FROM T_PR_I_DOCTO_MOVTO DET
      LEFT JOIN T_PR_I_DOCTO DOC ON DET.Id_Docto = DOC.Id
      LEFT JOIN T_PR_I_TIPO_DOCTO TD ON DOC.Id_Tipo_Docto = TD.Id
      LEFT JOIN T_PR_I_ESTADO_DOCTO ED ON DOC.Id_Estado = ED.Id
      LEFT JOIN T_PR_I_ITEM I ON DET.Id_Item = I.Id
      LEFT JOIN T_PR_I_BODEGA BO ON DET.Id_Bodega_Org = BO.Id
      LEFT JOIN T_PR_I_BODEGA BD ON DET.Id_Bodega_Dst = BD.Id
      LEFT JOIN T_PR_USUARIO UC ON DET.Id_Usuario_Creacion = UC.Id_Usuario
      LEFT JOIN T_PR_USUARIO UA ON DET.Id_Usuario_Actualizacion = UA.Id_Usuario 
      LEFT JOIN T_PR_I_TERCERO TER ON DOC.Id_Tercero = TER.Id
      ".$condicion."
      ORDER BY 2, 1, 5
    ";

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Tipo</th>
                <th>Consecutivo</th>
                <th>Tercero</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Item</th>
                <th>Cantidad</th>
                <th>Vlr. unitario</th>
                <th>Bodega origen</th>
                <th>Bodega destino</th>
                <th>Usuario que creo</th>
                <th>Usuario que actualizó</th>
                </tr>
              </thead>
          <tbody>';

    $q1 = Yii::app()->db->createCommand($query)->queryAll();

    $i = 1; 

    if(!empty($q1)){

      foreach ($q1 as $reg) {

        $Tipo    = $reg ['Desc_Tipo']; 
        $Consecutivo       =  $reg ['Tipo'].'-'.$reg ['Consecutivo']; 
        $Tercero = $reg ['Tercero']; 
        $Tipo    = $reg ['Desc_Tipo']; 
        $Fecha  = $reg ['Fecha'];
        $Estado_Docto  = $reg ['Estado_Docto'];
        $Item  = $reg ['Item'];
        $Cantidad  = $reg ['Cantidad'];
        $Vlr_Unit  = $reg ['Vlr_Unit'];

        if(is_null($reg ['Bodega_Origen'])){
          $Bodega_Origen  = '-';
        }else{
          $Bodega_Origen  = $reg ['Bodega_Origen'];
        }

        if(is_null($reg ['Bodega_Destino'])){
          $Bodega_Destino  = '-';
        }else{
          $Bodega_Destino  = $reg ['Bodega_Destino'];
        }

        $Usuario_Creacion  = $reg ['Usuario_Creacion'];
        $Usuario_Actualizacion  = $reg ['Usuario_Actualizacion'];

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
        <tr class="'.$clase.'">
              <td>'.$Tipo.'</td>
              <td>'.$Consecutivo.'</td>
              <td>'.substr($Tercero,0, 39).'</td>
              <td>'.$Fecha.'</td>
              <td>'.$Estado_Docto.'</td>
              <td>'.substr($Item,0, 40).'</td>
              <td align="right">'.$Cantidad.'</td>
              <td align="right">'.number_format(($Vlr_Unit),0,".",",").'</td>
              <td>'.$Bodega_Origen.'</td>
              <td>'.$Bodega_Destino.'</td>
              <td>'.$Usuario_Creacion.'</td>
              <td>'.$Usuario_Actualizacion.'</td>
          </tr>';

        $i++; 

      }
   
    }else{
      $tabla .= ' 
        <tr><td colspan="12" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  
      </tbody></table>';

    return $tabla;

  }

  public static function cosinvtotpantalla() {

    $criteria=new CDbCriteria;
    $criteria->join = 'LEFT JOIN T_PR_I_LINEA l ON l.Id = t.Id_Linea';
    $criteria->condition='t.Total_Inventario <> 0 AND t.Vlr_Costo <> 0';
    $criteria->order='l.Descripcion ASC, t.Descripcion ASC';
    $items_exist=IItem::model()->findAll($criteria);

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Línea</th>
                <th>Item</th>
                <th>Costo unitario</th>
                <th>Cantidad</th>
                <th>Costo Total</th>
                </tr>
              </thead>
          <tbody>';

    $i = 1;
    $total = 0; 

    if(!empty($items_exist)){

      foreach ($items_exist as $reg) {

        $linea        = $reg->idlinea->Descripcion; 
        $item         = $reg->Id_Item.' ('.$reg->Referencia.' - '.$reg->Descripcion.')'; 
        $costo_unit   = $reg->Vlr_Costo / $reg->Total_Inventario; 
        $cantidad     = $reg->Total_Inventario;
        $costo_tot    = ($reg->Vlr_Costo / $reg->Total_Inventario) * $cantidad;

        $total = $total + $costo_tot;

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
          <tr class="'.$clase.'">
              <td>'.$linea.'</td>
              <td>'.$item.'</td>
              <td align="right">'.number_format(($costo_unit),2,".",",").'</td>
              <td align="right">'.$cantidad.'</td>
              <td align="right">'.number_format(($costo_tot),2,".",",").'</td>
          </tr>';

        $i++; 

      }

      $tabla .= '    
        <tr class="'.$clase.'">
            <td colspan="4" align="right"><strong>TOTAL</strong></td>
            <td align="right"><strong>'.number_format(($total),2,".",",").'</strong></td>
        </tr>';
   
    }else{
      $tabla .= ' 
        <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  
      </tbody></table>';

    return $tabla;

  }

  public static function cosinvbodpantalla($bodega) {

    $criteria=new CDbCriteria;
    $criteria->join = 'LEFT JOIN T_PR_I_ITEM i ON i.Id = t.Id_Item LEFT JOIN T_PR_I_LINEA l ON i.Id_Linea = l.Id';
    $criteria->condition='t.Id_Bodega=:bodega AND t.Cantidad > 0';
    $criteria->params=array(':bodega'=>$bodega);
    $criteria->order='l.Descripcion ASC, i.Descripcion ASC';
    $items_exist=IExistencia::model()->findAll($criteria);

    $desc_bodega = IBodega::model()->findByPk($bodega)->Descripcion;

    $tabla = '
      <table class="table table-sm table-hover">
              <thead>
                <tr>
                <th>Línea</th>
                <th>Item</th>
                <th>Costo unitario</th>
                <th>Cantidad</th>
                <th>Costo Total</th>
                </tr>
              </thead>
          <tbody>';

    $i = 1;
    $total = 0; 

    if(!empty($items_exist)){

      foreach ($items_exist as $reg) {

        $linea        = $reg->iditem->idlinea->Descripcion; 
        $item         = $reg->DescItem($reg->Id_Item); 
        $costo_unit   = $reg->iditem->Vlr_Costo / $reg->iditem->Total_Inventario; 
        $cantidad     = $reg->Cantidad;
        $costo_tot    = ($reg->iditem->Vlr_Costo / $reg->iditem->Total_Inventario) * $cantidad;

        $total = $total + $costo_tot;

        if ($i % 2 == 0){
          $clase = 'odd'; 
        }else{
          $clase = 'even'; 
        }

        $tabla .= '    
          <tr class="'.$clase.'">
              <td>'.$linea.'</td>
              <td>'.$item.'</td>
              <td align="right">'.number_format(($costo_unit),2,".",",").'</td>
              <td align="right">'.$cantidad.'</td>
              <td align="right">'.number_format(($costo_tot),2,".",",").'</td>
          </tr>';

        $i++; 

      }

      $tabla .= '    
        <tr class="'.$clase.'">
            <td colspan="4" align="right"><strong>TOTAL '.$desc_bodega.'</strong></td>
            <td align="right"><strong>'.number_format(($total),2,".",",").'</strong></td>
        </tr>';
   
    }else{
      $tabla .= ' 
        <tr><td colspan="5" class="empty"><span class="empty">No se encontraron resultados.</span></td></tr>
      ';
    }

    $tabla .= '  
      </tbody></table>';

    return $tabla;

  }

}

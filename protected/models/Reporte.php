<?php

class Reporte extends CFormModel 
{

    public $fecha_inicial;
    public $fecha_final;
    public $marca_inicial;
    public $marca_final;
    public $cliente_inicial;
    public $cliente_final;
    public $opcion_exp;
    public $origen;
    public $clase;
    public $canal;
    public $ev;
    public $des_ora_ini;
    public $des_ora_fin;
    public $reg;
    public $ruta_inicial;
    public $ruta_final;
    public $vendedor_inicial;
    public $vendedor_final;
    public $co;
    public $des_ora;
    public $archivo;
    public $cliente;
    public $ruta;
    public $estado;
    public $asesor;
    public $firma;
    public $asesor_ant;
    public $asesor_nue;
    public $fecha_ret;
    public $consecutivo;
    public $linea;
    public $marca;
    public $segmento;
    public $tipo;
    public $lista;
    public $linea_inicial;
    public $linea_final;
    public $valor;
    public $dias;
    public $clasificacion;
    public $recibos;
    public $opc_ver;
    public $fec_ver;
    public $fec_che;
    public $fecha_cheque;
    public $opc;
    public $fecha;
    public $item;
    public $plan;
    public $criterio;
    public $cons_inicial;
    public $cons_final;
    public $c_o;
    public $opcion;
    public $proveedor;
    public $periodo_inicial;
    public $periodo_final;
    public $cia;
    public $un_inicial;
    public $un_final;
    public $ev_inicial;
    public $ev_final;
    public $n_oc;
    public $anio;
    public $un;
    public $coordinador;
    public $periodo;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fecha_inicial, fecha_final', 'safe'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'rent_marca'),
            array('fecha_inicial, fecha_final, marca_inicial, marca_final, opcion_exp', 'required','on'=>'rent_marca_item'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'rent_oracle'),
            array('fecha_inicial, fecha_final, des_ora_ini, des_ora_fin, opcion_exp', 'required','on'=>'rent_oracle_item'),
            array('fecha_inicial, fecha_final, cliente_inicial, cliente_final, opcion_exp', 'required','on'=>'rent_cliente'),
            array('fecha_inicial, fecha_final, marca_inicial, marca_final, opcion_exp', 'required','on'=>'nivel_servicio_marca'),
            array('fecha_inicial, fecha_final, linea_inicial, linea_final, opcion_exp', 'required','on'=>'nivel_servicio_linea'),
            array('opcion_exp', 'required','on'=>'error_ept'),
            array('opcion_exp', 'required','on'=>'error_tal'),
            array('fecha, opcion_exp', 'required','on'=>'error_conectores'),
            array('opcion_exp', 'required','on'=>'verificacion_recibos'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'rent_marca_p'),
            array('opcion_exp', 'required','on'=>'inv_peru'),
            array('opcion_exp', 'required','on'=>'inv_ecuador'),
            array('opcion_exp', 'required','on'=>'inv_cos_peru'),
            array('opcion_exp', 'required','on'=>'inv_cos_ecuador'),
            array('fecha_inicial, fecha_final, marca_inicial, marca_final, opcion_exp', 'required','on'=>'rent_inv_marca_p'),
            array('fecha_inicial, fecha_final, marca_inicial, marca_final, opcion_exp', 'required','on'=>'pedidos_pend_des_marca_p'),
            array('marca, estado, opcion_exp', 'required','on'=>'pedidos_acum_marca_p'),
            array('consecutivo', 'required','on'=>'act_ept'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'fact_tiendas_web'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'desp_tiendas_web'),
            
            array('c_o, tipo, consecutivo', 'required','on'=>'remision_tu_go'),
            array('tipo, consecutivo', 'required','on'=>'elim_error_trans'),
            array('fecha, opcion_exp', 'required','on'=>'error_transf'),
            array('n_oc, opcion_exp', 'required','on'=>'log_crossdocking'),
            
            array('tipo, cons_inicial, cons_final', 'required','on'=>'comp_inc'),
        );  
    }

    public function searchByCliente($filtro) {
        
        $resp = Yii::app()->db->createCommand("
            SELECT TOP 10 C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND (C_NIT_CLIENTE LIKE '".$filtro."%' OR C_NOMBRE_CLIENTE LIKE '%".$filtro."%') GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
        ")->queryAll();
        return $resp;
        
    }

    public function DescCliente($id_cliente) {
        
        $q = Yii::app()->db->createCommand("SELECT C_NIT_CLIENTE, C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_ROWID_CLIENTE = '".$id_cliente."'")->queryRow();
        return $q['C_NIT_CLIENTE'].' - '.$q['C_NOMBRE_CLIENTE'];
        
    }

    public function NitCliente($id_cliente) {
        
        $q = Yii::app()->db->createCommand("SELECT C_NIT_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_ROWID_CLIENTE = '".$id_cliente."'")->queryRow();
        return $q['C_NIT_CLIENTE'];
        
    }

    public function RazonSocialCliente($id_cliente) {
        
        $q = Yii::app()->db->createCommand("SELECT C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_ROWID_CLIENTE = '".$id_cliente."'")->queryRow();
        return $q['C_NOMBRE_CLIENTE'];
        
    }

    public function searchByItem($filtro) {
        
        $resp = Yii::app()->db->createCommand("
            SELECT TOP 10 I_ID_ITEM, CONCAT (I_ID_ITEM, ' - ', I_DESCRIPCION) AS DESCR FROM T_CF_ITEMS WHERE I_CIA = 2 AND I_ESTADO = 'ACTIVO' AND (I_ID_ITEM LIKE '%".$filtro."%' OR I_DESCRIPCION LIKE '%".$filtro."%') ORDER BY DESCR 
        ")->queryAll();
        return $resp;
        
    }

    public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("
            SELECT I_ID_ITEM , CONCAT (I_ID_ITEM, ' - ', I_DESCRIPCION) AS DESCR FROM T_CF_ITEMS WHERE I_CIA = 2 AND I_ID_ITEM = '".$filtro."'")->queryAll();
        return $resp;

    }

    public function DescItem($Id_Item){

        $desc= Yii::app()->db->createCommand("
            SELECT CONCAT (I_ID_ITEM, ' - ', I_DESCRIPCION) AS DESCR FROM T_CF_ITEMS WHERE I_CIA = 2 AND I_ID_ITEM = ".$Id_Item)->queryRow();

        return $desc['DESCR'];

    }


    /*public function searchByCliente2($filtro) {
        
        $resp = Yii::app()->db->createCommand("
            SELECT TOP 10 C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM TH_CLIENTES WHERE C_CIA = 2 AND (C_NIT_CLIENTE LIKE '".$filtro."%' OR C_NOMBRE_CLIENTE LIKE '%".$filtro."%') GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
        ")->queryAll();
        return $resp;
        
    }
    */
    
    /*public function searchByClienteCart($filtro) {
        
        $resp = Yii::app()->db->createCommand("SELECT DISTINCT TOP 10 t2001.f200_razon_social AS CLIENTE FROM UnoEE1.dbo.t201_mm_clientes WITH (NOLOCK) INNER JOIN UnoEE1.dbo.t200_mm_terceros AS t2001 WITH (NOLOCK) ON t2001.f200_rowid = f201_rowid_tercero WHERE f200_id_cia = 2 AND t2001.f200_razon_social LIKE '%".$filtro."%' order by CLIENTE
        ")->queryAll();
        return $resp;
        
    }

    public function searchByClienteCartNit($filtro) {
        
        $resp = Yii::app()->db->createCommand("SELECT DISTINCT TOP 10 t2001.f200_nit AS NIT, t2001.f200_razon_social AS CLIENTE FROM UnoEE1.dbo.t201_mm_clientes WITH (NOLOCK) INNER JOIN UnoEE1.dbo.t200_mm_terceros AS t2001 WITH (NOLOCK) ON t2001.f200_rowid = f201_rowid_tercero WHERE f200_id_cia = 2 AND (t2001.f200_nit LIKE '%".$filtro."%' OR t2001.f200_razon_social LIKE '%".$filtro."%') order by CLIENTE
        ")->queryAll();
        return $resp;
        
    }*/

    /*

    public function searchByItem($filtro) {
        
        $resp = Yii::app()->db->createCommand("
            SELECT DISTINCT TOP 10  
            f120_id AS ID,
            CONCAT(f120_id,' - ',f120_descripcion) AS DESCR
            FROM UnoEE1..t120_mc_items
            INNER JOIN UnoEE1..t121_mc_items_extensiones ON f120_rowid = f121_rowid_item
            WHERE f120_id_cia = 2 AND (f120_id LIKE '%".$filtro."%' OR f120_descripcion  LIKE '%".$filtro."%') ORDER BY DESCR 
        ")->queryAll();
        return $resp;
        
    }

    public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("

            SELECT
            f120_id AS ID,
            CONCAT(f120_id,' - ',f120_descripcion) AS DESCR
            FROM UnoEE1..t120_mc_items
            INNER JOIN UnoEE1..t121_mc_items_extensiones ON f120_rowid = f121_rowid_item
            WHERE f120_id_cia = 2 AND f120_id = '".$filtro."'")->queryAll();
        return $resp;

    }

    public function DescItem($id_item) {
        
        $q = Yii::app()->db->createCommand("
            SELECT  
            f120_id AS ID,
            CONCAT(f120_id,' - ',f120_descripcion) AS DESCR
            FROM UnoEE1..t120_mc_items
            INNER JOIN UnoEE1..t121_mc_items_extensiones ON f120_rowid = f121_rowid_item
            WHERE f120_id_cia = 2 AND f120_id = '".$id_item."' 
        ")->queryRow();
        
        return $q['DESCR'];
        
    }

    */


    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'fecha_inicial'=>'Fecha inicial',
            'fecha_final'=>'Fecha final',
            'marca_inicial'=>'Marca inicial',
            'marca_final'=>'Marca final',
            'cliente_inicial'=>'Cliente inicial',
            'cliente_final'=>'Cliente final',
            'opcion_exp'=>'Ver en',
            'origen' => 'Origen',
            'clase' => 'Clase',
            'canal' => 'Canal',
            'ev' => 'Estructura de ventas',
            'des_ora_ini'=>'Desc. oracle inicial',
            'des_ora_fin'=>'Desc. oracle final',
            'reg'=>'Regional',
            'ruta_inicial'=>'Ruta inicial',
            'ruta_final'=>'Ruta final',
            'vendedor_inicial'=>'Vendedor inicial',
            'vendedor_final'=>'Vendedor final',
            'co'=>'Centro de operación',
            'des_ora'=>'Desc. oracle',
            'archivo' => 'Archivo',
            'firma' => 'Firma',
            'asesor_ant' => 'Asesor antiguo',
            'asesor_nue' => 'Asesor nuevo',
            'fecha_ret' => 'Fecha de retiro',
            'consecutivo' => 'Consecutivo',
            'linea' => 'Línea',
            'marca' => 'Marca',
            'segmento' => 'Segmento',
            'tipo' => 'Tipo',
            'linea_inicial' => 'Línea inicial',
            'linea_final' => 'Línea final',
            'valor' => 'Valor',
            'dias' => 'Días',
            'clasificacion' => 'Clasificación',
            'recibos' => 'Recibos',
            'fecha_cheque' => 'Fecha canje',
            'fecha' => 'Fecha',
            'item' => 'Item',
            'plan' => 'Plan', 
            'criterio' => 'Criterio',
            'cons_inicial' => 'Consecutivo inicial',
            'cons_final' => 'Consecutivo final',
            'c_o'=>'CO',
            'opcion' => 'Opción',
            'periodo_inicial' => 'Periodo inicial',
            'periodo_final' => 'Periodo final',
            'cia'=>'Compañia',
            'un_inicial' => 'Unidad de negocio inicial',
            'un_final' => 'Unidad de negocio final',
            'ev_inicial' => 'Est. de venta inicial',
            'ev_final' => 'Est. de venta final',
            'n_oc' => '# Orden(es) de compra',
            'anio' => 'Año',
            'un' => 'Unidad de negocio',
            'coordinador' => 'Coordinador',
            'periodo' => 'Periodo',
        );
    }

}
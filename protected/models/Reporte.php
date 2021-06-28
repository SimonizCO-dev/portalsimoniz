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
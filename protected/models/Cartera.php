<?php

class Cartera extends CFormModel 
{
    public $anio;
    public $archivo;
    public $asesor;
    public $asesor_ant;
    public $asesor_nue;
    
    public $canal;
    public $cia;
    public $clase;
    public $clasificacion;
    public $cliente;
    public $cliente_inicial;
    public $cliente_final;
    public $co;
    
    public $consecutivo;
    public $cons_inicial;
    public $cons_final;
    public $coordinador;
    public $criterio;

    public $des_ora;
    public $des_ora_ini;
    public $des_ora_fin;
    public $dias;
    
    public $estado;
    public $ev;
    public $ev_inicial;
    public $ev_final;
    
    public $fecha;
    public $fecha_cheque;
    public $fecha_inicial;
    public $fecha_final;
    public $fecha_ret;
    public $firma;

    public $item;

    public $lista;
    public $linea;
    public $linea_inicial;
    public $linea_final;
    
    public $marca;
    public $marca_inicial;
    public $marca_final;

    public $n_oc;
    
    public $opcion;
    public $opcion_exp;
    public $origen;
    
    public $periodo;
    public $periodo_inicial;
    public $periodo_final;
    public $plan;
    public $proveedor;
    
    public $recibos;
    public $reg;
    public $ruta;
    public $ruta_inicial;
    public $ruta_final;

    public $segmento;

    public $tipo;

    public $un;
    public $un_inicial;
    public $un_final;

    public $valor;
    public $vendedor_inicial;
    public $vendedor_final;
    
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fecha_inicial, fecha_final', 'safe'),
            array('ruta, estado', 'required','on'=>'actualizacion_datos_saldo'),
            array('ruta, estado', 'required','on'=>'actualizacion_datos'),
            array('ruta, fecha_ret, asesor_ant, firma', 'required','on'=>'cambio_asesor'),
            array('ruta_inicial, ruta_final, estado, valor, dias, firma', 'required','on'=>'cobro_prejuridico'),
            array('ruta, firma', 'required','on'=>'saldo_cliente'),
            array('opcion_exp', 'required','on'=>'consulta_pagos'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'cruce_ant_cli'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'cruce_not_con'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'docs_asesor'),
            array('fecha_inicial, fecha_final, cliente', 'required','on'=>'hist_cliente'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'notas_anulacion'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'cruce_not_car'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'notas_credito'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'notas_devolucion'),
            array('fecha_inicial, fecha_final, vendedor_inicial, vendedor_final, opcion_exp', 'required','on'=>'recaudos_vendedor'),
            array('fecha_inicial, fecha_final', 'required','on'=>'rec_x_web_service'),
            array('cliente_inicial, cliente_final', 'required','on'=>'saldo_cartera_cliente'),
            array('ev, opcion_exp', 'required','on'=>'saldo_cartera_ev'),
            array('ruta_inicial, ruta_final', 'required','on'=>'saldo_cartera_ruta'),
            array('vendedor_inicial, vendedor_final', 'required','on'=>'saldo_cartera_vendedor'),
            array('co', 'required','on'=>'saldo_cartera_co'),
            
        );  
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'anio' => 'Año',
            'archivo' => 'Archivo',
            'asesor' => 'Asesor',
            'asesor_ant' => 'Asesor antiguo',
            'asesor_nue' => 'Asesor nuevo',

            'canal' => 'Canal',
            'cia'=>'Compañia',
            'clase' => 'Clase',
            'clasificacion' => 'Clasificación',
            'cliente' => 'Cliente',
            'cliente_inicial'=>'Cliente inicial',
            'cliente_final'=>'Cliente final',
            'co'=>'Centro de operación',
            'consecutivo' => 'Consecutivo',
            'cons_inicial' => 'Consecutivo inicial',
            'cons_final' => 'Consecutivo final',
            'coordinador' => 'Coordinador',
            'criterio' => 'Criterio',

            'des_ora'=>'Desc. oracle',
            'des_ora_ini'=>'Desc. oracle inicial',
            'des_ora_fin'=>'Desc. oracle final',
            'dias' => 'Días',

            'estado' => 'Estado',
            'ev' => 'Estructura de ventas',
            'ev_inicial' => 'Est. de venta inicial',
            'ev_final' => 'Est. de venta final',

            'fecha' => 'Fecha',
            'fecha_cheque' => 'Fecha canje',
            'fecha_inicial'=>'Fecha inicial',
            'fecha_final'=>'Fecha final',
            'fecha_ret' => 'Fecha de retiro',
            'firma' => 'Firma',

            'item' => 'Item',

            'lista' => 'Lista',
            'linea' => 'Línea',
            'linea_inicial' => 'Línea inicial',
            'linea_final' => 'Línea final',

            'marca' => 'Marca',
            'marca_inicial'=>'Marca inicial',
            'marca_final'=>'Marca final',

            'n_oc' => '# Orden(es) de compra',
            
            'opcion' => 'Opción',
            'opcion_exp'=>'Ver en',
            'origen' => 'Origen',
            
            'periodo' => 'Periodo',
            'periodo_inicial' => 'Periodo inicial',
            'periodo_final' => 'Periodo final',
            'plan' => 'Plan',
            'proveedor' => 'Proveedor',
            
            'recibos' => 'Recibos',
            'reg'=>'Regional',
            'ruta'=>'Ruta',
            'ruta_inicial'=>'Ruta inicial',
            'ruta_final'=>'Ruta final',

            'segmento' => 'Segmento',

            'tipo' => 'Tipo',

            'un' => 'Unidad de negocio',
            'un_inicial' => 'Unidad de negocio inicial',
            'un_final' => 'Unidad de negocio final',

            'valor' => 'Valor',
            'vendedor_inicial'=>'Vendedor inicial',
            'vendedor_final'=>'Vendedor final',
                      
        );
    }

}
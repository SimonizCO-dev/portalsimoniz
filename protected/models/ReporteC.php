<?php

class ReporteC extends CFormModel 
{

    public $opcion_exp;
    public $ruta;
    public $recibos;
    public $opc_ver;
    public $fec_ver;
    public $obs_ver;
    public $fec_che;
    public $fecha_cheque;
    public $opc;
    public $fecha;
    public $fecha_inicial;
    public $fecha_final;
    public $obs_rec;

    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('recibos', 'required','on'=>'carga_recibos'),
            //array('recibos', 'required','on'=>'verif_recibos'),
            array('recibos', 'required','on'=>'aplic_recibos'),
            array('recibos', 'required','on'=>'ent_fis_recibos'),
            array('opcion_exp', 'required','on'=>'verificacion_recibos'),
            array('fecha_inicial, fecha_final, opcion_exp', 'required','on'=>'control_recibos'),
        );  
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'opcion_exp'=>'Exportar a',
            'recibos' => 'Recibos',
            'fecha_cheque' => 'Fecha canje',
            'fecha' => 'Fecha',
        );
    }

}
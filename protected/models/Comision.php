<?php

class Comision extends CFormModel 
{
    public $tipo;
    public $liquidacion;
    public $id_vendedor;
    public $mes;
    public $anio;
    public $observaciones; 
    public $recibo;
    public $documento;  
    
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('mes, anio, tipo, liquidacion, observaciones', 'required','on'=>'liquidacion'), 
            array('id_vendedor, recibo', 'required','on'=>'ajuste'),
            array('tipo, documento', 'required','on'=>'historico'),            
        );  
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'tipo'=>'Tipo',
            'liquidacion'=>'Liquidación',
            'id_vendedor'=>'Vendedor',
            'mes'=>'Mes',
            'anio'=>'Año',
            'observaciones'=>'Observaciones',
            'recibo'=>'Recibo',
            'documento'=>'Documento',
        );
    }

}
<?php

class ReportePa extends CFormModel 
{
    
    public $opc;
    public $equipo;
    public $empresa_compra;
    public $tipo_equipo;
    public $fecha_compra_inicial;
    public $fecha_compra_final;
    public $inc_lic;
    public $opcion_exp;
    public $tipo_lic;
    public $version;
    public $clasif;

    public function rules() {
        
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fecha_factura_inicial, fecha_factura_final', 'safe'),
            array('opc', 'required','on'=>'zip_soportes'),
            array('opcion_exp', 'required','on'=>'lic_disp'),
            array('tipo_lic, opcion_exp', 'required','on'=>'lic_equipos'),
            array('opc', 'required','on'=>'zip_e'), 
            array('opc', 'required','on'=>'zip_l'),
            array('opcion_exp', 'required','on'=>'lic_venc'),                 
        );  
    }

    public function searchByEquipo($filtro) {
       
        $resp = Yii::app()->db->createCommand("
            SELECT TOP 10 t.Id_Equipo, t.Serial, d.Dominio AS T_Equipo, e.Descripcion AS Empresa FROM T_PR_EQUIPO t 
            LEFT JOIN T_PR_DOMINIO d ON t.Tipo_Equipo = d.Id_Dominio
            LEFT JOIN T_PR_PA_EMPRESA e ON t.Empresa_Compra = e.Id_Pa_Empresa
            WHERE (t.Serial LIKE '%".$filtro."%')
            ORDER BY t.Serial
        ")->queryAll();
        return $resp;
        
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'opc' => 'Opción',
            'equipo'=>'Serial',
            'empresa_compra'=> 'Empresa que compra',
            'tipo_equipo'=>'Tipo de equipo',
            'fecha_compra_inicial'=>'Fecha compra inicial',
            'fecha_compra_final'=>'Fecha compra final',
            'inc_lic'=>'Incluir licencia(s)',
            'opcion_exp'=>'Exportar a',
            'tipo_lic'=>'Tipo de licencia',
            'version'=>'Versión',
            'clasif'=>'Clasif.',
        );
    }

}
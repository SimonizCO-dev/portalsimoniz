<?php

/**
 * This is the model clASs for table "T_PR_C_CONTROL_CMS".
 *
 * The followings are the available columns in table 'T_PR_C_CONTROL_CMS':
 * @property integer $ROWID
 * @property integer $ID_BASE
 * @property integer $MES
 * @property integer $ANIO
 * @property integer $TIPO
 * @property integer $LIQUIDACION
 * @property integer $VENDEDOR
 * @property string $OBSERVACION
 * @property integer $ESTADO
 * @property integer $ID_USUARIO_CREACION
 * @property integer $ID_USUARIO_ACTUALIZACION
 * @property string $FECHA_CREACION
 * @property string $FECHA_ACTUALIZACION
 *
 * The followings are the available model relatiONs:
 * @property THUSUARIO $iDUSUARIOCREACION
 * @property THUSUARIO $iDUSUARIOACTUALIZACION
 * @property THDOMINIO $tIPO
 */
class CControlCms extends CActiveRecord
{
	public $Correos_Notif;

	/**
	 * @return string the ASsociated databASe table name
	 */
	public function tableName()
	{
		return 'T_PR_C_CONTROL_CMS';
	}

	/**
	 * @return array validatiON rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should ONly define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ID_BASE, MES, ANIO, TIPO, LIQUIDACION, OBSERVACION, ESTADO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION, FECHA_CREACION, FECHA_ACTUALIZACION', 'required'),
			array('ID_BASE, MES, ANIO, TIPO, LIQUIDACION, VENDEDOR, ESTADO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo PleASe remove those attributes that should not be searched.
			array('ROWID, ID_BASE, MES, ANIO, TIPO, LIQUIDACION, VENDEDOR, OBSERVACION, ESTADO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION, FECHA_CREACION, FECHA_ACTUALIZACION', 'safe', 'on'=>'search'),
		);
	}

	public function Desc_Liq($opc){

        switch ($opc) {
			case 1:
			    return 'INDIVIDUAL'; 
			    break;
			case 2:
			    return 'TODOS LOS VENDEDORES';  
			    break;
		}

    }

    public function Desc_Vend($id_vend){

        $q_vend = Yii::app()->db->createCommand("
            SELECT CONCAT(NIT_VENDEDOR,' - ',NOMBRE_VENDEDOR) AS VENDEDOR FROM T_PR_C_VENDEDORES WHERE ROWID = ".$id_vend)->queryRow();
        return $q_vend['VENDEDOR'];

    }

    public function Desc_Mes($mes){

        switch ($mes) {
			case 1:
			    return 'ENERO'; 
			    break;
			case 2:
			    return 'FEBRERO';  
			    break;
			case 3:
			    return 'MARZO';  
			    break;
			case 4:
			    return 'ABRIL';  
			    break;
			case 5:
			    return 'MAYO'; 
			    break;
			case 6:
			    return 'JUNIO';  
			    break;
			case 7:
			    return 'JULIO';  
			    break;
			case 8:
			    return 'AGOSTO';  
			    break;
			case 9:
			    return 'SEPTIEMBRE'; 
			    break;
			case 10:
			    return 'OCTUBRE';  
			    break;
			case 11:
			    return 'NOVIEMBRE';  
			    break;
			case 12:
			    return 'DICIEMBRE';  
			    break;

		}

    }

    public function searchByDoc($filtro, $tipo) {
       
        if($tipo == 1){
        	//FACTURA
        	$resp = Yii::app()->db->createCommand("SELECT DISTINCT TOP 10 f350_rowid AS Row_Id, CONCAT(f350_id_co,'-',f350_id_tipo_docto,'-',f350_consec_docto) AS Documento FROM UnoEE1..t350_co_docto_contable INNER JOIN T_PR_C_VENTAS AS t3 ON f350_rowid = t3.ROWID_FACTURA WHERE f350_id_cia = 2 AND CONCAT(f350_id_co,'-',f350_id_tipo_docto,'-',f350_consec_docto) LIKE '%".$filtro."%' ORDER BY Documento")->queryAll();
        }

        if($tipo == 2){
    		$resp = Yii::app()->db->createCommand("SELECT DISTINCT TOP 10 f350_rowid AS Row_Id, CONCAT(f350_id_co,'-',f350_id_tipo_docto,'-',f350_consec_docto) AS Documento FROM UnoEE1..t350_co_docto_contable INNER JOIN T_PR_C_RECIBOS AS t2 ON f350_rowid = t2.ROWID_RECIBO WHERE f350_id_cia = 2 AND CONCAT(f350_id_co,'-',f350_id_tipo_docto,'-',f350_consec_docto) LIKE '%".$filtro."%' ORDER BY Documento")->queryAll();
        }

        return $resp;

    }

	/**
	 * @return array relatiONal rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relatiON name and the related
		// clASs name for the relatiONs automatically generated below.
		return array(
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_CREACION'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_ACTUALIZACION'),
			'tipo' => array(self::BELONGS_TO, 'DOMINIO', 'TIPO'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ROWID' => 'ID',
			'ID_BASE'=>'ID de liquidación',
			'MES' => 'Mes',
			'ANIO' => 'Año',
			'TIPO' => 'Tipo',
			'LIQUIDACION' => 'Liquidación',
			'VENDEDOR' => 'Vendedor',
			'OBSERVACION' => 'Observaciones',
			'ESTADO' => 'Estado',
			'ID_USUARIO_CREACION' => 'Usuario que creo',
			'ID_USUARIO_ACTUALIZACION' => 'Usuario que actualizó',
			'FECHA_CREACION' => 'Fecha de creación',
			'FECHA_ACTUALIZACION' => 'Ultima fecha de actualización',
			'usuario_creacion' => 'Usuario que creo',
			'usuario_actualizacion' => 'Ultimo usuario que actualizó',
			'Correos_Notif' => 'E-mail(s) adic. para envió',
		);
	}

	/**
	 * Retrieves a list of models bASed ON the current search/filter cONditiONs.
	 *
	 * Typical usecASe:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - PASs data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * bASed ON the search/filter cONditiONs.
	 */
	public function search()
	{
		// @todo PleASe modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('idusuariocre','idusuarioact');

		$criteria->compare('t.ROWID',$this->ROWID);
		$criteria->compare('t.ID_BASE',$this->ID_BASE);
		$criteria->compare('t.MES',$this->MES);
		$criteria->compare('t.ANIO',$this->ANIO);
		$criteria->compare('t.TIPO',$this->TIPO);
		$criteria->compare('t.LIQUIDACION',$this->LIQUIDACION);
		$criteria->compare('t.VENDEDOR',$this->VENDEDOR);
		$criteria->compare('t.OBSERVACION',$this->OBSERVACION,true);

		if($this->ESTADO != ""){
			$criteria->AddCondition("t.ESTADO = '".$this->ESTADO."'"); 
	    }

	    if($this->FECHA_CREACION != ""){
      		$fci = $this->FECHA_CREACION." 00:00:00";
      		$fcf = $this->FECHA_CREACION." 23:59:59";

      		$criteria->addBetweenCondition('t.FECHA_CREACION', $fci, $fcf);
    	}

    	if($this->ID_USUARIO_CREACION != ""){
			$criteria->AddCondition("t.ID_USUARIO_CREACION = ".$this->ID_USUARIO_CREACION); 
	    }

    	if($this->FECHA_ACTUALIZACION != ""){
      		$fai = $this->FECHA_ACTUALIZACION." 00:00:00";
      		$faf = $this->FECHA_ACTUALIZACION." 23:59:59";

      		$criteria->addBetweenCondition('t.FECHA_ACTUALIZACION', $fai, $faf);
    	}

    	if($this->ID_USUARIO_ACTUALIZACION != ""){
			$criteria->AddCondition("t.ID_USUARIO_ACTUALIZACION = ".$this->ID_USUARIO_ACTUALIZACION); 
	    }

	    $criteria->order = 't.ROWID DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),	
		));
	}

	/**
	 * Returns the static model of the specified AR clASs.
	 * PleASe note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $clASsName active record clASs name.
	 * @return CCONtrolCms the static model clASs
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
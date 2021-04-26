<?php

/**
 * This is the model class for table "T_PR_C_VENDEDORES".
 *
 * The followings are the available columns in table 'T_PR_C_VENDEDORES':
 * @property integer $ID
 * @property integer $ROWID
 * @property string $NIT_VENDEDOR
 * @property string $NOMBRE_VENDEDOR
 * @property string $ID_VENDEDOR
 * @property string $RECIBO
 * @property string $RUTA
 * @property string $ESTADO
 * @property string $NOMBRE_RUTA
 * @property string $PORTAFOLIO
 * @property string $NIT_SUPERVISOR
 * @property string $NOMBRE_SUPERVISOR
 * @property integer $TIPO
 * @property integer $ID_USUARIO_ACTUALIZACION
 * @property string $FECHA_ACTUALIZACION
 * @property string $EMAIL
 * @property string $EMAIL_PERSONAL
 * @property string $TELEFONO
 * @property string $CIUDAD
 *
 * The followings are the available model relations:
 * @property THDOMINIO $tIPO
 * @property THUSUARIO $iDUSUARIOACTUALIZACION
 */
class CVendedores extends CActiveRecord
{
	public $archivo;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_C_VENDEDORES';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('EMAIL_PERSONAL, TIPO', 'required', 'on'=>'update'),
			array('TELEFONO, CIUDAD', 'required', 'on'=>'update2'),
			array('archivo','required','on'=>'imp'),
			array('EMAIL, EMAIL_PERSONAL','email', 'message'=>'E-mail no valido.'),
			array('ROWID, TIPO, ID_USUARIO_ACTUALIZACION', 'numerical', 'integerOnly'=>true),
			array('NIT_VENDEDOR, NIT_SUPERVISOR', 'length', 'max'=>25),
			array('NOMBRE_VENDEDOR, NOMBRE_SUPERVISOR, EMAIL, EMAIL_PERSONAL', 'length', 'max'=>100),
			array('ID_VENDEDOR', 'length', 'max'=>10),
			array('RECIBO', 'length', 'max'=>3),
			array('RUTA', 'length', 'max'=>20),
			array('ESTADO', 'length', 'max'=>8),
			array('NOMBRE_RUTA, TELEFONO, CIUDAD', 'length', 'max'=>50),
			array('PORTAFOLIO', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, ROWID, NIT_VENDEDOR, NOMBRE_VENDEDOR, ID_VENDEDOR, RECIBO, RUTA, ESTADO, NOMBRE_RUTA, PORTAFOLIO, NIT_SUPERVISOR, NOMBRE_SUPERVISOR, TIPO, ID_USUARIO_ACTUALIZACION, FECHA_ACTUALIZACION, EMAIL, EMAIL_PERSONAL, TELEFONO, CIUDAD', 'safe', 'on'=>'search'),
		);
	}

	public function searchByVendedor($filtro, $tipo) {
       
        $resp = Yii::app()->db->createCommand("
            SELECT TOP 10 ROWID, CONCAT(NIT_VENDEDOR,' - ',NOMBRE_VENDEDOR) AS VENDEDOR FROM T_PR_C_VENDEDORES WHERE (NIT_VENDEDOR LIKE '%".$filtro."%' OR NOMBRE_VENDEDOR LIKE '%".$filtro."%') AND TIPO = ".$tipo." ORDER BY 2")->queryAll();
        return $resp;
        
    }


	public function searchByVend($filtro) {
       
        $resp = Yii::app()->db->createCommand("
            SELECT TOP 10 ROWID, CONCAT(NIT_VENDEDOR,' - ',NOMBRE_VENDEDOR) AS VENDEDOR FROM T_PR_C_VENDEDORES WHERE (NIT_VENDEDOR LIKE '%".$filtro."%' OR NOMBRE_VENDEDOR LIKE '%".$filtro."%') ORDER BY 2")->queryAll();
        return $resp;
        
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_ACTUALIZACION'),
			'tipo' => array(self::BELONGS_TO, 'Dominio', 'TIPO'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'ROWID' => 'ROW ID',
			'NIT_VENDEDOR' => 'Nit',
			'NOMBRE_VENDEDOR' => 'Nombre',
			'ID_VENDEDOR' => 'ID Vendedor',
			'RECIBO' => 'Recibo',
			'RUTA' => 'Ruta',
			'ESTADO' => 'Estado',
			'NOMBRE_RUTA' => 'Nombre ruta',
			'PORTAFOLIO' => 'Portafolio',
			'NIT_SUPERVISOR' => 'Nit supervisor',
			'NOMBRE_SUPERVISOR' => 'Nombre supervisor',
			'TIPO' => 'Tipo',
			'ID_USUARIO_ACTUALIZACION' => 'Ultimo usuario que actualizó',
			'FECHA_ACTUALIZACION' => 'Ultima fecha de actualización',
			'EMAIL' => 'E-mail',
			'EMAIL_PERSONAL' => 'E-mail personal',
			'TELEFONO' => 'Celular',
			'CIUDAD' => 'Ciudad',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('idusuarioact');


	   	$criteria->compare('t.ID',$this->ID);
		$criteria->compare('t.ROWID',$this->ROWID);
		$criteria->compare('t.NIT_VENDEDOR',$this->NIT_VENDEDOR,true);
		$criteria->compare('t.NOMBRE_VENDEDOR',$this->NOMBRE_VENDEDOR,true);
		$criteria->compare('t.ID_VENDEDOR',$this->ID_VENDEDOR,true);
		$criteria->compare('t.RUTA',$this->RUTA,true);
		$criteria->compare('t.NOMBRE_RUTA',$this->NOMBRE_RUTA,true);
		$criteria->compare('t.NIT_SUPERVISOR',$this->NIT_SUPERVISOR,true);
		$criteria->compare('t.NOMBRE_SUPERVISOR',$this->NOMBRE_SUPERVISOR,true);
		$criteria->compare('t.TIPO',$this->TIPO);
		$criteria->compare('t.EMAIL',$this->EMAIL,true);
		$criteria->compare('t.EMAIL_PERSONAL',$this->EMAIL_PERSONAL,true);
		$criteria->compare('t.TELEFONO',$this->TELEFONO,true);
		$criteria->compare('t.CIUDAD',$this->CIUDAD,true);

		if($this->ESTADO != ""){
			$criteria->AddCondition("t.ESTADO = '".$this->ESTADO."'"); 
	    }

    	if($this->FECHA_ACTUALIZACION != ""){
      		$fai = $this->FECHA_ACTUALIZACION." 00:00:00";
      		$faf = $this->FECHA_ACTUALIZACION." 23:59:59";

      		$criteria->addBetweenCondition('t.FECHA_ACTUALIZACION', $fai, $faf);
    	}

    	if($this->ID_USUARIO_ACTUALIZACION != ""){
			$criteria->AddCondition("t.ID_USUARIO_ACTUALIZACION = ".$this->ID_USUARIO_ACTUALIZACION); 
	    }

	    $criteria->order = 't.NOMBRE_VENDEDOR ASC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),	
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CVendedores the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

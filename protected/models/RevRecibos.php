<?php

/**
 * This is the model class for table "T_PR_REV_RECIBOS".
 *
 * The followings are the available columns in table 'T_PR_REV_RECIBOS':
 * @property integer $Id_Reversion
 * @property integer $Id_Control
 * @property integer $Id_Usuario_Rev
 * @property string $Fecha_Hora_Rev
 * @property integer $Opc
 * @property integer $Id_Usuario_Verif
 * @property string $Fecha_Hora_Verif
 * @property integer $Id_Usuario_Aplic
 * @property string $Fecha_Hora_Aplic
 *
 * The followings are the available model relations:
 * @property THCONTROLRECIBOS $idControl
 * @property THUSUARIOS $idUsuarioRev
 * @property THUSUARIOS $idUsuarioVerif
 * @property THUSUARIOS $idUsuarioAplic
 */
class RevRecibos extends CActiveRecord
{
	
	public $recibo;
	public $fecha_rev;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_REV_RECIBOS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Control, Id_Usuario_Rev, Fecha_Hora_Rev, Opc, Id_Usuario_Verif, Fecha_Hora_Verif', 'required'),
			array('Id_Control, Id_Usuario_Rev, Opc, Id_Usuario_Verif, Id_Usuario_Aplic', 'numerical', 'integerOnly'=>true),
			array('Fecha_Hora_Aplic', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Reversion, Id_Control, Id_Usuario_Rev, Fecha_Hora_Rev, Opc, Id_Usuario_Verif, Fecha_Hora_Verif, Id_Usuario_Aplic, Fecha_Hora_Aplic, recibo, fecha_rev', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idcontrol' => array(self::BELONGS_TO, 'ControlRecibos', 'Id_Control'),
			'idusuariorev' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Rev'),
			'idusuarioverif' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Verif'),
			'idusuarioaplic' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Aplic'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Reversion' => 'ID',
			'Id_Control' => 'Recibo',
			'Id_Usuario_Rev' => 'Usuario que revirtió',
			'usuario_rev' => 'Usuario que revirtió',
			'Fecha_Hora_Rev' => 'Fecha y hora de reversión',
			'Opc' => 'Estado antes de revertir',
			'Id_Usuario_Verif' => 'Usuario que verificó',
			'usuario_verif' => 'Usuario que verificó',
			'Fecha_Hora_Verif' => 'Fecha y hora de verificación',
			'Id_Usuario_Aplic' => 'Usuario que aplicó',
			'usuario_aplic' => 'Usuario que aplicó',
			'Fecha_Hora_Aplic' => 'Fecha y hora de aplicación',
			'recibo' => 'Recibo',
			'fecha_rev' => 'Fecha de reversión',
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
	   	$criteria->with=array('idcontrol','idusuariorev');

	   	if($this->recibo != ""){
			$criteria->AddCondition("idcontrol.Recibo LIKE '%".$this->recibo."%'"); 
	    }

	    if($this->Id_Usuario_Rev != ""){
			$criteria->AddCondition("t.Id_Usuario_Rev = ".$this->Id_Usuario_Rev); 
	    }

	    if($this->fecha_rev != ""){
      		$fci = $this->fecha_rev." 00:00:00";
      		$fcf = $this->fecha_rev." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Rev', $fci, $fcf);
    	}

    	$criteria->compare('t.Opc',$this->Opc);

    	$criteria->order = 't.Fecha_Hora_Rev DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RevRecibos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

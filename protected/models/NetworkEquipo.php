<?php

/**
 * This is the model class for table "T_PR_NETWORK_EQUIPO".
 *
 * The followings are the available columns in table 'T_PR_NETWORK_EQUIPO':
 * @property integer $Id_Net_Equ
 * @property integer $Id_Equipo
 * @property integer $Id_Network
 * @property string $Notas
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class NetworkEquipo extends CActiveRecord
{
	public $ip;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_NETWORK_EQUIPO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Equipo, Id_Network, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Id_Equipo, Id_Network, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Notas', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Net_Equ, Id_Equipo, Id_Network, Notas, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
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
			'idequipo' => array(self::BELONGS_TO, 'Equipo', 'Id_Equipo'),
			'idnetwork' => array(self::BELONGS_TO, 'Network', 'Id_Network'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Net_Equ' => 'Id Net Equ',
			'Id_Equipo' => 'Id Equipo',
			'Id_Network' => 'Id Network',
			'Notas' => 'Notas',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Id Usuario Creacion',
			'Fecha_Creacion' => 'Fecha Creacion',
			'Id_Usuario_Actualizacion' => 'Id Usuario Actualizacion',
			'Fecha_Actualizacion' => 'Fecha Actualizacion',
			'ip' => 'IP',

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

		$criteria->compare('t.Id_Net_Equ',$this->Id_Net_Equ);
		$criteria->compare('t.Id_Equipo',$this->Id_Equipo);
		$criteria->compare('t.Id_Network',$this->Id_Network);
		$criteria->compare('t.Notas',$this->Notas,true);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->order = 't.Estado DESC, t.Fecha_Actualizacion DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NetworkEquipo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

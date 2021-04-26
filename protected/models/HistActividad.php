<?php

/**
 * This is the model class for table "TH_HIST_ACTIVIDAD".
 *
 * The followings are the available columns in table 'TH_HIST_ACTIVIDAD':
 * @property integer $Id
 * @property integer $Id_Actividad
 * @property string $Texto
 * @property string $Fecha_Registro
 * @property integer $Id_Usuario_Registro
 *
 * The followings are the available model relations:
 * @property THACTIVIDAD $idActividad
 * @property THUSUARIOS $idUsuarioRegistro
 */
class HistActividad extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_HIST_ACTIVIDAD';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Actividad, Texto, Fecha_Registro, Id_Usuario_Registro', 'required'),
			array('Id_Actividad, Id_Usuario_Registro', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Actividad, Texto, Fecha_Registro, Id_Usuario_Registro', 'safe', 'on'=>'search'),
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
			'idactividad' => array(self::BELONGS_TO, 'Actividad', 'Id_Actividad'),
			'idusuarioreg' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Registro'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Id_Actividad' => 'ID de actividad',
			'Texto' => 'Log',
			'Fecha_Registro' => 'Fecha',
			'Id_Usuario_Registro' => 'Usuario que registro',
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

		$criteria->compare('t.Id',$this->Id);
		$criteria->compare('t.Id_Actividad',$this->Id_Actividad);
		$criteria->compare('t.Texto',$this->Texto,true);
		$criteria->compare('t.Fecha_Registro',$this->Fecha_Registro,true);
		$criteria->compare('t.Id_Usuario_Registro',$this->Id_Usuario_Registro);
		$criteria->order = 't.Id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HistActividad the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

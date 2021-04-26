<?php

/**
 * This is the model class for table "TH_IMP_CHEQ".
 *
 * The followings are the available columns in table 'TH_IMP_CHEQ':
 * @property integer $Id
 * @property string $Cia
 * @property string $Co
 * @property string $Tipo_Docto
 * @property integer $Consecutivo
 * @property string $Firma
 * @property string $Soporte
 * @property integer $Usuario_Impresion
 * @property string $Fecha_Hora_Impresion
 * @property integer $Usuario_Reimpresion1
 * @property string $Fecha_Hora_Reimpresion1
 * @property integer $Usuario_Reimpresion2
 * @property string $Fecha_Hora_Reimpresion2
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $usuarioImpresion
 * @property THUSUARIOS $usuarioReimpresion1
 * @property THUSUARIOS $usuarioReimpresion2
 */
class ImpCheq extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_IMP_CHEQ';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Cia, Co, Tipo_Docto, Consecutivo, Firma, Soporte, Usuario_Impresion, Fecha_Hora_Impresion', 'required'),
			array('Consecutivo, Usuario_Impresion, Usuario_Reimpresion1, Usuario_Reimpresion2', 'numerical', 'integerOnly'=>true),
			array('Cia, Co, Tipo_Docto', 'length', 'max'=>10),
			array('Firma', 'length', 'max'=>20),
			array('Soporte', 'length', 'max'=>200),
			array('Fecha_Hora_Reimpresion1, Fecha_Hora_Reimpresion2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Cia, Co, Tipo_Docto, Consecutivo, Firma, Soporte, Usuario_Impresion, Fecha_Hora_Impresion, Usuario_Reimpresion1, Fecha_Hora_Reimpresion1, Usuario_Reimpresion2, Fecha_Hora_Reimpresion2', 'safe', 'on'=>'search'),
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
			'usuarioImpresion' => array(self::BELONGS_TO, 'Usuario', 'Usuario_Impresion'),
			'usuarioReimpresion1' => array(self::BELONGS_TO, 'Usuario', 'Usuario_Reimpresion1'),
			'usuarioReimpresion2' => array(self::BELONGS_TO, 'Usuario', 'Usuario_Reimpresion2'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Cia' => 'Cia',
			'Co' => 'Co',
			'Tipo_Docto' => 'Tipo Docto',
			'Consecutivo' => 'Consecutivo',
			'Firma' => 'Firma',
			'Soporte' => 'Soporte',
			'Usuario_Impresion' => 'Usuario Impresion',
			'Fecha_Hora_Impresion' => 'Fecha Hora Impresion',
			'Usuario_Reimpresion1' => 'Usuario Reimpresion1',
			'Fecha_Hora_Reimpresion1' => 'Fecha Hora Reimpresion1',
			'Usuario_Reimpresion2' => 'Usuario Reimpresion2',
			'Fecha_Hora_Reimpresion2' => 'Fecha Hora Reimpresion2',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('Cia',$this->Cia,true);
		$criteria->compare('Co',$this->Co,true);
		$criteria->compare('Tipo_Docto',$this->Tipo_Docto,true);
		$criteria->compare('Consecutivo',$this->Consecutivo);
		$criteria->compare('Firma',$this->Firma,true);
		$criteria->compare('Soporte',$this->Soporte,true);
		$criteria->compare('Usuario_Impresion',$this->Usuario_Impresion);
		$criteria->compare('Fecha_Hora_Impresion',$this->Fecha_Hora_Impresion,true);
		$criteria->compare('Usuario_Reimpresion1',$this->Usuario_Reimpresion1);
		$criteria->compare('Fecha_Hora_Reimpresion1',$this->Fecha_Hora_Reimpresion1,true);
		$criteria->compare('Usuario_Reimpresion2',$this->Usuario_Reimpresion2);
		$criteria->compare('Fecha_Hora_Reimpresion2',$this->Fecha_Hora_Reimpresion2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ImpCheq the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

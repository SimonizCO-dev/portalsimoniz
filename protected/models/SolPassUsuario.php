<?php

/**
 * This is the model class for table "T_PR_SOL_PASS_USUARIO".
 *
 * The followings are the available columns in table 'T_PR_SOL_PASS_USUARIO':
 * @property integer $Id_Sol
 * @property integer $Id_Usuario
 * @property string $Fecha_Hora_Sol
 * @property string $Fecha_Hora_Venc
 * @property integer $Estado
 */
class SolPassUsuario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_SOL_PASS_USUARIO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Usuario, Fecha_Hora_Sol, Fecha_Hora_Venc, Estado', 'required'),
			array('Id_Usuario, Estado', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Sol, Id_Usuario, Fecha_Hora_Sol, Fecha_Hora_Venc, Estado', 'safe', 'on'=>'search'),
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
			'idusuario' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Sol' => 'Id',
			'Id_Usuario' => 'Usuario',
			'Fecha_Hora_Sol' => 'Fecha y hora de solicitud',
			'Fecha_Hora_Venc' => 'Fecha y hora de vencimiento',
			'Estado' => 'Estado',
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

		$criteria->compare('Id_Sol',$this->Id_Sol);
		$criteria->compare('Id_Usuario',$this->Id_Usuario);
		$criteria->compare('Fecha_Hora_Sol',$this->Fecha_Hora_Sol,true);
		$criteria->compare('Fecha_Hora_Venc',$this->Fecha_Hora_Venc,true);
		$criteria->compare('Estado',$this->Estado);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SolPassUsuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

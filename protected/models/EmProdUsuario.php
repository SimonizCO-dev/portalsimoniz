<?php

/**
 * This is the model class for table "TH_EM_PROD_USUARIO".
 *
 * The followings are the available columns in table 'TH_EM_PROD_USUARIO':
 * @property integer $Id
 * @property string $Id_Users_Notif
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Hora_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Hora_Actualizacion
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class EmProdUsuario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_EM_PROD_USUARIO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Users_Notif', 'required'),
			array('Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Users_Notif, Id_Usuario_Creacion, Fecha_Hora_Creacion, Id_Usuario_Actualizacion, Fecha_Hora_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescUsers($users) {
        
        $resp = Yii::app()->db->createCommand("SELECT Nombres FROM T_PR_USUARIO WHERE Id_Usuario IN (".$users.")")->queryAll();
        $desc_users = "";;
		
		foreach ($resp as $reg) {
			$desc_users .= $reg['Nombres'].", ";
		}

		return substr ($desc_users, 0, -2);
        
 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
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
			'Id' => 'ID',
			'Id_Users_Notif' => 'Usuarios para notif.',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Hora_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizo',
			'Fecha_Hora_Actualizacion' => 'Ultima fecha de actualización',
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
		$criteria->compare('Id_Users_Notif',$this->Id_Users_Notif,true);
		$criteria->compare('Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('Fecha_Hora_Creacion',$this->Fecha_Hora_Creacion,true);
		$criteria->compare('Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('Fecha_Hora_Actualizacion',$this->Fecha_Hora_Actualizacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FichaItemUsuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

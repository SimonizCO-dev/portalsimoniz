<?php

/**
 * This is the model class for table "T_PR_NUCLEO_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_NUCLEO_EMPLEADO':
 * @property integer $Id_Nucleo
 * @property integer $Id_Empleado
 * @property integer $Id_Genero
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Nombre_Apellido
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Fecha_Nacimiento
 * @property integer $Id_Parentesco
 *
 * The followings are the available model relations:
 * @property THDOMINIO $idGenero
 * @property THDOMINIO $idParentesco
 * @property THEMPLEADO $idEmpleado
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class NucleoEmpleado extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_NUCLEO_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Nombre_Apellido, Fecha_Nacimiento, Id_Parentesco, Id_Genero', 'required'),
			array('Id_Empleado, Id_Genero, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Parentesco', 'numerical', 'integerOnly'=>true),
			array('Nombre_Apellido', 'length', 'max'=>200),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Nucleo, Id_Empleado, Id_Genero, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Nombre_Apellido, Fecha_Creacion, Fecha_Actualizacion, Fecha_Nacimiento, Id_Parentesco', 'safe', 'on'=>'search'),
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
			'idgenero' => array(self::BELONGS_TO, 'Dominio', 'Id_Genero'),
			'idparentesco' => array(self::BELONGS_TO, 'Dominio', 'Id_Parentesco'),
			'idempleado' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado'),
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
			'Id_Nucleo' => 'ID',
			'Id_Empleado' => 'Empleado',
			'Id_Genero' => 'Género',
			'Nombre_Apellido' => 'Nombres y apellidos',
			'Id_Parentesco' => 'Parentesco',
			'Fecha_Nacimiento' => 'Fecha de nacimiento',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
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

		$criteria->compare('t.Id_Nucleo',$this->Id_Nucleo);
		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Id_Genero',$this->Id_Genero);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Nombre_Apellido',$this->Nombre_Apellido,true);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Fecha_Nacimiento',$this->Fecha_Nacimiento,true);
		$criteria->compare('t.Id_Parentesco',$this->Id_Parentesco);
		$criteria->order = 't.Id_Nucleo DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NucleoEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

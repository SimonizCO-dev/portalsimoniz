<?php

/**
 * This is the model class for table "T_PR_FORMACION_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_FORMACION_EMPLEADO':
 * @property integer $Id_Formacion
 * @property integer $Id_Empleado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Fecha
 * @property string $Entidad
 * @property string $Titulo_Obtenido
 * @property string $Soporte
 * @property integer $Id_Nivel
 *
 * The followings are the available model relations:
 * @property Dominio $idNivel
 * @property Empleado $idEmpleado
 * @property Usuario $idUsuarioCreacion
 * @property Usuario $idUsuarioActualizacion
 */
class FormacionEmpleado extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_FORMACION_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Fecha, Entidad, Titulo_Obtenido, Id_Nivel', 'required', 'on'=>'create, update'), 
			array('Id_Empleado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Nivel', 'numerical', 'integerOnly'=>true),
			array('Entidad, Titulo_Obtenido', 'length', 'max'=>50),
			array('Soporte', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Formacion, Id_Empleado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, Fecha, Entidad, Titulo_Obtenido, Soporte, Id_Nivel', 'safe', 'on'=>'search'),
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
			'idnivel' => array(self::BELONGS_TO,'Dominio', 'Id_Nivel'),
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
			'Id_Formacion' => 'ID',
			'Id_Empleado' => 'Empleado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'Fecha' => 'Fecha',
			'Entidad' => 'Entidad',
			'Titulo_Obtenido' => 'Titulo Obtenido',
			'Soporte' => 'Soporte',
			'Id_Nivel' => 'Nivel',
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

		$criteria->compare('t.Id_Formacion',$this->Id_Formacion);
		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Fecha',$this->Fecha,true);
		$criteria->compare('t.Entidad',$this->Entidad,true);
		$criteria->compare('t.Titulo_Obtenido',$this->Titulo_Obtenido,true);
		$criteria->compare('t.Soporte',$this->Soporte,true);
		$criteria->compare('Id_Nivel',$this->Id_Nivel);
		$criteria->order = 't.Id_Formacion DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FormacionEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

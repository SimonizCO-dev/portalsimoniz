<?php

/**
 * This is the model class for table "T_PR_ELEMENTO_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_ELEMENTO_EMPLEADO':
 * @property integer $Id_E_Empleado
 * @property integer $Id_A_Elemento
 * @property integer $Cantidad
 * @property integer $Id_Contrato
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property integer $Id_Empleado
 *
 * The followings are the available model relations:
 * @property Usuario $idUsuarioCreacion
 * @property Usuario $idUsuarioActualizacion
 */
class ElementoEmpleado extends CActiveRecord
{
	
	public $empleado;
	public $unidad_gerencia;
	public $area;
	public $subarea;
	public $cargo;
	public $elementos;
	public $cant_ele;
	public $elemento;
	public $herramientas;
	public $opc;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_ELEMENTO_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_A_Elemento, Cantidad, Id_Contrato, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Id_Empleado', 'required'),
			array('Id_A_Elemento, Cantidad, Id_Contrato, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Empleado', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_E_Empleado, Id_A_Elemento, Cantidad, Id_Contrato, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Id_Empleado', 'safe', 'on'=>'search'),
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
			'idcontrato' => array(self::BELONGS_TO, 'ContratoEmpleado', 'Id_Contrato'),
			'idempleado' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado'),
			'idaelemento' => array(self::BELONGS_TO, 'AreaElemento', 'Id_A_Elemento'),
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
			'Id_E_Empleado' => 'ID',
			'Id_A_Elemento' => 'ID área elemento',
			'Cantidad' => 'Cantidad',
			'Id_Contrato' => 'ID contrato',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'Id_Empleado' => 'Empleado',
			'unidad_gerencia' => 'Unidad de gerencia',
			'area' => 'Área',
			'subarea' => 'Subárea',
			'cargo' => 'Cargo',
			'elementos' => 'Elementos',
			'elemento' => 'Elemento',
			'cant_ele' => 'Cant. de elementos',
			'herramientas' => 'Herramientas',
			'opc' => 'Opción',
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

		$criteria->compare('Id_E_Empleado',$this->Id_E_Empleado);
		$criteria->compare('Id_A_Elemento',$this->Id_A_Elemento);
		$criteria->compare('Cantidad',$this->Cantidad);
		$criteria->compare('Id_Contrato',$this->Id_Contrato);
		$criteria->compare('Estado',$this->Estado);
		$criteria->compare('Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('Id_Empleado',$this->Id_Empleado);
		$criteria->order = 't.Fecha_Actualizacion DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ElementoEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

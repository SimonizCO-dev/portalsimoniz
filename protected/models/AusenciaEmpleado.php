<?php

/**
 * This is the model class for table "T_PR_AUSENCIA_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_AUSENCIA_EMPLEADO':
 * @property integer $Id_Ausencia
 * @property integer $Id_Empleado
 * @property integer $Id_M_Ausencia
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Cod_Soporte
 * @property boolean $Descontar
 * @property boolean $Descontar_FDS
 * @property integer $Dias
 * @property string $Horas
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Observacion
 * @property string $Nota
 * @property string $Fecha_Inicial
 * @property string $Fecha_Final
 * @property integer $Id_Contrato
 *
 * The followings are the available model relations:
 * @property THDOMINIO $idMAusencia
 * @property THEMPLEADO $idEmpleado
 * @property THCONTRATOEMPLEADO $idContrato
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class AusenciaEmpleado extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_AUSENCIA_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_M_Ausencia, Cod_Soporte, Fecha_Inicial, Fecha_Final, Dias, Horas, Descontar, Descontar_FDS', 'required'),
			//array('Id_M_Ausencia, Fecha_Inicial', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Fecha_Inicial','message'=>'Ya existe una ausencia con este motivo / fecha inicial'),
			array('Id_Empleado, Id_M_Ausencia, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Dias, Id_Contrato', 'numerical', 'integerOnly'=>true),
			array('Cod_Soporte', 'length', 'max'=>50),
			array('Horas', 'length', 'max'=>3),
			array('Fecha_Creacion, Fecha_Actualizacion, Observacion, Nota', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Ausencia, Id_Empleado, Id_M_Ausencia, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Cod_Soporte, Descontar, Descontar_FDS, Dias, Horas, Fecha_Creacion, Fecha_Actualizacion, Observacion, Nota, Fecha_Inicial, Fecha_Final, Id_Contrato', 'safe', 'on'=>'search'),
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
			'idmausencia' => array(self::BELONGS_TO, 'Dominio', 'Id_M_Ausencia'),
			'idempleado' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idcontrato' => array(self::BELONGS_TO, 'ContratoEmpleado', 'Id_Contrato'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Ausencia' => 'ID',
			'Id_Empleado' => 'Empleado',
			'Id_M_Ausencia' => 'Motivo',
			'Cod_Soporte' => 'Cod. soporte',
			'Descontar' => 'Descontar',
			'Descontar_FDS' => 'Descontar FDS',
			'Dias' => '# Días',
			'Horas' => '# Horas',
			'Observacion' => 'Observaciones',
			'Nota' => 'Nota',
			'Fecha_Inicial' => 'Fecha inicial',
			'Fecha_Final' => 'Fecha final',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'Id_Contrato' => 'ID contrato',
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

		$criteria->compare('t.Id_Ausencia',$this->Id_Ausencia);
		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Id_M_Ausencia',$this->Id_M_Ausencia);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Cod_Soporte',$this->Cod_Soporte,true);
		$criteria->compare('t.Descontar',$this->Descontar);
		$criteria->compare('t.Descontar_FDS',$this->Descontar_FDS);
		$criteria->compare('t.Dias',$this->Dias);
		$criteria->compare('t.Horas',$this->Horas,true);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Observacion',$this->Observacion,true);
		$criteria->compare('t.Nota',$this->Nota,true);
		$criteria->compare('t.Fecha_Inicial',$this->Fecha_Inicial,true);
		$criteria->compare('t.Fecha_Final',$this->Fecha_Final,true);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->order = 't.Fecha_Inicial DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AusenciaEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

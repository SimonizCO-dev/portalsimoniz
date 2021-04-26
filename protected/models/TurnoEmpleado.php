<?php

/**
 * This is the model class for table "TH_TURNO_EMPLEADO".
 *
 * The followings are the available columns in table 'TH_TURNO_EMPLEADO':
 * @property integer $Id_T_Empleado
 * @property integer $Id_Turno
 * @property string $Fecha_Inicial
 * @property string $Fecha_Final
 * @property integer $Id_Empleado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Id_Contrato
 * @property integer $Estado
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THTURNOTRABAJO $idTurno
 * @property THEMPLEADO $idEmpleado
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THCONTRATOEMPLEADO $idContrato
 */
class TurnoEmpleado extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_TURNO_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Turno, Fecha_Inicial, Fecha_Final, Id_Empleado, Estado', 'required'),
			array('Id_Turno, Id_Empleado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Contrato, Estado', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_T_Empleado, Id_Turno, Fecha_Inicial, Fecha_Final, Id_Empleado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Contrato, Estado, Fecha_Creacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescTurno($id) {
 
       	$modelo_turno = TurnoTrabajo::model()->findByPk($id);

       	if($modelo_turno->Rango_Dias2 == ""){
       		$turno = $modelo_turno->Rango_Dias1.': De '.$modelo_turno->HoraAmPm($modelo_turno->Entrada1).' A '.$modelo_turno->HoraAmPm($modelo_turno->Salida1);
       	}else{
       		$turno = $modelo_turno->Rango_Dias1.': De '.$modelo_turno->HoraAmPm($modelo_turno->Entrada1).' A '.$modelo_turno->HoraAmPm($modelo_turno->Salida1).' / '.$modelo_turno->Rango_Dias2.': De '.$modelo_turno->HoraAmPm($modelo_turno->Entrada2).' A '.$modelo_turno->HoraAmPm($modelo_turno->Salida2);
       	}

        return $turno;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idturno' => array(self::BELONGS_TO, 'TurnoTrabajo', 'Id_Turno'),
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
			'Id_T_Empleado' => 'ID',
			'Id_Turno' => 'Turno',
			'Fecha_Inicial' => 'Fecha inicial',
			'Fecha_Final' => 'Fecha final',
			'Id_Empleado' => 'Empleado',
			'Estado' => 'Estado',
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

		$criteria->compare('t.Id_T_Empleado',$this->Id_T_Empleado);
		$criteria->compare('t.Id_Turno',$this->Id_Turno);
		$criteria->compare('t.Fecha_Inicial',$this->Fecha_Inicial,true);
		$criteria->compare('t.Fecha_Final',$this->Fecha_Final,true);
		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->order = 't.Fecha_Inicial DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TurnoEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

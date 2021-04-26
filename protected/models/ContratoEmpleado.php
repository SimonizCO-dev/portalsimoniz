<?php

/**
 * This is the model class for table "T_PR_CONTRATO_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_CONTRATO_EMPLEADO':
 * @property integer $Id_Contrato
 * @property integer $Id_Empleado
 * @property integer $Id_Unidad_Gerencia
 * @property integer $Id_Area
 * @property integer $Id_Subarea
 * @property integer $Id_Cargo
 * @property integer $Id_Empresa
 * @property integer $Id_M_Retiro
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Salario
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Fecha_Ingreso
 * @property string $Fecha_Liquidacion
 * @property string $Fecha_Retiro
 * @property string $Observacion
 * @property integer $Id_Turno
 * @property integer $Id_Grupo
 * @property string $Id_Trab_Esp
 * @property integer $Id_Con_Ex_Ocup
 * @property string $Restricciones
 * @property integer $Id_Centro_Costo
 * @property integer $Salario_Flexible
 *
 * The followings are the available model relations:
 * @property Dominio $idTurno
 * @property Dominio $idGrupo
 * @property Dominio $idConExOcup
 * @property Dominio $idMRetiro
 * @property Empleado $idEmpleado
 * @property Area $idArea
 * @property Cargo $idCargo
 * @property Empresa $idEmpresa
 * @property Usuario $idUsuarioCreacion
 * @property Usuario $idUsuarioActualizacion
 */
class ContratoEmpleado extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_CONTRATO_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Empresa, Id_Unidad_Gerencia, Id_Area, Id_Subarea, Id_Cargo, Fecha_Ingreso, Salario, Id_Centro_Costo, Salario_Flexible', 'required', 'on' => 'create, update'),
			array('Fecha_Liquidacion', 'required', 'on' => 'update2'),
			array('Fecha_Retiro, Id_M_Retiro', 'required', 'on' => 'terminacion'),
			array('Id_Empleado, Id_Cargo, Id_Area, Id_Empresa, Id_M_Retiro, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Salario, Id_Turno, Id_Grupo, Id_Con_Ex_Ocup, Id_Centro_Costo, Salario_Flexible', 'numerical', 'integerOnly'=>true),
			//array('Id_Trab_Esp', 'length', 'max'=>50),
			array('Fecha_Creacion, Fecha_Actualizacion, Fecha_Liquidacion, Fecha_Retiro, Observacion, Restricciones', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Contrato, Id_Empleado, Id_Cargo, Id_Area, Id_Empresa, Id_M_Retiro, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Salario, Fecha_Creacion, Fecha_Actualizacion, Fecha_Ingreso, Fecha_Liquidacion, Fecha_Retiro, Observacion, Id_Turno, Id_Grupo, Id_Trab_Esp, Id_Con_Ex_Ocup, Restricciones, Id_Centro_Costo, Salario_Flexible', 'safe', 'on'=>'search'),
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
			'idmretiro' => array(self::BELONGS_TO, 'Dominio', 'Id_M_Retiro'),
			'idempleado' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado'),
			'idunidadgerencia' => array(self::BELONGS_TO, 'UnidadGerencia', 'Id_Unidad_Gerencia'),
			'idarea' => array(self::BELONGS_TO, 'Area', 'Id_Area'),
			'idsubarea' => array(self::BELONGS_TO, 'Subarea', 'Id_Subarea'),
			'idcargo' => array(self::BELONGS_TO, 'Cargo', 'Id_Cargo'),
			'idempresa' => array(self::BELONGS_TO, 'Empresa', 'Id_Empresa'),
			'idturno' => array(self::BELONGS_TO, 'Dominio', 'Id_Turno'),
			'idgrupo' => array(self::BELONGS_TO, 'Dominio', 'Id_Grupo'),
			'idconexocup' => array(self::BELONGS_TO, 'Dominio', 'Id_Con_Ex_Ocup'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idcentrocosto' => array(self::BELONGS_TO, 'CentroCosto', 'Id_Centro_Costo'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Contrato' => 'ID',
			'Id_Empleado' => 'Empleado',
			'Id_Unidad_Gerencia' => 'Unidad de gerencia',
			'Id_Area' => 'Área',
			'Id_Subarea' => 'Subárea',
			'Id_Cargo' => 'Cargo',
			'Id_Empresa' => 'Empresa',
			'Id_M_Retiro' => 'Motivo de retiro',
			'Salario' => 'Salario',
			'Fecha_Ingreso' => 'Fecha de ingreso',
			'Fecha_Liquidacion' => 'Fecha de liquidación',
			'Fecha_Retiro' => 'Fecha de retiro',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'Observacion' => 'Observaciones',

			'Id_Turno' => 'Jornada de trabajo',
			'Id_Grupo' => 'Grupo',
			'Id_Trab_Esp' => 'Trabajo(s) específico(s)',
			'Id_Con_Ex_Ocup' => 'Concepto de examen ocupacional',
			'Restricciones' => 'Restricciones',

			'Id_Centro_Costo' => 'Centro de costo',
			'Salario_Flexible' => 'Salario flexible ?',
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

		$criteria->compare('Id_Contrato',$this->Id_Contrato);
		$criteria->compare('Id_Empleado',$this->Id_Empleado);
		$criteria->compare('Id_Unidad_Gerencia',$this->Id_Unidad_Gerencia);
		$criteria->compare('Id_Area',$this->Id_Area);
		$criteria->compare('Id_Subarea',$this->Id_Subarea);
		$criteria->compare('Id_Cargo',$this->Id_Cargo);
		$criteria->compare('Id_Empresa',$this->Id_Empresa);
		$criteria->compare('Id_M_Retiro',$this->Id_M_Retiro);
		$criteria->compare('Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('Salario',$this->Salario);
		$criteria->compare('Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('Fecha_Ingreso',$this->Fecha_Ingreso,true);
		$criteria->compare('Fecha_Liquidacion',$this->Fecha_Liquidacion,true);
		$criteria->compare('Fecha_Retiro',$this->Fecha_Retiro,true);
		$criteria->compare('Observacion',$this->Observacion,true);
		$criteria->compare('Id_Turno',$this->Id_Turno);
		$criteria->compare('Id_Grupo',$this->Id_Grupo);
		$criteria->compare('Id_Trab_Esp',$this->Id_Trab_Esp,true);
		$criteria->compare('Id_Con_Ex_Ocup',$this->Id_Con_Ex_Ocup);
		$criteria->compare('Restricciones',$this->Restricciones,true);
		$criteria->compare('Id_Centro_Costo',$this->Id_Centro_Costo);
		$criteria->compare('Salario_Flexible',$this->Salario_Flexible);
		$criteria->order = 't.Id_Contrato DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ContratoEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "T_PR_DISCIPLINARIO_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_DISCIPLINARIO_EMPLEADO':
 * @property integer $Id_Disciplinario
 * @property integer $Id_Empleado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Id_M_Disciplinario
 * @property integer $Id_Empleado_Imp
 * @property string $Orden_No
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Fecha
 * @property string $Observacion
 * @property integer $Id_Contrato
 *
 * The followings are the available model relations:
 * @property THDOMINIO $idMDisciplinario
 * @property THEMPLEADO $idEmpleado
 * @property THEMPLEADO $idEmpleadoImp
 * @property THCONTRATOEMPLEADO $idContrato
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class DisciplinarioEmpleado extends CActiveRecord
{
	
	public $A_Cod_Soporte;
	public $A_Fecha_Inicial;
	public $A_Fecha_Final;
	public $A_Dias;
	public $A_Horas;
	public $A_Descontar;
	public $A_Descontar_FDS;
	public $A_Observacion;
	public $A_Nota;
	public $tipo;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DISCIPLINARIO_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_M_Disciplinario, Id_Empleado_Imp, Fecha', 'required', 'on'=>'create_llamado, create_comparendo, update_llamado, update_sancion, update_comparendo'),
			array('Id_M_Disciplinario, Id_Empleado_Imp, Fecha, A_Cod_Soporte, A_Fecha_Inicial, A_Fecha_Final, A_Dias, A_Horas, A_Descontar, A_Descontar_FDS', 'required', 'on'=>'create_sancion'),

			array('Id_Empleado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_M_Disciplinario, Id_Empleado_Imp, Id_Contrato', 'numerical', 'integerOnly'=>true),
			array('A_Cod_Soporte, Orden_No', 'length', 'max'=>50),
			array('A_Horas', 'length', 'max'=>3),
			array('Fecha_Creacion, Fecha_Actualizacion, Observacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Disciplinario, Id_Empleado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_M_Disciplinario, Id_Empleado_Imp, Orden_No, Fecha_Creacion, Fecha_Actualizacion, Fecha, Observacion, Id_Contrato', 'safe', 'on'=>'search'),
		);
	}

	public function DescTipo($id_disciplinario) {

		$modelo_motivo = DisciplinarioEmpleado::model()->findByPk($id_disciplinario);

		if(!empty($modelo_motivo)){
			
			if($modelo_motivo->idmdisciplinario->Id_Padre == Yii::app()->params->motivos_d_llamado_atencion){
				return 'LLAMADO DE ATENCIÓN';
			}

			if($modelo_motivo->idmdisciplinario->Id_Padre == Yii::app()->params->motivos_d_sancion){
				return 'SANCIÓN';	
			}

			if($modelo_motivo->idmdisciplinario->Id_Padre == Yii::app()->params->motivos_d_comparendo){
				return 'COMPARENDO';
			}

		}else{
			return 'N/A';
		}

    }

    public function GetOpc($id_disciplinario) {

		$modelo_motivo = DisciplinarioEmpleado::model()->findByPk($id_disciplinario);

		if(!empty($modelo_motivo)){
			

			if($modelo_motivo->idmdisciplinario->Id_Padre == Yii::app()->params->motivos_d_llamado_atencion){
				return 1;
			}

			if($modelo_motivo->idmdisciplinario->Id_Padre == Yii::app()->params->motivos_d_sancion){
				return 2;	
			}

			if($modelo_motivo->idmdisciplinario->Id_Padre == Yii::app()->params->motivos_d_comparendo){
				return 3;	
			}

		}else{
			return 0;
		}

    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idmdisciplinario' => array(self::BELONGS_TO, 'Dominio', 'Id_M_Disciplinario'),
			'idempleado' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado'),
			'idempleadoimp' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado_Imp'),
			'idcontrato' => array(self::BELONGS_TO, 'ContratoEmpleado', 'Id_Contrato'),
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
			'Id_Disciplinario' => 'ID',
			'Id_Empleado' => 'Empleado',
			'Id_M_Disciplinario' => 'Motivo',
			'Id_Empleado_Imp' => 'Impuesto por',
			'Orden_No' => 'Orden No.',
			'Fecha' => 'Fecha',
			'Observacion' => 'Observaciones',
			'Id_Contrato' => 'ID contrato',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			
			'A_Cod_Soporte' => 'Cod. soporte',
			'A_Descontar' => 'Descontar',
			'A_Descontar_FDS' => 'Descontar FDS',
			'A_Dias' => '# Días',
			'A_Horas' => '# Horas',
			'A_Observacion' => 'Observaciones',
			'A_Nota' => 'Nota',
			'A_Fecha_Inicial' => 'Fecha Inicial',
			'A_Fecha_Final' => 'Fecha Final',
			'tipo' => 'Tipo',

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

		$criteria->compare('t.Id_Disciplinario',$this->Id_Disciplinario);
		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Id_M_Disciplinario',$this->Id_M_Disciplinario);
		$criteria->compare('t.Id_Empleado_Imp',$this->Id_Empleado_Imp);
		$criteria->compare('t.Orden_No',$this->Orden_No,true);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Fecha',$this->Fecha,true);
		$criteria->compare('t.Observacion',$this->Observacion,true);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->order = 't.Fecha DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DisciplinarioEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

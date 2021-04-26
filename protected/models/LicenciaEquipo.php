<?php

/**
 * This is the model class for table "T_PR_LICENCIA_EQUIPO".
 *
 * The followings are the available columns in table 'T_PR_LICENCIA_EQUIPO':
 * @property integer $Id_Lic_Equ
 * @property integer $Id_Equipo
 * @property integer $Id_Licencia
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THEQUIPO $idEquipo
 * @property THLICENCIA $idLicencia
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class LicenciaEquipo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_LICENCIA_EQUIPO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Equipo, Id_Licencia', 'required'),
			array('Id_Equipo, Id_Licencia, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Lic_Equ, Id_Equipo, Id_Licencia, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function searchByLicenciaAsocEquipo($filtro, $e) {
 		
 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10
			L.Id_Lic
		    FROM T_PR_LICENCIA L
			LEFT JOIN T_PR_DOMINIO CL ON L.Clasificacion = CL.Id_Dominio
			LEFT JOIN T_PR_DOMINIO TI ON L.Tipo = TI.Id_Dominio
			LEFT JOIN T_PR_DOMINIO VE ON L.Version = VE.Id_Dominio
			LEFT JOIN T_PR_DOMINIO PR ON L.Producto = PR.Id_Dominio
			WHERE L.Estado = ".Yii::app()->params->estado_lic_act."
			AND (CL.Dominio LIKE '%".$filtro."%' OR TI.Dominio LIKE '%".$filtro."%' OR VE.Dominio LIKE '%".$filtro."%' OR PR.Dominio LIKE '%".$filtro."%' OR L.Id_Licencia LIKE '%".$filtro."%' OR L.Num_Licencia LIKE '%".$filtro."%')
			AND L.Id_Lic NOT IN (SELECT Id_Licencia FROM T_PR_LICENCIA_EQUIPO WHERE Id_Equipo = ".$e." AND Estado = ".Yii::app()->params->estado_lic_act.")
			AND (SELECT COUNT(*) FROM T_PR_LICENCIA_EQUIPO LI WHERE LI.Id_Licencia = L.Id_Lic AND LI.Estado = 1) < L.Cant_Usuarios ORDER BY CL.Dominio
		")->queryAll();

        return $resp;
    }

    public function searchByEquipoAsocLicencia($filtro, $l) {
 		
 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10
			E.Id_Equipo
		    FROM T_PR_EQUIPO E
			LEFT JOIN T_PR_DOMINIO TI ON E.Tipo_Equipo = TI.Id_Dominio
			WHERE E.Estado = 1
			AND (TI.Dominio LIKE '%".$filtro."%' OR E.Serial LIKE '%".$filtro."%')
			AND E.Id_Equipo NOT IN (SELECT Id_Equipo FROM T_PR_LICENCIA_EQUIPO WHERE Id_Licencia = ".$l." AND Estado = 1) ORDER BY TI.Dominio, E.Serial
		")->queryAll();

        return $resp;
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idequipo' => array(self::BELONGS_TO, 'Equipo', 'Id_Equipo'),
			'idlicencia' => array(self::BELONGS_TO, 'Licencia', 'Id_Licencia'),
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
			'Id_Lic_Equ' => 'ID',
			'Id_Equipo' => 'Equipo (Tipo / Serial)',
			'Id_Licencia' => 'Licencia',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
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

		$criteria->compare('t.Id_Lic_Equ',$this->Id_Lic_Equ);
		$criteria->compare('t.Id_Equipo',$this->Id_Equipo);
		$criteria->compare('t.Id_Licencia',$this->Id_Licencia);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->order = 't.Estado DESC, t.Fecha_Actualizacion DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LicenciaEquipo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

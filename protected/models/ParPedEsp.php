<?php

/**
 * This is the model class for table "TH_PAR_PED_ESP".
 *
 * The followings are the available columns in table 'TH_PAR_PED_ESP':
 * @property integer $Id_Par_Ped_Esp
 * @property string $Consecutivo
 * @property string $Porc_Desc
 * @property string $Nit
 * @property string $Razon_Social
 * @property string $Direccion
 * @property string $Sucursal
 * @property string $Punto_Envio
 * @property string $Ciudad
 * @property string $Fecha
 * @property string $Estructura
 * @property string $Ruta
 * @property string $Asesor
 * @property string $Coordinador
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property string $Observaciones
 *
 * The followings are the available model relations:
 * @property THDETPARPEDESP[] $tHDETPARPEDESPs
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class ParPedEsp extends CActiveRecord
{
	
	
	public $item;
	public $cant;
	public $nota;

	public $cad_item;
	public $cad_vu;
	public $cad_cant;
	public $cad_iva;
	public $cad_not;

	public $desc_sucursal;
	public $desc_punto_envio;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_PAR_PED_ESP';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('Consecutivo, Nit, Razon_Social, Direccion, Sucursal, Punto_Envio, Ciudad, Fecha, Estructura, Ruta, Asesor, Coordinador, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Consecutivo, Nit', 'length', 'max'=>50),
			array('Razon_Social, Sucursal, Punto_Envio, Direccion, Ciudad, Estructura, Ruta, Asesor, Coordinador', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Par_Ped_Esp, Consecutivo, Nit, Razon_Social, Direccion, Sucursal, Punto_Envio, Ciudad, Fecha, Estructura, Ruta, Asesor, Coordinador, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescEstado($Estado) {

		switch ($Estado) {
		    case 0:
		        return "RECHAZADO";
		    case 1:
		        return "ELABORADO";
		    case 2:
		        return "PARAMETRIZADO";
		}

 	}

 	public function DescCliente($id) {

		$modelo_doc = ParPedEsp::model()->findByPk($id);
		return $modelo_doc->Nit.' - '.$modelo_doc->Razon_Social;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tHDETPARPEDESPs' => array(self::HAS_MANY, 'THDETPARPEDESP', 'Id_Par_Ped_Esp'),
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
			'Id_Par_Ped_Esp' => 'ID',
			'Consecutivo' => 'Consecutivo',
			'Porc_Desc' => '% Desc. adic.',
			'Nit' => 'Cliente',
			'Razon_Social' => 'Razón social',
			'Direccion' => 'Dirección',
			'Sucursal' => 'Sucursal',
			'Punto_Envio' => 'Punto de envío',
			'Ciudad' => 'Ciudad',
			'Fecha' => 'Fecha',
			'Estructura' => 'Estructura',
			'Ruta' => 'Ruta',
			'Asesor' => 'Asesor',
			'Coordinador' => 'Coordinador',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'Observaciones' => 'Observaciones',
			'item'=>'Item',
			'cant'=>'Cant.',
			'nota'=>'Nota(s)',
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

		$criteria->compare('t.Id_Par_Ped_Esp',$this->Id_Par_Ped_Esp);
		$criteria->compare('t.Consecutivo',$this->Consecutivo,true);
		$criteria->compare('t.Porc_Desc',$this->Porc_Desc,true);
		$criteria->compare('t.Nit',$this->Nit,true);
		$criteria->compare('t.Razon_Social',$this->Razon_Social,true);
		$criteria->compare('t.Direccion',$this->Direccion,true);
		$criteria->compare('t.Sucursal',$this->Sucursal,true);
		$criteria->compare('t.Punto_Envio',$this->Punto_Envio,true);
		$criteria->compare('t.Ciudad',$this->Ciudad,true);
		$criteria->compare('t.Fecha',$this->Fecha,true);
		$criteria->compare('t.Estructura',$this->Estructura,true);
		$criteria->compare('t.Ruta',$this->Ruta,true);
		$criteria->compare('t.Asesor',$this->Asesor,true);
		$criteria->compare('t.Coordinador',$this->Coordinador,true);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Observaciones',$this->Observaciones,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}

	public function searchparam()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		
		$criteria->compare('t.Id_Par_Ped_Esp',$this->Id_Par_Ped_Esp);
		$criteria->compare('t.Consecutivo',$this->Consecutivo,true);
		$criteria->compare('t.Porc_Desc',$this->Porc_Desc,true);
		$criteria->compare('t.Nit',$this->Nit,true);
		$criteria->compare('t.Razon_Social',$this->Razon_Social,true);
		$criteria->compare('t.Direccion',$this->Direccion,true);
		$criteria->compare('t.Sucursal',$this->Sucursal,true);
		$criteria->compare('t.Punto_Envio',$this->Punto_Envio,true);
		$criteria->compare('t.Ciudad',$this->Ciudad,true);
		$criteria->compare('t.Fecha',$this->Fecha,true);
		$criteria->compare('t.Estructura',$this->Estructura,true);
		$criteria->compare('t.Ruta',$this->Ruta,true);
		$criteria->compare('t.Asesor',$this->Asesor,true);
		$criteria->compare('t.Coordinador',$this->Coordinador,true);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Observaciones',$this->Observaciones,true);
		$criteria->AddCondition("t.Estado = 2");
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ParPedEsp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

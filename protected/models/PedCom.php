<?php

/**
 * This is the model class for table "TH_PED_COM".
 *
 * The followings are the available columns in table 'TH_PED_COM':
 * @property integer $Id_Ped_Com
 * @property integer $Id_Usuario
 * @property string $Fecha
 * @property string $Cliente
 * @property string $Sucursal
 * @property string $Punto_Envio
 * @property string $Fecha_Creacion
 * @property integer Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property integer $Estado
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuario
 */
class PedCom extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_PED_COM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Usuario, Cliente, Sucursal, Punto_Envio', 'required'),
			array('Id_Usuario, Cliente, Estado, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Cliente', 'length', 'max'=>50),
			array('Sucursal, Punto_Envio', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Ped_Com, Id_Usuario, Fecha, Cliente, Sucursal, Punto_Envio, Fecha_Creacion, Fecha_Actualizacion, Estado', 'safe', 'on'=>'search'),
		);
	}

	public function DescEstado($Estado) {

		switch ($Estado) {
		    case 1:
		        return "GUARDADO";
		    case 2:
		        return "ENVIADO";
		    case 0:
		        return "ANULADO";
		    case 3:
		        return "RECHAZADO";
		  	case 4:
		        return "CARGADO A SIESA";
		}

 	}

	public function DescCliente($nit) {

		$resp = Yii::app()->db->createCommand("
			SELECT C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_NIT_CLIENTE = '".$nit."'
		")->queryRow();

	    return $resp['C_NIT_CLIENTE']." -".$resp['C_NOMBRE_CLIENTE'];

 	}

 	public function DescSucursal($nit, $sucursal) {

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT 
			f201_id_sucursal,
			f201_descripcion_sucursal
			FROM UnoEE1..t200_mm_terceros
			INNER JOIN UnoEE1..t201_mm_clientes ON f200_id_Cia=f201_id_cia AND f200_rowid=f201_rowid_tercero
			INNER JOIN UnoEE1..t215_mm_puntos_envio_cliente ON f201_id_cia=f200_id_cia AND f215_rowid_tercero=f201_rowid_tercero AND f201_id_sucursal=f215_id_sucursal
			WHERE f200_id_cia = 2 AND f215_id != '000' AND f200_id = '".$nit."' AND f201_id_sucursal ='".$sucursal."'
		")->queryRow();

	    return $resp['f201_id_sucursal']." -".$resp['f201_descripcion_sucursal'];

 	}

 	public function DescPuntoEnvio($nit, $sucursal, $punto_envio) {

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT 
			f215_id,
			f215_descripcion
			FROM UnoEE1..t200_mm_terceros
			INNER JOIN UnoEE1..t201_mm_clientes ON f200_id_Cia=f201_id_cia AND f200_rowid=f201_rowid_tercero
			INNER JOIN UnoEE1..t215_mm_puntos_envio_cliente ON f201_id_cia=f200_id_cia AND f215_rowid_tercero=f201_rowid_tercero AND f201_id_sucursal=f215_id_sucursal
			WHERE f200_id_cia = 2 AND f215_id != '000' AND f200_id = '".$nit."' AND f201_id_sucursal = '".$sucursal."' AND f215_id = '".$punto_envio."'
		")->queryRow();

	    return $resp['f215_id']." -".$resp['f215_descripcion'];

 	}

 	public function NumDet($id_ped) {

		$detalle = detPedCom::model()->findAllByAttributes(array('Id_Ped_Com' => $id_ped));

	    return count($detalle);

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idusuario' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Ped_Com' => 'ID',
			'Id_Usuario' => 'Vendedor',
			'Fecha' => 'Fecha',
			'Cliente' => 'Cliente',
			'Sucursal' => 'Sucursal',
			'Punto_Envio' => 'Punto de envío',
			'Fecha_Creacion' => 'Fecha de creacion',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'Estado' => 'Estado',
			'item' => 'Item',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizo',
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

		$user = Yii::app()->user->getState('id_user');

		$criteria=new CDbCriteria;

		$criteria->AddCondition("t.Id_Usuario = ".$user);  

		$criteria->compare('Id_Ped_Com',$this->Id_Ped_Com);
		$criteria->compare('Fecha',$this->Fecha,true);
		$criteria->compare('Cliente',$this->Cliente);
		$criteria->compare('Estado',$this->Estado);

		$criteria->order = 't.Id_Ped_Com DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function search2()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.


		$criteria=new CDbCriteria;

		$criteria->AddCondition("t.Estado IN (2,3,4)");  

		$criteria->compare('Id_Ped_Com',$this->Id_Ped_Com);
		$criteria->compare('Id_Usuario',$this->Id_Usuario);
		$criteria->compare('Fecha',$this->Fecha,true);
		$criteria->compare('Cliente',$this->Cliente);
		$criteria->compare('Estado',$this->Estado);

		$criteria->order = 't.Id_Ped_Com DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PedCom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

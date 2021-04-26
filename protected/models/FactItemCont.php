<?php

/**
 * This is the model class for table "T_PR_FACT_ITEM_CONT".
 *
 * The followings are the available columns in table 'T_PR_FACT_ITEM_CONT':
 * @property integer $Id_Fac
 * @property integer $Id_Contrato
 * @property string $Numero_Factura
 * @property string $Fecha_Factura
 * @property string $Tasa_Cambio
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THCONT $idContrato
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class FactItemCont extends CActiveRecord
{
	
	public $vlr_total;
	public $item;
	public $cad_item;
	public $cad_cant;
	public $cad_vlr_u;
	public $cad_iva;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_FACT_ITEM_CONT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('Id_Contrato, Numero_Factura, Fecha_Factura, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Id_Contrato, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Numero_Factura', 'length', 'max'=>50),
			array('Tasa_Cambio', 'length', 'max'=>18),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Fac, Id_Contrato, Numero_Factura, Fecha_Factura, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescEstado($Estado) {

		switch ($Estado) {
		    case 0:
		        return "ANULADA";
		    case 1:
		        return "RECIBIDA";
		}

 	}

 	public function TotalItems($id_fac) {

 		$fac=FactItemCont::model()->findByPk($id_fac);
 		$det=DetFactItemCont::model()->findAll(array('condition'=>'Id_Fac = '.$id_fac));

 		$vlr_t = 0;

 		foreach ($det as $r) {
 			
 			$tasa_cambio = $fac->Tasa_Cambio;
 			
 			$vlr_unit = $r->Vlr_Unit;
 			$cant = $r->Cant;
 			$id_moneda = $r->iditem->Moneda;
 			$iva = $r->Iva;

 			if($id_moneda == Yii::app()->params->moneda_USD){

		        if($iva == 0){

		        	$vlr_total = ($vlr_unit * $tasa_cambio) * $cant;

		        }else{

		        	$vlr_base = ($vlr_unit * $tasa_cambio) * $cant;
		         	$vlr_iva = (($vlr_base * $iva) / 100);
		        	$vlr_total = $vlr_base + $vlr_iva;

		        }

	      	}else{

		        if($iva == 0){

		        	$vlr_total = $vlr_unit * $cant;

		        }else{

		        	$vlr_base = $vlr_unit * $cant;
		        	$vlr_iva = (($vlr_base * $iva) / 100);
		        	$vlr_total = $vlr_base + $vlr_iva;

		        }

	      	}

	      	$vlr_t += $vlr_total;
 		}

		$resp = number_format($vlr_t, 2).' COP';
		return $resp;
 	}

 	public function Desccontrato($Id_Contrato) {

		$modelo_cont = Cont::model()->findByPk($Id_Contrato);
		$desc_contrato = $modelo_cont->Id_Contrato.' / '.$modelo_cont->Proveedor.' - '.$modelo_cont->Concepto_Contrato;
		return $desc_contrato;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idcontrato' => array(self::BELONGS_TO, 'Cont', 'Id_Contrato'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Fac' => 'ID',
			'Id_Contrato' => 'Contrato (ID / Proveedor - Concepto)',
			'Numero_Factura' => 'N° de fact.',
			'Fecha_Factura' => 'Fecha de fact.',
			'Tasa_Cambio' => 'Tasa de cambio',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'vlr_total' => 'Vlr. total',
			'item' => 'Item',
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

		$criteria->compare('t.Id_Fac',$this->Id_Fac);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->compare('t.Numero_Factura',$this->Numero_Factura,true);
		$criteria->compare('t.Fecha_Factura',$this->Fecha_Factura,true);
		$criteria->compare('t.Tasa_Cambio',$this->Tasa_Cambio,true);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->order = 't.Id_Fac DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FactItemCont the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

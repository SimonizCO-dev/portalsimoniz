<?php

/**
 * This is the model class for table "T_PR_DET_FACT_ITEM_CONT".
 *
 * The followings are the available columns in table 'T_PR_DET_FACT_ITEM_CONT':
 * @property integer $Id_Det_Fac
 * @property integer $Id_Fac
 * @property integer $Id_Item
 * @property integer $Cant
 * @property integer $Vlr_Unit
 * @property integer $Iva
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 *
 * The followings are the available model relations:
 * @property THITEMCONT $idItem
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THFACTITEMCONT $idFac
 */
class DetFactItemCont extends CActiveRecord
{
	public $moneda;
	public $vlr_total;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DET_FACT_ITEM_CONT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Fac, Id_Item, Cant, Vlr_Unit, Iva', 'required'),
			array('Id_Fac, Id_Item, Cant, Vlr_Unit, Iva, Id_Usuario_Creacion', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Det_Fac, Id_Fac, Id_Item, Cant, Vlr_Unit, Iva, Id_Usuario_Creacion, Fecha_Creacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescItem($id_item) {

		$modelo_item_cont = ItemCont::model()->findByPk($id_item);
		return $modelo_item_cont->Id.' - '.$modelo_item_cont->Item;
 	}

 	public function TotalDet($id_det) {

 		$det=DetFactItemCont::model()->find(array('condition'=>'Id_Det_Fac = '.$id_det));

 		$tasa_cambio = $det->idfac->Tasa_Cambio;
 			
		$vlr_unit = $det->Vlr_Unit;
		$cant = $det->Cant;
		$id_moneda = $det->iditem->Moneda;
		$iva = $det->Iva;


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


		$resp = number_format($vlr_total, 2);
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
			'iditem' => array(self::BELONGS_TO, 'ItemCont', 'Id_Item'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idfac' => array(self::BELONGS_TO, 'FactItemCont', 'Id_Fac'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Det_Fac' => 'ID',
			'Id_Fac' => 'N° de fact.',
			'Id_Item' => 'ID de item / Item',
			'Cant' => 'Cant.',
			'Vlr_Unit' => 'Vlr. unit.',
			'Iva' => 'Iva',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'moneda' => 'Moneda',
			'vlr_total' => 'Vlr. total (COP)',
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

		$criteria->compare('Id_Det_Fac',$this->Id_Det_Fac);
		$criteria->compare('Id_Fac',$this->Id_Fac);
		$criteria->compare('Id_Item',$this->Id_Item);
		$criteria->compare('Cant',$this->Cant);
		$criteria->compare('Vlr_Unit',$this->Vlr_Unit);
		$criteria->compare('Iva',$this->Iva);
		$criteria->compare('Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('Fecha_Creacion',$this->Fecha_Creacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),	
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DetFactItemCont the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

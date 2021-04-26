<?php

/**
 * This is the model class for table "T_PR_ITEM_CONT".
 *
 * The followings are the available columns in table 'T_PR_ITEM_CONT':
 * @property integer $Id_Item
 * @property integer $Id_Contrato
 * @property string $Id
 * @property string $Item
 * @property string $Descripcion
 * @property integer $Cant
 * @property string $Vlr_Unit
 * @property integer $Moneda
 * @property integer $Iva
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
class ItemCont extends CActiveRecord
{
	
	public $vlr_total;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_ITEM_CONT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Contrato, Id, Item, Descripcion, Cant, Moneda, Vlr_Unit, Iva, Estado', 'required'),
			array('Id_Contrato, Cant, Moneda, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Id, Item', 'length', 'max'=>200),
			array('Vlr_Unit', 'length', 'max'=>18),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Item, Id_Contrato, Id, Item, Descripcion, Cant, Vlr_Unit, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function Desccontrato($Id_Contrato) {

		$modelo_cont = Cont::model()->findByPk($Id_Contrato);

		$desc_contrato = $modelo_cont->Id_Contrato.' / '.$modelo_cont->Proveedor.' - '.$modelo_cont->Concepto_Contrato;
		
		return $desc_contrato;

 	}

 	public function VlrTotalItem($Id_Item) {

		$modelo_item_cont = ItemCont::model()->findByPk($Id_Item);

		$Iva = $modelo_item_cont->Iva;

		if($Iva == 0){

			$vlr_total_item = $modelo_item_cont->Vlr_Unit * $modelo_item_cont->Cant;


		}else{

			$vlr_base = $modelo_item_cont->Vlr_Unit * $modelo_item_cont->Cant;
			$vlr_iva = (($vlr_base * $Iva) / 100);
			$vlr_total_item = $vlr_base + $vlr_iva;

		}
		
		return $vlr_total_item;

 	}

 	public function getIdItem_Item(){

		return $this->Id.' - '.$this->Item;

	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idContrato' => array(self::BELONGS_TO, 'Cont', 'Id_Contrato'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'moneda' => array(self::BELONGS_TO, 'Dominio', 'Moneda'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Item' => 'ID',
			'Id_Contrato' => 'Contrato (ID / Proveedor - Concepto)',
			'Id' => 'ID de item',
			'Item' => 'Item',
			'Descripcion' => 'Descripción',
			'Cant' => 'Cant.',
			'Vlr_Unit' => 'Vlr. unit.',
			'Moneda' => 'Moneda',
			'Iva' => 'Iva',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'vlr_total' => 'Vlr. total',
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

		$criteria->compare('t.Id_Item',$this->Id_Item);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->compare('t.Id',$this->Id,true);
		$criteria->compare('t.Item',$this->Item,true);
		$criteria->compare('t.Descripcion',$this->Descripcion,true);
		$criteria->compare('t.Cant',$this->Cant);
		$criteria->compare('t.Vlr_Unit',$this->Vlr_Unit);
		$criteria->compare('t.Moneda',$this->Moneda);
		$criteria->compare('t.Iva',$this->Iva);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->order = 't.Id_Item DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ItemCont the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

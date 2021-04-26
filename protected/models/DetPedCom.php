<?php

/**
 * This is the model class for table "TH_DET_PED_COM".
 *
 * The followings are the available columns in table 'TH_DET_PED_COM':
 * @property integer $Id_Det_Ped_Com
 * @property integer $Id_Ped_Com
 * @property integer $Item
 * @property integer $Und_Emp
 * @property integer $Un_Sol
 * @property string $Fecha_Creacion
 */
class DetPedCom extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DET_PED_COM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Ped_Com, Item, Un_Sol', 'required'),
			array('Id_Ped_Com, Item', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Item','message'=>'El ítem {value_Item} ya fue registrado.'),
			array('Id_Ped_Com, Item, Und_Emp, Un_Sol', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Det_Ped_Com, Id_Ped_Com, Item, Und_Emp, Un_Sol, Fecha_Creacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescItem($Id_Item){

        $desc= Yii::app()->db->createCommand("
		    SELECT CONCAT (I_ID_ITEM, ' - ', I_REFERENCIA, ' - ', I_DESCRIPCION) AS DESCR FROM T_CF_ITEMS WHERE I_ID_ITEM = ".$Id_Item." AND I_CIA = 2")->queryRow();

		return $desc['DESCR'];

    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idpedcom' => array(self::BELONGS_TO, 'PedCom', 'Id_Ped_Com'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Det_Ped_Com' => 'ID',
			'Id_Ped_Com' => 'ID de pedido',
			'Item' => 'Item',
			'Und_Emp' => 'Und. de empaque',
			'Un_Sol' => 'Unds. solicitadas',
			'Fecha_Creacion' => 'Fecha de creación',
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

		$criteria->compare('Id_Det_Ped_Com',$this->Id_Det_Ped_Com);
		$criteria->compare('Id_Ped_Com',$this->Id_Ped_Com);
		$criteria->compare('Item',$this->Item);
		$criteria->compare('Und_Emp',$this->Und_Emp);
		$criteria->compare('Un_Sol',$this->Un_Sol);
		$criteria->compare('Fecha_Creacion',$this->Fecha_Creacion,true);

		$criteria->order = 't.Id_Det_Ped_Com DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>20),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DetPedCom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

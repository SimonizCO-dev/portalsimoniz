<?php

/**
 * This is the model class for table "TH_DET_SOL_PROM".
 *
 * The followings are the available columns in table 'TH_DET_SOL_PROM':
 * @property integer $Id_Det_Sol_Prom
 * @property integer $Id_Sol_Prom
 * @property integer $Item
 * @property integer $Cant_Base
 * @property integer $Cant_Requerida
 * @property integer $Cant_Solicitada
 *
 * The followings are the available model relations:
 * @property DetSolProm $idDetSolProm
 * @property DetSolProm $tHDETSOLPROM
 */
class DetSolProm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DET_SOL_PROM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Sol_Prom, Item, Cant_Base, Cant_Requerida, Cant_Solicitada', 'required'),
			array('Id_Sol_Prom, Item', 'numerical', 'integerOnly'=>true),
			array('Cant_Base, Cant_Requerida', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Det_Sol_Prom, Id_Sol_Prom, Item, Cant_Base, Cant_Requerida, Cant_Solicitada', 'safe', 'on'=>'search'),
		);
	}

	public function DescItem($Item){

        $desc= Yii::app()->db->createCommand("
		    SELECT CONCAT (RTRIM(LTRIM(I_ID_ITEM)), ' - ', RTRIM(LTRIM(I_REFERENCIA)), ' - ', RTRIM(LTRIM(I_DESCRIPCION))) AS DESCR FROM T_CF_ITEMS WHERE I_ID_ITEM = ".$Item." AND I_CIA = 2")->queryRow();

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
			'idDetSolProm' => array(self::BELONGS_TO, 'DetSolProm', 'Id_Det_Sol_Prom'),
			'tHDETSOLPROM' => array(self::HAS_ONE, 'DetSolProm', 'Id_Det_Sol_Prom'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Det_Sol_Prom' => 'ID',
			'Id_Sol_Prom' => 'ID Solicitud',
			'Item' => 'Item',
			'Cant_Base' => 'Cant. base',
			'Cant_Requerida' => 'Cant. requerida',
			'Cant_Solicitada' => 'Cant. solicitada',
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

		$criteria->compare('t.Id_Det_Sol_Prom',$this->Id_Det_Sol_Prom);
		$criteria->compare('t.Id_Sol_Prom',$this->Id_Sol_Prom);
		$criteria->compare('t.Item',$this->Item);
		$criteria->compare('t.Cant_Base',$this->Cant_Base);
		$criteria->compare('t.Cant_Requerida',$this->Cant_Requerida);
		$criteria->compare('t.Cant_Solicitada',$this->Cant_Solicitada);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DetSolProm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

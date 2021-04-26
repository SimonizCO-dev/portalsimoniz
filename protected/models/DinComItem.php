<?php

/**
 * This is the model class for table "TH_DIN_COM_ITEM".
 *
 * The followings are the available columns in table 'TH_DIN_COM_ITEM':
 * @property integer $Id
 * @property integer $Id_Din_Com
 * @property string $Id_Plan
 * @property string $Id_Criterio
 *
 * The followings are the available model relations:
 * @property THDINCOM $idDinCom
 */
class DinComItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_DIN_COM_ITEM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Din_Com, Id_Plan, Id_Criterio', 'required'),
			array('Id_Din_Com', 'numerical', 'integerOnly'=>true),
			array('Id_Plan, Id_Criterio', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Din_Com, Id_Plan, Id_Criterio', 'safe', 'on'=>'search'),
		);
	}

	public static function DescPlan($id_plan){

		$q = Yii::app()->db->createCommand("SELECT DISTINCT Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = ".$id_plan)->queryRow();
		return $q['Plan_Descripcion'];
	}

	public static function DescCriterio($id_plan, $id_criterio){

		$q = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = ".$id_plan." AND Id_Criterio = ".$id_criterio)->queryRow();
		return $q['Criterio_Descripcion'];
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idDinCom' => array(self::BELONGS_TO, 'DinCom', 'Id_Din_Com'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Id_Din_Com' => 'ID Dinamica',
			'Id_Plan' => 'Plan',
			'Id_Criterio' => 'Criterio',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('Id_Din_Com',$this->Id_Din_Com);
		$criteria->compare('Id_Plan',$this->Id_Plan,true);
		$criteria->compare('Id_Criterio',$this->Id_Criterio,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DinComItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

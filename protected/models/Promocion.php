<?php

/**
 * This is the model class for table "TH_PROMOCIONES".
 *
 * The followings are the available columns in table 'TH_PROMOCIONES':
 * @property integer $Id_Promocion
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Id_Item_Padre
 * @property integer $Id_Item_Hijo
 * @property string $Cantidad
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property Promocion $idPromocion
 * @property Promocion $tHPROMOCIONES
 */
class Promocion extends CActiveRecord
{
	public $comp;
	public $cant;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_PROMOCION';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Item_Padre, Id_Item_Hijo, Cantidad', 'required'),
			array('Id_Item_Padre, Id_Item_Hijo', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Id_Item_Hijo','message'=>'Ya existe un registro con la misma combinación (Promoción - Componente).'),
			array('Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Item_Padre, Id_Item_Hijo', 'numerical', 'integerOnly'=>true),
			array('Cantidad', 'length', 'max'=>18),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Promocion, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Item_Padre, Id_Item_Hijo, Cantidad, Fecha_Creacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idPromocion' => array(self::BELONGS_TO, 'Promocion', 'Id_Promocion'),
			'tHPROMOCIONES' => array(self::HAS_ONE, 'Promocion', 'Id_Promocion'),
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
			'Id_Promocion' => 'ID',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Id_Item_Padre' => 'Promoción',
			'Id_Item_Hijo' => 'Componente',
			'Cantidad' => 'Cantidad',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'ultima fecha de actualización',
			'comp' => 'Componente',
			'cant' => 'Cantidad',
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

		$criteria->distinct = true;

		$criteria->together  =  true;

	   	$criteria->with=array('idusuariocre','idusuarioact');

		if($this->Cantidad != ""){
			$criteria->AddCondition("t.Cantidad = '".$this->Cantidad."'"); 
	    }

		if($this->Id_Item_Padre != ""){
			$criteria->AddCondition("t.Id_Item_Padre = '".$this->Id_Item_Padre."'"); 
	    }

	    if($this->Id_Item_Hijo != ""){
			$criteria->AddCondition("t.Id_Item_Hijo = '".$this->Id_Item_Hijo."'"); 
	    }

	    if($this->Cantidad != ""){
			$criteria->AddCondition("t.Cantidad = '".$this->Cantidad."'"); 
	    }

		if($this->Fecha_Creacion != ""){
      		$fci = $this->Fecha_Creacion." 00:00:00";
      		$fcf = $this->Fecha_Creacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
    	}

		if($this->Fecha_Creacion != ""){
      		$fci = $this->Fecha_Creacion." 00:00:00";
      		$fcf = $this->Fecha_Creacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
    	}

    	if($this->Fecha_Actualizacion != ""){
      		$fai = $this->Fecha_Actualizacion." 00:00:00";
      		$faf = $this->Fecha_Actualizacion." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Actualizacion', $fai, $faf);
    	}

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = '".$this->Id_Usuario_Creacion."'"); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = '".$this->Id_Usuario_Actualizacion."'"); 
	    }

		$criteria->order = 't.Id_Item_Padre, t.Id_Item_Hijo';	

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),	
		));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Promocion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

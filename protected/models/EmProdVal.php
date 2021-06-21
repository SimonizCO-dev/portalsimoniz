<?php

/**
 * This is the model class for table "T_PR_EM_PROD_VAL".
 *
 * The followings are the available columns in table 'T_PR_EM_PROD_VAL':
 * @property integer $Id_Em_Prod_Val
 * @property integer $Id_Em_Prod
 * @property integer $Id_Usuario
 * @property integer $Estado
 * @property string $Fecha_Actualizacion
 */
class EmProdVal extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_EM_PROD_VAL';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Em_Prod, Id_Usuario, Estado', 'required'),
			array('Id_Em_Prod, Id_Usuario, Estado', 'numerical', 'integerOnly'=>true),
			array('Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Em_Prod_Val, Id_Em_Prod, Id_Usuario, Estado, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescEstado($estado){

		switch ($estado) {
		    case 0:
		        $texto_estado = '<p class="fas fa-clock text-warning" title="SIN REVISAR"></p>';
		        break;
		    case 1:
		        $texto_estado = '<p class="fas fa-check text-success" title="VISTO"></p>';
		        break;
		}

		return $texto_estado;

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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Em_Prod_Val' => 'ID',
			'Id_Em_Prod' => 'ID emisión de producto',
			'Id_Usuario' => 'Usuario',
			'Estado' => 'Estado',
			'Fecha_Actualizacion' => 'Fecha de revisión',
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

		$criteria->compare('t.Id_Em_Prod_Val',$this->Id_Em_Prod_Val);
		$criteria->compare('t.Id_Em_Prod',$this->Id_Em_Prod);
		$criteria->compare('t.Id_Usuario',$this->Id_Usuario);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);

		$criteria->order = 't.Fecha_Actualizacion ASC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=> 20),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmProdVal the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

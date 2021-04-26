<?php

/**
 * This is the model class for table "T_PR_C_PRESUPUESTO".
 *
 * The followings are the available columns in table 'T_PR_C_PRESUPUESTO':
 * @property integer $ID_PRESUPUESTO_VEND
 * @property string $NIT_VENDEDOR
 * @property string $PRESUPUESTO
 * @property integer $ESTADO
 * @property integer $ID_USUARIO_CREACION
 * @property string $FECHA_CREACION
 * @property integer $ID_USUARIO_ACTUALIZACION
 * @property string $FECHA_ACTUALIZACION
 *
 * The followings are the available model relations:
 * @property THUSUARIO $iDUSUARIOCREACION
 * @property THUSUARIO $iDUSUARIOACTUALIZACION
 */
class CPresupuesto extends CActiveRecord
{
	public $archivo;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_C_PRESUPUESTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('archivo','required','on'=>'imp'),
			array('NIT_VENDEDOR, PRESUPUESTO, ESTADO', 'required'),
			array('ESTADO', 'numerical', 'integerOnly'=>true),
			array('NIT_VENDEDOR', 'length', 'max'=>25),
			array('PRESUPUESTO', 'length', 'max'=>19),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID_PRESUPUESTO_VEND, NIT_VENDEDOR, PRESUPUESTO, ESTADO', 'safe', 'on'=>'search'),
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
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_CREACION'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_ACTUALIZACION'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID_PRESUPUESTO_VEND' => 'ID',
			'NIT_VENDEDOR' => 'Nit',
			'PRESUPUESTO' => 'Presupuesto',
			'ESTADO' => 'Estado',
			'ID_USUARIO_CREACION' => 'Usuario que creo',
			'ID_USUARIO_ACTUALIZACION' => 'Ultimo usuario que actualizó',
			'FECHA_CREACION' => 'Fecha de creación',
			'FECHA_ACTUALIZACION' => 'Ultima fecha de actualización',
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

		$criteria->compare('t.ID_PRESUPUESTO_VEND',$this->ID_PRESUPUESTO_VEND);
		$criteria->compare('t.NIT_VENDEDOR',$this->NIT_VENDEDOR,true);
		$criteria->compare('t.PRESUPUESTO',$this->PRESUPUESTO,true);
		$criteria->compare('t.ESTADO',$this->ESTADO);

		$criteria->order = 't.ID_PRESUPUESTO_VEND DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),	
		));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CPresupuesto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

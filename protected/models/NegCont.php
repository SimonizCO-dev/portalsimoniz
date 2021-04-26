<?php

/**
 * This is the model class for table "T_PR_NEG_CONT".
 *
 * The followings are the available columns in table 'T_PR_NEG_CONT':
 * @property integer $Id_Neg
 * @property integer $Id_Contrato
 * @property string $Item
 * @property string $Costo
 * @property integer $Moneda
 * @property string $Porc_Desc
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THCONT $idContrato
 * @property THDOMINIO $moneda
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class NegCont extends CActiveRecord
{
	
	public $costo_final;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_NEG_CONT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Contrato, Item, Costo, Moneda, Porc_Desc, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Id_Contrato, Moneda, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Porc_Desc', 'length', 'max'=>5),
			array('Costo', 'length', 'max'=>18),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Neg, Id_Contrato, Item, Costo, Moneda, Porc_Desc, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function Desccontrato($Id_Contrato) {

		$modelo_cont = Cont::model()->findByPk($Id_Contrato);

		$desc_contrato = $modelo_cont->Id_Contrato.' / '.$modelo_cont->Proveedor.' - '.$modelo_cont->Concepto_Contrato;
		
		return $desc_contrato;

 	}

	public function CostoFinal($Id_Neg) {

		$modelo_neg = NegCont::model()->findByPk($Id_Neg);

		$costo = $modelo_neg->Costo;
		$porc_desc = $modelo_neg->Porc_Desc;

		$costo_final = ($costo - (($costo * $porc_desc) / 100));

		return number_format($costo_final, 2);

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
			'moneda' => array(self::BELONGS_TO, 'Dominio', 'Moneda'),
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
			'Id_Neg' => 'ID',
			'Id_Contrato' => 'Contrato (ID / Proveedor - Concepto)',
			'Item' => 'Item',
			'Costo' => 'Costo',
			'Moneda' => 'Moneda',
			'Porc_Desc' => '% Desc.',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'costo_final' => 'Costo final',
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

		$criteria->compare('t.Id_Neg',$this->Id_Neg);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->compare('t.Item',$this->Item,true);
		$criteria->compare('t.Costo',$this->Costo);
		$criteria->compare('t.Moneda',$this->Moneda);
		$criteria->compare('t.Porc_Desc',$this->Porc_Desc,true);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->order = 't.Id_Neg DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NegCont the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

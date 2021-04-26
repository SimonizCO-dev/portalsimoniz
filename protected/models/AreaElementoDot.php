<?php

/**
 * This is the model class for table "TH_AREA_ELEMENTO_DOT".
 *
 * The followings are the available columns in table 'TH_AREA_ELEMENTO_DOT':
 * @property integer $Id_Ae_Dot
 * @property integer $Id_A_Elemento
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 *
 * The followings are the available model relations:
 * @property THAREAELEMENTO $idAElemento
 * @property THUSUARIO $idUsuarioCreacion
 */
class AreaElementoDot extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_AREA_ELEMENTO_DOT';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_A_Elemento', 'required'),
			array('Id_A_Elemento, Id_Usuario_Creacion', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Ae_Dot, Id_A_Elemento, Id_Usuario_Creacion, Fecha_Creacion', 'safe', 'on'=>'search'),
		);
	}

	public function Desc_Elemento_Subarea_Area($id_a_elemento){

		$modelo_area_sub_elemento = AreaElemento::model()->findByPk($id_a_elemento);

		$id_elemento = $modelo_area_sub_elemento->Id_Elemento;

		$id_area = $modelo_area_sub_elemento->Id_Area;

		$id_subarea = $modelo_area_sub_elemento->Id_Subarea;

		if(is_null($id_elemento)){
			$elemento = 'NO ASIGNADO';
		}else{
			$elemento = $modelo_area_sub_elemento->idelemento->Elemento;
		}

		if(is_null($id_area)){
			$area = 'NO ASIGNADO';
		}else{
			$area = $modelo_area_sub_elemento->idarea->Area;
		}

		if(is_null($id_subarea)){
			$subarea = 'NO ASIGNADO';
		}else{
			$subarea = $modelo_area_sub_elemento->idsubarea->Subarea;
		}


		return $elemento.' ('.$subarea.' / '.$area.')';

    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idaelemento' => array(self::BELONGS_TO, 'AreaElemento', 'Id_A_Elemento'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Ae_Dot' => 'ID',
			'Id_A_Elemento' => 'Elemento (Subárea / Área)',
			'Id_Usuario_Creacion' => 'Usuario que creo',
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

		$criteria->compare('t.Id_Ae_Dot',$this->Id_Ae_Dot);
		$criteria->compare('t.Id_A_Elemento',$this->Id_A_Elemento);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>20),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AreaElementoDot the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

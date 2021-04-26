<?php

/**
 * This is the model class for table "T_PR_I_DOCTO_MOVTO".
 *
 * The followings are the available columns in table 'T_PR_I_DOCTO_MOVTO':
 * @property integer $Id
 * @property integer $Id_Docto
 * @property integer $Id_Bodega_Org
 * @property integer $Id_Bodega_Dst
 * @property integer $Id_Item
 * @property integer $Cantidad
 * @property string $Vlr_Unit_Item
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THIBODEGA $idBodegaOrg
 * @property THIBODEGA $idBodegaDst
 * @property THIDOCTO $idDocto
 * @property THIITEM $idItem
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class IDoctoMovto extends CActiveRecord
{
	public $tipo_docto;
	public $consecutivo_docto;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_I_DOCTO_MOVTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Docto, Id_Bodega_Org, Id_Bodega_Dst, Id_Item, Cantidad, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Vlr_Unit_Item', 'length', 'max'=>19),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Docto, Id_Bodega_Org, Id_Bodega_Dst, Id_Item, Cantidad, Vlr_Unit_Item, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function DescItem($item) {
 
        $q_item = Yii::app()->db->createCommand("SELECT CONCAT (Id_Item, ' (', Referencia, ' - ', Descripcion, ')') AS Descr FROM T_PR_I_ITEM WHERE Id = ".$item)->queryRow();
        return $q_item['Descr'];
 	}

 	public function NumDet($id_docto) {
 
        $modelo_num_det = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id_docto));
		return count($modelo_num_det);
 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idbodegaorg' => array(self::BELONGS_TO, 'IBodega', 'Id_Bodega_Org'),
			'idbodegadst' => array(self::BELONGS_TO, 'IBodega', 'Id_Bodega_Dst'),
			'iddocto' => array(self::BELONGS_TO, 'IDocto', 'Id_Docto'),
			'iditem' => array(self::BELONGS_TO, 'IItem', 'Id_Item'),
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
			'Id' => 'ID',
			'Id_Docto' => 'Docto',
			'Id_Bodega_Org' => 'Bodega origen',
			'Id_Bodega_Dst' => 'Bodega destino',
			'Id_Item' => 'Item',
			'Cantidad' => 'Cantidad',
			'Vlr_Unit_Item' => 'Vlr. unitario',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'tipo_docto' => 'Tipo',
			'consecutivo_docto' => 'Consecutivo',
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
		$criteria->compare('Id_Docto',$this->Id_Docto);
		$criteria->compare('Id_Bodega_Org',$this->Id_Bodega_Org);
		$criteria->compare('Id_Bodega_Dst',$this->Id_Bodega_Dst);
		$criteria->compare('Id_Item',$this->Id_Item);
		$criteria->compare('Cantidad',$this->Cantidad);
		$criteria->compare('Vlr_Unit_Item',$this->Vlr_Unit_Item,true);
		$criteria->compare('Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('Fecha_Actualizacion',$this->Fecha_Actualizacion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=> 50),		
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IDoctoMovto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

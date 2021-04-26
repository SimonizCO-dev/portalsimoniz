<?php

/**
 * This is the model class for table "TH_I_EXISTENCIA".
 *
 * The followings are the available columns in table 'TH_I_EXISTENCIA':
 * @property integer $Id
 * @property integer $Id_Item
 * @property integer $Id_Bodega
 * @property integer $Cantidad
 * @property string $Fecha_Ult_Ent
 * @property string $Fecha_Ult_Sal
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THIBODEGA $idBodega
 * @property THIITEM $idItem
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class IExistencia extends CActiveRecord
{
	public $orderby;
	public $linea;
	public $est_cant;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_I_EXISTENCIA';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Item, Id_Bodega, Cantidad, Id_Usuario_Actualizacion', 'required'),
			array('Id_Item, Id_Bodega, Cantidad, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Fecha_Ult_Ent, Fecha_Ult_Sal, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Item, Id_Bodega, Cantidad, Fecha_Ult_Ent, Fecha_Ult_Sal, Id_Usuario_Actualizacion, Fecha_Actualizacion, linea, orderby, est_cant', 'safe', 'on'=>'search'),
		);
	}

	public function DescItem($item) {
 
        $q_item = Yii::app()->db->createCommand("SELECT CONCAT (Id_Item, ' (', Referencia, ' - ', Descripcion, ')') AS Descr FROM T_PR_I_ITEM WHERE Id = ".$item)->queryRow();
        return $q_item['Descr'];
 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idbodega' => array(self::BELONGS_TO, 'IBodega', 'Id_Bodega'),
			'iditem' => array(self::BELONGS_TO, 'IItem', 'Id_Item'),
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
			'Id_Item' => 'Item',
			'Id_Bodega' => 'Bodega',
			'Cantidad' => 'Cantidad',
			'Fecha_Ult_Ent' => 'Fecha ult. entrada',
			'Fecha_Ult_Sal' => 'Fecha ult. salida',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'linea' => 'Línea(s)',
			'est_cant' => 'Estado de cantidad',
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

		$criteria->together  =  true;
	   	$criteria->with=array('idbodega','iditem','iditem.idlinea');

		$criteria->compare('t.Id',$this->Id);
		$criteria->compare('t.Id_Item',$this->Id_Item);
		$criteria->compare('t.Id_Bodega',$this->Id_Bodega);
		$criteria->compare('t.Cantidad',$this->Cantidad);
		$criteria->compare('t.Fecha_Ult_Ent',$this->Fecha_Ult_Ent,true);
		$criteria->compare('t.Fecha_Ult_Sal',$this->Fecha_Ult_Sal,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);

		if($this->linea != ""){
			$l = implode(",", $this->linea);
			$criteria->AddCondition("iditem.Id_Linea IN (".$l.")"); 
	    }

	    if($this->est_cant != ""){
			if($this->est_cant == 1){
				$criteria->AddCondition("t.Cantidad < iditem.Min_Stock"); 
			}else{
				$criteria->AddCondition("t.Cantidad >= iditem.Min_Stock"); 
			}
	    }


		if(empty($this->orderby)){
			$criteria->order = 'idlinea.Descripcion ASC, iditem.Descripcion ASC';  	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 'iditem.Descripcion ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'iditem.Descripcion DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idbodega.Descripcion ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idbodega.Descripcion DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Cantidad ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Cantidad DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Fecha_Ult_Ent ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Fecha_Ult_Ent DESC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Fecha_Ult_Sal ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Ult_Sal DESC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IExistencia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

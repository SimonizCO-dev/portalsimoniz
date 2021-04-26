<?php

/**
 * This is the model class for table "T_PR_I_ITEM".
 *
 * The followings are the available columns in table 'T_PR_I_ITEM':
 * @property integer $Id
 * @property integer $Id_Item
 * @property string $Referencia
 * @property string $Descripcion
 * @property string $UND_Medida
 * @property integer $Id_Linea
 * @property integer $Mes_Stock
 * @property integer $Min_Stock
 * @property integer $Max_Stock
 * @property string $Vlr_Costo
 * @property string $Vlr_Ult_Compra
 * @property integer $Total_Inventario
 * @property string $Nota
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THIDOCTOMOVTO[] $tHIDOCTOMOVTOs
 * @property THILINEA $idLinea
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class IItem extends CActiveRecord
{

	public $orderby;
	public $costo_unit;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_I_ITEM';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Item, Referencia, Descripcion, UND_Medida, Id_Linea, Mes_Stock, Min_Stock, Max_Stock, Nota, Estado', 'required'),
			array('Id_Item', 'unique'),
			array('Id_Item, Id_Linea, Mes_Stock, Min_Stock, Max_Stock, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Total_Inventario', 'numerical', 'integerOnly'=>true),
			array('Referencia, Descripcion, Nota', 'length', 'max'=>100),
			//array('UND_Medida', 'length', 'max'=>3),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Id_Item, Referencia, Descripcion, UND_Medida, Id_Linea, Mes_Stock, Min_Stock, Max_Stock, Nota, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function searchByItem($filtro) {
        
        $resp = Yii::app()->db->createCommand("
		    SELECT TOP 10 Id, CONCAT (Id_Item, ' - ', Referencia, ' - ', Descripcion) AS Descr FROM T_PR_I_ITEM WHERE (Id_Item LIKE '%".$filtro."%' OR Referencia LIKE '%".$filtro."%' OR Descripcion LIKE '%".$filtro."%') AND Estado = 1 ORDER BY Descr 
		")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("
		    SELECT Id, CONCAT (Id_Item, ' - ', Referencia, ' - ', Descripcion) AS Descr FROM T_PR_I_ITEM WHERE Id = '".$filtro."'")->queryAll();
        return $resp;

 	}

	public function DescUnd($und) {
 
        $unidad = Yii::app()->db->createCommand("SELECT DISTINCT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 AND f101_id = '".$und."' ORDER BY f101_descripcion")->queryRow();
        return $unidad['f101_descripcion'];
 	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idlinea' => array(self::BELONGS_TO, 'ILinea', 'Id_Linea'),
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
			'Id_Item' => 'ID Item',
			'Referencia' => 'Referencia',
			'Descripcion' => 'Descripción',
			'UND_Medida' => 'Und. de medida',
			'Id_Linea' => 'Línea',
			'Mes_Stock' => 'Stock Mes',
			'Min_Stock' => 'Stock Min.',
			'Max_Stock' => 'Stock Max.',
			'Vlr_Costo' => 'Costo total inventario',
			'Vlr_Ult_Compra' => 'Vlr. unitario ultima compra',
			'Total_Inventario' => 'Cant. total inventario',
			'Nota' => 'Nota',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'costo_unit' => 'Costo unitario',
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
	   	$criteria->with=array('idusuariocre','idusuarioact');

		$criteria->compare('t.Id',$this->Id);
		$criteria->compare('t.Id_Item',$this->Id_Item);
		$criteria->compare('t.Referencia',$this->Referencia,true);
		$criteria->compare('t.Descripcion',$this->Descripcion,true);
		$criteria->compare('t.UND_Medida',$this->UND_Medida,true);
		$criteria->compare('t.Id_Linea',$this->Id_Linea);
		$criteria->compare('t.Mes_Stock',$this->Mes_Stock);
		$criteria->compare('t.Min_Stock',$this->Min_Stock);
		$criteria->compare('t.Max_Stock',$this->Max_Stock);
		$criteria->compare('t.Nota',$this->Nota,true);
		$criteria->compare('t.Estado',$this->Estado);

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
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Id_Item ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Id_Item DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Referencia ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Referencia DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Descripcion ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Descripcion DESC'; 
			        break;
		        case 9:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 13:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 15:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 16:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 17:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 18:
			        $criteria->order = 't.Estado ASC'; 
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
	 * @return IItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

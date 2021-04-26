<?php

/**
 * This is the model class for table "T_PR_AREA_ELEMENTO".
 *
 * The followings are the available columns in table 'T_PR_AREA_ELEMENTO':
 * @property integer $Id_A_elemento
 * @property integer $Id_Area
 * @property integer $Id_Elemento
 * @property boolean $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property integer $Id_Subarea
 * @property integer $Dias_Entrega_Min
 * @property integer $Dias_Entrega_Max
 * @property integer $Opc_Entr
 *
 * The followings are the available model relations:
 * @property THELEMENTOSUGERIDO[] $tHELEMENTOSUGERIDOs
 * @property THAREA $idArea
 * @property THELEMENTO $idElemento
 * @property THSUBAREA $idSubarea
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THELEMENTOEMPLEADO[] $tHELEMENTOEMPLEADOs
 */
class AreaElemento extends CActiveRecord
{
	
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_AREA_ELEMENTO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Area, Id_Subarea, Id_Elemento, Dias_Entrega_Min, Dias_Entrega_Max, Opc_Entr, Estado', 'required'),
			array('Id_Area, Id_Subarea, Id_Elemento,', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Id_Elemento','message'=>'Elemento - Área - Subárea ya existe en el sistema.'),
			array('Id_Area, Id_Subarea, Id_Elemento, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_A_elemento, Id_Area, Id_Subarea, Id_Elemento, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function searchByElem($filtro) {
        
        $resp = Yii::app()->db->createCommand("
		    SELECT TOP 10 t.Id_A_elemento AS Id, e.Elemento AS Elemento, s.Subarea AS Subarea, a.Area AS Area FROM T_PR_AREA_ELEMENTO t
		    LEFT JOIN T_PR_ELEMENTO e ON e.Id_Elemento = t.Id_Elemento LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = t.Id_Subarea LEFT JOIN T_PR_AREA a ON a.Id_Area = t.Id_Area
		     WHERE t.Estado = 1 AND e.Estado = 1 AND s.Estado = 1 AND a.Estado = 1 AND (e.Elemento LIKE '%".$filtro."%' OR s.Subarea  LIKE '%".$filtro."%' OR a.Area LIKE '%".$filtro."%') ORDER BY e.Elemento ASC, s.Subarea ASC, a.Area ASC
		")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
 		$modelo_area_elemento = AreaElemento::model()->findByPk($filtro);
        return $modelo_area_elemento;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idarea' => array(self::BELONGS_TO, 'Area', 'Id_Area'),
			'idsubarea' => array(self::BELONGS_TO, 'Subarea', 'Id_Subarea'),
			'idelemento' => array(self::BELONGS_TO, 'Elemento', 'Id_Elemento'),
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
			'Id_A_elemento' => 'ID',
			'Id_Area' => 'Área',
			'Id_Subarea' => 'Subárea',
			'Id_Elemento' => 'Elemento',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'orderby' => 'Orden de resultados',
			'Dias_Entrega_Min' => 'Dias de entrega min.',
			'Dias_Entrega_Max' => 'Dias de entrega max.',
			'Opc_Entr' => 'Opción de entrega',
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
	   	$criteria->with=array('idelemento', 'idarea','idsubarea','idusuariocre','idusuarioact');

		$criteria->compare('t.Id_A_elemento',$this->Id_A_elemento);
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

	    if($this->Id_Elemento != ""){
			$criteria->AddCondition("t.Id_Elemento = ".$this->Id_Elemento); 
	    }


	    if($this->Id_Area != ""){
			$criteria->AddCondition("t.Id_Area = ".$this->Id_Area); 
	    }

	    if($this->Id_Subarea != ""){
			$criteria->AddCondition("t.Id_Subarea = ".$this->Id_Subarea); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 'idelemento.Elemento ASC, idsubarea.Subarea ASC, idarea.Area ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_A_elemento ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_A_elemento DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idelemento.Elemento ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idelemento.Elemento DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'idsubarea.Subarea ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'idsubarea.Subarea DESC'; 
			        break;
			    case 7:
			        $criteria->order = 'idarea.Area ASC'; 
			        break;
			    case 8:
			        $criteria->order = 'idarea.Area DESC'; 
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
	 * @return AreaElemento the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

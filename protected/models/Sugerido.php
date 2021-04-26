<?php

/**
 * This is the model class for table "T_PR_SUGERIDO".
 *
 * The followings are the available columns in table 'T_PR_SUGERIDO':
 * @property integer $Id_Sugerido
 * @property integer $Id_Cargo
 * @property integer $Id_Area
 * @property integer $Id_Subarea
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property boolean $Estado
 *
 * The followings are the available model relations:
 * @property THAREA $idArea
 * @property THCARGO $idCargo
 * @property THSUBAREA $idSubarea
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THELEMENTOSUGERIDO[] $tHELEMENTOSUGERIDOs
 */
class Sugerido extends CActiveRecord
{
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_SUGERIDO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Cargo, Id_Area, Id_Subarea', 'required', 'on' => 'create'),
			array('Estado', 'required', 'on' => 'update'),
			array('Id_Cargo, Id_Area, Id_Subarea', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Id_Cargo','message'=>'La combinación cargo - subárea - área ya existe en el sistema'),
			array('Id_Cargo, Id_Area, Id_Subarea, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Sugerido, Id_Cargo, Id_Area, Id_Subarea, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, Estado, orderby', 'safe', 'on'=>'search'),
		);
	}

 	public function searchBySug($filtro) {
        
        $resp = Yii::app()->db->createCommand("
		    SELECT TOP 10 t.Id_Sugerido AS Id, c.Cargo AS Cargo, s.Subarea AS Subarea, a.Area AS Area FROM T_PR_SUGERIDO t
		    LEFT JOIN T_PR_CARGO c ON c.Id_Cargo = t.Id_Cargo LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = t.Id_Subarea LEFT JOIN T_PR_AREA a ON a.Id_Area = t.Id_Area
		     WHERE t.Estado = 1 AND c.Estado = 1 AND s.Estado = 1 AND a.Estado = 1 AND (c.Cargo LIKE '%".$filtro."%' OR s.Subarea  LIKE '%".$filtro."%' OR a.Area LIKE '%".$filtro."%') ORDER BY c.Cargo ASC, s.Subarea ASC, a.Area ASC
		")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
       	$modelo_sugerido = Sugerido::model()->findByPk($filtro);
        return $modelo_sugerido;

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
			'idcargo' => array(self::BELONGS_TO, 'Cargo', 'Id_Cargo'),
			'idsubarea' => array(self::BELONGS_TO, 'Subarea', 'Id_Subarea'),
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
			'Id_Sugerido' => 'ID',
			'Id_Cargo' => 'Cargo',
			'Id_Area' => 'Área',
			'Id_Subarea' => 'Subárea',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Estado' => 'Estado',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',	
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
	   	$criteria->with=array('idusuariocre','idusuarioact', 'idarea', 'idsubarea','idcargo');
		
		$criteria->compare('t.Id_Sugerido',$this->Id_Sugerido);
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

		if($this->Id_Area != ""){
			$criteria->AddCondition("t.Id_Area = ".$this->Id_Area); 
	    }

    	if($this->Id_Subarea != ""){
			$criteria->AddCondition("t.Id_Subarea = ".$this->Id_Subarea); 
	    }

	    if($this->Id_Cargo != ""){
			$criteria->AddCondition("t.Id_Cargo = ".$this->Id_Cargo); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 'idcargo.Cargo ASC, idsubarea.Subarea ASC, idarea.Area ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Sugerido ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Sugerido DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idcargo.Cargo ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idcargo.Cargo DESC'; 
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
	 * @return Sugerido the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

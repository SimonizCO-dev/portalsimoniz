<?php

/**
 * This is the model class for table "T_PR_ELEMENTO_SUGERIDO".
 *
 * The followings are the available columns in table 'T_PR_ELEMENTO_SUGERIDO':
 * @property integer $Id_E_Sugerido
 * @property integer $Id_Sugerido
 * @property integer $Id_A_Elemento
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property boolean $Estado
 * @property integer $Cantidad
 *
 * The followings are the available model relations:
 * @property THAREAELEMENTO $idAElemento
 * @property THSUGERIDO $idSugerido
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class ElementoSugerido extends CActiveRecord
{
	
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_ELEMENTO_SUGERIDO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Sugerido, Id_A_Elemento, Cantidad', 'required', 'on' => 'create'),
			array('Id_Sugerido, Id_A_Elemento, Cantidad, Estado', 'required', 'on' => 'update'),
			array('Id_Sugerido, Id_A_Elemento', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Id_Sugerido','message'=>'Sugerido - Elemento ya existe en el sistema.'),
			array('Id_Sugerido, Id_A_Elemento, Cantidad, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_E_Sugerido, Id_Sugerido, Id_A_Elemento, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, Estado, orderby', 'safe', 'on'=>'search'),
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
			'idaelemento' => array(self::BELONGS_TO, 'AreaElemento', 'Id_A_Elemento'),
			'idsugerido' => array(self::BELONGS_TO, 'Sugerido', 'Id_Sugerido'),
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
			'Id_E_Sugerido' => 'ID',
			'Id_Sugerido' => 'Sugerido (Cargo - Subárea / Área)',
			'Id_A_Elemento' => 'Elemento (Subárea / Área)',
			'Cantidad' => 'Cantidad',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'Estado' => 'Estado',
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

		$criteria->join = '
		LEFT JOIN T_PR_USUARIO idusuariocre ON t.Id_Usuario_Creacion = idusuariocre.Id_Usuario
		LEFT JOIN T_PR_USUARIO idusuarioact ON t.Id_Usuario_Actualizacion = idusuarioact.Id_Usuario
		LEFT JOIN T_PR_SUGERIDO idsugerido ON t.Id_Sugerido = idsugerido.Id_Sugerido
		LEFT JOIN T_PR_CARGO idcargosu ON idsugerido.Id_Cargo = idcargosu.Id_Cargo
		LEFT JOIN T_PR_SUBAREA idsubareasu ON idsugerido.Id_Subarea = idsubareasu.Id_Subarea
		LEFT JOIN T_PR_AREA idareasu ON idsugerido.Id_Area = idareasu.Id_Area
		LEFT JOIN T_PR_AREA_ELEMENTO idaelemento ON t.Id_A_Elemento = idaelemento.Id_A_Elemento
		LEFT JOIN T_PR_ELEMENTO idelemento ON idelemento.Id_Elemento = idaelemento.Id_Elemento
		LEFT JOIN T_PR_SUBAREA idsubareael ON idaelemento.Id_Subarea = idsubareael.Id_Subarea
		LEFT JOIN T_PR_AREA idareael ON idaelemento.Id_Area = idareael.Id_Area
		';

		$criteria->compare('t.Id_E_Sugerido',$this->Id_E_Sugerido);
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

		if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if($this->Id_Sugerido != ""){
			$criteria->AddCondition("t.Id_Sugerido = '".$this->Id_Sugerido."'"); 
	    }


	    if($this->Id_A_Elemento != ""){
			$criteria->AddCondition("t.Id_A_Elemento = '".$this->Id_A_Elemento."'"); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 'idcargosu.Cargo ASC, idsubareasu.Subarea ASC, idareasu.Area ASC, idelemento.Elemento ASC, idsubareael.Subarea ASC, idareael.Area ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_E_Sugerido ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_E_Sugerido DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idcargosu.Cargo ASC, idsubareasu.Subarea ASC, idareasu.Area ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idcargosu.Cargo DESC, idsubareasu.Subarea DESC, idareasu.Area DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'idelemento.Elemento ASC, idsubareael.Subarea ASC, idareael.Area ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'idelemento.Elemento DESC, idsubareael.Subarea DESC, idareael.Area DESC';  
			        break;
		        case 7:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 8:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 11:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 13:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 15:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 16:
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
	 * @return ElementoSugerido the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

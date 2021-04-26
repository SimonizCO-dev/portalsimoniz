<?php

/**
 * This is the model class for table "T_PR_AREA_USUARIO".
 *
 * The followings are the available columns in table 'T_PR_AREA_USUARIO':
 * @property integer $Id_A_Usuario
 * @property integer $Id_Usuario
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property integer $Id_Area
 * @property integer $Estado
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THAREA $idArea
 * @property THUSUARIO $idUsuario
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class AreaUsuario extends CActiveRecord
{
	
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_AREA_USUARIO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Usuario, Id_Area', 'required'),
			array('Id_Usuario, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Area, Estado', 'numerical', 'integerOnly'=>true),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_A_Usuario, Id_Usuario, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Area, Estado, Fecha_Creacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
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
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idarea' => array(self::BELONGS_TO, 'Area', 'Id_Area'),
			'idusuario' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_A_Usuario' => 'ID',
			'Id_Usuario' => 'Usuario',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Id_Area' => 'Área',
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
		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('idusuario', 'idarea','idusuariocre','idusuarioact');

		$criteria->compare('t.Id_A_Usuario',$this->Id_A_Usuario);
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

	    if($this->Id_Usuario != ""){
			$criteria->AddCondition("t.Id_Usuario = ".$this->Id_Usuario); 
	    }

	    if($this->Id_Area != ""){
			$criteria->AddCondition("t.Id_Area = ".$this->Id_Area); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_A_Usuario DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 'idusuario.Usuario ASC'; 
			        break;
			    case 2:
			        $criteria->order = 'idusuario.Usuario DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idarea.Area ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idarea.Area DESC'; 
			        break;
		        case 5:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 9:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 11:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 13:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 14:
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
	 * @return AreaUsuario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

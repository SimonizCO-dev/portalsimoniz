<?php

/**
 * This is the model class for table "T_PR_PERFIL".
 *
 * The followings are the available columns in table 'T_PR_PERFIL':
 * @property integer $Id_Perfil
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Descripcion
 * @property boolean $Estado
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property boolean $Modificacion_Reg
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioActualizacion
 * @property THUSUARIOS $idUsuarioCreacion
 */
class Perfil extends CActiveRecord
{
	public $opciones_menu;
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_PERFIL';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Descripcion, Estado, opciones_menu, Modificacion_Reg', 'required'),
			array('Descripcion','unique'),
			array('Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Descripcion', 'length', 'max'=>50),
			array('Fecha_Creacion, Fecha_Actualizacion', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Perfil, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Descripcion, Estado, Fecha_Creacion, Fecha_Actualizacion, Modificacion_Reg, orderby', 'safe', 'on'=>'search'),
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
			'Id_Perfil' => 'ID',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualiz??',
			'Descripcion' => 'Perfil',
			'Estado' => 'Estado',
			'Fecha_Creacion' => 'Fecha de creaci??n',
			'Fecha_Actualizacion' => 'Ultima fecha de actualizaci??n',
			'orderby' => 'Orden de resultados',
			'Modificacion_Reg' => 'Permitir act. de reg.',
			'opciones_menu' => 'Opciones de menu'

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

		$criteria->compare('t.Id_Perfil',$this->Id_Perfil);
		$criteria->compare('t.Descripcion',$this->Descripcion,true);
		$criteria->compare('t.Estado',$this->Estado);
		$criteria->compare('t.Modificacion_Reg',$this->Modificacion_Reg);

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
			$criteria->order = 't.Id_Perfil DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Perfil ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Perfil DESC'; 
			        break;
			    case 3:
			        $criteria->order = 't.Descripcion ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.Descripcion DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Modificacion_Reg ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Modificacion_Reg DESC'; 
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
	 * @return Perfil the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

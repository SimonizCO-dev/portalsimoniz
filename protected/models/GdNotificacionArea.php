<?php

/**
 * This is the model class for table "T_PR_GD_NOTIFICACION_AREA".
 *
 * The followings are the available columns in table 'T_PR_NOTIFICACION_AREA':
 * @property integer $Id_Notif_Area
 * @property integer $Id_Area
 * @property string $Correos_Notif
 * @property integer $Estado_Notif
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class GdNotificacionArea extends CActiveRecord
{
	
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_GD_NOTIFICACION_AREA';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Area, Correos_Notif, Estado_Notif, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Id_Area, Estado_Notif, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Id_Area','unique','message' => 'Ya existe una configuración para esta área.'),
			array('Correos_Notif','ComprobarEmail'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Notif_Area, Id_Area, Correos_Notif, Estado_Notif, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function ComprobarEmail($attribute,$params) {
		$validos = 0;
		$analizar = explode(',', $this->Correos_Notif);
    	for($i = 0; $i < sizeof($analizar); $i++){
        	if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $this->Correos_Notif)) $validos++;
    	}

    	if( $validos != sizeof($analizar) ){
        	$this->addError($attribute, 'Hay e-mails no validos.');
        } 
   
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
			'idarea' => array(self::BELONGS_TO, 'Area', 'Id_Area'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Notif_Area' => 'ID',
			'Id_Area' => 'Área',
			'Correos_Notif' => 'E-mail(s) para notificaciones',
			'Estado_Notif' => 'Envio de e-mail',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
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
	   	$criteria->with=array('idusuariocre','idusuarioact','idarea');

		$criteria->compare('t.Id_Notif_Area',$this->Id_Notif_Area);
		$criteria->compare('t.Id_Area',$this->Id_Area);

		if($this->Correos_Notif != ""){
			$criteria->AddCondition("t.Correos_Notif LIKE '%".$this->Correos_Notif."%'"); 
	    }
		
		$criteria->compare('t.Estado_Notif',$this->Estado_Notif);
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
			$criteria->AddCondition("t.>Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

    	if(empty($this->orderby)){
			$criteria->order = 't.Id_Notif_Area DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Notif_Area ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Notif_Area DESC'; 
			        break;
		        case 3:
			        $criteria->order = 'idarea.Area ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idarea.Area DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Estado_Notif ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Estado_Notif DESC'; 
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
	 * @return NotificacionArea the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "TH_TURNO_TRABAJO".
 *
 * The followings are the available columns in table 'TH_TURNO_TRABAJO':
 * @property integer $Id_Turno_Trabajo
 * @property string $Rango_Dias1
 * @property string $Entrada1
 * @property string $Salida1
 * @property string $Rango_Dias2
 * @property string $Entrada2
 * @property string $Salida2
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property Usuario $idUsuarioCreacion
 * @property Usuario $idUsuarioActualizacion
 */
class TurnoTrabajo extends CActiveRecord
{
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_TURNO_TRABAJO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Rango_Dias1, Entrada1, Salida1, Estado', 'required'),
			array('Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Rango_Dias1, Rango_Dias2', 'length', 'max'=>3),
			array('Entrada2, Salida2', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Turno_Trabajo, Rango_Dias1, Entrada1, Salida1, Rango_Dias2, Entrada2, Salida2, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function HoraAmPm($hora) {

		if($hora != ""){
			return date('h:i A', strtotime($hora));
		}else{
			return '';
		}    
		
 	}

 	public function DescTurno($id) {
 
       	$modelo_turno = TurnoTrabajo::model()->findByPk($id);

       	if($modelo_turno->Rango_Dias2 == ""){
       		$turno = $modelo_turno->Rango_Dias1.': De '.$modelo_turno->HoraAmPm($modelo_turno->Entrada1).' A '.$modelo_turno->HoraAmPm($modelo_turno->Salida1);
       	}else{
       		$turno = $modelo_turno->Rango_Dias1.': De '.$modelo_turno->HoraAmPm($modelo_turno->Entrada1).' A '.$modelo_turno->HoraAmPm($modelo_turno->Salida1).' / '.$modelo_turno->Rango_Dias2.': De '.$modelo_turno->HoraAmPm($modelo_turno->Entrada2).' A '.$modelo_turno->HoraAmPm($modelo_turno->Salida2);
       	}

        return $turno;

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
			'Id_Turno_Trabajo' => 'ID',
			'Rango_Dias1' => 'Rango 1',
			'Entrada1' => 'Hora de entrada',
			'Salida1' => 'Hora de salida',
			'Rango_Dias2' => 'Rango 2',
			'Entrada2' => 'Hora de entrada',
			'Salida2' => 'Hora de salida',
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
	   	$criteria->with=array('idusuariocre','idusuarioact');

		$criteria->compare('t.Id_Turno_Trabajo',$this->Id_Turno_Trabajo);
		$criteria->compare('t.Rango_Dias1',$this->Rango_Dias1,true);
		
		if($this->Entrada1 != ""){ 
			$Entrada1 = date('H:i:s', strtotime($this->Entrada1));
			$criteria->AddCondition("t.Entrada1 = '".$Entrada1."'");	
		}

		if($this->Salida1 != ""){ 
			$Salida1 = date('H:i:s', strtotime($this->Salida1));
			$criteria->AddCondition("t.Salida1 = '".$Salida1."'");	
		}

		$criteria->compare('t.Rango_Dias2',$this->Rango_Dias2,true);

		if($this->Entrada2 != ""){ 
			$Entrada2 = date('H:i:s', strtotime($this->Entrada2));
			$criteria->AddCondition("t.Entrada2 = '".$Entrada2."'");	
		}

		if($this->Salida2 != ""){ 
			$Salida2 = date('H:i:s', strtotime($this->Salida2));
			$criteria->AddCondition("t.Salida2 = '".$Salida2."'");	
		}

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
			$criteria->AddCondition("t.Id_Usuario_Creacion = '".$this->Id_Usuario_Creacion."'"); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Turno_Trabajo DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Turno_Trabajo ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Turno_Trabajo DESC'; 
			        break;
			    /*case 3:
			        $criteria->order = 't.CADENA_NIT ASC'; 
			        break;
			    case 4:
			        $criteria->order = 't.CADENA_NIT DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.CADENA_SUC ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.CADENA_SUC DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.KAM_NIT ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.KAM_NIT DESC'; 
			        break;
		        case 9:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.FECHA_CREACION ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.FECHA_CREACION DESC'; 
			        break;
			    case 13:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 15:
			        $criteria->order = 't.FECHA_ACTUALIZACION ASC'; 
			        break;
			    case 16:
			        $criteria->order = 't.FECHA_ACTUALIZACION DESC'; 
			        break;
			    case 17:
			        $criteria->order = 't.ESTADO DESC'; 
			        break;
			    case 18:
			        $criteria->order = 't.ESTADO ASC'; 
			        break;*/
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
	 * @return TurnoTrabajo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "T_PR_CONTROL_RECIBOS".
 *
 * The followings are the available columns in table 'T_PR_CONTROL_RECIBOS':
 * @property integer $Id_Control
 * @property string $Recibo
 * @property string $Url
 * @property integer $Opc
 * @property integer $Id_Usuario_Carga
 * @property string $Fecha_Hora_Carga
 * @property integer $Verificacion
 * @property integer $Id_Usuario_Verif
 * @property string $Fecha_Hora_Verif
 * @property integer $Id_Usuario_Aplic
 * @property string $Fecha_Hora_Aplic
 * @property integer $Id_Usuario_Rec_Fis
 * @property string $Fecha_Hora_Rec_Fis
 * @property string $Fecha_Banco
 * @property string $Fecha_Cheque
 * @property integer $Banco_Correcto
 * @property integer $Motivo_Rechazo
 * @property string $Observaciones
 * @property string $Fecha_Hora_Obs


 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioCarga
 * @property THUSUARIOS $idUsuarioVerif
 * @property THUSUARIOS $idUsuarioAplic
 * @property THUSUARIOS $idUsuarioRecFis
 */
class ControlRecibos extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_CONTROL_RECIBOS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Opc, Id_Usuario_Carga, Verificacion, Id_Usuario_Verif, Id_Usuario_Aplic, Id_Usuario_Rec_Fis, Banco_Correcto, Motivo_Rechazo', 'numerical', 'integerOnly'=>true),
			array('Recibo', 'length', 'max'=>100),
			array('Url', 'length', 'max'=>200),
			array('Fecha_Hora_Carga, Fecha_Hora_Verif, Fecha_Hora_Aplic, Fecha_Hora_Rec_Fis, Fecha_Banco', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Control, Recibo, Opc, Verificacion', 'safe', 'on'=>'search'),
			array('Id_Control, Recibo, Opc, Verificacion, Fecha_Hora_Carga, Fecha_Hora_Verif, Fecha_Hora_Aplic, Fecha_Hora_Rec_Fis', 'safe', 'on'=>'search_estados'),
		);
	}

	public function Desc_Opc($opc){

        switch ($opc) {
			case 1:
			    return 'CARGADO'; 
			    break;
			case 2:
			    return 'VERIFICADO';  
			    break;
			case 3:
			    return 'APLICADO'; 
			    break;
			case 4:
			    return 'ENTREGADO FISICAMENTE';  
			    break;
		}

    }

    public function Desc_Verif($verif){

         switch ($verif) {
			case 1:
			    return 'APROBADO'; 
			    break;
			case 2:
			    return 'RECHAZADO BANCO';  
			    break;
			case 3:
			    return 'RECHAZADO VALOR'; 
			    break;
			case 4:
			    return 'OTRO RECHAZO';  
			    break;
		}

    }

    public function Desc_Banco($banco){

         switch ($banco) {
			case 1:
			    return 'BBVA'; 
			    break;
			case 2:
			    return 'BANCOLOMBIA';  
			    break;
			case 3:
			    return 'OCCIDENTE'; 
			    break;
			case 4:
			    return 'OCCIDENTE PANSELL';  
			    break;
		}

    }

    public function Desc_Motivo_Rechazo($motivo){

         switch ($motivo) {
			case 1:
			    return 'GRUPO EFECTIVO INCOMPLETO'; 
			    break;
			case 2:
			    return 'RCC MAL SUBIDO AL APLICATIVO';  
			    break;
			case 3:
			    return 'VALOR NO COINCIDE EN RCC'; 
			    break;
			case 4:
			    return 'PAGO YA CONFIRMADO';  
			    break;
			case 5:
			    return 'PAGO NO EVIDENCIADO EN BANCOS'; 
			    break;
			case 6:
			    return 'RUTA INCORRECTA';  
			    break;
			case 7:
			    return 'RCC NO DILIGENCIADO COMPLETAMENTE'; 
			    break;
			case 8:
			    return 'SIN SOPORTE DE CONSIGNACIÓN';  
			    break;
			case 9:
			    return 'PAGO SUBIDO DOBLE VEZ'; 
			    break;
			case 10:
			    return 'DIFERENCIA EN VALOR MAYOR A $300 PESOS';  
			    break;
			case 11:
			    return 'SOPORTE NO LEGIBLE Y/O INCOMPLETO'; 
			    break;
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
			'idusuariocarga' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Carga'),
			'idusuarioverif' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Verif'),
			'idusuarioaplic' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Aplic'),
			'idusuariorecfis' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Rec_Fis'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Control' => 'ID',
			'Recibo' => 'Recibo',
			'Url' => 'Url',
			'Opc' => 'Estado',
			'Id_Usuario_Carga' => 'Usuario que cargo',
			'Fecha_Hora_Carga' => 'Fecha de carga',
			'Verificacion' => 'Estado de verificación',
			'Id_Usuario_Verif' => 'Usuario que verificó',
			'Fecha_Hora_Verif' => 'Fecha de verificación',
			'Id_Usuario_Aplic' => 'Usuario que aplicó',
			'Fecha_Hora_Aplic' => 'Fecha de aplicación',
			'Id_Usuario_Rec_Fis' => 'Usuario que verifica ent. física',
			'Fecha_Hora_Rec_Fis' => 'Fecha de verificación ent. física',
			'Fecha_Banco' => 'Fecha banco',
			'Fecha_Cheque' => 'Fecha cheque',
			'Banco_Correcto' => 'Banco correcto',
			'Motivo_Rechazo' => 'Motivo de rechazo',
			'Observaciones' => 'Observaciones',
			'Fecha_Hora_Obs' => 'Fecha y hora de observación',
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

		if($this->Recibo == "" && $this->Opc == "" && $this->Fecha_Hora_Carga == "" && $this->Fecha_Hora_Verif == "" && $this->Fecha_Hora_Aplic == "" && $this->Fecha_Hora_Rec_Fis == ""){
			$criteria->AddCondition("t.Id_Control = 0"); 
		}

		$criteria->compare('t.Id_Control',$this->Id_Control);
		$criteria->compare('t.Recibo',$this->Recibo,true);
		$criteria->compare('t.Opc',$this->Opc);

		if($this->Verificacion != ""){

			$cond = implode(",", $this->Verificacion);

			$criteria->AddCondition("t.Verificacion IN (".$cond.")"); 
	    }

	    if($this->Fecha_Hora_Carga != ""){
      		$fai = $this->Fecha_Hora_Carga." 00:00:00";
      		$faf = $this->Fecha_Hora_Carga." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Carga', $fai, $faf, 'OR');
    	}

    	if($this->Fecha_Hora_Verif != ""){
      		$fai = $this->Fecha_Hora_Verif." 00:00:00";
      		$faf = $this->Fecha_Hora_Verif." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Verif', $fai, $faf, 'OR');
    	}

    	if($this->Fecha_Hora_Aplic != ""){
      		$fai = $this->Fecha_Hora_Aplic." 00:00:00";
      		$faf = $this->Fecha_Hora_Aplic." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Aplic', $fai, $faf, 'OR');
    	}

    	if($this->Fecha_Hora_Rec_Fis != ""){
      		$fai = $this->Fecha_Hora_Rec_Fis." 00:00:00";
      		$faf = $this->Fecha_Hora_Rec_Fis." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Hora_Rec_Fis', $fai, $faf, 'OR');
    	}

	    $criteria->order = 't.Recibo';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}


	public function search_estados()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.Id_Control',$this->Id_Control);
		$criteria->compare('t.Recibo',$this->Recibo,true);
		$criteria->compare('t.Opc',$this->Opc);

		//solo muestra los recibos con estado verificado y aplicado (los unicos con opcion de reversar)
		$criteria->AddCondition("t.Opc IN (2,3)"); 

		if($this->Verificacion != ""){

			$cond = implode(",", $this->Verificacion);

			$criteria->AddCondition("t.Verificacion IN (".$cond.")"); 
	    }

	    $criteria->order = 't.Recibo';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}


	public function search_mod_rec()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.Id_Control',$this->Id_Control);
		$criteria->compare('t.Recibo',$this->Recibo,true);

		//solo muestra los recibos con estado cargado los unicos al que se le puede act. la img
		$criteria->AddCondition("t.Opc = 1"); 

	    $criteria->order = 't.Recibo';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ControlRecibos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

<?php

/**
 * This is the model class for table "TH_LOG".
 *
 * The followings are the available columns in table 'TH_LOG':
 * @property integer $Id_Log
 * @property integer $Tipo
 * @property integer $Id_Usuario
 * @property integer $Id_Menu
 * @property string $Accion
 * @property string $Fecha_Hora
 *
 * The followings are the available model relations:
 * @property THMENU $idMenu
 * @property THUSUARIOS $idUsuario
 */
class Log extends CActiveRecord
{
	
	public $fecha_inicial;
	public $fecha_final;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_LOG';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Tipo, Id_Usuario, Fecha_Hora', 'required'),
			array('Tipo, Id_Usuario, Id_Menu', 'numerical', 'integerOnly'=>true),
			array('Accion', 'length', 'max'=>1000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Log, Tipo, Id_Usuario, Id_Menu, Accion, Fecha_Hora, fecha_inicial, fecha_final', 'safe', 'on'=>'search'),
		);
	}

	public function DescTipo($tipo){

		switch ($tipo) {
		    case 1:
		        $texto_tipo = 'SESIÓN';
		        break;
		    case 2:
		        $texto_tipo = 'CONSULTA DE MENÚ';
		        break;
		    case 3:
		        $texto_tipo = 'EJECUCIÓN';
		        break;    
		}

		return $texto_tipo;

	}

	public function DescOpcPadre($Id_Menu){

    	$Parent1 = Menu::model()->findByPk($Id_Menu);
	    $Parent2 = Menu::model()->findByPk($Parent1->Id_Padre);

    	if(intval($Id_Menu) == 1){
    		return $Parent1->Descripcion;
    	}else{

	        $Parent1 = Menu::model()->findByPk($Id_Menu);
	        $Parent2 = Menu::model()->findByPk($Parent1->Id_Padre);

	        return $Parent2->Descripcion.' -> '.$Parent1->Descripcion;	
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
			'idmenu' => array(self::BELONGS_TO, 'Menu', 'Id_Menu'),
			'idusuario' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Log' => 'ID',
			'Tipo' => 'Tipo',
			'Id_Usuario' => 'Usuario',
			'Id_Menu' => 'Opción',
			'Accion' => 'Acción',
			'Fecha_Hora' => 'Fecha y hora',
			'fecha_inicial' => 'Fecha inicial',
			'fecha_final' => 'Fecha final',
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


		
		$criteria->compare('t.Fecha_Hora',$this->Fecha_Hora,true);

		if($this->Tipo != "" || $this->Id_Usuario != "" || $this->Accion != "" || $this->Id_Menu != "" || $this->fecha_inicial != "" || $this->fecha_final != ""){

			
			$criteria->compare('t.Tipo',$this->Tipo);
			$criteria->compare('t.Id_Usuario',$this->Id_Usuario);
			$criteria->compare('t.Id_Menu',$this->Id_Menu);

			if($this->Accion != ""){
	      		$criteria->AddCondition("t.Accion LIKE '%".$this->Accion."%'"); 
	    	}

			if($this->fecha_inicial != "" && $this->fecha_final != ""){
	      		$fci = $this->fecha_inicial." 00:00:00";
	      		$fcf = $this->fecha_final." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Hora', $fci, $fcf);
	    	}else{
	    		if($this->fecha_inicial != "" && $this->fecha_final == ""){	
	    			$fci = $this->fecha_inicial." 00:00:00";
	      			$fcf = $this->fecha_inicial." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Hora', $fci, $fcf);
	    		}
	    	}

	    	$criteria->order = 't.Id_Log DESC'; 

		}else{
			//no se muestran registros
			$criteria->AddCondition("t.Id_Log = 0"); 
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
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

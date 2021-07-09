<?php

/**
 * This is the model class for table "TH_EM_PROD".
 *
 * The followings are the available columns in table 'TH_EM_PROD':
 * @property integer $Id_Em_Prod
 * @property string $Notas
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class EmProd extends CActiveRecord
{
	public $sop;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_EM_PROD';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Notas', 'required'),
			array('Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Documento', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Reg_Imp, Notas, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, item', 'safe', 'on'=>'search'),
		);
	}


	public function ResumenUsers($id){
        
  		//se busca el mismo nombre de usuario con id diferente al registro afectado
        $criteria=new CDbCriteria;
		$criteria->condition='Id_Em_Prod=:Id_Em_Prod';
		$criteria->params=array(':Id_Em_Prod'=>$id);
		$total= count(EmProdVal::model()->findAll($criteria));

		$criteria=new CDbCriteria;
		$criteria->condition='Id_Em_Prod=:Id_Em_Prod AND Estado=:Estado';
		$criteria->params=array(':Id_Em_Prod'=>$id,':Estado'=>1);
		$val= count(EmProdVal::model()->findAll($criteria));

		return $total.' / '.$val;
    
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
			'Id_Em_Prod' => 'ID',
			'Documento' => 'Documento',
			'Notas' => 'Nombre de emisión',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'sop' => 'Soporte',
			'val_us' => 'Usuarios Not. / Validación',
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


		$criteria->compare('t.Id_Em_Prod',$this->Id_Em_Prod);
		$criteria->compare('t.Notas',$this->Notas,true);

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }


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

    	$criteria->order = 't.Id_Em_Prod DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RegImp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

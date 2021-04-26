<?php

/**
 * This is the model class for table "T_PR_C_PTJ_RECAUDOS".
 *
 * The followings are the available columns in table 'T_PR_C_PTJ_RECAUDOS':
 * @property integer $ROWID
 * @property integer $TIPO
 * @property integer $DIA_INICIAL
 * @property integer $DIA_FINAL
 * @property string $PORCENTAJE
 * @property integer $ESTADO
 * @property integer $ID_USUARIO_CREACION
 * @property string $FECHA_CREACION
 * @property integer $ID_USUARIO_ACTUALIZACION
 * @property string $FECHA_ACTUALIZACION
 *
 * The followings are the available model relations:
 * @property THUSUARIO $iDUSUARIOCREACION
 * @property THUSUARIO $iDUSUARIOACTUALIZACION
 * @property THDOMINIO $tIPO
 */
class CPtjRecaudos extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_C_PTJ_RECAUDOS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TIPO, DIA_INICIAL, DIA_FINAL, PORCENTAJE', 'required'),
			array('TIPO, DIA_INICIAL, DIA_FINAL, ESTADO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION', 'numerical', 'integerOnly'=>true),
			array('PORCENTAJE', 'length', 'max'=>5),
			array('FECHA_CREACION, FECHA_ACTUALIZACION', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ROWID, TIPO, DIA_INICIAL, DIA_FINAL, PORCENTAJE, ESTADO, ID_USUARIO_CREACION, FECHA_CREACION, ID_USUARIO_ACTUALIZACION, FECHA_ACTUALIZACION', 'safe', 'on'=>'search'),
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
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_CREACION'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_ACTUALIZACION'),
			'tipo' => array(self::BELONGS_TO, 'Dominio', 'TIPO'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ROWID' => 'ID',
			'TIPO' => 'Tipo',
			'DIA_INICIAL' => 'Dia inicial',
			'DIA_FINAL' => 'Dia final',
			'PORCENTAJE' => 'Porcentaje',
			'ESTADO' => 'Estado',
			'ID_USUARIO_CREACION' => 'Usuario que creo',
			'ID_USUARIO_ACTUALIZACION' => 'Ultimo usuario que actualizó',
			'FECHA_CREACION' => 'Fecha de creación',
			'FECHA_ACTUALIZACION' => 'Ultima fecha de actualización',
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

		$criteria->compare('t.ROWID',$this->ROWID);
		$criteria->compare('t.TIPO',$this->TIPO);
		$criteria->compare('t.PORCENTAJE',$this->PORCENTAJE,true);
		$criteria->compare('DIA_INICIAL',$this->DIA_INICIAL);
		$criteria->compare('DIA_FINAL',$this->DIA_FINAL);
		$criteria->compare('t.ESTADO',$this->ESTADO);

		if($this->FECHA_CREACION != ""){
      		$fci = $this->FECHA_CREACION." 00:00:00";
      		$fcf = $this->FECHA_CREACION." 23:59:59";

      		$criteria->addBetweenCondition('t.FECHA_CREACION', $fci, $fcf);
    	}

    	if($this->FECHA_ACTUALIZACION != ""){
      		$fai = $this->FECHA_ACTUALIZACION." 00:00:00";
      		$faf = $this->FECHA_ACTUALIZACION." 23:59:59";

      		$criteria->addBetweenCondition('t.FECHA_ACTUALIZACION', $fai, $faf);
    	}

		if($this->ID_USUARIO_CREACION != ""){
			$criteria->AddCondition("t.ID_USUARIO_CREACION = ".$this->ID_USUARIO_CREACION); 
	    }

    	if($this->ID_USUARIO_ACTUALIZACION != ""){
			$criteria->AddCondition("t.ID_USUARIO_ACTUALIZACION = ".$this->ID_USUARIO_ACTUALIZACION); 
	    }

	    $criteria->order = 't.ROWID DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CPtjRecaudos the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

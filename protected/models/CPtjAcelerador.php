<?php

/**
 * This is the model class for table "T_PR_C_PTJ_ACELERADOR".
 *
 * The followings are the available columns in table 'T_PR_C_PTJ_ACELERADOR':
 * @property integer $ROWID
 * @property integer $TIPO
 * @property integer $ID_ACELERADOR
 * @property integer $ITEM
 * @property integer $ID_PLAN
 * @property string $CRITERIO
 * @property string $PORCENTAJE
 * @property string $FECHA_INICIAL
 * @property string $FECHA_FINAL
 * @property integer $ESTADO
 * @property integer $ID_USUARIO_CREACION
 * @property string $FECHA_CREACION
 * @property integer $ID_USUARIO_ACTUALIZACION
 * @property string $FECHA_ACTUALIZACION
 * @property THDOMINIO $tIPO
 *
 * The followings are the available model relations:
 * @property THUSUARIO $iDUSUARIOCREACION
 * @property THUSUARIO $iDUSUARIOACTUALIZACION
 * @property THDOMINIO $iDACELERADOR
 * @property THDOMINIO $tIPO
 */
//class CAceleradorCms extends CActiveRecord
class CPtjAcelerador extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_C_PTJ_ACELERADOR';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TIPO, ID_ACELERADOR, PORCENTAJE, FECHA_INICIAL, FECHA_FINAL', 'required'),
			array('TIPO, ID_ACELERADOR, ITEM, ID_PLAN, ESTADO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION', 'numerical', 'integerOnly'=>true),
			array('CRITERIO,', 'length', 'max'=>4),
			array('PORCENTAJE', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ROWID, TIPO, ID_ACELERADOR, ITEM, ID_PLAN, CRITERIO, PORCENTAJE, FECHA_INICIAL, FECHA_FINAL, ESTADO, ID_USUARIO_CREACION, FECHA_CREACION, ID_USUARIO_ACTUALIZACION, FECHA_ACTUALIZACION, usuario_creacion, usuario_actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function searchByItem($filtro) {
       
        $resp = Yii::app()->db->createCommand("

        	SELECT TOP 10 I_ID_ITEM, CONCAT(I_ID_ITEM,' - ',I_DESCRIPCION,' - ',I_REFERENCIA) AS ITEM FROM T_CF_ITEMS WHERE I_CRI_TIPO = 'PRODUCTO TERMINADO' AND [I_CIA] =  2 AND (I_ID_ITEM LIKE '%".$filtro."%' OR I_DESCRIPCION LIKE '%".$filtro."%'  OR I_REFERENCIA LIKE '%".$filtro."%') ORDER BY 2")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("
		    SELECT I_ID_ITEM, CONCAT(I_ID_ITEM,' - ',I_DESCRIPCION,' - ',I_REFERENCIA) AS ITEM FROM T_CF_ITEMS WHERE I_ID_ITEM = '".$filtro."'")->queryAll();
        return $resp;

 	}

 	public function Desc_Item($Item){

 		$resp = Yii::app()->db->createCommand("
		    SELECT CONCAT(I_ID_ITEM,' - ',I_DESCRIPCION,' - ',I_REFERENCIA) AS ITEM FROM T_CF_ITEMS WHERE I_ID_ITEM = '".$Item."'")->queryRow();
        return $resp['ITEM'];

    }

    public function Desc_Plan($Plan){

 		$resp = Yii::app()->db->createCommand("SELECT Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = '".$Plan."'")->queryRow();
        return $resp['Plan_Descripcion'];

    }

    public function Desc_Criterio($Criterio){

 		$resp = Yii::app()->db->createCommand("SELECT Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Criterio = '".$Criterio."'")->queryRow();
        return $resp['Criterio_Descripcion'];

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
			'acelerador' => array(self::BELONGS_TO, 'Dominio', 'ID_ACELERADOR'),
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
			'ID_ACELERADOR' => 'Acelerador',
			'ITEM' => 'Item',
			'ID_PLAN' => 'Plan',
			'CRITERIO' => 'Criterio',
			'PORCENTAJE' => 'Porcentaje',
			'FECHA_INICIAL' => 'Fecha inicial',
			'FECHA_FINAL' => 'Fecha final',
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
		$criteria->compare('t.ID_ACELERADOR',$this->ID_ACELERADOR);
		$criteria->compare('t.ITEM',$this->ITEM);
		$criteria->compare('t.ID_PLAN',$this->ID_PLAN);
		$criteria->compare('t.CRITERIO',$this->CRITERIO,true);
		$criteria->compare('t.PORCENTAJE',$this->PORCENTAJE,true);
		$criteria->compare('t.FECHA_INICIAL',$this->FECHA_INICIAL,true);
		$criteria->compare('t.FECHA_FINAL',$this->FECHA_FINAL,true);
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
	 * @return CAceleradorCms the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

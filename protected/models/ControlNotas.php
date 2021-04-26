<?php

/**
 * This is the model class for table "T_PR_CONTROL_NOTAS".
 *
 * The followings are the available columns in table 'T_PR_CONTROL_NOTAS':
 * @property integer $Id_Control
 * @property integer $Id_Cliente
 * @property string $Nota
 * @property string $Factura
 * @property integer $Valor_Factura
 * @property string $Porc_Desc
 * @property integer $Valor_Descuento
 * @property string $Fecha_Factura
 * @property string $Fecha_Pago
 * @property integer $Dias_Pago
 * @property string $Recibo
 * @property string $Observaciones
 * @property integer $Respuesta
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THUSUARIOS $idUsuarioCreacion
 * @property THUSUARIOS $idUsuarioActualizacion
 */
class ControlNotas extends CActiveRecord
{
	
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_CONTROL_NOTAS';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Cliente, Factura, Valor_Factura, Porc_Desc, Valor_Descuento, Fecha_Factura, Fecha_Pago, Dias_Pago, Recibo, Observaciones, Respuesta', 'required'),
			array('Id_Cliente, Dias_Pago, Respuesta, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Nota, Factura, Recibo', 'length', 'max'=>50),
			array('Valor_Factura, Valor_Descuento', 'length', 'max'=>19),
			//array('Porc_Desc', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Control, Id_Cliente, Nota, Factura, Valor_Factura, Porc_Desc, Valor_Descuento, Fecha_Factura, Fecha_Pago, Dias_Pago, Recibo, Observaciones, Respuesta, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Fecha_Creacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function searchByCliente($filtro) {
        
        $resp = Yii::app()->db->createCommand("
			SELECT TOP 10 C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND (C_NIT_CLIENTE LIKE '".$filtro."%' OR C_NOMBRE_CLIENTE LIKE '".$filtro."%') GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
		")->queryAll();
        return $resp;
        
 	}

 	public function searchById($filtro) {
        
        $resp = Yii::app()->db->createCommand("
			SELECT C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_ROWID_CLIENTE = '".$filtro."' GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
		")->queryAll();
        return $resp;
        
 	}

 	public function Desc_Cliente($Id_Cliente){

        $cliente = Yii::app()->db->createCommand("
			SELECT C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND C_ROWID_CLIENTE = '".$Id_Cliente."' GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
		")->queryRow();

        return $cliente['C_NIT_CLIENTE'].' - '.$cliente['C_NOMBRE_CLIENTE'];

    }

    public function Desc_Respuesta($res){

        switch ($res) {
			case 0:
			    return 'EN ELAB.'; 
			    break;
			case 1:
			    return 'APROBADO';  
			    break;
			case 2:
			    return 'NO APROBADO'; 
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
			'Id_Control' => 'Id',
			'Id_Cliente' => 'Cliente',
			'Nota' => 'Nota',
			'Factura' => 'Factura',
			'Valor_Factura' => 'Vlr. factura',
			'Porc_Desc' => '% descuento',
			'Valor_Descuento' => 'Vlr. descuento',
			'Fecha_Factura' => 'Fecha Factura',
			'Fecha_Pago' => 'Fecha de pago',
			'Dias_Pago' => 'Días de pago',
			'Recibo' => 'Recibo',
			'Observaciones' => 'Observaciones',
			'Respuesta' => 'Respuesta',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			
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

		//$criteria->compare('Id_Control',$this->Id_Control);
		$criteria->compare('t.Id_Cliente',$this->Id_Cliente);
		$criteria->compare('t.Nota',$this->Nota,true);
		$criteria->compare('t.Factura',$this->Factura,true);
		//$criteria->compare('t.Valor_Factura',$this->Valor_Factura,true);
		//$criteria->compare('t.Porc_Desc',$this->Porc_Desc,true);
		//$criteria->compare('t.Valor_Descuento',$this->Valor_Descuento,true);
		$criteria->compare('t.Fecha_Factura',$this->Fecha_Factura,true);
		$criteria->compare('t.Fecha_Pago',$this->Fecha_Pago,true);
		$criteria->compare('t.Dias_Pago',$this->Dias_Pago);
		$criteria->compare('t.Recibo',$this->Recibo,true);
		//$criteria->compare('t.Observaciones',$this->Observaciones,true);
		$criteria->compare('t.Respuesta',$this->Respuesta);

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);

		$criteria->order = 't.Fecha_Actualizacion ASC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));	
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ControlNotas the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

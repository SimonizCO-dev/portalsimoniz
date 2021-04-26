<?php

/**
 * This is the model class for table "TH_WIP_PROMOS".
 *
 * The followings are the available columns in table 'TH_WIP_PROMOS':
 * @property integer $ID
 * @property integer $CONSECUTIVO
 * @property integer $ID_ITEM
 * @property string $DESCRIPCION
 * @property string $ESTADO_OP
 * @property integer $INVENTARIO_TOTAL
 * @property integer $DE_0_A_30_DIAS
 * @property integer $DE_31_A_60_DIAS
 * @property integer $DE_61_A_90_DIAS
 * @property integer $MAS_DE_90_DIAS
 * @property string $WIP
 * @property string $FECHA_SOLICITUD_WIP
 * @property string $FECHA_ENTREGA_WIP
 * @property integer $CANT_A_ARMAR
 * @property integer $CANT_OC_AL_DIA
 * @property integer $CANT_PENDIENTE
 * @property string $CADENA
 * @property string $RESPONSABLE
 * @property integer $DIAS_VENCIMIENTO
 * @property string $REDISTRIBUCION
 * @property string $ESTADO_COMERCIAL
 * @property string $UN
 * @property string $SUB_MARCA
 * @property string $FAMILIA
 * @property string $SUB_FAMILIA
 * @property string $GRUPO
 * @property string $ORACLE
 * @property string $PTM
 * @property integer $ID_USUARIO_CREACION
 * @property integer $ID_USUARIO_ACTUALIZACION
 * @property string $FECHA_CREACION
 * @property string $FECHA_ACTUALIZACION
 * @property integer $CANT_VEND
 * @property string $FECHA_CUMPLIDO
 * @property string $OBSERVACIONES
 *
 * The followings are the available model relations:
 * @property THCLIENTES $cADENA
 * @property THUSUARIOS $iDUSUARIOCREACION
 * @property THUSUARIOS $iDUSUARIOACTUALIZACION
 */
class Wip extends CActiveRecord
{
	
	public $cad_item;
	public $cad_cant;
	public $firma;
	public $cargo;
	public $correos_notif;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_WIP';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('CONSECUTIVO, ID_ITEM, ESTADO_OP, INVENTARIO_TOTAL, DE_0_A_30_DIAS, DE_31_A_60_DIAS, DE_61_A_90_DIAS, MAS_DE_90_DIAS, CANT_A_ARMAR, CANT_OC_AL_DIA, CANT_PENDIENTE, DIAS_VENCIMIENTO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION', 'required'),
			array('RESPONSABLE, CANT_A_ARMAR', 'required', 'on' => 'update'),
			array('firma, cargo, correos_notif', 'required', 'on' => 'notif'),
			array('ID_ITEM, INVENTARIO_TOTAL, DE_0_A_30_DIAS, DE_31_A_60_DIAS, DE_61_A_90_DIAS, MAS_DE_90_DIAS, CANT_A_ARMAR, CANT_OC_AL_DIA, CANT_PENDIENTE, DIAS_VENCIMIENTO, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION, CANT_VEND', 'numerical', 'integerOnly'=>true),
			array('DESCRIPCION, WIP, CADENA, ESTADO_OP, ESTADO_COMERCIAL, RESPONSABLE, REDISTRIBUCION, UN, SUB_MARCA, FAMILIA, SUB_FAMILIA, GRUPO, ORACLE, PTM', 'length', 'max'=>200),
			array('OBSERVACIONES', 'length', 'max'=>50),
			array('CONSECUTIVO', 'length', 'max'=>7),
			array('FECHA_SOLICITUD_WIP, FECHA_ENTREGA_WIP, FECHA_CREACION, FECHA_ACTUALIZACION, FECHA_CUMPLIDO, OBSERVACIONES', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ID, CONSECUTIVO, ID_ITEM, DESCRIPCION, ESTADO_OP, INVENTARIO_TOTAL, DE_0_A_30_DIAS, DE_31_A_60_DIAS, DE_61_A_90_DIAS, MAS_DE_90_DIAS, WIP, FECHA_SOLICITUD_WIP, FECHA_ENTREGA_WIP, CANT_A_ARMAR, CANT_OC_AL_DIA, CANT_PENDIENTE, CADENA, RESPONSABLE, DIAS_VENCIMIENTO, REDISTRIBUCION, ESTADO_COMERCIAL, UN, SUB_MARCA, FAMILIA, SUB_FAMILIA, GRUPO, ORACLE, PTM, ID_USUARIO_CREACION, ID_USUARIO_ACTUALIZACION, FECHA_CREACION, FECHA_ACTUALIZACION, OBSERVACIONES', 'safe', 'on'=>'search'),
		);
	}

	public function vbtnview($fecha_cumplido){

       	if(is_null($fecha_cumplido)){
       		return false;
      	}else{
       		return true;
       	}

    }

    public function vbtnupdate($fecha_cumplido){

        if(is_null($fecha_cumplido)){
       		return true;
       	}else{
       		return false;
       	}

    }

    public function desccadena($id_Wip){

    	$model = Wip::model()->findByPk($id_Wip);

    	if($model->CADENA != ""){
    		return $model->CADENA;
    	}else{
    		return $model->OBSERVACIONES;	
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
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_CREACION'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'ID_USUARIO_ACTUALIZACION'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'CONSECUTIVO' => 'Consecutivo',
			'ID_ITEM' => 'Item',
			'DESCRIPCION' => 'Descripción',
			'ESTADO_OP' => 'Estado OP',
			'INVENTARIO_TOTAL' => 'Inventario total',
			'DE_0_A_30_DIAS' => 'De 0 a 30 días',
			'DE_31_A_60_DIAS' => 'De 31 a 60 días',
			'DE_61_A_90_DIAS' => 'De 61 a 90 días',
			'MAS_DE_90_DIAS' => 'Mas de 90 días',
			'WIP' => 'WIP',
			'FECHA_SOLICITUD_WIP' => 'Fecha de solicitud',
			'FECHA_ENTREGA_WIP' => 'Fecha de entrega',
			'CANT_A_ARMAR' => 'Cantidad',
			'CANT_OC_AL_DIA' => 'Cant. orden prod.',
			'CANT_PENDIENTE' => 'Cant. sin entregar',
			'CADENA' => 'Cadena',
			'RESPONSABLE' => 'Responsable',
			'DIAS_VENCIMIENTO' => 'Días de vencimiento',
			'REDISTRIBUCION' => 'Cadena a prestar',
			'ESTADO_COMERCIAL' => 'Estado comercial',
			'UN' => 'UN',
			'SUB_MARCA' => 'Sub-marca',
			'FAMILIA' => 'Familia',
			'SUB_FAMILIA' => 'Sub-familia',
			'GRUPO' => 'Grupo',
			'ORACLE' => 'Oracle',
			'PTM' => 'Cant. de prestamo',
			'ID_USUARIO_CREACION' => 'Usuario que creo',
			'ID_USUARIO_ACTUALIZACION' => 'Usuario que actualizó',
			'FECHA_CREACION' => 'Fecha de creación',
			'FECHA_ACTUALIZACION' => 'Fecha de actualización',
			'CANT_VEND' => 'Cant. vend.',
			'FECHA_CUMPLIDO' => 'Fecha cumplido',
			'OBSERVACIONES' => 'Observaciones',
			'firma' => 'Firma',
			'cargo' => 'Cargo',
			'correos_notif' => 'E-mail(s) para envió',
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

		$criteria->compare('t.ID',$this->ID);
		$criteria->compare('t.CONSECUTIVO',$this->CONSECUTIVO);
		$criteria->compare('t.ID_ITEM',$this->ID_ITEM);
		$criteria->compare('t.DESCRIPCION',$this->DESCRIPCION,true);
		$criteria->compare('t.ESTADO_OP',$this->ESTADO_OP);
		$criteria->compare('t.INVENTARIO_TOTAL',$this->INVENTARIO_TOTAL);
		$criteria->compare('t.DE_0_A_30_DIAS',$this->DE_0_A_30_DIAS);
		$criteria->compare('t.DE_31_A_60_DIAS',$this->DE_31_A_60_DIAS);
		$criteria->compare('t.DE_61_A_90_DIAS',$this->DE_61_A_90_DIAS);
		$criteria->compare('t.MAS_DE_90_DIAS',$this->MAS_DE_90_DIAS);
		$criteria->compare('t.WIP',$this->WIP,true);
		$criteria->compare('t.FECHA_SOLICITUD_WIP',$this->FECHA_SOLICITUD_WIP,true);
		$criteria->compare('t.FECHA_ENTREGA_WIP',$this->FECHA_ENTREGA_WIP,true);
		$criteria->compare('t.CANT_A_ARMAR',$this->CANT_A_ARMAR);
		$criteria->compare('t.CANT_OC_AL_DIA',$this->CANT_OC_AL_DIA);
		$criteria->compare('t.CANT_PENDIENTE',$this->CANT_PENDIENTE);
		$criteria->compare('t.CADENA',$this->CADENA,true);
		$criteria->compare('t.RESPONSABLE',$this->RESPONSABLE,true);
		$criteria->compare('t.DIAS_VENCIMIENTO',$this->DIAS_VENCIMIENTO);
		$criteria->compare('t.REDISTRIBUCION',$this->REDISTRIBUCION,true);
		$criteria->compare('t.ESTADO_COMERCIAL',$this->ESTADO_COMERCIAL);
		$criteria->compare('t.UN',$this->UN,true);
		$criteria->compare('t.SUB_MARCA',$this->SUB_MARCA,true);
		$criteria->compare('t.FAMILIA',$this->FAMILIA,true);
		$criteria->compare('t.SUB_FAMILIA',$this->SUB_FAMILIA,true);
		$criteria->compare('t.GRUPO',$this->GRUPO,true);
		$criteria->compare('t.ORACLE',$this->ORACLE,true);
		$criteria->compare('t.PTM',$this->PTM,true);
		$criteria->compare('t.ID_USUARIO_CREACION',$this->ID_USUARIO_CREACION);
		$criteria->compare('t.ID_USUARIO_ACTUALIZACION',$this->ID_USUARIO_ACTUALIZACION);
		$criteria->compare('t.FECHA_CREACION',$this->FECHA_CREACION,true);
		$criteria->compare('t.FECHA_ACTUALIZACION',$this->FECHA_ACTUALIZACION,true);
		$criteria->compare('t.CANT_VEND',$this->CANT_VEND);
		$criteria->compare('t.FECHA_CUMPLIDO',$this->FECHA_CUMPLIDO,true);
		$criteria->compare('t.OBSERVACIONES',$this->OBSERVACIONES,true);
		$criteria->order = 't.WIP DESC, t.ID ASC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchitemswip()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		
		$criteria->compare('t.WIP',$this->WIP,true);
		$criteria->order = 't.WIP DESC, t.ID ASC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>50)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wip the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

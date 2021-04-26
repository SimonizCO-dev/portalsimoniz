<?php

/**
 * This is the model class for table "T_PR_EQUIPO".
 *
 * The followings are the available columns in table 'T_PR_EQUIPO':
 * @property integer $Id_Equipo
 * @property integer $Tipo_Equipo
 * @property string $Serial
 * @property integer $Empresa_Compra
 * @property string $Fecha_Compra
 * @property integer $Proveedor
 * @property string $Numero_Factura
 * @property string $Numero_Inventario
 * @property string $Doc_Soporte
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property string $Modelo
 * @property string $MAC1
 * @property string $MAC2
 *
 * The followings are the available model relations:
 * @property THDOMINIO $tipoEquipo
 * @property THEMPRESA $empresaCompra
 * @property THPROVEEDOR $proveedor
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class Equipo extends CActiveRecord
{

	public $orderby;
	public $sop;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_EQUIPO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Tipo_Equipo, Serial, Modelo, MAC1, Empresa_Compra, Fecha_Compra, Proveedor, Numero_Factura, Numero_Inventario, Estado', 'required'),
			array('Serial, MAC1, MAC2','unique','on'=>'create'),
			array('Serial', 'uniqueSerial','on'=>'update'),
			array('Tipo_Equipo, Empresa_Compra, Proveedor, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Serial, Doc_Soporte, Modelo', 'length', 'max'=>200),
			array('Numero_Factura, Numero_Inventario', 'length', 'max'=>50),
			array('MAC1, MAC2', 'length', 'max'=>17),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Equipo, Tipo_Equipo, Serial, Empresa_Compra, Fecha_Compra, Proveedor, Numero_Factura, Numero_Inventario, Doc_Soporte, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'safe', 'on'=>'search'),
		);
	}

	public function uniqueSerial($attribute,$params){
        
  		//se busca el mismo nombre de usuario con id diferente al registro afectado
        $criteria=new CDbCriteria;
		$criteria->condition='Serial=:Serial AND Id_Equipo!=:Id_Equipo';
		$criteria->params=array(':Serial'=>$this->Serial,':Id_Equipo'=>$this->Id_Equipo);
		$modeloequipo=Equipo::model()->find($criteria);

        if(!is_null($modeloequipo)){
        	$this->addError($attribute, 'Este serial ya esta registrado.');
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
			'tipoequipo' => array(self::BELONGS_TO, 'Dominio', 'Tipo_Equipo'),
			'empresacompra' => array(self::BELONGS_TO, 'PaEmpresa', 'Empresa_Compra'),
			'proveedor' => array(self::BELONGS_TO, 'PaProveedor', 'Proveedor'),
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
			'Id_Equipo' => 'ID',
			'Tipo_Equipo' => 'Tipo',
			'Serial' => 'Serial',
			'Empresa_Compra' => 'Empresa que compro',
			'Fecha_Compra' => 'Fecha de compra',
			'Proveedor' => 'Proveedor',
			'Numero_Factura' => 'N° de factura',
			'Numero_Inventario' => 'N° de Inventario',
			'Doc_Soporte' => 'Soporte',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'orderby' => 'Orden de resultados',
			'sop' => 'Soporte',
			'Modelo' => 'Modelo',
			'MAC1' => 'MAC LAN',
			'MAC2' => 'MAC WAN',
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
		$criteria->with=array('tipoequipo', 'empresacompra', 'proveedor');

		if($this->Id_Equipo != ""){
			$criteria->AddCondition("t.Id_Equipo = '".$this->Id_Equipo."'"); 
	    }

	    if($this->Tipo_Equipo != ""){
			$criteria->AddCondition("t.Tipo_Equipo = '".$this->Tipo_Equipo."'"); 
	    }

	    $criteria->compare('t.Serial',$this->Serial,true);
	    $criteria->compare('t.Modelo',$this->Modelo,true);
	    $criteria->compare('t.Numero_Factura',$this->Numero_Factura,true);

	    if($this->Empresa_Compra != ""){
			$criteria->AddCondition("t.Empresa_Compra = '".$this->Empresa_Compra."'"); 
	    }

	    if($this->Fecha_Compra != ""){
      		$fai = $this->Fecha_Compra." 00:00:00";
      		$faf = $this->Fecha_Compra." 23:59:59";

      		$criteria->addBetweenCondition('t.Fecha_Compra', $fai, $faf);
    	}

    	if($this->Proveedor != ""){
			$criteria->AddCondition("t.Proveedor = '".$this->Proveedor."'"); 
	    }

	    $criteria->compare('t.Estado',$this->Estado);

	    if(empty($this->orderby)){
			$criteria->order = 'empresacompra.Descripcion ASC, t.Fecha_Compra DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Equipo ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Equipo DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'tipoequipo.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'tipoequipo.Dominio DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Serial ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Serial DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Modelo ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Modelo DESC'; 
			        break;
			    case 9:
			        $criteria->order = 'empresacompra.Descripcion DESC'; 
			        break;
			    case 10:
			        $criteria->order = 'empresacompra.Descripcion ASC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Fecha_Compra ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Fecha_Compra DESC'; 
			        break;
			    case 13:
			        $criteria->order = 'proveedor.Proveedor ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'proveedor.Proveedor DESC'; 
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
	 * @return Equipo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

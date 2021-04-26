<?php

/**
 * This is the model class for table "T_PR_LICENCIA".
 *
 * The followings are the available columns in table 'T_PR_LICENCIA':
 * @property integer $Id_Lic
 * @property integer $Clasificacion
 * @property integer $Tipo
 * @property integer $Version
 * @property integer $Producto
 * @property string $Id_Licencia
 * @property string $Num_Licencia
 * @property string $Cuenta_Registro
 * @property string $Link
 * @property string $Password
 * @property string $Token
 * @property integer $Cant_Usuarios
 * @property integer $Ubicacion
 * @property integer $Empresa_Compra
 * @property integer $Proveedor
 * @property string $Numero_Inventario
 * @property string $Numero_Factura
 * @property string $Fecha_Factura
 * @property integer $Valor_Comercial
 * @property string $Fecha_Inicio
 * @property string $Fecha_Final
 * @property string $Fecha_Inicio_Sop
 * @property string $Fecha_Final_Sop
 * @property string $Doc_Soporte
 * @property string $Doc_Soporte2
 * @property string $Notas
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 *
 * The followings are the available model relations:
 * @property THLICENCIAEQUIPO[] $tHLICENCIAEQUIPOs
 * @property THDOMINIO $clasificacion
 * @property THDOMINIO $tipo
 * @property THDOMINIO $version
 * @property THPAEMPRESA $empresaCompra
 * @property THPAPROVEEDOR $proveedor
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THDOMINIO $producto
 * @property THDOMINIO $ubicacion
 */
class Licencia extends CActiveRecord
{
	
	public $sop;
	public $sop2;
	public $cant_usuarios_disp;
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_LICENCIA';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Clasificacion, Tipo, Version, Num_Licencia, Cant_Usuarios, Ubicacion, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion', 'required'),
			array('Clasificacion, Tipo, Version, Producto, Cant_Usuarios, Ubicacion, Empresa_Compra, Proveedor, Valor_Comercial, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Id_Licencia, Num_Licencia, Password, Token, Numero_Inventario, Numero_Factura', 'length', 'max'=>200),
			array('Doc_Soporte, Doc_Soporte2', 'length', 'max'=>300),
			array('Cuenta_Registro', 'length', 'max'=>100),
			array('Fecha_Factura, Fecha_Inicio, Fecha_Final, Fecha_Inicio_Sop, Fecha_Final_Sop, Notas, Link', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Lic, Clasificacion, Tipo, Version, Producto, Id_Licencia, Num_Licencia, Cuenta_Registro, Link, Password, Token, Cant_Usuarios, Ubicacion, Empresa_Compra, Proveedor, Numero_Inventario, Numero_Factura, Fecha_Factura, Valor_Comercial, Fecha_Inicio, Fecha_Final, Fecha_Inicio_Sop, Fecha_Final_Sop, Doc_Soporte, Doc_Soporte2, Notas, Estado, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function CantUsuariosRest($Id_Lic) {

		$usuarios_x_lic= Licencia::model()->findByPk($Id_Lic)->Cant_Usuarios;	
		$modelo_usuarios_x_lic_act = LicenciaEquipo::model()->findAllByAttributes(array('Id_Licencia' => $Id_Lic, 'Estado' => 1));		
		
		$usuarios_x_lic_act = count($modelo_usuarios_x_lic_act);

		$usuarios_rest = $usuarios_x_lic - $usuarios_x_lic_act;

		return $usuarios_rest;

 	}

 	public function DescLicencia($Id_Lic) {

		$modelo_licencia = Licencia::model()->findByPk($Id_Lic);

		if($modelo_licencia->Producto == ""){
			$desc_licencia = $modelo_licencia->clasificacion->Dominio.' '.$modelo_licencia->version->Dominio.' - '.$modelo_licencia->tipo->Dominio.' / '.$modelo_licencia->Num_Licencia;
		}else{
			$desc_licencia = $modelo_licencia->clasificacion->Dominio.' '.$modelo_licencia->version->Dominio.' '.$modelo_licencia->producto->Dominio.' - '.$modelo_licencia->tipo->Dominio.' / '.$modelo_licencia->Num_Licencia;
		}

		return $desc_licencia;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'tHLICENCIAEQUIPOs' => array(self::HAS_MANY, 'THLICENCIAEQUIPO', 'Id_Licencia'),
			'clasificacion' => array(self::BELONGS_TO, 'Dominio', 'Clasificacion'),
			'tipo' => array(self::BELONGS_TO, 'Dominio', 'Tipo'),
			'version' => array(self::BELONGS_TO, 'Dominio', 'Version'),
			'producto' => array(self::BELONGS_TO, 'Dominio', 'Producto'),
			'ubicacion' => array(self::BELONGS_TO, 'Dominio', 'Ubicacion'),
			'estado' => array(self::BELONGS_TO, 'Dominio', 'Estado'),
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
			'Id_Lic' => 'ID',
			'Clasificacion' => 'Clasif.',
			'Tipo' => 'Tipo',
			'Version' => 'Versión',
			'Producto' => 'Producto',
			'Id_Licencia' => 'ID licencia',
			'Num_Licencia' => 'N° de licencia',
			'Cuenta_Registro' => 'Cuenta de registro',
			'Link' => 'Link',
			'Password' => 'Password',
			'Token' => 'Token',
			'Cant_Usuarios' => 'Usuarios x lic.',
			'Ubicacion' => 'Ubicación',
			'Empresa_Compra' => 'Empresa que compro',
			'Proveedor' => 'Proveedor',
			'Numero_Inventario' => 'N° de Inventario',
			'Numero_Factura' => 'N° de factura',
			'Fecha_Factura' => 'Fecha de factura',
			'Valor_Comercial' => 'Vlr. comercial',
			'Fecha_Inicio' => 'Fecha de inicio',
			'Fecha_Final' => 'Fecha de fin.',
			'Fecha_Inicio_Sop' => 'Fecha de inicio sop.',
			'Fecha_Final_Sop' => 'Fecha de fin. sop.',
			'Doc_Soporte' => 'Soporte',
			'Doc_Soporte2' => 'Soporte 2',
			'Notas' => 'Notas',
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Fecha_Creacion' => 'Fecha de creación',
			'Id_Usuario_Actualizacion' => 'Usuario que actualizó',
			'Fecha_Actualizacion' => 'Fecha de actualización',
			'sop' => 'Soporte',
			'sop2' => 'Soporte 2',
			'cant_usuarios_disp' => 'Usuarios x lic. disp.',
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
		$criteria->with=array('clasificacion', 'tipo', 'version', 'producto', 'ubicacion', 'estado', 'empresacompra');

		if($this->Clasificacion != ""){
			$criteria->AddCondition("t.Clasificacion = '".$this->Clasificacion."'"); 
	    }

	    if($this->Tipo != ""){
			$criteria->AddCondition("t.Tipo = '".$this->Tipo."'"); 
	    }

	    if($this->Version != ""){
			$criteria->AddCondition("t.Version = '".$this->Version."'"); 
	    }

	    if($this->Producto != ""){
			$criteria->AddCondition("t.Producto = '".$this->Producto."'"); 
	    }

	    if($this->Ubicacion != ""){
			$criteria->AddCondition("t.Ubicacion = '".$this->Ubicacion."'"); 
	    }

	    if($this->Empresa_Compra != ""){
			$criteria->AddCondition("t.Empresa_Compra = ".$this->Empresa_Compra); 
	    }

	    if($this->Estado != ""){
			$criteria->AddCondition("t.Estado = '".$this->Estado."'"); 
	    }

		$criteria->compare('t.Id_Lic',$this->Id_Lic);
		$criteria->compare('t.Num_Licencia',$this->Num_Licencia,true);
		$criteria->compare('t.Numero_Factura',$this->Numero_Factura,true);

		if(empty($this->orderby)){
			$criteria->order = 'clasificacion.Dominio ASC, tipo.Dominio ASC, version.Dominio ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Lic ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Lic DESC'; 
			        break;
	case 3:
			        $criteria->order = 'clasificacion.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'clasificacion.Dominio DESC'; 
			        break;
			    case 5:
			        $criteria->order = 'tipo.Dominio ASC'; 
			        break;
			    case 6:
			        $criteria->order = 'tipo.Dominio DESC'; 
			        break;
			    case 7:
			        $criteria->order = 'version.Dominio ASC'; 
			        break;
			    case 8:
			        $criteria->order = 'version.Dominio DESC'; 
			        break;
			    case 9:
			        $criteria->order = 'producto.Dominio ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'producto.Dominio DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Num_Licencia ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Num_Licencia DESC'; 
			        break;
			   	case 13:
			        $criteria->order = 'empresacompra.Descripcion ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'empresacompra.Descripcion DESC'; 
			        break;
			    case 15:
			        $criteria->order = 'ubicacion.Dominio ASC'; 
			        break;
			    case 16:
			        $criteria->order = 'ubicacion.Dominio DESC'; 
			        break;
			    case 17:
			        $criteria->order = 'estado.Dominio ASC'; 
			        break;
			    case 18:
			        $criteria->order = 'estado.Dominio DESC'; 
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
	 * @return Licencia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

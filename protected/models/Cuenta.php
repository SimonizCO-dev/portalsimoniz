<?php

/**
 * This is the model class for table "T_PR_CUENTA".
 *
 * The followings are the available columns in table 'T_PR_CUENTA':
 * @property integer $Id_Cuenta
 * @property integer $Clasificacion
 * @property integer $Tipo_Cuenta
 * @property integer $Tipo_Acceso
 * @property string $Cuenta_Usuario
 * @property string $Password
 * @property integer $Dominio
 * @property string $Observaciones
 * @property integer $Id_Cuenta_Red
 * @property integer $Estado
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Ext
 *
 * The followings are the available model relations:
 * @property THDOMINIO $clasificacion
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 */
class Cuenta extends CActiveRecord
{
	public $num_cuentas_red;
	public $num_usuarios_asoc;	
	public $orderby;	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_CUENTA';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Password, Id_Cuenta_Red', 'required', 'on' => 'actred'),
			array('Clasificacion, Tipo_Cuenta, Tipo_Acceso, Dominio, Estado, Id_Usuario_Creacion, Id_Usuario_Actualizacion', 'numerical', 'integerOnly'=>true),
			array('Cuenta_Usuario, Password', 'length', 'max'=>30),
			array('Ext', 'length', 'max'=>10),
			array('Observaciones', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Cuenta, Clasificacion, Tipo_Cuenta, Tipo_Acceso, Cuenta_Usuario, Password, Dominio, Estado, Fecha_Creacion, Fecha_Actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function DescTipoAcceso($Tipo_Acceso) {

		if($Tipo_Acceso == 1){
			return 'GENÉRICO';
		}

		if($Tipo_Acceso == 2){
			return 'PERSONAL';
		}

 	}

 	public function DescCuentaUsuario($Id_Cuenta) {

		$modelo_cuenta = Cuenta::model()->findByPk($Id_Cuenta);

		if($modelo_cuenta->Clasificacion == Yii::app()->params->c_correo){
			return $modelo_cuenta->Cuenta_Usuario.'@'.$modelo_cuenta->dominioweb->Dominio;
		}else{
			return $modelo_cuenta->Cuenta_Usuario;
		}
 	}

 	public function NumCuentasRed($Id_Cuenta) {

		$modelo_cuenta = Cuenta::model()->findByPk($Id_Cuenta);

		if($modelo_cuenta->Clasificacion == Yii::app()->params->c_correo){

			$modelo_cuenta_red = Cuenta::model()->findAllByAttributes(array('Id_Cuenta_Red' => $Id_Cuenta));
			
			return count($modelo_cuenta_red);

		}else{
			return '-';
		}

 	}

 	public function NumUsuariosAsoc($Id_Cuenta) {

		$modelo_cuenta_emp = CuentaEmpleado::model()->findAllByAttributes(array('Id_Cuenta' => $Id_Cuenta, 'Estado' => 1));

		return count($modelo_cuenta_emp);

 	}

 	public function searchByCuentaRed($id, $filtro) {
       
        $resp = Yii::app()->db->createCommand("
		    SELECT 
		    TOP 10 
		    C.Id_Cuenta, 
		    CONCAT (C.Cuenta_Usuario, '@', D.Dominio) AS Cuenta
		    FROM T_PR_CUENTA C
		    INNER JOIN T_PR_DOMINIO_WEB D ON C.Dominio = D.Id_Dominio_Web
		    WHERE C.Id_Cuenta != ".$id." AND C.Clasificacion = ".Yii::app()->params->c_correo." AND C.Estado = ".Yii::app()->params->estado_act." AND (C.Cuenta_Usuario LIKE '%".$filtro."%' OR  C.Dominio LIKE '%".$filtro."%') ORDER BY Cuenta

		")->queryAll();
        return $resp;
        
 	}

	/**
	 * @return array relational rules
	 .
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'clasificacion' => array(self::BELONGS_TO, 'Dominio', 'Clasificacion'),
			'tipocuenta' => array(self::BELONGS_TO, 'Dominio', 'Tipo_Cuenta'),
			'dominioweb' => array(self::BELONGS_TO, 'DominioWeb', 'Dominio'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idcuentared' => array(self::BELONGS_TO, 'Cuenta', 'Id_Cuenta_Red'),
			'estado' => array(self::BELONGS_TO, 'Dominio', 'Estado'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Cuenta' => 'ID',
			'Clasificacion' => 'Clasif.',
			'Tipo_Cuenta' => 'Tipo de cuenta',
			'Tipo_Acceso' => 'Tipo de acceso',
			'Cuenta_Usuario' => 'Cuenta / Usuario',
			'Password' => 'Password',
			'Dominio' => 'Dominio',
			'Observaciones' => 'Observaciones',
			'Id_Cuenta_Red' => 'Cuenta red.',
			'Estado' => 'Estado',
			'num_cuentas_red' => '# de cuentas red.',
			'num_usuarios_asoc' => '# de usuarios asoc.',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'orderby' => 'Orden de resultados',
			'Ext' => 'Ext.',
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
	   	$criteria->with=array('clasificacion','tipocuenta','dominioweb','idusuariocre','idusuarioact','estado');

		$criteria->compare('t.Id_Cuenta',$this->Id_Cuenta);
		$criteria->compare('t.Clasificacion',$this->Clasificacion);
		$criteria->compare('t.Tipo_Cuenta',$this->Tipo_Cuenta);
		$criteria->compare('t.Tipo_Acceso',$this->Tipo_Acceso);

		if($this->Cuenta_Usuario != ""){

			$criteria->AddCondition("t.Cuenta_Usuario LIKE '%".$this->Cuenta_Usuario."%'"); 
	    
	    }

		$criteria->compare('t.Password',$this->Password,true);
		$criteria->compare('t.Dominio',$this->Dominio);
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
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    $id_user = Yii::app()->user->getState('id_user');
	    $user_cuentas_esp = Yii::app()->params->usuarios_cuentas_esp;
	    $cuentas_esp = implode(",", Yii::app()->params->cuentas_esp);

	    if(!in_array($id_user, $user_cuentas_esp)){
	    	$criteria->AddCondition("t.Id_Cuenta NOT IN (".$cuentas_esp.")"); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Cuenta DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Cuenta ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Cuenta DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'clasificacion.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'clasificacion.Dominio DESC'; 
			        break; 
			    case 5:
			        $criteria->order = 't.Cuenta_Usuario ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Cuenta_Usuario DESC'; 
			        break;
			    case 7:
			        $criteria->order = 'dominioweb.Dominio ASC'; 
			        break;
			    case 8:
			        $criteria->order = 'dominioweb.Dominio DESC'; 
			        break; 
		        case 9:
			        $criteria->order = 'tipocuenta.Dominio ASC'; 
			        break;
			    case 10:
			        $criteria->order = 'tipocuenta.Dominio DESC'; 
			        break;
			    case 11:
			        $criteria->order = 't.Tipo_Acceso ASC'; 
			        break;
			    case 12:
			        $criteria->order = 't.Tipo_Acceso DESC'; 
			        break; 
		        case 13:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 14:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 15:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 16:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 17:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 18:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 19:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 20:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 21:
			        $criteria->order = 'estado.Dominio ASC'; 
			        break;
			    case 22:
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
	 * @return Cuenta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

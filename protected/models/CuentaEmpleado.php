<?php

/**
 * This is the model class for table "T_PR_CUENTA_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_CUENTA_EMPLEADO':
 * @property integer $Id_Cuenta_Emp
 * @property integer $Id_Empleado
 * @property integer $Id_Contrato
 * @property integer $Id_Cuenta
 * @property integer $Id_Usuario_Creacion
 * @property string $Fecha_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Fecha_Actualizacion
 * @property integer $Estado
 *
 * The followings are the available model relations:
 * @property THEMPLEADO $idEmpleado
 * @property THCONTRATOEMPLEADO $idContrato
 * @property THUSUARIO $idUsuarioCreacion
 * @property THUSUARIO $idUsuarioActualizacion
 * @property THCUENTA $idCuenta
 */
class CuentaEmpleado extends CActiveRecord
{
	
	public $clasificacion;
	public $orderby;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_CUENTA_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id_Empleado', 'required', 'on' => 'create'),
			array('Id_Empleado, Id_Contrato, Id_Cuenta, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Estado', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			array('clasificacion, orderby', 'safe'),
			// @todo Please remove those attributes that should not be searched.
			array('Id_Cuenta_Emp, Id_Empleado, Id_Contrato, Id_Cuenta, Id_Usuario_Creacion, Fecha_Creacion, Id_Usuario_Actualizacion, Fecha_Actualizacion, Estado, clasificacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function searchByEmpleadoAsocCuenta($filtro, $id_cuenta) {
 
 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10 
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado
			FROM T_PR_CONTRATO_EMPLEADO CE
			INNER JOIN T_PR_EMPLEADO E ON CE.Id_Empleado = E.Id_Empleado
			INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE CE.Id_M_Retiro IS NULL
			AND (E.Identificacion LIKE '%".$filtro."%' OR E.Nombre LIKE '%".$filtro."%' OR E.Apellido LIKE '%".$filtro."%')
			AND E.Id_Empleado NOT IN (SELECT Id_Empleado FROM T_PR_CUENTA_EMPLEADO WHERE Id_Cuenta = ".$id_cuenta." AND Estado = 1)
			ORDER BY Empleado
		")->queryAll();

        return $resp;
    }
     
    public function searchByEmpleado($filtro) {
       
 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10 
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado
			FROM T_PR_EMPLEADO E
			INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE (E.Identificacion LIKE '%".$filtro."%' OR E.Nombre LIKE '%".$filtro."%' OR E.Apellido LIKE '%".$filtro."%')
			AND E.Id_Empleado IN (SELECT DISTINCT Id_Empleado FROM T_PR_CUENTA_EMPLEADO)
			ORDER BY Empleado
		")->queryAll();

        return $resp;
    }

    public function searchByCuenta($filtro) {

    	$id_user = Yii::app()->user->getState('id_user');
	    $user_cuentas_esp = Yii::app()->params->usuarios_cuentas_esp;
	    $cuentas_esp = implode(",", Yii::app()->params->cuentas_esp);

	    if(!in_array($id_user, $user_cuentas_esp)){
	    	$condicion = " AND C.Id_Cuenta NOT IN (".$cuentas_esp.")"; 
	    }else{
	    	$condicion = ""; 	
	    }
       
 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10
			C.Id_Cuenta,
			C.Clasificacion,
			CASE
		    WHEN C.Clasificacion = ".Yii::app()->params->c_correo." THEN CONCAT (C.Cuenta_Usuario, '@', DW.Dominio, ' (', CL.Dominio, ')')
		    WHEN C.Clasificacion != ".Yii::app()->params->c_correo." THEN CONCAT (C.Cuenta_Usuario, ' (', CL.Dominio, ')')
		    ELSE ''
		    END AS Desc_Cuenta_Usuario
		    FROM T_PR_CUENTA C
			LEFT JOIN T_PR_DOMINIO_WEB DW ON C.Dominio = DW.Id_Dominio_Web
			LEFT JOIN T_PR_DOMINIO CL ON C.Clasificacion = CL.Id_Dominio
			WHERE (C.Cuenta_Usuario LIKE '%".$filtro."%' OR DW.Dominio LIKE '%".$filtro."%') ".$condicion."
		")->queryAll();

        return $resp;
    }

    public function DescCuentaUsuario($Id_Cuenta) {

		$modelo_cuenta = Cuenta::model()->findByPk($Id_Cuenta);

		if($modelo_cuenta->Clasificacion == Yii::app()->params->c_correo){
			return $modelo_cuenta->Cuenta_Usuario.'@'.$modelo_cuenta->dominioweb->Dominio.' ('.$modelo_cuenta->clasificacion->Dominio.')';
		}else{
			return $modelo_cuenta->Cuenta_Usuario.' ('.$modelo_cuenta->clasificacion->Dominio.')';
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
			'idempleado' => array(self::BELONGS_TO, 'Empleado', 'Id_Empleado'),
			'idcontrato' => array(self::BELONGS_TO, 'ContratoEmpleado', 'Id_Contrato'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idcuenta' => array(self::BELONGS_TO, 'Cuenta', 'Id_Cuenta'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Cuenta_Emp' => 'ID',
			'Id_Empleado' => 'Empleado',
			'Id_Contrato' => 'ID contrato',
			'Id_Cuenta' => 'Cuenta / Usuario',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',
			'Estado' => 'Estado',
			'clasificacion' => 'Clasif.',
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

		$criteria->compare('t.Id_Cuenta_Emp',$this->Id_Cuenta_Emp);
		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Id_Contrato',$this->Id_Contrato);
		$criteria->compare('t.Id_Cuenta',$this->Id_Cuenta);
		$criteria->compare('t.Id_Usuario_Creacion',$this->Id_Usuario_Creacion);
		$criteria->compare('t.Fecha_Creacion',$this->Fecha_Creacion,true);
		$criteria->compare('t.Id_Usuario_Actualizacion',$this->Id_Usuario_Actualizacion);
		$criteria->compare('t.Fecha_Actualizacion',$this->Fecha_Actualizacion,true);
		$criteria->compare('t.Estado',$this->Estado);

		$id_user = Yii::app()->user->getState('id_user');
	    $user_cuentas_esp = Yii::app()->params->usuarios_cuentas_esp;
	    $cuentas_esp = implode(",", Yii::app()->params->cuentas_esp);

	    if(!in_array($id_user, $user_cuentas_esp)){
	    	$criteria->AddCondition("t.Id_Cuenta NOT IN (".$cuentas_esp.")"); 
	    }

		$criteria->order = 't.Id_Cuenta_Emp DESC'; 

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),
		));
	}


	public function searchhist()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('idcuenta','idusuariocre','idusuarioact');

		if($this->Id_Cuenta != "" || $this->Id_Empleado != "" || $this->Id_Usuario_Creacion != "" || $this->Fecha_Creacion != "" || $this->Id_Usuario_Actualizacion != "" || $this->Fecha_Actualizacion != "" || $this->Estado != "" || $this->orderby != ""){

		    if($this->Id_Cuenta != ""){
				$criteria->AddCondition("t.Id_Cuenta = ".$this->Id_Cuenta); 
		    }

		    if($this->Id_Empleado != ""){
				$criteria->AddCondition("t.Id_Empleado = ".$this->Id_Empleado); 
		    }

    		if($this->Id_Usuario_Creacion != ""){
				$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
		    }

    		if($this->Fecha_Creacion != ""){
	      		$fci = $this->Fecha_Creacion." 00:00:00";
	      		$fcf = $this->Fecha_Creacion." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Creacion', $fci, $fcf);
	    	}

	    	if($this->Id_Usuario_Actualizacion != ""){
				$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
		    }

	    	if($this->Fecha_Actualizacion != ""){
	      		$fai = $this->Fecha_Actualizacion." 00:00:00";
	      		$faf = $this->Fecha_Actualizacion." 23:59:59";

	      		$criteria->addBetweenCondition('t.Fecha_Actualizacion', $fai, $faf);
	    	}

	    	if($this->Estado != ""){
				$criteria->AddCondition("t.Estado = ".$this->Estado); 
		    }

		    $id_user = Yii::app()->user->getState('id_user');
		    $user_cuentas_esp = Yii::app()->params->usuarios_cuentas_esp;
		    $cuentas_esp = implode(",", Yii::app()->params->cuentas_esp);

		    if(!in_array($id_user, $user_cuentas_esp)){
		    	$criteria->AddCondition("t.Id_Cuenta NOT IN (".$cuentas_esp.")"); 
		    }

		    if(empty($this->orderby)){
				$criteria->order = 't.Id_Cuenta_Emp DESC'; 	
			}else{
				switch ($this->orderby) {
			        case 1:
				        $criteria->order = 'idusuariocre.Usuario ASC'; 
				        break;
				    case 2:
				        $criteria->order = 'idusuariocre.Usuario DESC'; 
				        break;
				    case 3:
				        $criteria->order = 't.Fecha_Creacion ASC'; 
				        break;
				    case 4:
				        $criteria->order = 't.Fecha_Creacion DESC'; 
				        break;
				    case 5:
				        $criteria->order = 'idusuarioact.Usuario ASC'; 
				        break;
				    case 6:
				        $criteria->order = 'idusuarioact.Usuario DESC'; 
				        break;
					case 7:
				        $criteria->order = 't.Fecha_Actualizacion ASC'; 
				        break;
				    case 8:
				        $criteria->order = 't.Fecha_Actualizacion DESC'; 
				        break;
				    case 9:
				        $criteria->order = 't.Estado DESC'; 
				        break;
				    case 10:
				        $criteria->order = 't.Estado ASC'; 
				        break;  
				}
			}

    	}else{

    		$id_user = Yii::app()->user->getState('id_user');
		    $user_cuentas_esp = Yii::app()->params->usuarios_cuentas_esp;
		    $cuentas_esp = implode(",", Yii::app()->params->cuentas_esp);

		    if(!in_array($id_user, $user_cuentas_esp)){
		    	$criteria->AddCondition("t.Id_Cuenta NOT IN (".$cuentas_esp.")"); 
		    }

    		$criteria->order = 't.Id_Cuenta_Emp DESC'; 
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
	 * @return CuentaEmpleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

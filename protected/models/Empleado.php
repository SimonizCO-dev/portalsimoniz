<?php

/**
 * This is the model class for table "T_PR_EMPLEADO".
 *
 * The followings are the available columns in table 'T_PR_EMPLEADO':
 * @property integer $Id_Empleado
 * @property integer $Id_Ciudad_Nacimiento
 * @property integer $Id_Banco
 * @property integer $Id_T_Cuenta
 * @property integer $Id_Genero
 * @property integer $Id_Usuario_Creacion
 * @property integer $Id_Usuario_Actualizacion
 * @property string $Nombre
 * @property string $Apellido
 * @property boolean $Estado
 * @property string $Num_Cuenta
 * @property string $Direccion
 * @property string $Telefono
 * @property string $Correo
 * @property string $Talla_Overol
 * @property string $Talla_Camisa
 * @property string $Talla_Zapato
 * @property string $Talla_Pantalon
 * @property string $Talla_Bata
 * @property string $Fecha_Creacion
 * @property string $Fecha_Actualizacion
 * @property string $Fecha_Nacimiento
 * @property integer $Id_Tipo_Ident
 * @property string $Identificacion
 * @property integer $Id_Empresa
 * @property integer $Id_Eps
 * @property integer $Id_Fondo_P
 * @property integer $Id_Arl
 * @property integer $Id_Rh
 * @property integer $Id_Estado_Civil
 * @property integer $Id_Ciudad_Labor
 * @property string $Persona_Contacto
 * @property string $Tel_Persona_Contacto
 * @property integer $Id_Fondo_C
 * @property integer $Id_Caja_C
 * @property integer $Id_Raza
 * @property integer $Id_Grado_Esc
 * @property string $Id_Com_Fam
 * @property string $Id_Ocupacion
 * @property integer $Id_Ciudad_Residencia
 * @property integer $Id_Localidad_Residencia
 * @property integer $Id_Estrato
 * @property integer $Es_Gestante
 * @property integer $Id_Parentesco_Persona_Contacto
 * @property integer $Fuma
 * @property integer $Alergia
 * @property string $Observaciones
 * @property integer $Id_Regional_Labor
 * @property integer $Id_Ciudad_Exp_Ident
 * @property integer $Id_Usuario_Eval
 *
 * The followings are the available model relations:
 
 * @property Dominio $idCajaC
 * @property Dominio $idRaza
 * @property Dominio $idGradoEsc
 * @property Dominio $idLocalidadResidencia
 * @property Dominio $idEstrato
 * @property Dominio $esGestante
 * @property Dominio $idGenero
 * @property Dominio $idTipoIdent
 * @property Dominio $idEps
 * @property Dominio $idFondoP
 * @property Dominio $idArl
 * @property Dominio $idParentescoPersonaContacto
 * @property Dominio $idFondoC
 * @property Dominio $idRh
 * @property Dominio $idEstadoCivil
 * @property Dominio $idBanco
 * @property Dominio $idTCuenta
 * @property Ciudad $idCiudadResidencia
 * @property Ciudad $idCiudadNacimiento
 * @property Ciudad $idCiudadLabor
 * @property Empresa $idEmpresa
 * @property Usuario $idUsuarioCreacion
 * @property Usuario $idUsuarioActualizacion
 * @property Ciudad $idCiudadExpIdent
 */
class Empleado extends CActiveRecord
{
	
	public $orderby;
	public $empleado;
	public $edad;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'T_PR_EMPLEADO';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(
				'Id_Tipo_Ident, Identificacion, Id_Ciudad_Exp_Ident, Apellido, Nombre, Fecha_Nacimiento, Id_Ciudad_Nacimiento, Direccion, Telefono, Correo, Id_Grado_Esc, Id_Estado_Civil, Id_Raza,	Id_Ciudad_Residencia, Id_Rh, Id_Genero, Id_Estrato, Persona_Contacto, Tel_Persona_Contacto, Id_Parentesco_Persona_Contacto, Id_Ciudad_Labor, Id_Regional_Labor', 'required'),
			array('empleado', 'required', 'on' => 'asignacion, entrega, devolucion, eval'),
			array('Id_Usuario_Eval', 'required', 'on' => 'eval'),
			array('Id_Tipo_Ident, Identificacion', 'ECompositeUniqueValidator', 'attributesToAddError'=>'Identificacion','message'=>'# Identificación ya existe en el sistema '),
			array('Correo','email', 'message'=>'E-mail no valido'),
			array('
				Id_Tipo_Ident, Id_Ciudad_Nacimiento, Id_Grado_Esc, Id_Estado_Civil, Id_Raza, Id_Ciudad_Residencia, Id_Localidad_Residencia, Id_Rh, Id_Genero, Es_Gestante, Id_Estrato, Id_Parentesco_Persona_Contacto, Fuma, Alergia, Id_Ciudad_Labor, Id_Eps, Id_Caja_C, Id_Fondo_P, Id_Fondo_C, Id_Arl, Id_Banco, Id_T_Cuenta, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Id_Empresa, Id_Regional_Labor, Id_Usuario_Eval', 'numerical', 'integerOnly'=>true),

			array('Nombre, Apellido', 'length', 'max'=>30),

			array('Num_Cuenta', 'length', 'max'=>80),

			array('Direccion, Persona_Contacto', 'length', 'max'=>100),

			array('Telefono, Correo, Tel_Persona_Contacto', 'length', 'max'=>50),

			array('Talla_Overol, Talla_Camisa, Talla_Zapato, Talla_Pantalon, Talla_Bata', 'length', 'max'=>3),

			array('Identificacion', 'length', 'max'=>20),

			array('Fecha_Creacion, Fecha_Actualizacion, Observaciones, Id_Usuario_Eval', 'safe'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id_Empleado, Id_Ciudad_Nacimiento, Id_Banco, Id_T_Cuenta, Id_Genero, Id_Usuario_Creacion, Id_Usuario_Actualizacion, Nombre, Apellido, Estado, Num_Cuenta, Direccion, Telefono, Correo, Talla_Overol, Talla_Camisa, Talla_Zapato, Talla_Pantalon, Talla_Bata, Fecha_Creacion, Fecha_Actualizacion, Fecha_Nacimiento, Id_Tipo_Ident, Identificacion, Id_Empresa, Id_Eps, Id_Fondo_P, Id_Arl, Id_Rh, Id_Caja_C, Id_Estado_Civil, Id_Ciudad_Labor, Id_Regional_Labor, Persona_Contacto, Tel_Persona_Contacto, Id_Fondo_P, usuario_creacion, usuario_actualizacion, orderby', 'safe', 'on'=>'search'),
		);
	}

	public function searchByEmpleado($filtro) {
       
        $resp = Yii::app()->db->createCommand("
		    SELECT 
		    TOP 10 
		    E.Id_Empleado, 
		    CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Nombre_Apellido
		    FROM T_PR_EMPLEADO E
		    INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
		    WHERE (E.Identificacion LIKE '%".$filtro."%' OR E.Nombre LIKE '%".$filtro."%' OR E.Apellido LIKE '%".$filtro."%') AND E.estado = 1 ORDER BY Nombre_Apellido

		")->queryAll();
        return $resp;
        
 	}

 	public function searchByEmpleadoAsigEnt($filtro) {
        
 		$array_empresas = (Yii::app()->user->getState('array_empresas'));
 		$cadena_empresas = implode(",",$array_empresas);

 		$resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10 
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado, 
			(SELECT COUNT(*) FROM T_PR_ELEMENTO_EMPLEADO EE WHERE EE.Id_Contrato = CE.Id_Contrato AND EE.Estado = 1) AS Cant_Ele,
			(SELECT COUNT(*) FROM T_PR_HERRAMIENTA_EMPLEADO HE WHERE HE.Id_Contrato = CE.Id_Contrato AND HE.Estado = 1) AS Cant_Her  
			FROM T_PR_CONTRATO_EMPLEADO CE
			INNER JOIN T_PR_EMPLEADO E ON CE.Id_Empleado = E.Id_Empleado
			INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE CE.Id_M_Retiro IS NULL 
			AND CE.Id_Empresa IN (".$cadena_empresas.")
			AND (E.Identificacion LIKE '%".$filtro."%' OR E.Nombre LIKE '%".$filtro."%' OR E.Apellido LIKE '%".$filtro."%')
			AND ((SELECT COUNT(*) FROM T_PR_ELEMENTO_EMPLEADO EE WHERE EE.Id_Contrato = CE.Id_Contrato AND EE.Estado = 3) >= 0
			OR (SELECT COUNT(*) FROM T_PR_HERRAMIENTA_EMPLEADO HE WHERE HE.Id_Contrato = CE.Id_Contrato AND HE.Estado = 3) >= 0) 
			ORDER BY Empleado
		")->queryAll();

        return $resp;
        
 	}

 	public function searchByEmpleadoDev($filtro) {
        
 		$array_empresas = (Yii::app()->user->getState('array_empresas'));
 		$cadena_empresas = implode(",",$array_empresas);

        $resp = Yii::app()->db->createCommand("
			SELECT 
			TOP 10 
			E.Id_Empleado, 
			CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Empleado, 
			(SELECT COUNT(*) FROM T_PR_ELEMENTO_EMPLEADO EE WHERE EE.Id_Contrato = CE.Id_Contrato AND EE.Estado = 1) AS Cant_Ele,
			(SELECT COUNT(*) FROM T_PR_HERRAMIENTA_EMPLEADO HE WHERE HE.Id_Contrato = CE.Id_Contrato AND HE.Estado = 1) AS Cant_Her  
			FROM T_PR_CONTRATO_EMPLEADO CE
			INNER JOIN T_PR_EMPLEADO E ON CE.Id_Empleado = E.Id_Empleado
			INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio
			WHERE CE.Id_M_Retiro IS NULL 
			AND CE.Id_Empresa IN (".$cadena_empresas.")
			AND (E.Identificacion LIKE '%".$filtro."%' OR E.Nombre LIKE '%".$filtro."%' OR E.Apellido LIKE '%".$filtro."%')
			AND ((SELECT COUNT(*) FROM T_PR_ELEMENTO_EMPLEADO EE WHERE EE.Id_Contrato = CE.Id_Contrato AND EE.Estado = 1) >= 0
			OR (SELECT COUNT(*) FROM T_PR_HERRAMIENTA_EMPLEADO HE WHERE HE.Id_Contrato = CE.Id_Contrato AND HE.Estado = 1) >= 0) 
			ORDER BY Empleado
		")->queryAll();	
        return $resp;
        
 	}

 	public function searchById($filtro) {
 
        $resp = Yii::app()->db->createCommand("
		    SELECT E.Id_Empleado , CONCAT (E.Apellido, ' ', E.Nombre, ' (', TI.Dominio, ' ', E.Identificacion, ')') AS Nombre_Apellido FROM T_PR_EMPLEADO E INNER JOIN T_PR_DOMINIO TI ON E.Id_Tipo_Ident = TI.Id_Dominio WHERE E.Id_Empleado = '".$filtro."'")->queryAll();
        return $resp;

 	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idtipoident' => array(self::BELONGS_TO, 'Dominio', 'Id_Tipo_Ident'),
			'idciudadn' => array(self::BELONGS_TO, 'Ciudad', 'Id_Ciudad_Nacimiento'),
			'idgradoesc' => array(self::BELONGS_TO, 'Dominio', 'Id_Grado_Esc'),
			'idestadocivil' => array(self::BELONGS_TO, 'Dominio', 'Id_Estado_Civil'),
			'idraza' => array(self::BELONGS_TO, 'Dominio', 'Id_Raza'),
			'idciudadr' => array(self::BELONGS_TO, 'Ciudad', 'Id_Ciudad_Residencia'),
			'idlocres' => array(self::BELONGS_TO, 'Dominio', 'Id_Localidad_Residencia'),
			'idrh' => array(self::BELONGS_TO, 'Dominio', 'Id_Rh'),
			'idgenero' => array(self::BELONGS_TO, 'Dominio', 'Id_Genero'),
			'idestrato' => array(self::BELONGS_TO, 'Dominio', 'Id_Estrato'),
			'idparentpercont' => array(self::BELONGS_TO, 'Dominio', 'Id_Parentesco_Persona_Contacto'),
			'idciudadl' => array(self::BELONGS_TO, 'Ciudad', 'Id_Ciudad_Labor'),
			'ideps' => array(self::BELONGS_TO, 'Dominio', 'Id_Eps'),
			'idcajac' => array(self::BELONGS_TO, 'Dominio', 'Id_Caja_C'),
			'idfondop' => array(self::BELONGS_TO, 'Dominio', 'Id_Fondo_P'),
			'idfondoc' => array(self::BELONGS_TO, 'Dominio', 'Id_Fondo_C'),
			'idarl' => array(self::BELONGS_TO, 'Dominio', 'Id_Arl'),
			'idbanco' => array(self::BELONGS_TO, 'Dominio', 'Id_Banco'),
			'idtcuenta' => array(self::BELONGS_TO, 'Dominio', 'Id_T_Cuenta'),
			'idusuariocre' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Creacion'),
			'idusuarioact' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Actualizacion'),
			'idempresa' => array(self::BELONGS_TO, 'Empresa', 'Id_Empresa'),
			'idregional' => array(self::BELONGS_TO, 'Regional', 'Id_Regional_Labor'),
			'idciudadexpident' => array(self::BELONGS_TO, 'Ciudad', 'Id_Ciudad_Exp_Ident'),
			'idusuarioeval' => array(self::BELONGS_TO, 'Usuario', 'Id_Usuario_Eval'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id_Empleado' => 'ID',
			'Id_Tipo_Ident' => 'Tipo de identificación',
			'Identificacion' => '# Identificación',
			'Apellido' => 'Apellidos',
			'Nombre' => 'Nombres',
			'Fecha_Nacimiento' => 'Fecha de nacimiento',
			
			'edad' => 'Edad',
			
			'Id_Ciudad_Nacimiento' => 'Dpto - mun. nacimiento',
			'Direccion' => 'Dirección',
			'Telefono' => 'Teléfono(s)',
			'Correo' => 'E-mail',

			'Id_Grado_Esc' => 'Grado de escolaridad',
			'Id_Estado_Civil' => 'Estado civil', 
			'Id_Raza' => 'Raza',
			'Id_Com_Fam' => 'Composición familiar',
			'Id_Ocupacion' => 'Ocupación',
			'Id_Ciudad_Residencia' => 'Dpto - mun. residencia',
			'Id_Localidad_Residencia' => 'Localidad de residencia',
			'Id_Rh' => 'RH',
			'Id_Genero' => 'Género',
			'Es_Gestante' => 'Gestante ?',
			'Id_Estrato' => 'Estrato socioeconómico',
			'Persona_Contacto' => 'Persona de contacto',
			'Tel_Persona_Contacto' => 'Teléfono(s) contacto',
			'Id_Parentesco_Persona_Contacto' => 'Parentesco contacto',
			'Fuma' => 'Fuma ?',
			'Alergia' => 'Alergia ?',
			'Observaciones' => 'Observaciones (alergia)',

			'Id_Ciudad_Labor' => 'Dpto - mun. labor',
			'Id_Eps' => 'Eps',
			'Id_Caja_C' => 'Caja de compensación',
			'Id_Fondo_P' => 'Fondo de pensiones',
			'Id_Fondo_C' => 'Fondo de cesantías',
			'Id_Arl' => 'Arl',
			'Id_Banco' => 'Banco',
			'Id_T_Cuenta' => 'Tipo de cuenta',
			'Num_Cuenta' => 'Número de cuenta',	
			'Talla_Camisa' => 'Talla camisa',
			'Talla_Pantalon' => 'Talla pantalón',
			'Talla_Zapato' => 'Talla zapatos',
			'Talla_Overol' => 'Talla overol',
			'Talla_Bata' => 'Talla bata',

			'Id_Empresa' => 'Empresa',	
			'Estado' => 'Estado',
			'Id_Usuario_Creacion' => 'Usuario que creo',
			'Id_Usuario_Actualizacion' => 'Ultimo usuario que actualizó',
			'Fecha_Creacion' => 'Fecha de creación',
			'Fecha_Actualizacion' => 'Ultima fecha de actualización',

			'orderby' => 'Orden de resultados',
			'empleado' => 'Empleado',

			'Id_Regional_Labor' => 'Regional de labor',
			'Id_Ciudad_Exp_Ident' => 'Dpto - mun. de expedición',

			'Id_Usuario_Eval' => 'Usuario evaluador',
			
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
	   	$criteria->with=array('idusuariocre','idusuarioact', 'idtipoident', 'idempresa');

		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Identificacion',$this->Identificacion,true);
		$criteria->compare('t.Nombre',$this->Nombre,true);
		$criteria->compare('t.Apellido',$this->Apellido,true);
		$criteria->compare('t.Telefono',$this->Telefono,true);
		$criteria->compare('t.Correo',$this->Correo,true);
		$criteria->compare('t.Estado',$this->Estado);
			
		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);

		if($this->Id_Tipo_Ident != ""){
			$criteria->AddCondition("t.Id_Tipo_Ident = ".$this->Id_Tipo_Ident); 
	    }

	    if($this->Id_Empresa != ""){
			$criteria->AddCondition("t.Id_Empresa = '".$this->Id_Empresa."'"); 
	    }else{
	    	$criteria->AddCondition("t.Id_Empresa IN (0,".$cadena_empresas.")"); 
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

		if($this->Id_Usuario_Creacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Creacion = ".$this->Id_Usuario_Creacion); 
	    }

    	if($this->Id_Usuario_Actualizacion != ""){
			$criteria->AddCondition("t.Id_Usuario_Actualizacion = ".$this->Id_Usuario_Actualizacion); 
	    }

	    if(empty($this->orderby)){
			$criteria->order = 't.Id_Empleado DESC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Empleado ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Empleado DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idtipoident.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idtipoident.Dominio DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Identificacion ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Identificaciono DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Apellido ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Apellido DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Nombre ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Nombre DESC'; 
			        break;
			    case 11:
			        $criteria->order = 'idempresa.Descripcion ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idempresa.Descripcion DESC'; 
			        break;
			    case 13:
			        $criteria->order = 't.Telefono ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Telefono DESC'; 
			        break;
			    case 15:
			        $criteria->order = 't.Correo ASC'; 
			        break;
			    case 16:
			        $criteria->order = 't.Correo DESC'; 
			        break;     
		        case 17:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 18:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 19:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 20:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 21:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 22:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 23:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 24:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 25:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 26:
			        $criteria->order = 't.Estado ASC'; 
			        break;
			}
		}

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize'=>Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize'])),		
		));
	}

	public function searcheval()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together  =  true;
	   	$criteria->with=array('idusuariocre','idusuarioact', 'idtipoident', 'idempresa');

	   	$criteria->AddCondition("t.Estado = 1 AND t.Id_Usuario_Eval IS NULL"); 

		$criteria->compare('t.Id_Empleado',$this->Id_Empleado);
		$criteria->compare('t.Identificacion',$this->Identificacion,true);
		$criteria->compare('t.Nombre',$this->Nombre,true);
		$criteria->compare('t.Apellido',$this->Apellido,true);
		$criteria->compare('t.Telefono',$this->Telefono,true);
		$criteria->compare('t.Correo',$this->Correo,true);
			
		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);

		if($this->Id_Tipo_Ident != ""){
			$criteria->AddCondition("t.Id_Tipo_Ident = '".$this->Id_Tipo_Ident."'"); 
	    }

	    if($this->Id_Empresa != ""){
			$criteria->AddCondition("t.Id_Empresa = '".$this->Id_Empresa."'"); 
	    }else{
	    	$criteria->AddCondition("t.Id_Empresa IN (0,".$cadena_empresas.")"); 
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

		if($this->usuario_creacion != ""){
			$criteria->AddCondition("idusuariocre.Usuario = '".$this->usuario_creacion."'"); 
	    }

    	if($this->usuario_actualizacion != ""){
			$criteria->AddCondition("idusuarioact.Usuario = '".$this->usuario_actualizacion."'"); 
	    }

	    
		if(empty($this->orderby)){
			$criteria->order = 't.Apellido ASC'; 	
		}else{
			switch ($this->orderby) {
			    case 1:
			        $criteria->order = 't.Id_Empleado ASC'; 
			        break;
			    case 2:
			        $criteria->order = 't.Id_Empleado DESC'; 
			        break;
			    case 3:
			        $criteria->order = 'idtipoident.Dominio ASC'; 
			        break;
			    case 4:
			        $criteria->order = 'idtipoident.Dominio DESC'; 
			        break;
			    case 5:
			        $criteria->order = 't.Identificacion ASC'; 
			        break;
			    case 6:
			        $criteria->order = 't.Identificacion DESC'; 
			        break;
			    case 7:
			        $criteria->order = 't.Apellido ASC'; 
			        break;
			    case 8:
			        $criteria->order = 't.Apellido DESC'; 
			        break;
			    case 9:
			        $criteria->order = 't.Nombre ASC'; 
			        break;
			    case 10:
			        $criteria->order = 't.Nombre DESC'; 
			        break;
			    case 11:
			        $criteria->order = 'idempresa.Descripcion ASC'; 
			        break;
			    case 12:
			        $criteria->order = 'idempresa.Descripcion DESC'; 
			        break;
			    case 13:
			        $criteria->order = 't.Telefono ASC'; 
			        break;
			    case 14:
			        $criteria->order = 't.Telefono DESC'; 
			        break;
			    case 15:
			        $criteria->order = 't.Correo ASC'; 
			        break;
			    case 16:
			        $criteria->order = 't.Correo DESC'; 
			        break;     
		        case 17:
			        $criteria->order = 'idusuariocre.Usuario ASC'; 
			        break;
			    case 18:
			        $criteria->order = 'idusuariocre.Usuario DESC'; 
			        break;
			    case 19:
			        $criteria->order = 't.Fecha_Creacion ASC'; 
			        break;
			    case 20:
			        $criteria->order = 't.Fecha_Creacion DESC'; 
			        break;
			    case 21:
			        $criteria->order = 'idusuarioact.Usuario ASC'; 
			        break;
			    case 22:
			        $criteria->order = 'idusuarioact.Usuario DESC'; 
			        break;
				case 23:
			        $criteria->order = 't.Fecha_Actualizacion ASC'; 
			        break;
			    case 24:
			        $criteria->order = 't.Fecha_Actualizacion DESC'; 
			        break;
			    case 25:
			        $criteria->order = 't.Estado DESC'; 
			        break;
			    case 26:
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
	 * @return Empleado the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

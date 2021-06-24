<?php

class EmpleadoController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','searchEmpleado','searchEmpleadobyid','asignacion', 'entrega', 'devolucion','searchEmpleadoAsigEnt', 'searchEmpleadoDev','asigeval','eval'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','asige'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model=$this->loadModel($id);


		//logica visibilidad boton nuevo contrato
		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

		if(empty($query_contrato)){
			$asociacion_elementos= 0;
			$contrato_act = 0;
			$ter_cont = 0;
		}else{
			$asociacion_elementos = 1;
			$contrato_act = $query_contrato['Id_Contrato'];

			//se verifica si la persona tiene elementos asignados al contrato sin entregar
			$criteria=new CDbCriteria;
			$criteria->condition='Id_Empleado=:Id_Empleado AND Id_Contrato=:Id_Contrato AND Estado = 1';
			$criteria->params=array(':Id_Empleado'=>$id, ':Id_Contrato'=>$contrato_act);
			$modelo_elementos_sin_dev=ElementoEmpleado::model()->findAll($criteria);

			$query_ele_her_asig= Yii::app()->db->createCommand('
				SELECT 
				CE.Id_Contrato 
				FROM T_PR_CONTRATO_EMPLEADO CE
				WHERE CE.Id_M_Retiro IS NULL 
				AND ((SELECT COUNT(*) FROM T_PR_ELEMENTO_EMPLEADO EE WHERE EE.Id_Contrato = CE.Id_Contrato AND EE.Estado IN (1,3)) > 0
				OR (SELECT COUNT(*) FROM T_PR_HERRAMIENTA_EMPLEADO HE WHERE HE.Id_Contrato = CE.Id_Contrato AND HE.Estado IN (1,3)) > 0
				OR (SELECT COUNT(*) FROM T_PR_CUENTA_EMPLEADO CUE WHERE CUE.Id_Contrato = CE.Id_Contrato AND CUE.Estado = 1) > 0)
				OR (SELECT COUNT(*) FROM T_PR_EMP_EQUIPO EQE WHERE EQE.Id_Empleado = '.$id.' AND EQE.Estado = 1) > 0)
				AND CE.Id_Empleado = '.$id.'
			')->queryRow();


			if(empty($query_ele_her_asig)){
				$ter_cont = 1;
			}else{
				$ter_cont = 0;
			}

		}

		//Permiso para ver / modificar log / sueldo en contratos
		$upd_th = Yii::app()->user->getState('upd_th');

		//parientes del empleado
		$model_parientes=new NucleoEmpleado('search');
		$model_parientes->unsetAttributes();  // clear any default values
		$model_parientes->Id_Empleado = $id;

		//formación academica del empleado
		$model_formacion=new FormacionEmpleado('search');
		$model_formacion->unsetAttributes();  // clear any default values
		$model_formacion->Id_Empleado = $id;

		//evaluaciones del empleado
		$model_evaluaciones=new EvaluacionEmpleado('search');
		$model_evaluaciones->unsetAttributes();  // clear any default values
		$model_evaluaciones->Id_Empleado = $id;

		//contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Id_Contrato DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_contrato_act = new CActiveDataProvider('ContratoEmpleado', array(
		    'criteria'=>$criteria,
		));

		//contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Id_Contrato DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_contratos_ant = new CActiveDataProvider('ContratoEmpleado', array(
		    'criteria'=>$criteria,
		));
			
		//novedades contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Fecha_Creacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_novedades_act = new CActiveDataProvider('NovedadContrato', array(
		    'criteria'=>$criteria,
		));

		//novedades contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Fecha_Creacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_novedades_ant = new CActiveDataProvider('NovedadContrato', array(
		    'criteria'=>$criteria,
		));

		//turnos contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Fecha_Inicial DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_turnos_act = new CActiveDataProvider('TurnoEmpleado', array(
		    'criteria'=>$criteria,
		));

		//turnos contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Fecha_Inicial DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_turnos_ant = new CActiveDataProvider('TurnoEmpleado', array(
		    'criteria'=>$criteria,
		));

		//llamados de atención / sanciones contrato activo
		$criteria=new CDbCriteria;
		$criteria->join = 'LEFT JOIN T_PR_DOMINIO d ON t.Id_M_Disciplinario = d.Id_Dominio';
		$criteria->condition = "t.Id_Empleado = :Id_Empleado AND t.Id_Contrato = :Id_Contrato AND d.Id_Padre IN (".Yii::app()->params->motivos_d_llamado_atencion.",".Yii::app()->params->motivos_d_sancion.")";
		$criteria->order = "Fecha DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_disciplinarios_act = new CActiveDataProvider('DisciplinarioEmpleado', array(
		    'criteria'=>$criteria,
		));

		//llamados de atención / sanciones contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->join = 'LEFT JOIN T_PR_DOMINIO d ON t.Id_M_Disciplinario = d.Id_Dominio';
		$criteria->condition = "t.Id_Empleado = :Id_Empleado AND t.Id_Contrato != :Id_Contrato AND d.Id_Padre IN (".Yii::app()->params->motivos_d_llamado_atencion.",".Yii::app()->params->motivos_d_sancion.")";
		$criteria->order = "Fecha DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_disciplinarios_ant = new CActiveDataProvider('DisciplinarioEmpleado', array(
		    'criteria'=>$criteria,
		));

		//comparendos contrato activo
		$criteria=new CDbCriteria;
		$criteria->join = 'LEFT JOIN T_PR_DOMINIO d ON t.Id_M_Disciplinario = d.Id_Dominio';
		$criteria->condition = "t.Id_Empleado = :Id_Empleado AND t.Id_Contrato = :Id_Contrato AND d.Id_Padre = ".Yii::app()->params->motivos_d_comparendo;
		$criteria->order = "Fecha DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_comparendos_act = new CActiveDataProvider('DisciplinarioEmpleado', array(
		    'criteria'=>$criteria,
		));

		//comparendos contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->join = 'LEFT JOIN T_PR_DOMINIO d ON t.Id_M_Disciplinario = d.Id_Dominio';
		$criteria->condition = "t.Id_Empleado = :Id_Empleado AND t.Id_Contrato != :Id_Contrato AND d.Id_Padre = ".Yii::app()->params->motivos_d_comparendo;
		$criteria->order = "Fecha DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_comparendos_ant = new CActiveDataProvider('DisciplinarioEmpleado', array(
		    'criteria'=>$criteria,
		));

		//ausencias contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Fecha_Inicial DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_ausencias_act = new CActiveDataProvider('AusenciaEmpleado', array(
		    'criteria'=>$criteria,
		));

		//ausencias contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Fecha_Inicial DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_ausencias_ant = new CActiveDataProvider('AusenciaEmpleado', array(
		    'criteria'=>$criteria,
		));

		//elementos contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Fecha_Actualizacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_elementos_act = new CActiveDataProvider('ElementoEmpleado', array(
		    'criteria'=>$criteria,
		));

		//elementos contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Fecha_Actualizacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_elementos_ant = new CActiveDataProvider('ElementoEmpleado', array(
		    'criteria'=>$criteria,
		));

		//herramientas contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Fecha_Actualizacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_herramientas_act = new CActiveDataProvider('HerramientaEmpleado', array(
		    'criteria'=>$criteria,
		));

		//herramientas contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Fecha_Actualizacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_herramientas_ant = new CActiveDataProvider('HerramientaEmpleado', array(
		    'criteria'=>$criteria,
		));

		//cuentas contrato activo
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato = :Id_Contrato";
		$criteria->order = "Fecha_Actualizacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_cuentas_act = new CActiveDataProvider('CuentaEmpleado', array(
		    'criteria'=>$criteria,
		));

		//cuentas contratos anteriores
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado AND Id_Contrato != :Id_Contrato";
		$criteria->order = "Fecha_Actualizacion DESC";
		$criteria->params = array (':Id_Empleado' => $id, ':Id_Contrato' => $contrato_act);
		$model_cuentas_ant = new CActiveDataProvider('CuentaEmpleado', array(
		    'criteria'=>$criteria,
		));

		$this->render('view',array(
			'model'=>$model,
			'asociacion_elementos'=>$asociacion_elementos,
			'ter_cont'=>$ter_cont,
			'model_parientes'=>$model_parientes,
			'model_formacion'=>$model_formacion,
			'model_evaluaciones'=>$model_evaluaciones,
			'model_contrato_act'=>$model_contrato_act,
			'model_contratos_ant'=>$model_contratos_ant,
			'model_novedades_act'=>$model_novedades_act,
			'model_novedades_ant'=>$model_novedades_ant,
			'model_turnos_act'=>$model_turnos_act,
			'model_turnos_ant'=>$model_turnos_ant,
			'model_disciplinarios_act'=>$model_disciplinarios_act,
			'model_disciplinarios_ant'=>$model_disciplinarios_ant,
			'model_comparendos_act'=>$model_comparendos_act,
			'model_comparendos_ant'=>$model_comparendos_ant,
			'model_ausencias_act'=>$model_ausencias_act,
			'model_ausencias_ant'=>$model_ausencias_ant,
			'model_elementos_act'=>$model_elementos_act,
			'model_elementos_ant'=>$model_elementos_ant,
			'model_herramientas_act'=>$model_herramientas_act,
			'model_herramientas_ant'=>$model_herramientas_ant,
			'model_cuentas_act'=>$model_cuentas_act,
			'model_cuentas_ant'=>$model_cuentas_ant,
			'upd_th'=>$upd_th,	
		));
	}

	public function actionViewMed($id)
	{
		$model=$this->loadModel($id);

		//logica visibilidad boton nuevo contrato
		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

		if(empty($query_contrato)){
			$asociacion_elementos= 0;
		}else{
			$asociacion_elementos = 1;
		}

		//hco
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado";
		$criteria->order = "Id_Hco DESC";
		$criteria->params = array (':Id_Empleado' => $id);
		$model_hco = new CActiveDataProvider('HcoMed', array(
		    'criteria'=>$criteria,
		));

		//anexos
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado";
		$criteria->order = "Id_Anexo DESC";
		$criteria->params = array (':Id_Empleado' => $id);
		$model_anexos = new CActiveDataProvider('AnexoMed', array(
		    'criteria'=>$criteria,
		));

		//formulas
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado";
		$criteria->order = "Id_Formula DESC";
		$criteria->params = array (':Id_Empleado' => $id);
		$model_formulas = new CActiveDataProvider('FormulaMed', array(
		    'criteria'=>$criteria,
		));

		//soportes
		$criteria=new CDbCriteria;
		$criteria->condition = "Id_Empleado = :Id_Empleado";
		$criteria->order = "Id_Soporte DESC";
		$criteria->params = array (':Id_Empleado' => $id);
		$model_soportes = new CActiveDataProvider('SoporteMed', array(
		    'criteria'=>$criteria,
		));


		$this->render('viewmed',array(
			'model'=>$model,
			'asociacion_elementos'=>$asociacion_elementos,
			'model_hco'=>$model_hco,
			'model_anexos'=>$model_anexos,
			'model_formulas'=>$model_formulas,
			'model_soportes'=>$model_soportes,						
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Empleado;
		
		$tipos_ident = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_ident.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$ciudades = Ciudad::model()->findAll(array('order'=>'Ciudad'));

		$grados_escolaridad = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->nivel_estudio.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$estado_c = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->estado_civil.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$razas = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->raza.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$composicion_familiar = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->composicion_familiar.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$ocupaciones = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->ocupaciones.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$localidades = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->localidades_bog.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$rh= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->rh.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$generos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->genero.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$estratos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->estrato_socioeconomico.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$parentescos_contacto= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->parentesco_contacto.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		
		$eps= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->eps.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$caja_c= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->caja_c.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$pensiones= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->pensiones.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$cesantias= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->cesantias.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$arl= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->arl.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		
		$bancos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->bancos.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$tipos_cuenta= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_cuenta.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$regionales= Yii::app()->db->createCommand('SELECT r.Id_Regional, r.Regional FROM T_PR_REGIONAL r WHERE Estado = 1 ORDER BY r.Regional')->queryAll();


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Empleado']))
		{
			$model->attributes=$_POST['Empleado'];

			if(isset($_POST['Empleado']['Id_Ocupacion'])){
				//si vienen ocupaciones
				$ocu = $_POST['Empleado']['Id_Ocupacion'];

				if(!empty($ocu)){
					$model->Id_Ocupacion = implode(",", $ocu);
				}	
			}else{
				$model->Id_Ocupacion = null;	
			}

			if(isset($_POST['Empleado']['Id_Com_Fam'])){
				//si viene composicion fam,.
				$cf = $_POST['Empleado']['Id_Com_Fam'];

				if(!empty($cf)){
					$model->Id_Com_Fam = implode(",", $cf);
				}	
			}else{
				$model->Id_Com_Fam = null;	
			}

			$model->Nombre = trim($_POST['Empleado']['Nombre']);
			$model->Apellido = trim($_POST['Empleado']['Apellido']);
			$model->Estado = 0;
			$model->Id_Empresa = 0;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Empleado creado correctamente.");
				$this->redirect(array('contratoEmpleado/create','e'=>$model->Id_Empleado));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'tipos_ident'=>$tipos_ident,
			'ciudades'=>$ciudades,
			'grados_escolaridad'=>$grados_escolaridad,
			'estado_c' => $estado_c,
			'razas'=>$razas,
			'composicion_familiar'=>$composicion_familiar,
			'ocupaciones'=>$ocupaciones,
			'localidades'=>$localidades,
			'rh'=>$rh,
			'generos'=>$generos,
			'estratos'=>$estratos,
			'parentescos_contacto'=>$parentescos_contacto,
			'eps' => $eps,
			'caja_c' => $caja_c,
			'pensiones' => $pensiones,
			'cesantias' => $cesantias,
			'arl' => $arl,
			'bancos'=>$bancos,
			'tipos_cuenta' => $tipos_cuenta,
			'regionales' => $regionales,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		//com. fam. guardados

		$cf_act = $model->Id_Com_Fam;

		$array_cf_act = array();
		
		if($cf_act != ""){
			$cfs = explode(",", $cf_act);
			foreach ($cfs as $key => $value) {
				array_push($array_cf_act, $value);
			}	
		}

		$json_cf_act = json_encode($array_cf_act);

		//ocupaciones guardadas

		$ocup_act = $model->Id_Ocupacion;

		$array_ocup_act = array();
		
		if($ocup_act != ""){
			$ocup = explode(",", $ocup_act);
			foreach ($ocup as $key => $value) {
				array_push($array_ocup_act, $value);
			}	
		}

		$json_ocup_act = json_encode($array_ocup_act);

		$tipos_ident = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_ident.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$ciudades = Ciudad::model()->findAll(array('order'=>'Ciudad'));

		$grados_escolaridad = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->nivel_estudio.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$estado_c = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->estado_civil.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$razas = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->raza.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$composicion_familiar = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->composicion_familiar.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$ocupaciones = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->ocupaciones.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$localidades = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->localidades_bog.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$rh= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->rh.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$generos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->genero.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$estratos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->estrato_socioeconomico.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$parentescos_contacto= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->parentesco_contacto.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		
		$eps= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->eps.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$caja_c= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->caja_c.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$pensiones= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->pensiones.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$cesantias= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->cesantias.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$arl= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->arl.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		
		$bancos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->bancos.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$tipos_cuenta= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_cuenta.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$regionales= Yii::app()->db->createCommand('SELECT r.Id_Regional, r.Regional FROM T_PR_REGIONAL r WHERE Estado = 1 ORDER BY r.Regional')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Empleado']))
		{
			
			$model->attributes=$_POST['Empleado'];

			if(isset($_POST['Empleado']['Id_Ocupacion'])){
				//si vienen ocupaciones
				$ocu = $_POST['Empleado']['Id_Ocupacion'];

				if(!empty($ocu)){
					$model->Id_Ocupacion = implode(",", $ocu);
				}	
			}else{
				$model->Id_Ocupacion = null;	
			}

			if(isset($_POST['Empleado']['Id_Com_Fam'])){
				//si viene composicion fam,.
				$cf = $_POST['Empleado']['Id_Com_Fam'];

				if(!empty($cf)){
					$model->Id_Com_Fam = implode(",", $cf);
				}	
			}else{
				$model->Id_Com_Fam = null;	
			}

			$model->Nombre = trim($_POST['Empleado']['Nombre']);
			$model->Apellido = trim($_POST['Empleado']['Apellido']);
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Empleado actualizado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'json_cf_act' => $json_cf_act,
			'json_ocup_act' => $json_ocup_act,
			'tipos_ident'=>$tipos_ident,
			'ciudades'=>$ciudades,
			'grados_escolaridad'=>$grados_escolaridad,
			'estado_c' => $estado_c,
			'razas'=>$razas,
			'composicion_familiar'=>$composicion_familiar,
			'ocupaciones'=>$ocupaciones,
			'localidades'=>$localidades,
			'rh'=>$rh,
			'generos'=>$generos,
			'estratos'=>$estratos,
			'parentescos_contacto'=>$parentescos_contacto,
			'eps' => $eps,
			'caja_c' => $caja_c,
			'pensiones' => $pensiones,
			'cesantias' => $cesantias,
			'arl' => $arl,
			'bancos'=>$bancos,
			'tipos_cuenta' => $tipos_cuenta,
			'regionales' => $regionales,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Empleado('search');

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$tipos_ident= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_ident.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Empleado']))
			$model->attributes=$_GET['Empleado'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'tipos_ident'=>$tipos_ident,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAsigE()
	{
		$model=new Empleado('searcheval');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$tipos_ident= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_ident.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN (0,'.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Empleado']))
			$model->attributes=$_GET['Empleado'];

		$this->render('asige',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'tipos_ident'=>$tipos_ident,
			'empresas'=>$empresas,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Empleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Empleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Empleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='Empleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAsignacion()
	{
		$model=new Empleado;

		$model->scenario = 'asignacion';
		
		if(isset($_POST['Empleado']))
		{
			$model->attributes=$_POST['Empleado'];
			$this->redirect(array('elementoEmpleado/asignacion','e'=>$model->empleado));
		}

		$this->render('asignacion',array(
			'model'=>$model,
		));
	}

	public function actionEntrega()
	{
		$model=new Empleado;

		$model->scenario = 'entrega';
		
		if(isset($_POST['Empleado']))
		{
			$model->attributes=$_POST['Empleado'];
			$this->redirect(array('elementoEmpleado/entrega','e'=>$model->empleado));
		}

		$this->render('entrega',array(
			'model'=>$model,
		));
	}

	public function actionDevolucion()
	{
		$model=new Empleado;

		$model->scenario = 'devolucion';
		
		if(isset($_POST['Empleado']))
		{
			$model->attributes=$_POST['Empleado'];
			$this->redirect(array('elementoEmpleado/devolucion','e'=>$model->empleado));
		}

		$this->render('devolucion',array(
			'model'=>$model,
		));
	}

	public function actionSearchEmpleado(){
		$filtro = $_GET['q'];
        $data = Empleado::model()->searchByEmpleado($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Empleado'],
               'text' => $item['Nombre_Apellido'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionSearchEmpleadoById(){
		$filtro = $_GET['id'];
        $data = Empleado::model()->searchById($filtro);

        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Empleado'],
               'text' => $item['Nombre_Apellido'],
           );
        endforeach;

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchEmpleadoAsigEnt(){
		$filtro = $_GET['q'];
        $data = Empleado::model()->searchByEmpleadoAsigEnt($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Empleado'],
               'text' => $item['Empleado'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchEmpleadoDev(){
		$filtro = $_GET['q'];
        $data = Empleado::model()->searchByEmpleadoDev($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Empleado'],
               'text' => $item['Empleado'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionAsigEval($id, $opc)
	{
		$model=$this->loadModel($id);

		$model->scenario = 'eval';
		
		$usuarios=Usuario::model()->findAll(array('condition'=>'Estado = 1 AND Id_Usuario != 1', 'order'=>'Nombres'));

		if(isset($_POST['Empleado']))
		{
			$model->attributes=$_POST['Empleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->update()){
				if($opc == 1){
					$this->redirect(array('admin'));
				}else{
					$this->redirect(array('asige'));
				}	
			}
		}

		$this->render('asigeval',array(
			'model'=>$model,
			'opc'=>$opc,
			'usuarios'=>$usuarios,
		));
	}

	public function actionEval()
	{
		$model=new Empleado;

		$model->scenario = 'eval';

		$empleados_x_usuario = Empleado::model()->findAll(array('condition'=>'Estado = 1 AND Id_Usuario_Eval = '.Yii::app()->user->getState('id_user'), 'order'=>'Apellido'));

		$lista_empleados = array();

		foreach($empleados_x_usuario AS $reg){
			if(UtilidadesEmpleado::valregevalemp($reg->Id_Empleado) === 1){
				$lista_empleados[$reg->Id_Empleado] = UtilidadesEmpleado::nombreempleado($reg->Id_Empleado);	
			}	
		}
		
		if(isset($_POST['Empleado']))
		{
			$model->attributes=$_POST['Empleado'];
			$this->redirect(array('evalEmp/create','e'=>$model->empleado, 'opc'=>1));
		}

		$this->render('eval',array(
			'model'=>$model,
			'lista_empleados'=>$lista_empleados,
		));
	}

 	
}

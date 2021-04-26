<?php

class ContratoEmpleadoController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('res','create','update', 'update2', 'update3', 'terminacion', 'asignacion'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
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
	public function actionRes($id)
	{
		$model = $this->loadModel($id);
		
		$id_ug = $model->Id_Unidad_Gerencia;
		$id_area = $model->Id_Area;
		$id_subarea = $model->Id_Subarea;
		$id_cargo = $model->Id_Cargo;
		$id_c_costo = $model->Id_Centro_Costo;
		$ss = $model->Salario_Flexible;

		if(is_null($id_ug)){
			$unidad_gerencia = '-';
		}else{
			$unidad_gerencia = UnidadGerencia::model()->findByPk($id_ug)->Unidad_Gerencia;
		}

		if(is_null($id_area)){
			$area = '-';
		}else{
			$area = Area::model()->findByPk($id_area)->Area;
		}

		if(is_null($id_subarea)){
			$subarea = '-';
		}else{
			$subarea = Subarea::model()->findByPk($id_subarea)->Subarea;
		}

		if(is_null($id_cargo)){
			$cargo = '-';
		}else{
			$cargo = Cargo::model()->findByPk($id_cargo)->Cargo;
		}

		if(is_null($id_c_costo)){
			$centro_costo = '-';
		}else{
			$m_centro_costo = CentroCosto::model()->findByPk($id_c_costo);
			$centro_costo = $m_centro_costo->Codigo.' - '.$m_centro_costo->Centro_Costo;
		}

		if(is_null($ss)){
			$salario_flexible = '-';
		}else{
			$salario_flexible = UtilidadesVarias::textoestado2($ss);
		}

		$this->render('res',array(
			'model'=>$model,
			'e'=>$model->Id_Empleado,
			'unidad_gerencia'=>$unidad_gerencia,
			'area'=>$area,
			'subarea'=>$subarea,
			'cargo'=>$cargo,
			'centro_costo'=> $centro_costo,
			'salario_flexible'=> $salario_flexible,
		));
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($e)
	{
		$model=new ContratoEmpleado;

		$model->scenario = 'create';

		//se envia a funcion para saber cual es la fecha de ingreso minimo
		$fecha_ingreso_min = UtilidadesContrato::fechaingresomin($e);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') AND e.Estado = 1 ORDER BY e.Descripcion')->queryAll();

		$unidades_gerencia=UnidadGerencia::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Unidad_Gerencia'));

		$areas=Area::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Area'));

		$subareas=Subarea::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Subarea'));

		$cargos=Cargo::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Cargo'));

		$turnos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->turno_trabajo.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$concep_exa_ocup = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->con_exa_ocup.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$grupos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->grupos.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$trabajos_esp = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->trabajos_esp.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$centro_costo = Yii::app()->db->createCommand('SELECT c.Id_C_Costo, c.Codigo, c.Centro_Costo FROM T_PR_CENTRO_COSTO c WHERE c.Estado = 1 ORDER BY c.Centro_Costo')->queryAll();

		$lista_cc = array();
		foreach ($centro_costo as $cc) {
			$lista_cc[$cc['Id_C_Costo']] = $cc['Codigo'].' - '.$cc['Centro_Costo'];
		}

		if(isset($_POST['ContratoEmpleado']))
		{
			$model->attributes=$_POST['ContratoEmpleado'];

			//funcion para cambiar la empresa y el estado actual del empleado
			UtilidadesEmpleado::cambioempresaempleado($e, $model->Id_Empresa);

			//si vienen trabajos esp. se guardan en el campo
			if(isset($_POST['ContratoEmpleado']['Id_Trab_Esp'])){

				$tr_e = $_POST['ContratoEmpleado']['Id_Trab_Esp'];

				if(!empty($tr_e)){
					$model->Id_Trab_Esp = implode(",", $tr_e);
				}	
			}else{
				$model->Id_Trab_Esp = null;	
			}

			$model->Id_Empleado = $e;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				//se verifica si el nuevo contrato tiene combinación de sugeridos para ofrecer la asignación automatica

				$s = 0;

				$modelo_sugerido = Sugerido::model()->findByAttributes(array('Id_Area' => $model->Id_Area, 'Id_Subarea' => $model->Id_Subarea, 'Id_Cargo' => $model->Id_Cargo, 'Estado' => 1));
				if(!is_null($modelo_sugerido)){
					$asig_s = 1;
					$s = $modelo_sugerido->Id_Sugerido;
				}else{
					$asig_s = 0;
				}

				//se verifica si el usuario tiene áreas y subáreas asignadas para ofrecer asignación manual

				//subareas y areas asignadas a usuario
				$array_subareas =Yii::app()->user->getState('array_subareas');
				$string_subareas_usuario = implode(",", $array_subareas);
				$array_areas =Yii::app()->user->getState('array_areas');
				$string_areas_usuario = implode(",", $array_areas);

				if(!empty($array_subareas) && !empty($array_areas)){
					$criteria1=new CDbCriteria;
					$criteria1->join = 'LEFT JOIN T_PR_ELEMENTO e ON e.Id_Elemento = t.Id_Elemento LEFT JOIN T_PR_AREA a ON a.Id_Area = t.Id_Area LEFT JOIN T_PR_SUBAREA s ON s.Id_Subarea = t.Id_Subarea';
		        	$criteria1->condition='t.Estado = 1 AND (t.Id_Area IN ('.$string_areas_usuario.') AND t.Id_Subarea IN ('.$string_subareas_usuario.')) AND e.Estado = 1 AND s.Estado = 1 AND a.Estado = 1';
		        	$elementos_a=AreaElemento::model()->findAll($criteria1);

		        	if(!empty($elementos_a)){
		        		$asig_m = 1;
		        	}else{
		        		$asig_m = 0;
		        	}
				}else{
					$asig_m = 0;	
				}
				Yii::app()->user->setFlash('success', "Se registro la contratación correctamente.");
				$this->redirect(array('asignacion', 'id'=>$model->Id_Contrato, 'as'=>$asig_s, 'am' => $asig_m, 's' => $s));
			}
		}
		
		$this->render('create',array(
			'model'=>$model,
			'e'=>$e,
			'fecha_ingreso_min'=>$fecha_ingreso_min,
			'empresas'=>$empresas,
			'unidades_gerencia'=>$unidades_gerencia,
			'areas'=>$areas,
			'subareas'=>$subareas,
			'cargos'=>$cargos,
			'turnos'=>$turnos,
			'concep_exa_ocup'=>$concep_exa_ocup,
			'grupos'=>$grupos,
			'trabajos_esp'=>$trabajos_esp,
			'lista_cc'=>$lista_cc,
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

		$model->scenario = 'update';

		//se envia a funcion para saber cual es la fecha de ingreso minimo
		$fecha_ingreso_min = UtilidadesContrato::fechaingresomin($model->Id_Empleado); 

		$empresa_act = $model->Id_Empresa;
		$ug_act = $model->Id_Unidad_Gerencia;
		$area_act = $model->Id_Area;
		$subarea_act = $model->Id_Subarea;
		$cargo_act = $model->Id_Cargo;
		$turno_act = $model->Id_Turno;
		$fi_act = $model->Fecha_Ingreso;
		$salario_act = $model->Salario;
		$concepto_act = $model->Id_Con_Ex_Ocup;
		$rest_act = $model->Restricciones;
		$grupo_act = $model->Id_Grupo;

		//trabajos esp. guardados

		$te_act = $model->Id_Trab_Esp;

		$array_te_act = array();
		
		if($te_act != ""){
			$tes = explode(",", $te_act);
			foreach ($tes as $key => $value) {
				array_push($array_te_act, $value);
			}	
		}

		//trabajos esp. asignados
		$json_te_act = json_encode($array_te_act);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$model_empleado = Empleado::model()->findByPk($model->Id_Empleado);

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') OR e.Id_Empresa = '.$empresa_act.' ORDER BY e.Descripcion')->queryAll();

		if(!is_null($ug_act)){
			$unidades_gerencia=UnidadGerencia::model()->findAll(array('condition' => 'Estado = 1 OR Id_Unidad_Gerencia = '.$ug_act, 'order'=>'Unidad_Gerencia'));
		}else{
			$unidades_gerencia=UnidadGerencia::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Unidad_Gerencia'));	
		}

		if(!is_null($area_act)){
			$areas=Area::model()->findAll(array('condition' => 'Estado = 1 OR Id_Area = '.$area_act, 'order'=>'Area'));
		}else{
			$areas=Area::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Area'));	
		}

		if(!is_null($subarea_act)){
			$subareas=Subarea::model()->findAll(array('condition' => 'Estado = 1 OR Id_Subarea = '.$subarea_act, 'order'=>'Subarea'));
		}else{
			$subareas=Subarea::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Subarea'));	
		}

		if(!is_null($cargo_act)){
			$cargos=Cargo::model()->findAll(array('condition' => 'Estado = 1 OR Id_Cargo = '.$cargo_act, 'order'=>'Cargo'));
		}else{
			$cargos=Cargo::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Cargo'));	
		}

		if(!is_null($turno_act)){
			$turnos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->turno_trabajo.' AND (Id_Dominio = '.$turno_act.' OR Estado = 1) ORDER BY d.Dominio')->queryAll();
		}else{
			$turnos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->turno_trabajo.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		if(!is_null($concepto_act)){
			$concep_exa_ocup = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->con_exa_ocup.' AND (Id_Dominio = '.$concepto_act.' OR Estado = 1) ORDER BY d.Dominio')->queryAll();
		}else{
			$concep_exa_ocup = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->con_exa_ocup.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		if(!is_null($grupo_act)){
			$grupos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->grupos.' AND (Id_Dominio = '.$grupo_act.' OR Estado = 1) ORDER BY d.Dominio')->queryAll();
		}else{
			$grupos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->grupos.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		$trabajos_esp = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->trabajos_esp.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$centro_costo = Yii::app()->db->createCommand('SELECT c.Id_C_Costo, c.Codigo, c.Centro_Costo FROM T_PR_CENTRO_COSTO c WHERE c.Estado = 1 ORDER BY c.Centro_Costo')->queryAll();

		$lista_cc = array();
		foreach ($centro_costo as $cc) {
			$lista_cc[$cc['Id_C_Costo']] = $cc['Codigo'].' - '.$cc['Centro_Costo'];
		}

		if(isset($_POST['ContratoEmpleado']))
		{
			
			$model_empleado->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model_empleado->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model_empleado->save();

			$model->attributes=$_POST['ContratoEmpleado'];

			//si vienen trabajos esp. se guardan en el campo
			if(isset($_POST['ContratoEmpleado']['Id_Trab_Esp'])){
				
				$tr_e = $_POST['ContratoEmpleado']['Id_Trab_Esp'];

				if(!empty($tr_e)){
					$model->Id_Trab_Esp = implode(",", $tr_e);
				}	
			}else{
				$model->Id_Trab_Esp = null;	
			}

			$empresa_nueva = $model->Id_Empresa;

			if($empresa_act != $empresa_nueva){
				//función para saber si el registro que se esta actualizando es el ultimo historial creado para este empleado, si es el ultimo actualiza tabla empleado, si no solo actualiza la tabla de historial
				UtilidadesEmpleado::evaluarcambioempresaempleado($model->Id_Empleado, $id, $model->Id_Empresa);	
			}

			//función para registrar cambios en el contrato
			UtilidadesEmpleado::novedadcontratoempleado($id, $model->Id_Empleado, $empresa_act, $empresa_nueva, $ug_act, $model->Id_Unidad_Gerencia, $area_act, $model->Id_Area, $subarea_act, $model->Id_Subarea, $cargo_act, $model->Id_Cargo, $turno_act, $model->Id_Turno, $fi_act, $model->Fecha_Ingreso, $salario_act, $model->Salario, $concepto_act, $model->Id_Con_Ex_Ocup, $rest_act, $model->Restricciones, $grupo_act, $model->Id_Grupo, $te_act, $model->Id_Trab_Esp);	
			
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se actualizo el contrato del empleado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'json_te_act' => $json_te_act,
			'e'=>$model->Id_Empleado,
			'fecha_ingreso_min'=>$fecha_ingreso_min,
			'empresas'=>$empresas,
			'unidades_gerencia'=>$unidades_gerencia,
			'areas'=>$areas,
			'subareas'=>$subareas,
			'cargos'=>$cargos,
			'turnos'=>$turnos,
			'concep_exa_ocup'=>$concep_exa_ocup,
			'grupos'=>$grupos,
			'trabajos_esp'=>$trabajos_esp,
			'lista_cc'=>$lista_cc,
		));
	}

	public function actionUpdate2($id)
	{
		$model=$this->loadModel($id);

		$model->scenario = 'update2';

		$id_ug = $model->Id_Unidad_Gerencia;
		$id_area = $model->Id_Area;
		$id_subarea = $model->Id_Subarea;
		$id_cargo = $model->Id_Cargo;
		$id_c_costo = $model->Id_Centro_Costo;
		$ss = $model->Salario_Flexible;

		if(is_null($id_ug)){
			$unidad_gerencia = '-';
		}else{
			$unidad_gerencia = UnidadGerencia::model()->findByPk($id_ug)->Unidad_Gerencia;
		}

		if(is_null($id_area)){
			$area = '-';
		}else{
			$area = Area::model()->findByPk($id_area)->Area;
		}

		if(is_null($id_subarea)){
			$subarea = '-';
		}else{
			$subarea = Subarea::model()->findByPk($id_subarea)->Subarea;
		}

		if(is_null($id_cargo)){
			$cargo = '-';
		}else{
			$cargo = Cargo::model()->findByPk($id_cargo)->Cargo;
		}

		if(is_null($id_c_costo)){
			$centro_costo = '-';
		}else{
			$m_centro_costo = CentroCosto::model()->findByPk($id_c_costo);
			$centro_costo = $m_centro_costo->Codigo.' - '.$m_centro_costo->Centro_Costo;
		}

		if(is_null($ss)){
			$salario_flexible = '-';
		}else{
			$salario_flexible = UtilidadesVarias::textoestado2($ss);
		}
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ContratoEmpleado']))
		{
			$model->attributes=$_POST['ContratoEmpleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se actualizo el contrato del empleado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update2',array(
			'model'=>$model,
			'e'=>$model->Id_Empleado,
			'unidad_gerencia'=>$unidad_gerencia,
			'area'=>$area,
			'subarea'=>$subarea,
			'cargo'=>$cargo,
			'centro_costo'=> $centro_costo,
			'salario_flexible'=> $salario_flexible,
		));
	}

	public function actionUpdate3($id)
	{
		$model=$this->loadModel($id);

		$model->scenario = 'update3';

		$id_empresa = $model->Id_Empresa;
		$id_ug = $model->Id_Unidad_Gerencia;
		$id_area = $model->Id_Area;
		$id_subarea = $model->Id_Subarea;
		$id_cargo = $model->Id_Cargo;
		$id_turno = $model->Id_Turno;
		$fi_act = $model->Fecha_Ingreso;
		$salario_act = $model->Salario;
		$concepto_act = $model->Id_Con_Ex_Ocup;
		$rest_act = $model->Restricciones;
		$grupo_act = $model->Id_Grupo;
		$id_c_costo = $model->Id_Centro_Costo;
		$ss = $model->Salario_Flexible;

		//trabajos especificos guardados

		$te_act = $model->Id_Trab_Esp;

		$array_te_act = array();
		
		if($te_act != ""){
			$tes = explode(",", $te_act);
			foreach ($tes as $key => $value) {
				array_push($array_te_act, $value);
			}	
		}

		$json_te_act = json_encode($array_te_act);


		if(is_null($id_ug)){
			$unidad_gerencia = '-';
		}else{
			$unidad_gerencia = UnidadGerencia::model()->findByPk($id_ug)->Unidad_Gerencia;
		}

		if(is_null($id_area)){
			$area = '-';
		}else{
			$area = Area::model()->findByPk($id_area)->Area;
		}

		if(is_null($id_subarea)){
			$subarea = '-';
		}else{
			$subarea = Subarea::model()->findByPk($id_subarea)->Subarea;
		}

		if(is_null($id_cargo)){
			$cargo = '-';
		}else{
			$cargo = Cargo::model()->findByPk($id_cargo)->Cargo;
		}

		if(is_null($id_c_costo)){
			$centro_costo = '-';
		}else{
			$m_centro_costo = CentroCosto::model()->findByPk($id_c_costo);
			$centro_costo = $m_centro_costo->Codigo.' - '.$m_centro_costo->Centro_Costo;
		}

		if(is_null($ss)){
			$salario_flexible = '-';
		}else{
			$salario_flexible = UtilidadesVarias::textoestado2($ss);
		}

		if(!is_null($id_turno)){
			$turnos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->turno_trabajo.' AND (Id_Dominio = '.$id_turno.' OR Estado = 1) ORDER BY d.Dominio')->queryAll();
		}else{
			$turnos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->turno_trabajo.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		if(!is_null($concepto_act)){
			$concep_exa_ocup = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->con_exa_ocup.' AND (Id_Dominio = '.$concepto_act.' OR Estado = 1) ORDER BY d.Dominio')->queryAll();
		}else{
			$concep_exa_ocup = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->con_exa_ocup.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		if(!is_null($grupo_act)){
			$grupos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->grupos.' AND (Id_Dominio = '.$grupo_act.' OR Estado = 1) ORDER BY d.Dominio')->queryAll();
		}else{
			$grupos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->grupos.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		$trabajos_esp = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->trabajos_esp.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ContratoEmpleado']))
		{
			//si vienen trabajos esp.
			if(isset($_POST['ContratoEmpleado']['Id_Trab_Esp'])){
				
				$tr_e = $_POST['ContratoEmpleado']['Id_Trab_Esp'];

				if(!empty($tr_e)){
					$model->Id_Trab_Esp = implode(",", $tr_e);
				}	
			}else{
				$model->Id_Trab_Esp = null;	
			}

			$model->attributes=$_POST['ContratoEmpleado'];

			//función para registrar cambios en el contrato
			UtilidadesEmpleado::novedadcontratoempleado($id, $model->Id_Empleado, $id_empresa, $id_empresa, $id_ug, $id_ug, $id_area, $id_area, $id_subarea, $id_subarea, $id_cargo, $id_cargo, $id_turno, $model->Id_Turno, $fi_act, $fi_act, $salario_act, $salario_act, $concepto_act, $model->Id_Con_Ex_Ocup, $rest_act, $model->Restricciones, $grupo_act, $model->Id_Grupo, $te_act, $model->Id_Trab_Esp);	

			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se actualizo el contrato del empleado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update3',array(
			'model'=>$model,
			'json_te_act' => $json_te_act,
			'e'=>$model->Id_Empleado,
			'unidad_gerencia'=>$unidad_gerencia,
			'area'=>$area,
			'subarea'=>$subarea,
			'cargo'=>$cargo,
			'turnos'=>$turnos,
			'concep_exa_ocup'=>$concep_exa_ocup,
			'grupos'=>$grupos,
			'trabajos_esp'=>$trabajos_esp,
			'centro_costo'=> $centro_costo,
			'salario_flexible'=> $salario_flexible,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ContratoEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ContratoEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ContratoEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='historial-personal-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionTerminacion($e)
	{
		$motivos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_retiro.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato, Id_Unidad_Gerencia, Id_Area, Id_Subarea, Id_Cargo, Id_Centro_Costo, Salario_Flexible FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$e.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

		$id_contrato = $query_contrato['Id_Contrato'];

		$model=$this->loadModel($id_contrato);

		$model->scenario = 'terminacion';

		$id_ug = $query_contrato['Id_Unidad_Gerencia'];
		$id_area = $query_contrato['Id_Area'];
		$id_subarea = $query_contrato['Id_Subarea'];
		$id_cargo = $query_contrato['Id_Cargo'];
		$id_c_costo = $query_contrato['Id_Centro_Costo'];
		$ss = $query_contrato['Salario_Flexible'];

		if(is_null($id_ug)){
			$unidad_gerencia = '-';
		}else{
			$unidad_gerencia = UnidadGerencia::model()->findByPk($id_ug)->Unidad_Gerencia;
		}

		if(is_null($id_area)){
			$area = '-';
		}else{
			$area = Area::model()->findByPk($id_area)->Area;
		}

		if(is_null($id_subarea)){
			$subarea = '-';
		}else{
			$subarea = Subarea::model()->findByPk($id_subarea)->Subarea;
		}

		if(is_null($id_cargo)){
			$cargo = '-';
		}else{
			$cargo = Cargo::model()->findByPk($id_cargo)->Cargo;
		}

		if(is_null($id_c_costo)){
			$centro_costo = '-';
		}else{
			$m_centro_costo = CentroCosto::model()->findByPk($id_c_costo);
			$centro_costo = $m_centro_costo->Codigo.' - '.$m_centro_costo->Centro_Costo;
		}

		if(is_null($ss)){
			$salario_flexible = '-';
		}else{
			$salario_flexible = UtilidadesVarias::textoestado2($ss);
		}

		if(isset($_POST['ContratoEmpleado']))
		{
			$model->attributes=$_POST['ContratoEmpleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				//se desasigna la empresa del empleado
				UtilidadesEmpleado::desasignarempresaempleado($e);
				Yii::app()->user->setFlash('success', "Se actualizo el contrato del empleado correctamente.");	
				$this->redirect(array('empleado/view','id'=>$e));
			}
		}

		$this->render('terminacion',array(
			'model'=>$model,
			'id_contrato'=> $id_contrato,
			'e'=> $e,
			'unidad_gerencia'=>$unidad_gerencia,
			'area'=>$area,
			'subarea'=>$subarea,
			'cargo'=>$cargo,
			'motivos'=> $motivos,
			'centro_costo'=> $centro_costo,
			'salario_flexible'=> $salario_flexible,
		));
	}


	public function actionAsignacion($id, $as, $am, $s)
	{
		$model=$this->loadModel($id);
		$model->scenario = 'asignacion';

		$id_ug = $model->Id_Unidad_Gerencia;
		$id_area = $model->Id_Area;
		$id_subarea = $model->Id_Subarea;
		$id_cargo = $model->Id_Cargo;

		if(is_null($id_ug)){
			$unidad_gerencia = '-';
		}else{
			$unidad_gerencia = UnidadGerencia::model()->findByPk($id_ug)->Unidad_Gerencia;
		}

		if(is_null($id_area)){
			$area = '-';
		}else{
			$area = Area::model()->findByPk($id_area)->Area;
		}

		if(is_null($id_subarea)){
			$subarea = '-';
		}else{
			$subarea = Subarea::model()->findByPk($id_subarea)->Subarea;
		}

		if(is_null($id_cargo)){
			$cargo = '-';
		}else{
			$cargo = Cargo::model()->findByPk($id_cargo)->Cargo;
		}


		if(isset($_POST['ContratoEmpleado']))
		{
			//si viene por post se hace asignacion automatica
			$contrato = ContratoEmpleado::model()->findByPk($id);
			$elementos_sugeridos_asignar = ElementoSugerido::model()->findAllByAttributes(array('Estado' => 1, 'Id_Sugerido' => $s));

			foreach ($elementos_sugeridos_asignar as $elem_asig) {
				$elem_pend_ent = new ElementoEmpleado;
				$elem_pend_ent->Id_A_Elemento = $elem_asig->Id_A_Elemento;
				$elem_pend_ent->Id_Empleado = $contrato->Id_Empleado;
				$elem_pend_ent->Id_Usuario_Creacion = 1;
				$elem_pend_ent->Id_Usuario_Actualizacion = 1;
				$elem_pend_ent->Id_Contrato = $contrato->Id_Contrato;
				$elem_pend_ent->Cantidad = $elem_asig->Cantidad;
				$elem_pend_ent->Estado = 3;
				$elem_pend_ent->Fecha_Creacion = date('Y-m-d H:i:s');
				$elem_pend_ent->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$elem_pend_ent->save();
			}

			Yii::app()->user->setFlash('success', "Se registro la asignación de elementos de acuerdo a sugerido correctamente.");
			$this->redirect(array('empleado/view','id'=>$contrato->Id_Empleado));	

		}

		$this->render('asignacion',array(
			'model'=>$model,
			'id_contrato' => $model->Id_Contrato,
			'e'=>$model->Id_Empleado,
			'unidad_gerencia'=>$unidad_gerencia,
			'area'=>$area,
			'subarea'=>$subarea,
			'cargo'=>$cargo,
			'as'=> $as,
			'am'=> $am,
			's'=> $s,
		));
	}
}

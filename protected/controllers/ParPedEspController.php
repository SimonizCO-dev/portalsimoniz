<?php

class ParPedEspController extends Controller
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
				'actions'=>array('create','searchcliente','getsuccliente','getpuntenvsuccliente','searchitem', 'infoitem','genrepdoc','aprobrech','consulta','aprodoc','rechdoc'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ParPedEsp;	

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ParPedEsp']))
		{

			$user = Yii::app()->user->getState('id_user');

			$model->attributes=$_POST['ParPedEsp'];

			//se obtiene el consecutivo para el siguiente Par. Esp.

			$q_e_cons = Yii::app()->db->createCommand("SELECT TOP 1 Consecutivo FROM T_PR_PAR_PED_ESP ORDER BY Id_Par_Ped_Esp DESC")->queryRow();

			if(!empty($q_e_cons)){
				
				$year = date('y');
				
				$q_ult_cons = Yii::app()->db->createCommand("SELECT TOP 1 Consecutivo FROM  T_PR_PAR_PED_ESP WHERE Consecutivo LIKE '".$year."-%' ORDER BY Id_Par_Ped_Esp DESC")->queryRow();

				if(empty($q_ult_cons)){
					$n_cons = $year.'-0001';
				}else{
					$c = $q_ult_cons['Consecutivo'];
					//se obtiene el numero de la cadena de consecutivo
					$n = substr($c, 4, 5);
					$ns = ((int) $n) + 1;
					//función para rellenar ceros a la izq.
					$na = str_pad((int) $ns,4,"0",STR_PAD_LEFT);
					$n_cons = $year.'-'.$na;
				}
			}else{
				$year = date('y');
				$n_cons = $year.'-0001';
			}

			//datos de cliente

			$info_c = Yii::app()->db->createCommand("
			SET NOCOUNT ON EXEC P_PR_COM_PARAMETRIZACION_PV_ESP_ENC
			@VAR1 = '".$_POST['ParPedEsp']['Nit']."',
			@VAR2 = '".$_POST['ParPedEsp']['Sucursal']."',
			@VAR3 = '".$_POST['ParPedEsp']['Punto_Envio']."'
			")->queryRow();

			if(!empty($info_c)){

				$razon_social = $info_c['razon_social_cliente'];
				$direccion = $info_c['direccion'];
				$ciudad = $info_c['ciudad'];
				$estructura = $info_c['estructura'];
				$ruta = $info_c['ruta'];
				$asesor = $info_c['nombre_asesor'];
				$coordinador = $info_c['nombre_coordinador'];

				$model->Consecutivo = $n_cons;
				$model->Porc_Desc = $_POST['ParPedEsp']['Porc_Desc'];
				$model->Nit = $_POST['ParPedEsp']['Nit'];
				$model->Razon_Social = $razon_social;
				$model->Direccion = $direccion;
				$model->Sucursal = $_POST['ParPedEsp']['desc_sucursal'];
				$model->Punto_Envio = $_POST['ParPedEsp']['desc_punto_envio'];
				$model->Ciudad = $ciudad;
				$model->Fecha = $_POST['ParPedEsp']['Fecha'];
				$model->Estructura = $estructura;
				$model->Ruta = $ruta;
				$model->Asesor = $asesor;
				$model->Coordinador = $coordinador;
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$model->Estado = 1;
				$model->Id_Usuario_Creacion = $user;
				$model->Id_Usuario_Actualizacion = $user;
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Observaciones = $_POST['ParPedEsp']['Observaciones'];

				if($model->save()){

					$array_item = explode("|", $_POST['ParPedEsp']['cad_item']);
					$array_vlr_u = explode("|", $_POST['ParPedEsp']['cad_vu']);
					$array_cant = explode("|", $_POST['ParPedEsp']['cad_cant']);
					$array_iva = explode("|", $_POST['ParPedEsp']['cad_iva']);
					$array_nota = explode("|", $_POST['ParPedEsp']['cad_not']);

					$num_reg = count($array_item);

					for ($i = 0; $i < $num_reg; $i++) {

						$info_i = Yii::app()->db->createCommand("
						SELECT DISTINCT
						t120.f120_id AS codigo,
						t120.f120_descripcion AS descripcion,
						t106_1.f106_descripcion AS marca,
						I_CRI_ORACLE AS Cat_Oracle
						FROM UnoEE1..t120_mc_items AS t120 WITH (NOLOCK) 
						INNER JOIN T_CF_ITEMS ON I_CIA=f120_id_cia AND I_ROWID_ITEM=f120_rowid
						INNER JOIN UnoEE1..t125_mc_items_criterios AS t125_1 WITH (NOLOCK) ON t125_1.f125_id_cia=t120.f120_id_cia AND t125_1.f125_rowid_item=t120.f120_rowid 
						INNER JOIN UnoEE1..t106_mc_criterios_item_mayores AS t106_1 WITH (NOLOCK) ON t106_1.f106_id_cia=t125_1.f125_id_cia AND t106_1.f106_id_plan=t125_1.f125_id_plan AND t106_1.f106_id=t125_1.f125_id_criterio_mayor AND t106_1.f106_id_plan=500 
						where f120_id_cia=2 AND f120_id=".$array_item[$i]
						)->queryRow();

						$nuevo_det = new DetParPedEsp;
						$nuevo_det->Id_Par_Ped_Esp = $model->Id_Par_Ped_Esp;
						$nuevo_det->Codigo = $info_i['codigo'];
						$nuevo_det->Descripcion = $info_i['descripcion'];
						$nuevo_det->Marca = $info_i['marca'];
						$nuevo_det->Cat_Oracle = $info_i['Cat_Oracle'];
						$nuevo_det->Vlr_Unit = $array_vlr_u[$i];
						$nuevo_det->Cant = $array_cant[$i];
						$nuevo_det->Iva = $array_iva[$i];

						if($array_nota[$i] == '-'){
							$nuevo_det->Nota = 'N/A';
						}else{
							$nuevo_det->Nota = $array_nota[$i];	
						}

						$nuevo_det->Id_Usuario_Creacion = $user;
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->save();

					}

	            	Yii::app()->user->setFlash('success', "Se creo el documento (".$model->Consecutivo.") correctamente.");
					$this->redirect(array('parPedEsp/admin'));
				}
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo registrar el docto.");
				$this->redirect(array('parPedEsp/admin'));
			}

		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ParPedEsp('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ParPedEsp']))
			$model->attributes=$_GET['ParPedEsp'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionAprobRech()
	{
		$model=new ParPedEsp('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ParPedEsp']))
			$model->attributes=$_GET['ParPedEsp'];

		$this->render('aprob_rech',array(
			'model'=>$model,
		));
	}

	public function actionConsulta()
	{
		$model=new ParPedEsp('searchparam');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ParPedEsp']))
			$model->attributes=$_GET['ParPedEsp'];

		$this->render('consulta',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ParPedEsp the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ParPedEsp::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ParPedEsp $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='par-ped-esp-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchCliente(){
		$filtro = $_GET['q'];

		$resp = Yii::app()->db->createCommand("
			SELECT TOP 10 C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND (C_NIT_CLIENTE LIKE '".$filtro."%' OR C_NOMBRE_CLIENTE LIKE '".$filtro."%') AND C_ESTADO = 'ACTIVO' GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
		")->queryAll();


		$i = 0;
		$array_cli = array();
		foreach ($resp as $t) {
    		$array_cli[$i] = array('id' => $t['C_NIT_CLIENTE'],  'text' => $t['C_NIT_CLIENTE']." - ".$t['C_NOMBRE_CLIENTE']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_cli);
 	}

 	public function actionGetSucCliente(){
		
 		$nit = $_POST['nit'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT 

			f201_id_sucursal,
			f201_descripcion_sucursal
			FROM UnoEE1..t200_mm_terceros
			INNER JOIN UnoEE1..t201_mm_clientes ON f200_id_Cia=f201_id_cia AND f200_rowid=f201_rowid_tercero
			INNER JOIN UnoEE1..t215_mm_puntos_envio_cliente ON f201_id_cia=f200_id_cia AND f215_rowid_tercero=f201_rowid_tercero AND f201_id_sucursal=f215_id_sucursal
			WHERE f200_id_cia = 2 AND f215_id != '000' AND f200_id = '".$nit."'

		")->queryAll();

		$i = 0;
		$array_suc = array();
		foreach ($resp as $t) {
    		$array_suc[$i] = array('id' => $t['f201_id_sucursal'],  'text' => $t['f201_id_sucursal']." / ".$t['f201_descripcion_sucursal']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_suc);
 	}

 	public function actionGetPuntEnvSucCliente(){
		
		$nit = $_POST['nit'];
		$suc = $_POST['suc'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT 
			f215_id,
			f215_descripcion
			FROM UnoEE1..t200_mm_terceros
			INNER JOIN UnoEE1..t201_mm_clientes ON f200_id_Cia=f201_id_cia AND f200_rowid=f201_rowid_tercero
			INNER JOIN UnoEE1..t215_mm_puntos_envio_cliente ON f201_id_cia=f200_id_cia AND f215_rowid_tercero=f201_rowid_tercero AND f201_id_sucursal=f215_id_sucursal
			WHERE f200_id_cia = 2 AND f215_id != '000' AND f200_id = '".$nit."' AND f201_id_sucursal = '".$suc."'

		")->queryAll();

		$i = 0;
		$array_suc = array();
		foreach ($resp as $t) {
    		$array_suc[$i] = array('id' => $t['f215_id'],  'text' => $t['f215_id']." / ".$t['f215_descripcion']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_suc);

 	}

 	public function actionSearchItem(){
		$filtro = $_GET['q'];
		$nit = $_GET['nit'];
		$suc = $_GET['suc'];
		$pe = $_GET['pe'];

		$resp = Yii::app()->db->createCommand("
		SET NOCOUNT ON EXEC P_PR_COM_PARAMETRIZACION_PV_ESP
		@VAR1 = '".$nit."',
		@VAR2 = '".$suc."',
		@VAR3 = '".$pe."',
		@VAR4 = '".$filtro."'
		")->queryAll();

		$i = 0;
		$array_items = array();
		foreach ($resp as $t) {
    		$array_items[$i] = array('id' => $t['F_CODIGO'],  'text' => $t['F_CODIGO']." - ".$t['F_DESCRIPCION']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_items);
 	}


 	public function actionInfoItem(){
		
		$item = $_POST['item'];
		$cant = $_POST['cant'];
		$nit = $_POST['nit'];
		$suc = $_POST['suc'];
		$pe = $_POST['pe'];
		$desc_adic = $_POST['desc_adic'];

		$resp = Yii::app()->db->createCommand("
		SET NOCOUNT ON EXEC P_PR_COM_PARAMETRIZACION_PV_ESP
		@VAR1 = '".$nit."',
		@VAR2 = '".$suc."',
		@VAR3 = '".$pe."',
		@VAR4 = '".$item."'
		")->queryRow();

		$codigo = $resp['F_CODIGO'];
		$desc = $resp['F_DESCRIPCION'];
		$marca = $resp['F_MARCA'];
		$oracle = $resp['I_CRI_ORACLE'];
		$vlr_unit = floatval($resp['F_PRECIO']);
		$iva = floatval($resp['F_IVA']);

		$array_info = array();

		$array_info['codigo'] = $codigo;
		$array_info['desc'] = $desc;
		$array_info['marca'] = $marca;
		$array_info['oracle'] = $oracle;
		$array_info['vlr_unit'] = $vlr_unit;
        $array_info['iva'] = $iva;
		$array_info['vlr_subtotal'] = $vlr_unit * $cant;

		//se retorna un json con las opciones
		echo json_encode($array_info);

 	}

 	public function actionGenRepDoc($id)
	{
		$this->renderPartial('gen_rep_doc',array('id' => $id));
	}

	public function actionAproDoc($id)
	{
		
		$model=$this->loadModel($id);

		$model->Estado = 2;
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

		if($model->save()){
			Yii::app()->user->setFlash('success', "El Documento (".$model->Consecutivo.") fue aprobado correctamente.");
			$this->redirect(array('aprobrech'));
		}else{
			Yii::app()->user->setFlash('warning', "Error al aprobar el Documento (".$model->Consecutivo.").");
			$this->redirect(array('aprobrech'));	
		}
		
	}

	public function actionRechDoc($id)
	{
		
		$model=$this->loadModel($id);

		$model->Estado = 0;
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

		if($model->save()){
			Yii::app()->user->setFlash('success', "El Documento (".$model->Consecutivo.") fue rechazado correctamente.");
			$this->redirect(array('aprobrech'));
		}else{
			Yii::app()->user->setFlash('warning', "Error al rechazar el Documento (".$model->Consecutivo.").");
			$this->redirect(array('aprobrech'));	
		}
		
	}

}


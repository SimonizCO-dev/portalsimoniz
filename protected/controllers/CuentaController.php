<?php

class CuentaController extends Controller
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
				'actions'=>array('create','update','verificarduplicidad','actred','desred','searchcuentared', 'export', 'exportexcel','eli'),
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
	public function actionView($id)
	{
		
		$model = $this->loadModel($id);
		$pass = substr(base64_decode($model->Password), 5,-5);

		//usuarios asoc. 
		$emp_asoc=new CuentaEmpleado('search');
		$emp_asoc->unsetAttributes();  // clear any default values
		$emp_asoc->Id_Cuenta = $id;

		//novedades. 
		$nov_cue=new NovedadCuenta('search');
		$nov_cue->unsetAttributes();  // clear any default values
		$nov_cue->Id_Cuenta = $id;

		$this->render('view',array(
			'model'=>$model,
			'emp_asoc'=>$emp_asoc,
			'nov_cue'=>$nov_cue,
			'pass'=>$pass,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cuenta;

		$clases= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Estado = 1 AND Id_Padre = '.Yii::app()->params->clase_cuenta.' ORDER BY d.Dominio')->queryAll();

		$dominios= Yii::app()->db->createCommand('SELECT d.Id_Dominio_Web, d.Dominio FROM T_PR_DOMINIO_WEB d WHERE d.Estado = 1 AND Id_Tipo = '.Yii::app()->params->dominios_cuenta_correo.' ORDER BY d.Dominio ')->queryAll();

		$tipos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Estado = 1 AND Id_Padre = '.Yii::app()->params->tipo_correo.' ORDER BY d.Dominio')->queryAll();


		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cuenta']))
		{
			$clasificacion = $_POST['Cuenta']['Clasificacion'];

			if($clasificacion == Yii::app()->params->c_correo){
				$model->Clasificacion = $_POST['Cuenta']['Clasificacion'];
				$model->Tipo_Cuenta = $_POST['Cuenta']['Tipo_Cuenta'];
				$model->Tipo_Acceso = NULL;
				$model->Cuenta_Usuario = trim($_POST['Cuenta']['Cuenta_Usuario']);
				$model->Password = base64_encode(UtilidadesCuenta::generateRandomString().$_POST['Cuenta']['Password'].UtilidadesCuenta::generateRandomString());
				$model->Dominio = $_POST['Cuenta']['Dominio'];
				$model->Estado = Yii::app()->params->estado_act;
				
				if($_POST['Cuenta']['Ext'] == ""){
					$model->Ext = NULL;
				}else{
					$model->Ext = $_POST['Cuenta']['Ext'];
				}

			}else{
				$model->Clasificacion = $_POST['Cuenta']['Clasificacion'];
				$model->Tipo_Cuenta = NULL;
				$model->Tipo_Acceso = $_POST['Cuenta']['Tipo_Acceso'];
				$model->Cuenta_Usuario = trim($_POST['Cuenta']['Cuenta_Usuario']);
				$model->Password = base64_encode(UtilidadesCuenta::generateRandomString().$_POST['Cuenta']['Password'].UtilidadesCuenta::generateRandomString());
				$model->Dominio = $_POST['Cuenta']['Dominio'];
				$model->Estado = Yii::app()->params->estado_act;
				$model->Ext = NULL;
			}

			if($_POST['Cuenta']['Observaciones'] == ""){
				$model->Observaciones = NULL;
			}else{
				$model->Observaciones = $_POST['Cuenta']['Observaciones'];	
			}

			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Cuenta creada correctamente.");
				$this->redirect(array('update','id'=>$model->Id_Cuenta));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'clases'=>$clases,
			'dominios'=>$dominios,
			'tipos'=>$tipos,
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

		//usuarios asoc. 
		$emp_asoc=new CuentaEmpleado('search');
		$emp_asoc->unsetAttributes();  // clear any default values
		$emp_asoc->Id_Cuenta = $model->Id_Cuenta;

		//novedades. 
		$nov_cue=new NovedadCuenta('search');
		$nov_cue->unsetAttributes();  // clear any default values
		$nov_cue->Id_Cuenta = $model->Id_Cuenta;

		$password_act = $model->Password;
		$pass = substr(base64_decode($model->Password), 5,-5);
		$observaciones_act = $model->Observaciones;
		$estado_act = $model->Estado;

		if($model->Clasificacion == Yii::app()->params->c_correo){
			$tipo_cuenta_act = $model->Tipo_Cuenta;
			$tipo_acceso_act = NULL;
			if($model->Tipo_Cuenta == Yii::app()->params->t_c_generico){
				$ext_act = $model->Ext;
			}else{
				$ext_act = NULL;
			}
		
		}else{
			$tipo_cuenta_act = NULL;
			$tipo_acceso_act = $model->Tipo_Acceso;
			$ext_act = NULL;
		}


		$clases= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Estado = 1 AND Id_Padre = '.Yii::app()->params->clase_cuenta.' ORDER BY d.Dominio')->queryAll();

		$dominios= Yii::app()->db->createCommand('SELECT d.Id_Dominio_Web, d.Dominio FROM T_PR_DOMINIO_WEB d WHERE d.Estado = 1 AND Id_Tipo = '.Yii::app()->params->dominios_cuenta_correo.' ORDER BY d.Dominio ')->queryAll();

		$tipos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Estado = 1 AND Id_Padre = '.Yii::app()->params->tipo_correo.' ORDER BY d.Dominio')->queryAll();

		$estados= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Estado = 1 AND Id_Padre = '.Yii::app()->params->estado_cuenta.' AND d.Id_Dominio IN ('.Yii::app()->params->estado_act.','.Yii::app()->params->estado_ina.') ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cuenta']))
		{

			$model->attributes=$_POST['Cuenta'];

			if($_POST['Cuenta']['Password'] != ""){

				if($_POST['Cuenta']['Password'] != $password_act){
					$password = base64_encode(UtilidadesCuenta::generateRandomString().$_POST['Cuenta']['Password'].UtilidadesCuenta::generateRandomString());
					$model->Password = $password;
				}else{
					$model->Password = $password_act;	
				}

			}else{
				
				$model->Password = $password_act;	
			
			}

			if($_POST['Cuenta']['Observaciones'] == ""){
				$model->Observaciones = NULL;
			}else{
				$model->Observaciones = $_POST['Cuenta']['Observaciones'];	
			}

			if($model->Clasificacion == Yii::app()->params->c_correo){
				$model->Tipo_Cuenta = $model->Tipo_Cuenta;
				$model->Tipo_Acceso = NULL;

				if($_POST['Cuenta']['Ext'] == ""){
					$model->Ext = NULL;
				}else{
					$model->Ext = $_POST['Cuenta']['Ext'];
				}

			}else{
				$model->Tipo_Cuenta = NULL;
				$model->Tipo_Acceso = $model->Tipo_Acceso;
				$model->Ext = NULL;
			}


			$model->Estado = $_POST['Cuenta']['Estado'];	
			

			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				
				//función para registrar cambios en las Cuentas
				UtilidadesCuenta::novedadcuenta(
					$id,  
					$password_act, 
					$model->Password,
					$observaciones_act, 
					$model->Observaciones,
					$tipo_cuenta_act,
					$model->Tipo_Cuenta,
					$tipo_acceso_act,
					$model->Tipo_Acceso,
					$estado_act, 
					$model->Estado,
					$ext_act,
					$model->Ext
				);

				Yii::app()->user->setFlash('success', "Cuenta actualizada correctamente.");
				$this->redirect(array('admin'));
			
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'clases'=>$clases,
			'dominios'=>$dominios,
			'tipos'=>$tipos,
			'estados'=>$estados,
			'emp_asoc'=>$emp_asoc,
			'nov_cue'=>$nov_cue,
			'pass'=>$pass,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$model=new Cuenta('search');

		$clases= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->clase_cuenta.' ORDER BY d.Dominio')->queryAll();

		$dominios= Yii::app()->db->createCommand('SELECT d.Id_Dominio_Web, d.Dominio FROM T_PR_DOMINIO_WEB d WHERE Id_Tipo = '.Yii::app()->params->dominios_cuenta_correo.' ORDER BY d.Dominio')->queryAll();

		$tipos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipo_correo.' ORDER BY d.Dominio')->queryAll();

		$estados= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->estado_cuenta.' ORDER BY d.Dominio')->queryAll();
		
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cuenta']))
			$model->attributes=$_GET['Cuenta'];

		$this->render('admin',array(
			'model'=>$model,
			'clases'=>$clases,
			'dominios'=>$dominios,
			'tipos'=>$tipos,
			'estados'=>$estados,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cuenta the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cuenta::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cuenta $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cuenta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionVerificarDuplicidad()
	{
		
		$clase = $_POST['clase'];
		$cuenta_usuario = trim($_POST['cuenta_usuario']);
		$dominio = $_POST['dominio'];

		if($clase == Yii::app()->params->c_correo){
			//CORREO ELECTRONICO
			$q_cuenta = Yii::app()->db->createCommand("SELECT * FROM T_PR_CUENTA WHERE Clasificacion = ".$clase." AND cuenta_usuario = '".$cuenta_usuario."' AND Dominio = ".$dominio)->queryAll();
		}else{
			//DEMAS CUENTAS / USUARIOS
			$q_cuenta = Yii::app()->db->createCommand("SELECT * FROM T_PR_CUENTA WHERE Clasificacion = ".$clase." AND cuenta_usuario = '".$cuenta_usuario."'")->queryAll();	
		}

		if(empty($q_cuenta)){
			echo 1;
		}else{
			echo 0;
		}
	}

	public function actionActRed($id)
	{
		
		$model=$this->loadModel($id);
		$model->scenario = 'actred';

		$password_act = $model->Password;
		$pass = substr(base64_decode($model->Password), 5,-5);

		$cuenta = $model->Cuenta_Usuario.'@'.$model->dominioweb->Dominio;
		

		if(isset($_POST['Cuenta']))
		{

			$model->attributes=$_POST['Cuenta'];

			if($_POST['Cuenta']['Password'] != $password_act){
				$password = base64_encode(UtilidadesCuenta::generateRandomString().$_POST['Cuenta']['Password'].UtilidadesCuenta::generateRandomString());
				$model->Password = $password;
				$novedad = "Password: ".$password_act." / ".$password.", ";
			}else{
				$model->Password = $password_act;
				$novedad = '';	
			}

			$cuenta_red = $model->DescCuentaUsuario($model->Id_Cuenta_Red);

			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = Yii::app()->params->estado_red;
			
			if($model->save()){

				$nueva_novedad = new NovedadCuenta;
				$nueva_novedad->Id_Cuenta = $id;
				$novedad .= 'Correo para redirección: No asignado / '.$cuenta_red.'.';
				$nueva_novedad->Novedades = $novedad;
				$nueva_novedad->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
				if($nueva_novedad->save()){
					Yii::app()->user->setFlash('success', "La cuenta ".$cuenta." fue redireccionada a ".$cuenta_red." correctamente.");
					$this->redirect(array('admin'));
				}

			}else{
				Yii::app()->user->setFlash('warning', "No se pudo redireccionar la cuenta ".$cuenta.".");
				$this->redirect(array('admin'));
			}

		}

		$this->render('red',array(
			'model'=>$model,
			'pass'=>$pass,
		));

	}

	public function actionDesRed($id)
	{
		
		$model=$this->loadModel($id);

		$cuenta = $model->Cuenta_Usuario.'@'.$model->dominioweb->Dominio;
		$cuenta_red = $model->idcuentared->Cuenta_Usuario.'@'.$model->idcuentared->dominioweb->Dominio;
		
		$model->Id_Cuenta_Red = NULL;
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = Yii::app()->params->estado_act;
		
		if($model->save()){

			$nueva_novedad = new NovedadCuenta;
			$nueva_novedad->Id_Cuenta = $id;
			$nueva_novedad->Novedades = 'Correo para redirección: '.$cuenta_red.' / No asignado.';
			$nueva_novedad->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
			if($nueva_novedad->save()){
				$res = 1;
				$msg = "La redirección de la cuenta ".$cuenta." fue eliminada correctamente.";
			}
		}else{
			$res = 0;
			$msg = "No se pudo eliminar la redirección de la cuenta ".$cuenta.".";
		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

	public function actionSearchCuentaRed(){
		$id = $_GET['id'];
		$filtro = $_GET['q'];
        $data = Cuenta::model()->searchByCuentaRed($id, $filtro);
        $result = array();
        foreach($data as $cue):
           $result[] = array(
               'id'   => $cue['Id_Cuenta'],
               'text' => $cue['Cuenta'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionExport(){
    	
    	$model=new Cuenta('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['Cuenta'])) {
	        $model->attributes=$_GET['Cuenta'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('cuenta-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('cuenta-export');	
		$this->renderPartial('cuenta_export_excel',array('data' => $data));	
	}

	public function actionEli($id)
	{
		
		$model=$this->loadModel($id);

		$estado_act = $model->estado->Dominio;
		$cuenta = $model->DescCuentaUsuario($id).' - '.$model->clasificacion->Dominio;
		
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = Yii::app()->params->estado_eli;

		$estado_nue = Dominio::model()->findByPk(Yii::app()->params->estado_eli)->Dominio;
		
		if($model->save()){

			$nueva_novedad = new NovedadCuenta;
			$nueva_novedad->Id_Cuenta = $id;
			$nueva_novedad->Novedades = 'Estado: '.$estado_act.' / '.$estado_nue.'.';
			$nueva_novedad->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
			if($nueva_novedad->save()){
				$res = 1;
				$msg = "La cuenta / usuario (".$cuenta.") fue eliminada correctamente.";
			}

		}else{
			$res = 0;
			$msg = "No se pudo eliminar la cuenta / usuario (".$cuenta.").";
		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);
	}

}

<?php

class TipoTicketController extends Controller
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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','loadopc'),
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
		$model=new TipoTicket;

		$model->Scenario = 'create';

		$grupos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->grupos_act, 'params'=>array(':estado'=>1)));

		$usuarios=Usuario::model()->findAll(array('order'=>'Nombres', 'condition'=>'Estado=1 AND Id_Usuario != 1'));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TipoTicket']))
		{
			$model->attributes=$_POST['TipoTicket'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;
			if($model->save()){
				//se administran los usuarios relacionadas al tipo
				UtilidadesUsuario::admintipticketusuario($model->Id_Tipo, $model->Usuarios);
				Yii::app()->user->setFlash('success', "Configuración creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'grupos'=>$grupos,
			'usuarios'=>$usuarios,
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

		$model->Scenario = 'update';

		$grupos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->grupos_act, 'params'=>array(':estado'=>1)));

		$usuarios=Usuario::model()->findAll(array('order'=>'Nombres', 'condition'=>'Estado=1 AND Id_Usuario != 1'));

		//opciones activas en el combo usuarios
		$json_usuarios_tipticket_activos = UtilidadesUsuario::usuariostipticketactivos($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TipoTicket']))
		{
			$model->attributes=$_POST['TipoTicket'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				//se administran los usuarios relacionadas al tipo
				UtilidadesUsuario::admintipactusuario($model->Id_Tipo, $model->Usuarios);
				Yii::app()->user->setFlash('success', "Configuración actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'grupos'=>$grupos,
			'usuarios'=>$usuarios,
			'json_usuarios_tipticket_activos'=>$json_usuarios_tipticket_activos,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TipoTicket('search');

		$grupos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->grupos_act));
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TipoTicket']))
			$model->attributes=$_GET['TipoTicket'];

		$this->render('admin',array(
			'model'=>$model,
			'grupos'=>$grupos,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TipoTicket the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TipoTicket::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TipoTicket $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tipo-ticket-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionLoadOpc()
	{
		$grupo = $_POST['grupo'];

		$id = $_POST['id'];

		if($id != ""){
			$condicion = "AND Id_Tipo != ".$id;
		}else{
			$condicion = "";
		}
 

		$q_opc = Yii::app()->db->createCommand("SELECT Id_Tipo, Tipo FROM T_PR_TIPO_ACT WHERE  Id_Grupo = ".$grupo." AND Estado = 1 ".$condicion." AND Padre IS NULL ORDER BY Tipo")->queryAll();

		$i = 0;
		$array_opc = array();
		
		foreach ($q_opc as $c) {
			$array_opc[$i] = array('id' => trim($c['Id_Tipo']),  'text' => $c['Tipo']);
			$i++; 

		}
		
		//se retorna un json con las opciones
		echo json_encode($array_opc);

	}
}

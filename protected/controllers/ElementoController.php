<?php

class ElementoController extends Controller
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
				'actions'=>array('create','update', 'getelementossugerido', 'getelementos','getelementospendentempleado','getelementosentempleado'),
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
		$model=new Elemento;
		$model->scenario = 'create';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Elemento']))
		{
			$model->attributes=$_POST['Elemento'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Elemento creado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			//'areas'=>$m_areas,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Elemento']))
		{
			$model->attributes=$_POST['Elemento'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Elemento actualizado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Elemento('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Elemento']))
			$model->attributes=$_GET['Elemento'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Elemento the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Elemento::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Elemento $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='elemento-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGetElementosSugerido()
	{
		$id_contrato = $_POST['id_contrato'];
		$id_area = $_POST['id_area'];
		$id_subarea = $_POST['id_subarea'];
		$id_cargo = $_POST['id_cargo'];

	
		$opc_sugeridos = UtilidadesElemento::getelementossugerido($id_contrato, $id_area, $id_subarea, $id_cargo);
		echo $opc_sugeridos;
	}

	public function actionGetElementos()
	{
		$id_contrato = $_POST['id_contrato'];
		$id_area = $_POST['id_area'];
		$id_subarea = $_POST['id_subarea'];
		$id_cargo = $_POST['id_cargo'];

		$opc_elementos = UtilidadesElemento::getelementos($id_contrato, $id_area, $id_subarea, $id_cargo);
		echo $opc_elementos;
	}



	public function actionGetElementosPendEntEmpleado()
	{
		$id_contrato = $_POST['id_contrato'];

		$opc_elementos = UtilidadesElemento::getelementospendentempleado($id_contrato);
		echo $opc_elementos;
	}

	

	public function actionGetElementosEntEmpleado()
	{
		$id_contrato = $_POST['id_contrato'];

		$opc_elementos = UtilidadesElemento::getelementosentempleado($id_contrato);
		echo $opc_elementos;
	}
}

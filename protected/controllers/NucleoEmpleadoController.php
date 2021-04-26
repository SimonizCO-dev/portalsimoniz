<?php

class NucleoEmpleadoController extends Controller
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
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($e)
	{
		$model=new NucleoEmpleado;

		$generos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->genero.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$parentescos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->parentesco.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NucleoEmpleado']))
		{
			$model->attributes=$_POST['NucleoEmpleado'];
			$model->Id_Empleado = $e;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se actualizo el nucleo familiar del empleado correctamente.");
				$this->redirect(array('empleado/view','id'=>$e));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'e'=>$e,
			'generos' => $generos,
			'parentescos' => $parentescos,
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

		$genero_act = $model->Id_Genero;
		$parentesco_act = $model->Id_Parentesco;

		if(!is_null($genero_act)){
			$generos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->genero.' AND Estado = 1 OR Id_Dominio = '.$genero_act.' ORDER BY d.Dominio')->queryAll();
		}else{
			$generos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->genero.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();	
		}

		if(!is_null($parentesco_act)){
			$parentescos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->parentesco.' AND Estado = 1 OR Id_Dominio = '.$parentesco_act.' ORDER BY d.Dominio')->queryAll();
		}else{
			$parentescos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->parentesco.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();	
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['NucleoEmpleado']))
		{
			$model->attributes=$_POST['NucleoEmpleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se actualizo el nucleo familiar del empleado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'e'=>$model->Id_Empleado,
			'generos' => $generos,
			'parentescos' => $parentescos,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return NucleoEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=NucleoEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param NucleoEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='nucleo-empleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

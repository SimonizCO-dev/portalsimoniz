<?php

class DominioController extends Controller
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
		$model=new Dominio;

		$opciones_p= Yii::app()->db->createCommand('
		    SELECT d.Id_Dominio, d.Dominio 
		    FROM T_PR_DOMINIO d
		    WHERE Id_Padre = 1
		    GROUP BY d.Id_Dominio, d.Dominio ORDER BY d.Dominio
		')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dominio']))
		{
			$model->attributes=$_POST['Dominio'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Dominio creado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'opciones_p'=>$opciones_p,
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

		$opciones_p= Yii::app()->db->createCommand('
		    SELECT d.Id_Dominio, d.Dominio 
		    FROM T_PR_DOMINIO d
		    WHERE Id_Padre = 1
		    GROUP BY d.Id_Dominio, d.Dominio ORDER BY d.Dominio
		')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Dominio']))
		{
			$model->attributes=$_POST['Dominio'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Dominio actualizado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'opciones_p'=>$opciones_p,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Dominio('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$opciones_p= Yii::app()->db->createCommand('
		    SELECT d.Id_Dominio, d.Dominio 
		    FROM T_PR_DOMINIO d
		    WHERE EXISTS (SELECT COUNT(*) FROM T_PR_DOMINIO sd WHERE sd.Id_Padre = d.Id_Dominio HAVING COUNT(*) > 0)
		    GROUP BY d.Id_Dominio, d.Dominio ORDER BY d.Dominio
		')->queryAll();

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dominio']))
			$model->attributes=$_GET['Dominio'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'opciones_p'=>$opciones_p,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Dominio the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Dominio::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Dominio $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dominio-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

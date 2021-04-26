<?php

class UpdThController extends Controller
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
				'actions'=>array('update'),
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
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario = 'update';

		$usuarios=Usuario::model()->findAll(array('order'=>'Nombres', 'condition'=>'Estado=:estado AND Id_Usuario != 1', 'params'=>array(':estado'=>1)));

		//opciones activas en el combo usuarios
		$json_usuarios_activos = UtilidadesUsuario::usuariosactivosupdth($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UpdTh']))
		{
			$model->attributes=$_POST['UpdTh'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->validate() && $model->save()){	
				//se administran los usuarios relacionadas al permiso
				UtilidadesUsuario::adminusuarioupdth($id, $model->usuarios);
				Yii::app()->user->setFlash('success', "Usuarios actualizados correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'json_usuarios_activos'=>$json_usuarios_activos,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UpdTh('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UpdTh']))
			$model->attributes=$_GET['UpdTh'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UpdTh the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UpdTh::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UpdTh $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='upd-th-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

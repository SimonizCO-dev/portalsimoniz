<?php

class FichaItemUsuarioController extends Controller
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario', 'condition'=>'Estado=1 AND Id_Usuario != 1'));

		$array_user_reg = array();
		//opciones activas en el combo usuarios de registro
		$a_user_reg =  explode(",", $model->Id_Users_Reg);
		foreach ($a_user_reg as $ur => $id) {
			array_push($array_user_reg, $id);
		}

		$user_reg = json_encode($array_user_reg);

		$array_user_not = array();
		//opciones activas en el combo usuarios de notif.
		$a_user_not =  explode(",", $model->Id_Users_Notif);
		foreach ($a_user_not as $un => $id) {
			array_push($array_user_not, $id);
		}

		$user_not = json_encode($array_user_not);

		if(isset($_POST['FichaItemUsuario']))
		{
			$model->attributes=$_POST['FichaItemUsuario'];
			$model->Id_Users_Reg = implode(",", $_POST['FichaItemUsuario']['Id_Users_Reg']);
			$model->Id_Users_Notif = implode(",", $_POST['FichaItemUsuario']['Id_Users_Notif']);
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){	
				Yii::app()->user->setFlash('success', "Configuración actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'user_reg'=>$user_reg,
			'user_not'=>$user_not,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FichaItemUsuario('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FichaItemUsuario']))
			$model->attributes=$_GET['FichaItemUsuario'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FichaItemUsuario the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FichaItemUsuario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FichaItemUsuario $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ficha-item-usuario-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		if(!Yii::app()->user->isGuest) {
			$this->redirect(array('site/info'));	
		}else{
			$this->redirect(array('site/login'));		
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		
		if(!Yii::app()->user->isGuest) {
			$this->redirect(array('site/info'));	
		}else{
			$model=new LoginForm;
			$model->Scenario = 'login';

			// if it is ajax validation request
			if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}

			// collect user input data
			if(isset($_POST['LoginForm']))
			{
				$model->attributes=$_POST['LoginForm'];
				// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login()){

					//LOG
					$log = New Log;
			        $log->Tipo = 1;
			        $log->Accion = 'LOGIN';
			        $log->Id_Usuario = Yii::app()->user->getState('id_user');
			        $log->Fecha_Hora = date('Y-m-d H:i:s');
			        $log->save();

					$this->redirect(Yii::app()->user->returnUrl);
				}

			}
			// display the login form
			$this->render('login',array('model'=>$model));		
		}

	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		//LOG
		$log = New Log;
        $log->Tipo = 1;
        $log->Accion = 'LOGOUT';
        $log->Id_Usuario = Yii::app()->user->getState('id_user');
        $log->Fecha_Hora = date('Y-m-d H:i:s');
        $log->save();

		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionAyuda()
	{
		if(!Yii::app()->user->isGuest) {
			$this->render('ayuda');		
		}else{
			$this->redirect(array('site/login'));		
		}	
	}

	public function actionInfo()
	{
		if(!Yii::app()->user->isGuest) {
			$this->render('info');		
		}else{
			$this->redirect(array('site/login'));		
		}	
	}

	public function actionLog()
	{
		if(!Yii::app()->user->isGuest) {
			
			$id_menu = $_POST['id_menu'];

			//LOG
			$log = New Log;
			$log->Tipo = 2;
			$log->Id_Menu = $id_menu;
			$log->Id_Usuario = Yii::app()->user->getState('id_user');
			$log->Fecha_Hora = date('Y-m-d H:i:s');
			$log->save();

		}else{
			$this->redirect(array('site/login'));		
		}	
	}
}
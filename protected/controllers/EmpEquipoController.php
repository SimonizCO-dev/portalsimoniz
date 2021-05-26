<?php

class EmpEquipoController extends Controller
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
				'actions'=>array('create','inact'),
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
	public function actionCreate($e)
	{
		$model=new EmpEquipo;

		if(isset($_POST['EmpEquipo']))
		{
			$model->attributes=$_POST['EmpEquipo'];
			$model->Id_Equipo = $e;
			$model->Estado = 1;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			
			if($model->save()){

				$desc_Emp = UtilidadesEmpleado::nombreempleado($model->Id_Emp);

				Yii::app()->user->setFlash('success', "Se vinculo empleado ".$desc_Emp." correctamente.");
				$this->redirect(array('equipo/view','id'=>$e));
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo vincular el empleado ".$desc_Emp.".");
				$this->redirect(array('equipo/view','id'=>$e));
			}

		}

		$this->render('create',array(
			'model'=>$model,
			'e'=>$e,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new EmpEquipo('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EmpEquipo']))
			$model->attributes=$_GET['EmpEquipo'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return EmpEquipo the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=EmpEquipo::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param EmpEquipo $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='emp-equipo-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionInact($id)
	{
		
		$model=$this->loadModel($id);

        $desc_Emp = UtilidadesEmpleado::nombreempleado($model->Id_Emp);
		$desc_equipo = UtilidadesVarias::descequipo($model->Id_Equipo);

		
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 0;
		
		if($model->save()){
			//por equipo
			Yii::app()->user->setFlash('success', "Se desvinculo la licencia ".$desc_licencia." correctamente.");
			$this->redirect(array('equipo/view','id'=>$model->Id_Equipo));	
		}else{
			//por equipo
			Yii::app()->user->setFlash('warning', "no se pudo desvincular la licencia ".$desc_licencia.".");
			$this->redirect(array('equipo/view','id'=>$model->Id_Equipo));
	
		}

	}
}

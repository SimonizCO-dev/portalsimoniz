<?php

class AreaElementoDotController extends Controller
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
				'actions'=>array('create'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		


		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new AreaElementoDot;

		$elem_area = Yii::app()->db->createCommand("
		SELECT AE.Id_A_elemento AS id, E.Elemento as elemento, S.Subarea as subarea, A.Area as area 
		FROM T_PR_AREA_ELEMENTO AE
		LEFT JOIN T_PR_ELEMENTO E ON e.Id_Elemento = AE.Id_Elemento
		LEFT JOIN T_PR_SUBAREA S ON S.Id_Subarea = AE.Id_Subarea
		LEFT JOIN T_PR_AREA A ON A.Id_Area = AE.Id_Area
		WHERE AE.Estado = 1 AND Id_A_elemento NOT IN (SELECT Id_A_Elemento FROM T_PR_AREA_ELEMENTO_DOT)
		ORDER BY 2,3,4
		")->queryAll();

		$lista_ea = array();
		foreach ($elem_area as $ea) {

			if($ea['subarea'] == ""){
				$subarea = "NO ASIGNADO";
			}else{
				$subarea = $ea['subarea'];
			}

			if($ea['area'] == ""){
				$area = "NO ASIGNADO";
			}else{
				$area = $ea['area'];
			}


			$lista_ea[$ea['id']] = $ea['elemento'].' ('.$subarea.' / '.$area.')';
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AreaElementoDot']))
		{
			$model->attributes=$_POST['AreaElementoDot'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Elemento asociado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'lista_ea'=>$lista_ea,
		));
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AreaElementoDot('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['AreaElementoDot']))
			$model->attributes=$_GET['AreaElementoDot'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AreaElementoDot the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AreaElementoDot::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AreaElementoDot $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-elemento-dot-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

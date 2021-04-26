<?php

class IExistenciaController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('view','admin','export', 'exportexcel', 'listinvfis'),
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{

		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$model=new IExistencia('search');
		
		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));
		$bodegas=IBodega::model()->findAll(array('order'=>'Descripcion'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['IExistencia']))
			$model->attributes=$_GET['IExistencia'];

		$this->render('admin',array(
			'model'=>$model,
			'lineas'=>$lineas,
			'bodegas'=>$bodegas,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IExistencia the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IExistencia::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IExistencia $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='iexistencia-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExport(){
    	
    	$model=new IExistencia('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['IExistencia'])) {
	        $model->attributes=$_GET['IExistencia'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('existencias-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('existencias-export');
		$this->renderPartial('existencias_export_excel',array('data' => $data));	
	}

	public function actionListInvFis()
	{		
		$model=new IExistencia;
		$model->scenario = 'list_inv_fis';

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		if(isset($_POST['IExistencia']))
		{
			$model=$_POST['IExistencia'];
			$this->renderPartial('list_inv_fis_resp',array('model' => $model));	
		}

		$this->render('list_inv_fis',array(
			'model'=>$model,
			'lineas'=>$lineas,
		));
	}

}

<?php

class GdAudDocumentoController extends Controller
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
				'actions'=>array('admin','export','exportexcel'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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

		$model=new GdAudDocumento('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion'));
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GdAudDocumento']))
			$model->attributes=$_GET['GdAudDocumento'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'tipos'=>$tipos,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GdAudDocumento the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GdAudDocumento::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AudDocumento $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='gd-aud-documento-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

 	public function actionExport(){
    	
    	$model=new GdAudDocumento('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['GdAudDocumento'])) {
	        $model->attributes=$_GET['GdAudDocumento'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('aud-documento-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('aud-documento-export');
		$this->renderPartial('aud_documento_export_excel',array('data' => $data));	
	}
}

<?php

class CPtjCumpController extends Controller
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
				'actions'=>array('create','offconfig','verifconfig'),
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
		$model=new CPtjCump;

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CPtjCump']))
		{
			$model->attributes=$_POST['CPtjCump'];
			$model->ESTADO = 1;
			$model->ID_USUARIO_CREACION = Yii::app()->user->getState('id_user');
			$model->FECHA_CREACION = date('Y-m-d H:i:s');
			$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
			$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
			if($model->save())
				Yii::app()->user->setFlash('success', "Porcentaje creado correctamente.");
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
			'tipos'=>$tipos,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model=new CPtjCump('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CPtjCump']))
			$model->attributes=$_GET['CPtjCump'];

		$this->render('admin',array(
			'model'=>$model,
			'tipos'=>$tipos,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CPtjCump the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CPtjCump::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CPtjCump $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cptj-cump-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionOffConfig($id)
	{
		$model=$this->loadModel($id);

		$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
		$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
		$model->ESTADO = 0;
		if($model->save()){
			Yii::app()->user->setFlash('success', "El porcentaje de cumplimiento ".$model->ROWID." fue inactivado correctamente.");
			$this->redirect(array('admin'));
		}
	}

	public function actionVerifConfig()
	{
		$data = array();

		$tipo = $_POST['tipo'];
		$cum_inicial = $_POST['cum_inicial'];
		$cum_final = $_POST['cum_final'];

		$q = Yii::app()->db->createCommand("SELECT ROWID FROM T_PR_C_PTJ_CUMPLIMIENTO WHERE TIPO = ".$tipo." AND (('".$cum_inicial."' BETWEEN CUM_INICIAL AND CUM_FINAL) OR ('".$cum_final."' BETWEEN CUM_INICIAL AND CUM_FINAL) OR ('".$cum_inicial."' < CUM_INICIAL AND '".$cum_final."' > CUM_FINAL)) AND ESTADO = 1")->queryRow();

     
        $id = (!empty($q)?$q['ROWID']:null);

		
        if(!is_null($id)){
        	$valid = 0;
        	$id_row = $id;
        }else{
        	$valid = 1;
        	$id_row = 0;
        }

        $data['valid'] = $valid;
		$data['id'] = $id_row;

		echo json_encode($data);
	
	
	}
}

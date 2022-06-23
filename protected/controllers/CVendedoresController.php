<?php

class CVendedoresController extends Controller
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
				'actions'=>array('update', 'update2', 'export', 'exportexcel'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'admin2'),
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

		$model->Scenario = 'update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['CVendedores']))
		{
			$model->attributes=$_POST['CVendedores'];
			$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
			$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Vendedor actualizado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'tipos'=>$tipos,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate2($id)
	{
		$model=$this->loadModel($id);

		$model->Scenario = 'update2';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['CVendedores']))
		{
			$model->attributes=$_POST['CVendedores'];
			$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
			$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Vendedor actualizado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		$this->render('update2',array(
			'model'=>$model,
			'tipos'=>$tipos,
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

		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model=new CVendedores('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CVendedores']))
			$model->attributes=$_GET['CVendedores'];

		$this->render('admin',array(
			'model'=>$model,
			'tipos' => $tipos,
			'usuarios'=>$usuarios,	
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin2()
	{

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model=new CVendedores('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CVendedores']))
			$model->attributes=$_GET['CVendedores'];

		$this->render('admin2',array(
			'model'=>$model,
			'tipos' => $tipos,
			'usuarios'=>$usuarios,	
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CVendedores the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CVendedores::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CVendedores $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cvendedores-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExport(){
    	
		
    	$model=new CVendedores('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['CVendedores'])) {
	        $model->attributes=$_GET['CVendedores'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('cvendedores-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('cvendedores-export');

		$this->renderPartial('cvendedores_export_excel',array('data' => $data));	
	}
}

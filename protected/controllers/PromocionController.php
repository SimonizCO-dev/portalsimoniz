<?php

class PromocionController extends Controller
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
				'actions'=>array('create','update', 'export', 'exportexcel','evaluarexistencia'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Promocion;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Promocion']))
		{
			
			$model->attributes=$_POST['Promocion'];
			$comp = explode(",", $_POST['Promocion']['Id_Item_Hijo']);
			$cant = explode(",", $_POST['Promocion']['Cantidad']);

			foreach ($comp as $key => $valor) {
				$nuevo_comp_prom = new Promocion;
				$nuevo_comp_prom->Id_Item_Padre = $model->Id_Item_Padre;
				$nuevo_comp_prom->Id_Item_Hijo = $valor;
				$nuevo_comp_prom->Cantidad = $cant[$key];
				$nuevo_comp_prom->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nuevo_comp_prom->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nuevo_comp_prom->Fecha_Creacion = date('Y-m-d H:i:s');
				$nuevo_comp_prom->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nuevo_comp_prom->save();
			}

			Yii::app()->user->setFlash('success', "La Promoción ".Reporte::DescItem($model->Id_Item_Padre)." fue creada correctamente.");
			$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Promocion']))
		{
			$model->attributes=$_POST['Promocion'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El componente de la promoción ".Reporte::DescItem($model->Id_Item_Padre)." fue actualizado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
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

		$model=new Promocion('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));


		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Promocion']))
			$model->attributes=$_GET['Promocion'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Promocion the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Promocion::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Promocion $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='promocion-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExport(){
    	
    	$model=new Promocion('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['Promocion'])) {
	        $model->attributes=$_GET['Promocion'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('promocion-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('promocion-export');
		$this->renderPartial('promocion_export_excel',array('data' => $data));	
	}

	public function actionEvaluarExistencia()
	{
		$id_prom = $_POST['promocion'];
		$id_comp = $_POST['id_comp'];

		$model_componente = Promocion::model()->findByAttributes(array('Id_Item_Padre' => $id_prom, 'Id_Item_Hijo' => $id_comp));

		if(empty($model_componente)){
			echo 0;
		}else{
			echo 1;
		}
	}
}

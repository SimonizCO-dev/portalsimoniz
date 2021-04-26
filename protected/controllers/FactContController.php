<?php

class FactContController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'export', 'exportexcel','recibir','rechazar','anular','revertir'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','admin2','admin3'),
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
	public function actionView($id,$opc)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'opc'=>$opc,
		));
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new FactCont;

		$areas = Area::model()->findAll(array('condition'=>'Estado = 1', 'order'=>'Area'));

		$opc = 0;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FactCont']))
		{
			$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
			$model->attributes=$_POST['FactCont'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;

			if($_FILES['FactCont']['name']['sop']  != "") {

		        $documento_subido = CUploadedFile::getInstance($model,'sop');
	            $nombre_archivo = "{$rnd}-{$documento_subido}";
            	$model->Doc_Soporte = $nombre_archivo;
	            $opc = 1;
		    }

			if($model->save()){	

				if($opc == 1){
                	$documento_subido->saveAs(Yii::app()->basePath.'/../files/portal_Reportes/fact_cont/'.$nombre_archivo);	
            	}

            	Yii::app()->user->setFlash('success', "La factura (".$model->Num_Factura.") fue cargada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'areas'=>$areas,
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

		$areas = Area::model()->findAll(array('condition'=>'Estado = 1', 'order'=>'Area'));

		$opc = 0;

		$doc_actual = $model->Doc_Soporte;
		$ruta_doc_actual = Yii::app()->basePath.'/../files/portal_Reportes/fact_cont/'.$model->Doc_Soporte;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FactCont']))
		{
			$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
			$model->attributes=$_POST['FactCont'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($_FILES['FactCont']['name']['sop']  != "") {

		        $documento_subido = CUploadedFile::getInstance($model,'sop');
	            $nombre_archivo = "{$rnd}-{$documento_subido}";
            	$model->Doc_Soporte = $nombre_archivo;
	            $opc = 1;
		    }

			if($model->save()){

				if($opc == 1){
        			if ($doc_actual != "") {
            			unlink($ruta_doc_actual);
            		}
                	$documento_subido->saveAs(Yii::app()->basePath.'/../files/portal_Reportes/fact_cont/'.$nombre_archivo);
            	}

				Yii::app()->user->setFlash('success', "La factura (".$model->Num_Factura.") fue actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'areas'=>$areas,
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

		$model=new FactCont('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$areas = Area::model()->findAll(array('order'=>'Area'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FactCont']))
			$model->attributes=$_GET['FactCont'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'areas'=>$areas,
		));
	}

	public function actionAdmin2()
	{
		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$model=new FactCont('search2');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$lista_areas = UtilidadesUsuario::listaareasusuario();

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FactCont']))
			$model->attributes=$_GET['FactCont'];

		$this->render('admin2',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'lista_areas'=>$lista_areas,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin3()
	{
		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$model=new FactCont('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$areas = Area::model()->findAll(array('order'=>'Area'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FactCont']))
			$model->attributes=$_GET['FactCont'];

		$this->render('admin3',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'areas'=>$areas,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FactCont the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FactCont::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FactCont $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='fact-pend-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExport(){
    	
    	$model=new FactCont('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['FactCont'])) {
	        $model->attributes=$_GET['FactCont'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('fact-cont-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('fact-cont-export');
		$this->renderPartial('fact_cont_export_excel',array('data' => $data));	
	}

	public function actionRecibir($id)
	{
		
		$model=$this->loadModel($id);
		$model->Id_Usuario_Revision = Yii::app()->user->getState('id_user');
		$model->Fecha_Revision = date('Y-m-d H:i:s');
		$model->Estado = 2;
		
		if($model->save()){

			$res = 1;
			$msg = "La factura # ".$model->Num_Factura." fue recibida correctamente.";
			
		}else{

			$res = 0;
			$msg = "Error al recibir la factura # ".$model->Num_Factura.".";
			

		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

	public function actionRechazar($id)
	{
		
		$model=$this->loadModel($id);
		$model->Id_Usuario_Revision = Yii::app()->user->getState('id_user');
		$model->Fecha_Revision = date('Y-m-d H:i:s');
		$model->Estado = 3;
		
		if($model->save()){

			$res = 1;
			$msg = "La factura # ".$model->Num_Factura." fue rechazada correctamente.";
			
		}else{

			$res = 0;
			$msg = "Error al rechazar la factura # ".$model->Num_Factura.".";
			

		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

	public function actionAnular($id)
	{
		
		$model=$this->loadModel($id);
		$model->Id_Usuario_Revision = Yii::app()->user->getState('id_user');
		$model->Fecha_Revision = date('Y-m-d H:i:s');
		$model->Estado = 0;
		
		if($model->save()){

			$res = 1;
			$msg = "La factura # ".$model->Num_Factura." fue anulada correctamente.";
			
		}else{

			$res = 0;
			$msg = "Error al anular la factura # ".$model->Num_Factura.".";
			

		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

	public function actionRevertir($id)
	{
		
		$model=$this->loadModel($id);
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Id_Usuario_Revision = NULL;
		$model->Fecha_Revision = NULL;
		$model->Estado = 1;
		
		if($model->save()){

			$res = 1;
			$msg = "La estado de la factura # ".$model->Num_Factura." fue revertido correctamente.";
			
		}else{

			$res = 0;
			$msg = "Error al revertir el estado de la factura # ".$model->Num_Factura.".";
			

		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

}

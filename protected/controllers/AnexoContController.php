<?php

class AnexoContController extends Controller
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
				'actions'=>array('create','update'),
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
	public function actionCreate($c)
	{

		$model=new AnexoCont;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AnexoCont']))
		{
			$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
			$model->attributes=$_POST['AnexoCont'];
 			$model->Id_Contrato = $c;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

            $documento_subido = CUploadedFile::getInstance($model,'sop');
            $nombre_archivo = "{$rnd}-{$documento_subido}";
            $model->Doc_Soporte = $nombre_archivo;
 
            if($model->save()){
                $documento_subido->saveAs(Yii::app()->basePath.'/../files/panel_adm/docs_contratos/'.$nombre_archivo);
            	Yii::app()->user->setFlash('success', "Se cargo el anexo correctamente.");
				$this->redirect(array('cont/view','id'=>$c));
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo cargar el anexo.");
				$this->redirect(array('cont/view','id'=>$c));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'c'=>$c,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$opc = 0;

		$model=$this->loadModel($id);

		$ruta_doc_actual = Yii::app()->basePath.'/../files/panel_adm/docs_contratos/'.$model->Doc_Soporte;
		$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AnexoCont']))
		{
			
			if($_FILES['AnexoCont']['name']['sop']  != "") {

		        $documento_subido = CUploadedFile::getInstance($model,'sop');
	            $nombre_archivo = "{$rnd}-{$documento_subido}";
            	$model->Doc_Soporte = $nombre_archivo;
	            $opc = 1;
		    } 

			$model->attributes=$_POST['AnexoCont'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			

			if($model->save()){
				if($opc == 1){
            		unlink($ruta_doc_actual);
                	$documento_subido->saveAs(Yii::app()->basePath.'/../files/panel_adm/docs_contratos/'.$nombre_archivo);
            	}
            	Yii::app()->user->setFlash('success', "Se actualizo el anexo correctamente.");
				$this->redirect(array('cont/view','id'=>$model->Id_Contrato));
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo actualizar el anexo.");
				$this->redirect(array('cont/view','id'=>$model->Id_Contrato));
			}

		}
		
		$this->render('update',array(
			'model'=>$model,
			'c'=>$model->Id_Contrato,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AnexoCont the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AnexoCont::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AnexoCont $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='anexo-cont-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

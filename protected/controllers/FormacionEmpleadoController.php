<?php

class FormacionEmpleadoController extends Controller
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
	public function actionCreate($e)
	{
		$opc = 0;

		$model=new FormacionEmpleado;

		$model->scenario = 'create';

		$niveles= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->nivel_estudio.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FormacionEmpleado']))
		{
			$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
            $model->attributes=$_POST['FormacionEmpleado'];
 			
            $model->Id_Empleado = $e;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

            if($_FILES['FormacionEmpleado']['name']['Soporte']  != "") {
		    	$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
		        $soporte_subido = CUploadedFile::getInstance($model,'Soporte');
	            $nombre_archivo = "{$model->Id_Empleado}-{$rnd}-{$soporte_subido}"; 
	            $model->Soporte = $nombre_archivo;
	            $opc = 1;
		    } 
 
            if($model->save()){
        		if($opc == 1){
                	$soporte_subido->saveAs(Yii::app()->basePath.'/../files/talento_humano/cert_estudios_emp/'.$nombre_archivo);
            	}
            	Yii::app()->user->setFlash('success', "Se actualizo la formación del empleado correctamente.");
                $this->redirect(array('empleado/view','id'=>$e));
            }
		}

		$this->render('create',array(
			'model'=>$model,
			'e'=>$e,
			'niveles' => $niveles,
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

		if($model->Soporte != ""){
			$del_sop = 1;
		}else{
			$del_sop = 0;
		}

		$nivel_act = $model->Id_Nivel;

		$ruta_soporte_act = Yii::app()->basePath.'/../files/talento_humano/cert_estudios_emp/'.$model->Soporte;

		$model->scenario = 'update';

		if(!is_null($nivel_act)){
			$niveles= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->nivel_estudio.' AND Estado = 1 OR Id_Dominio = '.$nivel_act.' ORDER BY d.Dominio')->queryAll();
		}else{
			$niveles= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->nivel_estudio.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FormacionEmpleado']))
		{
			$model->attributes=$_POST['FormacionEmpleado']; 

		    if($_FILES['FormacionEmpleado']['name']['Soporte']  != "") {
		    	$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
		        $soporte_subido = CUploadedFile::getInstance($model,'Soporte');
	            $nombre_archivo = "{$model->Id_Empleado}-{$rnd}-{$soporte_subido}"; 
	            $model->Soporte = $nombre_archivo;
	            $opc = 1;
		    } 

			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
 
            if($model->save()){
            	if($opc == 1){
            		if($del_sop == 1){
        				unlink($ruta_soporte_act);
        			}
                	$soporte_subido->saveAs(Yii::app()->basePath.'/../files/talento_humano/cert_estudios_emp/'.$nombre_archivo);
            	}

            	if($_FILES['FormacionEmpleado']['name']['Soporte']  == "" && $model->Soporte == "") {
            		$model->Soporte = null;
            		$model->save();
            	}

            	Yii::app()->user->setFlash('success', "Se actualizo la formación del empleado correctamente.");
                $this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
            }

		}

		$this->render('update',array(
			'model'=>$model,
			'e'=>$model->Id_Empleado,
			'niveles' => $niveles,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FormacionEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FormacionEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FormacionEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='formacion-empleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

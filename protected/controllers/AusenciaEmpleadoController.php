<?php

class AusenciaEmpleadoController extends Controller
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
				'actions'=>array('create','update','infoausenciacre','infoausenciaact'),
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
		$model=new AusenciaEmpleado;

		$motivos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_ausencia.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		//se envia a funcion para saber cual es la fecha de minima para la ausencia
		$fecha_min = UtilidadesContrato::fechaminausdis($e);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AusenciaEmpleado']))
		{
			$model->attributes=$_POST['AusenciaEmpleado'];
			$model->Id_Empleado = $e;
			$model->Id_Contrato = UtilidadesEmpleado::contratoactualempleado($e);
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Ausencia asociada correctamente.");
				$this->redirect(array('empleado/view','id'=>$e));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'motivos'=>$motivos,
			'e'=>$e,
			'fecha_min'=>$fecha_min,
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

		$motivo_act = $model->Id_M_Ausencia;

		if(!is_null($motivo_act)){
			$motivos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_ausencia.' AND (Estado = 1 OR Id_Dominio = '.$motivo_act.') ORDER BY d.Dominio')->queryAll();
		}else{
			$motivos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_ausencia.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();
		}

		//se envia a funcion para saber cual es la fecha de minima para el disciplinario
		$fecha_min = UtilidadesContrato::fechaminausdis($model->Id_Empleado);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AusenciaEmpleado']))
		{
			$model->attributes=$_POST['AusenciaEmpleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Ausencia actualizada correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'motivos'=>$motivos,
			'e'=>$model->Id_Empleado,
			'fecha_min'=>$fecha_min,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AusenciaEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AusenciaEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AusenciaEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='AusenciaEmpleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionInfoAusenciaCre()
	{
		$fecha_inicial = $_POST['fecha_inicial'];
		$empleado = $_POST['empleado'];

		$criteria=new CDbCriteria;
		$criteria->condition='Fecha_Inicial=:Fecha_Inicial AND Id_Empleado=:Id_Empleado';
		$criteria->params=array(':Fecha_Inicial'=>$fecha_inicial, ':Id_Empleado' => $empleado);
		$ausencias=AusenciaEmpleado::model()->findAll($criteria);

		if(empty($ausencias)){
			$opc = 0;
			$mensaje = "";	
		}else{
			$opc = 1;
			$count = count ($ausencias);
			
			if($count == 1){
				$texto_motivo = 'Motivo (';
			}else{
				$texto_motivo = 'Motivos (';	
			}

			foreach ($ausencias as $au) {
				$texto_motivo .= $au->idmausencia->Dominio.', ';
			}

			$texto_motivo = substr ($texto_motivo, 0, -2);


			if($count == 1){
				$mensaje = "Se encuentra registrada ".$count." ausencia con la misma fecha inicial, ".$texto_motivo.")";	
			}else{
				$mensaje = "Se encuentran registradas ".$count." ausencias con la misma fecha inicial, ".$texto_motivo.")";	
			}		
		}

		echo $opc.'|'.$mensaje;
	}

	public function actionInfoAusenciaAct()
	{
		$fecha_inicial = $_POST['fecha_inicial'];
		$id_ausencia = $_POST['id_ausencia'];
		$empleado = $_POST['empleado'];

		$criteria=new CDbCriteria;
		$criteria->condition='Fecha_Inicial=:Fecha_Inicial AND Id_Ausencia !=:Id_Ausencia AND Id_Empleado=:Id_Empleado';
		$criteria->params=array(':Fecha_Inicial'=>$fecha_inicial, ':Id_Ausencia' => $id_ausencia, ':Id_Empleado' => $empleado);
		$ausencias=AusenciaEmpleado::model()->findAll($criteria);

		if(empty($ausencias)){
			$opc = 0;
			$mensaje = "";	
		}else{
			$opc = 1;
			$count = count ($ausencias);
			
			if($count == 1){
				$texto_motivo = 'Motivo (';
			}else{
				$texto_motivo = 'Motivos (';	
			}

			foreach ($ausencias as $au) {
				$texto_motivo .= $au->idmausencia->Dominio.', ';
			}

			$texto_motivo = substr ($texto_motivo, 0, -2);


			if($count == 1){
				$mensaje = "Se encuentra registrada ".$count." ausencia con la misma fecha inicial, ".$texto_motivo.")";	
			}else{
				$mensaje = "Se encuentran registradas ".$count." ausencias con la misma fecha inicial, ".$texto_motivo.")";	
			}		
		}

		echo $opc.'|'.$mensaje;
	}
}

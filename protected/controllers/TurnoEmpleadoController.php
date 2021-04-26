<?php

class TurnoEmpleadoController extends Controller
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
				'actions'=>array('create','update','infoturnocre','infoturnoact'),
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
		$model=new TurnoEmpleado;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$criteria=new CDbCriteria;
		$criteria->condition = "Estado = 1";
		$criteria->order = "Id_Turno_Trabajo";
		$turnos= TurnoTrabajo::model()->findAll($criteria);

		$lista_t = array();
		foreach ($turnos as $t) {
			$lista_t[$t->Id_Turno_Trabajo] = $t->Id_Turno_Trabajo.'. '.$t->DescTurno($t->Id_Turno_Trabajo);
		}

		//se envia a funcion para saber cual es la fecha de ingreso minimo
		$fecha_min = UtilidadesContrato::fechamintur($e);

		if(isset($_POST['TurnoEmpleado']))
		{
			$model->attributes=$_POST['TurnoEmpleado'];
			$model->Id_Empleado = $e;
			$model->Id_Contrato = UtilidadesEmpleado::contratoactualempleado($e);
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Turno de trabajo asociado correctamente.");
				$this->redirect(array('empleado/view','id'=>$e));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'e'=>$e,
			'lista_t'=>$lista_t,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$criteria=new CDbCriteria;
		$criteria->condition = "Estado = 1";
		$criteria->order = "Id_Turno_Trabajo";
		$turnos= TurnoTrabajo::model()->findAll($criteria);

		$lista_t = array();
		foreach ($turnos as $t) {
			$lista_t[$t->Id_Turno_Trabajo] = $t->Id_Turno_Trabajo.'. '.$t->DescTurno($t->Id_Turno_Trabajo);
		}

		//se envia a funcion para saber cual es la fecha de ingreso minimo
		$fecha_min = UtilidadesContrato::fechamintur($model->Id_Empleado);

		if(isset($_POST['TurnoEmpleado']))
		{
			$model->attributes=$_POST['TurnoEmpleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Turno de trabajo actualizado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'e'=>$model->Id_Empleado,
			'lista_t'=>$lista_t,
			'fecha_min'=>$fecha_min,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TurnoEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TurnoEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TurnoEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='turno-empleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionInfoTurnoCre()
	{
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];
		$empleado = $_POST['empleado'];

		$criteria=new CDbCriteria;
		$criteria->condition='((:Fecha_Inicial BETWEEN Fecha_Inicial AND Fecha_Final) OR (:Fecha_Final BETWEEN Fecha_Inicial AND Fecha_Final)) AND Id_Empleado=:Id_Empleado AND Estado = 1';
		$criteria->params=array(':Fecha_Inicial'=>$fecha_inicial, ':Fecha_Final'=>$fecha_final, ':Id_Empleado' => $empleado);
		$turnos=TurnoEmpleado::model()->findAll($criteria);

		if(empty($turnos)){
			$opc = 0;
			$mensaje = "";	
		}else{
			$opc = 1;
			
			$mensaje = "Ya existe un turno con coincidencias en fechas.";	
				
		}

		echo $opc.'|'.$mensaje;
	}

	public function actionInfoTurnoAct()
	{
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];
		$empleado = $_POST['empleado'];
		$id_t_empleado = $_POST['id_t_empleado'];

		$criteria=new CDbCriteria;
		$criteria->condition='((:Fecha_Inicial BETWEEN Fecha_Inicial AND Fecha_Final) OR (:Fecha_Final BETWEEN Fecha_Inicial AND Fecha_Final)) AND Id_T_Empleado !=:Id_T_Empleado AND Id_Empleado=:Id_Empleado AND Estado = 1';
		$criteria->params=array(':Fecha_Inicial'=>$fecha_inicial, ':Fecha_Final'=>$fecha_final, ':Id_T_Empleado' => $id_t_empleado, ':Id_Empleado' => $empleado);
		$turnos=TurnoEmpleado::model()->findAll($criteria);

		if(empty($turnos)){
			$opc = 0;
			$mensaje = "";	
		}else{
			$opc = 1;
			
			$mensaje = "Ya existe un turno con coincidencias en fechas.";	
				
		}

		echo $opc.'|'.$mensaje;
	}
}

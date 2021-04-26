<?php

class DisciplinarioEmpleadoController extends Controller
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
				'actions'=>array('create','update','loadmotivos'),
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
	public function actionCreate($e, $opc)
	{
		$model=new DisciplinarioEmpleado;

		//se envia a funcion para saber cual es la fecha de minima para el disciplinario
		$fecha_min = UtilidadesContrato::fechaminausdis($e);
		$id_contrato = UtilidadesEmpleado::contratoactualempleado($e);

		if($opc == 1){
			//Llamados de atención

			$model->scenario = 'create_llamado';
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_d_llamado_atencion." AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();
			
			$hist_act = "";	
		}

		if($opc == 2){
			//Sanciones

			$model->scenario = 'create_sancion';
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_d_sancion." AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();

			$hist_act = "";	
		}

		if($opc == 3){
			//Comparendos

			$model->scenario = 'create_comparendo';
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_d_comparendo." AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();

			//historico comparendos contrato activo
			$criteria=new CDbCriteria;
			$criteria->join = 'LEFT JOIN T_PR_DOMINIO d ON t.Id_M_Disciplinario = d.Id_Dominio';
			$criteria->condition = "t.Id_Empleado = :Id_Empleado AND t.Id_Contrato = :Id_Contrato AND d.Id_Padre = ".Yii::app()->params->motivos_d_comparendo;
			$criteria->order = "Fecha DESC";
			$criteria->params = array (':Id_Empleado' => $e, ':Id_Contrato' => $id_contrato);
			$hist_act = new CActiveDataProvider('DisciplinarioEmpleado', array(
			    'criteria'=>$criteria,
			));	
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DisciplinarioEmpleado']))
		{
			$model->attributes=$_POST['DisciplinarioEmpleado'];

			$model->Id_Empleado = $e;
			$model->Id_Contrato = $id_contrato;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){

				if($opc == 2){
					//se crea una ausencia asociada a la sanción
					$nueva_ausencia = new AusenciaEmpleado;
					$nueva_ausencia->Id_Empleado = $e;
					$nueva_ausencia->Id_M_Ausencia = Yii::app()->params->motivo_ausencia_sancion;
					$nueva_ausencia->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
					$nueva_ausencia->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$nueva_ausencia->Cod_Soporte = $model->A_Cod_Soporte;
					$nueva_ausencia->Descontar = $model->A_Descontar;
					$nueva_ausencia->Descontar_FDS = $model->A_Descontar_FDS;
					$nueva_ausencia->Dias = $model->A_Dias;
					$nueva_ausencia->Horas = $model->A_Horas;
					$nueva_ausencia->Fecha_Creacion = date('Y-m-d H:i:s');
					$nueva_ausencia->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$nueva_ausencia->Observacion = $model->A_Observacion;
					$nueva_ausencia->Nota = $model->A_Nota;
					$nueva_ausencia->Fecha_Inicial = $model->A_Fecha_Inicial;
					$nueva_ausencia->Fecha_Final = $model->A_Fecha_Final;
					$nueva_ausencia->Id_Contrato = $id_contrato;

					if($nueva_ausencia->save()){
						Yii::app()->user->setFlash('success', "Evento asociado correctamente.");
						$this->redirect(array('empleado/view','id'=>$e));
					}
				}else{
					Yii::app()->user->setFlash('success', "Evento asociado correctamente.");
					$this->redirect(array('empleado/view','id'=>$e));	
				}
			}		
		}

		$this->render('create',array(
			'model'=>$model,
			'motivos'=>$motivos,
			'opc'=>$opc,
			'e'=>$e,
			'fecha_min'=>$fecha_min,
			'hist_act'=>$hist_act,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $opc)
	{
		$model=$this->loadModel($id);

		//se envia a funcion para saber cual es la fecha de minima para el disciplinario
		$fecha_min = UtilidadesContrato::fechaminausdis($model->Id_Empleado);
		$id_contrato = UtilidadesEmpleado::contratoactualempleado($model->Id_Empleado);

		if($opc == 1){
			//Llamados de atención

			$model->scenario = 'update_llamado';
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_d_llamado_atencion." AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();
		}

		if($opc == 2){
			//Sanciones

			$model->scenario = 'update_sancion';
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_d_sancion." AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();
		}

		if($opc == 3){
			//Comparendos

			$model->scenario = 'update_comparendo';
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_d_comparendo." AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DisciplinarioEmpleado']))
		{
			$model->attributes=$_POST['DisciplinarioEmpleado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Evento actualizado correctamente.");
				$this->redirect(array('empleado/view','id'=>$model->Id_Empleado));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'motivos'=>$motivos,
			'opc'=>$opc,
			'e'=>$model->Id_Empleado,
			'fecha_min'=>$fecha_min,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DisciplinarioEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DisciplinarioEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DisciplinarioEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='disciplinario-empleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/*public function actionLoadMotivos()
	{
		$e = $_POST['e'];
		$fecha_act = $_POST['fecha'];
		$disc_act = $_POST['disc_act'];

		$id_contrato = UtilidadesEmpleado::contratoactualempleado($e);

		if($disc_act != 0){
			$motivo_e = Yii::app()->db->createCommand("SELECT TOP 1 de.Id_M_Disciplinario FROM T_PR_DISCIPLINARIO_EMPLEADO de WHERE de. Id_Contrato = ".$id_contrato." AND de.Id_M_Disciplinario IN (".Yii::app()->params->motivo_evento_esp.") AND (Id_Disciplinario < ".$disc_act.") AND DATEDIFF (day, de.Fecha, '".$fecha_act."') <= 180 ORDER BY de.Fecha DESC")->queryRow();
		}else{
			$motivo_e = Yii::app()->db->createCommand("SELECT TOP 1 de.Id_M_Disciplinario FROM T_PR_DISCIPLINARIO_EMPLEADO de WHERE de. Id_Contrato = ".$id_contrato." AND de.Id_M_Disciplinario IN (".Yii::app()->params->motivo_evento_esp.") AND DATEDIFF (day, de.Fecha, '".$fecha_act."') <= 180 ORDER BY de.Fecha DESC")->queryRow();
		}

		if(!empty($motivo_e)){

			$ult_mot_disc_esp_apl = $motivo_e['Id_M_Disciplinario'];

			//Disc. 1
			if($ult_mot_disc_esp_apl == Yii::app()->params->motivo_evento_esp_1){
				$mot_exc = Yii::app()->params->motivo_evento_esp_1.",".Yii::app()->params->motivo_evento_esp_3.",".Yii::app()->params->motivo_evento_esp_4;
			}

			//Disc. 2
			if($ult_mot_disc_esp_apl == Yii::app()->params->motivo_evento_esp_2){
				$mot_exc = Yii::app()->params->motivo_evento_esp_1.",".Yii::app()->params->motivo_evento_esp_2.",".Yii::app()->params->motivo_evento_esp_4;
			}

			//Disc. 3
			if($ult_mot_disc_esp_apl == Yii::app()->params->motivo_evento_esp_3){
				$mot_exc = Yii::app()->params->motivo_evento_esp_1.",".Yii::app()->params->motivo_evento_esp_2.",".Yii::app()->params->motivo_evento_esp_3;
			}

			//Disc. 4
			if($ult_mot_disc_esp_apl == Yii::app()->params->motivo_evento_esp_4){
				$mot_exc = Yii::app()->params->motivo_evento_esp_2.",".Yii::app()->params->motivo_evento_esp_3.",".Yii::app()->params->motivo_evento_esp_4;
			}

			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Id_Padre = ".Yii::app()->params->motivos_evento." AND d.Id_Dominio NOT IN (".$mot_exc.") AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();
		}else{
			$motivos= Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE (d.Id_Padre = ".Yii::app()->params->motivos_evento." AND d.Id_Dominio NOT IN (".Yii::app()->params->motivo_evento_esp_2.",".Yii::app()->params->motivo_evento_esp_3.",".Yii::app()->params->motivo_evento_esp_4.")) AND d.Estado = 1 ORDER BY d.Dominio")->queryAll();	
		}


		$i = 0;
		$array_motivos = array();
		
		foreach ($motivos as $m) {
			$array_motivos[$i] = array('id' => $m['Id_Dominio'],  'text' => $m['Dominio']);
			$i++; 

		}
		
		//se retorna un json con las opciones
		echo json_encode($array_motivos);

	}*/

}

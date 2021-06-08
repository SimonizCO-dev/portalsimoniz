<?php

class TicketController extends Controller
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
				'actions'=>array('create','update','getnovedades','getnovedadesdet'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','asig'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ticket;
		$model->scenario = 'create';

		//$q_grupos=Dominio::model()->findAll(array('condition'=>'Estado=:estado AND Id_Padre ='.Yii::app()->params->grupos_act, 'params'=>array(':estado'=>1)));

		$q_grupos = Yii::app()->db->createCommand("SELECT G.Id_Dominio, G.Dominio FROM T_PR_DOMINIO G WHERE G.Estado = 1 AND G.Id_Padre = ".Yii::app()->params->grupos_act." AND (SELECT COUNT (*) FROM T_PR_NOVEDAD_TICKET NT WHERE NT.Id_Grupo = G.Id_Dominio AND NT.Estado = 1) > 0 ORDER BY 2")->queryAll();

		$grupos = array();
		foreach ($q_grupos as $g) {
			$grupos[$g['Id_Dominio']] = $g['Dominio'];		
	    }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;

			//print_r($_FILES['Ticket']);

			if($_FILES['Ticket']['name']['Soporte']  != "") {
		        $data = 'data:image/jpg;base64,'.base64_encode(file_get_contents($_FILES['Ticket']['tmp_name']['Soporte']));
		        $model->Soporte = $data;
		    }else{
	    		$model->Soporte = null;
		    }

			if($model->save()){
				Yii::app()->user->setFlash('success', "Ticket ( ID ".$model->Id_Ticket." ) registrado correctamente.");
				$this->redirect(array('create'));	
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'grupos'=>$grupos,
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

		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id_Ticket));
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Ticket');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Ticket('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Ticket']))
			$model->attributes=$_GET['Ticket'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAsig()
	{
		$user = Yii::app()->user->getState('id_user');

		//$q_grupos=TipoTicketUsuario::model()->findAll(array('condition'=>'Estado=:estado AND Id_Usuario ='.Yii::app()->user->getState('id_user'), 'params'=>array(':estado'=>1)));

		$q_grupos = Yii::app()->db->createCommand("SELECT DISTINCT NT.Id_Grupo FROM T_PR_NOVEDAD_TICKET NT WHERE NT.Id_Novedad IN (
		SELECT DISTINCT NTU.Id_Novedad FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_NOVEDAD_TICKET NT ON NTU.Id_Novedad = NT.Id_Novedad AND NT.Estado = 1
		WHERE NTU.Estado = 1 AND NTU.Id_Usuario = ".$user.")")->queryAll();

		if(!empty($q_grupos)){

			$model=new Ticket('asig');
			$a = 1;
			
			$grupos = array();
			foreach ($q_grupos as $g) {
				$id_grupo = $g['Id_Grupo'];
				$desc_grupo = Dominio::model()->findByPk($id_grupo)->Dominio;
				$grupos[$id_grupo] = $desc_grupo;	
		    }

		    $model->unsetAttributes();  // clear any default values
			if(isset($_GET['Ticket'])){
				$model->attributes=$_GET['Ticket'];
			}

		}else{

			$model=new Ticket('asig');
			$a = 0;
			$grupos = array();

		}

		$this->render('asig',array(
			'model'=>$model,
			'a'=>$a,
			'grupos'=>$grupos,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ticket the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ticket::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ticket $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ticket-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGetNovedades()
	{	
		$grupo = $_POST['grupo'];

		
		$tipos = Yii::app()->db->createCommand("
		SELECT NT.Id_Novedad, NT.Novedad FROM T_PR_NOVEDAD_TICKET NT 
		WHERE NT.Estado = 1 AND NT.Id_Grupo = ".$grupo." AND Id_Novedad_Padre IS NULL ORDER BY 2
		")->queryAll();
	
		$i = 0;
		$array_tipos = array();
		foreach ($tipos as $t) {
			$array_tipos[$i] = array('id' => $t['Id_Novedad'],  'text' => $t['Novedad']);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_tipos);

	}

	public function actionGetNovedadesDet()
	{	
		$novedad = $_POST['novedad'];

		
		$tipos = Yii::app()->db->createCommand("
		SELECT NT.Id_Novedad, NT.Novedad FROM T_PR_NOVEDAD_TICKET NT 
		WHERE NT.Estado = 1 AND NT.Id_Novedad_Padre = ".$novedad." ORDER BY 2
		")->queryAll();
	
		$i = 0;
		$array_tipos = array();
		foreach ($tipos as $t) {
			$array_tipos[$i] = array('id' => $t['Id_Novedad'],  'text' => $t['Novedad']);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_tipos);

	}

	public function actionGetTiposXUser()
	{	
		$grupo = $_POST['grupo'];
		$user = Yii::app()->user->getState('id_user');
		
		/*$tipos = Yii::app()->db->createCommand("
		SELECT TT.Id_Tipo, TT.Tipo FROM T_PR_TIPO_TICKET TT 
		WHERE TT.Estado = 1 AND TT.Id_Grupo = ".$grupo." ORDER BY 2
		")->queryAll();*/

		$q_tipos = Yii::app()->db->createCommand("SELECT DISTINCT NTU.Id_Tipo FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_TIPO_TICKET NT ON NTU.Id_Tipo = NT.Id_Tipo AND NT.Estado = 1 AND NT.Id_Grupo = ".$grupo."
		WHERE NTU.Estado = 1 AND NTU.Id_Usuario = ".$user)->queryAll();
	
		$i = 0;
		$array_tipos = array();
		foreach ($q_tipos as $t) {
			$id_tipo = $t['Id_Tipo'];
			$desc_tipo = TipoTicket::model()->findByPk($id_tipo)->Tipo;
			$array_tipos[$i] = array('id' => $id_tipo,  'text' => $desc_tipo);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_tipos);

	}

	public function actionAsigT($id)
	{
		
		$model=$this->loadModel($id);
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');+
		$model->Id_Usuario_Asig = Yii::app()->user->getState('id_user');
		$model->Fecha_Asig = date('Y-m-d H:i:s');
		$model->Estado = 1;
		
		if($model->save()){

			$res = 1;
			$msg = "Se asignó el ticket ( ID ".$model->Id_Ticket." ) correctamente.";
			
		}else{

			$res = 0;
			$msg = "Error al rechazar la factura # ".$model->Num_Factura.".";
			

		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

	public function actionDesasig($id)
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



}

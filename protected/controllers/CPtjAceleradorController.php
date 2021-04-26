<?php

class CPtjAceleradorController extends Controller
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
				'actions'=>array('create','searchitem','searchitembyid','loadcriterios'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','offconfig','verifconfig'),
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
		$model=new CPtjAcelerador;

		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$aceler = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_acelerador.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$planes = Yii::app()->db->createCommand('SELECT DISTINCT Id_Plan, Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS ORDER BY 2')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CPtjAcelerador']))
		{
			$model->attributes=$_POST['CPtjAcelerador'];
			$model->ESTADO = 1;
			$model->ID_USUARIO_CREACION = Yii::app()->user->getState('id_user');
			$model->FECHA_CREACION = date('Y-m-d H:i:s');
			$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
			$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Porcentaje creado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'tipos' => $tipos,
			'aceler' => $aceler,
			'planes'=>$planes,	
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$tipos = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_comision.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$aceler = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipos_acelerador.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$planes = Yii::app()->db->createCommand('SELECT DISTINCT Id_Plan, Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS ORDER BY 2')->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model=new CPtjAcelerador('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CPtjAcelerador']))
			$model->attributes=$_GET['CPtjAcelerador'];

		$this->render('admin',array(
			'model'=>$model,
			'tipos' => $tipos,
			'aceler' => $aceler,
			'planes'=>$planes,	
			'usuarios'=>$usuarios,	
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CPtjAcelerador the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CPtjAcelerador::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CPtjAcelerador $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cptj-acelerador-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchItem(){
		$filtro = $_GET['q'];
        $data = CPtjAcelerador::model()->searchByItem($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['I_ID_ITEM'],
               'text' => $item['ITEM'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionSearchItemById(){
		$filtro = $_GET['id'];
        $data = CPtjAcelerador::model()->searchById($filtro);

        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['I_ID_ITEM'],
               'text' => $item['ITEM'],
           );
        endforeach;

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionLoadCriterios()
	{
		$plan = $_POST['plan'];
 

		$criterios = Yii::app()->db->createCommand("

        	SELECT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE  Id_Plan = '".$plan."' ORDER BY Criterio_Descripcion")->queryAll();

		$i = 0;
		$array_criterios = array();
		
		foreach ($criterios as $c) {
			$array_criterios[$i] = array('id' => trim($c['Id_Criterio']),  'text' => $c['Criterio_Descripcion']);
			$i++; 

		}
		
		//se retorna un json con las opciones
		echo json_encode($array_criterios);

	}

	public function actionOffConfig($id)
	{
		$model=$this->loadModel($id);

		$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
		$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
		$model->ESTADO = 0;
		if($model->save()){
			Yii::app()->user->setFlash('success', "El porcentaje de acelerador ".$model->ROWID." fue inactivado correctamente.");
			$this->redirect(array('admin'));
		}
	}

	public function actionVerifConfig()
	{
		$data = array();

		$tipo = $_POST['tipo'];
		$acelerador = $_POST['acelerador'];
		$item = $_POST['item'];
		$plan = $_POST['plan'];
		$criterio = $_POST['criterio'];
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];

		if($acelerador == Yii::app()->params->ac_item){
			//item

			$q = Yii::app()->db->createCommand("SELECT ROWID FROM T_PR_C_PTJ_ACELERADOR WHERE TIPO = ".$tipo." AND ID_ACELERADOR = ".$acelerador." AND ITEM = ".$item." AND (('".$fecha_inicial."' BETWEEN FECHA_INICIAL AND FECHA_FINAL) OR ('".$fecha_final."' BETWEEN FECHA_INICIAL AND FECHA_FINAL) OR ('".$fecha_inicial."' < FECHA_INICIAL AND '".$fecha_final."' > FECHA_FINAL)) AND ESTADO = 1")->queryRow();

		}

		if($acelerador == Yii::app()->params->ac_criterio){
			//plan, criterio

			$q = Yii::app()->db->createCommand("SELECT ROWID FROM T_PR_C_PTJ_ACELERADOR WHERE TIPO = ".$tipo." AND ID_ACELERADOR = ".$acelerador." AND ID_PLAN = '".$plan."' AND CRITERIO = '".$criterio."' AND (('".$fecha_inicial."' BETWEEN FECHA_INICIAL AND FECHA_FINAL) OR ('".$fecha_final."' BETWEEN FECHA_INICIAL AND FECHA_FINAL) OR ('".$fecha_inicial."' < FECHA_INICIAL AND '".$fecha_final."' > FECHA_FINAL)) AND ESTADO = 1")->queryRow();
		}

        $id = $q['ROWID'];

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

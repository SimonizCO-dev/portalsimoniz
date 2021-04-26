<?php

class IItemController extends Controller
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
				'actions'=>array('create','update','searchitem','searchitembyid'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
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
		$model=new IItem;

		$q_id_asignar = Yii::app()->db->createCommand("SELECT MAX(Id_Item) AS Cons FROM T_PR_I_ITEM")->queryRow();

		$id_asignar = $q_id_asignar['Cons'] + 1;
		
		$unidades = Yii::app()->db->createCommand("SELECT DISTINCT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 ORDER BY f101_descripcion")->queryAll();

		$lista_unidades = array();
		foreach ($unidades as $und) {
			$lista_unidades[str_replace(' ','',$und['f101_id'])] = $und['f101_descripcion'];
		}

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));



		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IItem']))
		{
			$model->attributes=$_POST['IItem'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Item creado correctamente.");
				$this->redirect(array('admin'));
			}			

		}

		$this->render('create',array(
			'model'=>$model,
			'lista_unidades'=>$lista_unidades,
			'lineas'=>$lineas,
			'id_asignar'=>$id_asignar,
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

		$id_asignar = $model->Id_Item;

		$unidades = Yii::app()->db->createCommand("SELECT DISTINCT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 ORDER BY f101_descripcion")->queryAll();

		$lista_unidades = array();
		foreach ($unidades as $und) {
			$lista_unidades[str_replace(' ','',$und['f101_id'])] = $und['f101_descripcion'];
		}

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IItem']))
		{
			$model->attributes=$_POST['IItem'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Item actualizado correctamente.");
				$this->redirect(array('admin'));
			}	
				
		}

		$this->render('update',array(
			'model'=>$model,
			'lista_unidades'=>$lista_unidades,
			'lineas'=>$lineas,
			'id_asignar'=>$id_asignar,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new IItem('search');
		$unidades=ILinea::model()->findAll(array('order'=>'Descripcion'));

		$unidades = Yii::app()->db->createCommand("SELECT DISTINCT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 ORDER BY f101_descripcion")->queryAll();

		$lista_unidades = array();
		foreach ($unidades as $und) {
			$lista_unidades[str_replace(' ','',$und['f101_id'])] = $und['f101_descripcion'];
		}

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion'));
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['IItem']))
			$model->attributes=$_GET['IItem'];

		$this->render('admin',array(
			'model'=>$model,
			'lista_unidades'=>$lista_unidades,
			'lineas'=>$lineas,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IItem the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IItem::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IItem $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='iitem-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchItem(){
		$filtro = $_GET['q'];
        $data = IItem::model()->searchByItem($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id'],
               'text' => $item['Descr'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchItemById(){
		$filtro = $_GET['id'];
        $data = IItem::model()->searchById($filtro);

        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id'],
               'text' => $item['Descr'],
           );
        endforeach;

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}
}

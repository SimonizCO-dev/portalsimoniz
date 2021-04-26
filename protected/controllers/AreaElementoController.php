<?php

class AreaElementoController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','searchelem','searchelembyid'),
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
		$model=new AreaElemento;

		$elementos = Yii::app()->db->createCommand('SELECT e.Id_Elemento, e.Elemento FROM T_PR_ELEMENTO e WHERE e.Estado = 1 ORDER BY e.Elemento')->queryAll();
		$subareas = Yii::app()->db->createCommand('SELECT s.Id_Subarea, s.Subarea FROM T_PR_SUBAREA s WHERE s.Estado = 1 ORDER BY s.Subarea')->queryAll();
		$areas = Yii::app()->db->createCommand('SELECT a.Id_Area, a.Area FROM T_PR_AREA a WHERE a.Estado = 1 ORDER BY a.Area')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['AreaElemento']))
		{
			$model->attributes=$_POST['AreaElemento'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Asociación creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'elementos'=>$elementos,
			'subareas'=>$subareas,
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

		$elem_act = $model->Id_Elemento;
		$area_act = $model->Id_Area;
		$subarea_act = $model->Id_Subarea;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(!is_null($elem_act)){
			$elementos= Yii::app()->db->createCommand('SELECT e.Id_Elemento, e.Elemento FROM T_PR_ELEMENTO e  WHERE (e.Estado = 1 OR e.Id_Elemento = '.$elem_act.') ORDER BY e.Elemento')->queryAll();
		}else{
			$elementos= Yii::app()->db->createCommand('SELECT e.Id_Elemento, e.Elemento FROM T_PR_ELEMENTO e  WHERE e.Estado = 1 ORDER BY e.Elemento')->queryAll();
		}

		if(!is_null($subarea_act)){
			$subareas= Yii::app()->db->createCommand('SELECT s.Id_Subarea, s.Subarea FROM T_PR_SUBAREA s WHERE (s.Estado = 1 OR s.Id_Subarea = '.$subarea_act.') ORDER BY s.Subarea')->queryAll();
		}else{
			$subareas= Yii::app()->db->createCommand('SELECT s.Id_Subarea, s.Subarea FROM T_PR_SUBAREA s WHERE s.Estado = 1 ORDER BY s.Subarea')->queryAll();
		}

		if(!is_null($area_act)){
			$areas= Yii::app()->db->createCommand('SELECT a.Id_Area, a.Area FROM T_PR_AREA a WHERE (a.Estado = 1 OR a.Id_Area = '.$area_act.') ORDER BY a.Area')->queryAll();
		}else{
			$areas= Yii::app()->db->createCommand('SELECT a.Id_Area, a.Area FROM T_PR_AREA a WHERE a.Estado = 1 ORDER BY a.Area')->queryAll();
		}

		if(isset($_POST['AreaElemento']))
		{
			$model->attributes=$_POST['AreaElemento'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Asociación actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'elementos'=>$elementos,
			'subareas'=>$subareas,
			'areas'=>$areas,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new AreaElemento('search');
		$model->unsetAttributes();  // clear any default values

		$elementos=Elemento::model()->findAll(array('order'=>'Elemento', 'condition'=>'Estado = 1'));
		$areas=Area::model()->findAll(array('order'=>'Area', 'condition'=>'Estado = 1'));
		$subareas=Subarea::model()->findAll(array('order'=>'Subarea', 'condition'=>'Estado = 1'));
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		if(isset($_GET['AreaElemento']))
			$model->attributes=$_GET['AreaElemento'];

		$this->render('admin',array(
			'model'=>$model,
			'elementos'=>$elementos,
			'areas'=>$areas,
			'subareas'=>$subareas,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return AreaElemento the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=AreaElemento::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param AreaElemento $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-elemento-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchElem(){
		$filtro = $_GET['q'];
        $data = AreaElemento::model()->searchByElem($filtro);
        $result = array();

        foreach($data as $item):

        	if(empty($item['Elemento'])){
        		$elemento = 'NO ASIGNADO';
        	}else{
        		$elemento = $item['Elemento'];
        	}

        	if(empty($item['Subarea'])){
        		$subarea = 'NO ASIGNADO';
        	}else{
        		$subarea = $item['Subarea'];
        	}

        	if(empty($item['Area'])){
        		$area = 'NO ASIGNADO';
        	}else{
        		$area = $item['Area'];
        	}

 			$text = $elemento." (".$subarea." / ".$area.")";


           $result[] = array(
               'id'   => $item['Id'],
               'text' => $text,
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionSearchElemById(){
		$filtro = $_GET['id'];
        $data = AreaElemento::model()->searchById($filtro);
   
       	if(is_null($data->Id_Elemento)){
 			$elemento = 'NO ASIGNADO';
 		}else{
 			$elemento = $data->idelemento->Elemento;
 		}

 		if(is_null($data->Id_Subarea)){
 			$subarea = 'NO ASIGNADO';
 		}else{
 			$subarea = $data->idsubarea->Subarea;
 		}

 		if(is_null($data->Id_Area)){
 			$area = 'NO ASIGNADO';
 		}else{
 			$area = $data->idarea->Area;
 		}

 		$id = $data->Id_A_elemento;
 		$text = $elemento." (".$subarea." / ".$area.")";

       	$result[] = 
       	array(
           'id'   => $id,
           'text' => $text,
       	);

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}
}

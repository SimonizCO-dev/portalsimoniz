<?php

class SugeridoController extends Controller
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
				'actions'=>array('create','update','searchsug','searchsugbyid'),
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

	public function actionCreate()
	{
		$model=new Sugerido;
		$model->Scenario = 'create';

		$cargos = Yii::app()->db->createCommand('SELECT c.Id_Cargo, c.Cargo FROM T_PR_CARGO c WHERE c.Estado = 1 ORDER BY c.Cargo')->queryAll();
		$subareas = Yii::app()->db->createCommand('SELECT s.Id_Subarea, s.Subarea FROM T_PR_SUBAREA s WHERE s.Estado = 1 ORDER BY s.Subarea')->queryAll();
		$areas = Yii::app()->db->createCommand('SELECT a.Id_Area, a.Area FROM T_PR_AREA a WHERE a.Estado = 1 ORDER BY a.Area')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sugerido']))
		{
			$model->attributes=$_POST['Sugerido'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;
			if($model->save()){
				Yii::app()->user->setFlash('success', "Sugerido creado correctamente, por favor asocié los elementos.");
				$this->redirect(array('sugerido/update','id'=>$model->Id_Sugerido));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'cargos'=>$cargos,
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
		$model->Scenario = 'update';
		
		//elementos asoc. 
		$elem_asoc=new ElementoSugerido('search');
		$elem_asoc->unsetAttributes();  // clear any default values
		$elem_asoc->Id_Sugerido = $model->Id_Sugerido;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sugerido']))
		{
			$model->attributes=$_POST['Sugerido'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Sugerido actualizado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'elem_asoc'=>$elem_asoc,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Sugerido('search');
		$cargos=Cargo::model()->findAll(array('order'=>'Cargo'));
		$subareas=Subarea::model()->findAll(array('order'=>'Subarea'));
		$areas=Area::model()->findAll(array('order'=>'Area'));
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Sugerido']))
			$model->attributes=$_GET['Sugerido'];

		$this->render('admin',array(
			'model'=>$model,
			'cargos'=>$cargos,
			'subareas'=>$subareas,
			'areas'=>$areas,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Sugerido the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Sugerido::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Sugerido $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sugerido-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

 	public function actionSearchSug(){
		$filtro = $_GET['q'];
        $data = Sugerido::model()->searchBySug($filtro);
        $result = array();

        foreach($data as $item):

        	if(empty($item['Cargo'])){
        		$cargo = 'SIN ASIGNAR';
        	}else{
        		$cargo = $item['Cargo'];
        	}

        	if(empty($item['Subarea'])){
        		$subarea = 'SIN ASIGNAR';
        	}else{
        		$subarea = $item['Subarea'];
        	}

        	if(empty($item['Area'])){
        		$area = 'SIN ASIGNAR';
        	}else{
        		$area = $item['Area'];
        	}

 			$text = $cargo." (".$subarea." / ".$area.")";


           $result[] = array(
               'id'   => $item['Id'],
               'text' => $text,
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionSearchSugById(){
		$filtro = $_GET['id'];
        $data = Sugerido::model()->searchById($filtro);
   
       	if(is_null($data->Id_Cargo)){
 			$cargo = 'SIN ASIGNAR';
 		}else{
 			$cargo = $data->idcargo->Cargo;
 		}

 		if(is_null($data->Id_Subarea)){
 			$subarea = 'SIN ASIGNAR';
 		}else{
 			$subarea = $data->idsubarea->Subarea;
 		}

 		if(is_null($data->Id_Area)){
 			$area = 'SIN ASIGNAR';
 		}else{
 			$area = $data->idarea->Area;
 		}

 		$id = $data->Id_Sugerido;
 		$text = $cargo." (".$subarea." / ".$area.")";

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

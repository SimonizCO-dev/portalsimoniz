<?php

class MenuController extends Controller
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
				'actions'=>array('create','update','loadmenu','searchopcion','getopcion'),
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
		$model=new Menu;

		$model->scenario = 'create';

		$opciones_p= Yii::app()->db->createCommand('
		    SELECT m.Id_Menu 
		    FROM T_PR_MENU m
		    WHERE Id_Padre in (SELECT Id_Menu FROM T_PR_MENU WHERE Id_Padre IN (1,2,3))
		    ORDER BY m.Id_Menu
		')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Opción de menú creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'opciones_p'=>$opciones_p,
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

		$model->scenario = 'update';

		$opciones_p= Yii::app()->db->createCommand('
		    SELECT m.Id_Menu 
		    FROM T_PR_MENU m
		    WHERE Id_Padre in (SELECT Id_Menu FROM T_PR_MENU WHERE Id_Padre IN (1,2,3))
		    ORDER BY m.Id_Menu
		')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Opción de menú actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'opciones_p'=>$opciones_p,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Menu('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$opciones_p= Yii::app()->db->createCommand('
		    SELECT m.Id_Menu 
		    FROM T_PR_MENU m
		    WHERE EXISTS (SELECT COUNT(*) FROM T_PR_MENU sm WHERE sm.Id_Padre = m.Id_Menu HAVING COUNT(*) > 0)
		    GROUP BY m.Id_Menu ORDER BY m.Id_Menu
		')->queryAll();

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Menu']))
			$model->attributes=$_GET['Menu'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'opciones_p'=>$opciones_p,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Menu the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Menu::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Menu $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='menu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionLoadMenu()
	{
		$opciones_menu = UtilidadesMenu::loadmenu();
		echo $opciones_menu;
	}

	public function actionSearchOpcion(){
		$filtro = $_GET['q'];
		$data = UtilidadesMenu::searchopcion($filtro);
        $result = array();
        foreach($data as $item):

        	$m_m = Menu::model()->findByPk($item['Id_Menu']);
        	if ($m_m->Descripcion_Larga == NULL || $m_m->Descripcion_Larga == ""){
        		$desc = $m_m->idpadre->Descripcion." -> ".$m_m->Descripcion." ( Sin Descripción )";
        	}else{
        		$desc = $m_m->idpadre->Descripcion." -> ".$m_m->Descripcion." ( ".$m_m->Descripcion_Larga." )";
        	}

           	$result[] = array(
               'id'   => $item['Id_Menu'],
               'text' => $desc,
           	);
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionGetOpcion()
	{
		$id_menu = $_POST['id_menu'];
		$model_menu = Menu::model()->findByPk($id_menu);

		$data = array();
		$data['link'] = $model_menu->Link;
		$data['dd'] = $model_menu->Descarga_Directa;

		echo json_encode($data);
	}
}

<?php

class ElementoSugeridoController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','act','inact'),
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
	public function actionCreate($s)
	{
		$model=new ElementoSugerido;
		$model->Scenario = 'create';

		$sugerido = Sugerido::model()->findByPk($s);
		$sugerido->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$sugerido->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$sugerido->save();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ElementoSugerido']))
		{
			$model->attributes=$_POST['ElementoSugerido'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;
			if($model->save()){
				Yii::app()->user->setFlash('success', "Elemento asociado a sugerido correctamente.");
				$this->redirect(array('sugerido/update','id'=>$s));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			's'=>$s,
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

		$sugerido = Sugerido::model()->findByPk($model->Id_Sugerido);
		$sugerido->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$sugerido->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$sugerido->save();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ElementoSugerido']))
		{
			$model->attributes=$_POST['ElementoSugerido'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "Elemento de sugerido actualizado correctamente.");
				$this->redirect(array('sugerido/update','id'=>$model->Id_Sugerido));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ElementoSugerido('search');
		$model->unsetAttributes();  // clear any default values

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$criteria = new CDbCriteria;
        $criteria->join ='LEFT JOIN T_PR_CARGO c ON t.Id_Cargo = c.Id_Cargo LEFT JOIN T_PR_AREA a ON t.Id_Area = a.Id_Area LEFT JOIN T_PR_SUBAREA s ON t.Id_Subarea = s.Id_Subarea';
        $criteria->order = 'c.Cargo ASC, s.Subarea ASC, a.Area ASC';
        $sugeridos=Sugerido::model()->findAll($criteria);

		$criteria = new CDbCriteria;
        $criteria->join ='LEFT JOIN T_PR_ELEMENTO e ON t.Id_Elemento = e.Id_Elemento LEFT JOIN T_PR_SUBAREA s ON t.Id_Subarea = s.Id_Subarea LEFT JOIN T_PR_AREA a ON t.Id_Area = a.Id_Area ';
        $criteria->order = 'e.Elemento ASC, s.Subarea ASC, a.Area ASC';
        $elementos_area=AreaElemento::model()->findAll($criteria);

		if(isset($_GET['ElementoSugerido']))
			$model->attributes=$_GET['ElementoSugerido'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'sugeridos' => $sugeridos,
			'elementos_area' => $elementos_area,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ElementoSugerido the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ElementoSugerido::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ElementoSugerido $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='elemento-sugerido-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAct($id, $opc)
	{
		
		$model=$this->loadModel($id);

		$sugerido = Sugerido::model()->findByPk($model->Id_Sugerido);
		$sugerido->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$sugerido->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$sugerido->save();

		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 1;
		
		if($model->save()){
			
			if($opc == 1){
				//consulta sug (AJAX)

				$res = 1;
				$msg = "Se activo el elemento correctamente.";

				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);

			}else{
				//por consulta

				Yii::app()->user->setFlash('success', "Se activo el elemento correctamente.");
				$this->redirect(array('sugerido/update','id'=>$model->Id_Sugerido));	
			}

		}else{

			if($opc == 1){
				//consulta sug (AJAX)

				$res = 0;
				$msg = "No se pudo activar el elemento.";

				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);

			}else{
				//por consulta

				Yii::app()->user->setFlash('warning', "No se pudo activar el elemento.");
				$this->redirect(array('sugerido/update','id'=>$model->Id_Sugerido));
			}

		}

	}

	public function actionInact($id, $opc)
	{
		
		$model=$this->loadModel($id);

		$sugerido = Sugerido::model()->findByPk($model->Id_Sugerido);
		$sugerido->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$sugerido->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$sugerido->save();

		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 0;
		
		if($model->save()){
			
			if($opc == 1){
				//consulta sug (AJAX)

				$res = 1;
				$msg = "Se inactivo el elemento correctamente...";

				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);

			}else{
				//por consulta

				Yii::app()->user->setFlash('success', "Se inactivo el elemento correctamente.");
				$this->redirect(array('sugerido/update','id'=>$model->Id_Sugerido));	
			}

		}else{

			if($opc == 1){
				//consulta sug (AJAX)

				$res = 0;
				$msg = "No se pudo inactivar el elemento.";

				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);

			}else{
				//por consulta

				Yii::app()->user->setFlash('warning', "No se pudo inactivar el elemento.");
				$this->redirect(array('sugerido/update','id'=>$model->Id_Sugerido));
			}

		}

	}

}

<?php

class ItemContController extends Controller
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
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($c)
	{
		$model=new ItemCont;

		$monedas =Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->monedas, 'params'=>array(':estado'=>1)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ItemCont']))
		{
			$model->attributes=$_POST['ItemCont'];
 			$model->Id_Contrato = $c;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
 
            if($model->save()){
            	Yii::app()->user->setFlash('success', "Se asocio el item (".$model->Item.") correctamente.");
				$this->redirect(array('cont/view','id'=>$c));
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo asociar el item.");
				$this->redirect(array('cont/view','id'=>$c));
			}

		}

		$this->render('create',array(
			'model'=>$model,
			'c'=>$c,
			'monedas'=>$monedas,
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

		//items asociados a contrato
		$historial=new HistItemCont('search');
		$historial->unsetAttributes();  // clear any default values
		$historial->Id_Item = $id;

		$monedas =Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->monedas, 'params'=>array(':estado'=>1)));

		$id_item_act = $model->Id;
		$item_act = $model->Item;
		$descripcion_act = $model->Descripcion;
		$cant_act = $model->Cant;
		$vlr_unit_act = $model->Vlr_Unit;
		$moneda_act = $model->Moneda;
		$iva_act = $model->Iva;
		$estado_act = $model->Estado;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ItemCont']))
		{
			$model->attributes=$_POST['ItemCont'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				
				//función para registrar cambios en las Cuentas
				UtilidadesVarias::novedaditem(
					$id,
					$id_item_act,
					$model->Id,  
					$item_act, 
					$model->Item,
					$descripcion_act, 
					$model->Descripcion,
					$cant_act, 
					$model->Cant,
					$vlr_unit_act, 
					$model->Vlr_Unit,
					$moneda_act,
					$model->Moneda,
					$iva_act,
					$model->Iva,
					$estado_act, 
					$model->Estado,
				);


				Yii::app()->user->setFlash('success', "Se actualizo el item correctamente.");
				$this->redirect(array('cont/view','id'=>$model->Id_Contrato));
			
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo actualizar el item.");
				$this->redirect(array('cont/view','id'=>$model->Id_Contrato));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'c'=>$model->Id_Contrato,
			'monedas'=>$monedas,
			'historial'=>$historial,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ItemCont the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ItemCont::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ItemCont $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='item-cont-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

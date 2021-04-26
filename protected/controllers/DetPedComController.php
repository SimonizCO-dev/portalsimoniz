<?php

class DetPedComController extends Controller
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
				'actions'=>array('create','searchitem','getunditem'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
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
	public function actionCreate($id)
	{
		$model=new DetPedCom;

		$cab = PedCom::model()->findByPk($id);

		//detalle 
		$detalle=new DetPedCom('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Ped_Com = $id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DetPedCom']))
		{
			$model->attributes=$_POST['DetPedCom'];
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			if($model->save()){
				$cab->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$cab->save();
				Yii::app()->user->setFlash('success', "Se agrego el item correctamente.");
				$this->redirect(array('create','id'=>$id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'cab'=>$cab,
			'detalle'=>$detalle,
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DetPedCom the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DetPedCom::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DetPedCom $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='det-ped-com-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchItem(){
		$filtro = $_GET['q'];

		$id_cab = $_GET['id_cab'];

		$modelo_pedido = PedCom::model()->findByPk($id_cab);

		$nit = $modelo_pedido->Cliente;
		$suc =$modelo_pedido->Sucursal;
		$pe = $modelo_pedido->Punto_Envio;

		$resp = Yii::app()->db->createCommand("
		SET NOCOUNT ON EXEC P_PR_COM_PEDIDO_SINRT
		@VAR1 = '".$nit."',
		@VAR2 = '".$suc."',
		@VAR3 = '".$pe."',
		@VAR4 = '".$filtro."'
		")->queryAll();

		$i = 0;
		$array_items = array();
		foreach ($resp as $t) {
    		$array_items[$i] = array('id' => $t['F_CODIGO'],  'text' => $t['F_CODIGO']." - ".$t['F_DESCRIPCION']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_items);
 	}

 	public function actionGetUndItem(){
		
		$item = $_POST['item'];

		$resp = Yii::app()->db->createCommand("
			SELECT
			f120_rowid
			,f120_id
			,f120_referencia
			,f120_id_unidad_empaque
			,CAST(t2.f122_factor as int) AS UND_EMP
			,f120_id_unidad_inventario
			,cast(t1.f122_factor as int) AS UND
			FROM UnoEE1..t120_mc_items
			LEFT JOIN UnoEE1..t122_mc_items_unidades AS t1 ON t1.f122_id_cia=f120_id_cia AND t1.f122_rowid_item=f120_rowid AND  t1.f122_id_unidad=f120_id_unidad_inventario
			LEFT JOIN UnoEE1..t122_mc_items_unidades AS t2 ON t2.f122_id_cia=f120_id_cia AND t2.f122_rowid_item=f120_rowid AND  t2.f122_id_unidad=f120_id_unidad_empaque
			WHERE f120_id_cia=2 AND f120_id=".$item."
		")->queryRow();

    	$data = array('Und_Emp' => $resp['UND_EMP'], 'Und' => $resp['UND']);
 
	    //se retorna un json con las opciones
		echo json_encode($data);

 	}
}

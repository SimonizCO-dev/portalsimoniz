<?php

class PedComController extends Controller
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
				'actions'=>array('create','update','update2'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','control','searchcliente','getsuccliente','getpuntenvsuccliente','searchitem'),
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
		$model=new PedCom;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PedCom']))
		{
			$model->attributes=$_POST['PedCom'];
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se registro el pedido ".$model->Id_Ped_Com." correctamente.");
				$this->redirect(array('update','id'=>$model->Id_Ped_Com));
			}
		}

		$this->render('create',array(
			'model'=>$model,
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

		//detalle 
		$detalle=new DetPedCom('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Ped_Com = $model->Id_Ped_Com;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PedCom']))
		{
			$model->attributes=$_POST['PedCom'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				if($model->Estado == 0){
					Yii::app()->user->setFlash('success', "Se anulo el pedido ".$model->Id_Ped_Com." correctamente.");
					$this->redirect(array('admin'));
				}
				if($model->Estado == 2){
					$model->Fecha = date('Y-m-d');
					$model->save();
					//se verifica que el vendedor tenga correos para noificar el envio de pedido
					$modelo_envio = PedComEnvio::model()->findByAttributes(array('Id_Usuario' => $model->Id_Usuario));
					
					if(!empty($modelo_envio)){

			        	$array_emails = explode(",", $modelo_envio->Emails);
			            $resp = UtilidadesMail::enviopedidocom($id ,$array_emails);
			            $num_notif = intval($resp);
			       		
			            if($num_notif > 1){
			            	Yii::app()->user->setFlash('success', "Se actualizo el pedido ".$model->Id_Ped_Com." correctamente, se enviaron ".$num_notif." notificaciones.");
			            }else{
			            	Yii::app()->user->setFlash('success', "Se actualizo el pedido ".$model->Id_Ped_Com." correctamente, se envío 1 notificación.");
			            }

						$this->redirect(array('admin'));

					}else{
						Yii::app()->user->setFlash('success', "Se actualizo el pedido ".$model->Id_Ped_Com." correctamente, no se enviaron notificaciones.");
						$this->redirect(array('admin'));
					}
					
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'detalle'=>$detalle,
		));
	}

	public function actionUpdate2($id)
	{
		$model=$this->loadModel($id);

		//detalle 
		$detalle=new DetPedCom('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Ped_Com = $model->Id_Ped_Com;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PedCom']))
		{
			$model->attributes=$_POST['PedCom'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "El pedido ".$model->Id_Ped_Com." fue actualizado correctamente.");
				$this->redirect(array('control'));
			}
		}

		$this->render('update2',array(
			'model'=>$model,
			'detalle'=>$detalle,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PedCom('search');

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PedCom']))
			$model->attributes=$_GET['PedCom'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionControl()
	{
		$model=new PedCom('search2');

		$usuarios= Yii::app()->db->createCommand('
		    SELECT u.Id_Usuario
		    FROM T_PR_PED_COM u
		    GROUP BY u.Id_Usuario
		')->queryAll();

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PedCom']))
			$model->attributes=$_GET['PedCom'];

		$this->render('control',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PedCom the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PedCom::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PedCom $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ped-com-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchCliente(){
		$filtro = $_GET['q'];

		$resp = Yii::app()->db->createCommand("
			SELECT TOP 10 C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND (C_NIT_CLIENTE LIKE '".$filtro."%' OR C_NOMBRE_CLIENTE LIKE '".$filtro."%') GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
		")->queryAll();


		$i = 0;
		$array_cli = array();
		foreach ($resp as $t) {
    		$array_cli[$i] = array('id' => $t['C_NIT_CLIENTE'],  'text' => $t['C_NIT_CLIENTE']." - ".$t['C_NOMBRE_CLIENTE']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_cli);
 	}

 	public function actionGetSucCliente(){

 		$nit = $_POST['nit'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT 
			f201_id_sucursal,
			f201_descripcion_sucursal
			FROM UnoEE1..t200_mm_terceros
			INNER JOIN UnoEE1..t201_mm_clientes ON f200_id_Cia=f201_id_cia AND f200_rowid=f201_rowid_tercero
			INNER JOIN UnoEE1..t215_mm_puntos_envio_cliente ON f201_id_cia=f200_id_cia AND f215_rowid_tercero=f201_rowid_tercero AND f201_id_sucursal=f215_id_sucursal
			WHERE f200_id_cia = 2 AND f215_id != '000' AND f200_id = '".$nit."'
		")->queryAll();

		$i = 0;
		$array_suc = array();
		foreach ($resp as $t) {
    		$array_suc[$i] = array('id' => $t['f201_id_sucursal'],  'text' => $t['f201_id_sucursal']." / ".$t['f201_descripcion_sucursal']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_suc);
 	}

 	public function actionGetPuntEnvSucCliente(){

 		$nit = $_POST['nit'];
		$suc = $_POST['suc'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT 
			f215_id,
			f215_descripcion
			FROM UnoEE1..t200_mm_terceros
			INNER JOIN UnoEE1..t201_mm_clientes ON f200_id_Cia=f201_id_cia AND f200_rowid=f201_rowid_tercero
			INNER JOIN UnoEE1..t215_mm_puntos_envio_cliente ON f201_id_cia=f200_id_cia AND f215_rowid_tercero=f201_rowid_tercero AND f201_id_sucursal=f215_id_sucursal
			WHERE f200_id_cia = 2 AND f215_id != '000' AND f200_id = '".$nit."' AND f201_id_sucursal = '".$suc."'
		")->queryAll();

		$i = 0;
		$array_suc = array();
		foreach ($resp as $t) {
    		$array_suc[$i] = array('id' => $t['f215_id'],  'text' => $t['f215_id']." / ".$t['f215_descripcion']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_suc);

 	}
}

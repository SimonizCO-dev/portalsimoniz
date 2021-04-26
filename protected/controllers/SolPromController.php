<?php

class SolPromController extends Controller
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
				'actions'=>array('create','update','searchcliente','searchitem','rev'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','redirect','genrepdoc'),
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
		
		//detalle 
		$detalle=new DetSolProm('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Sol_Prom = $id;

		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'detalle'=>$detalle,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new SolProm;

		$model->Scenario = 'registro';

		$tipos = Yii::app()->db->createCommand("SELECT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $tip) {
			$lista_tipos[$tip['Id_Criterio']] = $tip['Criterio_Descripcion'];
		}

		$e = 0;

		if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariossolprom(1))){
			$e = 1;
		}

		//se obtiene el consecutivo para el siguiente registro

		$q_cons = Yii::app()->db->createCommand("SELECT TOP 1 Num_Sol FROM T_PR_SOL_PROM ORDER BY Id_Sol_Prom DESC")->queryRow();

		if(!empty($q_cons)){
			
			$year = date('y');
			
			$q_ult_cons = Yii::app()->db->createCommand("SELECT TOP 1 Num_Sol FROM  T_PR_SOL_PROM WHERE Num_Sol LIKE '".$year."-%' ORDER BY Id_Sol_Prom DESC")->queryRow();

			if(empty($q_ult_cons)){
				$n_cons = $year.'-001';
			}else{
				$c = $q_ult_cons['Num_Sol'];
				//se obtiene el numero de la cadena de consecutivo
				$n = substr($c, 3, 4);
				$ns = ((int) $n) + 1;
				//función para rellenar ceros a la izq.
				$na = str_pad((int) $ns,3,"0",STR_PAD_LEFT);
				$n_cons = $year.'-'.$na;
			}
		}else{
			$year = date('y');
			$n_cons = $year.'-001';
		}

		$model->Num_Sol = $n_cons;
			
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SolProm']))
		{
			$model->attributes=$_POST['SolProm'];
			$model->Estado = 1;

			if($_POST['SolProm']['Cliente'] != ""){
				$model->Cliente = $_POST['SolProm']['Cliente'];	
			}else{
				$model->Cliente = null;	
			}

			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){

			$q_det = Yii::app()->db->createCommand("
				SELECT
				t1.f120_id AS Padre
				,t2.f120_id AS Hijo
				,f134_cant_base AS Cant_Base
				,f134_cant_requerida AS Cant_Requerida
			  	FROM UnoEE1..t134_mc_items_kits
			  	INNER JOIN UnoEE1..t120_mc_items AS t1 ON f134_rowid_item_ext_kit=t1.f120_rowid
			 	INNER JOIN UnoEE1..t120_mc_items AS t2 ON f134_rowid_item_ext_hijo=t2.f120_rowid
			  	WHERE t1.f120_id = ".$model->Kit
			)->queryAll();

			foreach ($q_det as $reg) {
				$det=new DetSolProm;
				$det->Id_Sol_Prom = $model->Id_Sol_Prom;
				$det->Item = $reg['Hijo'];
				$det->Cant_Base = $reg['Cant_Base'];
				$det->Cant_Requerida = $reg['Cant_Requerida'];
				$det->Cant_Solicitada = $reg['Cant_Requerida'] * $model->Cant;
				$det->save();
			}

				$emails_envio = UtilidadesMail::emailssolprom(2);
				if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviosolprom($model->Id_Sol_Prom, $emails_envio, '');	
				}
				Yii::app()->user->setFlash('success', "Solicitud ".$model->Num_Sol." creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'lista_tipos'=>$lista_tipos,
			'e'=>$e,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $s)
	{
		$model=$this->loadModel($id);

		$est_act = $model->Estado;

		if($model->Estado_Rechazo === null ){ $r = 0; }else{ $r = $model->Estado_Rechazo; }

		$tipos = Yii::app()->db->createCommand("SELECT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $tip) {
			$lista_tipos[$tip['Id_Criterio']] = $tip['Criterio_Descripcion'];
		}

		//detalle 
		$detalle=new DetSolProm('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Sol_Prom = $id;

		//permiso editar
		$e = 0;

		switch ($s) {
		    case 1:
				$model->Scenario = 'rev_gerencia';
				if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariossolprom(2))){
		        	$e = 1;
		        }		        
		        break;
		    case 2:
		        $model->Scenario = 'rev_planeacion';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariossolprom(3))){
		        	$e = 1;
		        }
		        break;
		    case 3:
		        $model->Scenario  = 'reg_logistica';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariossolprom(4))){
		        	$e = 1;
		        }
		        break;
		   	case 4:
		        $model->Scenario  = 'finalizacion';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(5))){
		        	$e = 1;
		        }
		        break;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SolProm']))
		{
			$model->attributes=$_POST['SolProm'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			switch ($model->Estado) {
			    case 0:
					$model->Estado_Rechazo = $est_act;
					$model->Estado = 0;
					$emails_envio = array();
		    		if($est_act == 1){
		    			$model->Observaciones_Ger = $_POST['SolProm']['Observaciones_Ger'];
		    			$model->Fecha = date('Y-m-d H:i:s');
						$model->Fecha_T_Entrega = date("Y-m-d",strtotime(date('Y-m-d')." + 20 days"));
		    		}
		    		if($est_act == 2){
		    			$model->Observaciones_Pla = $_POST['SolProm']['Observaciones_Pla'];
		    			if(isset($_POST['SolProm']['Val_Compra'])){ $model->Val_Compra = 1; }else{ $model->Val_Compra = 0; }
			    		if(isset($_POST['SolProm']['Val_Prod'])){ $model->Val_Prod = 1; }else{ $model->Val_Prod = 0; }
			    		if(isset($_POST['SolProm']['Val_MT'])){ $model->Val_MT = 1; }else{ $model->Val_MT = 0; }
			    	}
			        break;
			    case 2:
			    	if($est_act != $model->Estado){
			    		$model->Fecha = date('Y-m-d H:i:s');
						$model->Fecha_T_Entrega = date("Y-m-d",strtotime(date('Y-m-d')." + 20 days"));
						$emails_envio = UtilidadesMail::emailssolprom(3);
			    	}else{
			    		if(isset($_POST['SolProm']['Val_Compra'])){ $model->Val_Compra = 1; }else{ $model->Val_Compra = 0; }
			    		if(isset($_POST['SolProm']['Val_Prod'])){ $model->Val_Prod = 1; }else{ $model->Val_Prod = 0; }
			    		if(isset($_POST['SolProm']['Val_MT'])){ $model->Val_MT = 1; }else{ $model->Val_MT = 0; }
			    		$emails_envio = array();
			    	}
			    	break;
		    	case 3:
		    		$model->Estado_Rechazo = null;
		    		$emails_envio = UtilidadesMail::emailssolprom(4);
			    	break;
			   	case 4:
			    	$emails_envio = UtilidadesMail::emailssolprom(5);
			    	break;
			}

			if($model->save()){
				if($model->Estado != 0){
					if(!empty($emails_envio)){
						$resp = UtilidadesMail::enviosolprom($model->Id_Sol_Prom, $emails_envio, '');
					}
				}
				Yii::app()->user->setFlash('success', "Solicitud actualizada correctamente.");
				$this->redirect(array('admin'));	
			}

		}

		$this->render('update',array(
			'model'=>$model,
			'lista_tipos'=>$lista_tipos,
			'detalle'=>$detalle,
			's'=>$s,
			'e'=>$e,
			'r'=>$r,
		));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SolProm('search');

		$tipos = Yii::app()->db->createCommand("SELECT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $tip) {
			$lista_tipos[$tip['Id_Criterio']] = $tip['Criterio_Descripcion'];
		}

		$usuarios=Usuario::model()->findAll(array('order'=>'Nombres', 'condition'=>'Id_usuario != 1'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SolProm']))
			$model->attributes=$_GET['SolProm'];

		$this->render('admin',array(
			'model'=>$model,
			'lista_tipos'=>$lista_tipos,
			'usuarios'=>$usuarios,
		));
	}

	public function actionRedirect($id)
	{
		$model=$this->loadModel($id);
		$this->redirect(array('SolProm/update','id'=>$id, 's'=>$model->Estado));		
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return SolProm the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=SolProm::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param SolProm $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sol-prom-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchCliente(){
		$filtro = $_GET['q'];
		$estructura = trim($_GET['estructura']);

		$resp = Yii::app()->db->createCommand("
			SELECT TOP 10 C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_CIA = 2 AND (C_NIT_CLIENTE LIKE '".$filtro."%' OR C_NOMBRE_CLIENTE LIKE '".$filtro."%') AND C_ESTRUCTURA = '".$estructura."' GROUP BY C_ROWID_CLIENTE, C_NIT_CLIENTE,C_NOMBRE_CLIENTE ORDER BY C_NOMBRE_CLIENTE
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

 	public function actionSearchItem(){
		$filtro = $_GET['q'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT
			f120_id,
			CONCAT (f120_id, ' - ', f120_referencia, ' - ', f120_descripcion) AS descr
			FROM UnoEE1..t120_mc_items
			INNER JOIN UnoEE1..t134_mc_items_kits ON f134_rowid_item_ext_kit=f120_rowid
			WHERE f120_id_cia=2 AND (f120_id LIKE '".$filtro."%' OR f120_referencia LIKE '".$filtro."%' OR f120_descripcion LIKE '".$filtro."%')
		")->queryAll();

		$i = 0;
		$array_items = array();
		foreach ($resp as $t) {
    		$array_items[$i] = array('id' => $t['f120_id'],  'text' => $t['descr']);
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_items);
 	}

 	public function actionGenRepDoc($id)
	{
		$this->renderPartial('gen_rep_doc',array('id' => $id));
	}

	public function actionRev($id)
	{
		$model = $this->loadModel($id);
		$model->Scenario = 'rev_plan';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SolProm']))
		{
			$model->attributes=$_POST['SolProm'];
			$model->Estado_Rechazo = 3;
			$model->Estado= 2;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$obs= $model->Observaciones_Log;

			if($model->save())	{
				$model->Observaciones_Log = null;
				$emails_envio = UtilidadesMail::emailssolprom($model->Estado);
			    if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviosolprom($id, $emails_envio, $obs);	
				}
				$model->update();
				Yii::app()->user->setFlash('success', "Solicitud actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('rev_plan',array(
			'model'=>$model,
		));
	}


}

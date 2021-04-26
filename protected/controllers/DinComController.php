<?php

class DinComController extends Controller
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
				'actions'=>array('create','update','getcriteriosplancliente','getcriteriosplanitem'),
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
		$model=new DinCom;
		$model->Scenario = 'create';

		$lista_precios = Yii::app()->db->createCommand("SELECT DISTINCT f112_id, f112_descripcion FROM UnoEE1..t112_mc_listas_precios")->queryAll();

		$lp = array();
		foreach ($lista_precios as $reg) {
			$lp[$reg['f112_id']] = $reg['f112_descripcion'];
		}

		$centros_operacion = Yii::app()->db->createCommand("SELECT DISTINCT f285_id, f285_descripcion FROM UnoEE1..t285_co_centro_op")->queryAll();

		$co = array();
		foreach ($centros_operacion as $reg) {
			$co[$reg['f285_id']] = $reg['f285_descripcion'];
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DinCom']))
		{

			$model->attributes=$_POST['DinCom'];
			$model->Pais = implode(",", $_POST['DinCom']['Pais']);
			$model->Tipo = $_POST['DinCom']['Tipo'];

			$tipo = $model->Tipo;

			if($_POST['DinCom']['Fecha_Inicio'] != ""){
				$model->Fecha_Inicio = $_POST['DinCom']['Fecha_Inicio'];
			}else{
				$model->Fecha_Inicio = null;
			}

			if($_POST['DinCom']['Fecha_Fin'] != ""){
				$model->Fecha_Fin = $_POST['DinCom']['Fecha_Fin'];
			}else{
				$model->Fecha_Fin = null;
			}


			if($tipo == 5){
				//OBSEQUIO
				$model->Vlr_Min = null;
				$model->Vlr_Max = null;
				$model->Cant_Min = null;
				$model->Cant_Max = null;
				$model->Descuento = null;

				$model->Cant_Ped = $_POST['DinCom']['Cant_Ped'];
				$model->Cant_Obs = $_POST['DinCom']['Cant_Obs'];

			}else{
				//DEMÁS TIPOS
				$model->Cant_Ped = null;
				$model->Cant_Obs = null;


				if($_POST['DinCom']['Vlr_Min'] != ""){
					$model->Vlr_Min = $_POST['DinCom']['Vlr_Min'];
				}else{
					$model->Vlr_Min = null;
				}

				if($_POST['DinCom']['Vlr_Max'] != ""){
					$model->Vlr_Max = $_POST['DinCom']['Vlr_Max'];
				}else{
					$model->Vlr_Max = null;
				}

				if($_POST['DinCom']['Cant_Min'] != ""){
					$model->Cant_Min = $_POST['DinCom']['Cant_Min'];
				}else{
					$model->Cant_Min = null;
				}

				if($_POST['DinCom']['Cant_Max'] != ""){
					$model->Cant_Max = $_POST['DinCom']['Cant_Max'];
				}else{
					$model->Cant_Max = null;
				}

				$model->Descuento = $_POST['DinCom']['Descuento'];	
			}


			if($tipo == 1){
				//ITEM
				$model->Cliente = null;
				$model->Lista_Precios = null;
				$model->CO = null;	   
			}

			if($tipo == 2){
				//CLIENTE
			   	$model->Item = null;
				$model->Lista_Precios = null;
				$model->CO = null;	  
			}

			if($tipo == 3){
				//CRITERIO CLIENTE
				$model->Item = null;
				$model->Cliente = null;
				$model->Lista_Precios = null;
				$model->CO = null;
			}

			if($tipo == 4){
				//CRITERIO ITEM
				$model->Item = null;
				$model->Cliente = null;
				$model->Lista_Precios = null;
				$model->CO = null;
			}

			if($tipo == 5){
				//OBSEQUIO
				$model->Cliente = null;
				$model->Lista_Precios = null;
				$model->CO = null;		
			}

			if($tipo == 6){
				//LISTA PRECIOS
				$model->Item = null;
				$model->Cliente = null;
				$model->CO = null;		
			}

			if($tipo == 7){
				//CO
				$model->Item = null;
				$model->Cliente = null;
				$model->Lista_Precios = null;	
			}

			if($tipo == 8){
				//ITEM / CLIENTE
				$model->Lista_Precios = null;
				$model->CO = null;	
			}

			if($tipo == 9){
				//ITEM / CRITERIO CLIENTE

				$model->Cliente = null;
				$model->Lista_Precios = null;
				$model->CO = null;
				
			}

			if($tipo == 10){
				//ITEM / LISTA DE PRECIOS
				$model->Cliente = null;
				$model->CO = null;

			}

			if($tipo == 11){
				//ITEM / CO
				$model->Cliente = null;
				$model->Lista_Precios = null;
		
			}

			if($tipo == 12){
				//CRITERIO ITEM / CRITERIO CLIENTE

				$model->Item = null;
				$model->Cliente = null;
				$model->Lista_Precios = null;
				$model->CO = null;

			}

			if($tipo == 13){
				//CRITERIO ITEM / CLIENTE
				
				$model->Item = null;
				$model->Lista_Precios = null;
				$model->CO = null;	

			}

			if($tipo == 14){
				//CRITERIO ITEM / LISTA DE PRECIOS

				$model->Item = null;
				$model->Cliente = null;
				$model->CO = null;
				
			}

			if($tipo == 15){
				//CRITERIO ITEM / CO

				$model->Item = null;
				$model->Cliente = null;
				$model->Lista_Precios = null;
				
			}

			if($tipo == 16){
				//CRITERIO CLIENTE / LISTA DE PRECIOS

				$model->Item = null;
				$model->Cliente = null;
				$model->CO = null;
				
			}

			if($tipo == 17){
				//CRITERIO CLIENTE / CO

				$model->Item = null;
				$model->Cliente = null;
				$model->Lista_Precios = null;
				
			}

			if($tipo == 18){
				//CLIENTE / LISTA DE PRECIOS

				$model->Item = null;
				$model->CO = null;
				
			}

			if($tipo == 19){
				//CLIENTE / CO

				$model->Item = null;
				$model->Lista_Precios = null;
				
			}

			if($tipo == 20){
				//LISTA DE PRECIOS / CO

				$model->Item = null;
				$model->Cliente = null;
				
			}

			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;

			if($model->save()){	
			
				if($tipo == 3){
				//CRITERIO CLIENTE

					$array_plan_cliente = explode(',', $_POST['DinCom']['Cad_Plan_Cliente']);
					$array_criterio_cliente = explode(',', $_POST['DinCom']['Cad_Criterio_Cliente']);
				
					foreach ($array_plan_cliente as $key => $value) {
						$nuevo_cri_cliente = new DinComCliente;
						$nuevo_cri_cliente->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_cliente->Id_Plan = $value;
						$nuevo_cri_cliente->Id_Criterio = $array_criterio_cliente[$key];
						$nuevo_cri_cliente->save();
					}
				}

				if($tipo == 4){
					//CRITERIO ITEM

					$array_plan_item = explode(',', $_POST['DinCom']['Cad_Plan_Item']);
					$array_criterio_item = explode(',', $_POST['DinCom']['Cad_Criterio_Item']);
				
					foreach ($array_plan_item as $key => $value) {
						$nuevo_cri_item = new DinComItem;
						$nuevo_cri_item->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_item->Id_Plan = $value;
						$nuevo_cri_item->Id_Criterio = $array_criterio_item[$key];
						$nuevo_cri_item->save();
					}
					
				}

				if($tipo == 9){
					//ITEM / CRITERIO CLIENTE

					$array_plan_cliente = explode(',', $_POST['DinCom']['Cad_Plan_Cliente']);
					$array_criterio_cliente = explode(',', $_POST['DinCom']['Cad_Criterio_Cliente']);
				
					foreach ($array_plan_cliente as $key => $value) {
						$nuevo_cri_cliente = new DinComCliente;
						$nuevo_cri_cliente->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_cliente->Id_Plan = $value;
						$nuevo_cri_cliente->Id_Criterio = $array_criterio_cliente[$key];
						$nuevo_cri_cliente->save();
					}
					
				}

				if($tipo == 12){
					//CRITERIO ITEM / CRITERIO CLIENTE

					$array_plan_item = explode(',', $_POST['DinCom']['Cad_Plan_Item']);
					$array_criterio_item = explode(',', $_POST['DinCom']['Cad_Criterio_Item']);
				
					foreach ($array_plan_item as $key => $value) {
						$nuevo_cri_item = new DinComItem;
						$nuevo_cri_item->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_item->Id_Plan = $value;
						$nuevo_cri_item->Id_Criterio = $array_criterio_item[$key];
						$nuevo_cri_item->save();
					}

					$array_plan_cliente = explode(',', $_POST['DinCom']['Cad_Plan_Cliente']);
					$array_criterio_cliente = explode(',', $_POST['DinCom']['Cad_Criterio_Cliente']);
				
					foreach ($array_plan_cliente as $key => $value) {
						$nuevo_cri_cliente = new DinComCliente;
						$nuevo_cri_cliente->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_cliente->Id_Plan = $value;
						$nuevo_cri_cliente->Id_Criterio = $array_criterio_cliente[$key];
						$nuevo_cri_cliente->save();
					}	
				}

				if($tipo == 13){
					//CRITERIO ITEM / CLIENTE
					
					$array_plan_item = explode(',', $_POST['DinCom']['Cad_Plan_Item']);
					$array_criterio_item = explode(',', $_POST['DinCom']['Cad_Criterio_Item']);
				
					foreach ($array_plan_item as $key => $value) {
						$nuevo_cri_item = new DinComItem;
						$nuevo_cri_item->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_item->Id_Plan = $value;
						$nuevo_cri_item->Id_Criterio = $array_criterio_item[$key];
						$nuevo_cri_item->save();
					}	

				}

				if($tipo == 14){
					//CRITERIO ITEM / LISTA DE PRECIOS

					$array_plan_item = explode(',', $_POST['DinCom']['Cad_Plan_Item']);
					$array_criterio_item = explode(',', $_POST['DinCom']['Cad_Criterio_Item']);
				
					foreach ($array_plan_item as $key => $value) {
						$nuevo_cri_item = new DinComItem;
						$nuevo_cri_item->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_item->Id_Plan = $value;
						$nuevo_cri_item->Id_Criterio = $array_criterio_item[$key];
						$nuevo_cri_item->save();
					}
					
				}

				if($tipo == 15){
					//CRITERIO ITEM / CO

					$array_plan_item = explode(',', $_POST['DinCom']['Cad_Plan_Item']);
					$array_criterio_item = explode(',', $_POST['DinCom']['Cad_Criterio_Item']);
				
					foreach ($array_plan_item as $key => $value) {
						$nuevo_cri_item = new DinComItem;
						$nuevo_cri_item->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_item->Id_Plan = $value;
						$nuevo_cri_item->Id_Criterio = $array_criterio_item[$key];
						$nuevo_cri_item->save();
					}
					
				}

				if($tipo == 16){
					//CRITERIO CLIENTE / LISTA DE PRECIOS

					$array_plan_cliente = explode(',', $_POST['DinCom']['Cad_Plan_Cliente']);
					$array_criterio_cliente = explode(',', $_POST['DinCom']['Cad_Criterio_Cliente']);
				
					foreach ($array_plan_cliente as $key => $value) {
						$nuevo_cri_cliente = new DinComCliente;
						$nuevo_cri_cliente->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_cliente->Id_Plan = $value;
						$nuevo_cri_cliente->Id_Criterio = $array_criterio_cliente[$key];
						$nuevo_cri_cliente->save();
					}
					
				}

				if($tipo == 17){
					//CRITERIO CLIENTE / CO

					$array_plan_cliente = explode(',', $_POST['DinCom']['Cad_Plan_Cliente']);
					$array_criterio_cliente = explode(',', $_POST['DinCom']['Cad_Criterio_Cliente']);
				
					foreach ($array_plan_cliente as $key => $value) {
						$nuevo_cri_cliente = new DinComCliente;
						$nuevo_cri_cliente->Id_Din_Com = $model->Id_Dic_Com;
						$nuevo_cri_cliente->Id_Plan = $value;
						$nuevo_cri_cliente->Id_Criterio = $array_criterio_cliente[$key];
						$nuevo_cri_cliente->save();
					}
					
				}

				Yii::app()->user->setFlash('success', "Dinámica creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'lp'=>$lp,
			'co'=>$co,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$array_cri_cliente = array(3, 9, 12, 16, 17);

		$array_cri_item = array(4, 12, 13, 14, 15);

		//criterios cliente
		if(in_array($model->Tipo, $array_cri_cliente)) {
			$criterio_cliente=new DinComCliente('search');
			$criterio_cliente->unsetAttributes();  // clear any default values
			$criterio_cliente->Id_Din_Com = $id;
		}else{
			$criterio_cliente=new DinComCliente('search');
			$criterio_cliente->unsetAttributes();  // clear any default values
			$criterio_cliente->Id_Din_Com = 0;
		}

		if(in_array($model->Tipo, $array_cri_item)) {
			$criterio_item=new DinComItem('search');
			$criterio_item->unsetAttributes();  // clear any default values
			$criterio_item->Id_Din_Com = $id;
		}else{
			$criterio_item=new DinComItem('search');
			$criterio_item->unsetAttributes();  // clear any default values
			$criterio_item->Id_Din_Com = 0;
		}

		if(isset($_POST['DinCom']))
		{
			$model->Estado = $_POST['DinCom']['Estado'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){	
				Yii::app()->user->setFlash('success', "Dinámica actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'criterio_cliente'=>$criterio_cliente,
			'criterio_item'=>$criterio_item,
		));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DinCom('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DinCom']))
			$model->attributes=$_GET['DinCom'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DinCom the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DinCom::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DinCom $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='din-com-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGetCriteriosPlanCliente(){
		
 		$plan = $_POST['plan'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = ".$plan." ORDER BY Id_Criterio
		")->queryAll();

		$i = 0;
		$array_cri = array();
		foreach ($resp as $t) {
    		$array_cri[$i] = array('id' => trim($t['Id_Criterio']),  'text' => trim($t['Criterio_Descripcion']));
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_cri);
 	}

 	public function actionGetCriteriosPlanItem(){
		
 		$plan = $_POST['plan'];

		$resp = Yii::app()->db->createCommand("
			SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = ".$plan." ORDER BY Id_Criterio
		")->queryAll();

		$i = 0;
		$array_cri = array();
		foreach ($resp as $t) {
    		$array_cri[$i] = array('id' => trim($t['Id_Criterio']),  'text' => trim($t['Criterio_Descripcion']));
    		$i++; 
	    }

	    //se retorna un json con las opciones
		echo json_encode($array_cri);
 	}
}

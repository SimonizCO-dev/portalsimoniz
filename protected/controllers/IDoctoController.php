<?php

class IDoctoController extends Controller
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
				'actions'=>array('create','update','aprodoc','anuldoc','genrepdoc','movimientos','kardex','searchEmpleado','searchEmpleadobyid','ent','sal','movimientospant','cosinvbod','cosinvbodpant','cosinvtot','cosinvtotpant'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','admin2','delete'),
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
		
		$model = $this->loadModel($id);

		//detalle 
		$detalle=new IDoctoMovto('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Docto = $model->Id;

		$this->render('view',array(
			'model'=>$model,
			'detalle'=>$detalle,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new IDocto;

		$bodegas_hab = Yii::app()->user->getState('array_bodegas');
		$td_hab = Yii::app()->user->getState('array_td');

		$bodegas_activas = implode(",",$bodegas_hab);
		$criteria=new CDbCriteria;
		$criteria->condition='Id IN ('.$bodegas_activas.')';
		$bodegas=IBodega::model()->findAll($criteria);
					
		$tipos_activos = implode(",",$td_hab);
		$criteria=new CDbCriteria;
		$criteria->condition='Id IN ('.$tipos_activos.')';
		$tipos=ITipoDocto::model()->findAll($criteria);	
			
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IDocto']))
		{
			$model->attributes=$_POST['IDocto'];

			$q_cons = Yii::app()->db->createCommand("SELECT MAX(Consecutivo) + 1 AS CONS FROM T_PR_I_DOCTO WHERE Id_Tipo_Docto = ".$model->Id_Tipo_Docto)->queryRow();
			$cons = $q_cons['CONS'];

			if(is_null($cons)){
				$cons = 1;
			}

			if($model->Id_Tipo_Docto == Yii::app()->params->ent){
				//entrada

				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_destino = explode(",", $_POST['IDocto']['cad_bodega_destino']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);
				$vlrs = explode(",", $_POST['IDocto']['cad_vlr']);

				$model->Id_Emp = NULL;
				$model->Notas = NULL;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

				if($model->save()){

					$total = 0;

					foreach ($items as $key => $valor) {
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = null;
						$nuevo_det->Id_Bodega_Dst = $bodegas_destino[$key];
						$nuevo_det->Id_Item = $valor;
						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = $vlrs[$key];
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						if($nuevo_det->save()){
							$total = $total + ($vlrs[$key] * $cants[$key]);	
						}

					}

					$model->Vlr_Total = $total;
					$model->save();

					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}

			}

			if($model->Id_Tipo_Docto == Yii::app()->params->sal){
				//salida
			
				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_origen = explode(",", $_POST['IDocto']['cad_bodega_origen']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);

				$model->Id_Emp = NULL;
				$model->Notas = NULL;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				if($model->save()){

					foreach ($items as $key => $valor) {
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = $bodegas_origen[$key];
						$nuevo_det->Id_Bodega_Dst = null;
						$nuevo_det->Id_Item = $valor;
						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = 0;
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$nuevo_det->save();
								
					}

					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
			
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}
			}

			if($model->Id_Tipo_Docto == Yii::app()->params->trb){
				//transferencia
			
				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_origen = explode(",", $_POST['IDocto']['cad_bodega_origen']);
				$bodegas_destino = explode(",", $_POST['IDocto']['cad_bodega_destino']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);

				$model->Id_Emp = NULL;
				$model->Notas = NULL;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				if($model->save()){

					foreach ($items as $key => $valor) {
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = $bodegas_origen[$key];
						$nuevo_det->Id_Bodega_Dst = $bodegas_destino[$key];
						$nuevo_det->Id_Item = $valor;
						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = 0;
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$nuevo_det->save();

					}

				
					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
				
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}

			}

			if($model->Id_Tipo_Docto == Yii::app()->params->aje){
				//ajuste entrada
			
				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_destino = explode(",", $_POST['IDocto']['cad_bodega_destino']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);
				$notas = $_POST['IDocto']['Notas'];

				$model->Id_Emp = NULL;
				$model->Notas = $notas;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				if($model->save()){

					foreach ($items as $key => $valor) {
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = null;
						$nuevo_det->Id_Bodega_Dst = $bodegas_destino[$key];
						$nuevo_det->Id_Item = $valor;
						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = 0;
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$nuevo_det->save();
								
					}
				
					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}

			}

			if($model->Id_Tipo_Docto == Yii::app()->params->ajs){
				//ajuste salida
			
				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_origen = explode(",", $_POST['IDocto']['cad_bodega_origen']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);
				$notas = $_POST['IDocto']['Notas'];

				$model->Id_Emp = NULL;
				$model->Notas = $notas;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				if($model->save()){

					foreach ($items as $key => $valor) {
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = $bodegas_origen[$key];
						$nuevo_det->Id_Bodega_Dst = null;
						$nuevo_det->Id_Item = $valor;
						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = 0;
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$nuevo_det->save();
								
					}

					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}

			}

			if($model->Id_Tipo_Docto == Yii::app()->params->sad){
				//salida de dotacion
			
				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_origen = explode(",", $_POST['IDocto']['cad_bodega_origen']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);

				$model->Notas = NULL;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				if($model->save()){

					foreach ($items as $key => $valor) {
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = $bodegas_origen[$key];
						$nuevo_det->Id_Bodega_Dst = null;
						$nuevo_det->Id_Item = $valor;
						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = 0;
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						$nuevo_det->save();
					}
								
					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}


			}

			if($model->Id_Tipo_Docto == Yii::app()->params->dev){
				//Devolución
			
				$items = explode(",", $_POST['IDocto']['cad_item']);
				$bodegas_destino = explode(",", $_POST['IDocto']['cad_bodega_destino']);
				$cants = explode(",", $_POST['IDocto']['cad_cant']);

				$model->Id_Emp = NULL;
				$model->Notas = NULL;
				$model->Consecutivo = $cons;
				$model->Vlr_Total = 0;
				$model->Id_Estado = Yii::app()->params->elab;
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
				if($model->save()){

					foreach ($items as $key => $valor) {

						$total = 0;
						
						$nuevo_det = new IDoctoMovto;
						$nuevo_det->Id_Docto = $model->Id;
						$nuevo_det->Id_Bodega_Org = null;
						$nuevo_det->Id_Bodega_Dst = $bodegas_destino[$key];
						$nuevo_det->Id_Item = $valor;

						$modelo_i = IItem::model()->findBypk($valor);

						if($modelo_i->Vlr_Costo != 0 || $modelo_i->Total_Inventario != 0){
							$valor_item = $modelo_i->Vlr_Costo / $modelo_i->Total_Inventario;
						}else{
							$valor_item = 0;
						}

						$nuevo_det->Cantidad = $cants[$key];
						$nuevo_det->Vlr_Unit_Item = $valor_item;
						$nuevo_det->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$nuevo_det->Fecha_Creacion = date('Y-m-d H:i:s');
						$nuevo_det->Fecha_Actualizacion = date('Y-m-d H:i:s');
						if($nuevo_det->save()){
							$total = $total + ($valor_item * $cants[$key]);	
						}
								
					}

					$model->Vlr_Total = $total;
					$model->save();

					Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue creado correctamente.");
				}else{
					Yii::app()->user->setFlash('warning', "No se pudo crear el documento.");
				}

			}

			$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
			'tipos'=>$tipos,
			'bodegas'=>$bodegas,
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
		$detalle=new IDoctoMovto('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Docto = $model->Id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IDocto']))
		{
			$model->attributes=$_POST['IDocto'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue actualizado correctamente.");
			}else{
				Yii::app()->user->setFlash('warning', "No se pudo actualizar el documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.").");
			}

			$this->redirect(array('admin'));
			
		}

		$this->render('update',array(
			'model'=>$model,
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new IDocto('search');
		
		$estados=IEstadoDocto::model()->findAll(array('order'=>'Descripcion'));
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$bodegas_hab = Yii::app()->user->getState('array_bodegas');
		$td_hab = Yii::app()->user->getState('array_td');

		if(empty($bodegas_hab) || empty($td_hab)){
			$v = 0;

			if(empty($bodegas_hab)){
				$bodegas=IBodega::model()->findAll(array('order'=>'Descripcion'));
			}else{
				$bodegas_activas = implode(",",$bodegas_hab);
				$criteria=new CDbCriteria;
				$criteria->condition='Id_Bodega IN ('.$bodegas_activas.')';
				$bodegas=IBodega::model()->findAll($criteria);
			}

			if(empty($td_hab)){
				$tipos=ITipoDocto::model()->findAll(array('order'=>'Descripcion'));
			}else{
				$tipos_activos = implode(",",$td_hab);
				$criteria=new CDbCriteria;
				$criteria->condition='Id_Tipo_Docto IN ('.$tipos_activos.')';
				$tipos=ITipoDocto::model()->findAll($criteria);	
			}

		}else{
		
			$v = 1;

			$bodegas_activas = implode(",",$bodegas_hab);
			$criteria=new CDbCriteria;
			$criteria->condition='Id IN ('.$bodegas_activas.')';
			$bodegas=IBodega::model()->findAll($criteria);
		
			$tipos_activos = implode(",",$td_hab);
			$criteria=new CDbCriteria;
			$criteria->condition='Id IN ('.$tipos_activos.')';
			$tipos=ITipoDocto::model()->findAll($criteria);

		}

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['IDocto']))
			$model->attributes=$_GET['IDocto'];

		$this->render('admin',array(
			'model'=>$model,
			'tipos'=>$tipos,
			'bodegas'=>$bodegas,
			'estados'=>$estados,
			'usuarios'=>$usuarios,
			'v'=>$v,
		));
	}

	public function actionAdmin2()
	{
		$model=new IDocto('search');

		$estados=IEstadoDocto::model()->findAll(array('order'=>'Descripcion'));
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$bodegas_hab = Yii::app()->user->getState('array_bodegas');
		$td_hab = Yii::app()->user->getState('array_td');

		if(empty($bodegas_hab) || empty($td_hab)){
			$v = 0;

			if(empty($bodegas_hab)){
				$bodegas=IBodega::model()->findAll(array('order'=>'Descripcion'));
			}else{
				$bodegas_activas = implode(",",$bodegas_hab);
				$criteria=new CDbCriteria;
				$criteria->condition='Id_Bodega IN ('.$bodegas_activas.')';
				$bodegas=IBodega::model()->findAll($criteria);
			}

			if(empty($td_hab)){
				$tipos=ITipoDocto::model()->findAll(array('order'=>'Descripcion'));
			}else{
				$tipos_activos = implode(",",$td_hab);
				$criteria=new CDbCriteria;
				$criteria->condition='Id_Tipo_Docto IN ('.$tipos_activos.')';
				$tipos=ITipoDocto::model()->findAll($criteria);	
			}

		}else{
			
			$v = 1;

			$bodegas_activas = implode(",",$bodegas_hab);
			$criteria=new CDbCriteria;
			$criteria->condition='Id IN ('.$bodegas_activas.')';
			$bodegas=IBodega::model()->findAll($criteria);
		
			$tipos_activos = implode(",",$td_hab);
			$criteria=new CDbCriteria;
			$criteria->condition='Id IN ('.$tipos_activos.')';
			$tipos=ITipoDocto::model()->findAll($criteria);

		}

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['IDocto']))
			$model->attributes=$_GET['IDocto'];

		$this->render('admin2',array(
			'model'=>$model,
			'tipos'=>$tipos,
			'bodegas'=>$bodegas,
			'estados'=>$estados,
			'usuarios'=>$usuarios,
			'v'=>$v,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IDocto the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IDocto::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IDocto $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='idocto-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAproDoc($id)
	{
		
		$model=$this->loadModel($id);

		if($model->Id_Tipo_Docto == Yii::app()->params->ent){
			//entrada
		
			$command = Yii::app()->db->createCommand();
			$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
			$command->setText($sql)->execute();	

			$model->Id_Estado = Yii::app()->params->apro;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->sal){
			//salida

			$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

			if(!empty($modelo_detalle)){

				$valid = 1;
				$msg = "";

				foreach ($modelo_detalle as $det) {

					$item = $det->Id_Item;
					$modelo_item = IItem::model()->findByPk($item);
					$desc_item = $modelo_item->Id_Item." (".$modelo_item->Referencia." - ".$modelo_item->Descripcion.")";
					
					$bodega_origen = 	$det->Id_Bodega_Org;
					$modelo_bodega = IBodega::model()->findByPk($bodega_origen);
					$desc_bodega = $modelo_bodega->Descripcion;

					$cantidad_solicitada = 	$det->Cantidad;

					$modelo_existencia = IExistencia::model()->findByAttributes(array('Id_Bodega' => $bodega_origen, 'Id_Item' => $item));

					if(is_null($modelo_existencia)){

						$valid = 0;
						$msg .= "- El item ".$desc_item." No tiene existencias en la bodega ".$desc_bodega.".<br>";

					}else{

						$cantidad_en_bodega = $modelo_existencia->Cantidad;

						if($cantidad_en_bodega < $cantidad_solicitada){
							$valid = 0;
							$msg .= "- No esta disponible la cantidad solicitada (".$cantidad_solicitada.") para el item ".$desc_item." / ".$cantidad_en_bodega." Disponible(s).<br>";
						}
					}
				}

				if($valid == 1){
					//se puede aprobar la salida

					$command = Yii::app()->db->createCommand();
					$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
					$command->setText($sql)->execute();	

					$total_doc = 0;

					$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

					foreach ($modelo_detalle as $det) {
						$total_doc = $total_doc + ($det->Vlr_Unit_Item * $det->Cantidad);
					}

					$model->Vlr_Total = $total_doc;
					$model->Id_Estado = Yii::app()->params->apro;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

					if($model->save()){
						Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
						$this->redirect(array('admin2'));
					}

				}else{
					//no se puede aprobar la salida

					Yii::app()->user->setFlash('warning', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") no se pudo aprobar por los siguientes motivos: <br>".$msg);
						$this->redirect(array('admin2'));
				}
			}
		}


		if($model->Id_Tipo_Docto == Yii::app()->params->trb){
			//transferencia

			$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

			if(!empty($modelo_detalle)){

				$valid = 1;
				$msg = "";

				foreach ($modelo_detalle as $det) {

					$item = $det->Id_Item;
					$modelo_item = IItem::model()->findByPk($item);
					$desc_item = $modelo_item->Id_Item." (".$modelo_item->Referencia." - ".$modelo_item->Descripcion.")";
					
					$bodega_origen = 	$det['Id_Bodega_Org'];
					$modelo_bodega = IBodega::model()->findByPk($bodega_origen);
					$desc_bodega = $modelo_bodega->Descripcion;

					$cantidad_solicitada = 	$det['Cantidad'];

					$modelo_existencia = IExistencia::model()->findByAttributes(array('Id_Bodega' => $bodega_origen, 'Id_Item' => $item));

					if(is_null($modelo_existencia)){

						$valid = 0;
						$msg .= "- El item ".$desc_item." No tiene existencias en la bodega ".$desc_bodega.".<br>";

					}else{

						$cantidad_en_bodega = $modelo_existencia->Cantidad;

						if($cantidad_en_bodega < $cantidad_solicitada){
							$valid = 0;
							$msg .= "- No esta disponible la cantidad solicitada (".$cantidad_solicitada.") para el item ".$desc_item." / ".$cantidad_en_bodega." Disponible(s).<br>";
						}
					}
				}

				if($valid == 1){
					//se puede aprobar la transferencia

					$command = Yii::app()->db->createCommand();
					$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
					$command->setText($sql)->execute();

					$total_doc = 0;

					$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

					foreach ($modelo_detalle as $det) {
						$total_doc = $total_doc + ($det->Vlr_Unit_Item * $det->Cantidad);
					}

					$model->Vlr_Total = $total_doc;
					$model->Id_Estado = Yii::app()->params->apro;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

					if($model->save()){
						Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
						$this->redirect(array('admin2'));
					}

				}else{
					//no se puede aprobar la salida

					Yii::app()->user->setFlash('warning', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") no se pudo aprobar por los siguientes motivos: <br>".$msg);
						$this->redirect(array('admin2'));
				}
			}

		}

		if($model->Id_Tipo_Docto == Yii::app()->params->aje){
			//ajuste por entrada

			$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

			if(!empty($modelo_detalle)){

				$valid = 1;
				$msg = "";

				foreach ($modelo_detalle as $det) {

					$item = $det->Id_Item;
					$modelo_item = IItem::model()->findByPk($item);
					$desc_item = $modelo_item->Id_Item." (".$modelo_item->Referencia." - ".$modelo_item->Descripcion.")";
					
					$bodega_destino = 	$det->Id_Bodega_Dst;
					$modelo_bodega = IBodega::model()->findByPk($bodega_destino);
					$desc_bodega = $modelo_bodega->Descripcion;

					$modelo_existencia = IExistencia::model()->findByAttributes(array('Id_Bodega' => $bodega_destino, 'Id_Item' => $item));

					if(is_null($modelo_existencia)){

						$valid = 0;
						$msg .= "- El item ".$desc_item." No ha tenido movimientos en la bodega ".$desc_bodega.".<br>";

					}
				}

				if($valid == 1){
					//se puede aprobar el ajuste por salida

					$command = Yii::app()->db->createCommand();
					$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
					$command->setText($sql)->execute();

					$total_doc = 0;

					$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

					foreach ($modelo_detalle as $det) {
						$total_doc = $total_doc + ($det->Vlr_Unit_Item * $det->Cantidad);
					}

					$model->Vlr_Total = $total_doc;
					$model->Id_Estado = Yii::app()->params->apro;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

					if($model->save()){
						Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
						$this->redirect(array('admin2'));
					}

				}else{
					//no se puede aprobar el ajuste por salida

					Yii::app()->user->setFlash('warning', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") no se pudo aprobar por los siguientes motivos: <br>".$msg);
						$this->redirect(array('admin2'));
				}
			}

		}

		if($model->Id_Tipo_Docto == Yii::app()->params->ajs){
			//ajuste por salida

			$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

			if(!empty($modelo_detalle)){

				$valid = 1;
				$msg = "";

				foreach ($modelo_detalle as $det) {

					$item = $det->Id_Item;
					$modelo_item = IItem::model()->findByPk($item);
					$desc_item = $modelo_item->Id_Item." (".$modelo_item->Referencia." - ".$modelo_item->Descripcion.")";
					
					$bodega_origen = 	$det->Id_Bodega_Org;
					$modelo_bodega = IBodega::model()->findByPk($bodega_origen);
					$desc_bodega = $modelo_bodega->Descripcion;

					$cantidad_solicitada = 	$det->Cantidad;

					$modelo_existencia = IExistencia::model()->findByAttributes(array('Id_Bodega' => $bodega_origen, 'Id_Item' => $item));

					if(is_null($modelo_existencia)){

						$valid = 0;
						$msg .= "- El item ".$desc_item." No tiene existencias en la bodega ".$desc_bodega.".<br>";

					}else{

						$cantidad_en_bodega = $modelo_existencia->Cantidad;

						if($cantidad_en_bodega < $cantidad_solicitada){
							$valid = 0;
							$msg .= "- No esta disponible la cantidad solicitada (".$cantidad_solicitada.") para el item ".$desc_item." / ".$cantidad_en_bodega." Disponible(s).<br>";
						}
					}
				}

				if($valid == 1){
					//se puede aprobar el ajuste por salida

					$command = Yii::app()->db->createCommand();
					$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
					$command->setText($sql)->execute();	

					$total_doc = 0;

					$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

					foreach ($modelo_detalle as $det) {
						$total_doc = $total_doc + ($det->Vlr_Unit_Item * $det->Cantidad);
					}

					$model->Vlr_Total = $total_doc;
					$model->Id_Estado = Yii::app()->params->apro;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

					if($model->save()){
						Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
						$this->redirect(array('admin2'));
					}

				}else{
					//no se puede aprobar el ajuste por salida

					Yii::app()->user->setFlash('warning', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") no se pudo aprobar por los siguientes motivos: <br>".$msg);
						$this->redirect(array('admin2'));
				}
			}

		}

		if($model->Id_Tipo_Docto == Yii::app()->params->sad){
			//salida de dotación

			$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

			if(!empty($modelo_detalle)){

				$valid = 1;
				$msg = "";

				foreach ($modelo_detalle as $det) {

					$item = $det->Id_Item;
					$modelo_item = IItem::model()->findByPk($item);
					$desc_item = $modelo_item->Id_Item." (".$modelo_item->Referencia." - ".$modelo_item->Descripcion.")";
					
					$bodega_origen = 	$det->Id_Bodega_Org;
					$modelo_bodega = IBodega::model()->findByPk($bodega_origen);
					$desc_bodega = $modelo_bodega->Descripcion;

					$cantidad_solicitada = 	$det->Cantidad;

					$modelo_existencia = IExistencia::model()->findByAttributes(array('Id_Bodega' => $bodega_origen, 'Id_Item' => $item));

					if(is_null($modelo_existencia)){

						$valid = 0;
						$msg .= "- El item ".$desc_item." No tiene existencias en la bodega ".$desc_bodega.".<br>";

					}else{

						$cantidad_en_bodega = $modelo_existencia->Cantidad;

						if($cantidad_en_bodega < $cantidad_solicitada){
							$valid = 0;
							$msg .= "- No esta disponible la cantidad solicitada (".$cantidad_solicitada.") para el item ".$desc_item." / ".$cantidad_en_bodega." Disponible(s).<br>";
						}
					}
				}

				if($valid == 1){
					//se puede aprobar la salida de dotación

					$command = Yii::app()->db->createCommand();
					$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
					$command->setText($sql)->execute();	

					$total_doc = 0;

					$modelo_detalle = IDoctoMovto::model()->findAllByAttributes(array('Id_Docto' => $id));

					foreach ($modelo_detalle as $det) {
						$total_doc = $total_doc + ($det->Vlr_Unit_Item * $det->Cantidad);
					}

					$model->Vlr_Total = $total_doc;
					$model->Id_Estado = Yii::app()->params->apro;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

					if($model->save()){
						Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
						$this->redirect(array('admin2'));
					}

				}else{
					//no se puede aprobar la salida de dotación

					Yii::app()->user->setFlash('warning', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") no se pudo aprobar por los siguientes motivos: <br>".$msg);
						$this->redirect(array('admin2'));
				}
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->dev){
			//devolución
		
			$command = Yii::app()->db->createCommand();
			$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->apro;
			$command->setText($sql)->execute();	

			$model->Id_Estado = Yii::app()->params->apro;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue aprobado correctamente.");
				$this->redirect(array('admin2'));
			}
		}
	}


	public function actionAnulDoc($id)
	{
		$model=$this->loadModel($id);

		if($model->Id_Tipo_Docto == Yii::app()->params->ent){
			//entrada

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();	

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->sal){
			//salida

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();		

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}


		if($model->Id_Tipo_Docto == Yii::app()->params->trb){
			//transferencia

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();		

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->aje){
			//ajuste por entrada

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();		

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->ajs){
			//ajuste por salida

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();			

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->sad){
			//salida de dotación

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();		

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}

		if($model->Id_Tipo_Docto == Yii::app()->params->dev){
			//devolución

			if($model->Id_Estado == Yii::app()->params->apro){
			
				$command = Yii::app()->db->createCommand();
				$sql='EXEC P_CF_INV_ESTADOS_DOCTO @Id_Docto = '.$model->Id.', @Id_Tipo_Docto = '.$model->Id_Tipo_Docto.', @Id_Estado = '.Yii::app()->params->anul;
				$command->setText($sql)->execute();	

			}

			$model->Id_Estado = Yii::app()->params->anul;;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
				Yii::app()->user->setFlash('success', "El Documento # ".$model->Consecutivo." (".$model->idtipodocto->Descripcion.") fue anulado correctamente.");
				$this->redirect(array('admin2'));
			}
		}
		
	}

	public function actionGenRepDoc($id)
	{
		$this->renderPartial('gen_rep_doc',array('id' => $id));
	}

	public function actionMovimientos()
	{		
		$model=new IDocto;
		$model->scenario = 'movimientos';

		$tipos=ITipoDocto::model()->findAll(array('order'=>'Descripcion'));
		$bodegas=IBodega::model()->findAll(array('order'=>'Descripcion'));
		$estados=IEstadoDocto::model()->findAll(array('order'=>'Descripcion'));

		if(isset($_POST['IDocto']))
		{
			$model=$_POST['IDocto'];
			$this->renderPartial('movimientos_resp',array('model' => $model));	
		}

		$this->render('movimientos',array(
			'model'=>$model,
			'tipos'=>$tipos,
			'bodegas'=>$bodegas,
			'estados'=>$estados,
		));
	}

	public function actionKardex()
	{		
		$model=new IDocto;
		$model->scenario = 'kardex';

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		if(isset($_POST['IDocto']))
		{
			$model=$_POST['IDocto'];
			$this->renderPartial('kardex_resp',array('model' => $model));	
		}

		$this->render('kardex',array(
			'model'=>$model,
			'lineas'=>$lineas,
		));
	}

	public function actionSearchEmpleado(){
		$filtro = $_GET['q'];
        $data = IDocto::model()->searchByEmpleado($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Empleado'],
               'text' => $item['Empleado'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionSearchEmpleadoById(){
		$filtro = $_GET['id'];
        $data = IDocto::model()->searchById($filtro);

        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Empleado'],
               'text' => $item['Empleado'],
           );
        endforeach;

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionEnt()
	{		
		$model=new IDocto;
		$model->scenario = 'ent';

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		if(isset($_POST['IDocto']))
		{
			$model=$_POST['IDocto'];
			$this->renderPartial('ent_resp',array('model' => $model));	
		}

		$this->render('ent',array(
			'model'=>$model,
			'lineas'=>$lineas,
		));
	}

	public function actionSal()
	{		
		$model=new IDocto;
		$model->scenario = 'sal';

		$lineas=ILinea::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		if(isset($_POST['IDocto']))
		{
			$model=$_POST['IDocto'];
			$this->renderPartial('sal_resp',array('model' => $model));	
		}

		$this->render('sal',array(
			'model'=>$model,
			'lineas'=>$lineas,
		));
	}

	public function actionMovimientosPant()
	{	
		if (isset($_POST['tipo'])){ $tipo = $_POST['tipo']; } else { $tipo = ""; }
		if (isset($_POST['consecutivo'])){ $consecutivo = $_POST['consecutivo']; } else { $consecutivo = ""; }
		if (isset($_POST['fecha_inicial'])){ $fecha_inicial = $_POST['fecha_inicial']; } else { $fecha_inicial = ""; }
		if (isset($_POST['fecha_final'])){ $fecha_final = $_POST['fecha_final']; } else { $fecha_final = ""; }
		if (isset($_POST['tercero'])){ $tercero = $_POST['tercero']; } else { $tercero = ""; }
		if (isset($_POST['item'])){ $item = $_POST['item']; } else { $item = ""; }
		if (isset($_POST['bodega_origen'])){ $bodega_origen = $_POST['bodega_origen']; } else { $bodega_origen = ""; }
		if (isset($_POST['bodega_destino'])){ $bodega_destino = $_POST['bodega_destino']; } else { $bodega_destino = ""; }

		$resultados = UtilidadesSuministros::movimientospantalla($tipo, $consecutivo, $fecha_inicial, $fecha_final, $tercero, $item, $bodega_origen, $bodega_destino);

		echo $resultados;
	}

	public function actionCosInvBod()
	{		
		$model=new IDocto;
		$model->scenario = 'costo_inv_bod';

		$bodegas=IBodega::model()->findAll(array('order'=>'Descripcion'));

		if(isset($_POST['IDocto']))
		{
			$model=$_POST['IDocto'];
			$this->renderPartial('costo_inv_bod_resp',array('model' => $model));	
		}

		$this->render('costo_inv_bod',array(
			'model'=>$model,
			'bodegas'=>$bodegas,
		));
	}



	public function actionCosInvBodPant()
	{	
		if (isset($_POST['bodega'])){ $bodega = $_POST['bodega']; } else { $bodega = ""; }

		$resultados = UtilidadesSuministros::cosinvbodpantalla($bodega);

		echo $resultados;
	}

	public function actionCosInvTot()
	{		
		$model=new IDocto;
		$model->scenario = 'costo_inv_tot';

		if(isset($_POST['IDocto']))
		{
			$model=$_POST['IDocto'];
			$this->renderPartial('costo_inv_tot_resp',array('model' => $model));	
		}

		$this->render('costo_inv_tot',array(
			'model'=>$model,
		));
	}



	public function actionCosInvTotPant()
	{	
		if (isset($_POST['tipo'])){ $tipo = $_POST['tipo']; } else { $tipo = ""; }
		if (isset($_POST['consecutivo'])){ $consecutivo = $_POST['consecutivo']; } else { $consecutivo = ""; }
		if (isset($_POST['fecha_inicial'])){ $fecha_inicial = $_POST['fecha_inicial']; } else { $fecha_inicial = ""; }
		if (isset($_POST['fecha_final'])){ $fecha_final = $_POST['fecha_final']; } else { $fecha_final = ""; }
		if (isset($_POST['tercero'])){ $tercero = $_POST['tercero']; } else { $tercero = ""; }
		if (isset($_POST['item'])){ $item = $_POST['item']; } else { $item = ""; }
		if (isset($_POST['bodega_origen'])){ $bodega_origen = $_POST['bodega_origen']; } else { $bodega_origen = ""; }
		if (isset($_POST['bodega_destino'])){ $bodega_destino = $_POST['bodega_destino']; } else { $bodega_destino = ""; }

		$resultados = UtilidadesSuministros::cosinvtotpantalla($tipo, $consecutivo, $fecha_inicial, $fecha_final, $tercero, $item, $bodega_origen, $bodega_destino);

		echo $resultados;
	}
}

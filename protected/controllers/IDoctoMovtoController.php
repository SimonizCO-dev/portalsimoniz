<?php

class IDoctoMovtoController extends Controller
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
				'actions'=>array('create','update','verificarduplicidad'),
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

	public function actionCreate($id)
	{
		$model=new IDoctoMovto;

		$bodegas_hab = Yii::app()->user->getState('array_bodegas');

		$bodegas_activas = implode(",",$bodegas_hab);
		$criteria=new CDbCriteria;
		$criteria->condition='Id IN ('.$bodegas_activas.')';
		$bodegas=IBodega::model()->findAll($criteria);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IDoctoMovto']))
		{
			$model->attributes=$_POST['IDoctoMovto'];

			$modelo_docto = iDocto::model()->findByPk($model->Id_Docto);

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev){

				$modelo_i = IItem::model()->findBypk($model->Id_Item);

				if($modelo_i->Vlr_Costo != 0 || $modelo_i->Total_Inventario != 0){
					$valor_item = $modelo_i->Vlr_Costo / $modelo_i->Total_Inventario;
				}else{
					$valor_item = 0;
				}

				$model->Vlr_Unit_Item = $valor_item;
			}

			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){

				if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->ent || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev){
					//solo para entradas y devoluciones

					$valor_sumar = $model->Vlr_Unit_Item * $model->Cantidad;

					$modelo_docto->Vlr_Total = $modelo_docto->Vlr_Total + $valor_sumar;

					if($modelo_docto->save()){
						Yii::app()->user->setFlash('success', "El item fue agregado correctamente.");
						$this->redirect(array('iDocto/update','id'=>$id));		
					}

				}else{
					$this->redirect(array('iDocto/update','id'=>$id));		
				}

			}
		}

		$this->render('create',array(
			'model'=>$model,
			'id'=>$id,
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
		$model_ant=$this->loadModel($id);

		$model=$this->loadModel($id);

		if($model->Id_Bodega_Org != "" && $model->Id_Bodega_Dst != ""){

			$bod_sel = $model->Id_Bodega_Org.','.$model->Id_Bodega_Dst;

		}else{
			if($model->Id_Bodega_Org != "" && $model->Id_Bodega_Dst == ""){
				$bod_sel = $model->Id_Bodega_Org;
			}

			if($model->Id_Bodega_Org == "" && $model->Id_Bodega_Dst != ""){
				$bod_sel = $model->Id_Bodega_Dst;
			}
		}

		$bodegas=IBodega::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$bodegas_hab = Yii::app()->user->getState('array_bodegas');

		$bodegas_activas = implode(",",$bodegas_hab);
		$criteria=new CDbCriteria;
		$criteria->condition='Id IN ('.$bodegas_activas.','.$bod_sel.')';
		$bodegas=IBodega::model()->findAll($criteria);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['IDoctoMovto']))
		{
			$model->attributes=$_POST['IDoctoMovto'];
			$modelo_docto = iDocto::model()->findByPk($model->Id_Docto);

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev){

				$modelo_i = IItem::model()->findBypk($model->Id_Item);

				if($modelo_i->Vlr_Costo != 0 || $modelo_i->Total_Inventario != 0){
					$valor_item = $modelo_i->Vlr_Costo / $modelo_i->Total_Inventario;
				}else{
					$valor_item = 0;
				}

				$model->Vlr_Unit_Item = $valor_item;
			}
			
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save()){
			
				if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->ent  || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev){
					//solo para entradas y devoluciones

					$valor_restar = $model_ant->Vlr_Unit_Item * $model_ant->Cantidad;

					$valor_sumar = $model->Vlr_Unit_Item * $model->Cantidad;

					$modelo_docto->Vlr_Total = ($modelo_docto->Vlr_Total - $valor_restar) + $valor_sumar;

					if($modelo_docto->save()){
						Yii::app()->user->setFlash('success', "El item fue actualizado correctamente.");
						$this->redirect(array('iDocto/update','id'=>$modelo_docto->Id));		
					}

				}else{
					$this->redirect(array('iDocto/update','id'=>$modelo_docto->Id));		
				}
			}

		}

		$this->render('update',array(
			'model'=>$model,
			'id'=>$model_ant->Id_Docto,
			'bodegas'=>$bodegas,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model_det = $this->loadModel($id);
		$model_doc = IDocto::model()->findByPk($model_det->Id_Docto);

		if($model_doc->Id_Tipo_Docto == Yii::app()->params->ent || $model_doc->Id_Tipo_Docto == Yii::app()->params->dev){
			//solo entradas y devoluciones

			$vlr_restar = $model_det->Vlr_Unit_Item * $model_det->Cantidad;
			$nuevo_vlr = $model_doc->Vlr_Total - $vlr_restar;

			$model_doc->Vlr_Total = $nuevo_vlr;

			if($model_doc->save()){
				if($model_det->delete()){
					// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			        if(!isset($_GET['ajax']))
			            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
				}
			}

		}else{
			if($model_det->delete()){
				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		        if(!isset($_GET['ajax']))
		            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}	
		}
	
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return IDoctoMovto the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=IDoctoMovto::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param IDoctoMovto $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='idocto-movto-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionVerificarDuplicidad()
	{
		$docto = $_POST['docto'];
		$id_reg = $_POST['id_reg'];
		$item = $_POST['item'];
		$bodega_origen = $_POST['bodega_origen'];
		$bodega_destino = $_POST['bodega_destino'];

		$modelo_docto = IDocto::model()->findByPk($docto);

		if($id_reg == null){
			//creación

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->ent || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->aje || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev){
				//entrada / ajuste por entrada / devolución
				$q_item = Yii::app()->db->createCommand("SELECT * FROM T_PR_I_DOCTO_MOVTO WHERE Id_Docto = ".$docto." AND Id_Bodega_Dst = ".$bodega_destino." AND Id_Item = ".$item)->queryAll();

			}

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->sal || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->ajs || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->sad){
				//salida / ajuste por salida / salida de dotación

				$q_item = Yii::app()->db->createCommand("SELECT * FROM T_PR_I_DOCTO_MOVTO WHERE Id_Docto = ".$docto." AND Id_Bodega_Org = ".$bodega_origen." AND Id_Item = ".$item)->queryAll();

			}

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->trb){
				//transferencia

				$q_item = Yii::app()->db->createCommand("SELECT * FROM T_PR_I_DOCTO_MOVTO WHERE Id_Docto = ".$docto." AND Id_Bodega_Org = ".$bodega_origen." AND Id_Bodega_Dst = ".$bodega_destino." AND Id_Item = ".$item)->queryAll();

			}
			

		}else{
			
			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->ent || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->aje || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->dev){
				//entrada / ajuste por entrada / devolución
				$q_item = Yii::app()->db->createCommand("SELECT * FROM T_PR_I_DOCTO_MOVTO WHERE Id_Docto = ".$docto." AND Id_Bodega_Dst = ".$bodega_destino." AND Id_Item = ".$item." AND Id != ".$id_reg)->queryAll();

			}

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->sal || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->ajs || $modelo_docto->Id_Tipo_Docto == Yii::app()->params->sad){
				//salida / ajuste por salida / salida de dotación

				$q_item = Yii::app()->db->createCommand("SELECT * FROM T_PR_I_DOCTO_MOVTO WHERE Id_Docto = ".$docto." AND Id_Bodega_Org = ".$bodega_origen." AND Id_Item = ".$item." AND Id != ".$id_reg)->queryAll();

			}

			if($modelo_docto->Id_Tipo_Docto == Yii::app()->params->trb){
				//transferencia

				$q_item = Yii::app()->db->createCommand("SELECT * FROM T_PR_I_DOCTO_MOVTO WHERE Id_Docto = ".$docto." AND Id_Bodega_Org = ".$bodega_origen." AND Id_Bodega_Dst = ".$bodega_destino." AND Id_Item = ".$item." AND Id != ".$id_reg)->queryAll();

			}
			
		}

		if(empty($q_item)){
			echo 1;
		}else{
			echo 0;
		}
	}
}

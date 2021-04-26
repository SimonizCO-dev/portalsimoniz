<?php

class FactItemContController extends Controller
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
				'actions'=>array('create','existfact','infoitem','anul'),
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
		$detalle=new DetFactItemCont('search');
		$detalle->unsetAttributes();  // clear any default values
		$detalle->Id_Fac = $model->Id_Fac;


		$this->render('view',array(
			'model'=>$model,
			'detalle'=>$detalle,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($c)
	{
		$model=new FactItemCont;

		$items=ItemCont::model()->findAll(array('order'=>'Item', 'condition'=>'Id_Contrato = '.$c.' AND Estado = 1'));

		$user = Yii::app()->user->getState('id_user');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FactItemCont']))
		{
			//print_r($_POST['FactItemCont']);die;
			$model->attributes=$_POST['FactItemCont'];
			$model->Numero_Factura = $_POST['FactItemCont']['Numero_Factura'];
			$model->Fecha_Factura = $_POST['FactItemCont']['Fecha_Factura'];
			$model->Tasa_Cambio = $_POST['FactItemCont']['Tasa_Cambio'];
			$model->Id_Usuario_Creacion = $user;
			$model->Id_Usuario_Actualizacion = $user;
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;

			if($model->save()){

				$array_item = explode(",", $_POST['FactItemCont']['cad_item']);
				$array_cant = explode(",", $_POST['FactItemCont']['cad_cant']);
				$array_vlr_u = explode(",", $_POST['FactItemCont']['cad_vlr_u']);
				$array_iva = explode(",", $_POST['FactItemCont']['cad_iva']);

				$num_reg = count($array_item);

				for ($i = 0; $i < $num_reg; $i++) {

					$modelo_item_cont = ItemCont::model()->findByPk($array_item[$i]);
					$cant_act = $modelo_item_cont->Cant;
					$vlr_u_act = $modelo_item_cont->Vlr_Unit;
					$iva_act = $modelo_item_cont->Iva;

					$cant_nue = $array_cant[$i];
					$vlr_u_nue = $array_vlr_u[$i];
					$iva_nue = $array_iva[$i];

					if($cant_act != $cant_nue || $vlr_u_act != $vlr_u_nue || $iva_act != $iva_nue){

						$texto_novedad = "";

						if($cant_act != $cant_nue){
							$texto_novedad .= "Cant.: ".$cant_act." / ".$cant_nue.", ";
							$modelo_item_cont->Cant = $cant_nue;
						}

						if($vlr_u_act != $vlr_u_nue){
							$texto_novedad .= "Vlr. unit.: ".number_format($vlr_u_act, 2)." / ".number_format($vlr_u_nue, 2).", ";
							$modelo_item_cont->Vlr_Unit = $vlr_u_nue;
						}

						if($iva_act != $iva_nue){
							$texto_novedad .= "Iva: ".$iva_act." / ".$iva_nue.", ";
							$modelo_item_cont->Iva = $iva_nue;
						}

						$modelo_item_cont->Id_Usuario_Actualizacion = $user;
						$modelo_item_cont->Fecha_Actualizacion = date('Y-m-d H:i:s');

						if($modelo_item_cont->save()){
							$texto_novedad = substr ($texto_novedad, 0, -2);
							$nueva_novedad = new HistItemCont;
							$nueva_novedad->Id_Item = $array_item[$i];
							$nueva_novedad->Novedad = $texto_novedad;
							$nueva_novedad->Id_Usuario_Creacion = $user;
							$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
							$nueva_novedad->save();	
						}

					}

			 		$nuevo_det_fact_item = new DetFactItemCont;
					$nuevo_det_fact_item->Id_Fac = $model->Id_Fac;
					$nuevo_det_fact_item->Id_Item = $array_item[$i];
					$nuevo_det_fact_item->Cant = $array_cant[$i];
					$nuevo_det_fact_item->Vlr_Unit = $array_vlr_u[$i];
					$nuevo_det_fact_item->Iva = $array_iva[$i];
					$nuevo_det_fact_item->Id_Usuario_Creacion = $user;
					$nuevo_det_fact_item->Fecha_Creacion = date('Y-m-d H:i:s');
					$nuevo_det_fact_item->save();
				}

            	Yii::app()->user->setFlash('success', "Se asocio la factura (".$model->Numero_Factura.") correctamente.");
				$this->redirect(array('cont/view','id'=>$c));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'c'=>$c,
			'items'=>$items,
		));
	}

	public function actionAnul($id)
	{
		$model = $this->loadModel($id);
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 0;

		if($model->save()){
			Yii::app()->user->setFlash('success', "Se anulo la factura (".$model->Numero_Factura.") correctamente.");
			$this->redirect(array('cont/view','id'=>$model->Id_Contrato));	
		}else{
			Yii::app()->user->setFlash('warning', "No se pudo anular la factura (".$model->Numero_Factura.").");
			$this->redirect(array('cont/view','id'=>$model->Id_Contrato));
		}

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FactItemCont the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FactItemCont::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FactItemCont $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='fact-item-cont-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function actionExistFact()
	{
		$id_contrato = $_POST['id_contrato'];
		$n_factura = $_POST['n_factura'];
		$id_fact = $_POST['id_fact'];

		if($id_fact == 0){
			$modelo_fact=FactItemCont::model()->find(array('condition'=>"Id_Contrato = ".$id_contrato." AND Numero_Factura = '".$n_factura."' AND Estado = 1"));
		}else{
			$modelo_fact=FactItemCont::model()->find(array('condition'=>"Id_Contrato = ".$id_contrato." AND Numero_Factura = '".$n_factura."' AND Id_Fac != ".$id_fact." AND Estado = 1"));
		}

		if(empty($modelo_fact)){
			echo 1;
		}else{
			echo 0;
		}
	}

	public function actionInfoItem()
	{
		$item = $_POST['item'];

		$array_info = array();

		$modelo_item_cont = ItemCont::model()->findByPk($item);

		$desc_item = $modelo_item_cont->Id.' - '.$modelo_item_cont->Item;
		$cant = $modelo_item_cont->Cant;
		$vlr_unit = $modelo_item_cont->Vlr_Unit;
		$iva = $modelo_item_cont->Iva;
		$id_moneda = $modelo_item_cont->Moneda;
		$moneda = $modelo_item_cont->moneda->Dominio;

		$array_info['item'] = $item;
		$array_info['desc_item'] = $desc_item;
		$array_info['cant'] = $cant;
		$array_info['vlr_unit'] = str_replace(array('.', ','), array('', '.'), $vlr_unit);
		$array_info['iva'] = $iva;
		$array_info['moneda'] = $moneda;

		$resp = array('item' => intval($item), 'desc_item' => $desc_item, 'cant' => intval($cant), 'vlr_unit' => $vlr_unit, 'iva' => intval($iva), 'id_moneda' => intval($id_moneda), 'moneda' => $moneda);

	    echo json_encode($resp);

	}
}

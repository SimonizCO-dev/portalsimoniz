<?php

class CuentaEmpleadoController extends Controller
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
				'actions'=>array('create','inact','searchempleadoasoccuenta','searchempleado','searchcuenta', 'export', 'exportexcel'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$model=new CuentaEmpleado;
		$model->scenario = 'create';

		$modelo_cuenta = Cuenta::model()->findByPk($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CuentaEmpleado']))
		{
			$model->attributes=$_POST['CuentaEmpleado'];
			$model->Id_Cuenta = $id;
			$model->Id_Contrato = UtilidadesEmpleado::contratoactualempleado($model->Id_Empleado);
			$empleado = UtilidadesEmpleado::nombreempleado($model->Id_Empleado);
			$model->Estado = 1;
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			
			if($model->save()){
				Yii::app()->user->setFlash('success', "Se vinculo el empleado ".$empleado." correctamente.");
				$this->redirect(array('cuenta/update','id'=>$id));
			}else{
				Yii::app()->user->setFlash('warning', "no se pudo vincular el empleado ".$empleado.".");
				$this->redirect(array('cuenta/update','id'=>$id));

			}
			
			Yii::app()->end();

		}


		$this->render('create',array(
			'model'=>$model,
			'modelo_cuenta'=>$modelo_cuenta,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$model=new CuentaEmpleado('searchhist');

		$clases= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->clase_cuenta.' ORDER BY d.Dominio')->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CuentaEmpleado']))
			$model->attributes=$_GET['CuentaEmpleado'];

		$this->render('admin',array(
			'model'=>$model,
			'clases'=>$clases,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CuentaEmpleado the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CuentaEmpleado::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CuentaEmpleado $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cuenta-empleado-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionInact($id, $opc)
	{
		
		$model=$this->loadModel($id);

		$empleado = UtilidadesEmpleado::nombreempleado($model->Id_Empleado);
		$cuenta = Cuenta::model()->findByPk($model->Id_Cuenta);

		
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 0;
		
		if($model->save()){
			
			if($opc == 1){
				//consulta emp (AJAX)

				$res = 1;
				$msg = "Se desvinculo el empleado ".$empleado." de la cuenta / usuario (".$cuenta->DescCuentaUsuario($cuenta->Id_Cuenta).") correctamente.";

			}else{
				//por cuenta

				Yii::app()->user->setFlash('success', "Se desvinculo el empleado ".$empleado." correctamente.");
				$this->redirect(array('cuenta/update','id'=>$model->Id_Cuenta));	
			}

		}else{

			if($opc == 1){
				//consulta emp (AJAX)

				$res = 0;
				$msg = "No se pudo desvincular el empleado ".$empleado." de la cuenta / usuario (".$cuenta->DescCuentaUsuario($cuenta->Id_Cuenta).").";

			}else{
				//por cuenta

				Yii::app()->user->setFlash('warning', "no se pudo desvincular el empleado ".$empleado.".");
				$this->redirect(array('cuenta/update','id'=>$model->Id_Cuenta));
			}

		}

		$resp = array('res' => $res, 'msg' => $msg);
        echo json_encode($resp);

	}

	public function actionSearchEmpleadoAsocCuenta(){
		$filtro = $_GET['q'];
		$id_cuenta = $_GET['id_cuenta'];
        $data = CuentaEmpleado::model()->searchByEmpleadoAsocCuenta($filtro, $id_cuenta);
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

	public function actionSearchEmpleado(){
		$filtro = $_GET['q'];
        $data = CuentaEmpleado::model()->searchByEmpleado($filtro);
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

 	public function actionSearchCuenta(){
		$filtro = $_GET['q'];
        $data = CuentaEmpleado::model()->searchByCuenta($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Cuenta'],
               'text' => $item['Desc_Cuenta_Usuario'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionExport(){
    	
    	$model=new CuentaEmpleado('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['CuentaEmpleado'])) {
	        $model->attributes=$_GET['CuentaEmpleado'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('cuenta-empleado-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('cuenta-empleado-export');	
		$this->renderPartial('cuenta_empleado_export_excel',array('data' => $data));	
	}

}

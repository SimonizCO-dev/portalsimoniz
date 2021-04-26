<?php

class WipController extends Controller
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
				'actions'=>array('create','update', 'export', 'exportexcel', 'notifwip', 'validemailsadic','envionotifwip'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','genrepwip'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Wip;
		
		$fecha_min = date("Y-m-d");

		//se obtiene el consecutivo para el siguiente WIP

		$q_wip = Yii::app()->db->createCommand("SELECT TOP 1 WIP FROM T_PR_WIP ORDER BY ID DESC")->queryRow();

		if(!empty($q_wip)){
			
			$year = date('y');
			
			$q_ult_wip = Yii::app()->db->createCommand("SELECT TOP 1 WIP FROM  T_PR_WIP WHERE WIP LIKE '".$year."-%' ORDER BY ID DESC")->queryRow();

			if(empty($q_ult_wip)){
				$n_wip = $year.'-001';
			}else{
				$c = $q_ult_wip['WIP'];
				//se obtiene el numero de la cadena de consecutivo
				$n = substr($c, 3, 4);
				$ns = ((int) $n) + 1;
				//función para rellenar ceros a la izq.
				$na = str_pad((int) $ns,3,"0",STR_PAD_LEFT);
				$n_wip = $year.'-'.$na;
			}
		}else{
			$year = date('y');
			$n_wip = $year.'-001';
		}

		$cadenas = Yii::app()->db->createCommand("SELECT DISTINCT C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_ESTRUCTURA = 106 AND C_CIA = 2 ORDER BY C_NOMBRE_CLIENTE")->queryAll();

		$lista_cadenas = array();
		foreach ($cadenas as $cad) {
			$lista_cadenas[$cad['C_NOMBRE_CLIENTE']] = $cad['C_NOMBRE_CLIENTE'];
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Wip']))
		{
			$model->attributes=$_POST['Wip'];

			$user = Yii::app()->user->getState('id_user');

			$array_item = explode(",", $_POST['Wip']['cad_item']);
			$array_cant = explode(",", $_POST['Wip']['cad_cant']);

			$num_reg = count($array_item);


			if($model->CADENA != ""){

				$q_cad = Yii::app()->db->createCommand("SELECT DISTINCT C_NIT_CLIENTE FROM T_CF_CLIENTES WHERE C_ESTRUCTURA = 106 AND C_CIA = 2 AND C_NOMBRE_CLIENTE ='".$model->CADENA."'")->queryRow();

				$nit_cadena = $q_cad['C_NIT_CLIENTE'];
				$razon_cadena = $model->CADENA;

			}else{

				$nit_cadena = "";
				$razon_cadena = "";

			}

			for ($i = 0; $i < $num_reg; $i++) {
			  	
			  	$cons = $i + 1;

				$connection = Yii::app()->db;
				$command = $connection->createCommand("
					EXEC P_PR_COM_WIP_PROM
					@id = ".$cons.",
					@item = ".$array_item[$i].",
					@wip = N'".$_POST['Wip']['WIP']."',
					@fecha_s = '".$_POST['Wip']['FECHA_SOLICITUD_WIP']."',
					@fecha_e = '".$_POST['Wip']['FECHA_ENTREGA_WIP']."',
					@cant = ".$array_cant[$i].",
					@cadena = '".$razon_cadena."',
					@responsable = N'".$model->RESPONSABLE."',
					@usuario_cre = ".$user.",
					@usuario_act = ".$user.",
					@observaciones = '".$_POST['Wip']['OBSERVACIONES']."',
					@nit = '".$nit_cadena."'
				");

				$command->execute();

			}

			Yii::app()->user->setFlash('success', "El WIP ".$n_wip." fue creado correctamente.");
			$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
			'n_wip'=>$n_wip,
			'lista_cadenas'=>$lista_cadenas,
			'fecha_min'=>$fecha_min, 
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
		$model->scenario = 'update';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $cadenas = Yii::app()->db->createCommand("SELECT DISTINCT C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_ESTRUCTURA = 106 AND C_CIA = 2 ORDER BY C_NOMBRE_CLIENTE")->queryAll();

		$lista_cadenas = array();
		foreach ($cadenas as $cad) {
			$lista_cadenas[$cad['C_NOMBRE_CLIENTE']] = $cad['C_NOMBRE_CLIENTE'];
		}

        if(isset($_POST['Wip']))
        {
            $model->attributes=$_POST['Wip'];

            if($model->save()){

            	$regs_wip = Wip::model()->findAllByAttributes(array('WIP'=>$model->WIP));

	            foreach ($regs_wip as $reg) {
	            	$reg->CADENA = $model->CADENA;
	            	$reg->RESPONSABLE = $model->RESPONSABLE;
	            	$reg->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
					$reg->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
					$reg->OBSERVACIONES = $model->OBSERVACIONES;
					$reg->save();
	            }

	            Yii::app()->user->setFlash('success', "El WIP ".$model->WIP." fue actualizado correctamente.");
                $this->redirect(array('admin'));
            }
        }

        $this->render('update',array(
            'model'=>$model,
            'lista_cadenas'=>$lista_cadenas
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
		
		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$connection = Yii::app()->db;
		$command = $connection->createCommand("EXEC P_PR_COM_ACT_WIP_PROM");
		$command->execute();

		$cadenas = Yii::app()->db->createCommand("SELECT DISTINCT C_NOMBRE_CLIENTE FROM T_CF_CLIENTES WHERE C_ESTRUCTURA = 106 AND C_CIA = 2 ORDER BY C_NOMBRE_CLIENTE")->queryAll();

		$lista_cadenas = array();
		foreach ($cadenas as $cad) {
			$lista_cadenas[$cad['C_NOMBRE_CLIENTE']] = $cad['C_NOMBRE_CLIENTE'];
		}

		$model=new Wip('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Wip']))
			$model->attributes=$_GET['Wip'];

		$this->render('admin',array(
			'model'=>$model,
			'lista_cadenas'=>$lista_cadenas,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Wip the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Wip::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Wip $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='wip-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGenRepWip($id, $firma, $cargo)
	{
		$this->renderPartial('gen_rep_wip',array('id' => $id, 'firma' => $firma, 'cargo' => $cargo));
	}

	public function actionExport(){
    	
    	$model=new Wip('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['Wip'])) {
	        $model->attributes=$_GET['Wip'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('wip-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('wip-export');
		$this->renderPartial('wip_export_excel',array('data' => $data));	
	}

	public function actionNotifWip($id)
	{

		$model = $this->loadModel($id);
		$model->scenario = 'notif';

		$this->render('notif_wip',array(
			'model'=>$model,
		));
	
	}

	public function actionValidEmailsAdic()
	{

		$cad_emails_adic = $_POST['cad_emails_adic'];

		$validos = 0;

		$analizar = explode(',', $cad_emails_adic);
    	for($i = 0; $i < sizeof($analizar); $i++){
        	if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $cad_emails_adic)) $validos++;
    	}

    	if( $validos != sizeof($analizar) ){
        	echo 0;
        }else{
        	echo 1;
        }
	}

	public function actionEnvioNotifWip()
	{
		$id = $_POST['id'];
		$firma = $_POST['firma'];
		$cargo = $_POST['cargo'];
		$cadena_emails_adic = $_POST['cadena_emails_adic'];

		//se genera un documento con detalle x vendedor y si van correos adic. tambien general
		$this->renderPartial('save_pdf_wip',array('id' => $id, 'firma' => $firma, 'cargo' => $cargo, 'cadena_emails_adic' => $cadena_emails_adic));	
		
	}
}

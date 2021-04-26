<?php

class CControlCmsController extends Controller
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','revliq','repliq','genrepliq','repliq2','genrepliq2','repliqdet','genrepliqdet','notifliq', 'validemailsadic', 'envionotifliq'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		//
		$files = glob(Yii::app()->basePath.'/../files/talento_humano/comisiones/*'); //obtenemos todos los nombres de los ficheros
		foreach($files as $file){
		    if(is_file($file))
		    	unlink($file); //elimino el fichero
		}

		$model=new CControlCms('search');

		$tipos = Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = ".Yii::app()->params->tipos_comision." AND Estado = 1 ORDER BY d.Dominio")->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CControlCms']))
			$model->attributes=$_GET['CControlCms'];

		$this->render('admin',array(
			'model'=>$model,
			'tipos' => $tipos,
			'usuarios' => $usuarios,
		));		
		
	}

	public function actionRevLiq($id)
	{
		$model=$this->loadModel($id);

		$model->ESTADO = 0;
		$model->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
		$model->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');

		if($model->save()){

			$rev_liq = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_REV_EST_CMS @ID = ".$model->ID_BASE)->query();

			Yii::app()->user->setFlash('success', "La liquidación # ".$model->ID_BASE." fue revertida correctamente.");
			$this->redirect(array('admin'));
		}
	}

	public function actionRepLiq()
	{
		
		$model=new CControlCms('search');

		$tipos = Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = ".Yii::app()->params->tipos_comision." AND Estado = 1 ORDER BY d.Dominio")->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		$model->ESTADO = 1;
		if(isset($_GET['CControlCms']))
			$model->attributes=$_GET['CControlCms'];

		$this->render('rep_liq',array(
			'model'=>$model,
			'tipos' => $tipos,
			'usuarios' => $usuarios,
		));

	}

	public function actionGenRepLiq($id, $opc)
	{
		$model = CControlCms::model()->findByAttributes(array('ID_BASE' => $id)); 
		$this->renderPartial('gen_rep_liq',array('id' => $model->ID_BASE, 'opc' => $opc));
	}

	public function actionRepLiq2()
	{
		
		$model=new CControlCms('search');

		$tipos = Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = ".Yii::app()->params->tipos_comision." AND Estado = 1 ORDER BY d.Dominio")->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		$model->ESTADO = 1;
		if(isset($_GET['CControlCms']))
			$model->attributes=$_GET['CControlCms'];

		$this->render('rep_liq2',array(
			'model'=>$model,
			'tipos' => $tipos,
			'usuarios' => $usuarios,
		));

	}

	public function actionGenRepLiq2($id, $opc)
	{
		$model = CControlCms::model()->findByAttributes(array('ID_BASE' => $id));
		$this->renderPartial('gen_rep_liq2',array('id' => $model->ID_BASE, 'opc' => $opc));
	}

	public function actionRepLiqDet()
	{
		
		$model=new CControlCms('search');

		$tipos = Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = ".Yii::app()->params->tipos_comision." AND Estado = 1 ORDER BY d.Dominio")->queryAll();

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		$model->ESTADO = 1;
		if(isset($_GET['CControlCms']))
			$model->attributes=$_GET['CControlCms'];

		$this->render('rep_liq_det',array(
			'model'=>$model,
			'tipos' => $tipos,
			'usuarios' => $usuarios,
		));

	}

	public function actionGenRepLiqDet($id, $opc)
	{
		$model = CControlCms::model()->findByAttributes(array('ID_BASE' => $id));

		$this->renderPartial('gen_rep_liq_det',array('id' => $model->ID_BASE, 'opc' => $opc));	

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CControlCms the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CControlCms::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CControlCms $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ccontrol-cms-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function actionNotifLiq($id)
	{

		$model = $this->loadModel($id);
		$model->scenario = 'notif';

		$this->render('notif_liq',array(
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

	public function actionEnvioNotifLiq()
	{
		$id_base = $_POST['id_base'];
		$cadena_emails_adic = $_POST['cadena_emails_adic'];

		//se genera un documento con detalle x vendedor y si van correos adic. tambien general
		$this->renderPartial('save_pdf_liq_det',array('id' => $id_base, 'cadena_emails_adic' => $cadena_emails_adic));	
		
	}
	
}

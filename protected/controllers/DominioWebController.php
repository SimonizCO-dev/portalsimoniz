<?php

class DominioWebController extends Controller
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
				'actions'=>array('create','update', 'export', 'exportexcel','viewres'),
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
		$model=new DominioWeb;

		$tipos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipo_dominio_web.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DominioWeb']))
		{
			$model->attributes=$_POST['DominioWeb'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
			'tipos'=>$tipos,
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

		$tipos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipo_dominio_web.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DominioWeb']))
		{
			$model->attributes=$_POST['DominioWeb'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
			'tipos'=>$tipos,
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

		$model=new DominioWeb('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$tipos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->tipo_dominio_web.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DominioWeb']))
			$model->attributes=$_GET['DominioWeb'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'tipos'=>$tipos,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return DominioWeb the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=DominioWeb::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param DominioWeb $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dominio-web-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExport(){
    	
    	$model=new DominioWeb('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['DominioWeb'])) {
	        $model->attributes=$_GET['DominioWeb'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('dominio-web-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('dominio-web-export');
		$this->renderPartial('dominio_web_export_excel',array('data' => $data));	
	}

	public function actionViewRes()
	{		
		
		$titulo ='<h4>Resumen de dominios web</h4>';
		$modeloconalerta=DominioWeb::model()->findAll("DATEDIFF(day,'".date('Y-m-d')."',Fecha_Vencimiento) < 45 AND Estado = 1");
		$numconalerta = count ($modeloconalerta);
		$modelosinalerta=DominioWeb::model()->findAll("DATEDIFF(day,'".date('Y-m-d')."',Fecha_Vencimiento) >= 45 AND Estado = 1");
		$numsinalerta = count ($modelosinalerta);
		$modeloinactivos=DominioWeb::model()->findAll("Estado = 0");
		$numinactivos = count ($modeloinactivos);

		//se imprimen los parametros para mostrar la alerta

		echo $titulo;

		if($numconalerta == 0 && $numsinalerta == 0 && $numinactivos == 0){

			echo '
		    <div class="info-box bg-blue">
		        	<span class="info-box-icon"><i class="fas fa-info-circle"></i></span>
		        <div class="info-box-content">
		          	<br>
		          	<span class="info-box-number">No hay registros.</span>
		      	  	<br>
		        </div>
		    </div>';

		}else{

			if($numconalerta > 0){
				echo '
			    <div class="info-box bg-red">
			        	<span class="info-box-icon"><i class="fas fa-exclamation-circle"></i></span>
			        <div class="info-box-content">
			          	<span class="info-box-number">'.$numconalerta.' Registro(s) fuera de termino</span>
			      	  	<br>
			       	  	<button class="btn btn-default btn-sm" onclick="filtro(1)">Ver registro(s)</button>
			        </div>
			    </div>';
			}

			if($numsinalerta > 0){
				echo '
			    <div class="info-box bg-green">
			        	<span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
			        <div class="info-box-content">
			          	<span class="info-box-number">'.$numsinalerta.' Registro(s) sin alerta</span>
			      	  	<br>
			       	  	<button class="btn btn-default btn-sm" onclick="filtro(2)">Ver registro(s)</button>
			        </div>
			    </div>';
			}

			if($numinactivos > 0){
				echo '
			    <div class="info-box bg-gray">
			        	<span class="info-box-icon"><i class="fas fa-stop-circle"></i></span>
			        <div class="info-box-content">
			          	<span class="info-box-number">'.$numinactivos.' Registro(s) inactivo(s)</span>
			      	  	<br>
			       	  	<button class="btn btn-default btn-sm" onclick="filtro(3)">Ver registro(s)</button>
			        </div>
			    </div>';
			}
		}
	}
}

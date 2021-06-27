 <?php

class ContController extends Controller
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
				'actions'=>array('create','update','viewres'),
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$model = $this->loadModel($id);

		if($model->Estado != 0){
			$asociacion = 1;
		}else{
			$asociacion = 0;
		}

		//negociaciones asociadas a contrato
		$neg=new NegCont('search');
		$neg->unsetAttributes();  // clear any default values
		$neg->Id_Contrato = $id;

		//anexos asociados a contrato
		$anexos=new AnexoCont('search');
		$anexos->unsetAttributes();  // clear any default values
		$anexos->Id_Contrato = $id;

		//items asociados a contrato
		$items=new ItemCont('search');
		$items->unsetAttributes();  // clear any default values
		$items->Id_Contrato = $id;

		//facturas asociados a contrato
		$facturas=new FactItemCont('search');
		$facturas->unsetAttributes();  // clear any default values
		$facturas->Id_Contrato = $id;

		$this->render('view',array(
			'model'=>$model,
			'asociacion'=> $asociacion,
			'neg'=> $neg,
			'anexos'=> $anexos,
			'items'=> $items,
			'facturas'=> $facturas,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Cont;

		$empresas=PaEmpresa::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$period =Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->periodicidad, 'params'=>array(':estado'=>1)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cont']))
		{
			$model->attributes=$_POST['Cont'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$Fecha_Ren_Can = date_create($model->Fecha_Ren_Can);
			$model->Fecha_Ren_Can = date_format($Fecha_Ren_Can, 'Y-m-d');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
 
            if($model->save()){
            	Yii::app()->user->setFlash('success', "Contrato creado correctamente.");
                $this->redirect(array('view&id='.$model->Id_Contrato));
            }

		}

		$this->render('create',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'period'=>$period,
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

		$empresas=PaEmpresa::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$period=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->periodicidad, 'params'=>array(':estado'=>1)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cont']))
		{
			$model->attributes=$_POST['Cont'];
			$Fecha_Ren_Can = date_create($model->Fecha_Ren_Can);
			$model->Fecha_Ren_Can = date_format($Fecha_Ren_Can, 'Y-m-d');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
 
            if($model->save()){
            	Yii::app()->user->setFlash('success', "Contrato actualizado correctamente.");
                $this->redirect(array('view&id='.$id));
            }
		}

		$this->render('update',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'period'=>$period,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cont('search');
		$model->unsetAttributes();  // clear any default values

		$empresas=PaEmpresa::model()->findAll(array('order'=>'Descripcion'));

		$period=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->periodicidad));

		if(isset($_GET['Cont']))
			$model->attributes=$_GET['Cont'];

		$this->render('admin',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'period'=>$period,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cont the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cont::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cont $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cont-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionViewRes()
	{		
		
		$titulo ='<h4>Resumen de contratos</h4>';
		$modeloconalerta=Cont::model()->findAll("DATEDIFF(day,'".date('Y-m-d')."',Fecha_Final) < Dias_Alerta AND Estado = 1");
		$numconalerta = count ($modeloconalerta);
		$modelosinalerta=Cont::model()->findAll("DATEDIFF(day,'".date('Y-m-d')."',Fecha_Final) >= Dias_Alerta AND Estado = 1");
		$numsinalerta = count ($modelosinalerta);
		$modeloinactivos=Cont::model()->findAll("Estado = 0");
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

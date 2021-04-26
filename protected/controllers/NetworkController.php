<?php

class NetworkController extends Controller
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
				'actions'=>array('create','update','existsegment','asig','searchequipoasocnetwork','lib','asigipdhcp','asig2', 'export', 'exportexcel'),
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
		$model=new Network;

		$model->scenario = 'create';	

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Network']))
		{
			$model->attributes=$_POST['Network'];

			for ($i=1; $i <= 254 ; $i++) { 
				$nueva_ip = new Network;
				$nueva_ip->Network = $_POST['Network']['id_red_1'].'.'.$_POST['Network']['id_red_2'];
				$nueva_ip->Segment = $_POST['Network']['Segment'];
				$nueva_ip->Host = $i;
				$nueva_ip->Estado = 1;
				$nueva_ip->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nueva_ip->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nueva_ip->Fecha_Creacion = date('Y-m-d H:i:s');
				$nueva_ip->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nueva_ip->save();
			}

			Yii::app()->user->setFlash('success', "La red con IP's (".$_POST['Network']['id_red_1'].".".$_POST['Network']['id_red_2'].".".$_POST['Network']['Segment'].".1 / 254) fue creada correctamente.");
			$this->redirect(array('admin'));

		}

		$this->render('create',array(
			'model'=>$model,
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Network']))
		{
			$model->attributes=$_POST['Network'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->Id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	public function actionAsig($id)
	{
		$model=$this->loadModel($id);

		$model->scenario = 'asig';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Network']))
		{
			$model->attributes=$_POST['Network'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 2;
			$model->save();

			$new_hist = new NetworkEquipo;
			$new_hist->Id_Network = $id;
			$new_hist->Id_Equipo = $_POST['Network']['id_equipo'];
			$new_hist->Notas = $_POST['Network']['notas'];
			$new_hist->Estado = 1;
			$new_hist->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$new_hist->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$new_hist->Fecha_Creacion = date('Y-m-d H:i:s');
			$new_hist->Fecha_Actualizacion = date('Y-m-d H:i:s');

			$desc_equipo = UtilidadesVarias::descequipo($_POST['Network']['id_equipo']);

			if($new_hist->save()){
				Yii::app()->user->setFlash('success', "La IP ".$model->Ip($model->Id)." se asocio al equipo ".$desc_equipo." correctamente.");
				$this->redirect(array('admin'));
			}else{
				Yii::app()->user->setFlash('warning', "La IP ".$model->Ip($model->Id)." no pudo asociarse al equipo ".$desc_equipo.".");
				$this->redirect(array('admin'));
			}
		}

		$this->render('asig',array(
			'model'=>$model,
		));
	}

	public function actionAsigipdhcp()
	{
		$model= new Network;

		$model->scenario = 'asigipdhcp';

		$ips_disp = Network::model()->findAllByattributes(array('Estado' => 1));

		$lista_ips_disp = array();
		foreach ($ips_disp as $l_ip_d) {
			$lista_ips_disp[$l_ip_d->Id] = $l_ip_d->Ip($l_ip_d->Id);
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Network']))
		{

			foreach ($_POST['Network']['ips'] as $key => $id_ip) {
				$ip = Network::model()->findbyPk($id_ip);
				$ip->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$ip->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$ip->Estado = 3;
				$ip->save();	
			}

			Yii::app()->user->setFlash('success', "La(s) IP se asignaron por DHCP correctamente.");
			$this->redirect(array('admin'));
			
		}

		$this->render('asigipdhcp',array(
			'model'=>$model,
			'lista_ips_disp'=>$lista_ips_disp,
		));
	}

	public function actionAsig2($e)
	{
		$model= new Network;

		$model->scenario = 'asig2';

		$ips_disp = Network::model()->findAllByattributes(array('Estado' => 1));

		$lista_ips_disp = array();
		foreach ($ips_disp as $l_ip_d) {
			$lista_ips_disp[$l_ip_d->Id] = $l_ip_d->Ip($l_ip_d->Id);
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Network']))
		{
			$network = Network::model()->findByPk($_POST['Network']['ip']);
			$network->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$network->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$network->Estado = 2;
			$network->save();

			$new_hist = new NetworkEquipo;
			$new_hist->Id_Network = $_POST['Network']['ip'];
			$new_hist->Id_Equipo = $e;
			$new_hist->Notas = $_POST['Network']['notas'];
			$new_hist->Estado = 1;
			$new_hist->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$new_hist->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$new_hist->Fecha_Creacion = date('Y-m-d H:i:s');
			$new_hist->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($new_hist->save()){
				Yii::app()->user->setFlash('success', "La IP ".$network->Ip($network->Id)." se asocio a este equipo correctamente.");
				$this->redirect(array('equipo/view&id='.$e));
			}else{
				Yii::app()->user->setFlash('warning', "La IP ".$network->Ip($network->Id)." no pudo asociarse a este equipo.");
				$this->redirect(array('equipo/view&id='.$e));
			}
		}

		$this->render('asig2',array(
			'model'=>$model,
			'e'=>$e,
			'lista_ips_disp'=>$lista_ips_disp,
		));
	}

	public function actionSearchEquipoAsocNetwork(){
		$filtro = $_GET['q'];
        $data = Network::model()->searchByEquipoAsocNetwork($filtro);
        $result = array();
        foreach($data as $reg):

        	$desc_equipo = UtilidadesVarias::descequipo($reg['Id_Equipo']);

           	$result[] = array(
               'id'   => $reg['Id_Equipo'],
               'text' => $desc_equipo,
           	);
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionLib($id, $opc)
	{
		$model=$this->loadModel($id);
		$est = $model->Estado;

		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 1;
		$model->save();

		if($est == 2){

			$model_n = NetworkEquipo::model()->findByAttributes(array('Id_Network'=>$model->Id, 'Estado'=>1));
			$model_n->Estado = 0;
			$model_n->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model_n->Fecha_Actualizacion = date('Y-m-d H:i:s');
			//$model_n->save();

	 		$desc_equipo = UtilidadesVarias::descequipo($model_n->Id_Equipo);

	 		if($model->save() && $model_n->save()){
				if($opc == 1){
					Yii::app()->user->setFlash('success', "La IP ".$model->Ip($model->Id)." se libero del equipo ".$desc_equipo." correctamente.");
					$this->redirect(array('admin'));
				}else{
					Yii::app()->user->setFlash('success', "La IP ".$model->Ip($model->Id)." se libero de este equipo correctamente.");
					$this->redirect(array('equipo/view&id='.$model_n->Id_Equipo));
				}
			}else{
				if($opc == 1){
					Yii::app()->user->setFlash('warning', "La IP ".$model->Ip($model->Id)." no pudo ser liberada del equipo ".$desc_equipo.".");
					$this->redirect(array('admin'));
				}else{
					Yii::app()->user->setFlash('warning', "La IP ".$model->Ip($model->Id)." no pudo ser liberada este equipo.");
					$this->redirect(array('equipo/view&id='.$model_n->Id_Equipo));
				}
			
			}

	 	}else{
	 		if($model->save()){
				Yii::app()->user->setFlash('success', "La IP ".$model->Ip($model->Id)." se libero correctamente.");
				$this->redirect(array('admin'));
			}else{
				Yii::app()->user->setFlash('warning', "La IP ".$model->Ip($model->Id)." no pudo ser liberada.");
				$this->redirect(array('admin'));
			}
			
	 	}

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

		$model=new Network('search');
		
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$networks = Yii::app()->db->createCommand("SELECT DISTINCT Network FROM T_PR_NETWORK")->queryAll();

		$lista_net = array();
		foreach ($networks as $n) {
			$lista_net[$n['Network']] = $n['Network'];
		}

		$segments = Yii::app()->db->createCommand("SELECT DISTINCT Segment FROM T_PR_NETWORK")->queryAll();

		$lista_seg = array();
		foreach ($segments as $s) {
			$lista_seg[$s['Segment']] = $s['Segment'];
		}

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Network']))
			$model->attributes=$_GET['Network'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
			'lista_net'=>$lista_net,
			'lista_seg'=>$lista_seg,
		));	
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Network the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Network::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Network $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='network-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionExistSegment()
	{
		
		$id_1 = $_POST['id_1'];
		$id_2 = $_POST['id_2'];
		$segment = $_POST['segment'];

		$q_segment = Yii::app()->db->createCommand("SELECT * FROM T_PR_NETWORK WHERE Network = '".$id_1.".".$id_2."' AND Segment = ".$segment)->queryAll();	

		if(empty($q_segment)){
			echo 1;
		}else{
			echo 0;
		}
	}

	public function actionExport(){
    	
    	$model=new Network('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['Network'])) {
	        $model->attributes=$_GET['Network'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('network-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('network-export');
		$this->renderPartial('network_export_excel',array('data' => $data));	
	}
}

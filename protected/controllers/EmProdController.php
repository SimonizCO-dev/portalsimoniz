<?php

class EmProdController extends Controller
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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'envionotif'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('viewdoc'),
				'users'=>array('*'),
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
		$model=new EmProd;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//se obtiene el consecutivo para el siguiente WIP

		$q_con = Yii::app()->db->createCommand("SELECT TOP 1 Id_Em_Prod FROM T_PR_EM_PROD ORDER BY Id_Em_Prod DESC")->queryRow();

		if(!empty($q_con)){
				
			$c = $q_con['Id_Em_Prod'];

			$n = $c + 1;
			
		}else{
			$n = 1;
		}

		if(isset($_POST['EmProd']))
		{
			$model->attributes=$_POST['EmProd'];
 			
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

            $documento_subido = CUploadedFile::getInstance($model,'sop');
            $nombre_archivo = "Emision_Prod_{$n}.pdf"; 
            $model->Documento = $nombre_archivo;
 
            if($model->save()){
                $documento_subido->saveAs(Yii::app()->basePath.'/../files/portal_reportes/emision_prod/'.$model->Documento);

                $usuarios = EmProdUsuario::model()->findByPk(1)->Id_Users_Notif;

                $criteria = new CDbCriteria();
				$criteria->addCondition("Id_Usuario IN (:usuarios)");
				$criteria->params = array(':usuarios' => $usuarios);
				$usuarios_notif = Usuario::model()->findAll($criteria);

				//se recorren el parametro para saber usuarios

				$notif_env = 0;

				foreach ($usuarios_notif as $us) {

					if($us->Estado == 1){
						$nuevo_usuario_validador = new EmProdVal;
						$nuevo_usuario_validador->Id_Em_Prod = $model->Id_Em_Prod;
						$nuevo_usuario_validador->Id_Usuario = $us->Id_Usuario;
						$nuevo_usuario_validador->Estado = 0;
						if($nuevo_usuario_validador->save()){
							//despues de agregar usuario en lista de validación de la emision de producto se notifica via email
							$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us->Id_Usuario, $us->Correo);	
							$notif_env = $notif_env + $resp;
						}
					}
				}

				if($notif_env == 1){
					Yii::app()->user->setFlash('success', "Emisión de producto creada correctamente, se envio 1 notificación de validación.");
					$this->redirect(array('admin'));
				}else{
					Yii::app()->user->setFlash('success', "Emisión de producto actualizada correctamente, se enviaron ".$resp." notificaciones de validación.");
					$this->redirect(array('admin'));

				} 
            }
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
		$opc = 0;

		//usuarios validadores 
		$usuarios_val=new EmProdVal('search');
		$usuarios_val->unsetAttributes();  // clear any default values
		$usuarios_val->Id_Em_Prod = $id;

		$model=$this->loadModel($id);

		$ruta_doc_actual = Yii::app()->basePath.'/../files/portal_reportes/emision_prod/'.$model->Documento;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmProd']))
		{
			if($_FILES['EmProd']['name']['sop']  != "") {
		        $documento_subido = CUploadedFile::getInstance($model,'sop');
	            $nombre_archivo = "Emision_Prod_{$id}.pdf"; 
	            $model->Documento = $nombre_archivo;
	            $opc = 1;
		    } 

			$model->attributes=$_POST['EmProd'];
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
 
            if($model->save()){
            	if($opc == 1){
            		unlink($ruta_doc_actual);
                	$documento_subido->saveAs(Yii::app()->basePath.'/../files/portal_reportes/emision_prod/'.$model->Documento);

                	//se borran los usuarios validadores y se vuelve a enviar la notificacion a todo por que el soporte cambio
                	$criteria = new CDbCriteria();
					$criteria->addCondition("Id_Em_Prod = :Id_Em_Prod");
					$criteria->params = array(':Id_Em_Prod' => $id);
					$del_usuarios_notif = EmProdVal::model()->deleteAll($criteria);

                	$usuarios = EmProdUsuario::model()->findByPk(1)->Id_Users_Notif;

	                $criteria = new CDbCriteria();
					$criteria->addCondition("Id_Usuario IN (:usuarios)");
					$criteria->params = array(':usuarios' => $usuarios);
					$usuarios_notif = Usuario::model()->findAll($criteria);

					//se recorren el parametro para saber usuarios

					$notif_env = 0;

					foreach ($usuarios_notif as $us) {

						if($us->Estado == 1){
							$nuevo_usuario_validador = new EmProdVal;
							$nuevo_usuario_validador->Id_Em_Prod = $model->Id_Em_Prod;
							$nuevo_usuario_validador->Id_Usuario = $us->Id_Usuario;
							$nuevo_usuario_validador->Estado = 0;
							if($nuevo_usuario_validador->save()){
								//despues de agregar usuario en lista de validación de la emision de producto se notifica via email
								$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us->Id_Usuario, $us->Correo);	
								$notif_env = $notif_env + $resp;
							}
						}
					}

					if($notif_env == 1){
						Yii::app()->user->setFlash('success', "Emisión de producto creada correctamente, se envio 1 notificación de validación.");
						$this->redirect(array('admin'));
					}else{
						Yii::app()->user->setFlash('success', "Emisión de producto actualizada correctamente, se enviaron ".$resp." notificaciones de validación.");
						$this->redirect(array('admin'));

					}

            	}else{
            		Yii::app()->user->setFlash('success', "Emisión de producto actualizada correctamente.");
                	$this->redirect(array('admin'));
            	}
            }
		}

		$this->render('update',array(
			'model'=>$model,
			'usuarios_val'=>$usuarios_val,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new EmProd('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EmProd']))
			$model->attributes=$_GET['EmProd'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return EmProd the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=EmProd::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param EmProd $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='em-prod-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionViewDoc($id, $u)
    {
        
    	if(!Yii::app()->user->isGuest) {
			$v = 1;	
		}else{
			$v = 0;
		}


        $criteria = new CDbCriteria();
		$criteria->addCondition("Id_Em_Prod = :Id_Em_Prod AND Id_Usuario = :Id_Usuario");
		$criteria->params = array(':Id_Em_Prod' => $id, ':Id_Usuario' => $u);
		$model = EmProdVal::model()->find($criteria);

        if($model->Estado == 0){

	        $model->Estado = 1;
	        $model->Fecha_Actualizacion = date('Y-m-d H:i:s');

	        if($model->save()){
	            if($v == 0){
	                //Vista sin login
	                Yii::app()->user->setFlash('success', "Se marco como vista la emisión de producto ( ID ".$id." ).");
	                $this->redirect(array('site/login'));
	            }else{
	                //Vista logueado
	                Yii::app()->user->setFlash('success', "Se marco como vista la emisión de producto ( ID ".$id." ).");
	                $this->redirect(array('site/info'));
	            }
	        }else{
	            if($v == 0){
	                //Vista sin login
	                Yii::app()->user->setFlash('warning', "Error al marcar como vista la emisión de producto ( ID ".$id." ).");
	                $this->redirect(array('site/login'));
	            }else{
	                //Vista logueado
	                Yii::app()->user->setFlash('warning', "Error al marcar como vista la emisión de producto ( ID ".$id." ).");
	               	$this->redirect(array('site/info'));   
	            }
	        }
	    }else{
	    	if($v == 0){
                //Vista sin login
                Yii::app()->user->setFlash('warning', "La solicitud es invalida o el documento ya se marco como visto.");
                $this->redirect(array('site/login'));
            }else{
                //Vista logueado
                Yii::app()->user->setFlash('warning', "La solicitud es invalida o el documento ya se marco como visto");
               	$this->redirect(array('site/info'));   
            }
	    }

    }

    public function actionEnvioNotif($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);		

        $usuarios = EmProdUsuario::model()->findByPk(1)->Id_Users_Notif;

        $criteria = new CDbCriteria();
		$criteria->addCondition("Id_Usuario IN (:usuarios)");
		$criteria->params = array(':usuarios' => $usuarios);
		$usuarios_notif = Usuario::model()->findAll($criteria);

		//se recorren el parametro para saber usuarios

		$notif_env = 0;

		foreach ($usuarios_notif as $us) {

			$criteria = new CDbCriteria();
			$criteria->addCondition("Id_Em_Prod =:Id_Em_Prod AND Id_Usuario =:Id_Usuario");
			$criteria->params = array(':Id_Em_Prod' => $id, ':Id_Usuario' => $us->Id_Usuario);
			$val = EmProdVal::model()->find($criteria);

			//se verifica si el usuario aun no ha visto el documento

			if(!is_null($val)){
				if($val->Estado == 0){
					//se notifica via email
					$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us->Id_Usuario, $us->Correo);	
					$notif_env = $notif_env + $resp;
				}
			}else{
				$nuevo_usuario_validador = new EmProdVal;
				$nuevo_usuario_validador->Id_Em_Prod = $model->Id_Em_Prod;
				$nuevo_usuario_validador->Id_Usuario = $us->Id_Usuario;
				$nuevo_usuario_validador->Estado = 0;
				if($nuevo_usuario_validador->save()){
					//despues de agregar usuario en lista de validación de la emision de producto se notifica via email
					$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us->Id_Usuario, $us->Correo);	
					$notif_env = $notif_env + $resp;
				}
			}
		}

		if($notif_env == 0){
			Yii::app()->user->setFlash('success', "No se enviaron notificaciones.");
			$this->redirect(array('emprod/update&id='.$id)); 
		}
		if($notif_env == 1){
			Yii::app()->user->setFlash('success', "Se envio 1 notificación de validación.");
			$this->redirect(array('emprod/update&id='.$id)); 
		}
		if($notif_env > 1){
			Yii::app()->user->setFlash('success', "Se enviaron ".$resp." notificaciones de validación.");
			$this->redirect(array('emprod/update&id='.$id)); 
		}
               
	}
}

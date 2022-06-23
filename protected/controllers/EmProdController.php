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
				'actions'=>array('view','create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'consulta', 'envionotif','viewdoc2'),
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
		$model = $this->loadModel($id);

		$criteria = new CDbCriteria();
		$criteria->addCondition("Id_Em_Prod = :Id_Em_Prod AND Id_Usuario = :Id_Usuario");
		$criteria->params = array(':Id_Em_Prod' => $id, ':Id_Usuario' => Yii::app()->user->getState('id_user'));
		$model_u = EmProdVal::model()->find($criteria);

		if(!is_null($model_u)){
			if($model_u->Estado == 0){
				$val = 1;
			}else{
				$val = 0;
			}
		}else{
			$val = 0;
		}

		$this->render('view',array(
			'model'=>$model,
			'val'=>$val,
		));
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
		$m_usuarios=Usuario::model()->findAll(array('order'=>'Usuario', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));
		$usuariosEmail = EmProdUsuario::model()->findByPk(1)->id_Users_Email;
	
		$array_user_email = array();
		//opciones activas en el combo usuarios de notif.
		$a_user_email =  explode(",",$usuariosEmail);
		foreach ($a_user_email as $un => $id) {
			array_push($array_user_email, $id);
		}
		$user_correos = json_encode($array_user_email);

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
				$Variable='';				
				foreach($model->Id_Users_Notif as $row=>$key){					
					$Variable.=','.$key;
				}
				
			#	die($Variable);-
				EmProdUsuario::model()->updateAll(array('id_Users_Email'=>ltrim($Variable,",")),'id=1');
           
                $usuarios = EmProdUsuario::model()->findByPk(1)->id_Users_Email;

				$usuarios_notif = Yii::app()->db->createCommand("SELECT Id_Usuario, Correo, Estado FROM T_PR_USUARIO WHERE Id_Usuario IN (".$usuarios.")")->queryAll();

				//se recorren el parametro para saber usuarios

				$notif_env = 0;

				foreach ($usuarios_notif as $us) {

					if($us['Estado'] == 1){
						$nuevo_usuario_validador = new EmProdVal;
						$nuevo_usuario_validador->Id_Em_Prod = $model->Id_Em_Prod;
						$nuevo_usuario_validador->Id_Usuario = $us['Id_Usuario'];
						$nuevo_usuario_validador->Estado = 0;
						if($nuevo_usuario_validador->save()){
							//despues de agregar usuario en lista de validación de la emision de producto se notifica via email
							$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us['Id_Usuario'], $us['Correo'], 1);	
							$notif_env = $notif_env + $resp;
						}
					}
				}

				if($notif_env == 1){
					Yii::app()->user->setFlash('success', "Emisión de producto creada correctamente, se envió 1 notificación de validación.");
					$this->redirect(array('admin'));
				}else{
					Yii::app()->user->setFlash('success', "Emisión de producto creada correctamente, se enviaron ".$notif_env." notificaciones de validación.");
					$this->redirect(array('admin'));

				} 
            }
		}

		$this->render('create',array(
			'model'=>$model,
			'm_usuarios'=>$m_usuarios,
			'user_email'=>$user_correos
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

                	$usuarios = EmProdUsuario::model()->findByPk(2)->Id_Users_Notif;

					$usuarios_notif = Yii::app()->db->createCommand("SELECT Id_Usuario, Correo, Estado FROM T_PR_USUARIO WHERE Id_Usuario IN (".$usuarios.")")->queryAll();

					//se recorren el parametro para saber usuarios

					$notif_env = 0;

					foreach ($usuarios_notif as $us) {

						if($us['Estado'] == 1){
							$nuevo_usuario_validador = new EmProdVal;
							$nuevo_usuario_validador->Id_Em_Prod = $model->Id_Em_Prod;
							$nuevo_usuario_validador->Id_Usuario = $us['Id_Usuario'];
							$nuevo_usuario_validador->Estado = 0;
							if($nuevo_usuario_validador->save()){
								//despues de agregar usuario en lista de validación de la emision de producto se notifica via email
								$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us['Id_Usuario'], $us['Correo'], 2);	
								$notif_env = $notif_env + $resp;
							}
						}
					}

					if($notif_env == 1){
						Yii::app()->user->setFlash('success', "Emisión de producto creada correctamente, se envió 1 notificación de validación.");
						$this->redirect(array('admin'));
					}else{
						Yii::app()->user->setFlash('success', "Emisión de producto actualizada correctamente, se enviaron ".$notif_env." notificaciones de validación.");
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

			$SQL="update T_PR_EM_PROD_USUARIO set  id_Users_Email=id_Users_Email_bk";
			Yii::app()->db->createCommand($SQL)->execute();
		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	public function actionEliminar($id)
	{
		die("prueba");
		
	}
	/**
	 * Manages all models.
	 */
	public function actionConsulta($id=0,$cons=0)
	{
		if ($cons==0){
				$model=new EmProd('search');
				$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

				$model->unsetAttributes();  // clear any default values
				if(isset($_GET['EmProd']))
					$model->attributes=$_GET['EmProd'];

				$this->render('consulta',array(
					'model'=>$model,
					'usuarios'=>$usuarios,
				));
		}else{
			$SQL='delete from T_PR_EM_PROD where Id_Em_Prod='.$id;
			
			Yii::app()->db->createCommand($SQL)->execute();
			Yii::app()->user->setFlash('success', "Registro Eliminado satisfactoriamente ( ID ".$id." ).");
			$this->redirect(array('emprod/admin'));  
		}
	  
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
	                $this->redirect(array('emprod/consulta'));
	            }
	        }else{
	            if($v == 0){
	                //Vista sin login
	                Yii::app()->user->setFlash('warning', "Error al marcar como vista la emisión de producto ( ID ".$id." ).");
	                $this->redirect(array('site/login'));
	            }else{
	                //Vista logueado
	                Yii::app()->user->setFlash('warning', "Error al marcar como vista la emisión de producto ( ID ".$id." ).");
	               	$this->redirect(array('emprod/consulta'));   
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

    public function actionViewDoc2($id, $u)
    {
        
        $criteria = new CDbCriteria();
		$criteria->addCondition("Id_Em_Prod = :Id_Em_Prod AND Id_Usuario = :Id_Usuario");
		$criteria->params = array(':Id_Em_Prod' => $id, ':Id_Usuario' => $u);
		$model = EmProdVal::model()->find($criteria);

        if($model->Estado == 0){

	        $model->Estado = 1;
	        $model->Fecha_Actualizacion = date('Y-m-d H:i:s');

	        if($model->save()){
	            
	            //Vista logueado
	            Yii::app()->user->setFlash('success', "Se marco como vista la emisión de producto ( ID ".$id." ).");
	            $this->redirect(array('emprod/view&id='.$id)); 
	            
	        }else{
	            
	            //Vista logueado
	            Yii::app()->user->setFlash('warning', "Error al marcar como vista la emisión de producto ( ID ".$id." ).");
	           	$this->redirect(array('emprod/view&id='.$id));    
	            
	        }
	    }else{

	        //Vista logueado
	        Yii::app()->user->setFlash('warning', "La solicitud es invalida o el documento ya se marco como visto");
	       	$this->redirect(array('emprod/view&id='.$id));    
            
	    }

    }

    public function actionEnvioNotif($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);		

        $usuarios = EmProdUsuario::model()->findByPk(2)->Id_Users_Notif;

        $usuarios_notif = Yii::app()->db->createCommand("SELECT Id_Usuario, Correo, Estado FROM T_PR_USUARIO WHERE Id_Usuario IN (".$usuarios.")")->queryAll();

		//se recorren el parametro para saber usuarios

		$notif_env = 0;

		foreach ($usuarios_notif as $us) {

			$criteria = new CDbCriteria();
			$criteria->addCondition("Id_Em_Prod =:Id_Em_Prod AND Id_Usuario =:Id_Usuario");
			$criteria->params = array(':Id_Em_Prod' => $id, ':Id_Usuario' => $us['Id_Usuario']);
			$val = EmProdVal::model()->find($criteria);

			//se verifica si el usuario aun no ha visto el documento

			if(!is_null($val)){
				if($val->Estado == 0){
					//se notifica via email
					$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us['Id_Usuario'], $us['Correo'], 3);	
					$notif_env = $notif_env + $resp;
				}
			}else{
				$nuevo_usuario_validador = new EmProdVal;
				$nuevo_usuario_validador->Id_Em_Prod = $model->Id_Em_Prod;
				$nuevo_usuario_validador->Id_Usuario = $us['Id_Usuario'];
				$nuevo_usuario_validador->Estado = 0;
				if($nuevo_usuario_validador->save()){
					//despues de agregar usuario en lista de validación de la emision de producto se notifica via email
					$resp = UtilidadesMail::envionotifemisionproducto($model->Id_Em_Prod, $us['Id_Usuario'], $us['Id_Usuario'], 1);	
					$notif_env = $notif_env + $resp;
				}
			}
		}

		if($notif_env == 0){
			Yii::app()->user->setFlash('success', "No se enviaron notificaciones.");
			$this->redirect(array('emprod/update&id='.$id)); 
		}
		if($notif_env == 1){
			Yii::app()->user->setFlash('success', "Se envió 1 notificación de validación.");
			$this->redirect(array('emprod/update&id='.$id)); 
		}
		if($notif_env > 1){
			Yii::app()->user->setFlash('success', "Se enviaron ".$notif_env." notificaciones de validación.");
			$this->redirect(array('emprod/update&id='.$id)); 
		}
               
	}
}

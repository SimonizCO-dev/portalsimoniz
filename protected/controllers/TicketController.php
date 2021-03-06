<?php

class TicketController extends Controller
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
				'actions'=>array('view','det'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','getnovedades','getnovedadesdet','getnovedadesxuser','getnovedadesdetxuser','getusuariosxnovedad'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','asigt','cticket'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('fticket'),
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

		//hist 
		$hist=new HistTicket('search');
		$hist->unsetAttributes();  // clear any default values
		$hist->Id_Ticket = $id;

		$this->render('view',array(
			'model'=>$model,
			'hist'=>$hist,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionDet($id)
	{
		
		$model = $this->loadModel($id);

		//hist 
		$hist=new HistTicket('hist');
		$hist->unsetAttributes();  // clear any default values
		$hist->Id_Ticket = $id;

		$this->render('det',array(
			'model'=>$model,
			'hist'=>$hist,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Ticket;
		$model->scenario = 'create';

		//historico de tickets creados por usuario logueado
		$tickets=new Ticket('search');
		$tickets->unsetAttributes();  // clear any default values
		$tickets->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');

		//$q_grupos=Dominio::model()->findAll(array('condition'=>'Estado=:estado AND Id_Padre ='.Yii::app()->params->grupos_act, 'params'=>array(':estado'=>1)));

		$q_grupos = Yii::app()->db->createCommand("SELECT G.Id_Dominio, G.Dominio FROM T_PR_DOMINIO G WHERE G.Estado = 1 AND G.Id_Padre = ".Yii::app()->params->grupos_act." AND (SELECT COUNT (*) FROM T_PR_NOVEDAD_TICKET NT WHERE NT.Id_Grupo = G.Id_Dominio AND NT.Estado = 1) > 0 ORDER BY 2")->queryAll();

		$grupos = array();
		foreach ($q_grupos as $g) {
			$grupos[$g['Id_Dominio']] = $g['Dominio'];		
	    }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];
			
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			$model->Estado = 1;

			//Se consulta el empleado ligado al usuario para saber el ??rea
			$usuario_reg = Usuario::model()->findByPk(Yii::app()->user->getState('id_user'));

			$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$usuario_reg->Id_Emp.' ORDER BY 1 DESC')->queryRow();

			if(!empty($query_contrato)){
				$id_ult_contrato = $query_contrato['Id_Contrato'];
				$modelocontrato = ContratoEmpleado::model()->findByPk($id_ult_contrato);
				$area = $modelocontrato->Id_Area;
						
			}else{
				$area = null;
			}

			$model->Id_Area = $area;

			if($_FILES['Ticket']['name']['Soporte']  != "") {
		        $data = 'data:image/jpg;base64,'.base64_encode(file_get_contents($_FILES['Ticket']['tmp_name']['Soporte']));
		        $model->Soporte = $data;
		    }else{
	    		$model->Soporte = null;
		    }

			if($model->save()){
				
				if($model->Id_Novedad_Det != ""){
					$nov = $model->Id_Novedad_Det;
				}else{
					$nov = $model->Id_Novedad;
				}

				//se consultan los usuarios por novedad para enviar correo de notificaci??n de creaci??n

				$array_correos = array();
				$usuarios_x_novedad_ticket = NovedadTicketUsuario::model()->FindAllByAttributes(array('Estado' => 1, 'Id_Novedad' => $nov));
				foreach ($usuarios_x_novedad_ticket as $us) {
					if($us->idusuario->Estado == 1){
						$array_correos[] = $us->idusuario->Correo;
					}
				}

				if(!empty($array_correos)){
					$res = UtilidadesMail::enviocreacionticket($model->Id_Ticket, $array_correos);

					if($res == 1){
						Yii::app()->user->setFlash('success', "Ticket ( ID ".$model->Id_Ticket." ) registrado correctamente, se envi?? 1 notificaci??n de creaci??n.");
						$this->redirect(array('create'));
					}else{
					 	Yii::app()->user->setFlash('success', "Ticket ( ID ".$model->Id_Ticket." ) registrado correctamente, se enviaron ".$res." notificaciones de creaci??n.");
						$this->redirect(array('create'));
					}

				}else{
					Yii::app()->user->setFlash('success', "Ticket ( ID ".$model->Id_Ticket." ) registrado correctamente.");
					$this->redirect(array('create'));	
				}
					
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'grupos'=>$grupos,
			'tickets'=>$tickets,
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

		$estado_actual = $model->Estado;
		$id_usuario_asig_actual = $model->Id_Usuario_Asig;
		$usuario_asig_actual = $model->idusuarioasig->Nombres;

		//hist 
		$hist=new HistTicket('search');
		$hist->unsetAttributes();  // clear any default values
		$hist->Id_Ticket = $id;

		

		//Si el ticket tiene detalle de novedad 
		if($model->Id_Novedad_Det != ""){
			$usuarios_asig = NovedadTicketUsuario::model()->FindAllByAttributes(array('Estado' => 1, 'Id_Novedad' => $model->Id_Novedad_Det));
		}else{
			$usuarios_asig = NovedadTicketUsuario::model()->FindAllByAttributes(array('Estado' => 1, 'Id_Novedad' => $model->Id_Novedad));
		}

		//Estados a mostrar seg??n actual
		if($estado_actual == 2){
			$estados = array(2 => 'ASIGNADO', 3 => 'EN PROCESO', 4 => 'CERRADO');
		}

		if($estado_actual == 3){
			$estados = array(3 => 'EN PROCESO', 4 => 'CERRADO');
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Ticket']))
		{
			$model->attributes=$_POST['Ticket'];

			$estado_nuevo = $_POST['Ticket']['Estado'];	
			$id_usuario_asig_nuevo = $_POST['Ticket']['Id_Usuario_Asig'];
			$usuario_asig_nuevo = Usuario::model()->findByPk($_POST['Ticket']['Id_Usuario_Asig'])->Nombres;	
			$notas = $_POST['Ticket']['Notas'];

			$texto_novedad = "";
			$flag = 0;

			//usuario asignado para resolver
			if($id_usuario_asig_actual != $id_usuario_asig_nuevo){
				$flag = 1;
				$texto_novedad .= "Responsable: ".$usuario_asig_actual." / ".$usuario_asig_nuevo.", ";
			}

			//Estado
			if($estado_actual != $estado_nuevo){
				$flag = 1;
				$texto_novedad .= "Estado: ".$model->DescEstado($estado_actual)." / ".$model->DescEstado($estado_nuevo).", ";
			}

			//Notas (Cierre) / Fecha 
			if($estado_nuevo == 4){
				if($notas == ""){
					$model->Notas = null;	
				}
				else{
					$flag = 1;
					$model->Notas = $_POST['Ticket']['Notas'];
					$texto_novedad .= "Notas: ".$notas.", ";	
				}

				$flag = 1;
				$model->Fecha_Cierre = date('Y-m-d H:i:s');
				$texto_novedad .= "Fecha de cierre: ".UtilidadesVarias::textofechahora($model->Fecha_Cierre).", ";	

			}

			//alguno de los criterios cambio
			if($flag == 1){

				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

				$texto_novedad = substr ($texto_novedad, 0, -2);
				$nueva_novedad = new HistTicket;
				$nueva_novedad->Id_Ticket = $id;
				$nueva_novedad->Texto = $texto_novedad;
				$nueva_novedad->Id_Usuario_Registro = Yii::app()->user->getState('id_user');
				$nueva_novedad->Fecha_Registro = date('Y-m-d H:i:s');
				$nueva_novedad->save();
			}

			if($model->save()){
				//Notas (Cierre) / Fecha 
				if($estado_nuevo == 4){
					$correo = Usuario::model()->findByPk($model->Id_Usuario_Creacion)->Correo;
					$res = UtilidadesMail::enviocalificacionticketcerrado($id, $correo);

					if($res == 0){
						Yii::app()->user->setFlash('warning', "Ticket ( ID ".$id." ) actualizado correctamente, No se pudo enviar la solicitud para calificaci??n.");
						$this->redirect(array('admin'));
					}else{
					 	Yii::app()->user->setFlash('success', "Ticket ( ID ".$id." ) actualizado correctamente, Solicitud para calificaci??n enviada al correo ".$correo.".");
						$this->redirect(array('admin'));
					}

				}else{
					//diferente a cerrado si hay alguna novedad

					if($flag == 1){

						$correo = Usuario::model()->findByPk($model->Id_Usuario_Creacion)->Correo;
						$res = UtilidadesMail::envionovedadticket($id, $nueva_novedad->Id, $correo);

						if($res == 0){
							Yii::app()->user->setFlash('warning', "Ticket ( ID ".$id." ) actualizado correctamente, No se pudo enviar la novedad.");
							$this->redirect(array('admin'));
						}else{
						 	Yii::app()->user->setFlash('success', "Ticket ( ID ".$id." ) actualizado correctamente, Novedad de ticket enviada al correo ".$correo.".");
							$this->redirect(array('admin'));
						}
					}else{
						Yii::app()->user->setFlash('success', "Ticket ( ID ".$id." ) actualizado correctamente.");
							$this->redirect(array('admin'));	
					}
				}
			}	
			
		}

		$this->render('update',array(
			'model'=>$model,
			'hist'=>$hist,
			'usuarios_asig'=>$usuarios_asig,
			'estados'=>$estados,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$user = Yii::app()->user->getState('id_user');

		$q_grupos = Yii::app()->db->createCommand("SELECT DISTINCT NT.Id_Grupo FROM T_PR_NOVEDAD_TICKET NT WHERE NT.Id_Novedad IN (
		SELECT DISTINCT NTU.Id_Novedad FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_NOVEDAD_TICKET NT ON NTU.Id_Novedad = NT.Id_Novedad AND NT.Estado = 1
		WHERE NTU.Estado = 1 AND NTU.Id_Usuario = ".$user.")")->queryAll();

		$usuarios=Usuario::model()->findAll(array('condition'=>'Id_Usuario != 1', 'order'=>'Nombres'));

		if(!empty($q_grupos)){

			$model=new Ticket('search');
			$a = 1;
			
			$grupos = array();
			foreach ($q_grupos as $g) {
				$id_grupo = $g['Id_Grupo'];
				$desc_grupo = Dominio::model()->findByPk($id_grupo)->Dominio;
				$grupos[$id_grupo] = $desc_grupo;	
		    }

		    $model->unsetAttributes();  // clear any default values
			if(isset($_GET['Ticket'])){
				$model->attributes=$_GET['Ticket'];
			}

		}else{

			$model=new Ticket('search');
			$a = 0;
			$grupos = array();

		}

		$this->render('admin',array(
			'model'=>$model,
			'a'=>$a,
			'grupos'=>$grupos,
			'usuarios'=>$usuarios,

		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Ticket the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Ticket::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Ticket $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ticket-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionGetNovedades()
	{	
		$grupo = $_POST['grupo'];

		
		$tipos = Yii::app()->db->createCommand("
		SELECT NT.Id_Novedad, NT.Novedad FROM T_PR_NOVEDAD_TICKET NT 
		WHERE NT.Estado = 1 AND NT.Id_Grupo = ".$grupo." AND Id_Novedad_Padre IS NULL ORDER BY 2
		")->queryAll();
	
		$i = 0;
		$array_tipos = array();
		foreach ($tipos as $t) {
			$array_tipos[$i] = array('id' => $t['Id_Novedad'],  'text' => $t['Novedad']);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_tipos);

	}

	public function actionGetNovedadesDet()
	{	
		$novedad = $_POST['novedad'];

		
		$tipos = Yii::app()->db->createCommand("
		SELECT NT.Id_Novedad, NT.Novedad FROM T_PR_NOVEDAD_TICKET NT 
		WHERE NT.Estado = 1 AND NT.Id_Novedad_Padre = ".$novedad." ORDER BY 2
		")->queryAll();
	
		$i = 0;
		$array_tipos = array();
		foreach ($tipos as $t) {
			$array_tipos[$i] = array('id' => $t['Id_Novedad'],  'text' => $t['Novedad']);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_tipos);

	}

	public function actionGetNovedadesXUser()
	{	
		$grupo = $_POST['grupo'];
		$user = Yii::app()->user->getState('id_user');

		$q_nov = Yii::app()->db->createCommand("SELECT DISTINCT NTU.Id_Novedad FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_NOVEDAD_TICKET NT ON NTU.Id_Novedad = NT.Id_Novedad AND NT.Estado = 1 AND NT.Id_Grupo = ".$grupo."
		WHERE NTU.Estado = 1 AND NT.Id_Novedad_Padre IS NULL AND NTU.Id_Usuario = ".$user)->queryAll();
	
		$i = 0;
		$array_novedades = array();
		foreach ($q_nov as $n) {
			$id_nov = $n['Id_Novedad'];
			$desc_nov = NovedadTicket::model()->findByPk($id_nov)->Novedad;
			$array_novedades[$i] = array('id' => $id_nov,  'text' => $desc_nov);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_novedades);

	}

	public function actionGetNovedadesDetXUser()
	{	
		$novedades = implode(",", $_POST['novedad']);;
		$user = Yii::app()->user->getState('id_user');

		$q_nov = Yii::app()->db->createCommand("SELECT DISTINCT NTU.Id_Novedad FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_NOVEDAD_TICKET NT ON NTU.Id_Novedad = NT.Id_Novedad AND NT.Estado = 1 AND NT.Id_Novedad_Padre IN (".$novedades.")
		WHERE NTU.Estado = 1 AND NTU.Id_Usuario = ".$user)->queryAll();
	
		$i = 0;
		$array_novedades = array();
		foreach ($q_nov as $n) {
			$id_nov = $n['Id_Novedad'];
			$desc_nov = NovedadTicket::model()->findByPk($id_nov)->Novedad;
			$array_novedades[$i] = array('id' => $id_nov,  'text' => $desc_nov);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_novedades);

	}

	public function actionGetUsuariosXNovedad()
	{	
		$novedades = implode(",", $_POST['novedades']);
		$det_novedades = implode(",", $_POST['det_novedades']);

		if($det_novedades != ""){
			$det = ','.$det_novedades;
		}else{
			$det = '';
		}

		$q_user = Yii::app()->db->createCommand("
		SELECT DISTINCT NTU.Id_Usuario, U.Nombres FROM T_PR_NOVEDAD_TICKET_USUARIO NTU 
		INNER JOIN T_PR_USUARIO U ON NTU.Id_Usuario = U.Id_Usuario AND U.Estado = 1
		WHERE NTU.Estado = 1 AND NTU.Id_Novedad IN (".$novedades.$det.") ORDER BY 2
		")->queryAll();

		$i = 0;
		$array_u = array();
		foreach ($q_user as $u) {
			$array_u[$i] = array('id' => $u['Id_Usuario'],  'text' => $u['Nombres']);	
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_u);

	}

	public function actionAsigT($id, $opc)
	{
		
		$model=$this->loadModel($id);

		$estado_act = $model->Estado;

		$model->Id_Usuario_Asig = Yii::app()->user->getState('id_user');
		$model->Fecha_Asig = date('Y-m-d H:i:s');
		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = 2;

		if($model->save()){

			$texto_novedad = "Fecha de asignaci??n: ".UtilidadesVarias::textofechahora($model->Fecha_Asig).", Responsable: ".$model->idusuarioasig->Nombres.", Estado: ".$model->DescEstado($estado_act)." / ".$model->DescEstado($model->Estado);

			$nueva_novedad = new HistTicket;
			$nueva_novedad->Id_Ticket = $id;
			$nueva_novedad->Texto = $texto_novedad;
			$nueva_novedad->Id_Usuario_Registro =  Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Registro = date('Y-m-d H:i:s');
			$nueva_novedad->save();

			$correo = Usuario::model()->findByPk($model->Id_Usuario_Creacion)->Correo;
			$response = UtilidadesMail::envionovedadticket($id, $nueva_novedad->Id, $correo);

			if($response == 1){
				//AJAX
				if($opc == 1){ 
					$res = 1;
					$msg = "Se asign?? el ticket ID ".$id." correctamente, Novedad de ticket enviada al correo ".$correo.".";
					$resp = array('res' => $res, 'msg' => $msg);
	        		echo json_encode($resp);
				}

				//POST
				if($opc == 2){
					Yii::app()->user->setFlash('success', "Se asign?? el ticket ( ID ".$id." ) correctamente, Novedad de ticket enviada al correo ".$correo.".");
					$this->redirect(array('ticket/update&id='.$id));
				}
			}else{
				if($opc == 1){ 
					$res = 1;
					$msg = "Se asign?? el ticket ID ".$id." correctamente, no se envi?? ninguna notificaci??n.";
					$resp = array('res' => $res, 'msg' => $msg);
	        		echo json_encode($resp);
				}

				//POST
				if($opc == 2){
					Yii::app()->user->setFlash('success', "Se asign?? el ticket ( ID ".$id." ) correctamente, no se envi?? ninguna notificaci??n.");
					$this->redirect(array('ticket/update&id='.$id));
				}
			}
			
		}else{

			//AJAX
			if($opc == 1){
				$res = 0;
				$msg = "Error al asignar el ticket ID ".$id.".";
				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);
			}

			//POST
			if($opc == 2){
				Yii::app()->user->setFlash('warning', "Error al asignar el ticket ( ID ".$id." ).");
				$this->redirect(array('admin'));
			}
	
		}

	}

	public function actionCTicket($id)
	{
		
		$modelticket = Ticket::model()->findByAttributes(array('Id_Ticket' => $id, 'Estado' => 4));

		if(!is_null($modelticket)){

			$opc = 1;
			
		}else{
			$opc = 0;
			Yii::app()->user->setFlash('warning', "La solicitud es invalida o la calificaci??n ya fue enviada.");	
		}

		if($opc == 0){
			$this->render('cticket',array('opc'=> 0, 'modelticket'=> array()));
		}else{
			$this->render('cticket',array('opc'=> 1, 'modelticket'=> $modelticket));
		}		
	
	}

	public function actionFTicket($id, $c)
    {
        
    	if(!Yii::app()->user->isGuest) {
			$v = 1;	
		}else{
			$v = 0;
		}


        $model=$this->loadModel($id);

        if($model->Estado == 4){

	        $model->Calificacion = $c;
	        $model->Fecha_Calificacion = date('Y-m-d H:i:s');
	        $model->Estado = 5;

	        if($model->save()){
	            if($v == 0){
	                //Vista sin login
	                Yii::app()->user->setFlash('success', "Se envi?? la calificaci??n del ticket ( ID ".$id." ).");
	                $this->redirect(array('site/login'));
	            }else{
	                //Vista logueado
	                Yii::app()->user->setFlash('success', "Se envi?? la calificaci??n del ticket ( ID ".$id." ).");
	                $this->redirect(array('ticket/create'));
	            }
	        }else{
	            if($v == 0){
	                //Vista sin login
	                Yii::app()->user->setFlash('warning', "Error al enviar calificaci??n del ticket ( ID ".$id." ).");
	                $this->redirect(array('site/login'));
	            }else{
	                //Vista logueado
	                Yii::app()->user->setFlash('warning', "Error al enviar calificaci??n del ticket ( ID ".$id." ).");
	               	$this->redirect(array('ticket/create'));   
	            }
	        }
	    }else{
	    	if($v == 0){
                //Vista sin login
                Yii::app()->user->setFlash('warning', "La solicitud es invalida o la calificaci??n ya fue enviada.");
                $this->redirect(array('site/login'));
            }else{
                //Vista logueado
                Yii::app()->user->setFlash('warning', "La solicitud es invalida o la calificaci??n ya fue enviada.");
               	$this->redirect(array('ticket/create'));   
            }
	    }

    }

}

<?php

class GdDocumentoController extends Controller
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
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','docse','viewe','docsi','viewi','docst','viewt','auddescarga','gettipos'),
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
		
		$opc = Yii::app()->params->opc_lista_docs;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		if(!empty($menu_perfil)){

			$this->render('view',array(
				'model'=>$this->loadModel($id),
			));

		}else{
			$this->redirect(array('gddocumento/admin'));
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		
		$opc = Yii::app()->params->opc_lista_docs;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		if(!empty($menu_perfil)){

			$model=new GdDocumento;

			$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

			$areas=Area::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Area'));
			
			$unidades_gerencia=UnidadGerencia::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Unidad_Gerencia'));

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if(isset($_POST['GdDocumento']))
			{

				$q_cons = Yii::app()->db->createCommand("SELECT MAX(Id_Documento) + 1 AS CONS FROM T_PR_GD_DOCUMENTO")->queryRow();
				$cons = $q_cons['CONS'];

				if(is_null($cons)){
					$cons = 1;
				}

				//$rnd_con = rand(0,999999);  // genera un numero ramdom entre 0-999999
				//---$rnd_des = rand(0,999999);  // genera un numero ramdom entre 0-999999
	            $model->attributes=$_POST['GdDocumento'];

	            $ext_doc_consulta = $_POST['GdDocumento']['ext_doc_consulta'];
	            $ext_doc_descarga = $_POST['GdDocumento']['ext_doc_descarga'];
	 			
				$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Creacion = date('Y-m-d H:i:s');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

				$nombre_doc = str_replace(" ", "_", $model->Titulo);

	            $Documento_subido_con = CUploadedFile::getInstance($model,'doc_consulta');
	            $nombre_archivo_con = "{$cons}-{$nombre_doc}.{$ext_doc_consulta}"; 
	            $model->Url_Consulta = $nombre_archivo_con;

	            $Documento_subido_des = CUploadedFile::getInstance($model,'doc_descarga');
	            $nombre_archivo_des = "{$cons}-{$nombre_doc}.{$ext_doc_descarga}"; 
	            $model->Url_Descarga = $nombre_archivo_des;
	 
	            if($model->save()){
	                $Documento_subido_con->saveAs(Yii::app()->basePath.'/../files/gestion_documental/consulta/'.$nombre_archivo_con);
	   			
	   				$Documento_subido_des->saveAs(Yii::app()->basePath.'/../files/gestion_documental/descarga/'.$nombre_archivo_des);

	   				//se administran las areas relacionadas al GdDocumento
					UtilidadesVarias::adminareaDocumento($model->Id_Documento, $model->areas);

	              	//se llama la función encargada de enviar emails notificando nuevo GdDocumento
					$resp = UtilidadesMail::enviocreaciondocto($model->Id_Documento);

					if($resp == 1){
						Yii::app()->user->setFlash('success', "Documento creado correctamente, se envío notificación de creación a los E-mail(s) configurados.");
					}
					if($resp == 0 || $resp == 2){
						Yii::app()->user->setFlash('success', "Documento creado correctamente.");
					}

	                $this->redirect(array('admin'));
	            }
			}

			$this->render('create',array(
				'model'=>$model,
				'tipos'=>$tipos,
				'areas'=>$areas,
				'unidades_gerencia'=>$unidades_gerencia,
			));

		}else{
			$this->redirect(array('gddocumento/admin'));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		
		$opc = Yii::app()->params->opc_lista_docs;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		if(!empty($menu_perfil)){

			$opc_con = 0;
			$opc_des = 0;

			//$rnd_con = rand(0,999999);  // genera un numero ramdom entre 0-999999
			//$rnd_des = rand(0,999999);  // genera un numero ramdom entre 0-999999

			$model=$this->loadModel($id);
			$modelo_ant=$this->loadModel($id);

			$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

			$areas=Area::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Area'));
			
			$unidades_gerencia=UnidadGerencia::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Unidad_Gerencia'));

			$ruta_doc_con_actual = Yii::app()->basePath.'/../files/gestion_documental/consulta/'.$modelo_ant->Url_Consulta;

			$ruta_doc_des_actual = Yii::app()->basePath.'/../files/gestion_documental/descarga/'.$modelo_ant->Url_Descarga;

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			//opciones activas en el combo áreas
			$json_areas_activas = UtilidadesVarias::areasactivasDocumento($id);

			if(isset($_POST['GdDocumento']))
			{

				$model->attributes=$_POST['GdDocumento'];

				if($modelo_ant->Titulo != $model->Titulo){
					//si cambia el titulo (nombre) o area del archivo

					$info_doc_con = new SplFileInfo($modelo_ant->Url_Consulta);
					$ext_doc_con = $info_doc_con->getExtension();

					$nombre_doc = str_replace(" ", "_", $model->Titulo);

					$nombre_archivo_con = "{$id}-{$nombre_doc}.{$ext_doc_con}"; 

					$info_doc_des = new SplFileInfo($modelo_ant->Url_Descarga);
					$ext_doc_des = $info_doc_des->getExtension();

					$nombre_archivo_des = "{$id}-{$nombre_doc}.{$ext_doc_des}"; 

					rename(Yii::app()->basePath.'/../files/gestion_documental/consulta/'.$modelo_ant->Url_Consulta, Yii::app()->basePath.'/../files/gestion_documental/consulta/'.$nombre_archivo_con);

					rename(Yii::app()->basePath.'/../files/gestion_documental/descarga/'.$modelo_ant->Url_Descarga, Yii::app()->basePath.'/../files/gestion_documental/descarga/'.$nombre_archivo_des);
		            
		            $model->Url_Consulta = $nombre_archivo_con;
		            $model->Url_Descarga = $nombre_archivo_des;

					$model->save();

				}

				if($_FILES['GdDocumento']['name']['doc_consulta']  != "") {

					$ext_doc_consulta = $_POST['GdDocumento']['ext_doc_consulta'];

					$nombre_doc = str_replace(" ", "_", $model->Titulo);

		            $Documento_subido_con = CUploadedFile::getInstance($model,'doc_consulta');
		            $nombre_archivo_con = "{$id}-{$nombre_doc}.{$ext_doc_consulta}"; 
		            $model->Url_Consulta = $nombre_archivo_con;
		            $opc_con = 1;
			    }

			    if($_FILES['GdDocumento']['name']['doc_descarga']  != "") {

			    	$ext_doc_descarga = $_POST['GdDocumento']['ext_doc_descarga'];

			    	$nombre_doc = str_replace(" ", "_", $model->Titulo);

			        $Documento_subido_des = CUploadedFile::getInstance($model,'doc_descarga');
		            $nombre_archivo_des = "{$id}-{$nombre_doc}.{$ext_doc_descarga}"; 
		            $model->Url_Descarga = $nombre_archivo_des;
		            $opc_des = 1;
			    }  

				$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
	 
	            if($model->save()){
	            	if($opc_con == 1){
	            		unlink($ruta_doc_con_actual);
	                	$Documento_subido_con->saveAs(Yii::app()->basePath.'/../files/gestion_documental/consulta/'.$nombre_archivo_con);
	            	}
	            	if($opc_des == 1){
	            		unlink($ruta_doc_des_actual);
	                	$Documento_subido_des->saveAs(Yii::app()->basePath.'/../files/gestion_documental/descarga/'.$nombre_archivo_des); 
	            	}

	            	//se administran las areas relacionadas al GdDocumento
					UtilidadesVarias::adminareaDocumento($model->Id_Documento, $model->areas);
					Yii::app()->user->setFlash('success', "Documento actualizado correctamente.");
	                $this->redirect(array('admin'));
	            }
			}

			$this->render('update',array(
				'model'=>$modelo_ant,
				'tipos'=>$tipos,
				'areas'=>$areas,
				'unidades_gerencia'=>$unidades_gerencia,	
				'json_areas_activas'=>$json_areas_activas,
			));

		}else{
			$this->redirect(array('gddocumento/admin'));
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		
		$opc = Yii::app()->params->opc_lista_docs;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		$model=new GdDocumento('search');
			
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		
		$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion'));

		if(!empty($menu_perfil)){
				
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['GdDocumento']))
				$model->attributes=$_GET['GdDocumento'];

			$this->render('admin',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>0,
			));

		}else{

			$this->render('admin',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>1,
			));
		}
	}

	public function actionDocsE()
	{
		
		$opc = Yii::app()->params->opc_lista_docs_e;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		$model=new GdDocumento('searchdocse');
			
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Clasificacion=:clasificacion', 'params'=>array(':clasificacion'=>2)));

		if(!empty($menu_perfil)){

			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['GdDocumento']))
				$model->attributes=$_GET['GdDocumento'];

			$this->render('docs_e',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>0,
			));

		}else{
			$this->render('docs_e',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>1,
			));	
		}
	}

	public function actionDocsI()
	{
			
		$opc = Yii::app()->params->opc_lista_docs_i;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		$model=new GdDocumento('searchdocsi');
			
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Clasificacion=:clasificacion', 'params'=>array(':clasificacion'=>1)));

		if(!empty($menu_perfil)){

			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['GdDocumento']))
				$model->attributes=$_GET['GdDocumento'];

			$this->render('docs_i',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>0,
			));

		}else{
			$this->render('docs_i',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>1,
			));	
		}
	}

	public function actionDocsT()
	{
			
		$opc = Yii::app()->params->opc_lista_docs_t;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		$model=new GdDocumento('searchdocst');
			
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Clasificacion=:clasificacion', 'params'=>array(':clasificacion'=>1)));

		if(!empty($menu_perfil)){

			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['GdDocumento']))
				$model->attributes=$_GET['GdDocumento'];

			$this->render('docs_t',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>0,
			));

		}else{
			$this->render('docs_t',array(
				'model'=>$model,
				'usuarios'=>$usuarios,
				'tipos'=>$tipos,
				'error'=>1,
			));	
		}

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return GdDocumento the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=GdDocumento::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param GdDocumento $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='gd-Documento-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionViewE($id)
	{
		$model = $this->loadModel($id);

		$opc = Yii::app()->params->opc_lista_docs_e;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		if (!empty($menu_perfil) && $model->Clasificacion == 1) {

			//se inserta un registro en la tabla de auditoria 
			$new_aud = new GdAudDocumento;
			$new_aud->Id_Documento = $id;
			$new_aud->Accion = 1; //1. ver, 2. descargar
			$new_aud->Id_Usuario =  Yii::app()->user->getState('id_user');
			$new_aud->Fecha_Hora = date('Y-m-d H:i:s');
			if($new_aud->save()){
				//si se inserta el registro en la tabla se hace render a la vista
				$this->render('view_e',array('model'=>$model));
			}
		}else{
			$this->redirect(array('docse'));
		}
	}

	public function actionViewI($id)
	{
		$model = $this->loadModel($id);

		$opc = Yii::app()->params->opc_lista_docs_i;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL  WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		if (!empty($menu_perfil) && $model->Clasificacion == 2) {
		
			//se inserta un registro en la tabla de auditoria 
			$new_aud = new GdAudDocumento;
			$new_aud->Id_Documento = $id;
			$new_aud->Accion = 1; //1. ver, 2. descargar
			$new_aud->Id_Usuario =  Yii::app()->user->getState('id_user');
			$new_aud->Fecha_Hora = date('Y-m-d H:i:s');
			if($new_aud->save()){
				//si se inserta el registro en la tabla se hace render a la vista
				$this->render('view_i',array('model'=>$model));
			}
		}else{
			$this->redirect(array('docsi'));	
		}
	}

	public function actionViewT($id)
	{
		$model = $this->loadModel($id);

		$opc = Yii::app()->params->opc_lista_docs_t;

		$array_perfiles = (Yii::app()->user->getState('array_perfiles'));
		$cadena_perfiles = implode(",",$array_perfiles);

		//se valida si el o los perfiles que tiene el usuario tienen habilitada esta opción para controlar el acceso por URL

		$menu_perfil= Yii::app()->db->createCommand('SELECT Id_M_Perfil FROM T_PR_MENU_PERFIL WHERE Id_Menu = '.$opc.' AND Id_Perfil IN ('.$cadena_perfiles.') AND Estado = 1')->queryAll();

		if (!empty($menu_perfil) && $model->Clasificacion == 3) {
		
			//se inserta un registro en la tabla de auditoria 
			$new_aud = new GdAudDocumento;
			$new_aud->Id_Documento = $id;
			$new_aud->Accion = 1; //1. ver, 2. descargar
			$new_aud->Id_Usuario =  Yii::app()->user->getState('id_user');
			$new_aud->Fecha_Hora = date('Y-m-d H:i:s');
			if($new_aud->save()){
				//si se inserta el registro en la tabla se hace render a la vista
				$this->render('view_t',array('model'=>$model));
			}
		}else{
			$this->redirect(array('docst'));		
		}
	}

	public function actionAudDescarga()
	{
		$id_doc = $_POST['id_doc'];
		//se inserta un registro en la tabla de auditoria 
		$new_aud = new GdAudDocumento;
		$new_aud->Id_Documento = $id_doc;
		$new_aud->Accion = 2; //1. ver, 2. descargar
		$new_aud->Id_Usuario =  Yii::app()->user->getState('id_user');
		$new_aud->Fecha_Hora = date('Y-m-d H:i:s');
		if($new_aud->save()){
			echo 1;
		}

	}

	public function actionGetTipos()
	{	
		$clasificacion = $_POST['clasificacion'];

		if($clasificacion == 2 || $clasificacion == 3){
			$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado AND Clasificacion = 1', 'params'=>array(':estado'=>1)));	
		}else{
			$tipos=GdTipo::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado AND Clasificacion = 2', 'params'=>array(':estado'=>1)));
		}

		$i = 0;
		$array_tipos = array();
		foreach ($tipos as $t) {
    		$array_tipos[$i] = array('id' => $t->Id_Tipo,  'text' => $t->Descripcion);
    		$i++; 
	    }

		//se retorna un json con las opciones
		echo json_encode($array_tipos);

	}

}

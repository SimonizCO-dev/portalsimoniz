<?php

class UsuarioController extends Controller
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
				'actions'=>array('admin','profile'),
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
		$model=new Usuario;
		$model_perfiles_usuario=new PerfilUsuario;

		$model->scenario = 'create';
		
		$m_perfiles=Perfil::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$criteria = new CDbCriteria;
        $criteria->condition = 'Estado=:estado AND Id_Empresa != 0';
        $criteria->order = 'Descripcion';
        $criteria->params = array(':estado'=>1);
        $m_empresas=Empresa::model()->findAll($criteria);

        $m_areas=Area::model()->findAll(array('order'=>'Area', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

        $m_subareas=Subarea::model()->findAll(array('order'=>'Subarea', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$m_bodegas=IBodega::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

        $m_tipos_docto=ITipoDocto::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

        $m_niveles_detalle = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->niv_det_vis_emp.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		// Uncomment the following line if AJAX validation is needed
		//$this->performAjaxValidation($model);

		if(isset($_POST['Usuario']))
		{
			
			$model->attributes=$_POST['Usuario'];
			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
			if($model->validate() && $model->save()){
				//se administran los perfiles relacionadas al usuario
				UtilidadesUsuario::adminperfilusuario($model->Id_Usuario, $_POST['Usuario']['perfiles']);

				if(isset($_POST['Usuario']['empresas'])){
					//se administran las empresas relacionadas al usuario
					UtilidadesUsuario::adminempresausuario($model->Id_Usuario, $_POST['Usuario']['empresas']);
				}else{
					UtilidadesUsuario::adminempresausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['areas'])){
					//se administran las areas relacionadas al usuario
					UtilidadesUsuario::adminareausuario($model->Id_Usuario, $_POST['Usuario']['areas']);
				}else{
					UtilidadesUsuario::adminareausuario($model->Id_Usuario, array());	
				}
							
				if(isset($_POST['Usuario']['subareas'])){
					//se administran las subareas relacionadas al usuario
					UtilidadesUsuario::adminsubareausuario($model->Id_Usuario, $_POST['Usuario']['subareas']);
				}else{
					UtilidadesUsuario::adminsubareausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['bodegas'])){
					//se administran las bodegas con las que puede trabajar el usuario
					UtilidadesUsuario::adminbodegausuario($model->Id_Usuario, $_POST['Usuario']['bodegas']);
				}else{
					UtilidadesUsuario::adminbodegausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['tipos_docto'])){
					//se administran los tipos de documento con los que puede trabajar el usuario
					UtilidadesUsuario::admintipodoctousuario($model->Id_Usuario, $_POST['Usuario']['tipos_docto']);
				}else{
					UtilidadesUsuario::admintipodoctousuario($model->Id_Usuario, array());	
				}

				$model->Password = sha1($model->Password);
				$model->update();
				Yii::app()->user->setFlash('success', "Usuario creado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'm_perfiles'=>$m_perfiles,
			'm_empresas'=>$m_empresas,
			'm_areas'=>$m_areas,
			'm_subareas'=>$m_subareas,
			'm_niveles_detalle'=>$m_niveles_detalle,
			'm_bodegas'=>$m_bodegas,
			'm_tipos_docto'=>$m_tipos_docto,
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
		$Password = $model->Password;

		$model->scenario = 'update';

		$m_perfiles=Perfil::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$criteria = new CDbCriteria;
        $criteria->condition = 'Estado=:estado AND Id_Empresa != 0';
        $criteria->order = 'Descripcion';
        $criteria->params = array(':estado'=>1);
        $m_empresas=Empresa::model()->findAll($criteria);

        $m_areas=Area::model()->findAll(array('order'=>'Area', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

        $m_subareas=Subarea::model()->findAll(array('order'=>'Subarea', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

        $m_niveles_detalle = Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->niv_det_vis_emp.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		$m_bodegas=IBodega::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

        $m_tipos_docto=ITipoDocto::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		//opciones activas en el combo perfiles
		$json_perfiles_activos = UtilidadesUsuario::perfilesactivos($id);

		//opciones activas en el combo empresas
		$json_empresas_activas = UtilidadesUsuario::empresasactivas($id);

		//opciones activas en el combo áreas
		$json_areas_activas = UtilidadesUsuario::areasactivas($id);

		//opciones activas en el combo subáreas
		$json_subareas_activas = UtilidadesUsuario::subareasactivas($id);

		//opciones activas en el combo bodegas
		$json_bodegas_activas = UtilidadesUsuario::bodegasactivas($id);

		//opciones activas en el combo tipos de docto
		$json_td_activos = UtilidadesUsuario::tiposdoctoactivos($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Usuario']))
		{
			$model->attributes=$_POST['Usuario'];
			if($model->Password != ""){
				//si el password ha sido modificado
				$opc = 1;
			}else{
				//si el password no fue modificado
				$opc = 2;
			}
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->validate() && $model->save()){
				if($opc == 1){
					//el password fue modificado, se codifica y se vuelve a guardar el modelo
					$model->Password = sha1($model->Password);
					$model->update();
				}else{
					//el password no fue modificado
					$model->Password = $Password;
					$model->update();
				}
				//se administran los perfiles relacionadas al usuario
				UtilidadesUsuario::adminperfilusuario($model->Id_Usuario, $_POST['Usuario']['perfiles']);

				if(isset($_POST['Usuario']['empresas'])){
					//se administran las empresas relacionadas al usuario
					UtilidadesUsuario::adminempresausuario($model->Id_Usuario, $_POST['Usuario']['empresas']);
				}else{
					UtilidadesUsuario::adminempresausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['areas'])){
					//se administran las areas relacionadas al usuario
					UtilidadesUsuario::adminareausuario($model->Id_Usuario, $_POST['Usuario']['areas']);
				}else{
					UtilidadesUsuario::adminareausuario($model->Id_Usuario, array());	
				}
							
				if(isset($_POST['Usuario']['subareas'])){
					//se administran las subareas relacionadas al usuario
					UtilidadesUsuario::adminsubareausuario($model->Id_Usuario, $_POST['Usuario']['subareas']);
				}else{
					UtilidadesUsuario::adminsubareausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['subareas'])){
					//se administran las subareas relacionadas al usuario
					UtilidadesUsuario::adminsubareausuario($model->Id_Usuario, $_POST['Usuario']['subareas']);
				}else{
					UtilidadesUsuario::adminsubareausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['bodegas'])){
					//se administran las bodegas con las que puede trabajar el usuario
					UtilidadesUsuario::adminbodegausuario($model->Id_Usuario, $_POST['Usuario']['bodegas']);
				}else{
					UtilidadesUsuario::adminbodegausuario($model->Id_Usuario, array());	
				}

				if(isset($_POST['Usuario']['tipos_docto'])){
					//se administran los tipos de documento con los que puede trabajar el usuario
					UtilidadesUsuario::admintipodoctousuario($model->Id_Usuario, $_POST['Usuario']['tipos_docto']);
				}else{
					UtilidadesUsuario::admintipodoctousuario($model->Id_Usuario, array());	
				}

				Yii::app()->user->setFlash('success', "Usuario actualizado correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'm_perfiles'=>$m_perfiles,
			'm_empresas'=>$m_empresas,
			'm_areas'=>$m_areas,
			'm_subareas'=>$m_subareas,
			'm_niveles_detalle'=>$m_niveles_detalle,
			'm_bodegas'=>$m_bodegas,
			'm_tipos_docto'=>$m_tipos_docto,
			'json_perfiles_activos'=>$json_perfiles_activos,
			'json_empresas_activas'=>$json_empresas_activas,
			'json_areas_activas'=>$json_areas_activas,
			'json_subareas_activas'=>$json_subareas_activas,
			'json_bodegas_activas'=>$json_bodegas_activas,
			'json_td_activos'=>$json_td_activos,
		));
	}


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Usuario('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Usuario']))
			$model->attributes=$_GET['Usuario'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	public function actionProfile(){      
	    $model = new Usuario;
	    $model = Usuario::model()->findByAttributes(array('Id_Usuario'=>Yii::app()->user->getState('id_user')));
	    $model->setScenario('profile');

	    //perfiles asociados al usuario

	    $criteria = new CDbCriteria;
        $criteria->join ='LEFT JOIN T_PR_PERFIL p ON t.Id_Perfil = p.Id_Perfil';
        $criteria->condition = 't.Id_Usuario = :Id_Usuario';
        $criteria->order = 'p.Descripcion';
        $criteria->params = array(":Id_Usuario" => Yii::app()->user->getState('id_user'));
        $perfiles=PerfilUsuario::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        $criteria->join ='LEFT JOIN T_PR_EMPRESA e ON t.Id_Empresa = e.Id_Empresa';
        $criteria->condition = 't.Id_Usuario = :Id_Usuario';
        $criteria->order = 'e.Descripcion';
        $criteria->params = array(":Id_Usuario" => Yii::app()->user->getState('id_user'));
        $empresas=EmpresaUsuario::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        $criteria->join ='LEFT JOIN T_PR_I_BODEGA b ON t.Id_Bodega = b.Id';
        $criteria->condition = 't.Id_Usuario = :Id_Usuario';
        $criteria->order = 'b.Descripcion';
        $criteria->params = array(":Id_Usuario" => Yii::app()->user->getState('id_user'));
        $bodegas=BodegaUsuario::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        $criteria->join ='LEFT JOIN T_PR_I_TIPO_DOCTO td ON t.Id_Tipo_Docto = td.Id';
        $criteria->condition = 't.Id_Usuario = :Id_Usuario';
        $criteria->order = 'td.Descripcion';
        $criteria->params = array(":Id_Usuario" => Yii::app()->user->getState('id_user'));
        $tipos_docto=TipoDoctoUsuario::model()->findAll($criteria);

        $criteria = new CDbCriteria;
        $criteria->join ='INNER JOIN T_PR_AREA a ON t.Id_Area = a.Id_Area';
        $criteria->condition = 't.Id_Usuario = :Id_Usuario';
        $criteria->order = 'a.Area';
        $criteria->params = array(":Id_Usuario" => Yii::app()->user->getState('id_user'));
        $areas=AreaUsuario::model()->findAll($criteria);

	    if(isset($_POST['Usuario'])){
	 
	        $model->attributes = $_POST['Usuario'];
        	$valid = $model->validate();
	 
	        if($valid){
      			$model->Password = sha1($model->new_password);
      			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
	          	if($model->save()){
	          		Yii::app()->user->setFlash('success', "Nuevo password guardado.");
         			$this->redirect(array('profile'));
	          	}
          	}
    	}
	 
	    $this->render('profile',array('model'=>$model, 'perfiles'=>$perfiles, 'empresas'=>$empresas, 'bodegas'=>$bodegas, 'tipos_docto'=>$tipos_docto, 'areas'=>$areas)); 
	 }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Usuario the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Usuario::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Usuario $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='usuario-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

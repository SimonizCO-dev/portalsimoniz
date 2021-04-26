<?php

class LicenciaController extends Controller
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
				'actions'=>array('create','update','ret', 'export', 'exportexcel'),
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

		if($model->CantUsuariosRest($id) > 0 && $model->Estado != Yii::app()->params->estado_lic_ret){
			$asociacion = 1;
		}else{
			$asociacion = 0;
		}

		//Licencias asociadas a equipo
		$licencias=new LicenciaEquipo('search');
		$licencias->unsetAttributes();  // clear any default values
		$licencias->Id_Licencia = $id;

		$this->render('view',array(
			'model'=>$model,
			'asociacion'=> $asociacion,
			'licencias'=> $licencias,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$opc = 0;
		
		$model=new Licencia;

		$clases=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->clase_licencia, 'params'=>array(':estado'=>1)));

		$tipos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->tipo_licencia, 'params'=>array(':estado'=>1)));

		$versiones=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->version_licencia, 'params'=>array(':estado'=>1)));
		
		$productos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->producto_licencia, 'params'=>array(':estado'=>1)));

		$empresas=PaEmpresa::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado AND Tipo = '.Yii::app()->params->empresa_nac, 'params'=>array(':estado'=>1)));

		$proveedores=PaProveedor::model()->findAll(array('order'=>'Proveedor', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$ubicaciones=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->ubicacion_licencia, 'params'=>array(':estado'=>1)));

		$estados=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->estado_licencia, 'params'=>array(':estado'=>1)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Licencia']))
		{
			$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
			$rnd2 = rand(0,99999);  // genera un numero ramdom entre 0-99999
			$model->attributes=$_POST['Licencia'];

			if($_POST['Licencia']['Producto'] == ""){ $model->Producto = NULL; } else{ $model->Producto = $_POST['Licencia']['Producto']; };
			if($_POST['Licencia']['Id_Licencia'] == ""){ $model->Id_Licencia = NULL; } else{ $model->Id_Licencia = $_POST['Licencia']['Id_Licencia']; };
			if($_POST['Licencia']['Token'] == ""){ $model->Token = NULL; } else{ $model->Token = $_POST['Licencia']['Token']; };
			if($_POST['Licencia']['Empresa_Compra'] == ""){ $model->Empresa_Compra = NULL; } else{ $model->Empresa_Compra = $_POST['Licencia']['Empresa_Compra']; };
			if($_POST['Licencia']['Proveedor'] == ""){ $model->Proveedor = NULL; } else{ $model->Proveedor = $_POST['Licencia']['Proveedor']; };
			if($_POST['Licencia']['Numero_Factura'] == ""){ $model->Numero_Factura = NULL; } else{ $model->Numero_Factura = $_POST['Licencia']['Numero_Factura']; };
			if($_POST['Licencia']['Fecha_Factura'] == ""){ $model->Fecha_Factura = NULL; } else{ $model->Fecha_Factura = $_POST['Licencia']['Fecha_Factura']; };
			if($_POST['Licencia']['Valor_Comercial'] == ""){ $model->Valor_Comercial = NULL; } else{ $model->Valor_Comercial = $_POST['Licencia']['Valor_Comercial']; };
			if($_POST['Licencia']['Fecha_Inicio'] == ""){ $model->Fecha_Inicio = NULL; } else{ $model->Fecha_Inicio = $_POST['Licencia']['Fecha_Inicio']; };
			if($_POST['Licencia']['Fecha_Final'] == ""){ $model->Fecha_Final = NULL; } else{ $model->Fecha_Final = $_POST['Licencia']['Fecha_Final']; };
			if($_POST['Licencia']['Fecha_Inicio_Sop'] == ""){ $model->Fecha_Inicio_Sop = NULL; } else{ $model->Fecha_Inicio_Sop = $_POST['Licencia']['Fecha_Inicio_Sop']; };
			if($_POST['Licencia']['Fecha_Final_Sop'] == ""){ $model->Fecha_Final_Sop = NULL; } else{ $model->Fecha_Final_Sop = $_POST['Licencia']['Fecha_Final_Sop']; };
			if($_POST['Licencia']['Numero_Inventario'] == ""){ $model->Numero_Inventario = NULL; } else{ $model->Numero_Inventario = $_POST['Licencia']['Numero_Inventario']; };
			if($_POST['Licencia']['Cuenta_Registro'] == ""){ $model->Cuenta_Registro = NULL; } else{ $model->Cuenta_Registro = $_POST['Licencia']['Cuenta_Registro']; };
			if($_POST['Licencia']['Link'] == ""){ $model->Link = NULL; } else{ $model->Link = $_POST['Licencia']['Link']; };
			if($_POST['Licencia']['Password'] == ""){ $model->Password = NULL; } else{ $model->Password = $_POST['Licencia']['Password']; };
			if($_POST['Licencia']['Notas'] == ""){ $model->Notas = NULL; } else{ $model->Notas = str_replace(PHP_EOL, ' ', $_POST['Licencia']['Notas']); };

			$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

            $documento_subido = CUploadedFile::getInstance($model,'sop');
            $nombre_archivo = "{$rnd}-{$documento_subido}";
            $model->Doc_Soporte = $nombre_archivo;

            if($_FILES['Licencia']['name']['sop2']  != "") {

		        $documento_subido2 = CUploadedFile::getInstance($model,'sop2');
	            $nombre_archivo2 = "{$rnd2}-{$documento_subido2}";
            	$model->Doc_Soporte2 = $nombre_archivo2;
	            $opc = 1;
		    }else{
		    	$model->Doc_Soporte2 = NULL;	
		    }
 
            if($model->save()){
                $documento_subido->saveAs(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$nombre_archivo);

                if($opc == 1){
                	$documento_subido2->saveAs(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$nombre_archivo2);
            	}

        		Yii::app()->user->setFlash('success', "Licencia creada correctamente.");
                $this->redirect(array('admin'));
            }
		}

		$this->render('create',array(
			'model'=>$model,
			'clases'=>$clases,
			'tipos'=>$tipos,
			'versiones'=>$versiones,
			'productos'=>$productos,
			'empresas'=>$empresas,
			'proveedores'=>$proveedores,
			'ubicaciones'=>$ubicaciones,
			'estados'=>$estados,
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

		$opc = 0;
		$opc2 = 0;

		$ruta_doc_actual = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias//licencias/'.$model->Doc_Soporte;
		$doc_actual2 = $model->Doc_Soporte2;
		$ruta_doc_actual2 = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$model->Doc_Soporte2;
		$rnd = rand(0,99999);  // genera un numero ramdom entre 0-99999
		$rnd2 = rand(0,99999);  // genera un numero ramdom entre 0-99999

		$clases=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->clase_licencia, 'params'=>array(':estado'=>1)));

		$tipos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->tipo_licencia, 'params'=>array(':estado'=>1)));

		$versiones=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->version_licencia, 'params'=>array(':estado'=>1)));
		
		$productos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->producto_licencia, 'params'=>array(':estado'=>1)));

		$empresas=PaEmpresa::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Estado=:estado AND Tipo = '.Yii::app()->params->empresa_nac, 'params'=>array(':estado'=>1)));

		$proveedores=PaProveedor::model()->findAll(array('order'=>'Proveedor', 'condition'=>'Estado=:estado', 'params'=>array(':estado'=>1)));

		$ubicaciones=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Padre = '.Yii::app()->params->ubicacion_licencia, 'params'=>array(':estado'=>1)));

		$estados=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Estado=:estado AND Id_Dominio !=:dominio AND Id_Padre = '.Yii::app()->params->estado_licencia, 'params'=>array(':estado'=>1, ':dominio'=>Yii::app()->params->estado_lic_ret)));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Licencia']))
		{
			
			if($_FILES['Licencia']['name']['sop']  != "") {

		        $documento_subido = CUploadedFile::getInstance($model,'sop');
	            $nombre_archivo = "{$rnd}-{$documento_subido}";
            	$model->Doc_Soporte = $nombre_archivo;
	            $opc = 1;
		    }

		    if($_FILES['Licencia']['name']['sop2']  != "") {

		        $documento_subido2 = CUploadedFile::getInstance($model,'sop2');
	            $nombre_archivo2 = "{$rnd2}-{$documento_subido2}";
            	$model->Doc_Soporte2 = $nombre_archivo2;
	            $opc2 = 1;
		    } 

			$model->attributes=$_POST['Licencia'];

			if($_POST['Licencia']['Producto'] == ""){ $model->Producto = NULL; } else{ $model->Producto = $_POST['Licencia']['Producto']; };
			if($_POST['Licencia']['Id_Licencia'] == ""){ $model->Id_Licencia = NULL; } else{ $model->Id_Licencia = $_POST['Licencia']['Id_Licencia']; };
			if($_POST['Licencia']['Token'] == ""){ $model->Token = NULL; } else{ $model->Token = $_POST['Licencia']['Token']; };
			if($_POST['Licencia']['Empresa_Compra'] == ""){ $model->Empresa_Compra = NULL; } else{ $model->Empresa_Compra = $_POST['Licencia']['Empresa_Compra']; };
			if($_POST['Licencia']['Proveedor'] == ""){ $model->Proveedor = NULL; } else{ $model->Proveedor = $_POST['Licencia']['Proveedor']; };
			if($_POST['Licencia']['Numero_Factura'] == ""){ $model->Numero_Factura = NULL; } else{ $model->Numero_Factura = $_POST['Licencia']['Numero_Factura']; };
			if($_POST['Licencia']['Fecha_Factura'] == ""){ $model->Fecha_Factura = NULL; } else{ $model->Fecha_Factura = $_POST['Licencia']['Fecha_Factura']; };
			if($_POST['Licencia']['Valor_Comercial'] == ""){ $model->Valor_Comercial = NULL; } else{ $model->Valor_Comercial = $_POST['Licencia']['Valor_Comercial']; };
			if($_POST['Licencia']['Fecha_Inicio'] == ""){ $model->Fecha_Inicio = NULL; } else{ $model->Fecha_Inicio = $_POST['Licencia']['Fecha_Inicio']; };
			if($_POST['Licencia']['Fecha_Final'] == ""){ $model->Fecha_Final = NULL; } else{ $model->Fecha_Final = $_POST['Licencia']['Fecha_Final']; };
			if($_POST['Licencia']['Fecha_Inicio_Sop'] == ""){ $model->Fecha_Inicio_Sop = NULL; } else{ $model->Fecha_Inicio_Sop = $_POST['Licencia']['Fecha_Inicio_Sop']; };
			if($_POST['Licencia']['Fecha_Final_Sop'] == ""){ $model->Fecha_Final_Sop = NULL; } else{ $model->Fecha_Final_Sop = $_POST['Licencia']['Fecha_Final_Sop']; };
			if($_POST['Licencia']['Numero_Inventario'] == ""){ $model->Numero_Inventario = NULL; } else{ $model->Numero_Inventario = $_POST['Licencia']['Numero_Inventario']; };
			if($_POST['Licencia']['Cuenta_Registro'] == ""){ $model->Cuenta_Registro = NULL; } else{ $model->Cuenta_Registro = $_POST['Licencia']['Cuenta_Registro']; };
			if($_POST['Licencia']['Link'] == ""){ $model->Link = NULL; } else{ $model->Link = $_POST['Licencia']['Link']; };
			if($_POST['Licencia']['Password'] == ""){ $model->Password = NULL; } else{ $model->Password = $_POST['Licencia']['Password']; };
			if($_POST['Licencia']['Notas'] == ""){ $model->Notas = NULL; } else{ $model->Notas = str_replace(PHP_EOL, ' ', $_POST['Licencia']['Notas']); };

			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
            	
            	if($opc == 1){
            		if (file_exists($ruta_doc_actual)) {
            			unlink($ruta_doc_actual);
            		}
                	$documento_subido->saveAs(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$nombre_archivo);
            	}

            	if($opc2 == 1){
        			if ($doc_actual2 != "") {
            			unlink($ruta_doc_actual2);
            		}
                	$documento_subido2->saveAs(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$nombre_archivo2);
            	}

            	Yii::app()->user->setFlash('success', "Licencia actualizada correctamente.");
                $this->redirect(array('admin'));
            }

		}

		$this->render('update',array(
			'model'=>$model,
			'clases'=>$clases,
			'tipos'=>$tipos,
			'versiones'=>$versiones,
			'productos'=>$productos,
			'empresas'=>$empresas,
			'proveedores'=>$proveedores,
			'ubicaciones'=>$ubicaciones,
			'estados'=>$estados,
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

		$model=new Licencia('search');

		$clases=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->clase_licencia));

		$tipos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->tipo_licencia));

		$versiones=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->version_licencia));
		
		$productos=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->producto_licencia));

		$ubicaciones=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->ubicacion_licencia));

		$empresas=PaEmpresa::model()->findAll(array('order'=>'Descripcion', 'condition'=>'Tipo = '.Yii::app()->params->empresa_nac));

		$estados=Dominio::model()->findAll(array('order'=>'Dominio', 'condition'=>'Id_Padre = '.Yii::app()->params->estado_licencia));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Licencia']))
			$model->attributes=$_GET['Licencia'];

		$this->render('admin',array(
			'model'=>$model,
			'clases'=>$clases,
			'tipos'=>$tipos,
			'versiones'=>$versiones,
			'productos'=>$productos,
			'ubicaciones'=>$ubicaciones,
			'empresas'=>$empresas,
			'estados'=>$estados,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Licencia the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Licencia::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Licencia $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='licencia-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionRet($id)
	{
		
		$model=$this->loadModel($id);

		$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
		$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
		$model->Estado = Yii::app()->params->estado_lic_ret;

		$desc_licencia = $model->DescLicencia($model->Id_Lic);
			
		if($model->save()){

			Yii::app()->user->setFlash('success', "La licencia ".$desc_licencia." fue retirada correctamente.");
			$this->redirect(array('admin'));

		}else{
			
			Yii::app()->user->setFlash('warning', "No se pudo retirar la licencia ".$desc_licencia.".");
			$this->redirect(array('admin'));

		}
	}

	public function actionExport(){
    	
    	$model=new Licencia('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['Licencia'])) {
	        $model->attributes=$_GET['Licencia'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('licencia-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('licencia-export');
		$this->renderPartial('licencia_export_excel',array('data' => $data));	
	}
}

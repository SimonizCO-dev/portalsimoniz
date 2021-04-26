<?php

class ResOCRController extends Controller
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
				'actions'=>array('admin','admin2','download'),
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
		$model=new ResOCR;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ResOCR']))
		{
			$q_cons = Yii::app()->db->createCommand("SELECT MAX(Id) + 1 AS CONS FROM T_PR_RES_OC_R")->queryRow();
			$cons = $q_cons['CONS'];

			if(is_null($cons)){
				$cons = 1;
			}

			$model->attributes=$_POST['ResOCR'];
			
			if($model->Tipo == 1){
				$nombre_doc = $cons.'_resumen_oc_'.date('Y_m_d_H_i_s');
			}else{
				$nombre_doc = $cons.'_resumen_rem_'.date('Y_m_d_H_i_s');
			}

            $documento_subido = CUploadedFile::getInstance($model,'sop');
            $nombre_archivo = "{$nombre_doc}.zip"; 
            $model->Doc_Soporte = $nombre_archivo;

            $model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Creacion = date('Y-m-d H:i:s');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
 
            if($model->save()){
                $documento_subido->saveAs(Yii::app()->basePath.'/../files/portal_reportes/resumen_oc_rem/'.$nombre_archivo);
                Yii::app()->user->setFlash('success', "El Resumen # ".$cons." fue creado correctamente.");
                $this->redirect(array('admin'));
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
		$model=$this->loadModel($id);
		$modelo_ant=$this->loadModel($id);

		$opc = 0;

		$ruta_doc_act = Yii::app()->basePath.'/../files/portal_reportes/resumen_oc_rem/'.$modelo_ant->Doc_Soporte;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ResOCR']))
		{
			$model->attributes=$_POST['ResOCR'];

			if($_FILES['ResOCR']['name']['sop']  != "") {

				if($model->Tipo == 1){
					$nombre_doc = $id.'_resumen_oc_'.date('Y_m_d_H_i_s');
				}else{
					$nombre_doc = $id.'_resumen_rem_'.date('Y_m_d_H_i_s');
				}

	            $documento_subido = CUploadedFile::getInstance($model,'sop');
	            $nombre_archivo = "{$nombre_doc}.zip"; 
	            $model->Doc_Soporte = $nombre_archivo;
	            $opc = 1;

		    }else{

		    	if($modelo_ant->Tipo != $model->Tipo){

			    	if($model->Tipo == 1){
						$nombre_doc = $id.'_resumen_oc_'.date('Y_m_d_H_i_s');
					}else{
						$nombre_doc = $id.'_resumen_rem_'.date('Y_m_d_H_i_s');
					}

					$nombre_archivo = "{$nombre_doc}.zip"; 
		            $model->Doc_Soporte = $nombre_archivo;

		            rename(Yii::app()->basePath.'/../files/portal_reportes/resumen_oc_rem/'.$modelo_ant->Doc_Soporte, Yii::app()->basePath.'/../files/portal_reportes/resumen_oc_rem/'.$nombre_archivo);
		        }
		    }

			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Actualizacion = date('Y-m-d H:i:s');

			if($model->save()){
            	if($opc == 1){
            		unlink($ruta_doc_act);
                	$documento_subido->saveAs(Yii::app()->basePath.'/../files/portal_reportes/resumen_oc_rem/'.$nombre_archivo);
            	}
            	Yii::app()->user->setFlash('success', "El Resumen # ".$id." fue actualizado correctamente.");
                $this->redirect(array('admin'));
            }

		}
	
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ResOCR('search');

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ResOCR']))
			$model->attributes=$_GET['ResOCR'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	public function actionAdmin2()
	{
		$model=new ResOCR('search');

		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));
		$model->unsetAttributes();  // clear any default values
		$model->Estado = 1;
		if(isset($_GET['ResOCR']))
			$model->attributes=$_GET['ResOCR'];

		$this->render('admin2',array(
			'model'=>$model,
			'usuarios'=>$usuarios,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ResOCR the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ResOCR::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ResOCR $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='res-ocr-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionDownload($id)
	{
		$model=$this->loadModel($id);

		$archivo = Yii::app()->basePath.'/../files/portal_reportes/resumen_oc_rem/'.$model->Doc_Soporte;

		if(file_exists($archivo)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($archivo).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($archivo));
            flush(); // Flush system output buffer
            readfile($archivo);
        }	
	}
}

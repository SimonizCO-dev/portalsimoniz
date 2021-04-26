<?php

class CPresupuestoController extends Controller
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
				'actions'=>array('imp', 'uploadfile'),
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
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CPresupuesto('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CPresupuesto']))
			$model->attributes=$_GET['CPresupuesto'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CPresupuesto the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CPresupuesto::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CPresupuesto $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cpresupuesto-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionImp()
	{
		$model= new CPresupuesto;
		$model->scenario = 'imp';

		$this->render('imp',array(
			'model'=>$model,
		));
	}

	public function actionUploadFile()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['CPresupuesto']['tmp_name']['archivo'];
        
		set_time_limit(0);

		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';

		spl_autoload_register(array('YiiBase','autoload'));

		$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);

        $objPHPExcel->setActiveSheetIndex(0);

        //Convierto la data de la Hoja en un arreglo
        $dataExcel = $objPHPExcel->getActiveSheet()->toArray();

        $filas = count($dataExcel);

        $cont = 0;

   		if($filas < 2){

        	$opc = 0;
        	$msj = '<h5><i class="icon fas fa-info-circle"></i>Info</h5> El archivo esta vacio.';

        }else{

    		$opc = 1;
    	
    		//se ejecuta el sp por cada fila en el archivo

    		$msj = '<h5><i class="icon fas fa-info-circle"></i>Info</h5>';

    		for($i = 1; $i <= $filas -1; $i++){
        		$param1 = $dataExcel[$i][0]; //Nit vendedor
        		$param2 = $dataExcel[$i][1]; //Presupuesto

        		if(is_null($param1) || is_null($param2)){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias que son requeridas.<br>'; 
        			$opc = 0;
        		}else{

        			$nit = $param1;
        			$pres = $param2;

        			$nit_vend = Yii::app()->db->createCommand("SELECT TOP 1 NIT_VENDEDOR FROM T_PR_C_VENDEDORES WHERE NIT_VENDEDOR = '".$nit."'")->queryRow();

        			if(empty($nit_vend)) {
        				$fila_error = $i + 1;
	        			$msj .= 'Error en la fila # '.$fila_error.', el vendedor con NIT '.$nit.' no existe en el sistema.<br>'; 
	        			$opc = 0;
        			}else{

        				//Se valida si el vlr. asignado como presupuesto es numerico
        				if(!is_numeric($pres)) {
	        				$fila_error = $i + 1;
		        			$msj .= 'Error en la fila # '.$fila_error.', El vlr. asignado como presupuesto no es valido.<br>'; 
		        			$opc = 0;
	        			}else{

	        				$pres_x_vend = Yii::app()->db->createCommand("SELECT TOP 1 NIT_VENDEDOR FROM T_PR_C_PRESUPUESTO WHERE NIT_VENDEDOR = '".$nit."' AND PRESUPUESTO = ".$pres." AND ESTADO = 1" )->queryRow();

	        				if(empty($pres_x_vend)){
								
								$pres_vend_inact = Yii::app()->db->createCommand("UPDATE T_PR_C_PRESUPUESTO SET ESTADO = 0, ID_USUARIO_ACTUALIZACION = ".Yii::app()->user->getState('id_user').", FECHA_ACTUALIZACION = '".date('Y-m-d H:i:s')."' WHERE NIT_VENDEDOR = '".$nit."' AND ESTADO = 1")->execute();
	        				
		        				$nuevo_pres_x_vend=new CPresupuesto;
		        				$nuevo_pres_x_vend->NIT_VENDEDOR = $nit;
		        				$nuevo_pres_x_vend->PRESUPUESTO = $pres;
		        				$nuevo_pres_x_vend->ESTADO = 1;
								$nuevo_pres_x_vend->ID_USUARIO_CREACION = Yii::app()->user->getState('id_user');
								$nuevo_pres_x_vend->FECHA_CREACION = date('Y-m-d H:i:s');
								$nuevo_pres_x_vend->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
								$nuevo_pres_x_vend->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
								if($nuevo_pres_x_vend->save()){
									$cont = $cont + 1;
								}	

        					}else{
        						$cont = $cont + 1;
        					}

	        			}

        			}
        		}
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	if($f == 1){
        		$msj .= 'se ha asignado '.$f.' presupuesto correctamente.<br>'; 	
        	}else{
        		$msj .= 'se han asignado '.$f.' presupuestos correctamente.<br>'; 	
        	}	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);

    }
     
}

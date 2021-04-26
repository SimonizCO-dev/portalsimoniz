<?php

class ReporteCController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */


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

	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform actions
				'actions'=>array('cargarecibos','verifrecibos','aplicrecibos','entfisrecibos','verificacionrecibos','verificacionrecibospant','controlrecibos','controlrecibospant'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCargaRecibos()
	{		
		$model=new ReporteC;
		$model->scenario = 'carga_recibos';

		if(isset($_POST['ReporteC']))
		{
			$model->attributes=$_POST['ReporteC'];
			$recibos = explode(",", $model->recibos);
			$i = 0;

			foreach ($recibos as $indice => $rec) {

				$info = new SplFileInfo($rec);
        		$ext=$info->getExtension();

				$archivo = $rec;

				$coe = strlen($ext);
				$ce = $coe + 1;
				$recibo = substr($archivo,0,-$ce);

				//se verifica si el recibo existe
				$modelorecibo = ControlRecibos::model()->findByAttributes(array('Recibo' => $recibo));

				if(empty($modelorecibo)){

					$origen = Yii::app()->params->directorio_recibos.$archivo;
 
					$destino = Yii::app()->basePath.'/../files/control_recibos/recibos/'.$archivo;
					    
				    if (copy($origen, $destino)) {       
		        		
	        			$command = Yii::app()->db->createCommand();
						$sql="
						INSERT INTO T_PR_CONTROL_RECIBOS (Recibo, Url, Opc, Id_Usuario_Carga, Fecha_Hora_Carga)         
       					SELECT '".$recibo."', '".$archivo."', 1, ".Yii::app()->user->getState('id_user').", '".date('Y-m-d H:i:s')."'
						WHERE NOT EXISTS(SELECT 1 FROM T_PR_CONTROL_RECIBOS WHERE Recibo = '".$recibo."')";
						$command->setText($sql)->execute();

						$i++;
						
						unlink($origen);
						
			        }

				} 
				
			}		

			Yii::app()->user->setFlash('success', $i." Recibo(s) cargado(s).");
			$this->redirect(array('cargarecibos'));

		}

		$this->render('carga_recibos',array(
			'model'=>$model,
		));


	}

	public function actionVerifRecibos()
	{		
		$model=new ReporteC;
		$model->scenario = 'verif_recibos';

		if(isset($_POST['ReporteC']))
		{

			$opc = $_POST['ReporteC']['opc'];

			if($opc == 1){
				//verificacion de recibos

				$recibos = explode(",", $_POST['ReporteC']['recibos']);			
				$opc_ver = explode(",", $_POST['ReporteC']['opc_ver']);
				$fec = explode(",", $_POST['ReporteC']['fec_ver']);
				$obs = explode(",", $_POST['ReporteC']['obs_ver']);
				$i = 0;

				foreach ($recibos as $indice => $rec) {         
	        		$recibo_mod = ControlRecibos::model()->findByPk($rec);
	        		$recibo_mod->Opc = 2;
	        		if($opc_ver[$indice] == 1){
	        			$recibo_mod->Fecha_Banco = $fec[$indice];
	        		}
	        		if($opc_ver[$indice] == 2){
	        			$recibo_mod->Banco_Correcto = $obs[$indice];
	        		}
	        		if($opc_ver[$indice] == 3 || $opc_ver[$indice] == 4){
	        			$recibo_mod->Motivo_Rechazo = $obs[$indice];
	        		}
	        		$recibo_mod->Verificacion = $opc_ver[$indice];
	        		$recibo_mod->Id_Usuario_Verif = Yii::app()->user->getState('id_user');
	        		$recibo_mod->Fecha_Hora_Verif = date('Y-m-d H:i:s');
	        		if($recibo_mod->save()){
	        			$i++;
	        		}
				}		

				Yii::app()->user->setFlash('success', $i." Recibo(s) verificado(s).");

			}

			if($opc == 2){
				//registro de fecha de cheque

				$recibos = explode(",", $_POST['ReporteC']['recibos']);			
				$fec = explode(",", $_POST['ReporteC']['fec_che']);
				$i = 0;

				foreach ($recibos as $indice => $rec) {       
	        		$recibo_mod = ControlRecibos::model()->findByPk($rec);
	        		$recibo_mod->Fecha_Cheque = $fec[$indice];
	        		if($recibo_mod->save()){
	        			$i++;
	        		}
				}		

				Yii::app()->user->setFlash('success', $i." Fecha(s) de cheque(s) asignada(s).");
			}

			if($opc == 3){
				//registro de observaciones


				$recibos = explode(",", $_POST['ReporteC']['recibos']);			
				$obs = explode("||", $_POST['ReporteC']['obs_rec']);
				$i = 0;

				foreach ($recibos as $indice => $rec) {       
	        		$recibo_mod = ControlRecibos::model()->findByPk($rec);
	        		$recibo_mod->Observaciones = $obs[$indice];
	        		$recibo_mod->Fecha_Hora_Obs = date('Y-m-d H:i:s');
	        		if($recibo_mod->save()){
	        			$i++;
	        		}
				}		

				Yii::app()->user->setFlash('success', $i." Observacion(es) asignada(s).");
			}


			$this->redirect(array('verifrecibos'));

		}

		$this->render('verif_recibos',array(
			'model'=>$model,
		));

	}

	public function actionAplicRecibos()
	{		
		$model=new ReporteC;
		$model->scenario = 'aplic_recibos';

		if(isset($_POST['ReporteC']))
		{
			
			$model->attributes=$_POST['ReporteC'];
			$recibos = explode(",", $model->recibos);
			$i = 0;

			foreach ($recibos as $indice => $id_control) {
				$recibo_mod = ControlRecibos::model()->findByPk($id_control);
        		$recibo_mod->Opc = 3;
        		$recibo_mod->Id_Usuario_Aplic = Yii::app()->user->getState('id_user');
        		$recibo_mod->Fecha_Hora_Aplic = date('Y-m-d H:i:s');
        		if($recibo_mod->save()){
        			$i++;
        		}
			}

			Yii::app()->user->setFlash('success', $i." Recibo(s) aplicado(s).");
			$this->redirect(array('aplicrecibos'));

		}

		$this->render('aplic_recibos',array(
			'model'=>$model,
		));

	}

	public function actionEntFisRecibos()
	{		
		$model=new ReporteC;
		$model->scenario = 'ent_fis_recibos';

		if(isset($_POST['ReporteC']))
		{
			
			$model->attributes=$_POST['ReporteC'];
			$recibos = explode(",", $model->recibos);
			$i = 0;

			foreach ($recibos as $indice => $id_control) {
				$recibo_mod = ControlRecibos::model()->findByPk($id_control);
        		$recibo_mod->Opc = 4;
        		$recibo_mod->Id_Usuario_Rec_Fis = Yii::app()->user->getState('id_user');
        		$recibo_mod->Fecha_Hora_Rec_Fis = date('Y-m-d H:i:s');
        		if($recibo_mod->save()){
        			$i++;
        		}
			}

			Yii::app()->user->setFlash('success', $i." Recibo(s) verificado(s).");
			$this->redirect(array('entfisrecibos'));

		}

		$this->render('ent_fis_recibos',array(
			'model'=>$model,
		));

	}

	public function actionVerificacionRecibos()
	{		
		$model=new ReporteC;
		$model->scenario = 'verificacion_recibos';

		if(isset($_POST['ReporteC']))
		{
			$model=$_POST['ReporteC'];
			$this->renderPartial('verificacion_recibos_resp',array('model' => $model));	
		}

		$this->render('verificacion_recibos',array(
			'model'=>$model,
		));
	}

	public function actionVerificacionRecibosPant()
	{		

		$resultados = UtilidadesReportesC::verificacionrecibospantalla();

		echo $resultados;
	}

	public function actionControlRecibos()
	{		
		$model=new ReporteC;
		$model->scenario = 'control_recibos';

		if(isset($_POST['ReporteC']))
		{
			$model=$_POST['ReporteC'];
			$this->renderPartial('control_recibos_resp',array('model' => $model));	
		}

		$this->render('control_recibos',array(
			'model'=>$model,
		));
	}

	public function actionControlRecibosPant()
	{		

		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];

		$resultados = UtilidadesReportesC::controlrecibospantalla($fecha_inicial, $fecha_final);

		echo $resultados;
	}

}


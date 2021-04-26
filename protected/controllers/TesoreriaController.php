<?php

class TesoreriaController extends Controller
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
				'actions'=>array('printcheq','existcheq','regimpcheq','rprintcheq','verifcheq','regrimpcheq'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionPrintCheq()
	{		
		$model=new Tesoreria;
		$model->scenario = 'print_cheq';

		$cos = Yii::app()->db->createCommand("SELECT DISTINCT f350_id_co FROM UnoEE1..t350_co_docto_contable WHERE f350_id_cia = 2")->queryAll();

		$lista_co = array();
		foreach ($cos as $co) {
			$lista_co[$co['f350_id_co']] = $co['f350_id_co'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT f350_id_tipo_docto FROM UnoEE1..t350_co_docto_contable WHERE f350_id_cia = 2")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $td) {
			$lista_tipos[$td['f350_id_tipo_docto']] = $td['f350_id_tipo_docto'];
		}

		$this->render('print_cheq',array(
			'model'=>$model,
			'lista_co'=>$lista_co,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionExistCheq()
	{
		$cia = $_POST['cia'];
		$co = $_POST['co'];
		$tipo = $_POST['tipo'];
		$consecutivo = $_POST['consecutivo'];
		$firma = $_POST['firma'];

		//se verifica si el cheque ya fue impreso
		$modelocheque = ImpCheq::model()->findByAttributes(array('Cia'=>$cia, 'Co'=>$co, "Tipo_Docto"=>$tipo, "Consecutivo"=>$consecutivo));

		if(!empty($modelocheque)){
			$opc = 1;
		}else{
			//se verifica si la combinacion de info trae datos para generar el archivo 
			$query ="
			  SET NOCOUNT ON
			  EXEC P_PR_FIN_CH1
			  @CIA = '".$cia."',
			  @CO = '".$co."',
			  @DOCTO = '".$tipo."',
			  @NUM_INI = ".$consecutivo.",
			  @NUM_FIN = ".$consecutivo."
			";

			$data = Yii::app()->db->createCommand($query)->queryAll();

			if(!empty($data)){
				$this->renderPartial('save_pdf_cheq',array('cia' => $cia, 'co' => $co, 'tipo' => $tipo, 'consecutivo' => $consecutivo, 'firma' => $firma));	
				$opc = 2;
			}else{
				$opc = 0;
			}
		}	

        echo $opc;
		
	}

	public function actionRegImpCheq()
	{
		$cia = $_POST['cia'];
		$co = $_POST['co'];
		$tipo = $_POST['tipo'];
		$consecutivo = $_POST['consecutivo'];
		$firma = $_POST['firma'];

		//se guarda el registro de impresión del cheque
		$modelocheque = new ImpCheq;
		$modelocheque->Cia = $cia;
		$modelocheque->Co = $co;
		$modelocheque->Tipo_Docto = $tipo;
		$modelocheque->Consecutivo = $consecutivo;
		$modelocheque->Firma = $firma;
		$modelocheque->Soporte = $cia.'_'.$co.'_'.$tipo.'_'.$consecutivo.'.pdf';
		$modelocheque->Usuario_Impresion = Yii::app()->user->getState('id_user');
		$modelocheque->Fecha_Hora_Impresion = date('Y-m-d H:i:s');
		if($modelocheque->save()){
			$resp = 1;
		}else{
			$resp = 0;
		}
	
        echo $resp;
		
	}

	public function actionRPrintCheq()
	{		
		$model=new Tesoreria;
		$model->scenario = 'r_print_cheq';

		$cos = Yii::app()->db->createCommand("SELECT DISTINCT f350_id_co FROM UnoEE1..t350_co_docto_contable WHERE f350_id_cia = 2")->queryAll();

		$lista_co = array();
		foreach ($cos as $co) {
			$lista_co[$co['f350_id_co']] = $co['f350_id_co'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT f350_id_tipo_docto FROM UnoEE1..t350_co_docto_contable WHERE f350_id_cia = 2")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $td) {
			$lista_tipos[$td['f350_id_tipo_docto']] = $td['f350_id_tipo_docto'];
		}

		$this->render('r_print_cheq',array(
			'model'=>$model,
			'lista_co'=>$lista_co,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionVerifCheq()
	{
		$cia = $_POST['cia'];
		$co = $_POST['co'];
		$tipo = $_POST['tipo'];
		$consecutivo = $_POST['consecutivo'];

		//se verifica si el cheque ya fue impreso
		$modelocheque = ImpCheq::model()->findByAttributes(array('Cia'=>$cia, 'Co'=>$co, "Tipo_Docto"=>$tipo, "Consecutivo"=>$consecutivo));

		if(!empty($modelocheque)){
			if($modelocheque->Usuario_Reimpresion1 != ""){

				if($modelocheque->Usuario_Reimpresion2 != ""){
					//el cheque solo ha sido impreso 3 veces
					$opc = 1;

				}else{
					//el cheque solo ha sido impreso 2 veces
					$opc = 2;
				}
	
			}else{
				//el cheque solo ha sido impreso 1 vez
				$opc = 2;	
			}
	
		}else{
			//el cheque no ha sido impreso
			$opc = 0;
		}	

        echo $opc;
		
	}

	public function actionRegRImpCheq()
	{
		$cia = $_POST['cia'];
		$co = $_POST['co'];
		$tipo = $_POST['tipo'];
		$consecutivo = $_POST['consecutivo'];

		//se guarda el registro de reimpresión del cheque
		$modelocheque = ImpCheq::model()->findByAttributes(array('Cia'=>$cia, 'Co'=>$co, "Tipo_Docto"=>$tipo, "Consecutivo"=>$consecutivo));
		
		if($modelocheque->Usuario_Reimpresion1 == ""){
			$modelocheque->Usuario_Reimpresion1 = Yii::app()->user->getState('id_user');
			$modelocheque->Fecha_Hora_Reimpresion1 = date('Y-m-d H:i:s');
		}else{
			$modelocheque->Usuario_Reimpresion2 = Yii::app()->user->getState('id_user');
			$modelocheque->Fecha_Hora_Reimpresion2 = date('Y-m-d H:i:s');
		}

		if($modelocheque->save()){
			$resp = 1;
		}else{
			$resp = 0;
		}
		
        echo $resp;
		
	}

	
	
}

<?php

class ReporteController extends Controller
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
				'actions'=>array('searchcliente','searchitem','searchitembyid','loadcriterios', 'getopcionplan'),
				'users'=>array('@'),
			),

			array('allow', // allow authenticated user to perform actions
				'actions'=>array('compincpant'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionSearchCliente(){
		$filtro = $_GET['q'];
		$rep = new Reporte;
        $data = $rep->searchByCliente($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['C_ROWID_CLIENTE'],
               'text' => $item['C_NIT_CLIENTE'].' - '.$item['C_NOMBRE_CLIENTE'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchItem(){
		$filtro = $_GET['q'];
		$rep = new Reporte;
        $data = $rep->searchByItem($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['I_ID_ITEM'],
               'text' => $item['DESCR'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionSearchItemById(){
		$filtro = $_GET['id'];
		$rep = new Reporte;
        $data = $rep->searchById($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['I_ID_ITEM'],
               'text' => $item['DESCR'],
           );
        endforeach;

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionLoadCriterios()
	{
		$plan = $_POST['plan'];

		$criterios= Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM TH_CRITERIOS_CLIENTES WHERE Id_Plan = ".$plan." ORDER BY Criterio_Descripcion")->queryAll();	

		$i = 0;
		$array_criterios = array();
		
		foreach ($criterios as $c) {
			$array_criterios[$i] = array('id' => $c['Id_Criterio'],  'text' => $c['Criterio_Descripcion']);
			$i++; 

		}
		
		echo json_encode($array_criterios);
	}

	public function actionGetOpcionPlan()
	{

		$plan = $_POST['plan'];

		switch ($plan) {
		    case 100:
		        $opc = 'C_CLASE'; 
		        break;
		    case 200:
		        $opc = 'C_CANAL'; 
		        break;
		    case 300:
		        $opc = 'C_ESTRUCTURA'; 
		        break;
		    case 400:
		        $opc = 'C_SEGMENTO'; 
		        break;
		    case 500:
		        $opc = 'C_TIPOLOGIA'; 
		        break;
		    case 600:
		        $opc = 'C_REGIONALES'; 
		        break;
		    case 700:
		        $opc = 'C_WMS'; 
		        break;
		    case 800:
		        $opc = 'C_RUTA'; 
		        break;
		    case 850:
		        $opc = 'C_DEPARTAMENTO'; 
		        break;
		    case 870:
		        $opc = 'C_COND_PAGO'; 
		        break;
		    case 900:
		        $opc = 'C_CLASIFICACION'; 
		        break;
		    case 950:
		        $opc = 'C_COORDINADOR'; 
		        break;
		    case 960:
		        $opc = 'C_REGIMEN'; 
		        break;
		}

		echo $opc;

	}

	public function actionCompInc()
	{		
		$model=new Reporte;
		$model->scenario = 'comp_inc';

		$this->render('comp_inc',array(
			'model'=>$model,
		));
	}

	public function actionCompIncPant()
	{		

		$tipo = $_POST['tipo'];
		$cons_inicial = $_POST['cons_inicial'];
		$cons_final = $_POST['cons_final'];

		$resultados = UtilidadesReportes::compincpantalla($tipo, $cons_inicial, $cons_final);

		echo $resultados;
	}
	
}

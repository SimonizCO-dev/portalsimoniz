<?php

class CarteraController extends Controller
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
				'actions'=>array('actualizaciondatossaldo','actualizaciondatos','cambioasesor','cobroprejuridico','saldocliente','consultapagos','consultapagospant','cruceantcli','crucenotcon','docsasesor','histcliente','notasanulacion','crucenotcar','notascredito','notasdevolucion','recaudosvendedor','recxwebservice','pedidosretenidos','saldocarteracliente','saldocarteraev','saldocarteraruta','saldocarteravendedor','saldocarteraco999','saldocarteracolitigio','saldocarteraco','saldocarteracototal'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionActualizacionDatosSaldo()
	{		
		$model=new Cartera;
		$model->scenario = 'actualizacion_datos_saldo';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id, f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_estado = 1 ")->queryAll();

		$lista_rutas = array();
		foreach ($rutas as $ru) {
			$lista_rutas[$ru['Id']] = $ru['Ruta'];
		}

		$lista_estados = array(0 => 'INACTIVOS', 1 => 'ACTIVOS');

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('actualizacion_datos_saldo_resp',array('model' => $_POST['Cartera']));
		}

		$this->render('actualizacion_datos_saldo',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
			'lista_estados'=>$lista_estados,
		));
	}

	public function actionActualizacionDatos()
	{		
		$model=new Cartera;
		$model->scenario = 'actualizacion_datos';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id, f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_estado = 1 ")->queryAll();

		$lista_rutas = array();
		foreach ($rutas as $ru) {
			$lista_rutas[$ru['Id']] = $ru['Ruta'];
		}

		$lista_estados = array(0 => 'INACTIVOS', 1 => 'ACTIVOS');

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('actualizacion_datos_resp',array('model' => $_POST['Cartera']));
		}

		$this->render('actualizacion_datos',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
			'lista_estados'=>$lista_estados,
		));
	}

	public function actionCambioAsesor()
	{		
		$model=new Cartera;
		$model->scenario = 'cambio_asesor';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id, f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_estado = 1 ")->queryAll();

		$lista_rutas = array();
		foreach ($rutas as $ru) {
			$lista_rutas[$ru['Id']] = $ru['Ruta'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('cambio_asesor_resp',array('model' => $_POST['Cartera']));
		}

		$this->render('cambio_asesor',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
		));
	}

	public function actionCobroPrejuridico()
	{		
		$model=new Cartera;
		$model->scenario = 'cobro_prejuridico';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id, f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_estado = 1 ")->queryAll();

		$lista_rutas = array();
		foreach ($rutas as $ru) {
			$lista_rutas[$ru['Id']] = $ru['Ruta'];
		}

		$lista_estados = array(0 => 'INACTIVOS', 1 => 'ACTIVOS');

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('cobro_prejuridico_resp',array('model' => $_POST['Cartera']));
		}

		$this->render('cobro_prejuridico',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
			'lista_estados'=>$lista_estados,
		));
	}

	public function actionSaldoCliente()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cliente';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id, f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_estado = 1 ")->queryAll();

		$lista_rutas = array();
		foreach ($rutas as $ru) {
			$lista_rutas[$ru['Id']] = $ru['Ruta'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('saldo_cliente_resp',array('model' => $_POST['Cartera']));
		}

		$this->render('saldo_cliente',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
		));
	}

	public function actionConsultaPagos()
	{		
		$model=new Cartera;
		$model->scenario = 'consulta_pagos';

		if(isset($_POST['Cartera']))
		{
			$model=$_POST['Cartera'];
			$this->renderPartial('consulta_pagos_resp',array('model' => $model));	
		}

		$this->render('consulta_pagos',array(
			'model'=>$model,
		));
	}

	public function actionConsultaPagosPant()
	{		
		$resultados = UtilidadesReportes::consultapagospantalla();
		echo $resultados;
	}

	public function actionCruceAntCli()
	{		
		$model=new Cartera;
		$model->scenario = 'cruce_ant_cli';

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('cruce_ant_cli_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('cruce_ant_cli',array(
			'model'=>$model,
		));
	}

	public function actionCruceNotCon()
	{		
		$model=new Cartera;
		$model->scenario = 'cruce_not_con';

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('cruce_not_con_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('cruce_not_con',array(
			'model'=>$model,
		));
	}

	public function actionDocsAsesor()
	{		
		$model=new Cartera;
		$model->scenario = 'docs_asesor';

		if(isset($_POST['Cartera']))
		{
			$model=$_POST['Cartera'];
			$this->renderPartial('docs_asesor_resp',array('model' => $model));	
		}

		$this->render('docs_asesor',array(
			'model'=>$model,
		));
	}

	public function actionHistCliente()
	{		
		$model=new Cartera;
		$model->scenario = 'hist_cliente';

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('hist_cliente_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('hist_cliente',array(
			'model'=>$model,
		));
	}

	public function actionNotasAnulacion()
	{		
		$model=new Cartera;
		$model->scenario = 'notas_anulacion';

		if(isset($_POST['Cartera']))
		{
			$model=$_POST['Cartera'];
			$this->renderPartial('notas_anulacion_resp',array('model' => $model));	
		}

		$this->render('notas_anulacion',array(
			'model'=>$model,
		));
	}

	public function actionCruceNotCar()
	{		
		$model=new Cartera;
		$model->scenario = 'cruce_not_car';

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('cruce_not_car_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('cruce_not_car',array(
			'model'=>$model,
		));
	}

	public function actionNotasCredito()
	{		
		$model=new Cartera;
		$model->scenario = 'notas_credito';

		if(isset($_POST['Cartera']))
		{
			$model=$_POST['Cartera'];
			$this->renderPartial('notas_credito_resp',array('model' => $model));	
		}

		$this->render('notas_credito',array(
			'model'=>$model,
		));
	}

	public function actionNotasDevolucion()
	{		
		$model=new Cartera;
		$model->scenario = 'notas_devolucion';

		if(isset($_POST['Cartera']))
		{
			$model=$_POST['Cartera'];
			$this->renderPartial('notas_devolucion_resp',array('model' => $model));	
		}

		$this->render('notas_devolucion',array(
			'model'=>$model,
		));
	}

	public function actionRecaudosVendedor()
	{		
		$model=new Cartera;
		$model->scenario = 'recaudos_vendedor';

		$vendedores = Yii::app()->db->createCommand("SELECT distinct t2001.f200_razon_social as Nombre_Vendedor FROM UnoEE1.dbo.t210_mm_vendedores WITH (NOLOCK) inner join UnoEE1.dbo.t200_mm_terceros as t2001 WITH (NOLOCK) ON t2001.f200_rowid = [f210_rowid_tercero] where [f210_id_cia] = 2")->queryAll();
 
		$lista_vendedores = array();
		foreach ($vendedores as $vend) {
			$lista_vendedores[$vend['Nombre_Vendedor']] = $vend['Nombre_Vendedor'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('recaudos_vendedor_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('recaudos_vendedor',array(
			'model'=>$model,
			'lista_vendedores'=>$lista_vendedores,
		));
	}

	public function actionRecXWebService()
	{		
		$model=new Cartera;
		$model->scenario = 'rec_x_web_service';

		if(isset($_POST['Cartera']))
		{
			$model->attributes=$_POST['Cartera'];
			$this->renderPartial('rec_x_web_service_resp',array('model' => $model));	
		}

		$this->render('rec_x_web_service',array(
			'model'=>$model,		
		));
	}

	public function actionPedidosRetenidos()
	{		
		$model=new Cartera;
		$model->scenario = 'pedidos_retenidos';

		$this->renderPartial('pedidos_retenidos_resp');
	}

	public function actionSaldoCarteraCliente()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_cliente';

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('saldo_cartera_cliente_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('saldo_cartera_cliente',array(
			'model'=>$model,
		));
	}

	public function actionSaldoCarteraEv()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_ev';

		$evs = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_evs = array();
		foreach ($evs as $ev) {
			$lista_evs[$ev['Id_Criterio']] = $ev['Criterio_Descripcion'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('saldo_cartera_ev_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('saldo_cartera_ev',array(
			'model'=>$model,
			'lista_evs'=>$lista_evs,
		));
	}

	public function actionSaldoCarteraRuta()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_ruta';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id AS Id, f5790_descripcion as Ruta FROM UnoEE1..t5790_sm_ruta WHERE f5790_estado = 1 ")->queryAll();

		$lista_rutas = array();
		foreach ($rutas as $ru) {
			$lista_rutas[$ru['Id']] = $ru['Ruta'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('saldo_cartera_ruta_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('saldo_cartera_ruta',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
		));
	}

	public function actionSaldoCarteraVendedor()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_vendedor';

		$vendedores = Yii::app()->db->createCommand("SELECT DISTINCT t2001.f200_razon_social AS Nombre_Vendedor FROM UnoEE1..t210_mm_vendedores WITH (NOLOCK) INNER JOIN UnoEE1..t200_mm_terceros as t2001 WITH (NOLOCK) ON t2001.f200_rowid = f210_rowid_tercero WHERE f210_id_cia = 2")->queryAll();
 
		$lista_vendedores = array();
		foreach ($vendedores as $vend) {
			$lista_vendedores[$vend['Nombre_Vendedor']] = $vend['Nombre_Vendedor'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('saldo_cartera_vendedor_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('saldo_cartera_vendedor',array(
			'model'=>$model,
			'lista_vendedores'=>$lista_vendedores,
		));
	}

	public function actionSaldoCarteraCo999()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_co_999';

		$this->renderPartial('saldo_cartera_co_999_resp');
	}

	public function actionSaldoCarteraCoLitigio()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_co_litigio';

		$this->renderPartial('saldo_cartera_co_litigio_resp');
	}

	public function actionSaldoCarteraCo()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_co';

		$cos = Yii::app()->db->createCommand("SELECT DISTINCT f285_id, f285_descripcion FROM UnoEE1..t285_co_centro_op WHERE f285_id_cia = 2")->queryAll();

		$lista_co = array();
		foreach ($cos as $co) {
			$lista_co[$co['f285_id']] = $co['f285_descripcion'];
		}

		if(isset($_POST['Cartera']))
		{
			$this->renderPartial('saldo_cartera_co_resp',array('model' => $_POST['Cartera']));	
		}

		$this->render('saldo_cartera_co',array(
			'model'=>$model,
			'lista_co'=>$lista_co,
		));
	}

	public function actionSaldoCarteraCoTotal()
	{		
		$model=new Cartera;
		$model->scenario = 'saldo_cartera_co_total';

		$this->renderPartial('saldo_cartera_co_total_resp');
	}

}

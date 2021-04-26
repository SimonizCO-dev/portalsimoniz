<?php

class InventariosController extends Controller
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
				'actions'=>array('pedidosacumlinea','pedidosacummarca','pedidosacumoracle','pedidosacumlineatot','controlpedidoslinea','controlpedidosmarca','controlpedidosoracle','controlpedidossegmento','controlpedidosorigen','controlpedidoslinealista','controlpedidosmarcalista','analisisxproducto','itemscostos','diferenciasun','diferenciasunpant','listasvs560','logisticaexterior','logisticacomercialxora','logisticacomercialxun','uncomercial'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	public function actionPedidosAcumLinea()
	{		
		$model=new Inventarios;
		$model->scenario = 'pedidos_acum_linea';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('pedidos_acum_linea_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('pedidos_acum_linea',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionPedidosAcumMarca()
	{		
		$model=new Inventarios;
		$model->scenario = 'pedidos_acum_marca';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('pedidos_acum_marca_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('pedidos_acum_marca',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionPedidosAcumOracle()
	{		
		$model=new Inventarios;
		$model->scenario = 'pedidos_acum_oracle';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=950 ORDER BY DESCRIPCION")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $or) {
			$lista_oracle[$or['DESCRIPCION']] = $or['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('pedidos_acum_oracle_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('pedidos_acum_oracle',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionPedidosAcumLineaTot()
	{		
		$model=new Inventarios;
		$model->scenario = 'pedidos_acum_linea_tot';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('pedidos_acum_linea_tot_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('pedidos_acum_linea_tot',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionControlPedidosLinea()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_linea';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_linea_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_linea',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionControlPedidosMarca()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_marca';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_marca_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_marca',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionControlPedidosOracle()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_oracle';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=950 ORDER BY DESCRIPCION")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $or) {
			$lista_oracle[$or['DESCRIPCION']] = $or['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_oracle_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_oracle',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionControlPedidosSegmento()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_segmento';

		$segmentos = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=600 ORDER BY DESCRIPCION")->queryAll();

		$lista_segmentos = array();
		foreach ($segmentos as $se) {
			$lista_segmentos[$se['DESCRIPCION']] = $se['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_segmento_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_segmento',array(
			'model'=>$model,
			'lista_segmentos'=>$lista_segmentos,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}
	

	public function actionControlPedidosOrigen()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_origen';

		$origenes = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS ORIGEN FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100 ORDER BY DESCRIPCION")->queryAll();

		$lista_origenes = array();
		foreach ($origenes as $or) {
			$lista_origenes[$or['ORIGEN']] = $or['ORIGEN'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_origen_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_origen',array(
			'model'=>$model,
			'lista_origenes'=>$lista_origenes,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionControlPedidosLineaLista()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_linea_lista';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_linea_lista_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_linea_lista',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionControlPedidosMarcaLista()
	{		
		$model=new Inventarios;
		$model->scenario = 'control_pedidos_marca_lista';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT CI_TIPO, CASE CI_TIPO WHEN 'COM' THEN 'COMPRADOS' WHEN 'FAB' THEN 'FABRICADOS' END DESCRIPCION FROM T_CF_CONTROL_ITEMS WHERE CI_TIPO IS NOT NULL")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['CI_TIPO']] = $ti['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('control_pedidos_marca_lista_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('control_pedidos_marca_lista',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
			'lista_estados'=>$lista_estados,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionAnalisisXProducto()
	{		
		$model=new Inventarios;
		$model->scenario = 'analisis_x_producto';

		$this->renderPartial('analisis_x_producto_resp');
	}

	public function actionItemsCostos()
	{		
		$model=new Inventarios;
		$model->scenario = 'items_costos';

		$clases = Yii::app()->db->createCommand("SELECT DISTINCT I_CRI_CLASE FROM T_CF_ITEMS")->queryAll();

		$lista_clases = array();
		foreach ($clases as $cl) {
			$lista_clases[$cl['I_CRI_CLASE']] = $cl['I_CRI_CLASE'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $oracle) {
			$lista_oracle[$oracle['Id_Criterio']] = $oracle['Criterio_Descripcion'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('items_costos_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('items_costos',array(
			'model'=>$model,
			'lista_clases'=>$lista_clases,
			'lista_oracle'=>$lista_oracle,
			'lista_estados'=>$lista_estados,
		));
	}

	public function actionDiferenciasUn()
	{		
		$model=new Inventarios;
		$model->scenario = 'diferencias_un';

		if(isset($_POST['Inventarios']))
		{
			$model=$_POST['Inventarios'];
			$this->renderPartial('diferencias_un_resp');	
		}

		$this->render('diferencias_un',array(
			'model'=>$model,
		));
	}

	public function actionDiferenciasUnPant()
	{		

		$resultados = UtilidadesReportes::diferenciasunpantalla();

		echo $resultados;
	}

	public function actionListasVs560()
	{		
		$model=new Inventarios;
		$model->scenario = 'listas_vs_560';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}

		$listas = Yii::app()->db->createCommand("SELECT DISTINCT f112_id AS LISTA, f112_descripcion AS DESCRIPCION FROM UnoEE1.dbo.t112_mc_listas_precios WHERE f112_id_cia = '2' and f112_id_moneda = 'COP' AND f112_id <> '560' ORDER BY 2")->queryAll();

		$lista_l = array();
		foreach ($listas as $li) {
			$lista_l[$li['LISTA']] = $li['LISTA'].' - '.$li['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('listas_vs_560_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('listas_vs_560',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
			'lista_estados'=>$lista_estados,
			'lista_l'=>$lista_l,
		));
	}

	public function actionLogisticaExterior()
	{		
		$model=new Inventarios;
		$model->scenario = 'logistica_exterior';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$model->attributes=$_POST['Inventarios'];
			$this->renderPartial('logistica_exterior_resp',array('model' => $model));	
		}

		$this->render('logistica_exterior',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
		));
	}

	public function actionLogisticaComercialxOra()
	{		
		$model=new Inventarios;
		$model->scenario = 'logistica_comercial_x_ora';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=950 ORDER BY DESCRIPCION")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $or) {
			$lista_oracle[$or['DESCRIPCION']] = $or['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('logistica_comercial_x_ora_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('logistica_comercial_x_ora',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
		));
	}

	public function actionLogisticaComercialxUn()
	{		
		$model=new Inventarios;
		$model->scenario = 'logistica_comercial_x_un';

		$un = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=900 ORDER BY DESCRIPCION")->queryAll();

		$lista_un = array();
		foreach ($un as $lun) {
			$lista_un[$lun['DESCRIPCION']] = $lun['DESCRIPCION'];
		}

		if(isset($_POST['Inventarios']))
		{
			$this->renderPartial('logistica_comercial_x_un_resp',array('model' => $_POST['Inventarios']));
		}

		$this->render('logistica_comercial_x_un',array(
			'model'=>$model,
			'lista_un'=>$lista_un,
		));
	}

	public function actionUnComercial()
	{		
		$model=new Inventarios;
		$model->scenario = 'un_comercial';

		$this->renderPartial('un_comercial_resp');
	}
	
}

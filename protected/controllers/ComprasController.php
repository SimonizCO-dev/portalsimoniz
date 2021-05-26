<?php

class ComprasController extends Controller
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
				'actions'=>array('cuadrocompraspt','cuadrocompraspt2','cuadrocomprasmp','cuadrocomprasmp2'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCuadroComprasPt()
	{		
		$model=new Compras;
		$model->scenario = 'cuadro_compras_pt';

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}
		
		$origenes = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100")->queryAll();

		$lista_origenes = array();
		foreach ($origenes as $or) {
			$lista_origenes[$or['DESCRIPCION']] = $or['DESCRIPCION'];
		}

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[$ora['DESCRIPCION']] = $ora['DESCRIPCION'];
		}

		$proveedor = Yii::app()->db->createCommand("SELECT DISTINCT f123_dato as Proveedor FROM UnoEE1..t123_mc_items_desc_tecnicas WHERE f123_id_Cia = 2 AND f123_rowid_campo = 675 AND f123_dato != '' ORDER BY 1")->queryAll();

		$lista_pro = array();
		foreach ($proveedor as $pro) {
			$lista_pro[$pro['Proveedor']] = $pro['Proveedor'];
		}

		if(isset($_POST['Compras']))
		{
			$this->renderPartial('cuadro_compras_pt_resp',array('model' => $_POST['Compras']));
		}

		$this->render('cuadro_compras_pt',array(
			'model'=>$model,
			'lista_estados'=>$lista_estados,
			'lista_origenes'=>$lista_origenes,
			'lista_marcas'=>$lista_marcas,
			'lista_lineas'=>$lista_lineas,
			'lista_oracle'=>$lista_oracle, 
			'lista_pro'=>$lista_pro,			
		));
	}

	public function actionCuadroComprasPt2()
	{		
		$model=new Compras;
		$model->scenario = 'cuadro_compras_pt2';

		$estados = Yii::app()->db->createCommand("SELECT DISTINCT I_ESTADO FROM T_CF_ITEMS")->queryAll();

		$lista_estados = array();
		foreach ($estados as $es) {
			$lista_estados[$es['I_ESTADO']] = $es['I_ESTADO'];
		}
		
		$origenes = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100")->queryAll();

		$lista_origenes = array();
		foreach ($origenes as $or) {
			$lista_origenes[$or['DESCRIPCION']] = $or['DESCRIPCION'];
		}

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[$ora['DESCRIPCION']] = $ora['DESCRIPCION'];
		}

		$proveedor = Yii::app()->db->createCommand("SELECT DISTINCT f123_dato as Proveedor FROM UnoEE1..t123_mc_items_desc_tecnicas WHERE f123_id_Cia = 2 AND f123_rowid_campo = 675 AND f123_dato != '' ORDER BY 1")->queryAll();

		$lista_pro = array();
		foreach ($proveedor as $pro) {
			$lista_pro[$pro['Proveedor']] = $pro['Proveedor'];
		}

		if(isset($_POST['Compras']))
		{
			$this->renderPartial('cuadro_compras_pt2_resp',array('model' => $_POST['Compras']));
		}

		$this->render('cuadro_compras_pt2',array(
			'model'=>$model,
			'lista_estados'=>$lista_estados,
			'lista_origenes'=>$lista_origenes,
			'lista_marcas'=>$lista_marcas,
			'lista_lineas'=>$lista_lineas,
			'lista_oracle'=>$lista_oracle, 
			'lista_pro'=>$lista_pro,			
		));
	}

	public function actionCuadroComprasMp()
	{		
		$this->renderPartial('cuadro_compras_mp_resp');
	
	}

	public function actionCuadroComprasMp2()
	{		
		$this->renderPartial('cuadro_compras_mp2_resp');
	
	}
}

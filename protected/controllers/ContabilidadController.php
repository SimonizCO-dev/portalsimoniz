<?php

class ContabilidadController extends Controller
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
				'actions'=>array('facturacomstar','facturapansell','facturaproforma','facturatitan','facturapos','itemsexentosiva'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionFacturaComstar()
	{		
		$model=new Contabilidad;
		$model->scenario = 'factura_comstar';

		if(isset($_POST['Contabilidad']))
		{
			$this->renderPartial('factura_comstar_resp',array('model' => $_POST['Contabilidad']));
		}

		$this->render('factura_comstar',array(
			'model'=>$model,
		));
	}

	public function actionFacturaPansell()
	{		
		$model=new Contabilidad;
		$model->scenario = 'factura_pansell';

		if(isset($_POST['Contabilidad']))
		{
			$this->renderPartial('factura_pansell_resp',array('model' => $_POST['Contabilidad']));
		}

		$this->render('factura_pansell',array(
			'model'=>$model,
		));
	}

	public function actionFacturaProforma()
	{		
		$model=new Contabilidad;
		$model->scenario = 'factura_proforma';

		$cos = Yii::app()->db->createCommand("SELECT DISTINCT f350_id_co FROM UnoEE1..t350_co_docto_contable WHERE f350_id_cia = 2")->queryAll();

		$lista_co = array();
		foreach ($cos as $co) {
			$lista_co[$co['f350_id_co']] = $co['f350_id_co'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT f350_id_tipo_docto FROM UnoEE1..t350_co_docto_contable WHERE f350_id_cia = 2 AND f350_id_tipo_docto like 'R%'")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $td) {
			$lista_tipos[$td['f350_id_tipo_docto']] = $td['f350_id_tipo_docto'];
		}

		if(isset($_POST['Contabilidad']))
		{
			$this->renderPartial('factura_proforma_resp',array('model' => $_POST['Contabilidad']));
		}

		$this->render('factura_proforma',array(
			'model'=>$model,
			'lista_co'=>$lista_co,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionFacturaTitan()
	{		
		$model=new Contabilidad;
		$model->scenario = 'factura_titan';

		if(isset($_POST['Contabilidad']))
		{
			$this->renderPartial('factura_titan_resp',array('model' => $_POST['Contabilidad']));
		}

		$this->render('factura_titan',array(
			'model'=>$model,
		));
	}

	public function actionFacturaPos()
	{		
		$model=new Contabilidad;
		$model->scenario = 'factura_pos';

		if(isset($_POST['Contabilidad']))
		{
			$this->renderPartial('factura_pos_resp',array('model' => $_POST['Contabilidad']));
		}

		$this->render('factura_pos',array(
			'model'=>$model,
		));
	}

	public function actionItemsExentosIva()
	{		
		$model=new Contabilidad;
		$model->scenario = 'items_exentos_iva';

		if(isset($_POST['Contabilidad']))
		{
			$this->renderPartial('items_exentos_iva_resp',array('model' => $_POST['Contabilidad']));	
		}

		$this->render('items_exentos_iva',array(
			'model'=>$model,
		));
	}


	
	
}

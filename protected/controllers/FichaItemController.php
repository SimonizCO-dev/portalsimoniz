<?php

class FichaItemController extends Controller
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
				'actions'=>array('create','create2','update','update2','searchitem','searchitembyid','getinfoitem'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','notas','redirect'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate($s)
	{
		$model=new FichaItem;
		$model->Scenario = 'desarrollo';

		$e = 0;

		if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(1))){
			$e = 1;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$unidad = Yii::app()->db->createCommand("SELECT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_unidad = array();
		foreach ($unidad as $uni) {
			$lista_unidad[trim($uni['f101_id'])] = $uni['f101_id'].' - '.$uni['f101_descripcion'];
		}

		$tipo_inv = Yii::app()->db->createCommand("SELECT f149_id, f149_descripcion FROM UnoEE1..t149_mc_tipo_inv_serv WHERE f149_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_tipo_inv = array();
		foreach ($tipo_inv as $ti_inv) {
			$lista_tipo_inv[trim($ti_inv['f149_id'])] = $ti_inv['f149_id'].' - '.$ti_inv['f149_descripcion'];
		}

		$grupo_imp = Yii::app()->db->createCommand("SELECT f113_id, f113_descripcion FROM UnoEE1..t113_mc_grupos_impositivos WHERE f113_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_grupo_imp = array();
		foreach ($grupo_imp as $gr_i) {
			$lista_grupo_imp[trim($gr_i['f113_id'])] = $gr_i['f113_id'].' - '.$gr_i['f113_descripcion'];
		}

		$origen = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100 ORDER BY 2")->queryAll();

		$lista_origen = array();
		foreach ($origen as $ori) {
			$lista_origen[trim($ori['Id_Criterio'])] = $ori['Id_Criterio'].' - '.$ori['Criterio_Descripcion'];
		}

		$tipo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 200 ORDER BY 2")->queryAll();

		$lista_tipo = array();
		foreach ($tipo as $tip) {
			$lista_tipo[trim($tip['Id_Criterio'])] = $tip['Id_Criterio'].' - '.$tip['Criterio_Descripcion'];
		}

		$clasif = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 300 ORDER BY 2")->queryAll();

		$lista_clasif = array();
		foreach ($clasif as $clas) {
			$lista_clasif[trim($clas['Id_Criterio'])] = $clas['Id_Criterio'].' - '.$clas['Criterio_Descripcion'];
		}

		$clase = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 400 ORDER BY 2")->queryAll();

		$lista_clase = array();
		foreach ($clase as $cl) {
			$lista_clase[trim($cl['Id_Criterio'])] = $cl['Id_Criterio'].' - '.$cl['Criterio_Descripcion'];
		}

		$marca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY 2")->queryAll();

		$lista_marca = array();
		foreach ($marca as $mar) {
			$lista_marca[trim($mar['Id_Criterio'])] = $mar['Id_Criterio'].' - '.$mar['Criterio_Descripcion'];
		}

		$submarca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 550 ORDER BY 2")->queryAll();

		$lista_submarca = array();
		foreach ($submarca as $subm) {
			$lista_submarca[trim($subm['Id_Criterio'])] = $subm['Id_Criterio'].' - '.$subm['Criterio_Descripcion'];
		}

		$segmento = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 600 ORDER BY 2")->queryAll();

		$lista_segmento = array();
		foreach ($segmento as $seg) {
			$lista_segmento[trim($seg['Id_Criterio'])] = $seg['Id_Criterio'].' - '.$seg['Criterio_Descripcion'];
		}

		$familia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 650 ORDER BY 2")->queryAll();

		$lista_familia = array();
		foreach ($familia as $fam) {
			$lista_familia[trim($fam['Id_Criterio'])] = $fam['Id_Criterio'].' - '.$fam['Criterio_Descripcion'];
		}

		$linea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700 ORDER BY 2")->queryAll();

		$lista_linea = array();
		foreach ($linea as $lin) {
			$lista_linea[trim($lin['Id_Criterio'])] = $lin['Id_Criterio'].' - '.$lin['Criterio_Descripcion'];
		}

		$subfamilia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 750 ORDER BY 2")->queryAll();

		$lista_subfamilia = array();
		foreach ($subfamilia as $subfam) {
			$lista_subfamilia[trim($subfam['Id_Criterio'])] = $subfam['Id_Criterio'].' - '.$subfam['Criterio_Descripcion'];
		}

		$sublinea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 800 ORDER BY 2")->queryAll();

		$lista_sublinea = array();
		foreach ($sublinea as $sublin) {
			$lista_sublinea[trim($sublin['Id_Criterio'])] = $sublin['Id_Criterio'].' - '.$sublin['Criterio_Descripcion'];
		}

		$grupo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 850 ORDER BY 2")->queryAll();

		$lista_grupo = array();
		foreach ($grupo as $gr) {
			$lista_grupo[trim($gr['Id_Criterio'])] = $gr['Id_Criterio'].' - '.$gr['Criterio_Descripcion'];
		}

		$uni_neg = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 900 ORDER BY 2")->queryAll();

		$lista_un = array();
		foreach ($uni_neg as $un) {
			$lista_un[trim($un['Id_Criterio'])] = $un['Id_Criterio'].' - '.$un['Criterio_Descripcion'];
		}

		$fabrica = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 920 ORDER BY 2")->queryAll();

		$lista_fabrica = array();
		foreach ($fabrica as $fab) {
			$lista_fabrica[trim($fab['Id_Criterio'])] = $fab['Id_Criterio'].' - '.$fab['Criterio_Descripcion'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY 2")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[trim($ora['Id_Criterio'])] = $ora['Id_Criterio'].' - '.$ora['Criterio_Descripcion'];
		}

		$instalaciones = Yii::app()->db->createCommand("SELECT f157_id, f157_descripcion FROM UnoEE1..t157_mc_instalaciones WHERE f157_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_ins = array();
		foreach ($instalaciones as $ins) {
			$lista_ins[trim($ins['f157_id'])] = $ins['f157_id'].' - '.$ins['f157_descripcion'];
		}

		$bodegas = Yii::app()->db->createCommand("SELECT f150_id, f150_descripcion_corta FROM UnoEE1..t150_mc_bodegas WHERE f150_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_bodegas = array();
		foreach ($bodegas as $bod) {
			$lista_bodegas[trim($bod['f150_id'])] = $bod['f150_id'].' - '.$bod['f150_descripcion_corta'];
		}

		if(isset($_POST['FichaItem']))
		{
			$model->attributes=$_POST['FichaItem'];
			$model->Pais = implode(",", $_POST['FichaItem']['Pais']);
			$model->Estado_Solicitud = 1;
			$model->Step = 3;
			$model->Tipo = 1;
			$model->Id_Usuario_Solicitud = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Solicitud = date('Y-m-d H:i:s');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
			
			if($model->Tipo_Producto != 1){
				$model->Presentacion = null;
				$model->Envase = null;
				$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
			}else{
				$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
			}

			if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
				$model->Ep_Medida = null;
				$model->Cad_Medida = null;
			}

			$model->Tipo_Inventario = null;
			$model->Grupo_Impositivo = null;
			$model->Exento_Impuesto = null;

			$model->Crit_Origen = null;
			$model->Crit_Tipo = null;
			$model->Crit_Clasificacion = null;
			$model->Crit_Clase = null;
			$model->Crit_Marca = null;
			$model->Crit_Submarca = null;
			$model->Crit_Segmento = null;
			$model->Crit_Familia = null;
			$model->Crit_Subfamilia = null;
			$model->Crit_Linea = null;
			$model->Crit_Sublinea = null;
			$model->Crit_Grupo = null;
			$model->Crit_UN = null;
			$model->Crit_Fabrica = null;
			$model->Crit_Cat_Oracle = null;

			$model->Un_Cant = null;
			$model->Un_Peso = null;
			$model->Un_Largo = null;
			$model->Un_Ancho = null;
			$model->Un_Alto = null;
			$model->Un_Volumen = null;

			$model->Ep_Cant = null;
			$model->Ep_Peso = null;
			$model->Ep_Largo = null;
			$model->Ep_Ancho = null;
			$model->Ep_Alto = null;
			$model->Ep_Volumen = null;

			$model->Cad_Cant = null;
			$model->Cad_Peso = null;
			$model->Cad_Largo = null;
			$model->Cad_Ancho = null;
			$model->Cad_Alto = null;
			$model->Cad_Volumen = null;

			$model->Codigo_Item = null;
			$model->Referencia = null;

			$model->Maneja_Lote = null;
			$model->Tiempo_Reposicion = null;
			$model->Cant_Moq = null;
			$model->Stock_Minimo = null;
			$model->Instalaciones = null;
			$model->Bodegas = null;

			$model->Un_Gtin = null;
			$model->Ep_Gtin = null;
			$model->Cad_Gtin = null;
			
			if($model->save()){
				$emails_envio = Utilidadesmail::emailsfichaitem($model->Step);
				if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviofichaitem($model->Id, 1, $model->Step, $emails_envio, '');	
				}
				Yii::app()->user->setFlash('success', "Solicitud creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			's'=>$s,
			'e'=>$e,
			'lista_unidad'=>$lista_unidad,
			'lista_tipo_inv'=>$lista_tipo_inv,
			'lista_grupo_imp'=>$lista_grupo_imp,
			'lista_origen'=>$lista_origen,
			'lista_tipo'=>$lista_tipo,
			'lista_clasif'=>$lista_clasif,
			'lista_clase'=>$lista_clase,
			'lista_marca'=>$lista_marca,
			'lista_submarca'=>$lista_submarca,
			'lista_segmento'=>$lista_segmento,
			'lista_familia'=>$lista_familia,
			'lista_linea'=>$lista_linea,
			'lista_subfamilia'=>$lista_subfamilia,
			'lista_sublinea'=>$lista_sublinea,
			'lista_grupo'=>$lista_grupo,
			'lista_un'=>$lista_un,
			'lista_fabrica'=>$lista_fabrica,
			'lista_oracle'=>$lista_oracle,
			'lista_ins'=>$lista_ins,
			'lista_bodegas'=>$lista_bodegas,
		));
	}

	public function actionCreate2($s)
	{
		$model=new FichaItem;
		$model->Scenario = 'create2';

		$e = 0;

		if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(3))){
			$e = 1;
		}

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$origen = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100 ORDER BY 2")->queryAll();

		$lista_origen = array();
		foreach ($origen as $ori) {
			$lista_origen[trim($ori['Id_Criterio'])] = $ori['Criterio_Descripcion'];
		}

		$tipo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 200 ORDER BY 2")->queryAll();

		$lista_tipo = array();
		foreach ($tipo as $tip) {
			$lista_tipo[trim($tip['Id_Criterio'])] = $tip['Criterio_Descripcion'];
		}

		$clasif = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 300 ORDER BY 2")->queryAll();

		$lista_clasif = array();
		foreach ($clasif as $clas) {
			$lista_clasif[trim($clas['Id_Criterio'])] = $clas['Criterio_Descripcion'];
		}

		$clase = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 400 ORDER BY 2")->queryAll();

		$lista_clase = array();
		foreach ($clase as $cl) {
			$lista_clase[trim($cl['Id_Criterio'])] = $cl['Criterio_Descripcion'];
		}

		$marca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY 2")->queryAll();

		$lista_marca = array();
		foreach ($marca as $mar) {
			$lista_marca[trim($mar['Id_Criterio'])] = $mar['Criterio_Descripcion'];
		}

		$submarca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 550 ORDER BY 2")->queryAll();

		$lista_submarca = array();
		foreach ($submarca as $subm) {
			$lista_submarca[trim($subm['Id_Criterio'])] = $subm['Criterio_Descripcion'];
		}

		$segmento = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 600 ORDER BY 2")->queryAll();

		$lista_segmento = array();
		foreach ($segmento as $seg) {
			$lista_segmento[trim($seg['Id_Criterio'])] = $seg['Criterio_Descripcion'];
		}

		$familia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 650 ORDER BY 2")->queryAll();

		$lista_familia = array();
		foreach ($familia as $fam) {
			$lista_familia[trim($fam['Id_Criterio'])] = $fam['Criterio_Descripcion'];
		}

		$linea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700 ORDER BY 2")->queryAll();

		$lista_linea = array();
		foreach ($linea as $lin) {
			$lista_linea[trim($lin['Id_Criterio'])] = $lin['Criterio_Descripcion'];
		}

		$subfamilia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 750 ORDER BY 2")->queryAll();

		$lista_subfamilia = array();
		foreach ($subfamilia as $subfam) {
			$lista_subfamilia[trim($subfam['Id_Criterio'])] = $subfam['Criterio_Descripcion'];
		}

		$sublinea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 800 ORDER BY 2")->queryAll();

		$lista_sublinea = array();
		foreach ($sublinea as $sublin) {
			$lista_sublinea[trim($sublin['Id_Criterio'])] = $sublin['Criterio_Descripcion'];
		}

		$grupo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 850 ORDER BY 2")->queryAll();

		$lista_grupo = array();
		foreach ($grupo as $gr) {
			$lista_grupo[trim($gr['Id_Criterio'])] = $gr['Criterio_Descripcion'];
		}

		$uni_neg = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 900 ORDER BY 2")->queryAll();

		$lista_un = array();
		foreach ($uni_neg as $un) {
			$lista_un[trim($un['Id_Criterio'])] = $un['Criterio_Descripcion'];
		}

		$fabrica = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 920 ORDER BY 2")->queryAll();

		$lista_fabrica = array();
		foreach ($fabrica as $fab) {
			$lista_fabrica[trim($fab['Id_Criterio'])] = $fab['Criterio_Descripcion'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY 2")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[trim($ora['Id_Criterio'])] = $ora['Criterio_Descripcion'];
		}

		

		if(isset($_POST['FichaItem']))
		{
			$model->attributes=$_POST['FichaItem'];
			$model->Estado_Solicitud = 1;

			$q = Yii::app()->db->createCommand("SELECT  I_REFERENCIA, I_DESCRIPCION FROM T_CF_ITEMS WHERE I_ID_ITEM = '".$model->Codigo_Item."' ORDER BY 2")->queryRow();

			$model->Referencia = substr($q['I_REFERENCIA'], 0, 19);
			$model->Descripcion_Corta = substr($q['I_DESCRIPCION'], 0, 39);
			$model->Pais = 1;
			$model->Step = 9;
			$model->Tipo = 2;
			$model->Id_Usuario_Solicitud = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Solicitud = date('Y-m-d H:i:s');
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');

			if($model->save())	{
				$emails_envio = Utilidadesmail::emailsfichaitem($model->Step);
				if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviofichaitem($model->Id, 1, $model->Step, $emails_envio, '');	
				}
				Yii::app()->user->setFlash('success', "Solicitud creada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('create2',array(
			'model'=>$model,
			's'=>$s,
			'e'=>$e,
			'lista_origen'=>$lista_origen,
			'lista_tipo'=>$lista_tipo,
			'lista_clasif'=>$lista_clasif,
			'lista_clase'=>$lista_clase,
			'lista_marca'=>$lista_marca,
			'lista_submarca'=>$lista_submarca,
			'lista_segmento'=>$lista_segmento,
			'lista_familia'=>$lista_familia,
			'lista_linea'=>$lista_linea,
			'lista_subfamilia'=>$lista_subfamilia,
			'lista_sublinea'=>$lista_sublinea,
			'lista_grupo'=>$lista_grupo,
			'lista_un'=>$lista_un,
			'lista_fabrica'=>$lista_fabrica,
			'lista_oracle'=>$lista_oracle,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $s)
	{
		$model=$this->loadModel($id);
		$tipo_producto_actual = $model->Tipo_Producto;
		$step_rev = $model->Step_Rev;

		//permiso editar
		$e = 0;

		switch ($s) {
		    case 2:
				$model->Scenario = 'v_desarrollo';
				if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(1))){
		        	$e = 1;
		        }		        
		        break;
		    case 3:
		        $model->Scenario = 'finanzas';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(2)) || in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(5))){
		        	$e = 1;
		        }
		        break;
		    case 4:
		        $model->Scenario  = 'v_finanzas';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(2))){
		        	$e = 1;
		        }
		        break;
		   	case 5:
		        $model->Scenario  = 'comercial';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(3)) || in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(5))){
		        	$e = 1;
		        }
		        break;
		    case 6:
		        $model->Scenario  = 'v_comercial';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(3))){
		        	$e = 1;
		        }
		        break;
		    case 7:
		        if($model->Tipo_Producto == 1 || $model->Tipo_Producto == 5){
					$model->Scenario = 'ingenieria_pt_prom';	
				}else{
					$model->Scenario = 'ingenieria';
				}
				if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(4)) || in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(5))){
		        	$e = 1;
		        }
		        break;
		    case 8:
		        if($model->Tipo_Producto == 1 || $model->Tipo_Producto == 5){
					$model->Scenario = 'v_ingenieria_pt_prom';	
				}else{
					$model->Scenario = 'v_ingenieria';	
				}
				if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(4))){
		        	$e = 1;
		        }
		        break;
		    case 9:
		        if($model->Tipo_Producto == 1 || $model->Tipo_Producto == 5){
					$model->Scenario = 'dat_maestros_pt_prom';	
				}else{
					$model->Scenario = 'dat_maestros';
				}
				if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(5))){
		        	$e = 1;
		        }
		        break;
		    case 10:
		        $model->Scenario  = 'ficha_completa'; 
		        break; 
		}

		if($s == 9 || $s == 10){

			if($model->Un_Medida != null){
				$desc_un_med = $model->DescUnidad($model->Un_Medida);
			}else{
				$desc_un_med = "";
			}

			if($model->Ep_Medida != null){
				$desc_ep_med = $model->DescUnidad($model->Ep_Medida);
			}else{
				$desc_ep_med = "";
			}

			if($model->Cad_Medida != null){
				$desc_cad_med = $model->DescUnidad($model->Cad_Medida);
			}else{
				$desc_cad_med = "";
			}

		}else{
			$desc_un_med = "";
			$desc_ep_med = "";
			$desc_cad_med = "";
		}

		$unidad = Yii::app()->db->createCommand("SELECT f101_id, f101_descripcion FROM UnoEE1..t101_mc_unidades_medida WHERE f101_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_unidad = array();
		foreach ($unidad as $uni) {
			$lista_unidad[trim($uni['f101_id'])] = $uni['f101_id'].' - '.$uni['f101_descripcion'];
		}

		$tipo_inv = Yii::app()->db->createCommand("SELECT f149_id, f149_descripcion FROM UnoEE1..t149_mc_tipo_inv_serv WHERE f149_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_tipo_inv = array();
		foreach ($tipo_inv as $ti_inv) {
			$lista_tipo_inv[trim($ti_inv['f149_id'])] = $ti_inv['f149_id'].' - '.$ti_inv['f149_descripcion'];
		}

		$grupo_imp = Yii::app()->db->createCommand("SELECT f113_id, f113_descripcion FROM UnoEE1..t113_mc_grupos_impositivos WHERE f113_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_grupo_imp = array();
		foreach ($grupo_imp as $gr_i) {
			$lista_grupo_imp[trim($gr_i['f113_id'])] = $gr_i['f113_id'].' - '.$gr_i['f113_descripcion'];
		}

		$origen = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100 ORDER BY 2")->queryAll();

		$lista_origen = array();
		foreach ($origen as $ori) {
			$lista_origen[trim($ori['Id_Criterio'])] = $ori['Id_Criterio'].' - '.$ori['Criterio_Descripcion'];
		}

		$tipo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 200 ORDER BY 2")->queryAll();

		$lista_tipo = array();
		foreach ($tipo as $tip) {
			$lista_tipo[trim($tip['Id_Criterio'])] = $tip['Id_Criterio'].' - '.$tip['Criterio_Descripcion'];
		}

		$clasif = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 300 ORDER BY 2")->queryAll();

		$lista_clasif = array();
		foreach ($clasif as $clas) {
			$lista_clasif[trim($clas['Id_Criterio'])] = $clas['Id_Criterio'].' - '.$clas['Criterio_Descripcion'];
		}

		$clase = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 400 ORDER BY 2")->queryAll();

		$lista_clase = array();
		foreach ($clase as $cl) {
			$lista_clase[trim($cl['Id_Criterio'])] = $cl['Id_Criterio'].' - '.$cl['Criterio_Descripcion'];
		}

		$marca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY 2")->queryAll();

		$lista_marca = array();
		foreach ($marca as $mar) {
			$lista_marca[trim($mar['Id_Criterio'])] = $mar['Id_Criterio'].' - '.$mar['Criterio_Descripcion'];
		}

		$submarca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 550 ORDER BY 2")->queryAll();

		$lista_submarca = array();
		foreach ($submarca as $subm) {
			$lista_submarca[trim($subm['Id_Criterio'])] = $subm['Id_Criterio'].' - '.$subm['Criterio_Descripcion'];
		}

		$segmento = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 600 ORDER BY 2")->queryAll();

		$lista_segmento = array();
		foreach ($segmento as $seg) {
			$lista_segmento[trim($seg['Id_Criterio'])] = $seg['Id_Criterio'].' - '.$seg['Criterio_Descripcion'];
		}

		$familia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 650 ORDER BY 2")->queryAll();

		$lista_familia = array();
		foreach ($familia as $fam) {
			$lista_familia[trim($fam['Id_Criterio'])] = $fam['Id_Criterio'].' - '.$fam['Criterio_Descripcion'];
		}

		$linea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700 ORDER BY 2")->queryAll();

		$lista_linea = array();
		foreach ($linea as $lin) {
			$lista_linea[trim($lin['Id_Criterio'])] = $lin['Id_Criterio'].' - '.$lin['Criterio_Descripcion'];
		}

		$subfamilia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 750 ORDER BY 2")->queryAll();

		$lista_subfamilia = array();
		foreach ($subfamilia as $subfam) {
			$lista_subfamilia[trim($subfam['Id_Criterio'])] = $subfam['Id_Criterio'].' - '.$subfam['Criterio_Descripcion'];
		}

		$sublinea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 800 ORDER BY 2")->queryAll();

		$lista_sublinea = array();
		foreach ($sublinea as $sublin) {
			$lista_sublinea[trim($sublin['Id_Criterio'])] = $sublin['Id_Criterio'].' - '.$sublin['Criterio_Descripcion'];
		}

		$grupo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 850 ORDER BY 2")->queryAll();

		$lista_grupo = array();
		foreach ($grupo as $gr) {
			$lista_grupo[trim($gr['Id_Criterio'])] = $gr['Id_Criterio'].' - '.$gr['Criterio_Descripcion'];
		}

		$uni_neg = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 900 ORDER BY 2")->queryAll();

		$lista_un = array();
		foreach ($uni_neg as $un) {
			$lista_un[trim($un['Id_Criterio'])] = $un['Id_Criterio'].' - '.$un['Criterio_Descripcion'];
		}

		$fabrica = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 920 ORDER BY 2")->queryAll();

		$lista_fabrica = array();
		foreach ($fabrica as $fab) {
			$lista_fabrica[trim($fab['Id_Criterio'])] = $fab['Id_Criterio'].' - '.$fab['Criterio_Descripcion'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY 2")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[trim($ora['Id_Criterio'])] = $ora['Id_Criterio'].' - '.$ora['Criterio_Descripcion'];
		}

		$instalaciones = Yii::app()->db->createCommand("SELECT f157_id, f157_descripcion FROM UnoEE1..t157_mc_instalaciones WHERE f157_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_ins = array();
		foreach ($instalaciones as $ins) {
			$lista_ins[trim($ins['f157_id'])] = $ins['f157_id'].' - '.$ins['f157_descripcion'];
		}

		$bodegas = Yii::app()->db->createCommand("SELECT f150_id, f150_descripcion_corta FROM UnoEE1..t150_mc_bodegas WHERE f150_id_cia = 2 ORDER BY 2")->queryAll();

		$lista_bodegas = array();
		foreach ($bodegas as $bod) {
			$lista_bodegas[trim($bod['f150_id'])] = $bod['f150_id'].' - '.$bod['f150_descripcion_corta'];
		}

		$array_pa_activos = array();
		//opciones activas en el combo paises
		$p =  explode(",", $model->Pais);
		foreach ($p as $pa => $id) {
			array_push($array_pa_activos, $id);
		}

		$paises_activos = json_encode($array_pa_activos);

		$array_inst_activas = array();
		//opciones activas en el combo perfiles
		$instalaciones =  explode(",", $model->Instalaciones);
		foreach ($instalaciones as $ins => $id) {
			array_push($array_inst_activas, $id);
		}

		$instalaciones_activas = json_encode($array_inst_activas);

		$array_bod_activas = array();
		//opciones activas en el combo perfiles
		$b =  explode(",", $model->Bodegas);
		foreach ($b as $bo => $id) {
			array_push($array_bod_activas, $id);
		}

		$bodegas_activas = json_encode($array_bod_activas);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FichaItem']))
		{
			$model->attributes=$_POST['FichaItem'];
			
			switch ($s) {
			    case 2:
			    	if($step_rev == 9){
			    		$model->Pais = implode(",", $_POST['FichaItem']['Pais']);
						if(($tipo_producto_actual != 1 && $model->Tipo_Producto == 1) || ($tipo_producto_actual != 1 && $model->Tipo_Producto == 5)){
							$model->Step = 8;
							if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
								$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
								$model->Ep_Volumen = null;
								$model->Cad_Volumen = null;
							}else{
								$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
								$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
								$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

							}
						}else{
							$model->Step = 9;
							if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
								$model->Step = 9;
								$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
								$model->Ep_Volumen = null;
								$model->Cad_Volumen = null;
							}else{
								$model->Step = 9;
								$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
								$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
								$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

							}
						}
						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Ep_Medida = null;
							$model->Ep_Cant = null;
							$model->Ep_Peso = null;
							$model->Ep_Largo = null;
							$model->Ep_Ancho = null;
							$model->Ep_Alto = null;
							$model->Ep_Volumen = null;
							$model->Cad_Medida = null;
							$model->Cad_Cant = null;
							$model->Cad_Peso = null;
							$model->Cad_Largo = null;
							$model->Cad_Ancho = null;
							$model->Cad_Alto = null;
							$model->Cad_Volumen = null;
						}

						if($model->Tipo_Producto != 1){
							$model->Presentacion = null;
							$model->Envase = null;
							$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
						}else{
							$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
						}

					}else{
						$model->Un_Volumen = null;
						$model->Ep_Volumen = null;
						$model->Cad_Volumen = null;
						$model->Step = 4;
						$model->Pais = implode(",", $_POST['FichaItem']['Pais']);
						
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Ep_Medida = null;
							$model->Cad_Medida = null;
						}

						if($model->Tipo_Producto != 1){
							$model->Presentacion = null;
							$model->Envase = null;
							$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
						}else{
							$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');	
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			    case 3:
			    	
			    	if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

			    	if($step_rev == 9){

						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');

					}else{
						$model->Un_Volumen = null;
						$model->Ep_Volumen = null;
						$model->Cad_Volumen = null;
						$model->Step = 5;
						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			    case 4:

			    	if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

			        if($step_rev == 9){
						
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');

					}else{
						$model->Step = 6;
						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						$model->Un_Volumen = null;
						$model->Ep_Volumen = null;
						$model->Cad_Volumen = null;
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			   	case 5:

			   		if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

					if($step_rev == 9){	

						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						
					}else{
						$model->Un_Volumen = null;
						$model->Ep_Volumen = null;
						$model->Cad_Volumen = null;
						$model->Step = 7;
						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			    case 6:
			        
		        	if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

			        if($step_rev == 9){
						
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						
					}else{
						$model->Un_Volumen = null;
						$model->Ep_Volumen = null;
						$model->Cad_Volumen = null;
						$model->Step = 8;
						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			    case 7:
					
			    	if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

					if($step_rev == 9){	
						
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						
					}else{
						$model->Step = 9;
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');
						}
			
						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			    case 8:
					
			    	if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

					if($step_rev == 9){	
						
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Step = 9;
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');

						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
						
					}else{
						$model->Step = 9;
						if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = null;
							$model->Cad_Volumen = null;
						}else{
							$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
							$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
							$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');
						}

						$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
						$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
					}
					$model->Step_Rev = null;
					$model->Estado_Solicitud = 1;
					$model->Observaciones = null;
			        break;
			    case 9:

			    	if($model->Tipo_Producto != 1){
						$model->Presentacion = null;
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas;
					}else{
						$model->Descripcion_Corta = $model->Nombre_Funcional." ".$model->Marca_Producto." ".$model->Caracteristicas." ".$model->Presentacion;
					}	

			    	$model->Instalaciones = implode(",", $_POST['FichaItem']['Instalaciones']);
			    	$model->Bodegas = implode(",", $_POST['FichaItem']['Bodegas']);
			        
			        if($model->Tipo_Producto != 1 && $model->Tipo_Producto != 5){
						$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
						$model->Ep_Volumen = null;
						$model->Cad_Volumen = null;
					}else{
						$model->Un_Volumen = number_format(($model->Un_Largo * $model->Un_Ancho * $model->Un_Alto) / 1000000 ,6,'.','');
						$model->Ep_Volumen = number_format(($model->Ep_Largo * $model->Ep_Ancho * $model->Ep_Alto) / 1000000 ,6,'.','');
						$model->Cad_Volumen = number_format(($model->Cad_Largo * $model->Cad_Ancho * $model->Cad_Alto) / 1000000 ,6,'.','');
					}

					$model->Step = 10;
					$model->Estado_Solicitud = 2;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
			        break;

			}

			if($model->save()){
				$emails_envio = Utilidadesmail::emailsfichaitem($model->Step);
				if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviofichaitem($model->Id, 1, $model->Step, $emails_envio, '');	
				}
				Yii::app()->user->setFlash('success', "Solicitud actualizada correctamente.");
				$this->redirect(array('admin'));	
			}
		}

		$this->render('update',array(
			'model'=>$model,
			's'=>$s,
			'e'=>$e,
			'lista_unidad'=>$lista_unidad,
			'lista_tipo_inv'=>$lista_tipo_inv,
			'lista_grupo_imp'=>$lista_grupo_imp,
			'lista_origen'=>$lista_origen,
			'lista_tipo'=>$lista_tipo,
			'lista_clasif'=>$lista_clasif,
			'lista_clase'=>$lista_clase,
			'lista_marca'=>$lista_marca,
			'lista_submarca'=>$lista_submarca,
			'lista_segmento'=>$lista_segmento,
			'lista_familia'=>$lista_familia,
			'lista_linea'=>$lista_linea,
			'lista_subfamilia'=>$lista_subfamilia,
			'lista_sublinea'=>$lista_sublinea,
			'lista_grupo'=>$lista_grupo,
			'lista_un'=>$lista_un,
			'lista_fabrica'=>$lista_fabrica,
			'lista_oracle'=>$lista_oracle,
			'lista_ins'=>$lista_ins,
			'lista_bodegas'=>$lista_bodegas,
			'paises_activos'=>$paises_activos,
			'instalaciones_activas'=>$instalaciones_activas,
			'bodegas_activas'=>$bodegas_activas,
			'desc_un_med'=>$desc_un_med,
			'desc_ep_med'=>$desc_ep_med,
			'desc_cad_med'=>$desc_cad_med,
		));
	}

	public function actionUpdate2($id, $s)
	{
		$model=$this->loadModel($id);
		$model->Scenario = 'update';

		//permiso editar
		$e = 0;

		switch ($s) {
		    case 6:
		        $model->Scenario  = 'v_comercial_act';
		        if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(3))){
		        	$e = 1;
		        }
		        break;
		    case 9:
				$model->Scenario = 'dat_maestros_act';
				if(in_array(Yii::app()->user->getState('id_user'), UtilidadesVarias::usuariosfichaitem(5))){
		        	$e = 1;
		        }
		        break;
		    case 10:
		        $model->Scenario  = 'ficha_completa'; 
		        break; 
		}

		$origen = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 100 ORDER BY 2")->queryAll();

		$lista_origen = array();
		foreach ($origen as $ori) {
			$lista_origen[trim($ori['Id_Criterio'])] = $ori['Criterio_Descripcion'];
		}

		$tipo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 200 ORDER BY 2")->queryAll();

		$lista_tipo = array();
		foreach ($tipo as $tip) {
			$lista_tipo[trim($tip['Id_Criterio'])] = $tip['Criterio_Descripcion'];
		}

		$clasif = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 300 ORDER BY 2")->queryAll();

		$lista_clasif = array();
		foreach ($clasif as $clas) {
			$lista_clasif[trim($clas['Id_Criterio'])] = $clas['Criterio_Descripcion'];
		}

		$clase = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 400 ORDER BY 2")->queryAll();

		$lista_clase = array();
		foreach ($clase as $cl) {
			$lista_clase[trim($cl['Id_Criterio'])] = $cl['Criterio_Descripcion'];
		}

		$marca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY 2")->queryAll();

		$lista_marca = array();
		foreach ($marca as $mar) {
			$lista_marca[trim($mar['Id_Criterio'])] = $mar['Criterio_Descripcion'];
		}

		$submarca = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 550 ORDER BY 2")->queryAll();

		$lista_submarca = array();
		foreach ($submarca as $subm) {
			$lista_submarca[trim($subm['Id_Criterio'])] = $subm['Criterio_Descripcion'];
		}

		$segmento = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 600 ORDER BY 2")->queryAll();

		$lista_segmento = array();
		foreach ($segmento as $seg) {
			$lista_segmento[trim($seg['Id_Criterio'])] = $seg['Criterio_Descripcion'];
		}

		$familia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 650 ORDER BY 2")->queryAll();

		$lista_familia = array();
		foreach ($familia as $fam) {
			$lista_familia[trim($fam['Id_Criterio'])] = $fam['Criterio_Descripcion'];
		}

		$linea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700 ORDER BY 2")->queryAll();

		$lista_linea = array();
		foreach ($linea as $lin) {
			$lista_linea[trim($lin['Id_Criterio'])] = $lin['Criterio_Descripcion'];
		}

		$subfamilia = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 750 ORDER BY 2")->queryAll();

		$lista_subfamilia = array();
		foreach ($subfamilia as $subfam) {
			$lista_subfamilia[trim($subfam['Id_Criterio'])] = $subfam['Criterio_Descripcion'];
		}

		$sublinea = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 800 ORDER BY 2")->queryAll();

		$lista_sublinea = array();
		foreach ($sublinea as $sublin) {
			$lista_sublinea[trim($sublin['Id_Criterio'])] = $sublin['Criterio_Descripcion'];
		}

		$grupo = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 850 ORDER BY 2")->queryAll();

		$lista_grupo = array();
		foreach ($grupo as $gr) {
			$lista_grupo[trim($gr['Id_Criterio'])] = $gr['Criterio_Descripcion'];
		}

		$uni_neg = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 900 ORDER BY 2")->queryAll();

		$lista_un = array();
		foreach ($uni_neg as $un) {
			$lista_un[trim($un['Id_Criterio'])] = $un['Criterio_Descripcion'];
		}

		$fabrica = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 920 ORDER BY 2")->queryAll();

		$lista_fabrica = array();
		foreach ($fabrica as $fab) {
			$lista_fabrica[trim($fab['Id_Criterio'])] = $fab['Criterio_Descripcion'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY 2")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[trim($ora['Id_Criterio'])] = $ora['Criterio_Descripcion'];
		}

		if(isset($_POST['FichaItem']))
		{
			$model->attributes=$_POST['FichaItem'];

			switch ($s) {
			    case 6:
					$model->Step = 9;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
					$model->Observaciones = null;
			        break;
			    case 9:
			    	$model->Estado_Solicitud = 2;
					$model->Step = 10;
					$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
					$model->Observaciones = null;
			        break;

			}
						
			if($model->save()){
				$emails_envio = Utilidadesmail::emailsfichaitem($model->Step);
			    if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviofichaitem($model->Id, 1, $model->Step, $emails_envio, '');	
				}
				Yii::app()->user->setFlash('success', "Solicitud actualizada correctamente.");
				$this->redirect(array('admin'));	
			}
		}

		$this->render('update2',array(
			'model'=>$model,
			's'=>$s,
			'e'=>$e,
			'lista_origen'=>$lista_origen,
			'lista_tipo'=>$lista_tipo,
			'lista_clasif'=>$lista_clasif,
			'lista_clase'=>$lista_clase,
			'lista_marca'=>$lista_marca,
			'lista_submarca'=>$lista_submarca,
			'lista_segmento'=>$lista_segmento,
			'lista_familia'=>$lista_familia,
			'lista_linea'=>$lista_linea,
			'lista_subfamilia'=>$lista_subfamilia,
			'lista_sublinea'=>$lista_sublinea,
			'lista_grupo'=>$lista_grupo,
			'lista_un'=>$lista_un,
			'lista_fabrica'=>$lista_fabrica,
			'lista_oracle'=>$lista_oracle,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FichaItem('search');
		$usuarios=Usuario::model()->findAll(array('order'=>'Usuario'));

		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FichaItem']))
			$model->attributes=$_GET['FichaItem'];

		$this->render('admin',array(
			'model'=>$model,
			'usuarios'=>$usuarios,

		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FichaItem the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FichaItem::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FichaItem $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ficha-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSearchItem(){
		$filtro = $_GET['q'];
        $data = FichaItem::model()->searchByItem($filtro);
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
        $data = FichaItem::model()->searchById($filtro);

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

 	public function actionGetInfoItem()
	{
		$codigo = $_POST['codigo'];

		$query ="
		  SET NOCOUNT ON
		  EXEC P_CF_CONF_W_CREACION_IT
		  @ITEM = N'".$codigo."'
		";

		$info = Yii::app()->db->createCommand($query)->queryRow();	
		 
		$tiempo_repocision = intval($info['TIEMPO_REPO']);
		$cant_moq = intval($info['CANT_MOQ']);
		$stock_minimo = intval($info['STOCK_MINIMO']);
		$origen = trim($info['ORIGEN']);
		$tipo = trim($info['TIPO']);
		$clasif = trim($info['CLASIFICACION']); 
		$clase = trim($info['CLASE']); 
		$marca = trim($info['MARCA']);
		$submarca = trim($info['SUBMARCA']);
		$segmento = trim($info['SEGMENTO']);
		$familia = trim($info['FAMILIA']);
		$subfamilia = trim($info['SUBFAMILIA']); 
		$linea = trim($info['LINEA']);
		$sublinea = trim($info['SUB-LINEA']); 
		$grupo = trim($info['GRUPO']); 
		$unidad_negocio = trim($info['UNIDAD NEGOCIO']);
		$fabrica = trim($info['FABRICA_TERCERO']);  
		$cat_oracle = trim($info['CATEGORIA ORACLE']);
		
		
		$resp = array('tiempo_repocision' => $tiempo_repocision, 'cant_moq' => $cant_moq, 'stock_minimo' => $stock_minimo, 'origen' => $origen, 'tipo' => $tipo, 'clasif' => $clasif, 'clase' => $clase, 'marca' => $marca, 'submarca' => $submarca, 'segmento' => $segmento, 'familia' => $familia, 'subfamilia' => $subfamilia, 'linea' => $linea, 'sublinea' => $sublinea, 'grupo' => $grupo, 'unidad_negocio' => $unidad_negocio, 'fabrica' => $fabrica, 'cat_oracle' => $cat_oracle);

        echo json_encode($resp);	
	}

	public function actionNotas($id)
	{
		$model = $this->loadModel($id);
		$step_rev = $model->Step;
		$model->Scenario = 'notas';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FichaItem']))
		{
			$model->attributes=$_POST['FichaItem'];
			$model->Estado_Solicitud = 0;
			$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Actualizacion = date('Y-m-d H:i:s');
			if($model->Tipo == 2){
				$model->Step = 6;
			}else{
				$model->Step_Rev = $step_rev;
			}
		
			if($model->save())	{
				$emails_envio = Utilidadesmail::emailsfichaitem($model->Step);
			    if(!empty($emails_envio)){
					$resp = UtilidadesMail::enviofichaitem($id, 0, $model->Step, $emails_envio, $model->Observaciones);	
				}
				$model->Observaciones = null;
				$model->update();
				Yii::app()->user->setFlash('success', "Solicitud actualizada correctamente.");
				$this->redirect(array('admin'));
			}
		}

		$this->render('notas',array(
			'model'=>$model,
			't'=>$model->Tipo,
		));
	}

	public function actionRedirect($id)
	{
		$model=$this->loadModel($id);

		if($model->Tipo == 1){
			$this->redirect(array('fichaItem/update','id'=>$id, 's'=>$model->Step));	
		}else{
			$this->redirect(array('fichaItem/update2','id'=>$id, 's'=>$model->Step));		
		}

	}

}

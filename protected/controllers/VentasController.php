<?php

class VentasController extends Controller
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
				'actions'=>array('pedidospenddesreqtop','naf','fleteguia','uploadfleteguia','registroguia','uploadguia','seguimientodoc','uploadseguimientodoc','analisisventas','ventasperiodoprom','ventaempleado','feeterpelcons','feeterpeldet','segrutasmarcacoord','histlibped','consultafactelect','consultafactelectpant','seguimientorutas','clientexfecha','diferenciasrutas','diferenciasrutaspant','listap','vendedores','vendedorespant','pedidospenddesreqlinea','pedidospenddesreqmarca','nivelserviciopedidoxmarca','nivelserviciopedidoxev','costoxitempos','venposentr','venposentrpant','venposfalt','venposfaltpant','rentiteml560','rentitem','rentcriterios560','rentcriterios','rentxcliente560','rentxcliente','rentxestructura560','revisioncomercial','rentinvlinea','rentinvmarca','rentmarcaiteml560','rentinvoracle','clientescrmsiesa','docsclientespotenciales','pqrsdetalle','clientespot','consolidadoun'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionPedidosPendDesReqTop()
	{		
		$model=new Ventas;
		$model->scenario = 'pedidos_pend_des_req_top';

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('pedidos_pend_des_req_top_resp',array('model' => $model));	
		}

		$this->render('pedidos_pend_des_req_top',array(
			'model'=>$model,
		));
	}

	public function actionNaf()
	{		
		$model=new Ventas;
		$model->scenario = 'naf';

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('naf_resp',array('model' => $_POST['Ventas']));
		}

		$this->render('naf',array(
			'model'=>$model,
		));
	}

	public function actionFleteGuia()
	{		
		$model=new Ventas;

		$this->render('flete_guia',array(
			'model'=>$model,
		));
	}

	public function actionUploadFleteGuia()
	{		

		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Ventas']['tmp_name']['archivo'];

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

    		for($i = 1; $i <= $filas -1 ; $i++){
        		$param1 = $dataExcel[$i][0];
        		$param2 = $dataExcel[$i][1];
        		$param3 = $dataExcel[$i][2];
        		$param4 = $dataExcel[$i][3];

        		if($param1 == '' || $param2 == '' || $param3 == '' || $param4 == ''){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias.<br>'; 
        			$valid = 0;
        		}else{

        			//se valida si el documento existe

        			$co    = $param1;
					$td    = $param2;
					$cons  = $param3;

        			$query_exist_doc = "SELECT DISTINCT f350_rowid FROM UnoEE1..t350_co_docto_contable WHERE f350_id_co = '".$co."' AND f350_id_tipo_docto = '".$td."' AND f350_consec_docto = ".$cons."";

    				$row_exist_doc =  Yii::app()->db->createCommand($query_exist_doc)->queryRow();

					$doc = $row_exist_doc['f350_rowid'];

					if(is_null($doc)){
						$fila_error = $i + 1;
						$msj .= 'Error en la fila # '.$fila_error.', el documento no existe.<br>'; 
					}else{

						$co    = $param1;
						$td    = $param2;
						$cons  = $param3;

					 	$connection = Yii::app()->db;
						$command = $connection->createCommand("
							EXEC P_CF_CONF_ACT_VLR_GUIA
							@CO = N'".$param1."',
							@DOCTO = N'".$param2."',
							@CONSECUTIVO = N'".$param3."',
							@VLR_FLETE = N'".$param4."'
						");

						$command->execute();

						$cont = $cont + 1;

        			}		        		
        		}
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	$msj .= $f.' Registro(s) ejecutado(s) correctamente.<br>'; 	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);
		
	}

	public function actionRegistroGuia()
	{		
		$model=new Ventas;

		$this->render('registro_guia',array(
			'model'=>$model,
		));
	}

	public function actionUploadGuia()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Ventas']['tmp_name']['archivo'];

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

    		for($i = 1; $i <= $filas -1 ; $i++){
        		$param1 = $dataExcel[$i][0];
        		$param2 = $dataExcel[$i][1];
        		$param3 = $dataExcel[$i][2];
        		$param4 = $dataExcel[$i][3];
        		$param5 = $dataExcel[$i][4];
        		$param6 = $dataExcel[$i][5];
        		$param7 = $dataExcel[$i][6];
        		$param8 = $dataExcel[$i][7];
        		$param9 = $dataExcel[$i][8];

        		if($param1 == '' || $param2 == '' || $param3 == '' || $param4 == '' || $param5 == '' || $param6 == '' || $param7 == '' || $param8 == '' || $param9 == ''){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias.<br>'; 
        			$valid = 0;
        		}else{

        			//se valida si el documento existe

        			$co    = $param1;
					$td    = $param2;
					$cons  = $param3;

        			$query_exist_doc = "SELECT DISTINCT f350_rowid FROM UnoEE1..t350_co_docto_contable WHERE f350_id_co = '".$co."' AND f350_id_tipo_docto = '".$td."' AND f350_consec_docto = ".$cons."";

    				$row_exist_doc =  Yii::app()->db->createCommand($query_exist_doc)->queryRow();

					$doc = $row_exist_doc['f350_rowid'];

					if(is_null($doc)){
						$fila_error = $i + 1;
						$msj .= 'Error en la fila # '.$fila_error.', el documento no existe.<br>'; 
					}else{

						//se valida si el documento tiene ruta asignada

						$co    = $param1;
						$td    = $param2;
						$cons  = $param3;

						$query_exist_guia = "SELECT DISTINCT f462_id_vehiculo FROM UnoEE1..t350_co_docto_contable INNER JOIN UnoEE1..t462_cm_docto_transportador ON f462_rowid_docto = f350_rowid WHERE f350_id_co = '".$co."' AND f350_id_tipo_docto = '".$td."' AND f350_consec_docto = ".$cons."";
						
						$row_exist_guia =  Yii::app()->db->createCommand($query_exist_guia)->queryRow();

						$GUIA = $row_exist_guia['f462_id_vehiculo'];

						if(!is_null($GUIA)){
							$fila_error = $i + 1;
							$msj .= 'Error en la fila # '.$fila_error.', el documento ya tiene guía asignada.<br>'; 
						}else{

							//se valida si la placa a asignar existe
							$placa = $param4;

							$query_placa = "SELECT f163_id FROM UnoEE1..t163_mc_vehiculos WHERE f163_id_cia = 2 AND LTRIM(RTRIM([f163_id])) = '".$placa."'";
							$row_placa =  Yii::app()->db->createCommand($query_placa)->queryRow();

							$placa = $row_placa['f163_id'];

							if(is_null($placa)){
								$fila_error = $i + 1;
								$msj .= 'Error en la fila # '.$fila_error.', la placa no existe.<br>'; 
							}else{

							 	$connection = Yii::app()->db;
								$command = $connection->createCommand("
									EXEC P_CF_CONF_GUIA_DESPACHO
									@CO = N'".$param1."',
									@DOCTO = N'".$param2."',
									@CONSECUTIVO = N'".$param3."',
									@PLACA = N'".$param4."',
									@CONDUCTOR = N'".$param5."',
									@NIT_CONDUCTOR = N'".$param6."',
									@GUIA = N'".$param7."',
									@NOTAS = N'".$param8."',
									@VLR_FLETE = N'".$param9."'
								");

								$command->execute();

								$cont = $cont + 1;

							}	
						}
					}
        		}		        		
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	$msj .= $f.' Registro(s) ejecutado(s) correctamente.<br>'; 	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);
	}

	public function actionSeguimientoDoc()
	{		
		$model=new Ventas;

		$this->render('seguimiento_doc',array(
			'model'=>$model,
		));
	}

	public function actionUploadSeguimientoDoc()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Ventas']['tmp_name']['archivo'];

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

    		for($i = 1; $i <= $filas -1 ; $i++){
        		$param1 = $dataExcel[$i][0];
        		$param2 = $dataExcel[$i][1];
        		$param3 = $dataExcel[$i][2];
        		$param4 = $dataExcel[$i][3];
        		$param5 = $dataExcel[$i][4];

        		if($param1 == '' || $param2 == '' || $param3 == '' || $param4 == '' || $param5 == ''){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias.<br>'; 
        			$valid = 0;
        		}else{

        			//se valida si el documento existe

        			$co    = $param1;
					$td    = $param2;
					$cons  = $param3;

        			$query_exist_doc = "SELECT DISTINCT f350_rowid FROM UnoEE1..t350_co_docto_contable WHERE f350_id_co = '".$co."' AND f350_id_tipo_docto = '".$td."' AND f350_consec_docto = ".$cons."";

    				$row_exist_doc =  Yii::app()->db->createCommand($query_exist_doc)->queryRow();

					$doc = $row_exist_doc['f350_rowid'];

					if(is_null($doc)){
						$fila_error = $i + 1;
						$msj .= 'Error en la fila # '.$fila_error.', el documento no existe.<br>'; 
					}else{

					 	$connection = Yii::app()->db;
						$command = $connection->createCommand("
							EXEC P_PR_CONF_SEG_DESPACHO
							@CO = N'".$param1."',
							@DOCTO = N'".$param2."',
							@CONSECUTIVO = N'".$param3."',
							@NOTAS = N'".$param4."',
							@FECHA = N'".$param5."'
						");
						$command->execute();
						
						$cont = $cont + 1;
							
					}
        		}		        		
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	$msj .= $f.' Registro(s) ejecutado(s) correctamente.<br>'; 	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);
	}


	public function actionAnalisisVentas()
	{		
		$model=new Ventas;
		$model->scenario = 'analisis_ventas';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY DESCRIPCION")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[$ora['DESCRIPCION']] = $ora['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('analisis_ventas_resp',array('model' => $_POST['Ventas']));
		}

		$this->render('analisis_ventas',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
			'lista_lineas'=>$lista_lineas,
			'lista_oracle'=>$lista_oracle, 
		));
	}

	public function actionVentasPeriodoProm()
	{		
		$model=new Ventas;
		$model->scenario = 'ventas_periodo_prom';

		//$m_marcas=Marca::model()->findAll(array('order'=>'M_Descripcion'));

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('ventas_periodo_prom_resp',array('model' => $model));	
		}

		$this->render('ventas_periodo_prom',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
		));
	}

	public function actionVentaEmpleado()
	{		
		$model=new Ventas;
		$model->scenario = 'venta_empleado';

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('venta_empleado_resp',array('model' => $_POST['Ventas']));
		}

		$this->render('venta_empleado',array(
			'model'=>$model,
		));
	}

	public function actionFeeTerpelCons()
	{		
		$model=new Ventas;
		$model->scenario = 'fee_terpel_cons';

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('fee_terpel_cons_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('fee_terpel_cons',array(
			'model'=>$model,
		));
	}

	public function actionFeeTerpelDet()
	{		
		$model=new Ventas;
		$model->scenario = 'fee_terpel_det';

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('fee_terpel_det_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('fee_terpel_det',array(
			'model'=>$model,
		));
	}

	public function actionSegRutasMarcaCoord()
	{		
		$model=new Ventas;
		$model->scenario = 'seg_rutas_marca_coord';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$coordinadores = Yii::app()->db->createCommand("
			SELECT DISTINCT f200_id ,f200_razon_social FROM UnoEE1..t210_mm_vendedores 
			INNER JOIN UnoEE1..t200_mm_terceros ON f210_rowid_terc_supervisor = f200_rowid AND f200_ind_estado = 1
			WHERE f210_id_cia = 2 AND f200_id not IN (111157,111140,111141,111143,111149,111150,111151,111152,111153)
			ORDER BY 2"
		)->queryAll();

		$lista_coord = array();
		foreach ($coordinadores as $coord) {
			$lista_coord[$coord['f200_id']] = $coord['f200_razon_social'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('seg_rutas_marca_coord_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('seg_rutas_marca_coord',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
			'lista_coord'=>$lista_coord,
		));
	}

	public function actionHistLibPed()
	{		
		$model=new Ventas;
		$model->scenario = 'hist_lib_ped';

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('hist_lib_ped_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('hist_lib_ped',array(
			'model'=>$model,
		));
	}

	public function actionConsultaFactElect()
	{		
		$model=new Ventas;
		$model->scenario = 'consulta_fact_elect';

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('fact_elect_resp',array('model' => $model));	
		}

		$this->render('fact_elect',array(
			'model'=>$model,
		));
	}

	public function actionConsultaFactElectPant()
	{		
		
		$tipo = $_POST['tipo'];
		$cons_inicial = $_POST['cons_inicial'];
		$cons_final = $_POST['cons_final'];

		$resultados = UtilidadesReportes::consultafactelectpantalla($tipo, $cons_inicial, $cons_final);
		echo $resultados;
	}

	public function actionSeguimientoRutas()
	{		
		$model=new Ventas;
		$model->scenario = 'seguimiento_rutas';

		$rutas = Yii::app()->db->createCommand("SELECT DISTINCT f5790_id, f5790_descripcion FROM UnoEE1..t5790_sm_ruta WHERE f5790_id_cia = 2")->queryAll();
 
		$lista_rutas = array();
		foreach ($rutas as $rut) {
			$lista_rutas[$rut['f5790_id']] = $rut['f5790_descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('seguimiento_rutas_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('seguimiento_rutas',array(
			'model'=>$model,
			'lista_rutas'=>$lista_rutas,
		));
	}

	public function actionClientexFecha()
	{		
		$model=new Ventas;
		$model->scenario = 'cliente_x_fecha';

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('cliente_x_fecha_resp',array('model' => $model));	
		}

		$this->render('cliente_x_fecha',array(
			'model'=>$model,
		));
	}

	public function actionDiferenciasRutas()
	{		
		$model=new Ventas;
		$model->scenario = 'diferencias_rutas';

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('diferencias_rutas_resp');	
		}

		$this->render('diferencias_rutas',array(
			'model'=>$model,
		));
	}

	public function actionDiferenciasRutasPant()
	{		

		$resultados = UtilidadesReportes::diferenciasrutaspantalla();

		echo $resultados;
	}

	public function actionListaP()
	{		
		
		$model=new Ventas;

		$model->scenario = 'lista_p';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY DESCRIPCION")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[$ora['DESCRIPCION']] = $ora['DESCRIPCION'];
		}

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		$lps = Yii::app()->db->createCommand("SELECT DISTINCT f112_id, f112_descripcion FROM UnoEE1..t112_mc_listas_precios")->queryAll();

		$lista_pr = array();
		foreach ($lps as $lp) {
			$lista_pr[$lp['f112_id']] = $lp['f112_descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('lista_p_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('lista_p',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
			'lista_marcas'=>$lista_marcas,
			'lista_pr'=>$lista_pr,
		));
		
	}

	public function actionVendedores()
	{		
		$model=new Ventas;
		$model->scenario = 'vendedores';

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('vendedores_resp');	
		}

		$this->render('vendedores',array(
			'model'=>$model,
		));
	}

	public function actionVendedoresPant()
	{		

		$resultados = UtilidadesReportes::vendedorespantalla();

		echo $resultados;
	}

	public function actionPedidosPendDesReqLinea()
	{		
		$model=new Ventas;
		$model->scenario = 'pedidos_pend_des_req_linea';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('pedidos_pend_des_req_linea_resp',array('model' => $model));	
		}

		$this->render('pedidos_pend_des_req_linea',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
		));
	}

	public function actionPedidosPendDesReqMarca()
	{		
		$model=new Ventas;
		$model->scenario = 'pedidos_pend_des_req_marca';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('pedidos_pend_des_req_marca_resp',array('model' => $model));	
		}

		$this->render('pedidos_pend_des_req_marca',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
		));
	}

	public function actionNivelServicioPedidoXmarca()
	{		
		$model=new Ventas;
		$model->scenario = 'nivel_servicio_pedido_x_marca';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('nivel_servicio_pedido_x_marca_resp',array('model' => $model));	
		}

		$this->render('nivel_servicio_pedido_x_marca',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
		));
	}

	public function actionNivelServicioPedidoXev()
	{		
		$model=new Ventas;
		$model->scenario = 'nivel_servicio_pedido_x_ev';

		$model_ev = Yii::app()->db->createCommand("SELECT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan=300 ORDER BY DESCRIPCION")->queryAll();

		$lista_ev = array();
		foreach ($model_ev as $m_ev) {
			$lista_ev[$m_ev['DESCRIPCION']] = $m_ev['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('nivel_servicio_pedido_x_ev_resp',array('model' => $model));	
		}

		$this->render('nivel_servicio_pedido_x_ev',array(
			'model'=>$model,
			'lista_ev'=>$lista_ev,
		));
	}

	public function actionCostoXItemPos()
	{		
		$model=new Ventas;
		$model->scenario = 'costo_x_item_pos';

		$this->renderPartial('costo_x_item_pos_resp');
	}

	public function actionVenPosEntr()
	{		
		$model=new Ventas;
		$model->scenario = 'ven_pos_entr';

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('ven_pos_entr_resp',array('model' => $model));	
		}

		$this->render('ven_pos_entr',array(
			'model'=>$model,
		));
	}

	public function actionVenPosEntrPant()
	{		
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];

		$resultados = UtilidadesReportes::venposentrpantalla($fecha_inicial, $fecha_final);

		echo $resultados;
	}

	public function actionVenPosFalt()
	{		
		$model=new Ventas;
		$model->scenario = 'ven_pos_falt';

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('ven_pos_falt_resp',array('model' => $model));	
		}

		$this->render('ven_pos_falt',array(
			'model'=>$model,
		));
	}

	public function actionVenPosFaltPant()
	{		
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];

		$resultados = UtilidadesReportes::venposfaltpantalla($fecha_inicial, $fecha_final);

		echo $resultados;
	}

	public function actionRentItemL560()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_item_l560';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[$ora['Id_Criterio']] = $ora['Criterio_Descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_item_l560_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_item_l560',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
		));
	}

	public function actionRentItem()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_item';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $ora) {
			$lista_oracle[$ora['Id_Criterio']] = $ora['Criterio_Descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_item_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_item',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
		));
	}

	public function actionRentCriterios560()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_criterios_560';

		$clases = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 100 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_clases = array();
		foreach ($clases as $cla) {
			$lista_clases[$cla['Id_Criterio']] = $cla['Criterio_Descripcion'];
		}

		$canales = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 200 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_canales = array();
		foreach ($canales as $can) {
			$lista_canales[$can['Id_Criterio']] = $can['Criterio_Descripcion'];
		}

		$regionales = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 600 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_regionales = array();
		foreach ($regionales as $re) {
			$lista_regionales[$re['Id_Criterio']] = $re['Criterio_Descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_criterios_560_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_criterios_560',array(
			'model'=>$model,
			'lista_clases'=>$lista_clases,
			'lista_canales'=>$lista_canales,
			'lista_regionales'=>$lista_regionales,
		));
	}

	public function actionRentCriterios()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_criterios';

		$clases = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 100 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_clases = array();
		foreach ($clases as $cla) {
			$lista_clases[$cla['Id_Criterio']] = $cla['Criterio_Descripcion'];
		}

		$canales = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 200 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_canales = array();
		foreach ($canales as $can) {
			$lista_canales[$can['Id_Criterio']] = $can['Criterio_Descripcion'];
		}

		$regionales = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 600 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_regionales = array();
		foreach ($regionales as $re) {
			$lista_regionales[$re['Id_Criterio']] = $re['Criterio_Descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_criterios_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_criterios',array(
			'model'=>$model,
			'lista_clases'=>$lista_clases,
			'lista_canales'=>$lista_canales,
			'lista_regionales'=>$lista_regionales,
		));
	}

	public function actionRentxCliente560()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_x_cliente_560';

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('rent_x_cliente_560_resp',array('model' => $model));	
		}

		$this->render('rent_x_cliente_560',array(
			'model'=>$model,
		));
	}

	public function actionRentxCliente()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_x_cliente';

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('rent_x_cliente_resp',array('model' => $model));	
		}

		$this->render('rent_x_cliente',array(
			'model'=>$model,
		));
	}

	public function actionRentxEstructura560()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_x_estructura_560';

		$model_ev = Yii::app()->db->createCommand("SELECT Id_Criterio, Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 ORDER BY Criterio_Descripcion")->queryAll();

		$lista_ev = array();
		foreach ($model_ev as $m_ev) {
			$lista_ev[$m_ev['Id_Criterio']] = $m_ev['Criterio_Descripcion'];
		}

		if(isset($_POST['Ventas']))
		{
			$model=$_POST['Ventas'];
			$this->renderPartial('rent_x_estructura_560_resp',array('model' => $model));	
		}

		$this->render('rent_x_estructura_560',array(
			'model'=>$model,
			'lista_ev' => $lista_ev,
		));
	}

	public function actionRevisionComercial()
	{		
		$model=new Ventas;
		$model->scenario = 'revision_comercial';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('revision_comercial_resp',array('model' => $model));	
		}

		$this->render('revision_comercial',array(
			'model'=>$model,
			'lista_marcas' => $lista_marcas,		
		));
	}

	public function actionRentInvLinea()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_inv_linea';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 700 ORDER BY DESCRIPCION")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_inv_linea_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_inv_linea',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
		));
	}

	public function actionRentInvMarca()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_inv_marca';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_inv_marca_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_inv_marca',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
		));
	}

	public function actionRentMarcaItemL560()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_marca_item_l560';

		$marcas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 500 ORDER BY DESCRIPCION")->queryAll();

		$lista_marcas = array();
		foreach ($marcas as $ma) {
			$lista_marcas[$ma['DESCRIPCION']] = $ma['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_marca_item_l560_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_marca_item_l560',array(
			'model'=>$model,
			'lista_marcas'=>$lista_marcas,
		));
	}

	public function actionRentInvOracle()
	{		
		$model=new Ventas;
		$model->scenario = 'rent_inv_oracle';

		$oracle = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = 950 ORDER BY DESCRIPCION")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $or) {
			$lista_oracle[$or['DESCRIPCION']] = $or['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('rent_inv_oracle_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('rent_inv_oracle',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,
		));
	}

	public function actionClientesCrmSiesa()
	{		
		$model=new Ventas;
		$model->scenario = 'clientes_crm_siesa';

		$this->renderPartial('clientes_crm_siesa_resp');
	}

	public function actionDocsClientesPotenciales()
	{		
		$model=new Ventas;
		$model->scenario = 'docs_clientes_potenciales';

		$this->renderPartial('docs_clientes_potenciales_resp');
	}

	public function actionPqrsDetalle()
	{		
		$model=new Ventas;
		$model->scenario = 'pqrs_detalle';

		$this->renderPartial('pqrs_detalle_resp');
	}

	public function actionClientesPot()
	{		
		$model=new Ventas;
		$model->scenario = 'clientes_pot';

		if(isset($_POST['Ventas']))
		{
			$this->renderPartial('clientes_pot_resp',array('model' => $_POST['Ventas']));	
		}

		$this->render('clientes_pot',array(
			'model'=>$model,
		));
	}

	public function actionConsolidadoUn()
	{		
		$model=new Ventas;
		$model->scenario = 'consolidado_un';

		$un = Yii::app()->db->createCommand("SELECT DISTINCT Id_Criterio, Criterio_Descripcion AS DESCRIPCION FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = 300 ORDER BY DESCRIPCION")->queryAll();

		$lista_un = array();
		foreach ($un as $lun) {
			$lista_un[$lun['Id_Criterio']] = $lun['DESCRIPCION'];
		}

		if(isset($_POST['Ventas']))
		{
			$model->attributes=$_POST['Ventas'];
			$this->renderPartial('consolidado_un_resp',array('model' => $model));	
		}

		$this->render('consolidado_un',array(
			'model'=>$model,
			'lista_un'=>$lista_un,		
		));
	}
	
	
}

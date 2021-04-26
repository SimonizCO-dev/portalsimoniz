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
				'actions'=>array('rentmarca','rentmarcaitem','rentcliente','nivelserviciomarca','rentoracle', 'rentoracleitem','searchclientecart','searchclientecartnit','nivelserviciolinea','actualizarbod','actualizarreca','fotocart','facttiendasweb','desptiendasweb','dettranstiendasweb','uploaddettranstiendasweb', 'remisiontugo','elimerrortrans','errortransf','errortransfpant','logcrossdocking','compinc','compincpant'),
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
















































	









	public function actionRentMarca()
	{		
		$model=new Reporte;
		$model->scenario = 'rent_marca';

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('rent_marca_resp',array('model' => $model));	
		}

		$this->render('rent_marca',array(
			'model'=>$model,		
		));
	}

	public function actionRentMarcaItem()
	{		
		$model=new Reporte;
		$model->scenario = 'rent_marca_item';

		$m_marcas=Marca::model()->findAll(array('order'=>'M_Descripcion'));

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('rent_marca_item_resp',array('model' => $model));	
		}

		$this->render('rent_marca_item',array(
			'model'=>$model,
			'marcas'=>$m_marcas,
		));
	}

	public function actionRentCliente()
	{		
		$model=new Reporte;
		$model->scenario = 'rent_cliente';


		$clases = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM TH_CRITERIOS_CLIENTES WHERE Id_Plan = ".Yii::app()->params->clases." ORDER BY Criterio_Descripcion")->queryAll();

		$lista_clases = array();
		foreach ($clases as $cla) {
			$lista_clases[$cla['Id_Criterio']] = $cla['Criterio_Descripcion'];
		}

		$canales = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM TH_CRITERIOS_CLIENTES WHERE Id_Plan = ".Yii::app()->params->canales." ORDER BY Criterio_Descripcion")->queryAll();

		$lista_canales = array();
		foreach ($canales as $can) {
			$lista_canales[$can['Id_Criterio']] = $can['Criterio_Descripcion'];
		}

		$evs = Yii::app()->db->createCommand("SELECT Id_Plan, Plan_Descripcion, Id_Criterio, Criterio_Descripcion FROM TH_CRITERIOS_CLIENTES WHERE Id_Plan = ".Yii::app()->params->evs." ORDER BY Criterio_Descripcion")->queryAll();

		$lista_evs = array();
		foreach ($evs as $ev) {
			$lista_evs[$ev['Id_Criterio']] = $ev['Criterio_Descripcion'];
		}

		if(isset($_POST['Reporte']))
		{
			$this->renderPartial('rent_cliente_resp',array('model' => $_POST['Reporte']));	
		}

		$this->render('rent_cliente',array(
			'model'=>$model,
			//'clases'=>$m_clases,
			'lista_clases'=>$lista_clases,
			'lista_canales'=>$lista_canales,
			'lista_evs'=>$lista_evs,
		));
	}

	public function actionNivelServicioMarca()
	{		
		$model=new Reporte;
		$model->scenario = 'nivel_servicio_marca';

		$m_marcas=Marca::model()->findAll(array('order'=>'M_Descripcion'));

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('nivel_servicio_marca_resp',array('model' => $model));	
		}

		$this->render('nivel_servicio_marca',array(
			'model'=>$model,
			'marcas'=>$m_marcas,
		));
	}

	

	

	/**
	 * Performs the AJAX validation.
	 * @param Menu $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='reporte-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	

	public function actionRentOracle()
	{		
		$model=new Reporte;
		$model->scenario = 'rent_oracle';

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('rent_oracle_resp',array('model' => $model));	
		}

		$this->render('rent_oracle',array(
			'model'=>$model,		
		));
	}	

 	public function actionRentOracleItem()
	{		
		$model=new Reporte;
		$model->scenario = 'rent_oracle_item';

		$oracle = Yii::app()->db->createCommand("SELECT M_Rowid, M_Descripcion FROM TH_ORACLE ORDER BY M_Descripcion")->queryAll();

		$lista_oracle = array();
		foreach ($oracle as $lorac) {
			$lista_oracle[$lorac['M_Descripcion']] = $lorac['M_Descripcion'];
		}

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('rent_oracle_item_resp',array('model' => $model));	
		}

		$this->render('rent_oracle_item',array(
			'model'=>$model,
			'lista_oracle'=>$lista_oracle,		
		));
	}

	

	

	

	

	/*public function actionSearchClienteCart(){
		$filtro = $_GET['q'];
        $data = Cliente::model()->searchByClienteCart($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['CLIENTE'],
               'text' => $item['CLIENTE'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchClienteCartNit(){
		$filtro = $_GET['q'];
        $data = Cliente::model()->searchByClienteCartNit($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['NIT'],
               'text' => $item['NIT'].' - '.$item['CLIENTE'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}*/

 	

	
	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	public function actionNivelServicioLinea()
	{		
		$model=new Reporte;
		$model->scenario = 'nivel_servicio_linea';

		$lineas = Yii::app()->db->createCommand("SELECT DISTINCT Criterio_Descripcion AS DESCRIPCION FROM TH_CRITERIOS_ITEMS WHERE Id_Plan=700 ORDER BY 1")->queryAll();

		$lista_lineas = array();
		foreach ($lineas as $li) {
			$lista_lineas[$li['DESCRIPCION']] = $li['DESCRIPCION'];
		}

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('nivel_servicio_linea_resp',array('model' => $model));	
		}

		$this->render('nivel_servicio_linea',array(
			'model'=>$model,
			'lista_lineas'=>$lista_lineas,
		));
	}

	

	

	

	

	

	

	

	

	

	

	

	

	



	

	/*FIN rent. marca ecuador / peru*/

	/*INICIO inventario ecuador / peru*/


	/*FIN inventario ecuador / peru*/

	/*INICIO Inventario costo ecuador / peru*/

	/*FIN Inventario costo ecuador / peru*/

	/*INICIO Rent Inventario marca ecuador / peru*/

	/*FIN Rent Inventario marca ecuador / peru*/

	/*INICIO pedidos pend despacho ecuador / peru*/

	/*FIN pedidos pend despacho ecuador / peru*/

	/*INICIO pedidos acum marca ecuador / peru*/


	/*FIN pedidos acum marca ecuador / peru*/

	

	

	/*public function actionSearchItem(){
		$filtro = $_GET['q'];
		$model=new Reporte;
        $data = $model->searchByItem($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['ID'],
               'text' => $item['DESCR'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchItemById(){
		$filtro = $_GET['id'];
        $model=new Reporte;
        $data = $model->searchById($filtro);

        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['ID'],
               'text' => $item['DESCR'],
           );
        endforeach;

        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}*/

 	

	

	

	

	

	

	


	

	

	

	

	

	

	

	

	

	

	

	

	

	

	

	public function actionFotoCart()
	{		
		$model=new Reporte;
		$model->scenario = 'foto_cart';

		if(isset($_POST['Reporte']))
		{
			
			$q_consec = Yii::app()->db->createCommand("SELECT MAX(Cons) AS Consecutivo FROM TH_FOTO_CARTERA")->queryRow();

			if(empty($q_consec)){
				$cons = 1;
			}else{
				$cons = $q_consec['Consecutivo'] + 1;
			}

			$dia = intval(date('d'));

			if($dia <= 15){
				$mes_act = intval(date('m'));
				$mes = $mes_act - 1;

				if($mes < 10){
					$mes_per = '0'.$mes;
				}else{
					$mes_per = $mes;	
				}

				$periodo = date('Y').$mes_per;

			}else{
				$mes = date('m');
				$periodo = date('Y').$mes;
			}

			$date = date('Y-m-d H:i:s');

			$query ="EXEC unoee1..sp_cons_cxc 2,NULL,'PUC',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,30,31,60,61,90,91,120,121,180,181,360,361,9999,0,0,0,0,0,0,0,999999,0,'".$date."',1,'COP',1,10261,'Cons_Cart_Cort',0,190,0,'".$date."','".$date."',0,0";


    		$q1 = Yii::app()->db->createCommand($query)->queryAll();

    		$i = 0; 

    		if(!empty($q1)){
      			foreach ($q1 as $reg1) {

      				$modelo_foto_cartera = new FotoCartera;
      				$modelo_foto_cartera->Cons = $cons;
      				$modelo_foto_cartera->Saldo_Total = $reg1['f1_saldo_total'];
      				$modelo_foto_cartera->Saldo_1_30 = $reg1['f1_saldo_vencido1'];
      				$modelo_foto_cartera->Saldo_31_60 = $reg1['f1_saldo_vencido2'];
      				$modelo_foto_cartera->Saldo_61_90 = $reg1['f1_saldo_vencido3'];
      				$modelo_foto_cartera->Saldo_91_120 = $reg1['f1_saldo_vencido4'];
      				$modelo_foto_cartera->Saldo_121_180 = $reg1['f1_saldo_vencido5'];
      				$modelo_foto_cartera->Saldo_181_360 = $reg1['f1_saldo_vencido6'];
      				$modelo_foto_cartera->Saldo_361_9999 = $reg1['f1_saldo_vencido7'];
      				$modelo_foto_cartera->Estructura_Ventas = $reg1['f_02_300'];
      				$modelo_foto_cartera->Canal = $reg1['f_02_200'];
      				$modelo_foto_cartera->Periodo = $periodo;
      				$modelo_foto_cartera->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
      				$modelo_foto_cartera->Fecha_Creacion = date('Y-m-d H:i:s');
      				
      				if($modelo_foto_cartera->save()){
      					$i++;
      				}
      				
      			}
			}

			Yii::app()->user->setFlash('success', "Se insertaron ".$i." registros con el consecutivo ".$cons.".");
			$this->redirect(array('FotoCart'));
		}

		$this->render('foto_cart',array(
			'model'=>$model,
		));
	}

	

	

	

	

	

	

	

	

	

	

	

	

	public function actionConsultaFactElect()
	{		
		$model=new Reporte;
		$model->scenario = 'consulta_fact_elect';

		if(isset($_POST['Reporte']))
		{
			$model=$_POST['Reporte'];
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


	

	

	

	public function actionFactTiendasWeb()
	{		
		$model=new Reporte;
		$model->scenario = 'fact_tiendas_web';

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('fact_tiendas_web_resp',array('model' => $model));	
		}

		$this->render('fact_tiendas_web',array(
			'model'=>$model,		
		));
	}

	public function actionDespTiendasWeb()
	{		
		$model=new Reporte;
		$model->scenario = 'desp_tiendas_web';

		if(isset($_POST['Reporte']))
		{
			$model->attributes=$_POST['Reporte'];
			$this->renderPartial('desp_tiendas_web_resp',array('model' => $model));	
		}

		$this->render('desp_tiendas_web',array(
			'model'=>$model,		
		));
	}

	

	


	public function actionDetTransTiendasWeb()
	{		
		$model=new Reporte;

		$this->render('det_trans_tiendas_web',array(
			'model'=>$model,
		));
	}

	public function actionUploadDetTransTiendasWeb()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Reporte']['tmp_name']['archivo'];
        
        set_time_limit(0);

        // Se inactiva el autoloader de yii
		spl_autoload_unregister(array('YiiBase','autoload'));   

		require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel.php';
		require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel/Reader/Excel2007.php';
		require_once Yii::app()->basePath . '/extensions/PHPExcel/Classes/PHPExcel/IOFactory.php';

		//cuando se termina la accion relacionada con la libreria se activa el autoloader de yii
		spl_autoload_register(array('YiiBase','autoload'));

		$objPHPExcel = PHPExcel_IOFactory::load($file_tmp); 
        $objPHPExcel->setActiveSheetIndex(0);

        //Convierto la data de la Hoja en un arreglo
        $dataExcel = $objPHPExcel->getActiveSheet()->toArray();

        $filas = count($dataExcel);

        if($filas > 2){

       		$c = 0;
    	
    		//se ejecuta el sp por cada fila en el archivo

    		$msj = '<h5><i class="icon fas fa-info-circle"></i>Info</h5>';

    		$clean_number_caract = array("$", ",");

    		for($i = 1; $i <= $filas -1 ; $i++){

    			print_r($dataExcel[$i]);die;

        		$param1 = $dataExcel[$i][0]; //Id
        		$param2 = $dataExcel[$i][1]; //Autorizacion
        		$param3 = $dataExcel[$i][2];  //Recibo
        		$param4 = $dataExcel[$i][3];  //Valor
        		$param5 = $dataExcel[$i][4];  //Iva
        		$param6 = $dataExcel[$i][5];  //Tipo_Identificacion
        		$param7 = $dataExcel[$i][6];  //Identificacion
        		$param8 = $dataExcel[$i][7]; //Ref1
        		$param9 = $dataExcel[$i][8]; //Ref2
        		$param10 = $dataExcel[$i][9]; //Ref3
        		$param11 = $dataExcel[$i][10]; //Servicio
        		$param12 = $dataExcel[$i][11]; //Descripcion
        		$param13 = $dataExcel[$i][12]; //Fecha
        		$param14 = $dataExcel[$i][13]; //Nombre
        		$param15 = $dataExcel[$i][14]; //Franquisia
        		$param16 = $dataExcel[$i][15]; //Tipo
        		$param17 = $dataExcel[$i][16]; //Tarjeta
        		$param18 = $dataExcel[$i][17]; //Cuotas
        		$param19 = $dataExcel[$i][18]; //Origen
        		$param20 = $dataExcel[$i][19]; //Score
        		$param21 = $dataExcel[$i][20]; //Respuesta
        		$param22 = $dataExcel[$i][21]; //Status
        		$param23 = $dataExcel[$i][22]; //Pais

        		$query_exist = "SELECT Id FROM Tiendabinner..Det_Trans WHERE Id_Transaccion = ".$param1;

				$row_exist =  Yii::app()->db->createCommand($query_exist)->queryRow();

				if(empty($row_exist)){
					//no existe la cabecera
				
					$command = Yii::app()->db->createCommand("
					INSERT INTO Tiendabinner..Det_Trans
		           ([Id]
		           ,[Autorizacion]
		           ,[Recibo]
		           ,[Valor]
		           ,[Iva]
		           ,[Tipo_Identificacion]
		           ,[Identificacion]
		           ,[Ref1]
		           ,[Ref2]
		           ,[Ref3]
		           ,[Servicio]
		           ,[Descripcion]
		           ,[Fecha]
		           ,[Nombre]
		           ,[Franquisia]
		           ,[Tipo]
		           ,[Tarjeta]
		           ,[Cuotas]
		           ,[Origen]
		           ,[Score]
		           ,[Respuesta]
		           ,[Status]
		           ,[Pais])
		     VALUES
		           (".$param1."
		           ,'".$param2."'
		           ,'".$param3."'
		           ,".$param4."
		           ,".$param5."
		           ,'".$param6."'
		           ,'".$param7."'
		           ,'".$param8."'
		           ,'".$param9."'
		           ,'".$param10."'
		           ,'".$param11."'
		           ,'".$param12."'
		           ,'".$param13."'
		           ,'".$param14."'
		           ,'".$param15."'
		           ,'".$param16."'
		           ,'".$param17."'
		           ,".$param18."
		           ,'".$param19."'
		           ,".$param20."
		           ,'".$param21."'
		           ,'".$param22."'
		           ,'".$param23."'
					)");

					$command->execute();
					$c++;

				}
			}

			$msj .= $c.' Registro(s) insertados.<br>'; 	

        	$resp = array('msj' => $msj);

        	echo json_encode($resp);

		}

	}

	public function actionRemisionTuGo()
	{		
		$model=new Reporte;
		$model->scenario = 'remision_tu_go';

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

		if(isset($_POST['Reporte']))
		{
			$this->renderPartial('remision_tu_go_resp',array('model' => $_POST['Reporte']));
		}

		$this->render('remision_tu_go',array(
			'model'=>$model,
			'lista_co'=>$lista_co,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionElimErrorTrans()
	{		
		$model=new Reporte;
		$model->scenario = 'elim_error_trans';

		if(isset($_POST['Reporte']))
		{
			$c = 0;
			
			$array_cons = explode(",", $_POST['Reporte']['consecutivo']);
			$array_td = array('EPM' => 'PME', 'EPP' => 'PPE', 'EPT' => 'PTE');

			$array_cd_act = array();

			$td = $_POST['Reporte']['tipo'];
			$td_i = $array_td[$td];

			foreach ($array_cons as $cd) {

				if($cd != ""){

					$command = Yii::app()->db2->createCommand("
					UPDATE Repositorio_Datos.dbo.tbl_IN_Transf_29 SET Integrado_Pangea= 99 , Tipo_Docto='".$td_i."' WHERE Tipo_Docto='".$td."' AND Consec_Docto in (".$cd.") AND Integrado_Pangea = 4");

					if($command->execute() > 0){
						$c++;
						$array_cd_act[] = $cd;
					}
				}

			}

			if ($c > 0) {
				$cd_act = implode(",", $array_cd_act);
				$cad_res = $td. " (".$cd_act.")";
				Yii::app()->user->setFlash('success', "Se procesaron ".$c." documento(s), ".$cad_res.".");
			}else{
				Yii::app()->user->setFlash('warning', "No se proceso ningún documento.");	
			}			

		}

		$this->render('elim_error_trans',array(
			'model'=>$model,
		));
	}

	public function actionErrorTransf()
	{		
		$model=new Reporte;
		$model->scenario = 'error_transf';

		if(isset($_POST['Reporte']))
		{
			$model=$_POST['Reporte'];
			$this->renderPartial('error_transf_resp',array('model' => $model));	
		}

		$this->render('error_transf',array(
			'model'=>$model,
		));
	}

	public function actionErrorTransfPant()
	{		
		$fecha = $_POST['fecha'];

		$resultados = UtilidadesReportes::errortransfpantalla($fecha);

		echo $resultados;
	}

	public function actionLogCrossdocking()
	{		
		$model=new Reporte;
		$model->scenario = 'log_crossdocking';

		if(isset($_POST['Reporte']))
		{
			$this->renderPartial('log_crossdocking_resp',array('model' => $_POST['Reporte']));
		}

		$this->render('log_crossdocking',array(
			'model'=>$model,
		));
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

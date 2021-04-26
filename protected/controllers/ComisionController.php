<?php

class ComisionController extends Controller
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
				'actions'=>array('searchvendedor','searchvend','liquidacion','calcliqind','calcliqall','ajuste','searchrecibo','ajusterec','historico','searchdoc','gethistdocto'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionSearchVendedor(){
		$filtro = $_GET['q'];
		$tipo = $_GET['tipo'];
        $data = CVendedores::model()->searchByVendedor($filtro,$tipo);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['ROWID'],
               'text' => $item['VENDEDOR'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionSearchVend(){
		$filtro = $_GET['q'];
        $data = CVendedores::model()->searchByVend($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['ROWID'],
               'text' => $item['VENDEDOR'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

	public function actionLiquidacion()
	{		
		$model=new Comision;
		$model->scenario = 'liquidacion';

		/*$ins_bd1 = Yii::app()->db->createCommand("
			EXEC P_CF_CMS_INS_RECIBOS
		")->execute();

		$ins_bd2 = Yii::app()->db->createCommand("
			EXEC P_CF_CMS_INS_VENTAS
		")->execute();

		$ins_bd3 = Yii::app()->db->createCommand("
			EXEC P_CF_CMS_INS_VENTAS_DET
		")->execute();*/

		$tipos = Yii::app()->db->createCommand("SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = ".Yii::app()->params->tipos_comision." AND Estado = 1 ORDER BY d.Dominio")->queryAll();

		$this->render('liquidacion',array(
			'model'=>$model,
			'tipos'=>$tipos,
		));
	}

	public function actionCalcLiqInd()
	{
		//INDIVIDUAL

		$mes = intval($_POST['mes']);

		$nombre_mes = array(
			1 => 'ENERO',
			2 => 'FEBRERO',
			3 => 'MARZO',
			4 => 'ABRIL',
			5 => 'MAYO',
			6 => 'JUNIO',
			7 => 'JULIO',
			8 => 'AGOSTO',
			9 => 'SEPTIEMBRE',
			10 => 'OCTUBRE',
			11 => 'NOVIEMBRE',
			12 => 'DICIEMBRE',
		);

		$nombre_m = $nombre_mes[$mes]; 

		$anio = intval($_POST['anio']);

		if($mes < 10){
			$mes = '0'.$mes;
		}

		$periodo = "'".$anio.$mes."'";
		
		$tip = intval($_POST['tipo']);
		$desc_tipo = Dominio::model()->findByPk($tip)->Dominio;

		$liq = intval($_POST['liquidacion']);
		if($liq == 1){
			$desc_liq = "INDIVIDUAL";
		}else{
			$desc_liq = "TODOS LOS VENDEDORES";	
		}

		$vendedor = intval($_POST['vendedor']);
		$modelo_cvend = cVendedores::model()->findByAttributes(array('ROWID'=>$vendedor));
		$desc_vend = $modelo_cvend->NIT_VENDEDOR.' - '.$modelo_cvend->NOMBRE_VENDEDOR;

		$observaciones = $_POST['observaciones'];

		/*PROXIMO ID*/
		$q_next_id = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_ID_VEND")->queryRow();

		$id = $q_next_id['id'];

		$ins_int = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_VAL_VEND_IND @ROWID = ".$vendedor)->query();

		/*LIQUIDAR ACELERADOR*/
		$liq_acel = Yii::app()->db->createCommand("SET NOCOUNT ON EXEC P_CF_CMS_EJEC_ACEL_VEND @ID1 = ".$id.", @VENDEDOR1 = ".$vendedor.", @PERIODO = ".$periodo)->queryRow();

		$RES = $liq_acel['RES'];
		
		if($RES == 1){
			/*LIQUIDAR VENTAS*/
			$liq_vent = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_VENT_VEND @ID = ".$id.", @VENDEDOR = ".$vendedor.", @PERIODO = ".$periodo)->query();

			/*LIQUIDAR RECAUDOS*/
			$liq_recau = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_REC_VEND @ID = ".$id.", @VENDEDOR = ".$vendedor.", @PERIODO = ".$periodo)->query();

			/*LIQUIDAR AJUSTES*/
			$liq_recau = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_AJUS_REC_VEND @ID = ".$id.", @VENDEDOR = ".$vendedor.", @PERIODO = ".$periodo)->query();


		}

		$del_int = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_RET_LIQ")->query();

		//VERIFICACIÓN DE INSERCION POR LA LIQUIDACIÓN
		$est_liq = Yii::app()->db->createCommand("EXEC P_CF_CMS_CONS_ID_INSERT @ROWID = ".$id)->queryRow();

		$VALID = $est_liq['VR1'];

		if($VALID == 1){
			//SI GENERO RESULTADOS LA LIQUIDACION
			$new_liq = new CControlCms;
			$new_liq->ID_BASE = $id;
			$new_liq->MES = intval($_POST['mes']);
			$new_liq->ANIO = intval($_POST['anio']);
			$new_liq->TIPO = $tip;
			$new_liq->LIQUIDACION = $liq;
			if($vendedor != 0 && $vendedor != ""){
				$new_liq->VENDEDOR = $vendedor;
			}
			$new_liq->OBSERVACION = $observaciones;
			$new_liq->ESTADO = 1;
			$new_liq->ID_USUARIO_CREACION = Yii::app()->user->getState('id_user');
			$new_liq->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
			$new_liq->FECHA_CREACION = date('Y-m-d H:i:s');
			$new_liq->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
			if($new_liq->save()){

				$res = 1;
				$msg = "La ejecución del proceso con los siguientes parametros:<br><br>
				<strong>Mes: </strong>".$nombre_m."<br>
				<strong>Año: </strong>".$anio."<br>
				<strong>Tipo: </strong>".$desc_tipo."<br>
				<strong>Liquidación: </strong>".$desc_liq."<br>
				<strong>Vendedor: </strong>".$desc_vend."<br>
				<strong>Observaciones: </strong>".$observaciones."<br><br>
				<strong>Genero la liquidación con ID ".$id."</strong><br>";
				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);

			}

		}else{

			$res = 0;
			$msg = "La ejecución del proceso con los siguientes parametros:<br><br>
			<strong>Mes: </strong>".$nombre_m."<br>
			<strong>Año: </strong>".$anio."<br>
			<strong>Tipo: </strong>".$desc_tipo."<br>
			<strong>Liquidación: </strong>".$desc_liq."<br>
			<strong>Vendedor: </strong>".$desc_vend."<br>
			<strong>Observaciones: </strong>".$observaciones."<br><br>
			<strong>No genero ninguna liquidación.</strong>";
			$resp = array('res' => $res, 'msg' => $msg);
    		echo json_encode($resp);	
		
		}	

	}

	public function actionCalcLiqAll()
	{
		//TODOS LOS VENDEDORES

		$mes = intval($_POST['mes']);

		$nombre_mes = array(
			1 => 'ENERO',
			2 => 'FEBRERO',
			3 => 'MARZO',
			4 => 'ABRIL',
			5 => 'MAYO',
			6 => 'JUNIO',
			7 => 'JULIO',
			8 => 'AGOSTO',
			9 => 'SEPTIEMBRE',
			10 => 'OCTUBRE',
			11 => 'NOVIEMBRE',
			12 => 'DICIEMBRE',
		);

		$nombre_m = $nombre_mes[$mes]; 

		$anio = intval($_POST['anio']);

		if($mes < 10){
			$mes = '0'.$mes;
		}

		$periodo = "'".$anio.$mes."'";
		
		$tip = intval($_POST['tipo']);
		$desc_tipo = Dominio::model()->findByPk($tip)->Dominio;

		$liq = intval($_POST['liquidacion']);
		if($liq == 1){
			$desc_liq = "INDIVIDUAL";
		}else{
			$desc_liq = "TODOS LOS VENDEDORES";	
		}

		$vendedor = intval($_POST['vendedor']);

		$observaciones = $_POST['observaciones'];

		/*PROXIMO ID*/
		$q_next_id = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_ID_VEND")->queryRow();

		$id = $q_next_id['id'];

		//se valida si hay otra ejecución en curso
		$v = Yii::app()->db->createCommand("SET NOCOUNT ON EXEC P_CF_CMS_EJEC_VAL_VEND @Tipo= ".$tip)->queryRow();

		$response = $v['VAL'];
	
		if($response == 1){

			$q_vendedores = Yii::app()->db->createCommand("SELECT ROWID FROM T_PR_C_VENDEDORES WHERE TIPO = ".$tip." AND ESTADO = 'ACTIVO'")->queryAll();

			if(!empty($q_vendedores)){

				//$i = 0;

				foreach ($q_vendedores as $v) {

					$vend = $v['ROWID'];

					/*LIQUIDAR ACELERADOR*/
					$liq_acel = Yii::app()->db->createCommand("SET NOCOUNT ON EXEC P_CF_CMS_EJEC_ACEL_VEND @ID1 = ".$id.", @VENDEDOR1 = ".$vend.", @PERIODO = ".$periodo)->queryRow();

					$RES = $liq_acel['RES'];
				
					if($RES == 1){

						/*LIQUIDAR VENTAS*/
						$liq_vent = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_VENT_VEND @ID = ".$id.", @VENDEDOR = ".$vend.", @PERIODO = ".$periodo)->query();
						
						/*LIQUIDAR RECAUDOS*/
						$liq_recau = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_REC_VEND @ID = ".$id.", @VENDEDOR = ".$vend.", @PERIODO = ".$periodo)->query();

						/*LIQUIDAR AJUSTES*/
						$liq_recau = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_AJUS_REC_VEND @ID = ".$id.", @VENDEDOR = ".$vend.", @PERIODO = ".$periodo)->query();

						$act_liq = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_ACT_LIQ  @ROWID = ".$vend)->query();		
					}
				}

			$del_int = Yii::app()->db->createCommand("EXEC P_CF_CMS_EJEC_RET_LIQ")->query();

			}
		}

		//VERIFICACIÓN DE INSERCION POR LA LIQUIDACIÓN
		$est_liq = Yii::app()->db->createCommand("EXEC P_CF_CMS_CONS_ID_INSERT @ROWID = ".$id)->queryRow();

		$VALID = $est_liq['VR1'];

		if($VALID == 1){
			//SI GENERO RESULTADOS LA LIQUIDACION
			$new_liq = new CControlCms;
			$new_liq->ID_BASE = $id;
			$new_liq->MES = intval($_POST['mes']);
			$new_liq->ANIO = intval($_POST['anio']);
			$new_liq->TIPO = $tip;
			$new_liq->LIQUIDACION = $liq;
			if($vendedor != 0 && $vendedor != ""){
				$new_liq->VENDEDOR = $vendedor;
			}
			$new_liq->OBSERVACION = $observaciones;
			$new_liq->ESTADO = 1;
			$new_liq->ID_USUARIO_CREACION = Yii::app()->user->getState('id_user');
			$new_liq->ID_USUARIO_ACTUALIZACION = Yii::app()->user->getState('id_user');
			$new_liq->FECHA_CREACION = date('Y-m-d H:i:s');
			$new_liq->FECHA_ACTUALIZACION = date('Y-m-d H:i:s');
			if($new_liq->save()){

				$res = 1;
				$msg = "La ejecución del proceso con los siguientes parametros:<br><br>
				<strong>Mes: </strong>".$nombre_m."<br>
				<strong>Año: </strong>".$anio."<br>
				<strong>Tipo: </strong>".$desc_tipo."<br>
				<strong>Liquidación: </strong>".$desc_liq."<br>
				<strong>Observaciones: </strong>".$observaciones."<br><br>
				<strong>Genero la liquidación con ID ".$id."</strong><br>";
				$resp = array('res' => $res, 'msg' => $msg);
        		echo json_encode($resp);

			}

		}else{

			$res = 0;
			$msg = "La ejecución del proceso con los siguientes parametros:<br><br>
			<strong>Mes: </strong>".$nombre_m."<br>
			<strong>Año: </strong>".$anio."<br>
			<strong>Tipo: </strong>".$desc_tipo."<br>
			<strong>Liquidación: </strong>".$desc_liq."<br>
			<strong>Observaciones: </strong>".$observaciones."<br><br>
			<strong>No genero ninguna liquidación.</strong>";
			$resp = array('res' => $res, 'msg' => $msg);
    		echo json_encode($resp);	
		
		}

	}

	public function actionAjuste()
	{		
		$model=new Comision;
		$model->scenario = 'ajuste';

		$this->render('ajuste',array(
			'model'=>$model,
		));
	}

	public function actionSearchRecibo(){
		$filtro = $_GET['q'];
        $data = CControlCms::model()->searchByRecibo($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['ROWID_RC'],
               'text' => $item['RECIBO'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionAjusteRec()
	{

		$recibo = $_POST['recibo']; 
		$vendedor = $_POST['vendedor'];

		//PROCESO Y VERIFICACIÓN DE AJUSTE
		$est_ajuste = Yii::app()->db->createCommand("SET NOCOUNT ON EXEC P_CF_CMS_INS_AJUSTE_RECIBOS @ROWID_RC = ".$recibo." , @ROWID_VD = ".$vendedor)->queryRow();

		$VALID = $est_ajuste['VR1'];

		if($VALID == 1){
			//SI GENERO El AJUSTE
			
			$res = 1;
			$msg = "Se realizo el ajuste del recibo correctamente.";
			$resp = array('res' => $res, 'msg' => $msg);
    		echo json_encode($resp);

		}else{

			$res = 0;
			$msg = "No se pudo realizar el ajuste del recibo.";
			$resp = array('res' => $res, 'msg' => $msg);
    		echo json_encode($resp);	
		
		}

	}

	public function actionHistorico()
	{		
		$model=new Comision;
		$model->scenario = 'historico';

		$this->render('historico',array(
			'model'=>$model,
		));
	}

	public function actionSearchDoc(){
		$filtro = $_GET['q'];
		$tipo = $_GET['tipo'];
        $data = CControlCms::model()->searchByDoc($filtro, $tipo);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Row_Id'],
               'text' => $item['Documento'],
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionGetHistDocto()
	{
		
 		$tipo = $_POST['tipo'];
 		$id_docto = $_POST['documento'];

 		if($tipo == 1){
			
			//FACTURA
 			$sql_hist_docto = "
 			SELECT
			f350_rowid AS Rowid
			,ISNULL(t3.PEDIDO,  '-') AS DOC_ALT1	
			,ISNULL(t3.FACTURA, '-') AS DOC_ALT2	
			,ISNULL(t3.FACTURA, '-') AS DOCUMENTO
			,0 AS DIAS_PAGO	
			,ISNULL(t3.VLR_SUBTOTAL, 0) AS BASE
			,ISNULL(t3.PORCENTAJE, 0) AS PORCENTAJE	
			,ISNULL(((t3.PORCENTAJE * t3.VLR_SUBTOTAL) / 100), 0) AS VLR_BASE
			,t9.Ciudad as CORRERIA
			,ISNULL(t3.PTJ_CORRERIA, 0) AS PTJ_CORRERIA	
			,((t3.VLR_SUBTOTAL * t3.PTJ_CORRERIA) / 100) AS VLR_BASE_CORRERIA	
			,ISNULL(t4.ACELERADOR, 0) AS ACELERADOR
			,ISNULL(t4.PORCENTAJE, 0) AS PTJ_ACELERADOR	
			,ISNULL(((t4.PORCENTAJE * t4.VLR_SUBTOTAL) / 100), 0) AS VLR_BASE_ACELERADOR	
			,CAST(ISNULL(t7.OBSERVACION, ISNULL(t8.OBSERVACION, '-')) as nvarchar) AS OBSERVACION_EJECUTADO
			,ISNULL(t3.NOMBRE_VENDEDOR, '-') AS VENDEDOR_EJECUTADO	
			,ISNULL(t7.FECHA_CREACION, ISNULL(t8.FECHA_CREACION, getdate())) AS FECHA_CREACION_EJECUTADO	
			,ISNULL(t3.ID_BASE, ISNULL(t4.ID_BASE, 0)) AS ID_BASE_EJECUTADO
			,cast(0 AS decimal(4, 2)) AS PTJ_BASE_AJUSTADO	
			,cast(0 AS decimal(28, 2)) AS VLR_BASE_AJUSTADO
			,CAST('-' AS nvarchar) AS OBSERVACION_AJUSTADO
			,'-' AS VENDEDOR_AJUSTADO
			,getdate() AS FECHA_CREACION_AJUSTADO
			,0 AS ID_BASE_AJUSTADO		
			FROM UnoEE1..t350_co_docto_contable 
			INNER JOIN T_PR_C_VENTAS AS t3 ON f350_rowid = t3.ROWID_FACTURA
			LEFT JOIN T_PR_C_VENTAS_DET AS t4 ON f350_rowid = t4.ROWID_REMISION
			LEFT JOIN T_PR_C_CONTROL_CMS AS t7 ON t3.ID_BASE = t7.ID_BASE
			LEFT JOIN T_PR_C_CONTROL_CMS AS t8 ON t4.ID_BASE = t8.ID_BASE
			LEFT JOIN T_PR_C_PTJ_CORRERIA AS t9 ON t3.ID_CIUDAD=t9.Id_Siesa
			WHERE f350_id_cia = 2 AND f350_rowid =".$id_docto;

			$hist_docto = Yii::app()->db->createCommand($sql_hist_docto)->queryRow();

			$Rowid 						= $hist_docto['Rowid'];
			$DOC_ALT1					= $hist_docto['DOC_ALT1'];
			$DOC_ALT2 					= $hist_docto['DOC_ALT2'];
			$DOCUMENTO 					= $hist_docto['DOCUMENTO']; 
			//$DIAS_PAGO 				= $hist_docto['DIAS_PAGO']; 
			$BASE 						= number_format($hist_docto['BASE'], 2, ',', '.');
			$PORCENTAJE 				= number_format($hist_docto['PORCENTAJE'], 2, ',', '.');
			$VLR_BASE 					= number_format($hist_docto['VLR_BASE'], 2, ',', '.'); 
			$CORRERIA 					= number_format($hist_docto['CORRERIA'], 2, ',', '.');
			$PTJ_CORRERIA 				= number_format($hist_docto['PTJ_CORRERIA'], 2, ',', '.'); 
			$VLR_BASE_CORRERIA 			= number_format($hist_docto['VLR_BASE_CORRERIA'], 2, ',', '.');  
			$ACELERADOR 				= number_format($hist_docto['ACELERADOR'], 2, ',', '.');
			$PTJ_ACELERADOR 			= number_format($hist_docto['PTJ_ACELERADOR'], 2, ',', '.'); 
			$VLR_BASE_ACELERADOR 		= number_format($hist_docto['VLR_BASE_ACELERADOR'], 2, ',', '.');
			$OBSERVACION_EJECUTADO 		= $hist_docto['OBSERVACION_EJECUTADO']; 
			$VENDEDOR_EJECUTADO 		= $hist_docto['VENDEDOR_EJECUTADO']; 
			$FECHA_CREACION_EJECUTADO 	= $hist_docto['FECHA_CREACION_EJECUTADO']; 
			$ID_BASE_EJECUTADO 			= $hist_docto['ID_BASE_EJECUTADO']; 
			//$PTJ_BASE_AJUSTADO		= $hist_docto['PTJ_BASE_AJUSTADO']; 
			//$VLR_BASE_AJUSTADO 		= $hist_docto['VLR_BASE_AJUSTADO']; 
			//$OBSERVACION_AJUSTADO 	= $hist_docto['OBSERVACION_AJUSTADO']; 
			//$VENDEDOR_AJUSTADO 		= $hist_docto['VENDEDOR_AJUSTADO']; 
			//$FECHA_CREACION_AJUSTADO 	= $hist_docto['FECHA_CREACION_AJUSTADO']; 
			//$ID_BASE_AJUSTADO 		= $hist_docto['ID_BASE_AJUSTADO'];

			$contenido = '
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>Row Id</label>
	 				<p>'.$Rowid.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Docto ALT. 1</label>
	 				<p>'.$DOC_ALT1.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Docto ALT. 2</label>
	 				<p>'.$DOC_ALT2.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Documento</label>
	 				<p>'.$DOCUMENTO.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
					<label>Base</label>
	 				<p>'.$BASE.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>% base</label>
	 				<p>'.$PORCENTAJE.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Vlr. base</label>
	 				<p>'.$VLR_BASE.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Vlr. correria</label>
	 				<p>'.$CORRERIA.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>% correria</label>
	 				<p>'.$PTJ_CORRERIA.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Vlr. base correria</label>
	 				<p>'.$VLR_BASE_CORRERIA.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Acelerador</label>
	 				<p>'.$ACELERADOR.'</p>
	 			</div>

	 			<div class="col-sm-3">
	 				<label>% acelerador</label>
	 				<p>'.$PTJ_ACELERADOR.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>Vlr. base acelerador</label>
	 				<p>'.$VLR_BASE_ACELERADOR.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Observaciones ejecutado</label>
	 				<p>'.$OBSERVACION_EJECUTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Vendedor ejecutado</label>
	 				<p>'.$VENDEDOR_EJECUTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Fecha de creación ejecutado</label>
	 				<p>'.UtilidadesVarias::textofechahora($FECHA_CREACION_EJECUTADO).'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
					<label>ID base ejecutado</label>
	 				<p>'.$ID_BASE_EJECUTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				
	 			</div>
	 			<div class="col-sm-3">
	 				
	 			</div>
	 		</div>
	 		';

 		}

 		if($tipo == 2){
 			
 			//RECIBO
 			$sql_hist_docto = "
 			SELECT 
			f350_rowid AS Rowid
			,ISNULL(t2.PEDIDO, '-') AS DOC_ALT1	
			,ISNULL(t2.FACTURA, '-') AS DOC_ALT2	
			,ISNULL(t2.RECIBO, '-') AS DOCUMENTO
			,ISNULL(t2.DIAS_PAGO,  0) AS DIAS_PAGO	
			,CAST(ISNULL(t2.BASE_RECIBO, 0) AS money) AS BASE
			,ISNULL(t2.PORCENTAJE, 0.00) AS PORCENTAJE	
			,ISNULL(t2.VLR_BASE_CMS, 0.00) AS VLR_BASE
			,'' AS CORRERIA	
			,cast(0 AS decimal(4, 2)) AS PTJ_CORRERIA	
			,cast(0 AS decimal(28, 2)) AS VLR_BASE_CORRERIA	
			,'' AS ACELERADOR	
			,cast(0 AS decimal(4, 2)) AS PTJ_ACELERADOR	
			,cast(0 AS decimal(28, 2)) AS VLR_BASE_ACELERADOR
			,CAST(ISNULL(t6.OBSERVACION, '-') AS nvarchar) AS OBSERVACION_EJECUTADO
			,CASE WHEN t2.ID_BASE = 0 THEN 'SIN PAGO' ELSE ISNULL(t2.VENDEDOR, '-') END AS VENDEDOR_EJECUTADO	
			,ISNULL(t6.FECHA_CREACION, getdate()) AS FECHA_CREACION_EJECUTADO	
			,ISNULL(t2.ID_BASE, 0) AS ID_BASE_EJECUTADO
			,ISNULL(t1.PORCENTAJE, 0) AS PTJ_BASE_AJUSTADO	
			,ISNULL(t1.VLR_BASE_CMS, 0) AS VLR_BASE_AJUSTADO
			,CAST(ISNULL(t5.OBSERVACION, '-') as nvarchar) AS OBSERVACION_AJUSTADO
			,ISNULL(t1.VENDEDOR, '-') AS VENDEDOR_AJUSTADO
			,ISNULL(t5.FECHA_CREACION, getdate()) AS FECHA_CREACION_AJUSTADO
			,ISNULL(t1.ID_BASE, 0) AS ID_BASE_AJUSTADO
			FROM UnoEE1..t350_co_docto_contable 
			INNER JOIN T_PR_C_RECIBOS AS t2 ON f350_rowid = t2.ROWID_RECIBO
			LEFT JOIN T_PR_C_AJUSTE_RECIBOS AS t1 ON f350_rowid = t1.ROWID_RECIBO
			LEFT JOIN T_PR_C_CONTROL_CMS AS t5 ON t1.ID_BASE = t5.ID_BASE
			LEFT JOIN T_PR_C_CONTROL_CMS AS t6 ON t2.ID_BASE = t6.ID_BASE
			WHERE f350_id_cia = 2 AND f350_rowid =".$id_docto;

			$hist_docto = Yii::app()->db->createCommand($sql_hist_docto)->queryRow();

			$Rowid 						= $hist_docto['Rowid'];
			$DOC_ALT1					= $hist_docto['DOC_ALT1'];
			$DOC_ALT2 					= $hist_docto['DOC_ALT2'];
			$DOCUMENTO 					= $hist_docto['DOCUMENTO']; 
			$DIAS_PAGO 					= $hist_docto['DIAS_PAGO']; 
			$BASE 						= number_format($hist_docto['BASE'], 2, '.', ','); 
			$PORCENTAJE 				= number_format($hist_docto['PORCENTAJE'], 2, '.', ',');
			$VLR_BASE 					= number_format($hist_docto['VLR_BASE'], 2, '.', ',');
			//$CORRERIA 				= $hist_docto['CORRERIA'];
			//$PTJ_CORRERIA 			= $hist_docto['PTJ_CORRERIA']; 
			//$VLR_BASE_CORRERIA 		= $hist_docto['VLR_BASE_CORRERIA']; 
			//$ACELERADOR 				= $hist_docto['ACELERADOR'];
			//$PTJ_ACELERADOR 			= $hist_docto['PTJ_ACELERADOR']; 
			//$VLR_BASE_ACELERADOR 		= $hist_docto['VLR_BASE_ACELERADOR'];
			$OBSERVACION_EJECUTADO 		= $hist_docto['OBSERVACION_EJECUTADO']; 
			$VENDEDOR_EJECUTADO 		= $hist_docto['VENDEDOR_EJECUTADO']; 
			$FECHA_CREACION_EJECUTADO 	= $hist_docto['FECHA_CREACION_EJECUTADO']; 
			$ID_BASE_EJECUTADO 			= $hist_docto['ID_BASE_EJECUTADO']; 
			$PTJ_BASE_AJUSTADO 			= number_format($hist_docto['PTJ_BASE_AJUSTADO'], 2, '.', ','); 
			$VLR_BASE_AJUSTADO 			= number_format($hist_docto['VLR_BASE_AJUSTADO'], 2, '.', ','); 
			$OBSERVACION_AJUSTADO 		= $hist_docto['OBSERVACION_AJUSTADO']; 
			$VENDEDOR_AJUSTADO 			= $hist_docto['VENDEDOR_AJUSTADO']; 
			$FECHA_CREACION_AJUSTADO 	= $hist_docto['FECHA_CREACION_AJUSTADO']; 
			$ID_BASE_AJUSTADO 			= $hist_docto['ID_BASE_AJUSTADO'];

			$contenido = '
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>Row Id</label>
	 				<p>'.$Rowid.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Docto ALT. 1</label>
	 				<p>'.$DOC_ALT1.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Docto ALT. 2</label>
	 				<p>'.$DOC_ALT2.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Documento</label>
	 				<p>'.$DOCUMENTO.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>Días pago</label>
	 				<p>'.$DIAS_PAGO.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Base</label>
	 				<p>'.$BASE.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>% base</label>
	 				<p>'.$PORCENTAJE.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Vlr. base</label>
	 				<p>'.$VLR_BASE.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>Observaciones ejecutado</label>
	 				<p>'.$OBSERVACION_EJECUTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Vendedor ejecutado</label>
	 				<p>'.$VENDEDOR_EJECUTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Fecha de creación ejecutado</label>
	 				<p>'.UtilidadesVarias::textofechahora($FECHA_CREACION_EJECUTADO).'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Id base ejecutado</label>
	 				<p>'.$ID_BASE_EJECUTADO.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>% base ajustado</label>
	 				<p>'.$PTJ_BASE_AJUSTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>Vlr. base ajustado</label>
	 				<p>'.$VLR_BASE_AJUSTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Observaciones de ajuste</label>
	 				<p>'.$OBSERVACION_AJUSTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				<label>Vendedor de ajuste</label>
	 				<p>'.$VENDEDOR_AJUSTADO.'</p>
	 			</div>
	 		</div>
	 		<div class="row">
	 			<div class="col-sm-3">
	 				<label>Fecha de creación ajuste</label>
	 				<p>'.UtilidadesVarias::textofechahora($FECHA_CREACION_AJUSTADO).'</p>
	 			</div>
	 			<div class="col-sm-3">
					<label>ID base ajuste</label>
	 				<p>'.$ID_BASE_AJUSTADO.'</p>
	 			</div>
	 			<div class="col-sm-3">
	 				
	 			</div>
	 			<div class="col-sm-3">
	 				
	 			</div>
	 		</div>
	 		';
			
 		}

		echo $contenido;
	}

}

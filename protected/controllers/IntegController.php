<?php

class IntegController extends Controller
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
				'actions'=>array('estadoitems','uploadestadoitems','logmobile','logmobilepant','ingresoswebbinner','uploadingresoswebbinner','pagostiendabinner','uploadpagostiendabinner','confirmacionpagos','uploadconfirmacionpagos','auditoriapedidos','auditoriapedidospant','cambiofecpedxml','actreca','actrecapant','elimpedido','elimrecibo','listamateriales','listamaterialesdet','listamaterialespant','calidadpqrs','actualizarreca'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionEstadoItems()
	{		
		$model=new Integ;

		$this->render('estado_items',array(
			'model'=>$model,
		));
	}

	public function actionUploadEstadoItems()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Integ']['tmp_name']['archivo'];
        
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

        		if($param1 === '' || $param2 === ''){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias.<br>'; 
        			$valid = 0;
        		}else{

        			//se valida si el item existe

        			$codigo    = $param1;

        			$query_exist_item = "SELECT f120_id FROM UnoEE1..t120_mc_items WHERE f120_id = ".$param1;

    				$row_exist_item =  Yii::app()->db->createCommand($query_exist_item)->queryRow();

					$id_item = $row_exist_item['f120_id'];

					if(is_null($id_item)){
						$fila_error = $i + 1;
						$msj .= 'Error en la fila # '.$fila_error.', el item no existe.<br>'; 
					}else{

						//se valida si el estado es valido
						$estado = $param2;

						if($estado != 0 && $estado != 1 && $estado != 2){
							$fila_error = $i + 1;
							$msj .= 'Error en la fila # '.$fila_error.', el estado no es valido.<br>'; 
						}else{

						 	$connection = Yii::app()->db;
							$command = $connection->createCommand("
								UPDATE t1
								SET t1.f121_ind_estado = ".$estado."
								FROM UnoEE1..t121_mc_items_extensiones AS t1
								INNER JOIN UnoEE1..t120_mc_items ON f121_rowid_item=f120_rowid
								WHERE f120_id = ".$id_item." AND f120_id_cia=2
							");

							$command->execute();

							$cont = $cont + 1;

						}	
					}
					
        		}		        		
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	$msj .= $f.' Item(s) actualizado(s) correctamente.<br>'; 	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);
	}

	public function actionLogMobile()
	{		
		$model=new Integ;
		$model->scenario = 'log_mobile';

		if(isset($_POST['Integ']))
		{
			$model=$_POST['Integ'];
			$this->renderPartial('log_mobile_resp',array('model' => $model));	
		}

		$this->render('log_mobile',array(
			'model'=>$model,
		));
	}

	public function actionLogMobilePant()
	{		
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];

		$resultados = UtilidadesReportes::logmobilepantalla($fecha_inicial, $fecha_final);

		echo $resultados;
	}

	public function actionIngresosWebBinner()
	{		
		$model=new Integ;

		$this->render('ingresos_web_binner',array(
			'model'=>$model,
		));
	}

	public function actionUploadIngresosWebBinner()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Integ']['tmp_name']['archivo'];

		set_time_limit(0);

		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';

		spl_autoload_register(array('YiiBase','autoload'));

		$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);

        $objPHPExcel->setActiveSheetIndex(0);

        //Convierto la data de la Hoja en un arreglo
        $dataExcel = $objPHPExcel->getActiveSheet()->toArray();

        $filas = count($dataExcel);

        if($filas > 2){

       		$c = 0;
    	
    		//se ejecuta el sp por cada fila en el archivo

    		$msj = '<h5><i class="icon fas fa-info-circle"></i>Info</h5>';

    		for($i = 1; $i <= $filas -1 ; $i++){
        		$param1 = intval($dataExcel[$i][0]); //Número de pedido
        		$param2 = str_replace("'", "", $dataExcel[$i][1]); //Estado del pedido
        		$param3 = str_replace("'", "", $dataExcel[$i][2]);  //Fecha del pedido
        		$param4 = str_replace("'", "", $dataExcel[$i][3]);  //Nota del cliente
        		$param5 = str_replace("'", "", $dataExcel[$i][4]);  //Nombre (facturación)
        		$param6 = str_replace("'", "", $dataExcel[$i][5]);  //Apellidos (facturación)
        		$param7 = str_replace("'", "", $dataExcel[$i][6]);  //Identificacion
        		$param8 = str_replace("'", "", $dataExcel[$i][7]); //Empresa (facturación)
        		$param9 = str_replace("'", "", $dataExcel[$i][8]); //Dirección lineas 1 y 2 (facturación)
        		$param10 = str_replace("'", "", $dataExcel[$i][9]); //Ciudad (facturación)
        		$param11 = str_replace("'", "", $dataExcel[$i][10]); //Código de provincia (facturación)
        		$param12 = str_replace("'", "", $dataExcel[$i][11]); //Código postal (facturación)
        		$param13 = str_replace("'", "", $dataExcel[$i][12]); //Código del país (facturación)
        		$param14 = str_replace("'", "", $dataExcel[$i][13]); //Correo electrónico (facturación)
        		$param15 = str_replace("'", "", $dataExcel[$i][14]); //Teléfono (facturación)
        		$param16 = str_replace("'", "", $dataExcel[$i][15]); //Nombre (envío)
        		$param17 = str_replace("'", "", $dataExcel[$i][16]); //Apellidos (envío)
        		$param18 = str_replace("'", "", $dataExcel[$i][17]); //Dirección lineas 1 y 2 (envío)
        		$param19 = str_replace("'", "", $dataExcel[$i][18]); //Ciudad (envío)
        		$param20 = str_replace("'", "", $dataExcel[$i][19]); //Código de provincia (envío)
        		$param21 = str_replace("'", "", $dataExcel[$i][20]); //Código postal (envío)
        		$param22 = str_replace("'", "", $dataExcel[$i][21]); //Código del país (envío)
        		$param23 = str_replace("'", "", $dataExcel[$i][22]); //Título del método de pago
        		$param24 = str_replace("'", "", $dataExcel[$i][23]); //Importe de descuento del carrito
        		$param25 = str_replace("'", "", $dataExcel[$i][24]); //Importe de subtotal del pedido
        		$param26 = str_replace("'", "", $dataExcel[$i][25]); //Título del método de envío
        		$param27 = str_replace("'", "", $dataExcel[$i][26]); //Importe de envío del pedido
        		$param28 = str_replace("'", "", $dataExcel[$i][27]); //Importe reembolsado del pedido
        		$param29 = str_replace("'", "", $dataExcel[$i][28]); //Importe total del pedido
        		$param30 = str_replace("'", "", $dataExcel[$i][29]); //Importe total de impuestos del pedido
        		$param31 = str_replace("'", "", $dataExcel[$i][30]); //SKU
        		$param32 = str_replace("'", "", $dataExcel[$i][31]); //Artículo #
        		$param33 = str_replace("'", "", $dataExcel[$i][32]); //Item Name
        		$param34 = str_replace("'", "", $dataExcel[$i][33]); //Cantidad
        		$param35 = str_replace("'", "", $dataExcel[$i][34]); //Coste de artículo
        		$param36 = str_replace("'", "", $dataExcel[$i][35]); //Código de cupón
        		$param37 = str_replace("'", "", $dataExcel[$i][36]); //Importe de descuento
        		$param38 = str_replace("'", "", $dataExcel[$i][37]); //Importe de impuestos del descuento

        		$query_exist_cab = "SELECT Order_Number FROM Tiendabinner..Web_Orders WHERE Order_Number = ".$param1;

				$row_exist_cab =  Yii::app()->db->createCommand($query_exist_cab)->queryRow();

				if(empty($row_exist_cab)){
					//no existe la cabecera

					if(is_numeric($param31)){
				
						$command = Yii::app()->db->createCommand("
						INSERT INTO Tiendabinner..Web_Orders
						([Order_Number]
			           ,[Order_Status]
			           ,[Order_Date]
			           ,[Customer_Note]
			           ,[Billing_First_Name]
			           ,[Billing_Last_Name]
			           ,[Plain_Orders__Billing_Ident]
			           ,[Billing_Company]
			           ,[Billing_Address]
			           ,[Billing_City]
			           ,[Billing_State]
			           ,[Billing_Postcode]
			           ,[Billing_Country]
			           ,[Billing_Email]
			           ,[Billing_Phone]
			           ,[Shipping_First_Name]
			           ,[Shipping_Last_Name]
			           ,[Shipping_Address]
			           ,[Shipping_City]
			           ,[Shipping_State]
			           ,[Shipping_Postcode]
			           ,[Shipping_Country]
			           ,[Payment_Method_Title]
			           ,[Cart_Discount]
			           ,[Order_Subtotal]
			           ,[Shipping_Method_Title]
			           ,[Order_Shipping]
			           ,[Order_Refund]
			           ,[Order_Total]
			           ,[Order_Total_Tax]
			           ,[Coupons]
			           ,[Fecha]
			           )
						VALUES
						(".$param1."
			           ,'".$param2."'
			           ,'".$param3."'
			           ,'".$param4."'
			           ,'".$param5."'
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
			           ,'".$param18."'
			           ,'".$param19."'
			           ,'".$param20."'
			           ,'".$param21."'
			           ,'".$param22."'
			           ,'".$param23."'
			           ,".$param24."
			           ,".$param25."
			           ,'".$param26."'
			           ,".$param27."
			           ,".$param28."
			           ,".$param29."
			           ,".$param30."
			           ,'".$param36."'
			           ,'".date('Y-m-d H:i:s')."'
						)");

						$command->execute();
						$c++;

						$command2 = Yii::app()->db->createCommand("
						INSERT INTO Tiendabinner..Web_Orders_Details
			           ([Order_Number]
			           ,[Sku]
			           ,[Line_Id]
			           ,[Name]
			           ,[Qty]
			           ,[Item_Price]
			           ,[Fecha])
			     		VALUES
			           (".$param1."
			           ,".$param31."
			           ,".$param32."
			           ,'".$param33."'
			           ,".$param34."
			           ,".$param35."
			           ,'".date('Y-m-d H:i:s')."'
						)");

						$command2->execute();
						$c++;

					}

				}else{

					$query_exist_det = "SELECT Order_Number FROM Tiendabinner..Web_Orders_Details WHERE Order_Number = ".$param1." AND Line_Id = ".$param32;

					$row_exist_det =  Yii::app()->db->createCommand($query_exist_det)->queryRow();

					if(empty($row_exist_det)){
						//no existe el detalle

						if(is_numeric($param31)){

							$command2 = Yii::app()->db->createCommand("
							INSERT INTO Tiendabinner..Web_Orders_Details
				           ([Order_Number]
				           ,[Sku]
				           ,[Line_Id]
				           ,[Name]
				           ,[Qty]
				           ,[Item_Price]
				           ,[Fecha])
				     		VALUES
				           (".$param1."
				           ,".$param31."
				           ,".$param32."
				           ,'".$param33."'
				           ,".$param34."
				           ,".$param35."
				           ,'".date('Y-m-d H:i:s')."'
							)");

							$command2->execute();
							$c++;
						}

					}

				}
			}

			$msj .= $c.' Registro(s) insertados.<br>'; 	

        	$resp = array('msj' => $msj);

        	echo json_encode($resp);

		}

	}

	public function actionPagosTiendaBinner()
	{		
		$model=new Integ;

		$this->render('pagos_tienda_binner',array(
			'model'=>$model,
		));
	}

	public function actionUploadPagosTiendaBinner()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Integ']['tmp_name']['archivo'];
        
        set_time_limit(0);

		$lineas = file($file_tmp);
		$num_lineas = count($lineas) - 1;

		$i=0;

		for ($c=1; $c < $num_lineas; $c++) { 
			$data = explode(";",str_replace("'", "", $lineas[$c]));
			$n_trans_interno = $data[0];
        	$forma_pago = $data[1];
        	$ambiente = $data[2];
        	$tipo_trans = $data[3];
        	$tipo_tarjeta = $data[4];
        	$fran_tarjeta = $data[5];
        	$ult_dig_tarjeta = $data[6];
        	$cuotas = $data[7];
        	$valor = $data[8];
        	$impuesto = $data[9];
        	$descripcion = $data[10];
        	$n_autorizacion = $data[11];
        	$msg_red = $data[12];
        	$n_recibo = $data[13];
        	$ref_1 = $data[14];
        	$ref_2 = $data[15];
        	$ref_3 = $data[16];
        	$cod_pse = $data[17];
        	$fecha_tr = $data[18];
        	$canal = $data[19];
        	$tipo_docto_cliente = $data[20];
        	$n_docto_cliente = $data[21];
        	$nombre_completo_cliente = $data[22];
        	$email_cliente = $data[23];
        	$telefono_cliente = $data[24];
        	$ciudad_cliente = $data[25];
        	$direccion_cliente = $data[26];
        	$celular_cliente = $data[27];

        	$exist = Yii::app()->db->createCommand("SELECT n_trans_interno FROM Pagos_Inteligentes..T_PAGOS WHERE n_trans_interno = ".$n_trans_interno)->queryRow();

        	if(empty($exist)){

        		$connection = Yii::app()->db;
				$command = $connection->createCommand("
				INSERT INTO Pagos_Inteligentes..T_PAGOS
				([n_trans_interno]
				,[forma_pago]
				,[ambiente]
				,[tipo_trans]
				,[tipo_tarjeta]
				,[fran_tarjeta]
				,[ult_dig_tarjeta]
				,[cuotas]
				,[valor]
				,[impuesto]
				,[descripcion]
				,[n_autorizacion]
				,[msg_red]
				,[n_recibo]
				,[ref_1]
				,[ref_2]
				,[ref_3]
				,[cod_pse]
				,[fecha_tr]
				,[canal]
				,[tipo_docto_cliente]
				,[n_docto_cliente]
				,[nombre_completo_cliente]
				,[email_cliente]
				,[telefono_cliente]
				,[ciudad_cliente]
				,[direccion_cliente]
				,[celular_cliente])
				VALUES
				(".$n_trans_interno."
				,'".$forma_pago."'
				,'".$ambiente."'
				,'".$tipo_trans."'
				,'".$tipo_tarjeta."'
				,'".$fran_tarjeta."'
				,'".$ult_dig_tarjeta."'
				,'".$cuotas."'
				,".$valor."
				,".$impuesto."
				,'".$descripcion."'
				,'".$n_autorizacion."'
				,'".$msg_red."'
				,'".$n_recibo."'
				,'".$ref_1."'
				,'".$ref_2."'
				,'".$ref_3."'
				,'".$cod_pse."'
				,'".$fecha_tr."'
				,'".$canal."'
				,'".$tipo_docto_cliente."'
				,'".$n_docto_cliente."'
				,'".$nombre_completo_cliente."'
				,'".$email_cliente."'
				,'".$telefono_cliente."'
				,'".$ciudad_cliente."'
				,'".$direccion_cliente."'
				,'".$celular_cliente."'
				)");

				$command->execute();
				$i++;

        	}

		}
		
        $msj .= $i.' Registro(s) insertados.<br>'; 	

        $resp = array('msj' => $msj);

        echo json_encode($resp);

	}

	public function actionConfirmacionPagos()
	{		
		$model=new Integ;

		$this->render('confirmacion_pagos',array(
			'model'=>$model,
		));
	}

	public function actionUploadConfirmacionPagos()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['Integ']['tmp_name']['archivo'];

		set_time_limit(0);

		spl_autoload_unregister(array('YiiBase','autoload'));  

		require_once Yii::app()->basePath . '/extensions/phpspreadsheet/vendor/autoload.php';

		spl_autoload_register(array('YiiBase','autoload'));

		$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_tmp);

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

        		$param1 = $dataExcel[$i][0]; //TypeStatus
        		$param2 = $dataExcel[$i][1]; //Status_Details
        		$param3 = $dataExcel[$i][2];  //Id_Transaccion
        		$param4 = $dataExcel[$i][3];  //Autorizacion
        		$param5 = str_replace($clean_number_caract, "", $dataExcel[$i][4]);  //Valor_Venta
        		$param6 = $dataExcel[$i][5];  //Fecha_Venta
        		$param7 = $dataExcel[$i][6];  //Medio_Pago
        		$param8 = $dataExcel[$i][7]; //Franquicia
        		$param9 = $dataExcel[$i][8]; //N_Identif
        		$param10 = $dataExcel[$i][9]; //Ref1
        		$param11 = $dataExcel[$i][10]; //Ref2
        		$param12 = $dataExcel[$i][11]; //Ref3
        		$param13 = $dataExcel[$i][12]; //Descripcion
        		$param14 = str_replace($clean_number_caract, "", $dataExcel[$i][13]); //Comision
        		$param15 = $dataExcel[$i][14]; //Porcentaje
        		$param16 = str_replace($clean_number_caract, "", $dataExcel[$i][15]); //Iva_Comision
        		$param17 = str_replace($clean_number_caract, "", $dataExcel[$i][16]); //Fee
        		$param18 = str_replace($clean_number_caract, "", $dataExcel[$i][17]); //IvaFee
        		$param19 = str_replace($clean_number_caract, "", $dataExcel[$i][18]); //ReteIca
        		$param20 = str_replace($clean_number_caract, "", $dataExcel[$i][19]); //ReteIva
        		$param21 = str_replace($clean_number_caract, "", $dataExcel[$i][20]); //ReteFTE
        		$param22 = str_replace($clean_number_caract, "", $dataExcel[$i][21]); //Gravamen
        		$param23 = str_replace($clean_number_caract, "", $dataExcel[$i][22]); //Valor_Desembolsar
        		$param24 = $dataExcel[$i][23]; //Desembolso
        		$param25 = $dataExcel[$i][24]; //Fecha_Desembolso

        		$query_exist = "SELECT Id_Transaccion FROM Tiendabinner..Confirmacion_Pagos WHERE Id_Transaccion = ".$param3;

				$row_exist =  Yii::app()->db->createCommand($query_exist)->queryRow();

				if(empty($row_exist)){
					//no existe la cabecera
				
					$command = Yii::app()->db->createCommand("
					INSERT INTO Tiendabinner..Confirmacion_Pagos
					([TypeStatus]
		           ,[Status_Details]
		           ,[Id_Transaccion]
		           ,[Autorizacion]
		           ,[Valor_Venta]
		           ,[Fecha_Venta]
		           ,[Medio_Pago]
		           ,[Franquicia]
		           ,[N_Identif]
		           ,[Ref1]
		           ,[Ref2]
		           ,[Ref3]
		           ,[Descripcion]
		           ,[Comision]
		           ,[Porcentaje]
		           ,[Iva_Comision]
		           ,[Fee]
		           ,[IvaFee]
		           ,[ReteIca]
		           ,[ReteIva]
		           ,[ReteFTE]
		           ,[Gravamen]
		           ,[Valor_Desembolsar]
		           ,[Desembolso]
		           ,[Fecha_Desembolso]
		           )
					VALUES
					('".$param1."'
		           ,'".$param2."'
		           ,".$param3."
		           ,'".$param4."'
		           ,".$param5."
		           ,'".$param6."'
		           ,'".$param7."'
		           ,'".$param8."'
		           ,'".$param9."'
		           ,'".$param10."'
		           ,'".$param11."'
		           ,'".$param12."'
		           ,'".$param13."'
		           ,".$param14."
		           ,'".$param15."'
		           ,".$param16."
		           ,".$param17."
		           ,".$param18."
		           ,".$param19."
		           ,".$param20."
		           ,".$param21."
		           ,".$param22."
		           ,".$param23."
		           ,'".$param24."'
		           ,'".$param25."'
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

	public function actionAuditoriaPedidos()
	{		
		$model=new Integ;
		$model->scenario = 'auditoria_pedidos';

		$cos = Yii::app()->db->createCommand("SELECT DISTINCT f430_id_co FROM UnoEE1..t430_cm_pv_docto")->queryAll();

		$lista_co = array();
		foreach ($cos as $co) {
			$lista_co[$co['f430_id_co']] = $co['f430_id_co'];
		}

		$tipos = Yii::app()->db->createCommand("SELECT DISTINCT f430_id_tipo_docto FROM UnoEE1..t430_cm_pv_docto")->queryAll();

		$lista_tipos = array();
		foreach ($tipos as $ti) {
			$lista_tipos[$ti['f430_id_tipo_docto']] = $ti['f430_id_tipo_docto'];
		}

		$this->render('auditoria_pedidos',array(
			'model'=>$model,
			'lista_co'=>$lista_co,
			'lista_tipos'=>$lista_tipos,
		));
	}

	public function actionAuditoriaPedidosPant()
	{		

		$co = $_POST['co'];
		$tipo = $_POST['tipo'];
		$consecutivo = $_POST['consecutivo'];

		$resultados = UtilidadesReportes::auditoriapedidospantalla($co, $tipo, $consecutivo);

		echo $resultados;
	}

	public function actionCambioFecPedXml()
	{		
		$model=new Integ;
		$model->scenario = 'cambio_fecha_pedidos_xml';

		if(isset($_POST['Integ']))
		{
			
			$command = Yii::app()->db->createCommand();
			$sql='EXEC P_CF_CONF_MOBILE_PED_ERROR';
			$command->setText($sql)->execute();

			Yii::app()->user->setFlash('success', "El proceso se ejecuto correctamente.");
			$this->redirect(array('CambioFecPedXml'));
		}

		$this->render('cambio_fecha_pedidos_xml',array(
			'model'=>$model,
		));
	}

	public function actionActReca()
	{		
		$model=new Integ;

		$this->render('act_reca',array(
			'model'=>$model,
		));
	}

	public function actionActRecaPant()
	{		
		$resultados = UtilidadesReportes::actrecapantalla();
		echo $resultados;
	}

	public function actionActualizarReCa()
	{		
		$reca = $_POST['reca'];

		$command = Yii::app()->db2->createCommand();

		$sql='  UPDATE Repositorio_Datos..T_IN_Recibos_Caja set INTEGRADO = 0 WHERE INTEGRADO = 1 and ROWID = :reca';
		$params = array(
		    "reca" => $reca,
		);
		$command->setText($sql)->execute($params);

		UtilidadesVarias::log($sql);

		Yii::app()->user->setFlash('success',"El recibo se actualizo correctamente.");
		echo 1;

	}

	public function actionElimPedido()
	{		
		$model=new Integ;
		$model->scenario = 'elim_pedido';

		if(isset($_POST['Integ']))
		{
			
			$command = Yii::app()->db->createCommand();
			$sql='EXEC P_CF_CONF_ELIM_ERROR_PED';
			$command->setText($sql)->execute();

			UtilidadesVarias::log($sql);

			Yii::app()->user->setFlash('success', "El proceso se ejecuto correctamente.");
			$this->redirect(array('ElimPedido'));
		}

		$this->render('elim_pedido',array(
			'model'=>$model,
		));
	}

	public function actionElimRecibo()
	{		
		$model=new Integ;
		$model->scenario = 'elim_recibo';

		if(isset($_POST['Integ']))
		{
			
			$command = Yii::app()->db->createCommand();
			$sql='EXEC P_CF_CONF_ELIM_ERROR_REC';
			$command->setText($sql)->execute();

			UtilidadesVarias::log($sql);

			Yii::app()->user->setFlash('success', "El proceso se ejecuto correctamente.");
			$this->redirect(array('ElimRecibo'));
		}

		$this->render('elim_recibo',array(
			'model'=>$model,
		));
	}

	public function actionListaMateriales()
	{		
		$model=new Integ;
		$model->scenario = 'lista_materiales';

		if(isset($_POST['Integ']))
		{
			$model->attributes=$_POST['Integ'];
			$this->redirect(array('integ/listamaterialesdet','i'=>$model->item));
		}

		$this->render('lista_materiales',array(
			'model'=>$model,
		));
	}

	public function actionListaMaterialesDet($i)
	{		
		$model=new Integ;
	
		$this->render('lista_materiales_det',array(
			'item'=>$i,
		));
	}

	public function actionListaMaterialesPant()
	{		
		$item = $_POST['item'];

		$resultados = UtilidadesReportes::listamaterialespantalla($item);
		echo $resultados;
	}

	public function actionCalidadPqrs()
	{		
		$model=new Integ;
		$model->scenario = 'calidad_pqrs';

		if(isset($_POST['Integ']))
		{
			$model->attributes=$_POST['Integ'];
			$this->renderPartial('calidad_pqrs_resp',array('model' => $model));	
		}

		$this->render('calidad_pqrs',array(
			'model'=>$model,		
		));
	}
	
}

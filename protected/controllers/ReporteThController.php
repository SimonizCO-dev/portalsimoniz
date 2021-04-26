<?php

class ReporteThController extends Controller
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
				'actions'=>array('empleadosactivos', 'empleadosactivospant', 'tallajeempleados', 'tallajeempleadospant', 'hijos', 'hijospant','ausencias', 'ausenciaspant', 'llamatenc', 'llamatencpant','sanciones','sancionespant','comparendos','comparendospant','contratosfinalizados','contratosfinalizadospant','importadorausencias','uploadausencias','elemherremp','elemherremppant','obscuenta','obscuentapant','elemherrpend','elemherrpendpant','importadorturnos','uploadturnos','empleadosxug','cuentas','cuentaspant','evaluac','evaluacpant'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionEmpleadosActivos()
	{		
		$model=new ReporteTh;
		$model->scenario = 'empleados_activos';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('empleados_activos_resp',array('model' => $model));	
		}

		$this->render('empleados_activos',array(
			'model'=>$model,
			'empresas'=>$empresas,
		));
	}

	public function actionEmpleadosActivosPant()
	{		
		$fecha_inicial_cont = $_POST['fecha_inicial_cont'];
		$fecha_final_cont = $_POST['fecha_final_cont'];
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }

		$resultados = UtilidadesReportesTh::empleadosactivospantalla($fecha_inicial_cont, $fecha_final_cont, $empresa);

		echo $resultados;
	}


	public function actionEmpleadosXUg()
	{		
		$model=new ReporteTh;
		$model->scenario = 'empleados_x_ug';

		$unidades_gerencia=UnidadGerencia::model()->findAll(array('condition' => 'Estado = 1', 'order'=>'Unidad_Gerencia'));

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('empleados_x_ug_resp',array('model' => $model));	
		}

		$this->render('empleados_x_ug',array(
			'model'=>$model,
			'unidades_gerencia'=>$unidades_gerencia,
		));
	}




	public function actionTallajeEmpleados()
	{		
		$model=new ReporteTh;
		$model->scenario = 'tallaje_empleados';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('tallaje_empleados_resp',array('model' => $model));	
		}

		$this->render('tallaje_empleados',array(
			'model'=>$model,
			'empresas'=>$empresas,
		));
	}

	public function actionTallajeEmpleadosPant()
	{		
		$fecha_inicial_cont = $_POST['fecha_inicial_cont'];
		$fecha_final_cont = $_POST['fecha_final_cont'];
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }

		$resultados = UtilidadesReportesTh::tallajeempleadospantalla($fecha_inicial_cont, $fecha_final_cont, $empresa);

		echo $resultados;
	}

	
	public function actionHijos()
	{		
		$model=new ReporteTh;
		$model->scenario = 'hijos';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		$generos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->genero.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('hijos_resp',array('model' => $model));	
		}

		$this->render('hijos',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'generos'=>$generos
		));
	}

	public function actionHijosPant()
	{		
		$genero = $_POST['genero'];
		$edad_inicial = $_POST['edad_inicial'];
		$edad_final = $_POST['edad_final'];
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }

		$resultados = UtilidadesReportesTh::hijospantalla($genero, $edad_inicial, $edad_final, $empresa);

		echo $resultados;
	}

	public function actionAusencias()
	{		
		$model=new ReporteTh;
		$model->scenario = 'ausencias';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		$motivos_ausencia= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_ausencia.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('ausencias_resp',array('model' => $model));	
		}

		$this->render('ausencias',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'motivos_ausencia'=>$motivos_ausencia
		));
	}

	public function actionAusenciasPant()
	{		

		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];
		$fecha_inicial_reg = $_POST['fecha_inicial_reg'];
		$fecha_final_reg = $_POST['fecha_final_reg'];
		if (isset($_POST['motivo_ausencia'])){ $motivo_ausencia = $_POST['motivo_ausencia']; } else { $motivo_ausencia = ""; }
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }
		if (isset($_POST['id_empleado'])){ $id_empleado = $_POST['id_empleado']; } else { $id_empleado = ""; }

		$resultados = UtilidadesReportesTh::ausenciaspantalla($motivo_ausencia, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado);

		echo $resultados;
	}

	public function actionLlamAtenc()
	{		
		$model=new ReporteTh;
		$model->scenario = 'llam_atenc';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		$motivos_llam_atenc= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_d_llamado_atencion.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('llam_atenc_resp',array('model' => $model));	
		}

		$this->render('llam_atenc',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'motivos_llam_atenc'=>$motivos_llam_atenc
		));
	}

	public function actionLlamAtencPant()
	{		
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];
		$fecha_inicial_reg = $_POST['fecha_inicial_reg'];
		$fecha_final_reg = $_POST['fecha_final_reg'];
		if (isset($_POST['motivo'])){ $motivo = $_POST['motivo']; } else { $motivo = ""; }
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }
		if (isset($_POST['id_empleado'])){ $id_empleado = $_POST['id_empleado']; } else { $id_empleado = ""; }

		$resultados = UtilidadesReportesTh::llamatencpantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado);

		echo $resultados;
	}

	public function actionSanciones()
	{		
		$model=new ReporteTh;
		$model->scenario = 'sanciones';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		$motivos_sanciones= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_d_sancion.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('sanciones_resp',array('model' => $model));	
		}

		$this->render('sanciones',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'motivos_sanciones'=>$motivos_sanciones
		));
	}

	public function actionSancionesPant()
	{		
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];
		$fecha_inicial_reg = $_POST['fecha_inicial_reg'];
		$fecha_final_reg = $_POST['fecha_final_reg'];
		if (isset($_POST['motivo'])){ $motivo = $_POST['motivo']; } else { $motivo = ""; }
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }
		if (isset($_POST['id_empleado'])){ $id_empleado = $_POST['id_empleado']; } else { $id_empleado = ""; }

		$resultados = UtilidadesReportesTh::sancionespantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado);

		echo $resultados;
	}

	public function actionComparendos()
	{		
		$model=new ReporteTh;
		$model->scenario = 'comparendos';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		$motivos_comparendos= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_d_comparendo.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('comparendos_resp',array('model' => $model));	
		}

		$this->render('comparendos',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'motivos_comparendos'=>$motivos_comparendos
		));
	}

	public function actionComparendosPant()
	{		
		$fecha_inicial = $_POST['fecha_inicial'];
		$fecha_final = $_POST['fecha_final'];
		$fecha_inicial_reg = $_POST['fecha_inicial_reg'];
		$fecha_final_reg = $_POST['fecha_final_reg'];
		if (isset($_POST['motivo'])){ $motivo = $_POST['motivo']; } else { $motivo = ""; }
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }
		if (isset($_POST['id_empleado'])){ $id_empleado = $_POST['id_empleado']; } else { $id_empleado = ""; }

		$resultados = UtilidadesReportesTh::comparendospantalla($motivo, $fecha_inicial, $fecha_final, $empresa, $fecha_inicial_reg, $fecha_final_reg, $id_empleado);

		echo $resultados;
	}
	
	public function actionContratosFinalizados()
	{		
		$model=new ReporteTh;
		$model->scenario = 'contratos_fin';

		$array_empresas = (Yii::app()->user->getState('array_empresas'));
		$cadena_empresas = implode(",",$array_empresas);
		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Empresa, e.Descripcion FROM T_PR_EMPRESA e WHERE e.Id_Empresa IN ('.$cadena_empresas.') ORDER BY e.Descripcion')->queryAll();

		$motivos_retiro= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE Id_Padre = '.Yii::app()->params->motivos_retiro.' AND Estado = 1 ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('contratos_fin_resp',array('model' => $model));	
		}

		$this->render('contratos_fin',array(
			'model'=>$model,
			'empresas'=>$empresas,
			'motivos_retiro'=>$motivos_retiro
		));
	}

	public function actionContratosFinalizadosPant()
	{		
		$fecha_inicial_fin = $_POST['fecha_inicial_fin'];
		$fecha_final_fin = $_POST['fecha_final_fin'];
		$liquidado = $_POST['liquidado'];

		if (isset($_POST['motivo_retiro'])){ $motivo_retiro = $_POST['motivo_retiro']; } else { $motivo_retiro = ""; }
		if (isset($_POST['empresa'])){ $empresa = $_POST['empresa']; } else { $empresa = ""; }

		$resultados = UtilidadesReportesTh::contratosfinalizadospantalla($motivo_retiro, $liquidado, $fecha_inicial_fin, $fecha_final_fin, $empresa);

		echo $resultados;
	}

	public function actionImportadorAusencias()
	{		
		$model=new ReporteTh;

		$this->render('importador_ausencias',array(
			'model'=>$model,
		));
	}

	public function actionUploadAusencias()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['ReporteTh']['tmp_name']['archivo'];
        
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
        	$msj = '<h5><i class="icon fas fa-info-circle"></i> Info</h5> El archivo esta vacio.';

        }else{

    		$opc = 1;
    	
    		//se ejecuta el sp por cada fila en el archivo

    		$msj = '<h5><i class="icon fas fa-info-circle"></i> Info</h5>';

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
        		$param10 = $dataExcel[$i][9];
        		$param11 = $dataExcel[$i][10];

        		if(is_null($param1) || is_null($param2) || is_null($param3) || is_null($param4) || is_null($param5) || is_null($param6) || is_null($param7) || is_null($param8) || is_null($param9)){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias que son requeridas.<br>'; 
        			$valid = 0;
        		}else{

        			//se valida si tipo de identificación existe

        			$identificacion = $param1;

        			$ident_emp = Yii::app()->db->createCommand("SELECT TOP 1 Id_Empleado FROM T_PR_EMPLEADO WHERE Identificacion = '".$identificacion."'")->queryRow();

					if(empty($ident_emp)){
						$fila_error = $i + 1;
						$msj .= 'Error en la fila # '.$fila_error.', el n°. de identificación '.$identificacion.' no existe.<br>'; 
					}else{

						//se valida si el empleado tiene contrato activo

						$id_emp = $ident_emp['Id_Empleado'];

						$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato, Fecha_Ingreso FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_emp.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

						

						if(empty($query_contrato)){
							$fila_error = $i + 1;
							$msj .= 'Error en la fila # '.$fila_error.', el empleado con n°. de identificación '.$identificacion.' no cuenta con un contrato activo.<br>'; 
						}else{

							$contrato_act = $query_contrato['Id_Contrato'];
							$fecha_ingreso = $query_contrato['Fecha_Ingreso'];

							$motivo = $param2;
			        		$cod_sop = $param3;
			        		$fecha_inicial = $param4;
			        		$fecha_final = $param5;
			        		$descontar = $param6;
			        		$descontar_fds = $param7;
			        		$dias = $param8;
			        		$horas = $param9;
			        		$observaciones = $param10;
			        		$notas = $param11;

			        		//se evalua si el id de motivo elegido hace parte de motivos de ausencia

			        		$q_motivo = Yii::app()->db->createCommand("SELECT TOP 1 Id_Dominio FROM T_PR_DOMINIO WHERE Id_Dominio = ".$motivo." AND Id_Padre = ".Yii::app()->params->motivos_ausencia)->queryRow();

							if(empty($q_motivo)){
								$fila_error = $i + 1;
								$msj .= 'Error en la fila # '.$fila_error.', el ID utilizado como motivo no es valido.<br>'; 
							}else{

				        		//se evalua si existe un registro creado con los mismos parametros que estan llegando para omitir la inserción

				        		$a_e = Yii::app()->db->createCommand("SELECT TOP 1 Id_Ausencia FROM T_PR_AUSENCIA_EMPLEADO WHERE Id_Empleado = ".$id_emp." AND Id_M_Ausencia = ".$motivo." AND Cod_Soporte = '".$cod_sop."' AND Descontar = ".$descontar." AND Descontar_FDS = ".$descontar_fds." AND Dias = ".$dias." AND Horas = ".$horas." AND Fecha_Inicial = '".$fecha_inicial."' AND Fecha_Final = '".$fecha_final."' AND Id_Contrato = ".$contrato_act)->queryRow();

								if(!empty($a_e)){
									$fila_error = $i + 1;
									$msj .= 'Error en la fila # '.$fila_error.', ya existe una ausencia registrada con los mismos parametros.<br>'; 
								}else{

									if($fecha_inicial < $fecha_ingreso){
										$fila_error = $i + 1;
										$msj .= 'Error en la fila # '.$fila_error.', la fecha inicial de la ausencia no puede ser menor a la fecha de ingreso del contrato.<br>'; 
									}else{

										if($fecha_inicial > date('Y-m-d')){
											$fila_error = $i + 1;
											$msj .= 'Error en la fila # '.$fila_error.', la fecha inicial no puede ser mayor a la fecha actual.<br>'; 
										}else{
										
											$model=new AusenciaEmpleado;

										 	$model->Id_Empleado = $id_emp;
										 	$model->Id_Contrato = $contrato_act;
										 	$model->Fecha_Inicial = $fecha_inicial;
										 	$model->Fecha_Final= $fecha_final;
										 	$model->Id_M_Ausencia = $motivo;
										 	$model->Cod_Soporte = strtoupper($cod_sop);
										 	$model->Descontar = $descontar;
										 	$model->Descontar_FDS = $descontar_fds;
											$model->Dias = $dias;
											$model->Horas = $horas;
											$model->Observacion = strtoupper($observaciones);
											$model->Nota = strtoupper($notas);
											$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
											$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
											$model->Fecha_Creacion = date('Y-m-d H:i:s');
											$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
											$model->save();

											$cont = $cont + 1;

										}
									}
								}	
							}
						}
        			}		        		
        		}
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	$msj .= $f.' Ausencia(s) cargada(s) correctamente.<br>'; 	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);
	}

	public function actionElemHerrEmp()
	{		
		$model=new ReporteTh;
		$model->scenario = 'elem_herr_emp';

		$this->render('elem_herr_emp',array(
			'model'=>$model,
		));

	}

	public function actionElemHerrEmpPant()
	{		
		$id_empleado = $_POST['id_empleado'];

		$resultados = UtilidadesReportesTh::elemherremppantalla($id_empleado);

		echo $resultados;
	}

	public function actionObsCuenta()
	{		
		$model=new ReporteTh;
		$model->scenario = 'obs_cuenta';

		$this->render('obs_cuenta',array(
			'model'=>$model,
		));

	}

	public function actionObsCuentaPant()
	{		
		$id_empleado = $_POST['id_empleado'];

		$resultados = UtilidadesReportesTh::obscuentapantalla($id_empleado);

		echo $resultados;
	}

	public function actionElemHerrPend()
	{		
		$model=new ReporteTh;
		$model->scenario = 'elem_herr_pend';

		$this->render('elem_herr_pend',array(
			'model'=>$model,
		));

	}

	public function actionElemHerrPendPant()
	{		
		$resultados = UtilidadesReportesTh::elemherrpendpantalla();

		echo $resultados;
	}

	public function actionImportadorTurnos()
	{		
		$model=new ReporteTh;

		$this->render('importador_turnos',array(
			'model'=>$model,
		));
	}

	public function actionUploadTurnos()
	{		
		$opc = '';
       	$msj = '';

		$file_tmp = $_FILES['ReporteTh']['tmp_name']['archivo'];
        
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
        	$msj = '<h5><i class="icon fas fa-info-circle"></i> Info</h5> El archivo esta vacio.';

        }else{

    		$opc = 1;
    	
    		//se ejecuta el sp por cada fila en el archivo

    		$msj = '<h5><i class="icon fas fa-info-circle"></i> Info</h5>';

    		for($i = 1; $i <= $filas -1 ; $i++){
        		$param1 = $dataExcel[$i][0];
        		$param2 = $dataExcel[$i][1];
        		$param3 = $dataExcel[$i][2];
        		$param4 = $dataExcel[$i][3];

        		if(is_null($param1) || is_null($param2) || is_null($param3) || is_null($param4)){
    				$fila_error = $i + 1;
        			$msj .= 'Error en la fila # '.$fila_error.', hay columnas vacias que son requeridas.<br>'; 
        			$valid = 0;
        		}else{

        			//se valida si tipo de identificación existe

        			$identificacion = $param1;

        			$ident_emp = Yii::app()->db->createCommand("SELECT TOP 1 Id_Empleado FROM T_PR_EMPLEADO WHERE Identificacion = '".$identificacion."'")->queryRow();

					if(empty($ident_emp)){
						$fila_error = $i + 1;
						$msj .= 'Error en la fila # '.$fila_error.', el n°. de identificación '.$identificacion.' no existe.<br>'; 
					}else{

						//se valida si el empleado tiene contrato activo

						$id_emp = $ident_emp['Id_Empleado'];

						$query_contrato= Yii::app()->db->createCommand('SELECT TOP 1 Id_Contrato, Fecha_Ingreso FROM T_PR_CONTRATO_EMPLEADO WHERE Id_Empleado = '.$id_emp.' AND Id_M_Retiro IS NULL ORDER BY 1 DESC')->queryRow();

						

						if(empty($query_contrato)){
							$fila_error = $i + 1;
							$msj .= 'Error en la fila # '.$fila_error.', el empleado con n°. de identificación '.$identificacion.' no cuenta con un contrato activo.<br>'; 
						}else{

							$contrato_act = $query_contrato['Id_Contrato'];
							$fecha_ingreso = $query_contrato['Fecha_Ingreso'];

							$turno = $param2;
			        		$fecha_inicial = $param3;
			        		$fecha_final = $param4;
			        	
			        		//se evalua si el id de motivo elegido hace parte de motivos de ausencia

			        		$q_motivo = Yii::app()->db->createCommand("SELECT TOP 1 Id_Turno_Trabajo FROM T_PR_TURNO_TRABAJO WHERE Id_Turno_Trabajo = ".$turno)->queryRow();

							if(empty($q_motivo)){
								$fila_error = $i + 1;
								$msj .= 'Error en la fila # '.$fila_error.', el ID utilizado como turno no es valido.<br>'; 
							}else{

				        		//se evalua si existe un registro creado con los mismos parametros que estan llegando para omitir la inserción

				        		$a_e = Yii::app()->db->createCommand("SELECT TOP 1 Id_T_Empleado FROM T_PR_TURNO_EMPLEADO WHERE Id_Empleado = ".$id_emp." AND Id_Turno = ".$turno." AND Fecha_Inicial = '".$fecha_inicial."' AND Fecha_Final = '".$fecha_final."' AND Id_Contrato = ".$contrato_act)->queryRow();

								if(!empty($a_e)){
									$fila_error = $i + 1;
									$msj .= 'Error en la fila # '.$fila_error.', ya existe un turno registrado con los mismos parametros.<br>'; 
								}else{

									if($fecha_inicial < $fecha_ingreso){
										$fila_error = $i + 1;
										$msj .= 'Error en la fila # '.$fila_error.', la fecha inicial de la ausencia no puede ser menor a la fecha de ingreso del contrato.<br>'; 
									}else{

										//se valida si el turno se sobrepone a rangos de fechas de turnos creados para el empleado

										$t_e = Yii::app()->db->createCommand("SELECT TOP 1 Id_T_Empleado FROM T_PR_TURNO_EMPLEADO WHERE Id_Empleado = ".$id_emp." AND (('".$fecha_inicial."' BETWEEN Fecha_Inicial AND Fecha_Final) OR ('".$fecha_final."' BETWEEN Fecha_Inicial AND Fecha_Final)) AND Estado = 1 AND Id_Contrato = ".$contrato_act)->queryAll();

										if(!empty($t_e)){
											$fila_error = $i + 1;
											$msj .= 'Error en la fila # '.$fila_error.', Las fechas asignadas para este turno se cruzan con uno existente.<br>'; 
										}else{

											$model=new TurnoEmpleado;

										 	$model->Id_Empleado = $id_emp;
										 	$model->Id_Contrato = $contrato_act;
										 	$model->Fecha_Inicial = $fecha_inicial;
										 	$model->Fecha_Final= $fecha_final;
										 	$model->Id_Turno = $turno;
											$model->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
											$model->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
											$model->Fecha_Creacion = date('Y-m-d H:i:s');
											$model->Fecha_Actualizacion = date('Y-m-d H:i:s');
											$model->Estado = 1;
											$model->save();

											$cont = $cont + 1;

										}
										
									}
								}	
							}
						}
        			}		        		
        		}
        	}
        }

        $f = $filas -1;

        if($f == $cont && $opc == 1){
        	$msj .= $f.' Turno(s) cargado(s) correctamente.<br>'; 	
        }

        $resp = array('opc' => $opc, 'msj' => $msj);

        echo json_encode($resp);
	}

	public function actionEvaluac()
	{		
		$model=new ReporteTh;
		$model->scenario = 'evaluac';

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('evaluac_resp');	
		}

		$this->render('evaluac',array(
			'model'=>$model,
		));
	}

	public function actionEvaluacPant()
	{		

		$resultados = UtilidadesReportesTh::evaluacpantalla();

		echo $resultados;
	}

	public function actionCuentas()
	{		
		$model=new ReporteTh;
		$model->scenario = 'cuentas';

		$dominios= Yii::app()->db->createCommand('SELECT d.Id_Dominio_Web, d.Dominio FROM T_PR_DOMINIO_WEB d WHERE d.Estado = 1 AND Id_Tipo = '.Yii::app()->params->dominios_cuenta_correo.' ORDER BY d.Dominio ')->queryAll();

		$estados= Yii::app()->db->createCommand('SELECT d.Id_Dominio, d.Dominio FROM T_PR_DOMINIO d WHERE d.Estado = 1 AND Id_Padre = '.Yii::app()->params->estado_cuenta.' ORDER BY d.Dominio')->queryAll();

		if(isset($_POST['ReporteTh']))
		{
			$model=$_POST['ReporteTh'];
			$this->renderPartial('cuentas_resp',array('model' => $model));	
		}

		$this->render('cuentas',array(
			'model'=>$model,
			'dominios'=>$dominios,
			'estados'=>$estados,
		));
	}

	public function actionCuentasPant()
	{		
		if (isset($_POST['dominio'])){ $dominio = $_POST['dominio']; } else { $dominio = ""; }
		if (isset($_POST['estado'])){ $estado = $_POST['estado']; } else { $estado = ""; }

		$resultados = UtilidadesReportesTh::cuentaspantalla($dominio, $estado);

		echo $resultados;
	}

}

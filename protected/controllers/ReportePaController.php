<?php

class ReportePaController extends Controller
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
				'actions'=>array('searchequipo', 'zipsoportes','soportesequipo','licdisp','licdisppant','licequipos','loadversiones', 'licequipospant','zipe','soportese','zipl','soportesl','licvenc','licvencpant'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionSearchEquipo(){
		$filtro = $_GET['q'];

		$b=new ReportePa;

        $data = $b->searchByEquipo($filtro);
        $result = array();
        foreach($data as $item):
           $result[] = array(
               'id'   => $item['Id_Equipo'],
               'text' => $item['Serial'].' ('.$item['T_Equipo'].' - '.$item['Empresa'].')',
           );
        endforeach;
        header('Content-type: application/json');
        echo CJSON::encode( $result );
        Yii::app()->end(); 
 	}

 	public function actionZipSoportes()
	{		
		$model=new ReportePa;
		$model->scenario = 'zip_soportes';

		$tipos_equipo= Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->tipo_equipo.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$clases_licencias= Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->clase_licencia.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Pa_Empresa, e.Descripcion FROM T_PR_PA_EMPRESA e WHERE e.Estado = 1 AND e.Tipo = '.Yii::app()->params->empresa_nac.' ORDER BY e.Descripcion')->queryAll();

		$this->render('zip_soportes',array(
			'model'=>$model,
			'tipos_equipo'=>$tipos_equipo,
			'clases_licencias'=>$clases_licencias,
			'empresas'=>$empresas,
		));
	}


	public function actionSoportesEquipo()
	{

		$opc = $_POST['opc'];
		$serial = $_POST['serial'];
		$fecha_compra_inicial = $_POST['fecha_compra_inicial'];
		$fecha_compra_final = $_POST['fecha_compra_final'];
		$empresa_compra = $_POST['empresa_compra'];
		$tipos_equipo = $_POST['tipos_equipo'];
		$inc_lic = $_POST['inc_lic'];  

		if($opc == 1){
		  //individual

		  $query = "SELECT Id_Equipo, Serial, Doc_Soporte FROM T_PR_EQUIPO WHERE Estado = 1";

		  if($serial != ""){
    		$query .= " AND Id_Equipo = ".$serial;  
		  }

		}

		if($opc == 2){
		  //grupal

		  $query = "SELECT Id_Equipo, Serial, Doc_Soporte FROM T_PR_EQUIPO WHERE Estado = 1";

		  if($fecha_compra_inicial != "" && $fecha_compra_final != ""){
		    $query .= " AND Fecha_Compra BETWEEN '".$fecha_compra_inicial."' AND '".$fecha_compra_final."'";  
		  }else{
		    if($fecha_compra_inicial != "" && $fecha_compra_final == ""){
      		$query .= " AND Fecha_Compra = '".$fecha_compra_inicial."'";  
		    } 
		  }

		  if($empresa_compra != ""){
				$query .= " AND Empresa_Compra = ".$empresa_compra;  
		  }

		  if($tipos_equipo != ""){
		    $query .= " AND Tipo_Equipo IN (".$tipos_equipo.")"; 
		  }
		}

		$q_equipos = Yii::app()->db->createCommand($query)->queryAll();

		if(empty($q_equipos)){
			$resp = 0;
			$msg = "No se encontraron soportes de equipos / licencias con los filtros aplicados.";
			$ruta = "";
			$archivo = "";
			$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
    		echo json_encode($resp);
		}else{

			//se empieza a generar el zip de acuerdo a los equipos y licencias relacionadas

			$array_ids = array();
			$array_seriales_equipos = array();
			$array_soportes_equipos = array();

			$array_seriales = array();
			$array_soportes = array();


			$q_clases_lic = Yii::app()->db->createCommand("SELECT Id_Dominio, Dominio FROM T_PR_DOMINIO WHERE Id_Dominio IN (".$inc_lic.")")->queryAll(); 

		    foreach ($q_clases_lic as $cl) {

		    	$clase = $cl['Dominio'];
		    	$array_seriales[$clase] = array();
		    	$array_soportes[$clase] = array();
			}

			//equipos

			foreach ($q_equipos as $reg) {

			  $Id_Equipo = $reg['Id_Equipo'];
			  $Serial = $reg['Serial'];
			  $Doc_Soporte_Equipo = $reg['Doc_Soporte'];

			  array_push($array_ids, $Id_Equipo);		 

			  $ruta_sop_equipo = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/equipos/'.$Doc_Soporte_Equipo;
			        
			  if(file_exists($ruta_sop_equipo)){
			  	array_push($array_seriales_equipos, $Serial);
			    array_push($array_soportes_equipos, $Doc_Soporte_Equipo);
			  }

			  //licencias

		    foreach ($q_clases_lic as $cl) {

		    	$id_clase = $cl['Id_Dominio'];
		    	$clase = $cl['Dominio'];

	    		$sop_lic = Yii::app()->db->createCommand("
	    			SELECT 
	    			L.Id_Licencia,
	    			L.Doc_Soporte,
	    			L.Doc_Soporte2
	    			FROM T_PR_LICENCIA_EQUIPO LE
						LEFT JOIN T_PR_LICENCIA L ON LE.Id_Licencia = L.Id_Lic
	    			WHERE L.Clasificacion = ".$id_clase." AND L.Estado = ".Yii::app()->params->estado_lic_act." AND LE.Estado = 1 AND LE.Id_Equipo = ".$Id_Equipo."
	    		")->queryAll();



	    		if(!empty($sop_lic)){
	    			foreach ($sop_lic as $licen) {

		  				$Doc_Soporte_SO = $licen['Doc_Soporte'];
		  				$Doc_Soporte_SO2 = $licen['Doc_Soporte2'];

  						$ruta_sop_so = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$Doc_Soporte_SO;

  						if(file_exists($ruta_sop_so)){
						  	array_push($array_seriales[$clase], $Serial);
						    array_push($array_soportes[$clase], $Doc_Soporte_SO);
						  }

						  if($Doc_Soporte_SO2 != ""){
				  			$ruta_sop_so2 = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$Doc_Soporte_SO2;

	  						if(file_exists($ruta_sop_so2)){
							  	array_push($array_seriales[$clase], $Serial);
							    array_push($array_soportes[$clase], $Doc_Soporte_SO2);
							  }
						  }
		  			}
	    		}
			  }
			}

			//se recorre el array
			if(!empty($array_seriales_equipos)){

				//se borran los archivos .zip y .txt que estaban en la carpeta

				$files = glob(Yii::app()->basePath.'/../tmp/zip/*'); //obtenemos todos los nombres de los ficheros
				foreach($files as $file){
				    if(is_file($file))
				    	unlink($file); //elimino el fichero
				}

				//se crea el texto especificando que filtros de busqueda se utilizaron
		  	$txt_info = fopen(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt', 'w');

		  	fwrite($txt_info, "Filtros de busqueda: " . PHP_EOL);

		  	if($opc == 1){
			  	//individual

			  	if($serial != ""){
			  		$modelo_equipo = Equipo::model()->findByPk($serial);
			    	fwrite($txt_info, "Serial:  ".$modelo_equipo->Serial. PHP_EOL);
			  	} 

				}

				if($opc == 2){
				  //grupal

				  if($fecha_compra_inicial != "" && $fecha_compra_final != ""){
				    fwrite($txt_info, "Fecha de compra:  ".$fecha_compra_inicial." Al ".$fecha_compra_final .PHP_EOL);
				  }else{
				    if($fecha_compra_inicial != "" && $fecha_compra_final == ""){
		      		fwrite($txt_info, "Fecha de compra:  ".$fecha_compra_inicial .PHP_EOL);  
				    } 
				  }

				  if($empresa_compra != ""){
				  	$modelo_empresa = Empresa::model()->findByPk($empresa_compra);
						fwrite($txt_info, "Empresa de compra:  ".$modelo_empresa->Descripcion. PHP_EOL); 
				  }

				  if($tipos_equipo != ""){
				    $query .= " AND Tipo_Equipo IN (".$tipos_equipo.")";
				    $q_tipos_equipo = Yii::app()->db->createCommand("SELECT Dominio FROM T_PR_DOMINIO WHERE Id_Dominio IN (".$tipos_equipo.")")->queryAll(); 

				    $cadena_te = "";

				    foreach ($q_tipos_equipo as $tipo) {

					  	$cadena_te .= $tipo['Dominio'].", ";

					  }

					  fwrite($txt_info, "Tipo(s) de equipo:  ".substr($cadena_te, 0, -2). PHP_EOL);
			 	 		
				  }
				
				}

		    $cadena_lic = "";

		    foreach ($q_clases_lic as $clase) {

			  	$cadena_lic .= $clase['Dominio'].", ";

			  }

			  fwrite($txt_info, "Incluir licencia(s):  ".substr($cadena_lic, 0, -2). PHP_EOL);

				fclose($txt_info); 

				$rnd = rand(0,99999);
				$id = $rnd;
				$ruta_zip = Yii::app()->basePath.'/../tmp/zip/'.$id.'.zip';

				//se declara un nuevo objeto ZIP
				$zip = new ZipArchive(); 

		  	$zip->open($ruta_zip, ZipArchive::CREATE);

  			//se añade el directorio de equipos dentro del zip
		  	$dir_equipos = 'EQUIPOS';
		  	$zip->addEmptyDir($dir_equipos);

		  	foreach ($array_soportes_equipos as $key => $value) {
		    
		    	$soporte_equipo = $value;
		    	$info_soporte_equipo = new SplFileInfo($soporte_equipo);
		    	$ext = $info_soporte_equipo->getExtension();

		    	$zip->addFile(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/equipos/'.$value, $dir_equipos.'/'.trim($array_seriales_equipos[$key].'.'.$ext));

		  	}

		  	foreach ($q_clases_lic as $cl) {

		    	$clase = $cl['Dominio'];

					if(!empty($array_soportes[$clase])){

					$dir = $clase;
			  		$zip->addEmptyDir($dir);

			  		$serial_act = "";

			  		$c = 1;

				  	foreach ($array_soportes[$clase] as $key => $value) {

					    $soporte_so = $value;
					    $info_soporte_so = new SplFileInfo($soporte_so);
					    $ext = $info_soporte_so->getExtension();

					    $serial = $array_seriales[$clase][$key];

					    if($serial == $serial_act){
					    	$c = $c + 1;
					    	$zip->addFile(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$value, $dir.'/'.trim($array_seriales[$clase][$key].'_'.$c.'.'.$ext));
					    }else{
					    	$c = 1;
					    	$zip->addFile(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$value, $dir.'/'.trim($array_seriales[$clase][$key].'_'.$c.'.'.$ext));
					    }

					    $serial_act = $serial;

					  }

					}

			  }
			  
			  //se añade txt con info de los filtros aplicados en el reporte
			  $zip->addFile(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt', 'filtro_busqueda.txt');
	 
			  //se cierra el zip
			  $zip->close();

			  //se elimina el archivos con info de los filtros de la carpeta temporal
			  unlink(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt');

				$resp = 1;
				$msg = "Zip generado correctamente.";
				$ruta = "/simoniz/tmp/zip/".$id.".zip";
				$archivo = "Soportes_".date('Y_m_d_H_i_s').".zip";
				$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
		    echo json_encode($resp);
			}
		}
	}

	public function actionLicDisp()
	{		
		$model=new ReportePa;
		$model->scenario = 'lic_disp';

		$clasif_lic = Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->clase_licencia.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$empresas = Yii::app()->db->createCommand('SELECT e.Id_Pa_Empresa, e.Descripcion FROM T_PR_PA_EMPRESA e WHERE e.Estado = 1 AND e.Tipo = '.Yii::app()->params->empresa_nac.' ORDER BY e.Descripcion')->queryAll();

		if(isset($_POST['ReportePa']))
		{
			$model=$_POST['ReportePa'];
			$this->renderPartial('lic_disp_resp',array('model' => $model));	
		}

		$this->render('lic_disp',array(
			'model'=>$model,
			'clasif_lic'=>$clasif_lic,
			'empresas'=>$empresas,
		));
	}

	public function actionLicDispPant()
	{		
		$empresa_compra = $_POST['empresa_compra'];
		$clasif = $_POST['clasif'];

		$resultados = UtilidadesReportesPa::licdisppantalla($empresa_compra, $clasif);
		echo $resultados;
	}

	public function actionLicEquipos()
	{		
		$model=new ReportePa;
		$model->scenario = 'lic_equipos';

		$tipos_equipo= Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->tipo_equipo.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Pa_Empresa, e.Descripcion FROM T_PR_PA_EMPRESA e WHERE e.Estado = 1 AND e.Tipo = '.Yii::app()->params->empresa_nac.' ORDER BY e.Descripcion')->queryAll();

		if(isset($_POST['ReportePa']))
		{
			$model=$_POST['ReportePa'];
			$this->renderPartial('lic_equipos_resp',array('model' => $model));	
		}

		$this->render('lic_equipos',array(
			'model'=>$model,
			'tipos_equipo'=>$tipos_equipo,
			'empresas'=>$empresas,
		));
	}

	public function actionLoadVersiones()
	{
		$tipo = $_POST['tipo'];
 
		$versiones = Yii::app()->db->createCommand("SELECT Id_Dominio, Dominio FROM T_PR_DOMINIO WHERE Id_Padre = '".$tipo."' AND Estado = 1 ORDER BY Dominio")->queryAll();

		$i = 0;
		$array_versiones = array();
		
		foreach ($versiones as $v) {
			$array_versiones[$i] = array('id' => $v['Id_Dominio'],  'text' => $v['Dominio']);
			$i++; 

		}
		
		//se retorna un json con las opciones
		echo json_encode($array_versiones);

	}

	public function actionLicEquiposPant()
	{		
		$empresa_compra = $_POST['empresa_compra'];
		$tipo_equipo = $_POST['tipo_equipo'];
		$tipo_licencia = $_POST['tipo_licencia'];
		$version = $_POST['version'];

		$resultados = UtilidadesReportesPa::licequipospantalla($empresa_compra, $tipo_equipo, $tipo_licencia, $version);
		echo $resultados;
	}

	public function actionZipE()
	{		
		$model=new ReportePa;
		$model->scenario = 'zip_e';

		$tipos_equipo= Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->tipo_equipo.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Pa_Empresa, e.Descripcion FROM T_PR_PA_EMPRESA e WHERE e.Estado = 1 AND e.Tipo = '.Yii::app()->params->empresa_nac.' ORDER BY e.Descripcion')->queryAll();

		$this->render('zip_e',array(
			'model'=>$model,
			'tipos_equipo'=>$tipos_equipo,
			'empresas'=>$empresas,
		));
	}


	public function actionSoportesE()
	{

		$opc = $_POST['opc'];
		$serial = $_POST['serial'];
		$fecha_compra_inicial = $_POST['fecha_compra_inicial'];
		$fecha_compra_final = $_POST['fecha_compra_final'];
		$empresa_compra = $_POST['empresa_compra'];
		$tipos_equipo = $_POST['tipos_equipo'];

		if($opc == 1){
	  		//individual

		  	$query = "SELECT Id_Equipo, Serial, Doc_Soporte FROM T_PR_EQUIPO WHERE Estado = 1";

		  	if($serial != ""){
    			$query .= " AND Id_Equipo = ".$serial;  
		  	}

		}

		if($opc == 2){
	  		//grupal

			$query = "SELECT Id_Equipo, Serial, Doc_Soporte FROM T_PR_EQUIPO WHERE Estado = 1";

			if($fecha_compra_inicial != "" && $fecha_compra_final != ""){
		    	$query .= " AND Fecha_Compra BETWEEN '".$fecha_compra_inicial."' AND '".$fecha_compra_final."'";  
		  	}else{
		    	if($fecha_compra_inicial != "" && $fecha_compra_final == ""){
      				$query .= " AND Fecha_Compra = '".$fecha_compra_inicial."'";  
 				} 
		  	}

		  	if($empresa_compra != ""){
				$query .= " AND Empresa_Compra = ".$empresa_compra;  
		  	}

		  	if($tipos_equipo != ""){
		    	$query .= " AND Tipo_Equipo IN (".$tipos_equipo.")"; 
		  	}
		}

		$q_equipos = Yii::app()->db->createCommand($query)->queryAll();

		if(empty($q_equipos)){
		
			$resp = 0;
			$msg = "No se encontraron equipos con los filtros aplicados.";
			$ruta = "";
			$archivo = "";
			$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
    		echo json_encode($resp);
		
		}else{

			//se empieza a generar el zip de acuerdo a los equipos y licencias relacionadas

			$array_ids = array();
			$array_seriales_equipos = array();
			$array_soportes_equipos = array();

			//equipos

			foreach ($q_equipos as $reg) {

		  		$Id_Equipo = $reg['Id_Equipo'];
			  	$Serial = $reg['Serial'];
			  	$Doc_Soporte_Equipo = $reg['Doc_Soporte'];

			  	array_push($array_ids, $Id_Equipo);		 

			  	$ruta_sop_equipo = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/equipos/'.$Doc_Soporte_Equipo;
			        
			  	if(file_exists($ruta_sop_equipo)){
			  		array_push($array_seriales_equipos, $Serial);
			    	array_push($array_soportes_equipos, $Doc_Soporte_Equipo);
			  	}

			}

			//se recorre el array
			if(!empty($array_seriales_equipos)){

				//se borran los archivos .zip y .txt que estaban en la carpeta

				$files = glob(Yii::app()->basePath.'/../tmp/zip/*'); //obtenemos todos los nombres de los ficheros
				foreach($files as $file){
				    if(is_file($file))
				    	unlink($file); //elimino el fichero
				}

				//se crea el texto especificando que filtros de busqueda se utilizaron
		  		$txt_info = fopen(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt', 'w');

		  		fwrite($txt_info, "Filtros de busqueda: " . PHP_EOL);

		  		if($opc == 1){
			  		//individual

			  		if($serial != ""){
			  			$modelo_equipo = Equipo::model()->findByPk($serial);
			    		fwrite($txt_info, "Serial:  ".$modelo_equipo->Serial. PHP_EOL);
			  		} 

				}

				if($opc == 2){
			  		//grupal

				  	if($fecha_compra_inicial != "" && $fecha_compra_final != ""){
				    	fwrite($txt_info, "Fecha de compra:  ".$fecha_compra_inicial." Al ".$fecha_compra_final .PHP_EOL);
				  	}else{
				    	if($fecha_compra_inicial != "" && $fecha_compra_final == ""){
		      				fwrite($txt_info, "Fecha de compra:  ".$fecha_compra_inicial .PHP_EOL);  
				    	} 
				  	}

				  	if($empresa_compra != ""){
				  		$modelo_empresa = Empresa::model()->findByPk($empresa_compra);
						fwrite($txt_info, "Empresa de compra:  ".$modelo_empresa->Descripcion. PHP_EOL); 
				  	}

				  	if($tipos_equipo != ""){
				    	$query .= " AND Tipo_Equipo IN (".$tipos_equipo.")";
				    	$q_tipos_equipo = Yii::app()->db->createCommand("SELECT Dominio FROM T_PR_DOMINIO WHERE Id_Dominio IN (".$tipos_equipo.")")->queryAll(); 

				    	$cadena_te = "";	

				    	foreach ($q_tipos_equipo as $tipo) {

					  		$cadena_te .= $tipo['Dominio'].", ";

					  	}

					  	fwrite($txt_info, "Tipo(s) de equipo:  ".substr($cadena_te, 0, -2). PHP_EOL);
			 	 		
				 	}
				
				}

		   
				fclose($txt_info); 

				$rnd = rand(0,99999);
				$id = $rnd;
				$ruta_zip = Yii::app()->basePath.'/../tmp/zip/'.$id.'.zip';

				//se declara un nuevo objeto ZIP
				$zip = new ZipArchive(); 
		  		$zip->open($ruta_zip, ZipArchive::CREATE);

  				//se añade el directorio de equipos dentro del zip
		  		$dir_equipos = 'EQUIPOS';
		  		$zip->addEmptyDir($dir_equipos);

		  		foreach ($array_soportes_equipos as $key => $value) {
		    
			    	$soporte_equipo = $value;
			    	$info_soporte_equipo = new SplFileInfo($soporte_equipo);
			    	$ext = $info_soporte_equipo->getExtension();

		    		$zip->addFile(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/equipos/'.$value, $dir_equipos.'/'.trim($array_seriales_equipos[$key].'.'.$ext));

		  		}
			  
			  	//se añade txt con info de los filtros aplicados en el reporte
			  	$zip->addFile(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt', 'filtro_busqueda.txt');
	 
			  	//se cierra el zip
			  	$zip->close();

			  	//se elimina el archivos con info de los filtros de la carpeta temporal
			  	unlink(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt');

				$resp = 1;
				$msg = "Zip generado correctamente.";
				$ruta = "/simoniz/tmp/zip/".$id.".zip";
				$archivo = "Soportes_equipos_".date('Y_m_d_H_i_s').".zip";
				$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
		    	echo json_encode($resp);
			}else{
				$resp = 0;
				$msg = "No se encontraron soportes de equipos con los filtros aplicados.";
				$ruta = "";
				$archivo = "";
				$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
	    		echo json_encode($resp);
			}
		}
	}

	public function actionZipL()
	{		
		$model=new ReportePa;
		$model->scenario = 'zip_l';

		$clases_licencias= Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->clase_licencia.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$empresas= Yii::app()->db->createCommand('SELECT e.Id_Pa_Empresa, e.Descripcion FROM T_PR_PA_EMPRESA e WHERE e.Estado = 1 AND e.Tipo = '.Yii::app()->params->empresa_nac.' ORDER BY e.Descripcion')->queryAll();

		$this->render('zip_l',array(
			'model'=>$model,
			'clases_licencias'=>$clases_licencias,
			'empresas'=>$empresas,
		));
	}


	public function actionSoportesL()
	{

		$opc = $_POST['opc'];
		$serial = $_POST['serial'];
		$fecha_compra_inicial = $_POST['fecha_compra_inicial'];
		$fecha_compra_final = $_POST['fecha_compra_final'];
		$empresa_compra = $_POST['empresa_compra'];
		$inc_lic = $_POST['inc_lic'];

		$q_clases_lic = Yii::app()->db->createCommand("SELECT Id_Dominio, Dominio FROM T_PR_DOMINIO WHERE Id_Dominio IN (".$inc_lic.")")->queryAll(); 

	    foreach ($q_clases_lic as $cl) {

	    	$clase = $cl['Dominio'];
	    	$array_seriales[$clase] = array();
	    	$array_soportes[$clase] = array();
		}


		if($opc == 1){
		  //individual

		  $query = "
		  	SELECT 
			LE.Id_Equipo,
			E.Serial,
			LE.Id_Licencia,
			L.Clasificacion,
			C.Dominio AS Clas,
			L.Doc_Soporte,
			L.Doc_Soporte2
			FROM T_PR_LICENCIA_EQUIPO LE
			LEFT JOIN T_PR_LICENCIA L ON LE.Id_Licencia = L.Id_Lic
			LEFT JOIN T_PR_EQUIPO E ON LE.Id_Equipo = E.Id_Equipo
			LEFT JOIN T_PR_DOMINIO C ON L.Clasificacion = C.Id_Dominio
			WHERE E.Estado = 1 AND LE.Estado = 1
		  ";

		  if($serial != ""){
    		$query .= " AND E.Id_Equipo = ".$serial;  
		  }

		  if($inc_lic != ""){
		    $query .= " AND L.Clasificacion IN (".$inc_lic.")"; 
		  }

		  $query .= " ORDER BY LE.Id_Equipo, C.Dominio"; 

		}

		if($opc == 2){
		  //grupal

		  $query = "
		  	SELECT 
			LE.Id_Equipo,
			E.Serial,
			LE.Id_Licencia,
			L.Clasificacion,
			C.Dominio AS Clas,
			L.Doc_Soporte,
			L.Doc_Soporte2
			FROM T_PR_LICENCIA_EQUIPO LE
			LEFT JOIN T_PR_LICENCIA L ON LE.Id_Licencia = L.Id_Lic
			LEFT JOIN T_PR_EQUIPO E ON LE.Id_Equipo = E.Id_Equipo
			LEFT JOIN T_PR_DOMINIO C ON L.Clasificacion = C.Id_Dominio
			WHERE E.Estado = 1 AND LE.Estado = 1
		  ";

		  if($fecha_compra_inicial != "" && $fecha_compra_final != ""){
		    $query .= " AND L.Fecha_Factura BETWEEN '".$fecha_compra_inicial."' AND '".$fecha_compra_final."'";  
		  }else{
		    if($fecha_compra_inicial != "" && $fecha_compra_final == ""){
      		$query .= " AND L.Fecha_Factura = '".$fecha_compra_inicial."'";  
		    } 
		  }

		  if($empresa_compra != ""){
				$query .= " AND L.Empresa_Compra = ".$empresa_compra;  
		  }

		  if($inc_lic != ""){
		    $query .= " AND L.Clasificacion IN (".$inc_lic.")"; 
		  }

		  $query .= " ORDER BY LE.Id_Equipo, C.Dominio"; 

		}

		$q_licencias = Yii::app()->db->createCommand($query)->queryAll();

		if(!empty($q_licencias)){

			foreach ($q_licencias as $reg) {

		    	$clase = $reg['Clas'];
		    	$Serial = $reg['Serial'];
		    	$Doc_Soporte = $reg['Doc_Soporte'];
		  		$Doc_Soporte2 = $reg['Doc_Soporte2'];

				$ruta_sop_so = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$Doc_Soporte;

				if(file_exists($ruta_sop_so)){
				  	array_push($array_seriales[$clase], $Serial);
				    array_push($array_soportes[$clase], $Doc_Soporte);
				}

			  	if($Doc_Soporte2 != ""){
		  			$ruta_sop_so2 = Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$Doc_Soporte2;

					if(file_exists($ruta_sop_so2)){
					  	array_push($array_seriales[$clase], $Serial);
					    array_push($array_soportes[$clase], $Doc_Soporte2);
					}
			  	}
		  				
	  		}

			//se borran los archivos .zip y .txt que estaban en la carpeta

			$files = glob(Yii::app()->basePath.'/../tmp/zip/*'); //obtenemos todos los nombres de los ficheros
			foreach($files as $file){
			    if(is_file($file))
			    	unlink($file); //elimino el fichero
			}

			//se crea el texto especificando que filtros de busqueda se utilizaron
		  	$txt_info = fopen(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt', 'w');

		  	fwrite($txt_info, "Filtros de busqueda: " . PHP_EOL);

		  	if($opc == 1){
			  	//individual

			  	if($serial != ""){
			  		$modelo_equipo = Equipo::model()->findByPk($serial);
			    	fwrite($txt_info, "Serial:  ".$modelo_equipo->Serial. PHP_EOL);
			  	}

			  	if($inc_lic != ""){

			  		$cadena_lic = "";

		    		foreach ($q_clases_lic as $clase) {

			  			$cadena_lic .= $clase['Dominio'].", ";

			  		}

			 	 	fwrite($txt_info, "Incluir licencia(s):  ".substr($cadena_lic, 0, -2). PHP_EOL);

			 	 }


			}

			if($opc == 2){
			  	//grupal

				if($fecha_compra_inicial != "" && $fecha_compra_final != ""){
					fwrite($txt_info, "Fecha de compra:  ".$fecha_compra_inicial." Al ".$fecha_compra_final .PHP_EOL);
				}else{
				    if($fecha_compra_inicial != "" && $fecha_compra_final == ""){
		      			fwrite($txt_info, "Fecha de compra:  ".$fecha_compra_inicial .PHP_EOL);  
				    } 
			  	}

		  		if($empresa_compra != ""){
				  	$modelo_empresa = Empresa::model()->findByPk($empresa_compra);
					fwrite($txt_info, "Empresa de compra:  ".$modelo_empresa->Descripcion. PHP_EOL); 
			  	}

				if($inc_lic != ""){

			  		$cadena_lic = "";

		    		foreach ($q_clases_lic as $clase) {

			  			$cadena_lic .= $clase['Dominio'].", ";

			  		}

			 	 	fwrite($txt_info, "Incluir licencia(s):  ".substr($cadena_lic, 0, -2). PHP_EOL);

			 	}
				
			}


			fclose($txt_info); 

			$rnd = rand(0,99999);
			$id = $rnd;
			$ruta_zip = Yii::app()->basePath.'/../tmp/zip/'.$id.'.zip';

			//se declara un nuevo objeto ZIP
			$zip = new ZipArchive(); 
	  		$zip->open($ruta_zip, ZipArchive::CREATE);

	  		foreach ($q_clases_lic as $cl) {

		    	$clase = $cl['Dominio'];

				if(!empty($array_soportes[$clase])){

					$dir = $clase;
					$zip->addEmptyDir($dir);

					$serial_act = "";

					$c = 1;

			  	foreach ($array_soportes[$clase] as $key => $value) {

				    $soporte_so = $value;
				    $info_soporte_so = new SplFileInfo($soporte_so);
				    $ext = $info_soporte_so->getExtension();

				    $serial = $array_seriales[$clase][$key];

				    if($serial == $serial_act){
				    	$c = $c + 1;
				    	$zip->addFile(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$value, $dir.'/'.trim($array_seriales[$clase][$key].'_'.$c.'.'.$ext));
				    }else{
				    	$c = 1;
				    	$zip->addFile(Yii::app()->basePath.'/../files/panel_adm/docs_equipos_licencias/licencias/'.$value, $dir.'/'.trim($array_seriales[$clase][$key].'_'.$c.'.'.$ext));
				    }

				    $serial_act = $serial;

				  }

				}

			}
		  
		  	//se añade txt con info de los filtros aplicados en el reporte
		  	$zip->addFile(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt', 'filtro_busqueda.txt');
 
		  	//se cierra el zip
		  	$zip->close();

		  	//se elimina el archivos con info de los filtros de la carpeta temporal
		  	unlink(Yii::app()->basePath.'/../tmp/zip/filtro_busqueda.txt');

			$resp = 1;
			$msg = "Zip generado correctamente.";
			$ruta = "/simoniz/tmp/zip/".$id.".zip";
			$archivo = "Soportes_licencias_".date('Y_m_d_H_i_s').".zip";
			$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
	    	echo json_encode($resp);

	  	}else{
	  		$resp = 0;
			$msg = "No se encontraron licencias con los filtros aplicados.";
			$ruta = "";
			$archivo = "";
			$resp = array('resp' => $resp, 'msg' => $msg, 'ruta' => $ruta, 'archivo' => $archivo);
    		echo json_encode($resp);	
	  	}

  			 
	}

	public function actionLicVenc()
	{		
		$model=new ReportePa;
		$model->scenario = 'lic_venc';

		$clasif_lic = Yii::app()->db->createCommand('SELECT te.Id_Dominio, te.Dominio FROM T_PR_DOMINIO te WHERE te.Id_Padre = '.Yii::app()->params->clase_licencia.' AND te.Estado = 1 ORDER BY te.Dominio')->queryAll();

		$empresas = Yii::app()->db->createCommand('SELECT e.Id_Pa_Empresa, e.Descripcion FROM T_PR_PA_EMPRESA e WHERE e.Estado = 1 AND e.Tipo = '.Yii::app()->params->empresa_nac.' ORDER BY e.Descripcion')->queryAll();

		if(isset($_POST['ReportePa']))
		{
			$model=$_POST['ReportePa'];
			$this->renderPartial('lic_venc_resp',array('model' => $model));	
		}

		$this->render('lic_venc',array(
			'model'=>$model,
			'clasif_lic'=>$clasif_lic,
			'empresas'=>$empresas,
		));
	}

	public function actionLicVencPant()
	{		
		$empresa_compra = $_POST['empresa_compra'];
		$clasif = $_POST['clasif'];

		$resultados = UtilidadesReportesPa::licvencpantalla($empresa_compra, $clasif);
		echo $resultados;
	}

}

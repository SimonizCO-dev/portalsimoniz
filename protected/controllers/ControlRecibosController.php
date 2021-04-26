<?php

class ControlRecibosController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('cargarRecibosverif','cargarRecibosaplic','cargarRecibosentfis','viewrecibo','actestados','revrec','modrec','update','rotateimage'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin', 'export', 'exportexcel'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionRevRec($id)
	{
		$model=$this->loadModel($id);

		//recibo verificado
		if($model->Opc == 2){
			$opc = $model->Opc;
			$usuario_verif = $model->Id_Usuario_Verif;
			$fecha_hora_verif = $model->Fecha_Hora_Verif;
			$usuario_aplic = NULL;
			$fecha_hora_aplic = NULL;
		}

		//recibo aplicado
		if($model->Opc == 3){
			$opc = $model->Opc;
			$usuario_verif = $model->Id_Usuario_Verif;
			$fecha_hora_verif = $model->Fecha_Hora_Verif;
			$usuario_aplic =$model->Id_Usuario_Aplic;
			$fecha_hora_aplic = $model->Fecha_Hora_Aplic;
		}

		$model->Opc = 1;
		$model->Verificacion = null;
		$model->Id_Usuario_Verif = null;
		$model->Fecha_Hora_Verif = null;
		$model->Id_Usuario_Aplic = null;
		$model->Fecha_Hora_Aplic = null;
		$model->Fecha_Banco = null;
		$model->Banco_Correcto = null;
		$model->Motivo_Rechazo = null;

		if($model->save()){
			
			$nueva_rev = new RevRecibos;
			$nueva_rev->Id_Control = $id;
			$nueva_rev->Id_Usuario_Rev = Yii::app()->user->getState('id_user');
			$nueva_rev->Fecha_Hora_Rev = date('Y-m-d H:i:s');
			$nueva_rev->Opc = $opc;
			$nueva_rev->Id_Usuario_Verif = $usuario_verif;
			$nueva_rev->Fecha_Hora_Verif = $fecha_hora_verif;
			$nueva_rev->Id_Usuario_Aplic = $usuario_aplic;
			$nueva_rev->Fecha_Hora_Aplic = $fecha_hora_aplic;

			$nueva_rev->save();

			Yii::app()->user->setFlash('success', "El recibo ".$model->Recibo." fue revertido correctamente.");
			$this->redirect(array('actestados'));
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if(Yii::app()->request->getParam('export')) {
    		$this->actionExport();
    		Yii::app()->end();
		}

		$model=new ControlRecibos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ControlRecibos']))
			$model->attributes=$_GET['ControlRecibos'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionActEstados()
	{
		$model=new ControlRecibos('search_estados');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ControlRecibos']))
			$model->attributes=$_GET['ControlRecibos'];

		$this->render('act_estados',array(
			'model'=>$model,
		));
	}


	public function actionModRec()
	{
		$model=new ControlRecibos('search_mod_rec');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ControlRecibos']))
			$model->attributes=$_GET['ControlRecibos'];

		$this->render('mod_rec',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		
		$opc = 0;

		$model=$this->loadModel($id);

		$ruta_imagen_actual = Yii::app()->basePath.'/../files/control_recibos/recibos/'.$model->Url;

		$rec = $model->Recibo;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ControlRecibos']))
		{
			if($_FILES['ControlRecibos']['name']['Recibo']  != "") {

				$imagen_subida = CUploadedFile::getInstance($model,'Recibo');
				$ext = $imagen_subida->getExtensionName();

	            $nombre_archivo = "{$rec}.{$ext}"; 
	            $model->Recibo = $rec;
	            $model->Url = $nombre_archivo;
	            $opc = 1;
		    } 

			$model->Id_Usuario_Carga = Yii::app()->user->getState('id_user');
			$model->Fecha_Hora_Carga = date('Y-m-d H:i:s');
 
            if($model->save()){
            	if($opc == 1){
            		unlink($ruta_imagen_actual);
                	$imagen_subida->saveAs(Yii::app()->basePath.'/../files/control_recibos/recibos/'.$nombre_archivo);
            	}
            	Yii::app()->user->setFlash('success', "Recibo ".$rec." modificado.");
                $this->redirect(array('modrec'));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return ControlRecibos the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=ControlRecibos::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param ControlRecibos $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='control-recibos-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function ActionCargarRecibosVerif(){

		$opc = $_POST['opc'];
		$ruta = $_POST['ruta'];
		$fecha_cheq = $_POST['fecha_cheq'];

		$data = array();

		if($opc == '0'){
	        $criteria = "SELECT TOP 300 * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 1";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}
		if($opc == '1'){
	        $criteria = "SELECT * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 1 AND Recibo LIKE '%".$ruta."%'";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}
		if($opc == '2'){
	        $criteria = "SELECT * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 1 AND Fecha_Cheque = '".$fecha_cheq."'";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}
		if($opc == '3'){
	        $criteria = "SELECT * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 1 AND Recibo LIKE '%".$ruta."%' AND Fecha_Cheque = '".$fecha_cheq."'";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}

        //si hay recibos pendientes por verificación
		if(!empty($modelo_recibos)) {

		  $cadena = '<table class="table table-sm table-hover" style="font-size:11px !important;"><thead><tr><th>Recibo / Visualizar</th><th>Fecha y hora de carga</th><th><i class="fas fa-save" aria-hidden="true"></i></th><th>Fecha canje</th><th>Fecha banco</th><th>Apr.</th><th>Rec. banco</th><th>Rec. valor</th><th>Otro rec.</th><th>Obs. rechazo</th><th><i class="fas fa-save" aria-hidden="true"></th><th>Observaciones</th></tr></thead><tbody>';
		  $i = 1;

		  $cont = 0;

		  $data['opc_che'] = 0;
		  $data['opc_obs'] = 0;

			foreach ($modelo_recibos as $recibos) {
				$cadena .= '
				<tr>
				<td><input type="button" class="btn btn-primary btn-sm" style="padding: 0px 10px 0px 10px !important;font-size:11px !important;" onClick="viewrecibo('.$recibos->Id_Control.');" value="'.$recibos->Recibo.'" ></td>
				<td>'.UtilidadesVarias::textofechahora($recibos->Fecha_Hora_Carga).'</td>
				';
				
				if($recibos->Fecha_Cheque == ""){
					$data['opc_che'] = 1;
					$cadena .= '
					<td><input type="checkbox" class="cheq" id="'.$recibos->Id_Control.'" onChange="hidemsg();"/></td>
					<td><input type="date" value="'.date('Y-m-d').'" id="date_che_'.$recibos->Id_Control.'"></td>
					';

				}else{
					$cadena .= '
					<td></td>
					<td>'.$recibos->Fecha_Cheque.'</td>
					';
				}

				$label = '<label id="l_'.$recibos->Id_Control.'">N/A</label>';

				$array_bancos_correctos = Yii::app()->params->bancos_recibos;

				$select_ban = '<select onchange="resetmsn()" id="b_'.$recibos->Id_Control.'" style="display:none;">
					<option value="">SELECCIONE UN BANCO</option>';
				
				foreach($array_bancos_correctos as $key => $value){
					$select_ban .= '<option value="'.$key.'">'.$value.'</option>';
				}
					
				$select_ban .= '</select>';

				$array_motivos_rechazo = Yii::app()->params->motivos_rechazo_recibos;

				$select_mot = '<select onchange="resetmsn()" id="m_'.$recibos->Id_Control.'" style="display:none;">
					<option value="">SELECCIONE UN MOTIVO</option>';
				
				foreach($array_motivos_rechazo as $key => $value){
					$select_mot .= '<option value="'.$key.'">'.$value.'</option>';
				}
					
				$select_mot .= '</select>';


				$cadena .= '
				<td><input type="date" value="'.date('Y-m-d').'" id="date_'.$recibos->Id_Control.'"></td>
				<td><input type="checkbox" class="grupo" name="grupo'.$i.'[]" value="1" id="'.$recibos->Id_Control.'" onChange="uncheckgroup(this);"/></td>
				
				<td><input type="checkbox" class="grupo" name="grupo'.$i.'[]" value="2" id="'.$recibos->Id_Control.'" onChange="uncheckgroup(this);" return false;"/></td>
				<td><input type="checkbox" class="grupo" name="grupo'.$i.'[]" value="3" id="'.$recibos->Id_Control.'" onChange="uncheckgroup(this);"/></td>
				<td><input type="checkbox" class="grupo" name="grupo'.$i.'[]" value="4" id="'.$recibos->Id_Control.'" onChange="uncheckgroup(this);"/></td>
				<td>'.$label.''.$select_ban.''.$select_mot.'</td>';

				
				if($recibos->Observaciones == ""){
					$data['opc_obs'] = 1;
					$cadena .= '
					<td><input type="checkbox" class="obs" id="'.$recibos->Id_Control.'" onChange="hidemsg();"/></td>
					<td><textarea class="form-control form-control-sm" rows="2" cols="50" maxlength="100" onkeyup="convert_may(this)" id="obs_'.$recibos->Id_Control.'"></textarea></td>
					';

				}else{
					$cadena .= '
					<td></td>
					<td>'.$recibos->Observaciones.' / '.$recibos->Fecha_Hora_Obs.'</td>
					';
				}

				$cadena .= '</tr>';
				$i++;
				$cont++;
			}

		  	$cadena .= '<tr><td colspan="12" align="right"><strong>'.$cont.' Recibo(s)</strong></td></tr></tbody></table>';
		  	$data['opc'] = 1;

		}else{
		  $cadena = '<br>No se encontraron recibos pendientes por verificar.<br><br>';

		  $data['opc'] = 0;
		}

    	$data['info'] = $cadena;

    	echo json_encode($data);

	}

	public function ActionCargarRecibosAplic(){

		$filtro = $_POST['filtro'];

		$data = array();

		if($filtro == '0'){
	        $criteria = "SELECT TOP 300 * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 2 AND Verificacion = 1";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}else{
	        $criteria = "SELECT * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 2 AND Verificacion = 1 AND Recibo LIKE '%".$filtro."%'";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}

        //si hay recibos pendientes por verificación
		if(!empty($modelo_recibos)) {

		  	 $cadena = '<table class="table table-sm table-hover"><thead><tr><th>Recibo / Visualizar</th><th>Verificado por</th><th>Fecha y hora de verificación</th><th>Fecha banco</th><th><input type="checkbox" onchange="check_uncheck_all(event)";/></th></tr></thead><tbody>';
  			$i = 1;

  			$cont = 0;   

		  	foreach ($modelo_recibos as $recibos) {

		  		if ($recibos->Fecha_Banco == "") { 
		  			$fb = "N/A"; 
		  		}else{ 
		  			$fb = UtilidadesVarias::textofecha($recibos->Fecha_Banco);
		  		}

			    $cadena .= '
			    <tr>
			    <td><input type="button" class="btn btn-primary btn-sm" style="padding: 0px 10px 0px 10px !important" onClick="viewrecibo('.$recibos->Id_Control.');" value="'.$recibos->Recibo.'" ></td>
			    <td>'.$recibos->idusuarioverif->Usuario.'</td>
			    <td>'.UtilidadesVarias::textofechahora($recibos->Fecha_Hora_Verif).'</td>
			    <td>'.$fb.'</td>
			    <td><input type="checkbox" class="checks" value="'.$recibos->Id_Control.'"/></td>
			    </tr>';
			    $i++;
			    $cont++;
		  	}

		  	$cadena .= '<tr><td colspan="5" align="right"><strong>'.$cont.' Recibo(s)</strong></td></tr></tbody></table>';
		  	$data['opc'] = 1;

		}else{
		  $cadena = '<br>No se encontraron recibos pendientes por aplicar.<br><br>';

		  $data['opc'] = 0;
		}

    	$data['info'] = $cadena;

    	echo json_encode($data);

	}

	public function ActionCargarRecibosEntFis(){

		$filtro = $_POST['filtro'];

		$data = array();

		if($filtro == '0'){
	        $criteria = "SELECT TOP 1000 * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 3 ORDER BY Fecha_Hora_Aplic";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}else{
	        $criteria = "SELECT * FROM T_PR_CONTROL_RECIBOS WHERE Opc = 3 AND Recibo LIKE '%".$filtro."%'";
	        $modelo_recibos=ControlRecibos::model()->findAllBySQL($criteria);
		}

        //si hay recibos pendientes por verificación
		if(!empty($modelo_recibos)) {

		  	$cadena = '<table class="table table-sm table-hover"><thead><tr><th>Recibo / Visualizar</th><th>Aplicado por</th><th>Fecha y hora de aplicación</th><th><input type="checkbox"  onchange="check_uncheck_all(event)";/></th></tr></thead><tbody>';
			$i = 1; 

			$cont = 0; 

		  	foreach ($modelo_recibos as $recibos) {
				$cadena .= '
				<tr>
				<td><input type="button" class="btn btn-primary btn-sm" style="padding: 0px 10px 0px 10px !important" onClick="viewrecibo('.$recibos->Id_Control.');" value="'.$recibos->Recibo.'" ></td>
				<td>'.$recibos->idusuarioaplic->Usuario.'</td>
				<td>'.UtilidadesVarias::textofechahora($recibos->Fecha_Hora_Aplic).'</td>
				<td><input type="checkbox" class="checks" value="'.$recibos->Id_Control.'"/></td>
				</tr>';
				$i++;
				$cont++;
			}

		  	$cadena .= '<tr><td colspan="4" align="right"><strong>'.$cont.' Recibo(s)</strong></td></tr></tbody></table>';
		  	$data['opc'] = 1;

		}else{
		  $cadena = '<br>No se encontraron recibos pendientes por verificar.<br><br>';

		  $data['opc'] = 0;
		}

    	$data['info'] = $cadena;

    	echo json_encode($data);

	}

	public function actionViewRecibo($id)
	{		
		
		$modelo_recibo = ControlRecibos::model()->findByPk($id);

		if(!empty($modelo_recibo)){
			clearstatcache();
			$rn = rand(1,1000);
			echo '<h3>'.$modelo_recibo->Recibo.'</h3>';
			echo '<img src="'.Yii::app()->baseUrl."/files/control_recibos/recibos/".$modelo_recibo->Url."?".$rn.'" class="img-responsive center-block" onclick="rotateimage('.$id.');" style="cursor:pointer">';

		}

	}

	public function actionRotateImage()
	{		
		$id = $_POST['id'];
		$modelo_recibo = ControlRecibos::model()->findByPk($id);

		if(!empty($modelo_recibo)){
			$filename = Yii::app()->basePath.'/../files/control_recibos/recibos/'.$modelo_recibo->Url;

			// Load the image
			$source = imagecreatefromjpeg($filename);
			// Rotate
			$rotate = imagerotate($source, -90, 0);

			//and save it on your server...
			imagejpeg($rotate, Yii::app()->basePath.'/../files/control_recibos/recibos/'.$modelo_recibo->Url);
			imagedestroy($source);
			imagedestroy($rotate);
			echo $id;

		}

	}

	public function actionExport(){
    	
    	$model=new ControlRecibos('search');
	    $model->unsetAttributes();  // clear any default values
	    
	    if(isset($_GET['ControlRecibos'])) {
	        $model->attributes=$_GET['ControlRecibos'];
	    }

    	$dp = $model->search();
		$dp->setPagination(false);
 
		$data = $dp->getData();

		Yii::app()->user->setState('control-recibos-export',$data);
	}

	public function actionExportExcel()
	{
		$data = Yii::app()->user->getState('control-recibos-export');
		$this->renderPartial('control_recibos_export_excel',array('data' => $data));	
	}
}

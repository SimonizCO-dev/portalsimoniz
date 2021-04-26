<?php

class UtilidadesVarias {
   
	public static function textofechahora($datetime) {

		$fecha = date_create($datetime);

		$diatxt = date_format($fecha, 'l');
		$dianro = date_format($fecha, 'd');
		$mestxt = date_format($fecha, 'F');
		$anionro = date_format($fecha, 'Y');

		$hora = date_format($fecha, 'g');
		$min = date_format($fecha, 'i');
		$jorn = date_format($fecha, 'A');
		
		// *********** traducciones y modificaciones de fechas a letras y a español ***********
		$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
		$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
		$diaesp=str_replace($ding, $desp, $diatxt);
		$mesesp=str_replace($ming, $mesp, $mestxt);

		return $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro.' - '.$hora.':'.$min.' '.$jorn;	

	}

	public static function textofecha($date) {

		$fecha = date_create($date);

		$diatxt = date_format($fecha, 'l');
		$dianro = date_format($fecha, 'd');
		$mestxt = date_format($fecha, 'F');
		$anionro = date_format($fecha, 'Y');
		
		// *********** traducciones y modificaciones de fechas a letras y a español ***********
		$ding=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$ming=array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$mesp=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$desp=array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
		$mesn=array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
		$diaesp=str_replace($ding, $desp, $diatxt);
		$mesesp=str_replace($ming, $mesp, $mestxt);

		return $diaesp.", ".$dianro." de ".$mesesp." de ".$anionro;	
	}

	public static function textoestado1($opc) {

		if($opc == 0){
			return 'INACTIVO';
		}

		if($opc == 1){
			return 'ACTIVO';	
		}
	}


	public static function textoestado2($opc) {

		if($opc == 0){
			return 'NO';
		}

		if($opc == 1){
			return 'SI';	
		}
	}

	public static function textoavatar($opc) {

		if($opc == 1){
			return 'FEMENINO';
		}

		if($opc == 2){
			return 'MASCULINO';	
		}
	}


	public static function listaareasusuario() {

		$array_areas_usuario = Yii::app()->user->getState('array_areas');
		$lista_areas = array();

		if(!empty($array_areas_usuario)){

			$areas_usuario = implode(",", $array_areas_usuario);
			$areas = Yii::app()->db->createCommand("SELECT Id_Area, Area FROM T_PR_AREA WHERE Estado = 1 AND Id_Area IN (".$areas_usuario.") ORDER BY Area")->queryAll();
			foreach ($areas as $ar) {
				$lista_areas[$ar['Id_Area']] = $ar['Area'];
			}

		}

		return $lista_areas;
	}

	public static function estadoexiststock($item, $cantidad) {
		
		$cant_min_stock_item = IItem::model()->findByPk($item)->Min_Stock;

		if($cantidad >= $cant_min_stock_item){
			return "";
		}else{
			return "bg-danger";
		}
		
	}

	public static function digitocontrolean13($digits){
  	
  		$digits =(string)$digits;
		
		$even_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};

		$even_sum_three = $even_sum * 3;

		$odd_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};

		$total_sum = $even_sum_three + $odd_sum;
		
		$next_ten = (ceil($total_sum/10))*10;
		$check_digit = $next_ten - $total_sum;
		return $check_digit;

	}

	public static function digitocontrolean14($digits){
  	
  		$digits =(string)$digits;

  		//print_r($digits);die;
		
		$even_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10} + $digits{12};
		
		$even_sum_three = $even_sum * 3;
		
		$odd_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
		
		$total_sum = $even_sum_three + $odd_sum;
		
		$next_ten = (ceil($total_sum/10))*10;
		$check_digit = $next_ten - $total_sum;
		return $check_digit;

	}

	public static function listaplanescliente() {

		$planes_cliente = Yii::app()->db->createCommand("SELECT DISTINCT Id_Plan, Plan_Descripcion FROM T_CF_CRITERIOS_CLIENTES ORDER BY Id_Plan")->queryAll();

		$lista_planes = array();
		foreach ($planes_cliente as $pc) {
			$lista_planes[trim($pc['Id_Plan'])] = trim($pc['Plan_Descripcion']);
		}

		return $lista_planes;
	}

	public static function descplancliente($id_plan) {

		$plan = Yii::app()->db->createCommand("SELECT Plan_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = ".$id_plan)->queryRow();
		return $plan['Plan_Descripcion'];
	}

	public static function listaplanesitem() {

		$planes_item = Yii::app()->db->createCommand("SELECT DISTINCT Id_Plan, Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS ORDER BY Id_Plan")->queryAll();

		$lista_planes = array();
		foreach ($planes_item as $pc) {
			$lista_planes[trim($pc['Id_Plan'])] = trim($pc['Plan_Descripcion']);
		}

		return $lista_planes;
	}

	public static function descplanitem($id_plan) {

		$plan = Yii::app()->db->createCommand("SELECT Plan_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = ".$id_plan)->queryRow();
		return $plan['Plan_Descripcion'];
	}

	public static function desccricliente($id_plan, $criterios) {

		$array_criterios = explode(",", $criterios);

		$texto_criterios = "";

		foreach ($array_criterios as $key => $value) {
			$q_criterio = Yii::app()->db->createCommand("SELECT Criterio_Descripcion FROM T_CF_CRITERIOS_CLIENTES WHERE Id_Plan = ".$id_plan." AND Id_Criterio = '".$value."'")->queryRow();
			$texto_criterios .= $q_criterio['Criterio_Descripcion'].", ";
		}

		$texto_criterios = substr ($texto_criterios, 0, -2);

		return $texto_criterios;
	}

	public static function desccriitem($id_plan, $criterios) {

		$array_criterios = explode(",", $criterios);

		$texto_criterios = "";

		foreach ($array_criterios as $key => $value) {
			$q_criterio = Yii::app()->db->createCommand("SELECT Criterio_Descripcion FROM T_CF_CRITERIOS_ITEMS WHERE Id_Plan = ".$id_plan." AND Id_Criterio = '".$value."'")->queryRow();
			$texto_criterios .= $q_criterio['Criterio_Descripcion'].", ";
		}

		$texto_criterios = substr ($texto_criterios, 0, -2);

		return $texto_criterios;
	}

	public function DescItem($Id_Item){

        $desc= Yii::app()->db->createCommand("
        	SELECT
            CONCAT(f120_id,' - ',f120_descripcion) AS DESCR
            FROM UnoEE1..t120_mc_items
            INNER JOIN UnoEE1..t121_mc_items_extensiones ON f120_rowid = f121_rowid_item
            WHERE f120_id_cia = 2 AND f120_id = '".$Id_Item."'"
        )->queryRow();

		return $desc['DESCR'];

    }

    public static function adminareadocumento($id_documento, $array) {
		$array_areas_selec = array();
	
		foreach ($array as $clave => $valor) {
		    
		    //se busca el registro para saber si tiene que ser creado 
		    $criteria=new CDbCriteria;
			$criteria->condition='Id_Documento=:Id_Documento AND Id_Area=:Id_Area';
			$criteria->params=array(':Id_Documento'=>$id_documento,':Id_Area'=>$valor);
			$modelo_area_documento=GdAreaDocumento::model()->find($criteria);

			if(!is_null($modelo_area_documento)){
				//si el estado es inactivo se cambia a activo, si ya esta activo no se realiza ninguna acción
				if($modelo_area_documento->Estado == 0){
					$modelo_area_documento->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$modelo_area_documento->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$modelo_area_documento->Estado = 1;
					if($modelo_area_documento->save()){
						array_push($array_areas_selec, intval($valor));
					}	
				}else{
					array_push($array_areas_selec, intval($valor));	
				}
			}else{
				//se debe insertar un nuevo registro en la tabla
				$nueva_area_documento = new GdAreaDocumento;
			    $nueva_area_documento->Id_Documento = $id_documento;
			    $nueva_area_documento->Id_Area = $valor;
				$nueva_area_documento->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
				$nueva_area_documento->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
				$nueva_area_documento->Fecha_Creacion = date('Y-m-d H:i:s');
				$nueva_area_documento->Fecha_Actualizacion = date('Y-m-d H:i:s');
				$nueva_area_documento->Estado = 1;
				if($nueva_area_documento->save()){
					array_push($array_areas_selec, intval($valor));
				}
			}
		}

		//se inactivan las areas que no vienen en el array
		$areas_excluidas = implode(",",$array_areas_selec);
		$ae = str_replace("'", "", $areas_excluidas);

		$criteria=new CDbCriteria;
		$criteria->condition='Id_Documento=:Id_Documento AND Id_Area NOT IN ('.$ae.')';
		$criteria->params=array(':Id_Documento'=>$id_documento);
		$modelo_area_documento_inactivar=GdAreaDocumento::model()->findAll($criteria);
		if(!is_null($modelo_area_documento_inactivar)){
			foreach ($modelo_area_documento_inactivar as $areas_inactivar) {
				//si el estado es activo se cambia a inactivo, si ya esta inactivo no se realiza ninguna acción
				if($areas_inactivar->Estado == 1){
					$areas_inactivar->Id_Usuario_Actualizacion = Yii::app()->user->getState('id_user');
					$areas_inactivar->Fecha_Actualizacion = date('Y-m-d H:i:s');
					$areas_inactivar->Estado = 0;
					$areas_inactivar->save();
				}	
			}
		}
	}

	public static function areasactivasdocumento($id_documento) {
		//opciones activas en el combo áreas
		$criteria=new CDbCriteria;
		$criteria->condition='Id_Documento=:Id_Documento AND Estado=:Estado';
		$criteria->params=array(':Id_Documento'=>$id_documento,':Estado'=> 1);
		$array_ar_activas = array();
		$areas_activas=GdAreaDocumento::model()->findAll($criteria);
		foreach ($areas_activas as $area_act) {
			array_push($array_ar_activas, $area_act->Id_Area);
		}

		return json_encode($array_ar_activas);
	}

	public static function descclasif($clasif){

        switch ($clasif) {
			case 1:
			    return 'EXTERNO'; 
			case 2:
			    return 'INTERNO';  
			case 3:
			    return 'TRANSVERSAL';  
		}
    }

    public static function textoaccion($opc) {

		if($opc == 1){
			return 'CONSULTA';
		}

		if($opc == 2){
			return 'DESCARGA';	
		}
	}

	public static function estadofechavencdominioweb($opc, $id) {
		//recibe parametro de tabla, pk de la tabla a consultar
		
		if($opc == 1){

			$modelodominio = DominioWeb::model()->findByPk($id);

			if($modelodominio->Estado == 0){

				return "bg-default";

			}else{

				$str = strtotime(date('Y-m-d')) - (strtotime($modelodominio->Fecha_Vencimiento));
				$diff = floor($str/3600/24);

				if ($diff > -45){
					return "bg-danger";
				}else{
					return "bg-success";

				}

			}

		}
		
	}

	public static function estadofechavencimiento($id) {

		$modelocon = Cont::model()->findByPk($id);

		if($modelocon->Estado == 0){

			return "bg-default";

		}else{

			$str = strtotime($modelocon->Fecha_Final) - strtotime(date('Y-m-d'));
			$diff = floor($str/3600/24);

			if ($diff < $modelocon->Dias_Alerta){
				return "bg-danger";
			}

			if ($diff >= $modelocon->Dias_Alerta){
				return "bg-success";
			}

		}		
	}

	public static function novedaditem($id, $id_item_act, $id_item_nue, $item_act, $item_nue, $descripcion_act, $descripcion_nue, $cant_act, $cant_nue, $vlr_unit_act, $vlr_unit_nue, $moneda_act, $moneda_nue, $iva_act, $iva_nue, $estado_act, $estado_nue){

		$texto_novedad = "";
		$flag = 0;

		if($id_item_act != $id_item_nue){
			$flag = 1;

			$texto_novedad .= "ID de item: ".$id_item_act." / ".$id_item_nue.", ";
		}

		if($item_act != $item_nue){
			$flag = 1;

			$texto_novedad .= "Item: ".$item_act." / ".$item_nue.", ";
		}

		if($descripcion_act != $descripcion_nue){
			$flag = 1;

			$texto_novedad .= "Descripción: ".$descripcion_act." / ".$descripcion_nue.", ";
		}

		if($cant_act != $cant_nue){
			$flag = 1;

			$texto_novedad .= "Cant.: ".$cant_act." / ".$cant_nue.", ";
		}

		if($vlr_unit_act != $vlr_unit_nue){
			$flag = 1;

			$texto_novedad .= "Vlr. unit.: ".number_format($vlr_unit_act, 0)." / ".number_format($vlr_unit_nue, 0).", ";
		}

		if($moneda_act != $moneda_nue){
			$flag = 1;	

			$n_moneda_act = Dominio::model()->findByPk($moneda_act);
			$n_moneda_nue = Dominio::model()->findByPk($moneda_nue);

			$texto_novedad .= "Moneda: ".$n_moneda_act->Dominio." / ".$n_moneda_nue->Dominio.", ";
		}

		if($iva_act != $iva_nue){
			$flag = 1;

			$texto_novedad .= "Iva: ".$iva_act." / ".$iva_nue.", ";
		}

		if($estado_act != $estado_nue){
			$flag = 1;

			$texto_novedad .= "Estado: ".UtilidadesVarias::textoestado1($estado_act)." / ".UtilidadesVarias::textoestado1($estado_nue).", ";
		}

		//alguno de los criterios cambio
		if($flag == 1){
			$texto_novedad = substr ($texto_novedad, 0, -2);
			$nueva_novedad = new HistItemCont;
			$nueva_novedad->Id_Item = $id;
			$nueva_novedad->Novedad = $texto_novedad;
			$nueva_novedad->Id_Usuario_Creacion = Yii::app()->user->getState('id_user');
			$nueva_novedad->Fecha_Creacion = date('Y-m-d H:i:s');
			$nueva_novedad->save();
		}
	}

	public static function descequipo($equipo) {
		$modeloequipo = Equipo::model()->findByPk($equipo);
		return $modeloequipo->tipoequipo->Dominio.' / '.$modeloequipo->Serial;
	}

	 public static function listapaises(){
		return array(1 => 'COLOMBIA', 2 => 'ECUADOR', 3 => 'PERÚ', 4 => 'CHILE');
	}

	public static function descpaises($paises){

		$array_paises = explode(",", $paises);

		$texto_pais = "";

		foreach ($array_paises as $key => $value) {
			
			switch ($value) {
			    case 1:
			        $pais = 'COLOMBIA';
			        break;
			    case 2:
			        $pais = 'ECUADOR';
			        break;
			    case 3:
			        $pais = 'PERÚ';
			        break;
			    case 4:
			        $pais = 'CHILE';
			        break;
			}

			$texto_pais .= $pais.", ";
		}

		$texto = substr ($texto_pais, 0, -2);
		return $texto;

	}

	public static function usuariosfichaitem($step) {
		switch ($step) {
		   	case 1:
		   		$users = FichaItemUsuario::model()->findByPk(1)->Id_Users_Reg;
		        break;
		    case 2:
		   		$users = FichaItemUsuario::model()->findByPk(2)->Id_Users_Reg;
		        break;
		    case 3:
		        $users = FichaItemUsuario::model()->findByPk(3)->Id_Users_Reg;
		        break;
		    case 4:
		        $users = FichaItemUsuario::model()->findByPk(4)->Id_Users_Reg;
		        break;
		   	case 5:
		        $users = FichaItemUsuario::model()->findByPk(5)->Id_Users_Reg;
		        break; 
		}

		return explode(",", $users);
	
	}

	public static function usuariossolprom($step) {
		switch ($step) {
		   	case 1:
		   		$users = SolPromUsuario::model()->findByPk(1)->Id_Users_Reg;
		        break;
		    case 2:
		   		$users = SolPromUsuario::model()->findByPk(2)->Id_Users_Reg;
		        break;
		    case 3:
		        $users = SolPromUsuario::model()->findByPk(3)->Id_Users_Reg;
		        break;
		    case 4:
		        $users = SolPromUsuario::model()->findByPk(4)->Id_Users_Reg;
		        break;
		   	case 5:
		        $users = SolPromUsuario::model()->findByPk(5)->Id_Users_Reg;
		        break; 
		}

		return explode(",", $users);
	
	}

	public static function log($accion) {
		//LOG
		$log = New Log;
        $log->Tipo = 3;
        $log->Accion = $accion;
        $log->Id_Usuario = Yii::app()->user->getState('id_user');
        $log->Fecha_Hora = date('Y-m-d H:i:s');
        $log->save();
	}
}
